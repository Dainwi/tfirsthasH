<?php
include "../includes/header.php";
?>
<?php
include "../config.php";
include "../includes/sidebar.php";
include "../includes/top-nav.php";

$sql1 = "SELECT * FROM clients_db ";
$res1 = mysqli_query($con, $sql1);
$totalClients = mysqli_num_rows($res1);



?>



<form action="<?php $_SERVER['PHP_SELF']; ?>" method="get">
    <div class="container card-bg p-2">
        <div class="row">
            <div class="col-md-5 ">
                <div class="input-group">

                    <input type="text" name="search" class="form-control border border-primary" placeholder="Search Clients">
                    <button type="submit" class="btn btn-outline-primary" id="btn-search-client">Search</button>
                </div>
            </div>
            <div class="col-md-4">
                <?php
                if (isset($_GET['search'])) {
                    echo "<p class='text-center'>Search for : <span class='font-weight-bold'> {$_GET["search"]}</span></p>";
                }

                ?>

            </div>
        </div>
    </div>

</form>

<div class="card container card-bg p-3 mt-3">

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th class='text-warning'>#</th>
                    <th><small class="text-warning">Name</small></th>
                    <th><small class="text-warning">Phone</small></th>
                    <th><small class="text-warning">Email</small></th>
                    <!-- <th><small class="text-warning">Authentication</small></th> -->
                    <th><small class="text-warning">Subscription Status</small></th>
                    <th><small class="text-warning">Plan name</small></th>
                    <th><small class="text-warning"></small></th>
                </tr>
            </thead>
            <tbody>
                <?php
                 if (isset($_GET['search'])) {
                    $termS = $_GET['search'];
                    if ($termS != "") {
                        $sql = "SELECT * FROM admin JOIN subscription ON admin.c_id = subscription.c_id JOIN plan ON subscription.p_id = plan.p_id WHERE  LOWER(c_name) LIKE LOWER('%$termS%') OR c_phone LIKE '%$termS%' OR LOWER(c_email) LIKE LOWER('%$termS%')";
                    } else {
                        $sql = "SELECT * FROM admin JOIN subscription ON admin.c_id = subscription.c_id JOIN plan ON subscription.p_id = plan.p_id ORDER BY c_id DESC LIMIT {$offset},{$limit}";
                    }
                } else {
                    $sql = "SELECT * FROM admin JOIN subscription ON admin.c_id = subscription.c_id JOIN plan ON subscription.p_id = plan.p_id";
                }

                

                $res = mysqli_query($con, $sql);
                if (mysqli_num_rows($res) > 0) {
                    $i = 1;
                    while ($row = mysqli_fetch_assoc($res)) {
                ?>
                        <tr>
                            <td class='text-light'><?php echo $i; ?></td>
                            <td class="c-name text-light"><?php echo $row['c_name'] ?></td>
                            <td class="c-phone text-light"><?php echo $row['c_phone'] ?></td>
                            <td class="c-email text-light"><?php echo $row['c_email'] ?></td>
                            <td class="c-email text-light"><?php echo $row['sub_status']==1 ?'Active':"no Active Plan";?></td>
                            <td class="c-email text-light"><?php echo $row['p_name']?></td>
                            <!-- <td class='text-center'>
                                <?php
                                $verify = $row['c_auth'];
                                ?>
                                <form action="clients-auth.php" method="post" id="form-toggle">

                                    <input type="hidden" name="clid" value="<?php echo $row['c_id']; ?>">
                                    <input type="hidden" name="clname" value="<?php echo $row['c_name']; ?>">
                                    <input type="hidden" name="clemail" value="<?php echo $row['c_email']; ?>">
                                    <input type="hidden" name="clphone" value="<?php echo $row['c_phone']; ?>">
                                    <input type="hidden" name="togglebtn1" value="1">
                                    <div class="form-check form-switch text-center">
                                        <input type="checkbox" class="form-check-input switch-toogle border shadow" style="border: 1px solid #000 !important;" id="customSwitch<?php echo $i; ?>" value="<?php echo $verify; ?>" name="verify" <?php if ($verify == 1) {
                                                                                                                                                                                                                                                    echo "checked";
                                                                                                                                                                                                                                                } ?>>
                                        <label class="custom-control-label form-check-label" for="customSwitch<?php echo $i; ?>"></label>
                                    </div>
                                </form>

                            </td> -->
                            <td>
                                <div> 
                                <a class="btn btn-sm btn-outline-primary view-project" href="client-detail.php?id=<?php echo $row['c_id']?>">View</a>

                                </div>
                            </td>

                        </tr>


                <?php
                        $i++;
                    }
                } else {
                    echo "<tr><td colspan='6' class='text-center'>no result found</td></tr>";
                }

                ?>

            </tbody>
        </table>
    </div>
</div>
<?php include "../includes/footer.php";?>
