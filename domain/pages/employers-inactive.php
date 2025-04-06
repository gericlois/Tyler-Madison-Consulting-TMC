<!DOCTYPE html>
<html lang="en">
<?php
session_start();
if (!isset($_SESSION["admin_id"])) {
  header("Location: login.php");
} else {
    include "includes/head.php";
    include "../../pages/includes/connection.php";
}?>

<body>

    <!-- ======= Header ======= -->
    <?php include "includes/header.php" ?>

    <!-- ======= Sidebar ======= -->
    <?php include "includes/sidebar.php" ?>

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Inactive Employer
                <a href="employers-add.php" class="btn btn-primary rounded-pill">
                    <i class="bi bi-plus-circle me-1"></i> Add Employer
                </a>
            </h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                    <li class="breadcrumb-item active">Inactive Employer</li>
                </ol>
            </nav>
            <?php
                            if (isset($_GET['success'])) {
                                if ($_GET["success"] == "EmployerAdded") {
                                    echo '
                                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                                            <b>A new employer has been added! Review the details of the posted employer.</b>
                                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                        </div>';
                                }
                                if ($_GET["success"] == "employerUpdated") {
                                    echo '
                                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                                            <b>The employer has been successfully updated!</b> Review the updated details to ensure accuracy.
                                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                        </div>';
                                }
                                if ($_GET["success"] == "StatusUpdated") {
                                    echo '
                                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                                            <b>The employer has been successfully updated!</b> Review the updated details to ensure accuracy.
                                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                        </div>';
                                }
                            }
                            ?>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Inactive Employer</h5>
                            <p>Manage and view all employer in a structured table format. This section allows you to
                                track employer listings, including titles, descriptions, locations, salaries, and 
                                dates. </p>

                            <!-- Table with stripped rows -->
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>Employer ID</th>
                                        <th>Name</th>
                                        <th>Location</th>
                                        <th>Status</th>
                                        <th>Date Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT * FROM `employers`
                                            WHERE status = 2
                                            ORDER BY employer_id DESC";

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
                                                $status_class = "bg-dark";
                                            }
                                        
                

                                            echo "<tr>
                                                <td>{$row['employer_id']}</td>
                                                <td><a href='employers-profile.php?id={$row['employer_id']}' class='text-primary fw-bold'>{$row['name']}</a></td>
                                                <td>{$row['location']}</td>
                                                <td><span class='badge $status_class'>{$row['status']}</span></td>
                                                <td>{$row['created_at']}</td>
                                                <td>";

                                            if ($row['status'] == "Active") {
                                                echo " <a href='scripts/employer-update.php?id={$row['employer_id']}&status=2' class='btn btn-sm btn-dark' onclick='return confirm(\"Are you sure you want to make the employer  Inactive?\")'>Inactive</a>";
                                            } else if ($row['status'] == "Inactive") {
                                                echo " <a href='scripts/employer-update.php?id={$row['employer_id']}&status=1' class='btn btn-sm btn-primary' onclick='return confirm(\"Are you sure you want to make the employer  Active?\")'>Active</a>";
                                            }

                                            echo "</td></tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='10' class='text-center'>No Inactive Employer Found</td></tr>";
                                    }
                                    $conn->close();
                                    ?>
                                </tbody>
                            </table>

                            <!-- End Table with stripped rows -->

                        </div>
                    </div>

                </div>
            </div>
        </section>

    </main><!-- End #main -->

    <!-- ======= Footer ======= -->
    <?php include "includes/footer.php" ?>

    <!-- Vendor JS Files -->
    <?php include "includes/scripts.php" ?>

</body>

</html>