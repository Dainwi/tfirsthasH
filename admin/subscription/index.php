<?php
define('TITLE', 'Admin Dashboard');
define('PAGE', 'subscription');
define('PAGE_T', 'Subscription');
include "../include/header.php";
include "../include/sidebar.php";
include "../include/top-nav.php";
?>
<?php
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page
    // echo "<script>window.location.href='../../landingpage/index.php'</script>";
    header("Location: $url");
    exit();
}
?>
<?php
$userId = $_SESSION['user_id'];

$sql = "SELECT p_id, valid_to FROM subscription WHERE c_id = '$userId'";
$result = mysqli_query($con, $sql);
$row = mysqli_fetch_assoc($result);
$pId = $row['p_id'];
$expiryDate = strtotime($row['valid_to']);
$renewal = date('M j, Y', $expiryDate);
$currentDate = time();

$daysLeft = floor(($expiryDate - $currentDate) / (60 * 60 * 24));

$daysThreshold = 10;

if ($daysLeft <= $daysThreshold) {
    $renewalRequired = true;
} else {
    $renewalRequired = false;
}

$sql2 = "SELECT p_name, p_duration, p_price_s, p_price_c FROM plan WHERE p_id = '$pId'";
$result2 = mysqli_query($con, $sql2);
$row2 = mysqli_fetch_assoc($result2);
$planName = $row2['p_name'];
$planPrice = $row2['p_price_s'];
$planPriceC = $row2['p_price_c'];

$sql3 = "SELECT * FROM transaction WHERE t_user_id = '$userId'";
$result3 = mysqli_query($con, $sql3);

?>

<div class="row">
    <div class="col-md-3 col-lg-3">
        <div class="card card-bg rounded stats-card">
            <div class="card-body pb-2 pt-3">
                <div class="stats-icon change-success mx-2">
                    <i class="material-icons">trending_up</i>
                </div>
                <div class="m-l-15">
                    <h4 class="m-b-0 text-white"><?php echo $planName ?></h4>
                    <p class="m-b-0 text-white">Current Plan</p>
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
                    <h4 class="m-b-0 text-white"><?php echo $daysLeft ?></h4>
                    <p class="m-b-0 text-white">Days Left</p>
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
                    <h4 class="m-b-0 text-white"><?php echo $renewal ?></h4>
                    <p class="m-b-0 text-white">Renewal Date</p>
                </div>

            </div>
        </div>
    </div>


    <?php if ($renewalRequired) {
        echo "<div class='col-md-3 col-lg-3 py-2'>
            <div class='card bg-light rounded stats-card'>
                <div class='card-body pb-2 pt-3'>

                    <div style='background-color: #c52332;' class='stats-icon change-success mx-2'>
                        <i class='material-icons'>trending_down</i>
                    </div>
                    <div class='m-l-15'>
                        <a style='background-color: #c52332; color: white;' id='renew' class='px-2 rounded' href='#'>Click to Renew</a>                        
                        <h5 class='m-b-0 text-dark'>Membership</h5>
                    </div>
                </div>
            </div>
        </div>";
    } else {
        echo "<div class='col-md-3 col-lg-3 py-2'>
            <div class='card card-bg rounded stats-card'>
                <div class='card-body pb-2 pt-3'>

                    <div style='background-color: #dfba08;' class='stats-icon change-success mx-2'>
                        <i class='material-icons'>trending_up</i>
                    </div>
                    <div class='m-l-15'>
                        <button type='button' class='px-2 rounded' data-bs-toggle='modal' data-bs-target='#upgrade' style='background-color: #dfba08; color: white;' >Upgrade Now</button>                        
                        <h5 class='m-b-0 text-muted'>Membership</h5>
                    </div>
                </div>
            </div>
        </div>";
    }
    ?>




    <div class="col">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <h5 class="card-title">Invoices</h5>

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

<!-- Upgrade Membership  -->
<div class="modal" id="upgrade" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <!-- ... (Add the content of the Add Coupon Modal) ... -->
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content card-bg">
            <div class="modal-header">
                <h5 class="modal-title text-white" id="exampleModalCenterTitle">Available Plans!!</h5>
                <button type="button" class="btn-close bg-light" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST">
                    <?php
                    // Assuming $con is your database connection object
                    $sqlx = "SELECT p_id, p_name, p_duration, p_price_s, p_price_c FROM plan";
                    $result2 = mysqli_query($con, $sqlx);

                    while ($rowx = mysqli_fetch_assoc($result2)) {
                        $planid = $rowx['p_id'];
                        $planname = $rowx['p_name'];
                        $planprice = $rowx['p_price_s'];
                        $planpriceC = $rowx['p_price_c'];
                        $planduration = $rowx['p_duration'];

                        echo '<div class="mb-3">';
                        echo '<div class="custom-control custom-radio border rounded px-3 py-3">';
                        echo '<input type="radio" id="plan' . $planid . '" name="membershipPlan" value="' . $planid . '" class="custom-control-input" data-price-c="' . $planprice . '" data-plan-d="' . $planduration . '" data-plan-name="' . $planname . '">';
                        echo '<label class="custom-control-label text-white" for="plan' . $planid . '">';
                        echo '<strong class="text-white">' . $planname . '</strong> - <span class="text-muted"><del>₹' . $planpriceC . '</del></span> ₹' . $planprice ;
                        echo '</label>';
                        echo '</div>';
                        echo '</div>';
                        
                        
                    }
                    ?>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" id="upgradePlan" class="btn btn-success">Upgrade Now</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<!-- for renew the plan  -->
<script>
    $(document).ready(function() {
        var planId = "<?php echo $row['p_id']; ?>";
        var planDuration = "<?php echo $row2['p_duration']; ?>";
        var userId = "<?php echo $_SESSION['user_id'];  ?>";
        $("#renew").click(function(e) {

            var payableAmount = "<?php echo $row2["p_price_s"]; ?>"; // Get the updated payable amount 


            var options = {
                key: '<?php echo $rkey ?>', // Replace with your actual key
                amount: payableAmount * 100, // Amount in paise
                currency: 'INR',
                name: '<?php echo $rname ?>',
                handler: function(response) {
                    // ... Other variables ...

                    // You can use the variables here in your AJAX request
                    $.ajax({
                        type: "POST",
                        url: "payment.php",
                        data: {
                            paymentStatus: true,
                            payment_id: response.razorpay_payment_id,
                            payableAmount: payableAmount,
                            planId: planId,
                            planDuration: planDuration,
                            userId: userId,
                            // ... Other data ...
                        },
                        success: function(response) {
                            window.location.reload();
                            console.log("Payment success");
                        },
                        error: function(xhr, status, error) {
                            console.error(
                                "AJAX request to payment-process.php failed:",
                                error);
                        }


                    });

                },
                // ... Rest of your Razorpay options ...
            };

            var rzp = new Razorpay(options);
            rzp.open();
        });
    });
</script>



<!-- for upgrade plan -->
<script>
    $(document).ready(function() {
        $('#upgradePlan').click(function() {
            var selectedPlanId = $('input[name="membershipPlan"]:checked').val();
            var planPriceC = $('input[name="membershipPlan"]:checked').data('price-c');
            var planName = $('input[name="membershipPlan"]:checked').data('plan-name');
            var planDuration = $('input[name="membershipPlan"]:checked').data('plan-d');

            var planId = selectedPlanId;
            var userId = '<?php echo $userId; ?>';
            var payableAmount = planPriceC;

            var options = {
                key: '<?php echo $rkey ?>',
                amount: payableAmount * 100, // Assuming payableAmount is in the correct currency unit that Razorpay expects
                currency: 'INR',
                name: '<?php echo $rname ?>',
                handler: function(response) {
                    $.ajax({
                        type: "POST",
                        url: "payment.php",
                        data: {
                            upgradeStatus: true,
                            payment_id: response.razorpay_payment_id,
                            payableAmount: payableAmount,
                            planId: planId,
                            planDuration: planDuration,
                            userId: userId,
                        },
                        success: function(response) {
                            // Assuming the response is a success indicator (you might need to adjust based on actual response structure)
                            // if (response === 'success') { // Check if the response indicates success
                                // alert("Upgrade successful!");
                                
                                window.location.reload();
                                // Reload the page
                            // } else {
                            //     // Handle failure case, maybe show a message to the user
                            //     console.error("Upgrade failed:", response);
                            // }
                        },
                        error: function(xhr, status, error) {
                            console.error("AJAX request to payment.php failed:", error);
                        }
                    });
                },
                // Other Razorpay options...
            };

            var rzp = new Razorpay(options);
            rzp.open();
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php
include_once("../include/footer.php");
?>