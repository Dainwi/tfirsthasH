<?php
include_once("../../config.php");
session_start();

if (isset($_POST['updateCompany'])) {
    // Process the form data and update the company details in the database
    $companyId = mysqli_real_escape_string($con, $_POST['companyId']);
    $companyName = mysqli_real_escape_string($con, $_POST['companyName']);
    $companyPhone = mysqli_real_escape_string($con, $_POST['companyPhone']);
    $companyEmail = mysqli_real_escape_string($con, $_POST['companyEmail']);
    $companyAddress = mysqli_real_escape_string($con, $_POST['companyAddress']);
    $companyGST = mysqli_real_escape_string($con, $_POST['companyGST']);

    // Update the company details in the database
    $sql_update_company = "UPDATE companies SET 
                            company_name = '$companyName', 
                            company_phone = '$companyPhone', 
                            company_email = '$companyEmail', 
                            company_add = '$companyAddress', 
                            gst = '$companyGST'
                            WHERE cus_id = '$companyId'";

    if (mysqli_query($con, $sql_update_company)) {
      $response = 1;
    } else {
       $response = 0;
    }
    echo $response;
}

if (isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    $cusID = $_SESSION['user_id'];
    if ($check !== false) {
        $image = $_FILES['fileToUpload']['tmp_name'];
        $imgContent = addslashes(file_get_contents($image));

        // Insert image content into database
        // Assuming $imgContent contains the image data
        // Assuming $companyId contains the customer ID
        $sql_image_query = "UPDATE companies SET company_logo = '$imgContent' WHERE cus_id = '$cusID'";
        $update = mysqli_query($con, $sql_image_query);
        if ($update) {
           $response =1;
        } else {
            $response =0;
        }
        echo $response;
    } else {
        echo "Please select an image file to upload.";
    }
}