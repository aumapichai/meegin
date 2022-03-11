<!-- Preloader -->
<!-- <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
        </div> -->

<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>

    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">

        <div class="d-flex align-items-center border-kitchen">
            <img src="../picture/chef.png" class="img-kitchen-nav" alt="">
            <span class="text-kitchen-1">พ่อครัว</span>
        </div>

        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>

    </ul>
</nav>
<!-- /.navbar -->

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index.php" class="brand-link d-flex align-items-center">
        <img src="../picture/logo.ico" alt="Logo" class="brand-image-amdin mr-2" style="opacity: .8">
        <span class="brand-text font-weight-light">MeginPOS</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="../picture/miapa.ico" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <div class="d-block text-white"><span
                        style="color: #f39c12; font-weight: 500;">พ่อครัว</span><?php echo ' '.$_SESSION['fname']; ?>
                </div>
            </div>
        </div>



        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="index.php" class="nav-link <?php if($active == "Order"){  echo " active"; } ?>">

                        <i class="nav-icon fas fa-utensils"></i>
                        <p>
                            Order

                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="manage_food.php"
                        class="nav-link <?php if($active == "จัดการอาหาร"){  echo " active"; } ?>">
                        <i class="nav-icon fas fa-tasks"></i>
                        <p>
                            จัดการอาหาร
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link btn-logout">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>
                            ออกจากระบบ
                        </p>
                    </a>
                </li>

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>