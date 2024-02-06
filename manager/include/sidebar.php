      <div class="page-sidebar">
          <a class="logo text-warning" href="<?php echo $url . "/manager/dashboard" ?>"> <img src="../../assets/images/logo/logo.png" alt="" class="img-fluid me-2" style="width: 33px;"> First Hash</a>
          <ul class="list-unstyled accordion-menu ps">

              <!-- dashboard -->
              <li class="<?php if (PAGE == 'dashboard') {
                                echo "active-page";
                            } ?>">
                  <a href="<?php echo $url . "/manager/dashboard" ?>" class="active"><i data-feather="activity"></i>Dashboard</a>

              </li>


              <!-- projects  -->
              <li class="<?php if (PAGE == 'projects' || PAGE == 'completed-projects') {
                                echo "active-page";
                            } ?>">
                  <a href="#" class="<?php if (PAGE == 'projects' || PAGE == 'completed-projects') {
                                            echo "active";
                                        } ?>"><i data-feather="trello"></i>Projects<i class="fas fa-chevron-right dropdown-icon"></i></a>
                  <ul class="">
                      <li><a href="<?php echo $url . "/manager/projects" ?>" class="<?php if (PAGE == 'projects') {
                                                                                        echo "active";
                                                                                    } ?>"><i class="far fa-circle"></i>Ongoing</a></li>

                      <li><a href="<?php echo $url . "/manager/projects/completed-projects.php" ?>" class="<?php if (PAGE == 'completed-projects') {
                                                                                                                echo "active";
                                                                                                            } ?>"><i class="far fa-circle"></i>Completed</a></li>



                  </ul>
              </li>

              <!-- tasks  -->
              <li class="<?php if (PAGE == 'mdaily-tasks' || PAGE == 'my-tasks' || PAGE == 'completed-tasks') {
                                echo "active-page";
                            } ?>">
                  <a href="#" class="<?php if (PAGE == 'mdaily-tasks' || PAGE == 'my-tasks' || PAGE == 'completed-tasks') {
                                            echo "active";
                                        } ?>"><i data-feather="archive"></i>My Tasks<i class="fas fa-chevron-right dropdown-icon"></i></a>
                  <ul class="">
                      <li><a href="<?php echo $url . "/manager/tasks" ?>" class="<?php if (PAGE == 'my-tasks') {
                                                                                        echo "active";
                                                                                    } ?>"><i class="far fa-circle"></i>Project tasks</a></li>
                      <li><a href="<?php echo $url . "/manager/tasks/daily-tasks.php" ?>" class="<?php if (PAGE == 'mdaily-tasks') {
                                                                                                        echo "active";
                                                                                                    } ?>"><i class="far fa-circle"></i>Daily tasks</a></li>

                      <li><a href="<?php echo $url . "/manager/tasks/completed-tasks.php" ?>" class="<?php if (PAGE == 'completed-tasks') {
                                                                                                            echo "active";
                                                                                                        } ?>"><i class="far fa-circle"></i>Completed</a></li>



                  </ul>
              </li>

              <li class="<?php if (PAGE == 'project-tasks') {
                                echo "active-page";
                            } ?>"><a href="<?php echo $url . "/manager/project-tasks" ?>"><i data-feather="check-square"></i>Project Tasks</a></li>




              <!-- attendance -->
              <li class="<?php if (PAGE == 'attendance') {
                                echo "active-page";
                            } ?>">
                  <a href="<?php echo $url . "/manager/attendance" ?>" class="active"><i data-feather="calendar"></i>Attendance</a>

              </li>

              <!-- attendance -->
              <li class="<?php if (PAGE == 'daily-tasks') {
                                echo "active-page";
                            } ?>">
                  <a href="<?php echo $url . "/manager/daily-task" ?>" class="active"><i data-feather="edit"></i>Daily Tasks</a>

              </li>



              <!-- billing -->
              <li class="<?php if (PAGE == 'payment') {
                                echo "active-page";
                            } ?>">
                  <a href="<?php echo $url . "/manager/payments" ?>" class="active"><i data-feather="dollar-sign" style="width:0px; height:0px;"></i><i class="fas fa-rupee-sign fa-lg" style="position: relative; left:-10px;"></i> <span class="ms-2">Payments</span> </a>

              </li>
              <!-- profile -->
              <li class="<?php if (PAGE == 'profile') {
                                echo "active-page";
                            } ?>">
                  <a href="<?php echo $url . "/manager/profile" ?>" class="active"><i data-feather="user"></i>Profile</a>

              </li>


          </ul>
          <a href="#" id="sidebar-collapsed-toggle"><i data-feather="arrow-right"></i></a>
      </div>
      <div class="page-content">