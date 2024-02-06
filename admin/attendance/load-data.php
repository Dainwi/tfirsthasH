<?php
include_once("../../config.php");

if (isset($_POST['dataload'])) {
    $userId = $_POST['userId'];
    $year = $_POST['year'];
    $month = $_POST['month'];
    $output = "";
    $sql = "SELECT * FROM attendance_db WHERE month(a_date)='$month' AND year(a_date)='$year' AND e_id={$userId} ORDER BY a_date ASC";
    $res = mysqli_query($con, $sql);
    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            $time = $row['a_time'];
            $otime = $row['out_time'];
            $status = $row["a_status"];
            $date = date_create($row["a_date"]);
            $dat = date_format($date, "d M-Y");
            if ($status == 0) {
                $sta = "<span class='text-danger'>On Leave</span>";
            } elseif ($status == 1) {
                $sta = "<span class='text-success'>Present</span>";
            } else {
                $sta = "holiday";
            }
            if ($time == 0) {
                $tim = "";
            } else {
                $tim = $time;
            }
            if ($otime == 0) {
                $timo = "";
            } else {
                $timo = $otime;
            }
            $output .= "<tr>
            <td class='text-center text-info'>{$dat}</td>
            <td class='text-center text-info'>{$tim}</td>
            <td class='text-center text-info'>{$timo}</td>
            <td class='text-center text-info'>{$sta}</td>
            </tr>";
        }
    } else {
        $output .= "<tr> <td class='text-center' colspan='3'>no data found</td> </tr>";
    }

    echo $output;
}
