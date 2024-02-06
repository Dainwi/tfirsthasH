<div class="page-header">
    <nav class="navbar navbar-expand-lg d-flex justify-content-between">
        <div class="header-title flex-fill">
            <a href="#" id="sidebar-toggle"><i data-feather="arrow-left"></i></a>
            <h5><?php echo PAGE_T ?></h5>
        </div>
        <!-- <div class="header-search">
            <form>
                <input class="form-control" type="text" placeholder="Type something.." aria-label="Search">
                <a href="#" class="close-search"><i data-feather="x"></i></a>
            </form>
        </div> -->
        <div class="flex-fill" id="headerNav">
            <ul class="navbar-nav">
                <!-- <li class="nav-item d-md-block d-lg-none">
                    <a class="nav-link" href="#" id="toggle-search"><i data-feather="search"></i></a>
                </li> -->
                <li class="nav-item d-none d-md-block ">
                    <a class="nav-link px-3 rounded text-warning btn-sm" role="button" href="<?php echo $url . "/admin/team-allocation" ?>" style="background-color: #1f1f2b;"><i class='fas fa-sitemap me-2'></i> Team Allocation</a>
                </li>
                <li class="nav-item d-none d-md-block ">
                    <a class="nav-link px-3 rounded text-primary btn-sm" role="button" href="<?php echo $url . "/admin/projects/create-new-project.php" ?>" style="background-color: #1f1f2b;"><i class='fas fa-plus me-2'></i> New Project</a>
                </li>

                <!-- <li class="nav-item dropdown">
                    <a class="nav-link notifications-dropdown" href="#" id="notificationsDropDown" role="button" data-bs-toggle="dropdown" aria-expanded="false">3<div class="spinner-grow text-danger" role="status"></div></a>
                    <div class="dropdown-menu dropdown-menu-end notif-drop-menu" aria-labelledby="notificationsDropDown">
                        <h6 class="dropdown-header">Notifications</h6>
                        <a href="#">
                            <div class="header-notif">
                                <div class="notif-image">
                                    <span class="notification-badge bg-info text-white">
                                        <i class="fas fa-bullhorn"></i>
                                    </span>
                                </div>
                                <div class="notif-text">
                                    <p class="bold-notif-text">faucibus dolor in commodo lectus mattis</p>
                                    <small>19:00</small>
                                </div>
                            </div>
                        </a>
                        <a href="#">
                            <div class="header-notif">
                                <div class="notif-image">
                                    <span class="notification-badge bg-primary text-white">
                                        <i class="fas fa-bolt"></i>
                                    </span>
                                </div>
                                <div class="notif-text">
                                    <p class="bold-notif-text">faucibus dolor in commodo lectus mattis</p>
                                    <small>18:00</small>
                                </div>
                            </div>
                        </a>
                        <a href="#">
                            <div class="header-notif">
                                <div class="notif-image">
                                    <span class="notification-badge bg-success text-white">
                                        <i class="fas fa-at"></i>
                                    </span>
                                </div>
                                <div class="notif-text">
                                    <p>faucibus dolor in commodo lectus mattis</p>
                                    <small>yesterday</small>
                                </div>
                            </div>
                        </a>
                        <a href="#">
                            <div class="header-notif">
                                <div class="notif-image">
                                    <span class="notification-badge">
                                        <img src="../../assets/images/avatars/profile-image.png" alt="">
                                    </span>
                                </div>
                                <div class="notif-text">
                                    <p>faucibus dolor in commodo lectus mattis</p>
                                    <small>yesterday</small>
                                </div>
                            </div>
                        </a>
                        <a href="#">
                            <div class="header-notif">
                                <div class="notif-image">
                                    <span class="notification-badge">
                                        <img src="../../assets/images/avatars/profile-image.png" alt="">
                                    </span>
                                </div>
                                <div class="notif-text">
                                    <p>faucibus dolor in commodo lectus mattis</p>
                                    <small>yesterday</small>
                                </div>
                            </div>
                        </a>
                    </div>
                </li> -->
                <li class="nav-item dropdown">
                    <a class="nav-link profile-dropdown" href="#" id="profileDropDown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img class="rounded-circle" style="height:36px" src="<?php

                                                                                if ($user_photo == '0') {
                                                                                    echo $url . "/assets/images/avatars/profile-image.png";
                                                                                } else {
                                                                                    echo $url . "/assets/images/profile/" . $user_photo;
                                                                                }
                                                                                ?>" alt="">
                    </a>
                    <div class="dropdown-menu dropdown-menu-end profile-drop-menu" aria-labelledby="profileDropDown">
                        <a class="dropdown-item" href="<?php echo $url . "/admin/profile"; ?>"><i data-feather="user"></i>Profile</a>
                        <a class="dropdown-item" href="<?php echo $url . "/admin/inventory" ?>"><i data-feather="shopping-bag"></i>Inventory</a>
                        <!-- <a class="dropdown-item" href="#"><i data-feather="edit"></i>Activities<span class="badge rounded-pill bg-success">12</span></a>
                        <a class="dropdown-item" href="#"><i data-feather="check-circle"></i>Tasks</a> -->
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="<?php echo $url . "/admin/company" ?>"><i data-feather="settings"></i>Company Details</a>
                        <a class="dropdown-item" href="<?php echo $url . "/admin/dashboard/terms-condition.php" ?>"><i data-feather="file-text"></i>Terms</a>
                        <a class="dropdown-item" href="<?php echo $url . "/admin/include/logout.php"; ?>"><i data-feather="log-out"></i>Logout</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</div>
<div class="main-wrapper">