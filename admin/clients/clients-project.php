<?php
include_once("../../config.php");

if (isset($_POST['projectData'])) {
    $clid = $_POST['clid'];
    $output = "";

    $output .= "<div class='table-responsive'>
        <table class='table'>
            <thead>
                <tr>
                    <th><small class='font-weight-semibold text-info'>Name</small></th>
                    <th><small class='font-weight-semibold text-info'>Event</small></th>
                    <th><small class='font-weight-semibold text-info'>Date</small></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>";
    $sql = "SELECT * FROM projects_db WHERE project_client={$clid}";
    $res = mysqli_query($con, $sql);
    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            $ldate = $row['project_ldate'];
            if ($ldate == "0000-00-00") {
                $date1 = date_create($row['project_sdate']);
                $date = date_format($date1, "d M Y");
            } else {
                $date2 = date_create($row['project_sdate']);
                $dat1 = date_format($date2, "d M Y");
                $date3 = date_create($row['project_ldate']);
                $dat2 = date_format($date3, "d M Y");
                $date = $dat1 . " - " . $dat2;
            }

            $project = $row['project_status'];
            if ($project == 0) {
                $prview = "{$url}/admin/projects/view-pending-project.php?prid={$row['project_id']}";
            } elseif ($project == 1) {
                $prview = "{$url}/admin/projects/view-ongoing-project.php?prid={$row['project_id']}";
            } else {
                $prview = "{$url}/admin/projects/completed-project-details.php?prid={$row['project_id']}";
            }

            $output .= "<tr>
            <td class='text-light'>{$row['project_name']}</td>
             <td class='text-light'>{$row['project_event']}</td>
              <td class='text-light'><small>{$date}</small></td>
              <td><a href='{$prview}' class='btn btn-outline-primary btn-sm'>View</a></td>
            </tr>";
        }
    } else {
        $output .= "<tr><td>no data found</td></tr>";
    }
    $output .= "</tbody></table></div>";

    echo $output;
}
