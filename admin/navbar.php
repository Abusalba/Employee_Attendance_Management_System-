<!doctype html>
<html lang="en" data-layout="vertical" data-sidebar="dark" data-sidebar-size="lg" data-preloader="disable" data-theme="default" data-topbar="light" data-bs-theme="light">

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Navbar</title>
</head>

<body>

    <!-- Begin page -->
    <div id="layout-wrapper">

        <!-- ========== App Menu ========== -->
        <div class="app-menu navbar-menu">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <a href="index.php" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="assets/images/logo-dark1.png" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="assets/images/logo-sm1.png" alt="" height="22">
                    </span>
                </a>
                <a href="index.php" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="assets/images/logo-light1.png" alt="" height="40">
                    </span>
                    <span class="logo-lg">
                        <img src="assets/images/logo-sm1.png" alt="" height="40">
                    </span>
                </a>
                <button type="button" class="btn btn-sm p-0 fs-3xl header-item float-end btn-vertical-sm-hover" id="vertical-hover">
                    <i class="ri-record-circle-line"></i>
                </button>
            </div>

            <div id="scrollbar">
                <div class="container-fluid">

                    <div id="two-column-menu">
                    </div>
                    <ul class="navbar-nav" id="navbar-nav">

                        <li class="menu-title"><span data-key="t-menu">Menu</span></li>
                        <li class="nav-item">
                            <a class="nav-link menu-link collapsed" href="index.php">
                                <i class="ph-gauge"></i> <span data-key="t-dashboards">Dashboards</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link menu-link collapsed" href="attendence.php">
                                <i class="fa-regular fa-calendar-check"></i> <span data-key="t-attendece">Attendence</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link menu-link collapsed" href="employees.php">
                                <i class="fa-solid fa-users"></i> <span data-key="t-employees">Employees</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link menu-link collapsed" href="department.php">
                                <i class="fa-solid fa-building-user"></i> <span data-key="t-employees">Department</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link menu-link collapsed" href="leave_request.php">
                                <i class="fa-regular fa-comment-dots"></i> <span data-key="t-employees">Leave Request</span>
                            </a>
                        </li>

                        <li class="nav-item"> <a class="nav-link menu-link collapsed" href="logout.php">
                                <i class="fa-solid fa-right-from-bracket"></i> <span data-key="t-attendece">Logout</span>
                            </a></li>


                    </ul>
                </div>
                <!-- Sidebar -->
            </div>

            <div class="sidebar-background"></div>
        </div>

        <!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Toggle light and dark mode
    document.querySelectorAll('#light-dark-mode .dropdown-item').forEach(item => {
        item.addEventListener('click', () => {
            const mode = item.getAttribute('data-mode');
            if (mode === 'light') {
                document.body.classList.remove('bg-dark', 'text-light');
                document.body.classList.add('bg-light', 'text-dark');
                document.querySelector('.logo-light').classList.remove('d-none');
                document.querySelector('.logo-dark').classList.add('d-none');
            } else if (mode === 'dark') {
                document.body.classList.remove('bg-light', 'text-dark');
                document.body.classList.add('bg-dark', 'text-light');
                document.querySelector('.logo-light').classList.add('d-none');
                document.querySelector('.logo-dark').classList.remove('d-none');
            }
        });
    });
</script>
</body>
</html>