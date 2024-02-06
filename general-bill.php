<?php
require_once('vendor/autoload.php');
include_once("config.php");


if (isset($_GET['bp'])) {

    $bid = $_GET['bp'];

    $sql = "SELECT * FROM general_billing WHERE gb_id = {$bid}";
    $result = mysqli_query($con, $sql);
    if (mysqli_num_rows($result) > 0) {

        while ($row = mysqli_fetch_assoc($result)) {
            $clientName = $row['gb_client'];
            $cphone = $row['gb_phone'];
            $clientAddress = $row['gb_address'];
            $date = $row['gb_date'];
            $sTotal = $row['gb_sub_total'];
            $tax = $row['gb_tax'];
            $taxAmount = $row['gb_tax_amount'];
            $total = $row['gb_total'];
            $stype = $row['gb_type'];
            $paidA = $row['gb_paid'];
            $dueA = $row['gb_due'];

            if ($stype == 0) {
                $tclass = "d-none";
            } else {
                $tclass = "";
            }
        }
    }


    $html = '<div style="padding-top:1mm;"><div style="margin-top:1mm; padding-left: 2mm; margin-left:16mm;">';

    $html .= '<h5 style="font-family: sans-serif; margin-top:1mm; margin-bottom:4px; padding-top:0mm; color:#3d3d3d;">Invoice To:</h5>';

    $html .= '<p style="font-family: sans-serif; margin-top:0mm; font-size:18px; font-weight:bold; padding-top:0mm; color:#3d3d3d;">' . $clientName . '</p>';

    $html .= '<table width="100%" style="border-spacing:0px;">
    <tr>
        <td width="60%">
        <p style="font-family: sans-serif;  color:#3a3b3c;">A: ' . $clientAddress . '</p>
        <p style="font-size:4px; visibility:hidden;">..</p>
       <p style="font-family: sans-serif; margin-top:1mm; margin-bottom:2mm; font-size:14px; padding-top:0mm; color:#3a3b3c;"><span style="color:#1f1f1f">P:</span> ' . $cphone . '</p>
        </td>
        <td width="40%" style="text-align: right; padding-right:20mm; display: table-cell; vertical-align: bottom;">
        <p style="font-family: sans-serif; margin-top:0mm; margin-bottom:0mm; font-size:14px; padding-top:0mm; color:#3a3b3c;">DATE: ' . $date . '</p>

        </td>
        
    </tr>
</table>';

    $html .= '</div></div>';





    $html .= '<style> .table{ margin:17mm; border-spacing:0px; border-radius:8px; padding:0px; padding-bottom:14px;  border-bottom: 1.5px solid #3d3d3d;} 
	.trr{ padding-left:2mm; padding-right:2mm; padding-top:3mm; padding-bottom:3mm; border-radius:8em;  background: #3d3d3d; color:#fff; font-family: sans-serif; font-size:12px;}
	.text-center{ text-align:center; }
	.tr-content{ padding-top:4mm; padding-bottom: 2mm; border-top: 1.5px solid #a9a9a9; font-family: sans-serif} 
	.total-class{
		background:#3d3d3d; padding:10px; text-align:center; font-size:16px; color:#fff; font-family: sans-serif;
	}
	</style>';

    $html .= '<table class="table" width="100%">';
    $html .= '<thead><tr class="trr"> 
            <th class="text-center trr">Sl.</th>
            <th class="text-center trr">Product</th>
			<th class="text-center trr">Amount</th>';
    $html .= '</tr></thead><tbody>';

    $sql1 = "SELECT*FROM gb_items WHERE general_billing_id={$bid}";
    $res1 = mysqli_query($con, $sql1);
    if (mysqli_num_rows($res1) > 0) {
        $i = 1;
        while ($row1 = mysqli_fetch_assoc($res1)) {

            $html .= ' <tr class="" style="margin:10mm;">
					<td scope="row" class="text-center tr-content">' . $i . '</td>
					<td scope="row" class="text-center tr-content">' . $row1['gb_item_name'] . '</td>
					<td scope="row" class="text-center tr-content">Rs ' . $row1['gb_price'] . '</td>
					</tr>';
            $html .= '</tbody></table>';

            $i++;
        }
    }
    if ($stype == 0) {
    } else {
        $html .= '<table width="100%" style="margin-top:8px; pading-top:5mm;  font-family: sans-serif; border-top:1px solid #f6f6f6;">
    <tr>
        <td width="50%" style="padding:10px"></td>
        <td width="50%" style="text-align: right; padding-right:17mm"><span style="font-size: 10px; visibility:hidden;"> ...</span>
		<p style= "text-align : right; float:right; color:#3a3b3c; margin-top:4mm; padding:2mm;">Sub - Total amount: Rs.' . $sTotal . ' </p>
                                   <span style="font-size: 10px; visibility:hidden; margin:0mm; padding:0mm;"> ...</span> <small class="d-block my-0 py-0" style="display:block">CGST (9%) , </small><small class="my-0 py-0">SGST (9%)</small><p style="color:#3a3b3c; margin-top:2mm; padding-top:2mm;">Tax Amount : Rs.' . $taxAmount . ' </p><br/>
									</td>
    </tr>
</table>';
    }

    $html .= '<h3 width="28%" style="border-radius:3px;  text-align : right; float:right; margin-right:17mm;" class="total-class"><span style="font-weight: bold;">Total : </span>Rs. ' . $total . '</h3><br/><br/>';


    $html .= '<table width="100%" style="margin-top:8px; pading-top:5mm; font-weight: bold; font-family: sans-serif; border-top:1px solid #f6f6f6;">
    <tr>
        <td width="50%" style="padding:10px"></td>
        <td width="50%" style="text-align: right; padding-right:17mm"><span style="font-size: 10px; visibility:hidden;"> ...</span>
		<p style= "text-align : right; float:right; color:#3a3b3c; margin-top:4mm; padding:2mm;">Amount Paid: : Rs. ' . $paidA . ' </p>
                                   <span style="font-size: 10px; visibility:hidden; margin:0mm; padding:0mm;"> ...</span> <p style="color:#3a3b3c; margin-top:2mm; padding-top:2mm;">Amount Due: Rs. ' . $dueA . ' </p><br/>
									</td>
    </tr>
</table>';
    $html .= '<p style="margin-left:17mm; margin-top:20px; font-weight: bold; font-family: sans-serif; color:#3d3d3d;">Thank you !!</p>';



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
 
</table>');
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
