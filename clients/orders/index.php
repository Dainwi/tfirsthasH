<?php
define('TITLE', 'My Orders');
define('PAGE', 'order');
define('PAGE_T', 'My Orders');
include_once('../include/header.php');
include_once('../include/sidebar.php');
include_once('../include/top-nav.php');
?>
<div class="container mt-4">
    <div class="row">
        <?php
        $sql = "SELECT * FROM projects_db WHERE project_client={$client_id}";
        $res = mysqli_query($con, $sql);
        if (mysqli_num_rows($res) > 0) {
            while ($row = mysqli_fetch_assoc($res)) {
        ?>
                <div class="col-md-4">
                    <div class="card shadow rounded card-bg">
                        <div class="card-body">
                            <h4 class="fw-bold card-title"><?php echo $row['project_event'] ?></h4>

                            <div class="row">
                                <div class="col-4">
                                    <small class="text-info"> Event Date</small>
                                </div>
                                <div class="col-8">
                                    <small class="text-warning">:
                                        <?php
                                        $sdate = $row['project_sdate'];
                                        $ldate = $row['project_ldate'];

                                        if ($ldate == "0000-00-00") {
                                            echo $sdate;
                                        } else {
                                            echo $sdate . " " . "to" . " " . $ldate;
                                        }


                                        ?>
                                    </small>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-4">
                                    <small class="text-info"> Status</small>
                                </div>
                                <div class="col-8">
                                    <small class="text-warning">:
                                        <?php
                                        if ($row['project_status'] == 0) {
                                            $status = "<span class='text-warning fw-bold'>pending</span>";
                                        } elseif ($row['project_status'] == 1) {
                                            $status = "<span class='text-info fw-bold'>in progress</span>";
                                        } else {
                                            $status = "<span class='text-success fw-bold'>completed</span>";
                                        }
                                        echo $status;
                                        ?>
                                    </small>
                                </div>
                            </div>

                            <p class="mt-4"><a href="<?php echo $url . "/clients/orders/view-order.php?order=" . $row['project_id']; ?>" class="btn btn-outline-info px-5 rounded">view</a></p>

                        </div>
                    </div>
                </div>

        <?php
            }
        }
        ?>
    </div>
</div>
<?php include_once('../include/footer.php') ?>