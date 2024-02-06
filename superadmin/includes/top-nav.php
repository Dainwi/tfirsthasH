<div class="page-header">
  <nav class="navbar navbar-expand-lg d-flex justify-content-between">
    <div class="header-title flex-fill">
      <a href="#" id="sidebar-toggle"><i data-feather="arrow-left"></i></a>
      <h5>Dashboard</h5>
    </div>
    
    <div class="flex-fill" id="headerNav">
      <ul class="navbar-nav">
        
        <li class="nav-item dropdown">
          <a class="nav-link profile-dropdown" href="#" id="profileDropDown" role="button" data-bs-toggle="dropdown" aria-expanded="false"><img src="../../assets/images/avatars/profile-image.png" alt=""></a>
          <div class="dropdown-menu dropdown-menu-end profile-drop-menu" aria-labelledby="profileDropDown">
            <a class="dropdown-item" href="#"><i data-feather="user"></i>Profile</a>
            <a class="dropdown-item" href="#"><i data-feather="inbox"></i>Messages</a>
            <a class="dropdown-item" href="#"><i data-feather="edit"></i>Activities<span class="badge rounded-pill bg-success">12</span></a>
            <a class="dropdown-item" href="#"><i data-feather="check-circle"></i>Tasks</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#"><i data-feather="settings"></i>Settings</a>
            <a class="dropdown-item" href="#"><i data-feather="unlock"></i>Lock</a>
            <a class="dropdown-item" href="<?php echo $url . "/superadmin/includes/logout.php"; ?>"><i data-feather="log-out"></i>Logout</a>
          </div>
        </li>
      </ul>
    </div>
  </nav>
</div>
<div class="main-wrapper">