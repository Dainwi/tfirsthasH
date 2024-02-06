<?php
require_once('vendor/autoload.php');
include_once("config.php");

if (isset($_GET['bp'])) {
    $bpid = $_GET['bp'];
    $sql = "SELECT * FROM billing_db WHERE billing_id={$bpid}";
    $res = mysqli_query($con, $sql);
    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            $projid = $row['b_project_id'];
            $type = $row['b_type'];
            $date = $row['b_date'];
            $amount = $row['b_amount'];
        }
    } else {
        echo "no data found";
    }
    if ($type == 0) {
        $text = "One Time Payment";
    } elseif ($type == 1) {
        $text = "Advance Payment";
    } elseif ($type == 2) {
        $text = "Mid Payment";
    } elseif ($type == 3) {
        $text = "Final Settlement";
    }

    $sql1 = "SELECT * FROM projects_db LEFT JOIN clients_db ON projects_db.project_client = clients_db.c_id WHERE project_id ={$projid}";
    $res1 = mysqli_query($con, $sql1);
    if (mysqli_num_rows($res1) > 0) {
        while ($row = mysqli_fetch_assoc($res1)) {
            $cname = $row['c_name'];
            $cemail = $row['c_email'];
            $cphone = $row['c_phone'];
            $caddress = $row['c_address'];
            $event = $row['project_event'];
        }
    }

    $html = '<div style="padding-top:1mm;"><div style="margin-top:1mm; padding-left: 2mm; margin-left:16mm;">';

    $html .= '<h5 style="font-family: sans-serif; margin-top:0px; margin-bottom:4px; padding-top:0mm; color:#3d3d3d;">Invoice To:</h5>';

    $html .= '<p style="font-family: sans-serif; margin-top:0mm; font-size:18px; font-weight:bold; padding-top:0mm; color:#3d3d3d;">' . $cname . '</p>';

    $html .= '<table width="100%" style="border-spacing:0px;">
    <tr>
        <td width="60%">
        <p style="font-family: sans-serif;  color:#3a3b3c;">A: ' . $caddress . '</p><p style="font-size:4px; visibility:hidden;">..</p>
        <p style="font-family: sans-serif; margin-top:2mm; margin-bottom:2mm; font-size:14px; padding-top:8px; color:#3a3b3c;"><span style="color:#1f1f1f">E:</span> ' . $cemail . '</p>
        <p style="font-size:4px; visibility:hidden;">..</p>
       <p style="font-family: sans-serif; margin-top:1mm; margin-bottom:2mm; font-size:14px; padding-top:0mm; color:#3a3b3c;"><span style="color:#1f1f1f">P:</span> ' . $cphone . '</p>
        </td>
        <td width="40%" style="text-align: right; padding-right:20mm; display: table-cell; vertical-align: bottom;">
        <p style="font-family: sans-serif; margin-top:0mm; margin-bottom:0mm; font-size:14px; padding-top:0mm; color:#3a3b3c;">DATE: ' . $date . '</p>
        <p style="font-size:4px; visibility:hidden;">..</p><p style="font-family: sans-serif; margin-top:1mm; margin-bottom:2mm; font-size:14px; padding-top:0mm; color:#3a3b3c;"><span style="color:#1f1f1f">EVENT:</span> ' . $event . '</p>
        </td>
        
    </tr>
</table>';

    $html .= '</div></div>';



    $html .= '<style> .table{ margin:17mm; border-spacing:0px; border-radius:8px; padding:0px;} 
	.trr{ padding-left:2mm; padding-right:2mm; padding-top:3mm; padding-bottom:3mm; border-radius:8em;  background: #3d3d3d; color:#fff; font-family: sans-serif; font-size:12px;}
	.text-center{ text-align:center; }
	.tr-content{ padding-top:4mm; padding-bottom: 2mm; border-top: 1.5px solid #a9a9a9; font-family: sans-serif} 
	.total-class{
		background:#3d3d3d; padding:10px; text-align:center; font-size:16px; color:#fff; font-family: sans-serif;
	}
	</style>';

    $html .= '<table class="table" width="100%" style=" padding-bottom:14px;  border-bottom: 1.5px solid #3d3d3d;">';
    $html .= '<thead><tr class="trr"> 
            <th class="text-center trr">Item</th>
            <th class="text-center trr">Amount</th>';
    $html .= '</tr></thead><tbody>';
    $html .= ' <tr class="" style="margin:10mm;">
					<td scope="row" class="text-center tr-content">' . $text . '</td>
					<td scope="row" class="text-center tr-content">' . $amount . '</td>
					</tr>';
    $html .= '</tbody></table>';

    $html .=
        '<h3 width="28%" style="border-radius:3px;  text-align : right; float:right; margin-right:17mm;" class="total-class"><span style="font-weight: bold;">Total : </span>Rs. ' . $amount . '</h3>';
    $html .= '<p style="margin-left:17mm; margin-top:60px; font-weight: bold; font-family: sans-serif; color:#3d3d3d;">Thank you !!</p>';


    $sql4 = "SELECT * FROM terms_condition";
    $res4 = mysqli_query($con, $sql4);
    if (mysqli_num_rows($res4) > 0) {
        //terms page 
        $html .= '<h4 style="margin-left:15mm; padding-top:6mm;">TERMS &amp; CONDITIONS:</h4>';
        $html .= '<ul style="margin-left:8mm; margin-right:10mm; ">';

        while ($row4 = mysqli_fetch_assoc($res4)) {
            $html .= "<li style='padding-bottom:2mm; font-size:12px; color:#3a3b3c;'>{$row4['terms_details']}</li>";
        }
        $html .= "</ul>";
    }

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
        <td width="50%" style="text-align: right; padding-bottom:13px; padding-right:20px; display: table-cell; vertical-align: bottom;"><h2 style="color:#3d3d3d; font-size:32px; padding:5px;">INVOICE</h2></td>
    </tr>
</table>

');
    $mpdf->SetHTMLFooter('
<table width="100%" style=" font-family: sans-serif; border-spacing:0px;">
   
</table>
       ');
    $mpdf->WriteHTML($html);

    $file = 'invoice/' . time() . '.pdf';
    $mpdf->output($file, 'I');
    //D
    //I
    //F
    //S


} else {
    echo "something went wrong";
}
