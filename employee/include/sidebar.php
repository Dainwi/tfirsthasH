      <div class="page-sidebar">
          <a class="logo text-warning" href="<?php echo $url . "/employee/dashboard" ?>"> <img src="../../assets/images/logo/logo.png" alt="" class="img-fluid me-2" style="width: 33px;"> First Hash</a>
          <ul class="list-unstyled accordion-menu ps">

              <!-- dashboard -->
              <li class="<?php if (PAGE == 'dashboard') {
                                echo "active-page";
                            } ?>">
                  <a href="<?php echo $url . "/employee/dashboard" ?>" class="active"><i data-feather="activity"></i>Dashboard</a>

              </li>


              <!-- tasks  -->
              <li class="<?php if (PAGE == 'daily-tasks' || PAGE == 'my-tasks' || PAGE == 'completed-tasks') {
                                echo "active-page";
                            } ?>">
                  <a href="#" class="<?php if (PAGE == 'daily-tasks' || PAGE == 'my-tasks' || PAGE == 'completed-tasks') {
                                            echo "active";
                                        } ?>"><i data-feather="trello"></i>Tasks<i class="fas fa-chevron-right dropdown-icon"></i></a>
                  <ul class="">
                      <li><a href="<?php echo $url . "/employee/tasks" ?>" class="<?php if (PAGE == 'my-tasks') {
                                                                                        echo "active";
                                                                                    } ?>"><i class="far fa-circle"></i>My tasks</a></li>
                      <li><a href="<?php echo $url . "/employee/tasks/daily-tasks.php" ?>" class="<?php if (PAGE == 'daily-tasks') {
                                                                                                        echo "active";
                                                                                                    } ?>"><i class="far fa-circle"></i>Daily tasks</a></li>

                      <li><a href="<?php echo $url . "/employee/tasks/completed-tasks.php" ?>" class="<?php if (PAGE == 'completed-tasks') {
                                                                                                            echo "active";
                                                                                                        } ?>"><i class="far fa-circle"></i>Completed</a></li>



                  </ul>
              </li>








              <!-- billing -->
              <li class="<?php if (PAGE == 'payment') {
                                echo "active-page";
                            } ?>">
                  <a href="<?php echo $url . "/employee/payments" ?>" class="active"><i data-feather="dollar-sign" style="width:0px; height:0px;"></i><i class="fas fa-rupee-sign fa-lg" style="position: relative; left:-10px;"></i> <span class="ms-2">Payments</span> </a>

              </li>
              <!-- profile -->
              <li class="<?php if (PAGE == 'profile') {
                                echo "active-page";
                            } ?>">
                  <a href="<?php echo $url . "/employee/profile" ?>" class="active"><i data-feather="user"></i>Profile</a>

              </li>


          </ul>
          <a href="#" id="sidebar-collapsed-toggle"><i data-feather="arrow-right"></i></a>
      </div>
      <div class="page-content">