<?php
include "../includes/header.php";
?>
<?php
include "../config.php";
include "../includes/sidebar.php";
include "../includes/top-nav.php";

// $id = $_GET['id'];

// $sql = "SELECT * FROM admin WHERE c_id={$id}";
// $res1 = mysqli_query($con, $sql);
// $totalClients = mysqli_num_rows($res1);

// echo $totalClients['c_name'];

// ?>

<?php
// client-details.php

$id = $_GET['id'];

// Query to get admin and transaction details for the specified ID
$sql = "SELECT * FROM admin 
        JOIN transaction ON admin.c_id = transaction.t_user_id 
        WHERE admin.c_id = $id";
$res1 = mysqli_query($con, $sql);

$sql3 = "SELECT * FROM transaction WHERE t_user_id = '$id' ORDER BY t_date DESC";
$result3 = mysqli_query($con, $sql3);

// Query to get client count for the specified admin ID
$sql5 = "SELECT admin.c_id AS admin_id, COUNT(clients_db.cus_id) AS client_count
         FROM admin
         LEFT JOIN clients_db ON admin.c_id = clients_db.cus_id
         WHERE admin.c_id = $id
         GROUP BY admin.c_id";
$result5 = mysqli_query($con, $sql5);

// Display results
if ($result5) {
    while ($row = mysqli_fetch_assoc($result5)) {
        $admin_id = $row['admin_id'];
        $client_count = $row['client_count'];
    }
} else {
    echo "Error executing query: " . mysqli_error($con);
}

// Query to get client count for the specified admin ID
$sql6 = "SELECT admin.c_id AS admin_id, COUNT(users_db.cus_id) AS employee_count
         FROM admin
         LEFT JOIN users_db ON admin.c_id = users_db.cus_id
         WHERE admin.c_id = $id
         GROUP BY admin.c_id";
$result6 = mysqli_query($con, $sql6);

// Display results
if ($result6) {
    while ($row = mysqli_fetch_assoc($result6)) {
       
        $employee_count = $row['employee_count'];
    }
} else {
    echo "Error executing query: " . mysqli_error($con);
}

$id = $_GET['id'];

// Assuming $id is sanitized or validated to prevent SQL injection

$startdate = date('Y-m-d', strtotime(date('Y-01-01')));
$enddate = date('Y-m-d', strtotime(date('Y-12-31')));

$sql7 = "SELECT projects_db.project_id, projects_db.project_status, quotation_db.q_project_id, quotation_db.grand_total, quotation_db.q_date
         FROM projects_db
         LEFT JOIN quotation_db ON projects_db.project_id = quotation_db.q_project_id
         WHERE quotation_db.q_date BETWEEN '$startdate' AND '$enddate' AND cus_id = '$id' AND (projects_db.project_status = 1 OR projects_db.project_status = 2)";

$res7 = mysqli_query($con, $sql7);
$totalRevenue = 0;

if ($res7) {
    while ($row7 = mysqli_fetch_assoc($res7)) {
        $totalRevenue += $row7['grand_total'];
    }
} else {
    echo "Error executing query: " . mysqli_error($con);
}

// echo "Total Revenue for cus_id $id: $totalRevenue";


// Assuming $id is sanitized or validated to prevent SQL injection

// Get the current month and year
$currentMonth = date('m');
$currentYear = date('Y');

$sql8 = "SELECT project_status FROM projects_db 
         WHERE project_status = 2 
         AND cus_id = '$id'
         AND MONTH(project_sdate) = '$currentMonth'
         AND YEAR(project_sdate) = '$currentYear'";
$res8 = mysqli_query($con, $sql8);

$projectDone = mysqli_num_rows($res8);


$sql9 = "SELECT c_id, MAX(valid_to) AS subscription_expiry_date
         FROM subscription
         WHERE c_id = '$id'
         GROUP BY c_id";

$result9 = mysqli_query($con, $sql9);

if ($result9) {
    $row = mysqli_fetch_assoc($result9);

    $subscriptionExpiryDate = $row['subscription_expiry_date'];

    // Calculate the difference in days
    $currentDate = date('Y-m-d');
    $daysLeft = floor((strtotime($subscriptionExpiryDate) - strtotime($currentDate)) / (60 * 60 * 24));

    // echo "Subscription Expiry Date: $subscriptionExpiryDate<br>";
    // echo "Days Left: $daysLeft days";
} else {
    echo "Error executing query: " . mysqli_error($con);
}



if (mysqli_num_rows($res1) > 0) {
  $row = mysqli_fetch_assoc($res1);
?>
 <div class="card">
  <div class="card-body">
    <h5 class="card-title">User Profile</h5>
    <p class="card-text">Name:  <?php echo $row['c_name']; ?></p>
    <p class="card-text">Address:  <?php echo $row['address']; ?></p>
    <p class="card-text">Email:  <?php echo $row['c_email']; ?></p>
    <p class="card-text">Phone:  <?php echo $row['c_phone']; ?></p>
  </div>
</div>

  <!-- add more HTML elements as needed -->
  
  <div class="row">
    <div class="col-md-3 col-lg-3">
        <div class="card card-bg rounded stats-card">
            <div class="card-body pb-2 pt-3">
                <div class="stats-icon change-success mx-2">
                    <!-- <i class="material-icons">trending_up</i> -->
                </div>
                <div class="m-l-15">
                    <h4 class="m-b-0 text-white"> <?php echo $client_count; ?></h4>
                    <p class="m-b-0 text-white">Active clients </p>
                </div>

            </div>
        </div>
    </div>

    <div class="col-md-3 col-lg-3">
        <div class="card card-bg rounded stats-card">
            <div class="card-body pb-2 pt-3">

                <div class="stats-icon change-success mx-2">
                    <!-- <i class="material-icons">trending_up</i> -->
                </div>
                <div class="m-l-15">
                    <h4 class="m-b-0 text-white"><?php echo $employee_count; ?></h4>
                    <p class="m-b-0 text-white">Employee Count</p>
                </div>

            </div>
        </div>
    </div>
    <div class="col-md-3 col-lg-3">
        <div class="card card-bg rounded stats-card">
            <div class="card-body pb-2 pt-3">

                <div class="stats-icon change-success mx-2">
                    <i class="material-icons">trending_up</i>
                </div>
                <div class="m-l-15">
                    <h4 class="m-b-0 text-white"><?php echo $totalRevenue ?></h4>
                    <p class="m-b-0 text-white">Total Revenue</p>
                </div>

            </div>
        </div>
    </div>
    <div class="col-md-3 col-lg-3">
        <div class="card card-bg rounded stats-card">
            <div class="card-body pb-2 pt-3">

                <div class="stats-icon change-success mx-2">
                    <i class="material-icons">trending_up</i>
                </div>
                <div class="m-l-15">
                    <h4 class="m-b-0 text-white"><?php echo $projectDone ?></h4>
                    <p class="m-b-0 text-white">Projects This Month</p>
                </div>

            </div>
        </div>
    </div>
    <div class="col-md-3 col-lg-3">
        <div class="card card-bg rounded stats-card">
            <div class="card-body pb-2 pt-3">

                <div class="stats-icon change-success mx-2">
                    <i class="material-icons">trending_up</i>
                </div>
                <div class="m-l-15">
                    <h4 class="m-b-0 text-white"><?php echo $daysLeft ?> days</h4>
                    <p class="m-b-0 text-white">Plan ends in</p>
                </div>

            </div>
        </div>
    </div>
 <div class="row">
 <div class="col">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <h5 class="card-title">Transactions</h5>

                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Date</th>
                                <th scope="col">Payment Id</th>
                                <th scope="col">Paid Amount</th>
                                <th scope="col">Plan</th>

                            </tr>
                        </thead>

                        <tbody>
                        <?php
                            while ($row3 = mysqli_fetch_assoc($result3)) {
                            ?>

                            <tr>
                                <th scope="row">
                                    <?php echo $row3['t_date']; ?>
                                </th>
                                <td>
                                    <?php echo $row3['t_razorpay_id']; ?>
                                </td>
                                <td>
                                    <?php echo "Rs." . $row3['t_amount']; ?>
                                </td>
                                <td>
                                    <?php
                                        $tPlanId = $row3['t_plan_id'];
                                        $sql4 = "SELECT p_name FROM plan WHERE p_id = '$tPlanId'";
                                        $result4 = mysqli_query($con, $sql4);
                                        $row4 = mysqli_fetch_assoc($result4);
                                        $planName = $row4["p_name"];
                                        echo $planName;

                                        ?>
                                </td>

                            </tr>
                            <?php
                            }
                        

                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        </div>
 </div>
 
    
<?php
} else {
  echo "No client found with ID {$id}.";
}
?>



