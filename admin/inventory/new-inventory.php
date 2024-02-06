<?php

include_once("../../config.php");

session_start();

if (isset($_POST['inventory'])) {
    // echo "<pre>";
    // print_r($_POST);

    $pname = $_POST['p-name'];

    if (isset($_POST['amount']) && $_POST['amount'] != "") {
        $pamount = $_POST['amount'];
    } else {
        $pamount = 0;
    }

    // if (isset($_POST['date']) && $_POST['date'] != "") {
    //     $date = $_POST['date'];
    // } else {
    //     $date = date("0000-00-00");
    // }
    $date = $_POST['date'];



    if (isset($_POST['assign-to']) && $_POST['assign-to'] != "") {
        $assign = $_POST['assign-to'];
    } else {
        $assign = "N/A";
    }

$cusID = $_SESSION['user_id'];

    $sql = "INSERT INTO inventory_db(product_name,product_amount,assign_to,i_date, cus_id) VALUES('$pname',$pamount ,'$assign','$date', '$cusID')";
    $res = mysqli_query($con, $sql);

    if ($res) {
        $lastid = mysqli_insert_id($con);

        if (isset($_POST['info1'])) {

            $info1 = $_POST['info1'];
            $info2 = $_POST['info2'];
            $info3 = $_POST['info3'];
            foreach ($info1 as $index => $infos) {
                $info_1 = $infos;
                $info_2 = $info2[$index];
                if ($info_2 == "") {
                    $info_2 = "0";
                }
                $info_3 = $info3[$index];
                if ($info_3 == "") {
                    $info_3 = "0";
                }

                $sql3 = "INSERT INTO inventory_item(item_inventory,info_1,info_2,info_3)VALUES('$lastid','$info_1','$info_2','$info_3')";
                $res3 = mysqli_query($con, $sql3);
            }

            if ($res3) {
                echo "<script>window.location=document.referrer;</script>";
            } else {
                echo "<script>window.location=document.referrer;</script>";
            }
        } else {
            echo "<script>window.location=document.referrer;</script>";
        }
    } else {
        echo "<script>window.location=document.referrer;</script>";
    }
}

if (isset($_POST['btn_del_inventory'])) {
    $inidd = $_POST['inid'];

    $sql  = "DELETE FROM inventory_item WHERE item_inventory={$inidd};";
    $sql .= "DELETE FROM inventory_db WHERE inventory_id={$inidd}";
    $res = mysqli_multi_query($con, $sql);

    if ($res) {
        echo "<script>window.location=document.referrer;</script>";
    } else {
        echo "<script>window.location=document.referrer;</script>";
    }
}

if (isset($_POST['viewin'])) {
    $invid = $_POST['inid'];
    $output = "";
    $sql = "SELECT * FROM inventory_db WHERE inventory_id ={$invid}";
    $res = mysqli_query($con, $sql);
    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            $pname = $row['product_name'];
            $pdate = $row['i_date'];
            $pamount = $row['product_amount'];
            $passign = $row['assign_to'];
        }
        $output .= "<h4 class='text-light'>{$pname}</h4>";
        $output .= "<h6 class='text-light'>Price : Rs. {$pamount}</h6>";
        $output .= "<h6 class='text-light'>Date : {$pdate}</h6>";
        $output .= "<h6 class='text-light'>Assign to : {$passign}</h6>";
        $output .= "<p class='border-bottom py-2'></p>";

        $sql2 = "SELECT * FROM inventory_item WHERE item_inventory={$invid}";
        $res2 = mysqli_query($con, $sql2);
        if (mysqli_num_rows($res2) > 0) {
            $im = 1;
            $output .= "<small class='text-info'>more info :</small>";
            $output .= "<div class='table-responsive'>
    <table class='table table-sm table-borderless'>
        <tbody>";
            while ($row2 = mysqli_fetch_assoc($res2)) {
                if ($row2['info_2'] == '0') {
                    $info2 = "";
                } else {
                    $info2 = $row2['info_2'];
                }

                if ($row2['info_3'] == '0') {
                    $info3 = "";
                } else {
                    $info3 = $row2['info_3'];
                }
                $output .= "<tr>
                <th scope='row'>{$im}</th>
                <td>{$row2['info_1']}</td>
                <td>{$info2}</td>
                <td>{$info3}</td>
            </tr>
            ";
                $im++;
            }
            $output .= "</tbody>
    </table>
</div>";
        }
    }
    echo $output;
}
