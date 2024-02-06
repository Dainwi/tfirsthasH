<?php
include "../includes/header.php";
include "../includes/sidebar.php";
include "../includes/top-nav.php";
include '../config.php';

// Handle form submission for adding or updating coupon
if (isset($_POST['addcoupon'])) {
    $cou_name = $_POST['cou_name'];
    $cou_price = $_POST['cou_price'];

    $sql = "INSERT INTO coupon (cou_name, cou_price) VALUES ('$cou_name', '$cou_price')";

    $result = mysqli_query($con, $sql);

    if ($result) {
        // header("location: index.php");
        exit(0);
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($con);
    }

}

if (isset($_POST['updateCoupon'])) {
    $edit_coupon_id = $_POST['edit_coupon_id'];
    $cou_name = $_POST['cou_name_updated'];
    $cou_price = $_POST['cou_price_updated'];

    $sql = "UPDATE coupon SET cou_name='$cou_name', cou_price='$cou_price' WHERE cou_id=$edit_coupon_id";

    $result = mysqli_query($con, $sql);

    if ($result) {
        // header("location: index.php");
        exit(0);
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($con);
    }
}

// Fetch all coupons
$sql = "SELECT * FROM coupon";
$res = mysqli_query($con, $sql);

?>

<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <h5 class="card-title">All Coupons</h5>
                    <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#addCoupon">
                        <i data-feather="plus"></i> Add new coupon
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Coupon ID</th>
                                <th scope="col">Name</th>
                                <th scope="col">Price</th>
                                <th scope="col">Status</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            $ii = 1;
                            while ($row = mysqli_fetch_assoc($res)) {
                                if ($row['cou_status']) {
                                    $active = 'active';
                                } else {
                                    $active = 'Deactive';
                                }
                                ?>
                                <tr>
                                    <th scope="row">
                                        <?php echo $ii; ?>
                                    </th>
                                    <td>
                                        <?php echo htmlspecialchars($row['cou_name']); ?>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($row['cou_price']); ?>
                                    </td>
                                    <td>
                                        <a type='button' class='btn btn-<?php echo ($active == 'active')?'success':'danger'; ?> update-status'
                                            status-id="<?php echo $row['cou_status'] ?>"
                                            data-id="<?php echo $row['cou_id'] ?>">
                                            <?php echo $active ?>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="#" class="edit-coupon" data-bs-toggle="modal"
                                            data-bs-target="#updateCoupon" data-id="<?php echo $row['cou_id']; ?>"><i
                                                class="fas fa-edit"></i></a>
                                        <a href="#" class ="del-coupon" data-id="<?php echo $row['cou_id']; ?>"><i
                                                class="fas fa-trash text-danger"></i></a>
                                    </td>
                                </tr>
                                <?php
                                $ii++;
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Coupon Modal -->
<div class="modal" id="addCoupon" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <!-- ... (Add the content of the Add Coupon Modal) ... -->
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content card-bg">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Add Plan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST">
                    <div class="mb-3">
                        <div class="form-floating">
                            <input required id="cou_name" type="text" class="form-control" name="cou_name"
                                placeholder="Fullname">
                            <label for="cou_name">Copoun name</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-floating">
                            <input required id="cou_price" name="cou_price" type="text" class="form-control"
                                placeholder="₹">
                            <label for="cou_price">Price</label>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="addcoupon" class="btn btn-success">Save changes</button>
                    </div>
                </form>

            </div>

        </div>
    </div>
</div>

<!-- Update Coupon Modal -->
<div class="modal" id="updateCoupon" tabindex="-1" aria-labelledby="updateCoupon" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content card-bg">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Update Coupon</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST">
                    <input type="hidden" id="edit_coupon_id" name="edit_coupon_id" value="">
                    <div class="mb-3">
                        <div class="form-floating">
                            <input required id="cou_name_updated" type="text" class="form-control"
                                name="cou_name_updated" placeholder="Fullname">
                            <label for="cou_name_updated">Coupon name</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-floating">
                            <input required id="cou_price_updated" name="cou_price_updated" type="text"
                                class="form-control" placeholder="₹">
                            <label for="cou_price_updated">Price</label>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="updateCoupon" class="btn btn-success">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Add the jQuery library (if not already included) -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<!-- Add the JavaScript code for dynamic loading -->
<script>
    $(document).ready(function () {

        $('.edit-coupon').click(function () {
            var couponId = $(this).data('id');
            $('#edit_coupon_id').val(couponId);

            $.ajax({
                url: 'action.php',
                type: 'GET',
                data: {
                    id: couponId
                },
                dataType: 'json',
                success: function (data) {
                    $('#cou_name_updated').val(data.cou_name);
                    $('#cou_price_updated').val(data.cou_price);
                },
                error: function () {
                    alert('Error fetching plan details');
                }
            });
        });




    });

    $(document).ready(function () {
        $(".update-status").click(function (e) {
            e.preventDefault();

            var couId = $(this).attr('data-id');
            var statusId = $(this).attr('status-id');

            // console.log("Clicked - Coupon ID: " + couId + ", Status ID: " + statusId);

            $.ajax({
                type: "post",
                url: "action.php",
                data: {
                    updateStatus: true,
                    couId: couId,
                    statusId: statusId,
                },

                success: function (response) {
                    // console.log("Success: " + response);
                    location.reload(); // Reload the page upon success
                },
                error: function (xhr, status, error) {
                    // console.error("Error: " + error);
                    // Handle AJAX error here
                }
            });
        });
    });

    $(document).ready(function () {
        $('.del-coupon').click(function (e) { 
            e.preventDefault();

            var cId = $(this).data('id');

            $.ajax({
                type: "post",
                url: "action.php",
                data: {
                    delStatus: true,
                    cId: cId,
                },
                success: function (response) {
                    location.reload();
                }
            });


            
        });
    });


</script>



<?php
include "../includes/footer.php";
?>