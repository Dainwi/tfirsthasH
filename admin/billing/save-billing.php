<?php
session_start();
include_once('../../config.php');



if (isset($_POST['general_bill'])) {
    // echo "<pre>";
    // print_r($_POST);
    // echo "</pre>";

    $client_name = mysqli_real_escape_string($con, $_POST['client_name']);
    $client_number = mysqli_real_escape_string($con, $_POST['client_number']);
    $client_address = mysqli_real_escape_string($con, $_POST['client_address']);
    $tax = mysqli_real_escape_string($con, $_POST['tax']);
    $tax_amount = mysqli_real_escape_string($con, $_POST['tax_amount']);
    $sub_total = mysqli_real_escape_string($con, $_POST['sub_total']);
    $total_amount = mysqli_real_escape_string($con, $_POST['total_amount']);

    $statusType = $_POST['statusType'];
    $amountPaid = $_POST['amount-paid'];
    $amountDue = $_POST['amount-due'];

    $userID = $_SESSION['user_id'];


    $sql2 = "INSERT INTO general_billing(gb_client,gb_phone,gb_address,gb_sub_total,gb_tax,gb_tax_amount,gb_total,gb_type,gb_paid,gb_due,cus_id) VALUES('$client_name','$client_number','$client_address','$sub_total','$tax','$tax_amount','$total_amount','$statusType','$amountPaid','$amountDue','$userID')";
    $res2 = mysqli_query($con, $sql2);

    if ($res2) {
        $last_id1 = mysqli_insert_id($con);
        $product1 = $_POST['product'];
        $qty1 = $_POST['qty'];
        $price1 = $_POST['price'];
        $total1 = $_POST['total'];

        foreach ($product1 as $index => $products) {

            $q_product = $products;
            $q_quantity = $qty1[$index];
            $q_price = $price1[$index];
            $q_total = $total1[$index];

            $sql3 = "INSERT INTO gb_items(gb_item_name,general_billing_id,gb_price) VALUES('$q_product','$last_id1','$q_total')";
            $result3 = mysqli_query($con, $sql3);
        }
        if ($result3) {
            echo "<script>window.location=document.referrer</script>";
        } else {
            echo "<script>window.location=document.referrer</script>";
        }
    } else {
        echo "something went wrong";
    }
}


if (isset($_POST['btn_del_billing'])) {
    $billid = $_POST['billid'];

    $sql = "DELETE FROM general_billing WHERE gb_id={$billid};";
    $sql .= "DELETE FROM gb_items WHERE general_billing_id = {$billid}";

    $res = mysqli_multi_query($con, $sql);
    if ($res) {
        echo "<script>window.location=document.referrer;</script>";
    } else {
        echo "<script>window.location=document.referrer;</script>";
    }
}

if (isset($_POST['billingviewmodal'])) {
    $billid = $_POST['billid'];
    $response = "";
    $sql = "SELECT * FROM general_billing WHERE gb_id={$billid}";
    $res = mysqli_query($con, $sql);
    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            $client = $row['gb_client'];
            $phone = $row['gb_phone'];
            $address = $row['gb_address'];
            $date = $row['gb_date'];
            $subtotal = $row['gb_sub_total'];
            $taxamount = $row['gb_tax_amount'];
            $total = $row['gb_total'];
            $paid = $row['gb_paid'];
            $due = $row['gb_due'];
        }

        $response .= "<div style='background-color:#181821;'><div class='container px-5 py-4' style='background-color:#181821;'><div class='d-flex justify-content-between'><span><img class='img-fluid d-inline' style='width:45px' src='{$url}/assets/images/logo/logo.png' /><h4 class='text-warning d-inline mt-3 mx-3'>FIRST HASH</h4></span><h3 class='text-light'>INVOICE</h3></div></div>";
        $response .= "<div class='container px-5 py-4 mb-0 ' style='background-color:#181821;'><div class='col-6' style=' border-left: 1px solid #3d3d3d !important;'>
                    <h6 class='ms-2 pb-0 mb-0 text-info'>INVOICE TO:</h6>
                    <h5 class='ms-2 pt-0 pb-0 mb-3 mt-0 text-info'>{$client}, {$address}</h5>
                    <h5 class='ms-2 pb-0 mb-0 text-info'>Date : {$date}</h5>
                </div></div>";

        $response .= "<div class='container mt-0' style='background-color:#181821;'><div class='table-responsive mt-5 mx-4'>
                    <table class='table table-bordered table-sm'><thead><th class='text-center text-info' scope='col'>ITEM</th><th class='text-center text-info' scope='col'>AMOUNT</th></thead><tbody>";

        $sql1 = "SELECT * FROM gb_items WHERE general_billing_id={$billid}";
        $res1 = mysqli_query($con, $sql1);
        if (mysqli_num_rows($res1) > 0) {
            while ($row1 = mysqli_fetch_assoc($res1)) {
                $response .= "<tr>";

                $response .= "<td class='text-center text-light'>{$row1['gb_item_name']}</td>";
                $response .= "<td class='text-center text-light'>Rs. {$row1['gb_price']}</td>";

                $response .= "</td>";
            }
        }

        $response .= "</tbody></table>
                    </div></div>
                    <div class='container card text-end mt-2 mb-5'><h6 class='text-light'><span class='text-warning'>Subtotal : </span> Rs. {$subtotal}</h6>
                    <h6 class='text-light mt-0 mb-0'><span class='text-warning'>Tax (18%) : </span> Rs. {$taxamount} </h6>
                    <h6 class='text-light mt-3 mb-0'><span class='text-warning'>Amount Paid : </span> Rs. {$paid} </h6>
                    <h6 class='text-light mt-0 mb-0'><span class='text-warning'>Amount Due : </span> Rs. {$due} </h6>

                    <h4 class='text-light my-4'><span class='text-warning'>Total : </span> Rs. {$total} </h4>
                    </div>
                    </div>";
    }
    echo $response;
}
