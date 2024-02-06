<?php
define('TITLE', 'My Orders');
define('PAGE', 'order');
define('PAGE_T', 'Order Details');
include_once('../include/header.php');

include_once('../include/sidebar.php');
include_once('../include/top-nav.php');
if (isset($_GET['order'])) {
    $projectid = $_GET['order'];

    $sql = "SELECT * FROM projects_db WHERE project_id = {$projectid} AND project_client={$client_id}";
    $res = mysqli_query($con, $sql);
    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            $prid = $row['project_id'];
            $title = $row['project_event'];
            $sdate = $row['project_sdate'];
            $ldate = $row['project_ldate'];
            $status = $row['project_status'];
        }
        if ($ldate == "0000-00-00") {
            $date = $sdate;
        } else {
            $date = $sdate . "<span class='text-warning'> to </span>" . $ldate;
        }
        $sql2 = "SELECT * FROM quotation_db WHERE q_project_id ={$prid}";
        $res2 = mysqli_query($con, $sql2);
        if (mysqli_num_rows($res2) > 0) {
            while ($row2 = mysqli_fetch_assoc($res2)) {
                $pcost = $row2['q_package_cost'];
                $total = $row2['grand_total'];
            }
        } else {
            $pcost = 0;
        }


?>
        <div class="container mt-4">
            <div class="row">
                <div class="col-md-7">
                    <div class="card rounded card-bg">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div class="media align-items-center">

                                    <div>
                                        <h4 class="mb-0 text-warning"><?php echo $title ?></h4>
                                    </div>
                                </div>
                                <div>
                                    <?php if ($status == 1) {
                                        echo "<span class='badge rounded-pill bg-info'>In Progress</span>";
                                    } elseif ($status == 2) {
                                        echo "<span class='badge badge-pill badge-green'>Completed</span>";
                                    } elseif ($status == 0) {
                                        echo "<span class='badge badge-pill badge-orange'>Pending</span>";
                                    }
                                    ?>

                                </div>
                            </div>
                            <div class="mt-4 mb-2">
                                <p class="pb-0 mb-0 text-info">Date range:</p>
                                <p class="text-white"><?php echo $date ?></p>
                            </div>
                            <div class="pt-3">
                                <?php if ($pcost == 0 || $pcost == "") {
                                } else { ?>
                                    <a href="<?php echo $url . "/clients/my-orders/quotation-print.php?qid=" . $prid; ?>" class="btn btn-secondary btn-tone">Quotation</a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="card rounded card-bg">
                        <div class="card-body">
                            <div class="m-l-10">
                                <h5 class="m-b-0 text-warning">Payment & Invoice : </h5>
                                <?php
                                $sql_payment = "SELECT * FROM billing_db WHERE b_project_id={$prid}";
                                $res_payment = mysqli_query($con, $sql_payment);
                                $total_pending = 0;
                                if (mysqli_num_rows($res_payment) > 0) {
                                    $i = 1;
                                ?>
                                    <div class="card table-responsive">
                                        <table class="table table-bordered">

                                            <tbody>

                                                <?php
                                                while ($row_p = mysqli_fetch_assoc($res_payment)) {
                                                    $total_pending += $row_p['b_amount'];

                                                ?>

                                                    <tr>
                                                        <th scope="row" class="text-white"><?php echo $i; ?></th>
                                                        <td class="text-white"><?php echo $row_p['b_date']; ?></td>
                                                        <td class="text-white"><?php echo "Rs " . $row_p['b_amount']; ?></td>
                                                        <td><a href="<?php echo $url . "/clients/my-orders/billing-print.php?bprint&bp=" . $row_p['billing_id']; ?>" class="btn btn-sm btn-outline-primary">view</a></td>
                                                    </tr>
                                                <?php
                                                    $i++;
                                                }

                                                ?>
                                            </tbody>
                                        </table>
                                    </div>


                                <?php
                                } else {
                                    echo "<p class='text-center mt-4'>no payments</p>";
                                }


                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="card rounded card-bg">
                        <div class="card-body">
                            <h5 class="mb-4 text-warning pb-4">Work Progress : </h5>

                            <?php
                            $sql1 = "SELECT * FROM events_db WHERE event_pr_id={$projectid} ORDER BY event_date ASC";
                            $res1 = mysqli_query($con, $sql1);
                            $datestr = strtotime(date("Y-m-d"));
                            if (mysqli_num_rows($res1) > 0) {
                                while ($row1 = mysqli_fetch_assoc($res1)) {
                                    $eventdate = $row1['event_date'];
                                    $eventd = strtotime(date($eventdate));
                            ?>
                                    <div class="transactions-list">
                                        <div class="tr-item">
                                            <div class="tr-company-name">
                                                <i class="fas fa-sort mt-1"></i>
                                                <div class="tr-text ms-2">
                                                    <h4 class="text-white"><?php echo $row1['event_name'] ?></h4>
                                                    <p><?php echo date('dM Y', $eventd); ?></p>
                                                </div>
                                            </div>
                                            <div class="tr-rate">
                                                <p>
                                                    <?php

                                                    if ($eventd < $datestr) {
                                                        echo "<span class='text-success'><i class='fas fa-check-circle'></i> Completed</span>";
                                                    } else {
                                                        echo "<span><i class='far fa-clock'></i> Progress</span>";
                                                    }
                                                    ?>

                                                </p>
                                            </div>
                                        </div>
                                    </div>


                                <?php
                                }
                                ?>
                                <div class="transactions-list">
                                    <div class="tr-item">
                                        <div class="tr-company-name">
                                            <i class="fas fa-sort mt-1"></i>
                                            <div class="tr-text ms-2">
                                                <h4 class="text-white">Delivery</h4>
                                                <p></p>
                                            </div>
                                        </div>
                                        <div class="tr-rate">
                                            <p>
                                                <?php

                                                if ($status == 2) {
                                                    echo "<span class='text-success'><i class='fas fa-check-circle'></i> Delivered</span>";
                                                } else {
                                                    echo "<span><i class='far fa-clock'></i> On the way</span>";
                                                }
                                                ?>

                                            </p>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            }
                            ?>

                        </div>
                    </div>
                </div>

            </div>

        </div>
<?php
    } else {
        echo "<p class='text-center'>No data</p>";
    }
} else {
    echo "<p class='text-center'>No data</p>";
}

include_once('../include/footer.php') ?>