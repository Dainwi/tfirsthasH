<?php
include "../includes/header.php";
include "../includes/sidebar.php";
include "../includes/top-nav.php";
?>

<?php
function checkData($data)
{
    if ($data == 30) {
        return "1 Month";
    } else if ($data == 180) {
        return "6 Months";
    } else if ($data == 365) {
        return "1 Year";
    } else if ($data == 4) {
        return "Life time";
    } else {
        return "Unknown Duration";
    }
}

?>

<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-body">
                <!-- <div class="col-6"></div> -->
                <div class="d-flex justify-content-between">
                    <h5 class="card-title">All Plans</h5>
                    <button type="button" class="btn btn-secondary" data-bs-toggle="modal"
                        data-bs-target="#exampleModalCenter">
                        <i data-feather="plus"></i> Add new plan
                    </button>
                </div>
                <?php
                include "../config.php";
                $sql = "SELECT * FROM plan";
                $res = mysqli_query($con, $sql);
                if (mysqli_num_rows($res) > 0) {
                    $ii = 1;
                }
                ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Plan ID</th>
                                <th scope="col">Name</th>
                                <th scope="col">Current Price</th>
                                <th scope="col">Offer Price</th>
                                <th scope="col">Duration</th>
                                <th scope="col">Status</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            while ($row = mysqli_fetch_assoc($res)) {
                                if ($row['p_status']) {
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
                                        <?php echo $row['p_name'] ?>
                                    </td>
                                    <td>
                                        <?php echo $row['p_price_c'] ?>
                                    </td>
                                    <td>
                                        <?php echo $row['p_price_s'] ?>
                                    </td>
                                    <td>
                                        <?php
                                        $data = $row['p_duration'];
                                        echo checkData($data);
                                        ?>
                                    </td>
                                    <td><a type='button' class='btn btn-<?php echo ($active == 'active')?'success':'danger'; ?> update-status'
                                            status-id="<?php echo $row['p_status'] ?>" data-id="<?php echo $row['p_id'] ?>">
                                            <?php echo $active ?>
                                        </a></td>

                                    <td>
                                        <a href="#" class="edit-plan" data-bs-toggle="modal" data-bs-target="#updatePlan"
                                            data-id="<?php echo $row['p_id']; ?>"><i class="fas fa-edit"></i></a>
                                        <a href="#" class="del-plan"  data-id="<?php echo $row['p_id']; ?>"><i
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

<!-- table end here -->
<!-- Add Plan Modal -->
<div class="modal" id="exampleModalCenter" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content card-bg">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Add Plan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="action.php" method="POST">
                    <div class="mb-3">
                        <div class="form-floating">
                            <input required id="p_name" type="text" class="form-control" name="p_name"
                                placeholder="Fullname">
                            <label for="p_name">Plan name</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-floating">
                            <select id="p_duration" class="form-select" name="p_duration">
                                <option selected>Choose duration</option>
                                <option value="30">1 Month</option>
                                <option value="180">6 Months</option>
                                <option value="365">1 year</option>
                                <option value="4">One time offer</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-floating">
                            <input required id="p_price_c" name="p_price_c" type="text" class="form-control"
                                placeholder="₹">
                            <label for="p_price_c">Current Price</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-floating">
                            <input required id="p_price_s" name="p_price_s" type="text" class="form-control"
                                placeholder="">
                            <label for="p_price_s">Offer Price</label>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="addplan" class="btn btn-success">Save changes</button>
                    </div>
                </form>

            </div>

        </div>
    </div>
</div>


<!-- Update Plan Modal -->
<div class="modal" id="updatePlan" tabindex="-1" aria-labelledby="updatePlan" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content card-bg">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Update Plan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="action.php" method="POST">
                    <input type="hidden" id="edit_plan_id" name="edit_plan_id" value="">
                    <div class="mb-3">
                        <div class="form-floating">
                            <input required id="p_name_updated" type="text" class="form-control" name="p_name_updated"
                                placeholder="Fullname">
                            <label for="p_name_updated">Plan name</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-floating">
                            <select id="p_duration_updated" class="form-select" name="p_duration_updated">
                                <option selected>Choose duration</option>
                                <option value="30">1 Month</option>
                                <option value="180">6 Months</option>
                                <option value="365">1 year</option>
                                <option value="4">One time offer</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-floating">
                            <input required id="p_price_c_updated" name="p_price_c_updated" type="text"
                                class="form-control" placeholder="₹">
                            <label for="p_price_c_updated">Current Price</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-floating">
                            <input required id="p_price_s_updated" name="p_price_s_updated" type="text"
                                class="form-control" placeholder="">
                            <label for="p_price_s_updated">Offer Price</label>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="addplan" class="btn btn-success">Save changes</button>
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
        $('.edit-plan').click(function () {
            var planId = $(this).data('id');
            $('#edit_plan_id').val(planId);

            $.ajax({
                url: 'action.php',
                type: 'GET',
                data: {
                    id: planId
                },
                dataType: 'json',
                success: function (data) {
                    $('#p_name_updated').val(data.p_name);
                    $('#p_duration_updated option[value="' + data.p_duration + '"]').prop('selected', true);
                    $('#p_price_c_updated').val(data.p_price_c);
                    $('#p_price_s_updated').val(data.p_price_s);
                },
                error: function () {
                    alert('Error fetching plan details');
                }
            });
        });
    });

    $(document).ready(function () {
        $('.del-plan').click(function (e) { 
            e.preventDefault();

            var pId = $(this).data('id');

            $.ajax({
                type: "post",
                url: "action.php",
                data: {
                    delStatus: true,
                    pId: pId,
                },
                success: function (response) {
                    location.reload();
                }
            });


            
        });
    });

    $(document).ready(function () {
        $(".update-status").click(function (e) {
            e.preventDefault();

            var pId = $(this).attr('data-id');
            var statusId = $(this).attr('status-id');

            // console.log("Clicked - Coupon ID: " + pId + ", Status ID: " + statusId);

            $.ajax({
                type: "post",
                url: "action.php",
                data: {
                    updateStatus: true,
                    pId: pId,
                    statusId: statusId,

                },
                success: function (response) {
                    // console.log("Success: " + response);
                    location.reload();
                }, error: function (xhr, status, error) {
                    // console.error("Error: " + error);
                    // Handle AJAX error here
                }
            });

        });
    });


</script>

<?php
include "../includes/footer.php";
?>