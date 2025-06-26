<!DOCTYPE html>
<html lang="en">
<?php
session_start();

// Check if user is logged in and is either admin or employer
if (!isset($_SESSION["admin_id"]) || !in_array($_SESSION["role"], ["admin", "superadmin", "employer"])) {
    header("Location: login.php");
    exit();
}

// Include common files
include "includes/head.php";
include "../../pages/includes/connection.php";


// Validate employer ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<p style='color:red;'>Invalid employer account.</p>";
    exit();
}

$employer_id = 1;
// Fetch employer details

$sql = "SELECT * FROM employers WHERE employer_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $employer_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<p style='color:red;'>Employer not found.</p>";
    exit();
}

$employer = $result->fetch_assoc(); // Now, $employer is properly set
$stmt->close();
?>

<body>

    <!-- Header -->
    <?php include "includes/header.php" ?>

    <!-- Sidebar -->
    <?php include "includes/sidebar.php" ?>

    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Employer Profile</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item">Employers</li>
                    <li class="breadcrumb-item active">Profile</li>
                </ol>
            </nav>
        </div>

        <section class="section profile">
            <div class="row">
                <div class="col-xl-4">
                    <div class="card">
                        <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                            <h2><?php echo htmlspecialchars($employer['name']); ?> <a
                                    href='employers-edit.php?id=<?php echo htmlspecialchars($employer['employer_id']); ?>'
                                    class='btn btn-sm btn-success'>Edit</a></h2>
                            <span><?php echo nl2br(htmlspecialchars($employer['description'])); ?></span>
                            <span><strong>Location:</strong><?php echo nl2br(htmlspecialchars($employer['location'])); ?></span>
                            <span><strong>Location:</strong><?php echo $employer_id ?></span>
                            <div class="social-links mt-2">
                                <a href="<?php echo htmlspecialchars($employer['link_facebook']); ?>"
                                    class="facebook"><i class="bi bi-facebook"></i></a>
                                <a href="<?php echo htmlspecialchars($employer['link_instagram']); ?>"
                                    class="instagram"><i class="bi bi-instagram"></i></a>
                                <a href="<?php echo htmlspecialchars($employer['link_linkedin']); ?>"
                                    class="linkedin"><i class="bi bi-linkedin"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-8">
                    <div class="card">
                        <div class="card-body pt-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <ul class="nav nav-tabs nav-tabs-bordered">
                                    <li class="nav-item">
                                        <button class="nav-link active" data-bs-toggle="tab"
                                            data-bs-target="#profile-overview">Files</button>
                                    </li>
                                </ul>
                                <button class="btn btn-success btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#uploadFileModal">
                                    <i class="bi bi-upload"></i> Add File
                                </button>
                            </div>

                            <div class="tab-content pt-2">
                                <div class="tab-pane fade show active profile-overview" id="profile-overview">
                                    <table class="table datatable">
                                        <thead>
                                            <tr>
                                                <th>File ID</th>
                                                <th>Name</th>
                                                <th>Type</th>
                                                <th>Uploaded By</th>
                                                <th>Created At</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $file_sql = "
                                                SELECT f.*, u.username 
                                                FROM files f
                                                LEFT JOIN users u ON f.uploaded_by = u.user_id
                                                WHERE f.user_id = ?
                                                ORDER BY f.created_at DESC
                                            ";
                                            $stmt = $conn->prepare($file_sql);
                                            $stmt->bind_param("i", $employer['user_id']);
                                            $stmt->execute();
                                            $files_result = $stmt->get_result();
                                            $allowed_extensions = ['pdf', 'doc', 'docx', 'txt'];

                                            if ($files_result->num_rows > 0) {
                                                while ($file = $files_result->fetch_assoc()) {
                                                    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

                                                    if (!in_array($file_extension, $allowed_extensions)) {
                                                        continue; // Skip non-document files
                                                    }

                                                    echo "<tr>";
                                                    echo "<td>" . htmlspecialchars($file['file_id']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($file['name']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($file['type']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($file['username']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($file['created_at']) . "</td>";
                                                    echo "<td>";

                                                    if ($file_extension === 'pdf') {
                                                        // Button to open PDF modal
                                                        echo '<button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#pdfViewerModal" data-pdf="uploads/' . htmlspecialchars($file['name']) . '">View</button> ';
                                                    } else {
                                                        // Link to open document in new tab
                                                        echo '<a href="uploads/' . htmlspecialchars($file['name']) . '" class="btn btn-sm btn-primary" target="_blank">Open</a> ';
                                                    }

                                                    // Delete button
                                                    echo '<a href="scripts/file-delete.php?id=' . htmlspecialchars($file['file_id']) . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure you want to delete this file?\')">Delete</a>';

                                                    echo "</td>";
                                                    echo "</tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='6' class='text-center'>No files found</td></tr>";
                                            }
                                            $stmt->close();
                                            ?>
                                        </tbody>

                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- PDF Viewer Modal -->
                <div class="modal fade" id="pdfViewerModal" tabindex="-1" aria-labelledby="pdfViewerLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-xl modal-dialog-centered" style="max-width:90vw;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="pdfViewerLabel">PDF Viewer</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-0" style="height:80vh;">
                                <iframe id="pdfFrame" src="" frameborder="0" style="width:100%; height:100%;"></iframe>
                            </div>
                        </div>
                    </div>
                </div>



                <!-- Upload File Modal -->
                <div class="modal fade" id="uploadFileModal" tabindex="-1" aria-labelledby="uploadFileModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <form action="scripts/file-upload.php" method="post" enctype="multipart/form-data"
                            class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="uploadFileModalLabel">Upload File</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="fileName" class="form-label">File Name</label>
                                    <input type="text" class="form-control" id="fileName" name="name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="fileType" class="form-label">File Type</label>
                                    <input type="text" class="form-control" id="fileType" name="type" required>
                                </div>
                                <div class="mb-3">
                                    <label for="fileUpload" class="form-label">Choose File</label>
                                    <input type="file" class="form-control" id="fileUpload" name="file" required>
                                </div>
                                <input type="hidden" name="user_id" value="<?php echo $employer['user_id']; ?>">
                                <input type="hidden" name="uploaded_by" value="<?php echo $_SESSION['admin_id']; ?>">
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Upload</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>


                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body pt-3">
                            <ul class="nav nav-tabs nav-tabs-bordered">
                                <li class="nav-item">
                                    <button class="nav-link active" data-bs-toggle="tab"
                                        data-bs-target="#profile-overview">Jobs</button>
                                </li>
                            </ul>
                            <div class="tab-content pt-2">
                                <div class="tab-pane fade show active profile-overview" id="profile-overview">
                                    <table class="table datatable">
                                        <thead>
                                            <tr>
                                                <th>Job ID</th>
                                                <th>Title</th>
                                                <th>Salary</th>
                                                <th data-type="date" data-format="YYYY/DD/MM">Start Date</th>
                                                <th data-type="date" data-format="YYYY/DD/MM">Deadline</th>
                                                <th>Status</th>
                                                <th>Applicants</th> <!-- Added Applicants Column -->
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sql = "
                                                SELECT 
                                                    jp.job_id, 
                                                    jp.title, 
                                                    jp.description, 
                                                    jp.location, 
                                                    jp.salary, 
                                                    jp.end_at, 
                                                    jp.status, 
                                                    u.username AS posted_by_name, 
                                                    jp.posted_at, 
                                                    e.name AS employer_name, 
                                                    e.employer_id,
                                                    (SELECT COUNT(*) FROM jobapplications ja WHERE ja.job_id = jp.job_id) AS applicant_count
                                                FROM jobpostings jp
                                                LEFT JOIN users u ON jp.posted_by = u.user_id 
                                                LEFT JOIN employers e ON jp.employer_id = e.employer_id
                                                WHERE jp.employer_id = " . intval($employer['employer_id']) . "
                                                ORDER BY jp.job_id DESC
                                            ";

                                            $result = $conn->query($sql);

                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    // Convert numeric status to text
                                                    if ($row['status'] == "1") {
                                                        $row['status'] = "Active";
                                                    } elseif ($row['status'] == "2") {
                                                        $row['status'] = "Inactive";
                                                    }

                                                    // Assign class based on status
                                                    $status_class = "bg-secondary";
                                                    if ($row['status'] == "Active") {
                                                        $status_class = "bg-primary";
                                                    } elseif ($row['status'] == "Inactive") {
                                                        $status_class = "bg-danger";
                                                    }

                                                    echo "<tr>
                                            <td>{$row['job_id']}</td>
                                            <td>{$row['title']}</td>
                                            <td>$" . number_format($row['salary'], 2) . "</td>
                                            <td>{$row['posted_at']}</td>
                                            <td>{$row['end_at']}</td>
                                            <td><span class='badge $status_class'>{$row['status']}</span></td>
                                            <td>{$row['applicant_count']}</td> <!-- Display applicant count -->
                                            <td>
                                                <a href='jobs-profile.php?id={$row['job_id']}' class='btn btn-sm btn-success'>View</a>
                                                <a href='jobs-edit.php?id={$row['job_id']}' class='btn btn-sm btn-success'>Edit</a>";

                                                    if ($row['status'] == "Active") {
                                                        echo " <a href='scripts/job-update.php?id={$row['job_id']}&status=2' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure you want to make the Job Posting Inactive?\")'>Inactive</a>";
                                                    } else if ($row['status'] == "Inactive") {
                                                        echo " <a href='scripts/job-update.php?id={$row['job_id']}&status=1' class='btn btn-sm btn-primary' onclick='return confirm(\"Are you sure you want to make the Job Posting Active?\")'>Active</a>";
                                                    }

                                                    echo "</td></tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='10' class='text-center'>No jobs found</td></tr>";
                                            }
                                            $conn->close();
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include "includes/footer.php" ?>
    <?php include "includes/scripts.php" ?>
</body>


<script>
var pdfViewerModal = document.getElementById('pdfViewerModal');
pdfViewerModal.addEventListener('show.bs.modal', function(event) {
    var button = event.relatedTarget;
    var pdfUrl = button.getAttribute('data-pdf');
    var iframe = pdfViewerModal.querySelector('#pdfFrame');
    iframe.src = pdfUrl;
});

pdfViewerModal.addEventListener('hidden.bs.modal', function() {
    var iframe = pdfViewerModal.querySelector('#pdfFrame');
    iframe.src = ''; // clear src on close to stop PDF loading
});
</script>

</html>