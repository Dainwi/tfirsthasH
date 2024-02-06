<?php
require_once('vendor/autoload.php');
include_once("config.php");
if (isset($_GET['qproject'])) {
    $projid = $_GET['qproject'];
    $sqlq = "SELECT * FROM quotation_db WHERE q_project_id={$projid}";
    $resq = mysqli_query($con, $sqlq);
    if (mysqli_num_rows($resq) > 0) {
        while ($rowq = mysqli_fetch_assoc($resq)) {
            $q_id = $rowq['q_id'];
            $q_date = $rowq['q_date'];
            $q_photobook = $rowq['q_photobook'];
            $extra_deliverables = $rowq['extra_deliverables'];
            $package_cost = $rowq['q_package_cost'];
            $addon_cost = $rowq['q_addons_cost'];
            $grand_total = $rowq['grand_total'];
        }
    }
    $sql = "SELECT * FROM projects_db LEFT JOIN clients_db ON projects_db.project_client = clients_db.c_id WHERE project_id ={$projid}";
    $res = mysqli_query($con, $sql);
    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            $cname = $row['c_name'];
            $caddress = $row['c_address'];
            $event = $row['project_event'];
            $cemail = $row['c_email'];
            $cphone = $row['c_phone'];
        }
    }


    $html = '<div style="padding-top:1mm;"><div style="margin-top:1mm; padding-left: 2mm; margin-left:14mm; margin-right-14mm">';

    $html .= '<h5 style="font-family: sans-serif; margin-top:0px; margin-bottom:4px; padding-top:0mm; color:#3d3d3d;">Quotation To:</h5>';

    $html .= '<p style="font-family: sans-serif; margin-top:0mm; font-size:18px; font-weight:bold; padding-top:0mm; color:#3d3d3d;">' . $cname . '</p>';

    $html .= '<table width="100%" style="border-spacing:0px; margin-right:10mm;">
    <tr>
        <td width="60%">
        <p style="font-family: sans-serif;  color:#3a3b3c;">A: ' . $caddress . '</p><p style="font-size:4px; visibility:hidden;">..</p>
        <p style="font-family: sans-serif; margin-top:2mm; margin-bottom:2mm; font-size:14px; padding-top:8px; color:#3a3b3c;"><span style="color:#1f1f1f">E:</span> ' . $cemail . '</p>
        <p style="font-size:4px; visibility:hidden;">..</p>
       <p style="font-family: sans-serif; margin-top:1mm; margin-bottom:2mm; font-size:14px; padding-top:0mm; color:#3a3b3c;"><span style="color:#1f1f1f">P:</span> ' . $cphone . '</p>
        </td>
        <td width="40%" style="text-align: right; padding-right:10mm; display: table-cell; vertical-align: bottom;">
        <p style="font-family: sans-serif; margin-top:0mm; margin-bottom:0mm; font-size:14px; padding-top:0mm; color:#3a3b3c;">DATE: ' . $q_date . '</p>
        <p style="font-size:4px; visibility:hidden;">..</p><p style="font-family: sans-serif; margin-top:1mm; margin-bottom:2mm; font-size:14px; padding-top:0mm; color:#3a3b3c;"><span style="color:#1f1f1f">EVENT:</span> ' . $event . '</p>
        </td>
        
    </tr>
</table>';

    $html .= '</div></div>';

    $html .= '<style> .table{ border-spacing:0px; border-radius:8px; padding:0px; margin-bottom:10px; margin-left:6mm; margin-right:10mm;} 
	.trr{ text-align:left; border: 1px solid #ffbd59; padding-left:2mm; padding-right:2mm; padding-top:3mm; padding-bottom:3mm; border-radius:8em;  background: #ffbd59; color:#3d3d3d; font-family: sans-serif; font-size:11px;}
	
	.tr-content{ padding-top:2mm; padding-bottom: 2mm; padding-left:8px; border-top: 1px solid #a9a9a9; font-family: sans-serif; font-size:12px;} 
	.total-class{
		background:#3d3d3d; padding:10px; font-size:16px; color:#fff; font-family: sans-serif;
	}
	</style>';

    $html .= '<h5 style="padding-left: 2mm; margin-left:14mm; font-family: sans-serif; margin-top:20px; margin-bottom:4px; padding-top:0mm; color:#3d3d3d;">EVENT DETAILS:</h5>';

    $html .= '<div style="padding-top:1mm;"><div style="margin-left:9mm; margin-right:9mm;">';
    $sql1 = "SELECT * FROM events_db WHERE event_pr_id = {$projid}";
    $res1 = mysqli_query($con, $sql1);
    if (mysqli_num_rows($res1) > 0) {

        while ($row1 = mysqli_fetch_assoc($res1)) {
            $eve = strtotime($row1['event_date']);
            $eventdate = date("jS M Y", $eve);
            $eid = $row1['event_id'];
            $gather = $row1['event_gathering'];
            if ($gather == 0 || $gather == "") {
                $gathering = "";
            } else {
                $gathering = $gather;
            }
            $html .= "<table class='table' width='100%' style=' padding-bottom:14px; border: 0.2px solid #a9a9a9;'>
                            <thead>
                               <tr class='trr'>
                                    <th class='trr'>{$row1['event_name']} | {$eventdate} | Gathering: {$gathering} | {$row1['event_venue']}</th>
                                </tr>
                            </thead>
                            <tbody>";
            $html .= '<tr style="margin:10mm;">';


            $sqlr = "SELECT * FROM event_requirements LEFT JOIN employee_type ON event_requirements.er_type = employee_type.type_id WHERE er_event=$eid";
            $resr = mysqli_query($con, $sqlr);
            $result_array = array();
            if (mysqli_num_rows($resr) > 0) {

                while ($rowr = mysqli_fetch_assoc($resr)) {
                    $require = $rowr["type_name"];
                    array_push($result_array, $require);
                }
                $req = implode(', ', $result_array);
            }
            $html .= "<td scope='row' class=' tr-content'>Requirements: <br>{$req}</td>";

            $html .= "</tr>";
            $html .= '<tr style="margin:10mm;">';
            $sqldeliv = "SELECT * FROM event_deliverables WHERE ed_eventid={$eid}";
            $resdeliv = mysqli_query($con, $sqldeliv);
            $deliv_array = array();
            if (mysqli_num_rows($resdeliv) > 0) {

                while ($rowdeliv = mysqli_fetch_assoc($resdeliv)) {
                    $deliv = $rowdeliv["ed_deliverables"];
                    array_push($deliv_array, $deliv);
                }
                $deli = implode(', ', $deliv_array);
            }
            $html .= "<td scope='row' class=' tr-content'>Deliverables: <br>{$deli}</td>";
            $html .= "</tr>";
            $html .= "</tbody></table>";
        }
    }
    $html .= '</div></div>';

    $html .= "<div style='margin-left:15mm; margin-right:15mm; font-family: sans-serif;'>";
    if ($q_photobook != "") {
        $html .= "<h5 style='margin-bottom:4px; font-family: sans-serif; color:#3a3b3c;'>PHOTOBOOK : {$q_photobook}</h5>";
    }
    $html .= "</div>";

    if ($extra_deliverables != "") {
        $html .= "<table class='table' width='100%' style='margin-left:15mm; margin-right:18mm; margin-top:12px; padding-bottom:14px; border: 0.2px solid #a9a9a9;'>
                            <thead>
                               <tr style='text-align:left;'>
                                    <th class='tr-content' style='text-align:left; color:#3d3d3d;'>EXTRA DELIVERABLES <br> {$extra_deliverables}</th>
                                </tr>
                            </thead>
                            </table>";
    }



    $sql_add = "SELECT * FROM addons_db WHERE add_quotation_id ={$q_id}";
    $res_add = mysqli_query($con, $sql_add);
    if (mysqli_num_rows($res_add) > 0) {
        $html .= "<div style='margin-left:14mm; margin-right:14mm; font-family: sans-serif; margin-top:6mm;'>";
        $html .= "<h5 style='margin-bottom:4px; font-family: sans-serif; color:#3a3b3c;'>EXPENSE DETAILS :</h5>";
        while ($row_add = mysqli_fetch_assoc($res_add)) {
            $addid = $row_add['add_id'];

            $html .= "<ul style='margin-top:1px; padding-left:12px; font-family: sans-serif;'>";
            $html .= "<li>{$row_add['add_title']} (Rs. {$row_add['add_price']})</li>";
            $html .= "<ol>";
            $sql_add_items = "SELECT * FROM addons_items WHERE addons_id ={$addid}";
            $res_add_items = mysqli_query($con, $sql_add_items);
            if (mysqli_num_rows($res_add_items) > 0) {
                while ($row_add_items = mysqli_fetch_assoc($res_add_items)) {
                    $html .= "<li>{$row_add_items['addons_items_desc']}</li>";
                }
            }
            $html .= "</ol>";

            $html .= "</ul>";
        }
        $html .= "</div>";
    }


    $html1 .= '<span style="font-size: 10px; visibility:hidden;"> ...</span><table width="100%" style="margin-top:8px; pading-top:5mm; font-weight: bold; font-family: sans-serif; border-top:1px solid #f6f6f6;">
    <tr>
        
        <td width="50%" style="text-align: left; padding-left:17mm;"><h3 style="font-family: sans-serif; margin-bottom:4mm; margin-top:12mm; margin-left:17mm; margin-right:17mm; padding-bottom:0mm;">Quotation :</h3><span style="font-size: 18px; visibility:hidden;"> ...</span>
		<p style= " color:#3a3b3c; margin-top:4mm; padding:2mm;">Package Cost : Rs. ' . $package_cost . '</p>
                                   <span style="font-size: 10px; visibility:hidden; margin:0mm; padding:0mm;"> ...</span> <p style="color:#3a3b3c; margin-top:2mm; padding-top:2mm;">AddOns : Rs ' . $addon_cost . ' </p><br/>
									</td>
                                    <td width="50%" style="padding:10px"></td>
    </tr>
</table>
<h3 width="28%" style="border-radius:3px;  text-align : left; float:left; margin-left:17mm;" class="total-class"><span style="font-weight: bold;">Total : </span>Rs. ' . $grand_total . '</h3>';

    $html1 .= '<h4 style="width:100%; margin-top:20mm; margin-left:17mm; text-align:left; float:left; font-weight: bold; font-family: sans-serif; color:#3d3d3d;">Thank you !!</h4>';


    $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4', 'margin_left' => 0, 'margin_right' => 0, 'margin_top' => 0, 'margin_bottom' => 0, 'margin_header' => 0, 'margin_footer' => 0]); //use this customization

    $mpdf->setAutoTopMargin = 'stretch';
    $mpdf->setAutoBottomMargin = 'stretch';

    $mpdf->SetHTMLHeader('
     <table width="100%" style=" font-family: sans-serif; border-spacing:0px;">
    <tr>
        <td width="50%" style="padding:15px; background:#3d3d3d; "></td>
        <td width="50%" style="text-align: right; padding-right:20px; background:#ffbd59; ">
        <p style="font-size:10px;">https://firsthash.in</p></td>
    </tr>
</table>
<table width="98%" style="margin-left:15mm; margin-right:15mm; margin-top:4mm; background:#ffffff; font-weight: bold; font-family: sans-serif; border-bottom:1.5px solid #585858;">
    <tr>
        <td width="50%" style="padding:5px"><img class="img-fluid" style="width:110px; height:70px; " src="assets/images/logo/logo_firsthash.jpg" /></td>
        <td width="50%" style="text-align: right; padding-bottom:13px; padding-right:20px; display: table-cell; vertical-align: bottom;"><h2 style="color:#3d3d3d; font-size:32px; padding:5px;">QUOTATION</h2></td>
    </tr>
</table>

');
    $mpdf->SetHTMLFooter('
<table width="100%" style=" font-family: sans-serif; border-spacing:0px;">
   
</table>
       ');

    $mpdf->WriteHTML($html);
    $mpdf->AddPage();
    $mpdf->WriteHTML($html1);
    $file = 'invoice/' . time() . '.pdf';
    $mpdf->output($file, 'I');
    //D
    //I
    //F
    //S
} else {
    echo "something went wrong";
}