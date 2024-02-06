      <div class="page-sidebar">
          <a class="logo text-warning" href="<?php echo $url . "/admin/dashboard" ?>"> <img src="../../assets/images/logo/logo.png" alt="" class="img-fluid me-2" style="width: 33px;"> First Hash</a>
          <ul class="list-unstyled accordion-menu ps">

              <!-- dashboard -->
              <li class="<?php if (PAGE == 'dashboard') {
                                echo "active-page";
                            } ?>">
                  <a href="<?php echo $url . "/admin/dashboard" ?>" class="active"><i data-feather="activity"></i>Dashboard</a>

              </li>

              <!-- clients -->
              <li class="<?php if (PAGE == 'clients') {
                                echo "active-page";
                            } ?>">
                  <a href="<?php echo $url . "/admin/clients" ?>" class="active"><i data-feather="users"></i>Clients</a>

              </li>

              <!-- projects  -->
              <li class="<?php if (PAGE == 'projects' || PAGE == 'new-project' || PAGE == 'pending-project' || PAGE == 'ongoing-project' || PAGE == 'completed-project' || PAGE == 'deliverables') {
                                echo "active-page";
                            } ?>">
                  <a href="#" class="<?php if (PAGE == 'projects' || PAGE == 'new-project' || PAGE == 'pending-project' || PAGE == 'ongoing-project' || PAGE == 'completed-project' || PAGE == 'deliverables') {
                                            echo "active";
                                        } ?>"><i data-feather="trello"></i>Projects<i class="fas fa-chevron-right dropdown-icon"></i></a>
                  <ul class="">
                      <li><a href="<?php echo $url . "/admin/projects" ?>" class="<?php if (PAGE == 'projects') {
                                                                                        echo "active";
                                                                                    } ?>"><i class="far fa-circle"></i>All Projects</a></li>
                      <li><a href="<?php echo $url . "/admin/projects/pending-projects.php" ?>" class="<?php if (PAGE == 'pending-project') {
                                                                                                            echo "active";
                                                                                                        } ?>"><i class="far fa-circle"></i>Pending</a></li>
                      <li><a href="<?php echo $url . "/admin/projects/ongoing-projects.php" ?>" class="<?php if (PAGE == 'ongoing-project') {
                                                                                                            echo "active";
                                                                                                        } ?>"><i class="far fa-circle"></i>Ongoing</a></li>
                      <li><a href="<?php echo $url . "/admin/projects/completed-projects.php" ?>" class="<?php if (PAGE == 'completed-project') {
                                                                                                                echo "active";
                                                                                                            } ?>"><i class="far fa-circle"></i>Completed</a></li>

                      <li><a href="<?php echo $url . "/admin/projects/create-new-project.php" ?>" class="<?php if (PAGE == 'new-project') {
                                                                                                                echo "active";
                                                                                                            } ?>"><i class="far fa-circle"></i>New Project</a></li>


                      <li><a href="<?php echo $url . "/admin/projects/deliverables.php" ?>" class="<?php if (PAGE == 'deliverables') {
                                                                                                        echo "active";
                                                                                                    } ?>"><i class="far fa-circle"></i>Deliverables</a></li>
                  </ul>
              </li>

              <!-- event allocation -->
              <!-- <li class="<?php if (PAGE == 'allocation') {
                                    echo "active-page";
                                } ?>">
                  <a href="<?php echo $url . "/admin/team-allocation" ?>" class="active"><i data-feather="archive"></i>Team Allocation</a>

              </li> -->


              <!-- employee -->
              <li class="<?php if (PAGE == 'employee' || PAGE == 'add-employee' || PAGE == 'employee-type') {
                                echo "active-page";
                            } ?>">
                  <a href="#" class="<?php if (PAGE == 'employee' || PAGE == 'add-employee' || PAGE == 'employee-type') {
                                            echo "active";
                                        } ?>"><i data-feather="users"></i>Team<i class="fas fa-chevron-right dropdown-icon"></i></a>
                  <ul class="">
                      <li><a href="<?php echo $url . "/admin/employees" ?>" class="<?php if (PAGE == 'employee') {
                                                                                        echo "active";
                                                                                    } ?>"><i class="far fa-circle"></i>All Team</a></li>
                      <li><a href="<?php echo $url . "/admin/employees/add-member.php" ?>" class="<?php if (PAGE == 'add-employee') {
                                                                                                        echo "active";
                                                                                                    } ?>"><i class="far fa-circle"></i>Add New</a></li>
                      <li><a href="<?php echo $url . "/admin/employees/employee-types.php" ?>" class="<?php if (PAGE == 'employee-type') {
                                                                                                            echo "active";
                                                                                                        } ?>"><i class="far fa-circle"></i>Team Types</a></li>
                  </ul>
              </li>


              <!-- <li class="<?php if (PAGE == 'attendance' || PAGE == 'daily-tasks') {
                                    echo "active-page";
                                } ?>">
                  <a href="#" class="<?php if (PAGE == 'attendance' || PAGE == 'daily-tasks') {
                                            echo "active";
                                        } ?>"><i data-feather="calendar"></i>Daily Works<i class="fas fa-chevron-right dropdown-icon"></i></a>
                  <ul class="">




                  </ul>
              </li> -->

              <!-- salary  -->
              <li class="<?php if (PAGE == 'pending-salary' || PAGE == 'salary') {
                                echo "active-page";
                            } ?>">
                  <a href="#" class="<?php if (PAGE == 'pending-salary' || PAGE == 'salary') {
                                            echo "active";
                                        } ?>"><i data-feather="briefcase"></i>Salary<i class="fas fa-chevron-right dropdown-icon"></i></a>
                  <ul class="">
                      <li><a href="<?php echo $url . "/admin/salary/pending-salary.php" ?>" class="<?php if (PAGE == 'pending-salary') {
                                                                                                        echo "active";
                                                                                                    } ?>"><i class="far fa-circle"></i>Pending</a></li>
                      <li><a href="<?php echo $url . "/admin/salary" ?>" class="<?php if (PAGE == 'salary') {
                                                                                    echo "active";
                                                                                } ?>"><i class="far fa-circle"></i>Paid</a></li>


                  </ul>
              </li>

              <!-- daily works  -->

              <li class="<?php if (PAGE == 'attendance') {
                                echo "active-page";
                            } ?>"><a href=" <?php echo $url . "/admin/attendance" ?>"><i data-feather="calendar"></i>Attendance</a></li>
              <li class="<?php if (PAGE == 'daily-tasks') {
                                echo "active-page";
                            } ?>"><a href="<?php echo $url . "/admin/daily-task" ?>"><i data-feather="edit"></i>Daily Tasks</a></li>


              <li class="<?php if (PAGE == 'project-tasks') {
                                echo "active-page";
                            } ?>"><a href="<?php echo $url . "/admin/project-tasks" ?>"><i data-feather="check-square"></i>Project Tasks</a></li>


              <!-- data management -->
              <li class="<?php if (PAGE == 'data-management') {
                                echo "active-page";
                            } ?>"><a href="<?php echo $url . "/admin/data-management" ?>"><i data-feather="archive"></i>Data Manage</a></li>



              <!-- billing -->
              <li class="<?php if (PAGE == 'billing') {
                                echo "active-page";
                            } ?>">
                  <a href="<?php echo $url . "/admin/billing" ?>" class="active"><i data-feather="dollar-sign" style="width:0px; height:0px;"></i><i class="fas fa-rupee-sign fa-lg" style="position: relative; left:-10px;"></i> <span class="ms-2">Billing</span> </a>

              </li>
              <!-- inventory -->
              <!-- <li class="<?php if (PAGE == 'inventory') {
                                    echo "active-page";
                                } ?>">
                  <a href="<?php echo $url . "/admin/inventory" ?>" class="active"><i data-feather="shopping-bag"></i>Inventory</a>

              </li> -->
              
              <li class="<?php if (PAGE == 'subscription') {
                                echo "active-page";
                            } ?>">
                  <a href="<?php echo $url . "/admin/subscription" ?>" class="active"><i data-feather="dollar-sign" style="width:0px; height:0px;"></i><i class="fas fa-rupee-sign fa-lg" style="position: relative; left:-10px;"></i> <span class="ms-2">My Subscription</span> </a>

              </li>


          </ul>
          <a href="#" id="sidebar-collapsed-toggle"><i data-feather="arrow-right"></i></a>
      </div>
      <div class="page-content">