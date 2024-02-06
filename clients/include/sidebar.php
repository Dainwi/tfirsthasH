      <div class="page-sidebar">
          <a class="logo text-warning" href="<?php echo $url . "/clients/dashboard" ?>"> <img src="../../assets/images/logo/logo.png" alt="" class="img-fluid me-2" style="width: 33px;"> First Hash</a>
          <ul class="list-unstyled accordion-menu ps">

              <!-- dashboard -->
              <li class="<?php if (PAGE == 'dashboard') {
                                echo "active-page";
                            } ?>">
                  <a href="<?php echo $url . "/clients/dashboard" ?>" class="active"><i data-feather="activity"></i>Dashboard</a>

              </li>

              <!-- profile -->
              <li class="<?php if (PAGE == 'order') {
                                echo "active-page";
                            } ?>">
                  <a href="<?php echo $url . "/clients/orders" ?>" class="active"><i data-feather="trello"></i>My Orders</a>

              </li>

              <!-- profile -->
              <li class="<?php if (PAGE == 'profile') {
                                echo "active-page";
                            } ?>">
                  <a href="<?php echo $url . "/clients/profile" ?>" class="active"><i data-feather="user"></i>Profile</a>

              </li>


          </ul>
          <a href="#" id="sidebar-collapsed-toggle"><i data-feather="arrow-right"></i></a>
      </div>
      <div class="page-content">