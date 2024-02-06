<?php
include "../includes/header.php";
?>
<?php
include "../config.php";
include "../includes/sidebar.php";
include "../includes/top-nav.php";

// Query to get all transactions
$sql3 = "SELECT * FROM transaction JOIN admin ON transaction.t_user_id = admin.c_id ORDER BY t_date DESC"; // Assuming you want to order by date in descending order
$result3 = mysqli_query($con, $sql3);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transactions</title>
    <!-- Add your CSS stylesheets or CDN links here -->
</head>
<body>

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
                                <th scope="col">User Name</th>
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
                                    <th scope="row"><?php echo $row3['t_date']; ?></th>
                                    <td><?php echo $row3['c_name']; ?></td>
                                    <td><?php echo $row3['t_razorpay_id']; ?></td>
                                    <td><?php echo "Rs." . $row3['t_amount']; ?></td>
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

</body>
</html>
<?php include "../includes/footer.php";?>



