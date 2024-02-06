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
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 col-lg-3">
            <div class="card card-bg rounded stats-card">
                <div class="card-body pb-2 pt-3">

                    <div class="stats-icon change-success mx-2">
                        <i class="material-icons">trending_up</i>
                    </div>
                    <div class="m-l-15">
                        <h3 class="m-b-0"><?php echo $totalClients ?></h3>
                        <p class="m-b-0 text-muted">Active Clients</p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<h1>Hello</h1>
<?php
include "../includes/footer.php";
?>