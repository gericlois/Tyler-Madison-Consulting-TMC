<aside id="sidebar" class="sidebar">

    <?php $current_page = basename($_SERVER['PHP_SELF']); ?>

    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">
            <a class="nav-link <?= ($current_page == 'index.php') ? '' : 'collapsed' ?>" href="index.php">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li><!-- End Dashboard Nav -->

        <li class="nav-item">
            <a class="nav-link <?= ($current_page == 'jobs.php' || $current_page == 'jobs-inactive.php' || $current_page == 'jobs-filled.php') ? '' : 'collapsed' ?>" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-pc-display-horizontal"></i><span>Jobs</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="components-nav" class="nav-content collapse <?= ($current_page == 'jobs.php' || $current_page == 'jobs-inactive.php' || $current_page == 'jobs-filled.php') ? 'show' : '' ?>" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="jobs.php" class="<?= ($current_page == 'jobs.php') ? 'active' : '' ?>">
                        <i class="bi bi-circle"></i><span>Jobs</span>
                    </a>
                </li>
                <li>
                    <a href="jobs-inactive.php" class="<?= ($current_page == 'jobs-inactive.php') ? 'active' : '' ?>">
                        <i class="bi bi-circle"></i><span>Inactive Jobs</span>
                    </a>
                </li>
                <li>
                    <a href="jobs-filled.php" class="<?= ($current_page == 'jobs-filled.php') ? 'active' : '' ?>">
                        <i class="bi bi-circle"></i><span>Filled Jobs</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Jobs Nav -->

        <li class="nav-item">
            <a class="nav-link <?= ($current_page == 'employees.php' || $current_page == 'employees-inactive.php' || $current_page == 'employees-blocked.php') ? '' : 'collapsed' ?>" data-bs-target="#employee-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-person-check-fill"></i><span>Employees</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="employee-nav" class="nav-content collapse <?= ($current_page == 'employees.php' || $current_page == 'employees-inactive.php' | $current_page == 'employees-blocked.php') ? 'show' : '' ?>" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="employees.php" class="<?= ($current_page == 'employees.php') ? 'active' : '' ?>">
                        <i class="bi bi-circle"></i><span>Employees</span>
                    </a>
                </li>
                <li>
                    <a href="employees-inactive.php" class="<?= ($current_page == 'employees-inactive.php') ? 'active' : '' ?>">
                        <i class="bi bi-circle"></i><span>Inactive Employees</span>
                    </a>
                </li>
                <li>
                    <a href="employees-blocked.php" class="<?= ($current_page == 'employees-blocked.php') ? 'active' : '' ?>">
                        <i class="bi bi-circle"></i><span>Blocked Employees</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Employees Nav -->

        <li class="nav-item">
            <a class="nav-link <?= ($current_page == 'employers.php' || $current_page == 'employers-inactive.php' || $current_page == 'employers-blocked.php') ? '' : 'collapsed' ?>" data-bs-target="#employer-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-person-badge-fill"></i><span>Employer</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="employer-nav" class="nav-content collapse <?= ($current_page == 'employers.php' || $current_page == 'employers-inactive.php' || $current_page == 'employers-blocked.php') ? 'show' : '' ?>" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="employers.php" class="<?= ($current_page == 'employers.php') ? 'active' : '' ?>">
                        <i class="bi bi-circle"></i><span>Employer</span>
                    </a>
                </li>
                <li>
                    <a href="employers-inactive.php" class="<?= ($current_page == 'employers-inactive.php') ? 'active' : '' ?>">
                        <i class="bi bi-circle"></i><span>Inactive Employer</span>
                    </a>
                </li>
                <li>
                    <a href="employers-blocked.php" class="<?= ($current_page == 'employers-blocked.php') ? 'active' : '' ?>">
                        <i class="bi bi-circle"></i><span>Blocked Employer</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Employer Nav -->

        <li class="nav-item">
            <a class="nav-link <?= ($current_page == 'users.php' || $current_page == 'users-inactive.php') ? '' : 'collapsed' ?>" data-bs-target="#user-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-person"></i><span>User</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="user-nav" class="nav-content collapse <?= ($current_page == 'users.php' || $current_page == 'users-inactive.php') ? 'show' : '' ?>" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="users.php" class="<?= ($current_page == 'users.php') ? 'active' : '' ?>">
                        <i class="bi bi-circle"></i><span>User</span>
                    </a>
                </li>
                <li>
                    <a href="users-inactive.php" class="<?= ($current_page == 'users-inactive.php') ? 'active' : '' ?>">
                        <i class="bi bi-circle"></i><span>Inactive Users</span>
                    </a>
                </li>
            </ul>
        </li><!-- End User Nav -->

        <li class="nav-heading">Website Settings</li>

        <li class="nav-item">
            <a class="nav-link <?= ($current_page == 'settings.php') ? '' : 'collapsed' ?>" href="settings.php">
                <i class="bi bi-gear"></i>
                <span>Settings</span>
            </a>
        </li><!-- End Settings Page Nav -->

        <li class="nav-item">
            <a class="nav-link <?= ($current_page == 'history.php') ? '' : 'collapsed' ?>" href="history.php">
                <i class="bi bi-eye"></i>
                <span>Activity History</span>
            </a>
        </li><!-- End History Page Nav -->

    </ul>

</aside><!-- End Sidebar -->