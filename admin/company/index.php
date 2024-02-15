<?php
include_once("../../config.php");

define('TITLE', 'Company Profile');
define('PAGE', 'company_profile');
define('PAGE_T', 'Company Profile');
include_once('../include/header.php');
include_once('../include/sidebar.php');
include_once('../include/top-nav.php');
// echo $_SESSION['user_id'];

$sql_company_profile = "SELECT * FROM companies WHERE cus_id={$_SESSION['user_id']}";
$res_company_profile = mysqli_query($con, $sql_company_profile);

if (mysqli_num_rows($res_company_profile) > 0) {
    while ($row_company_profile = mysqli_fetch_assoc($res_company_profile)) {
        $companyId = $row_company_profile['cus_id'];
        $companyName = $row_company_profile['company_name'];
        $companyPhone = $row_company_profile['company_phone'];
        $companyEmail = $row_company_profile['company_email'];
        $companyLogo = $row_company_profile['company_logo'];
        $companyTerms = $row_company_profile['company_terms'];
        $companyAddress = $row_company_profile['company_add'];
        $companyGST = $row_company_profile['gst'];
    }
}

// Replace 'your_table' and 'image_column' with your actual table and column names
$sql = "SELECT company_logo FROM companies WHERE cus_id={$_SESSION['user_id']}";
$result = $con->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        $imageData = $row['company_logo'];
    }
    // Set the content-type header to image/jpeg (or appropriate type)
    // header("Content-type: image/jpeg");

    $base64 = base64_encode($imageData);

    // Output the image
    // echo $imageData;
} else {
    echo "No image found";
}
?>

<!-- Your HTML content for displaying company profile -->
<div class="container mt-5">
    <div class="row g-4">
        <!-- Company Details -->
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-body">
                    <h2 class="card-title">Company Details</h2>
                    <p><strong>Company Name:</strong> <?php echo $companyName; ?></p>
                    <p><strong>Phone:</strong> <?php echo $companyPhone; ?></p>
                    <p><strong>Email:</strong> <?php echo $companyEmail; ?></p>
                    <p><strong>Address:</strong> <?php echo $companyAddress; ?></p>
                </div>
            </div>
        </div>

        <!-- Company Logo & Upload -->
        <div class="col-md-6 d-flex flex-column align-items-center">
            <h2>Company Logo</h2>
            <div class="mb-3">
                <img src="data:image/jpeg;base64,<?php echo $base64; ?>" alt="Company Logo" class="img-thumbnail rounded-circle" style="width: 150px; height: 150px;">
            </div>
            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalUploadPhoto">
                Upload Photo
            </button>
        </div>
    </div>

    <!-- Modal for Uploading Photo -->
    <div class="modal fade" id="modalUploadPhoto" tabindex="-1" aria-labelledby="modalUploadPhotoLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content card-bg">
                <div class="modal-header">
                    <h5 class="modal-title text-light" id="modalUploadPhotoLabel">Edit Company Details</h5>
                    <button type="button" class="btn-close btn-primary" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="ajax-company.php" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="fileToUpload" class="form-label text-light">Select image to upload:</label>
                            <input type="file" class="form-control" name="fileToUpload" id="fileToUpload">
                        </div>
                        <div class="text-center my-4">
                            <button type="submit" class="btn btn-primary" name="submit">Upload Image</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


    <!-- Button to open Edit Company modal -->
    <div class="container mt-4">
        <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modalEditCompany">
            Edit Company
        </button>
    </div>

    <!-- Edit Company Modal -->
    <div class="modal fade" id="modalEditCompany" tabindex="-1" role="dialog" aria-labelledby="modalEditCompanyLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content card-bg">
                <div class="modal-header text-center">
                    <h4 class="modal-title w-100 text-light font-weight-bold">Edit Company Details</h4>
                    <button type="button" class="btn-light btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"></span>
                    </button>
                </div>
                <div class="modal-body mx-3">
                    <form method="post">
                        <!-- Company details form fields go here -->
                        <!-- <div class="mb-3">
                        <label for="companyLogo" class="form-label font-weight-semibold">Company Logo:</label>
                        <input type="file" class="form-control" id="companyLogo" name="company-logo" value="<?php echo $companyLogo; ?>" required>
                    </div> -->
                        <div class="mb-3">
                            <label for="companyName" class="form-label text-light font-weight-semibold">Company Name:</label>
                            <input type="text" class="form-control" id="companyName" name="company-name" value="<?php echo $companyName; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="companyPhone" class="form-label text-light font-weight-semibold">Company Phone:</label>
                            <input type="tel" class="form-control" id="companyPhone" name="company-phone" value="<?php echo $companyPhone; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="companyEmail" class="form-label text-light font-weight-semibold">Company email:</label>
                            <input type="email" class="form-control" id="companyEmail" name="company-email" value="<?php echo $companyEmail; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="companyAddress" class="form-label text-light font-weight-semibold">Company address:</label>
                            <input type="text" class="form-control" id="companyAddress" name="company-address" value="<?php echo $companyAddress; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="companyGST" class="form-label text-light font-weight-semibold">Company GST No.:</label>
                            <input type="text" class="form-control" id="companyGST" name="company-gst" value="<?php echo $companyGST; ?>" required>
                        </div>
                        <!-- Add more fields as needed -->

                        <input type="hidden" id="companyId" name="company-id" value="<?php echo $companyId; ?>">

                        <div class="modal-footer d-flex justify-content-center">
                            <button type="submit" class="btn btn-secondary btn-tone" id="update-comp" name="update-company">Update <i class="anticon anticon-export ml-1"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#update-comp').click(function(e) {
            e.preventDefault();
            var companyId = $('#companyId').val();
            var companyName = $('#companyName').val();
            var companyPhone = $('#companyPhone').val();
            var companyEmail = $('#companyEmail').val();
            var companyAddress = $('#companyAddress').val();
            var companyGST = $('#companyGST').val();
            $.ajax({
                type: "post",
                url: "ajax-company.php",
                data: {
                    updateCompany: true,
                    companyId: companyId,
                    companyName: companyName,
                    companyPhone: companyPhone,
                    companyEmail: companyEmail,
                    companyAddress: companyAddress,
                    companyGST: companyGST
                },
                success: function(response) {
                    if (response == 1) {
                        $("#modalEditCompany").modal("hide");
                        window.setTimeout(function() {
                            location.reload();
                        }, 1500);
                    } else {
                        alert("update failed")
                    }
                }
            });
        });
    });

    $(document).ready(function() {
        $('#img-upload').click(function(e) {
            e.preventDefault();
            var formData = new FormData();
            var fileInput = $('#fileToUpload')[0].files[0];
            formData.append('submit', true);
            formData.append('fileToUpload', fileInput);

            $.ajax({
                type: "POST",
                url: "ajax-company.php",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response == 1) {
                        window.setTimeout(function() {
                            location.reload();
                        }, 1500);
                    } else {
                        alert("update failed")
                    }
                }
            });
        });
    });
</script>

<?php
include_once('../include/footer.php');
?>
