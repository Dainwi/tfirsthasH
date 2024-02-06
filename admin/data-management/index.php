<?php
define('TITLE', 'Admin Dashboard');
define('PAGE', 'data-management');
define('PAGE_T', 'Data Management');
include_once('../include/header.php');
include_once('../include/sidebar.php');
include_once('../include/top-nav.php');

$cusID=$_SESSION['user_id'];

$today = date("Y-m-d");
if (isset($_GET['m'])) {
    $month = $_GET['m'];

    $mon = date('M', strtotime($month));
} else {
    $month = date('m');
    $mon = date('M', strtotime($month));
}


if (isset($_GET['y'])) {
    $year = $_GET['y'];
} else {
    $year = date('Y');
}

$ay = $year + 1;
$sy = $year - 1;

$am = $month + 1;

$sm = $month - 1;
if ($month == 12) {
    $urlma = $url . "/admin/data-management/index.php?m=1&y=" . $ay;
    $amonth = 1;
} else {
    $urlma = $url . "/admin/data-management/index.php?m=" . $am . "&y=" . $year;
    $amonth = $am;
}

if ($month == 1) {
    $urlms = $url . "/admin/data-management/index.php?m=12&y=" . $sy;
    $smonth = 12;
} else {
    $urlms = $url . "/admin/data-management/index.php?m=" . $sm . "&y=" . $year;
    $smonth = $sm;
}



$cdate = $year . "-" . $month . "-01";
$newdate = date($cdate);

$monthh = date('F', strtotime($newdate));
?>

<div class="card card-bg mb-1">
    <div class="card-body pt-3 pb-2 d-flex justify-content-between align-items-center">
        <h5 class="text-uppercase">
            <div class="input-group">
                <a href="<?php echo $urlms ?>" class="btn btn-sm btn-outline-success"><i class="fas fa-minus"></i></a>
                <input type="text" class="form-control form-control-sm text-center text-uppercase border-success"
                    disabled value="<?php echo $monthh; ?>" id="">
                <a href="<?php echo $urlma ?>" class="btn btn-sm btn-outline-success"><i class="fas fa-plus"></i></a>
            </div>


        </h5>
        <h5>
            <div class="input-group">
                <a href="<?php echo $url . "/admin/data-management/index.php?m=" . $month . "&y=" . $sy ?>"
                    class="btn btn-sm btn-outline-warning"><i class="fas fa-minus"></i></a>
                <input type="text" class="form-control form-control-sm text-center text-uppercase border border-warning"
                    disabled value="<?php echo $year ?>" id="">
                <a href="<?php echo $url . "/admin/data-management/index.php?m=" . $month . "&y=" . $ay ?>"
                    class="btn btn-sm btn-outline-warning"><i class="fas fa-plus"></i></a>
            </div>
        </h5>
    </div>
</div>

<div class="table-responsive card-bg rounded shadow mt-0">
    <table class="table table-sm table-bordered">
        <thead>
            <th class='text-warning text-center'>Event Date</th>
            <th class='text-warning text-center'>Event</th>
            <th class='text-warning text-center'>Client Name</th>
            <th class='text-warning text-center'>Client Phone</th>
            <th class='text-warning text-center'>Data Stored In</th>
        </thead>
        <tbody>
            <?php
          $cusID = mysqli_real_escape_string($con, $cusID); // Sanitize variables
$month = mysqli_real_escape_string($con, $month);
$year = mysqli_real_escape_string($con, $year);

$sqld = "SELECT * FROM events_db LEFT JOIN clients_db ON events_db.event_cl_id=clients_db.c_id WHERE events_db.cus_id={$cusID} AND month(event_date)='{$month}' AND year(event_date)='{$year}' ORDER BY events_db.event_date ASC";
$resd = mysqli_query($con, $sqld);

            
            if (mysqli_num_rows($resd) > 0) {
                while ($rowd = mysqli_fetch_assoc($resd)) {
                    $eid = $rowd['event_id'];
                    $edate  = date("jS M Y", strtotime($rowd['event_date']));;
                    echo "<tr>";
                    echo "<td class='text-white text-center'>{$edate}</td>";
                    echo "<td class='text-white text-center'>{$rowd['event_name']}</td>";
                    echo "<td class='text-white text-center'>{$rowd['c_name']}</td>";
                    echo "<td class='text-white text-center'>{$rowd['c_phone']}</td>";
                    echo "<td class='text-white text-center'><input type='hidden' name='eid' value='{$eid}'><textarea class='form-control comment-e' placeholder='Where is Your Data ?' style='height: 80px'>{$rowd["event_note"]}</textarea></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5' class='text-center'>no data</td></tr>";
            }
            ?>

        </tbody>
    </table>
</div>

<script>
$(".comment-e").change(function(e) {
    e.preventDefault();
    var eid = $(this).closest('td').find("input[name='eid']").val();
    var notes = $(this).val();
    $.ajax({
        type: "post",
        url: "data-ajax.php",
        data: {
            updatenotes: true,
            notes: notes,
            eid: eid,
        },
        success: function(response) {
            if (response == 0) {
                alert("something went wrong");
            }
        }
    });
});
</script>

<?php
include_once("../include/footer.php");
?>