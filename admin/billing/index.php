<?php
define('TITLE', 'Billing');
define('PAGE', 'billing');
define('PAGE_T', 'General Billing');
include_once('../include/header.php');
include_once('../include/sidebar.php');
include_once('../include/top-nav.php');

$userID = $_SESSION['user_id'];

$limit = 15;
if (isset($_GET['page'])) {
    $page = $_GET['page'];
    $ii = ($page * $limit) - 1;
} else {
    $page = 1;
    $ii = 1;
}
$offset = ($page - 1) * $limit;
?>

<div class="container-fluid">
    <div class="card card-bg">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">

                <div>
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-toggle="modal" data-bs-target="#billingModal">
                        <i class="fas fa-plus me-2"></i> New Bill
                    </button>
                </div>
            </div>
            <div class="card-view">
                <?php

                $sql2 = "SELECT * FROM general_billing WHERE cus_id = {$userID} ORDER BY gb_id DESC LIMIT {$offset},{$limit}";
                $res2 = mysqli_query($con, $sql2);
                if (mysqli_num_rows($res2) > 0) {

                ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Client Name</th>
                                    <th>Client Phone</th>
                                    <th>Date</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                while ($row2 = mysqli_fetch_assoc($res2)) {
                                    $gbid = $row2['gb_id'];
                                    $clName = $row2['gb_client'];
                                    $clPhone = $row2['gb_phone'];
                                    $date = date("d M Y", strtotime($row2['gb_date']));
                                ?>

                                    <tr>
                                        <td><?php echo $ii; ?></td>
                                        <td><?php echo $row2['gb_client']; ?></td>
                                        <td><?php echo $row2['gb_phone']; ?></td>
                                        <td><?php echo $date; ?></td>

                                        <td><a href="#" data-id="<?php echo $gbid ?>" data-name="<?php echo $clName ?>" data-phone="<?php echo $clPhone ?>" class="btn btn-outline-success btn-sm btn-view-bill">view &amp; print</a></td>
                                        <td><input type="hidden" name="gbid" value="<?php echo $gbid ?>"><button type="button" class="btn btn-sm btn-outline-danger btn-del"><i class="fas fa-trash-alt"></i></button></td>
                                    </tr>
                                <?php $ii++;
                                }  ?>

                            </tbody>
                        </table>
                    </div>
                <?php } else {
                    echo "<p class='mt-4 text-center'>no data</p>";
                } ?>
            </div>

        </div>

        <div class="card-footer">
            <nav aria-label="Page navigation example">
                <?php

                $sql_pg = "SELECT * FROM general_billing";
                $res_pg = mysqli_query($con, $sql_pg);
                if (mysqli_num_rows($res_pg) > 0) {

                    $total_records = mysqli_num_rows($res_pg);

                    $total_page = ceil($total_records / $limit);
                    echo '<ul class="pagination justify-content-center">';
                    if ($page > 1) {
                        echo "<li class='page-item'><a class='page-link' href='{$url}/admin/billing/index.php?page=" . ($page - 1) . "'>Prev</a></li>";
                    } else {
                        echo '<li class="page-item disabled"><a class="page-link">Prev</a></li>';
                    }
                    for ($i = 1; $i <= $total_page; $i++) {
                        if ($i == $page) {
                            $active = "active";
                        } else {
                            $active = "";
                        }
                        echo '<li class="page-item ' . $active . '"><a class="page-link" href="' . $url . '/admin/billing/index.php?page=' . $i . '">' . $i . '</a></li>';
                    }
                    if ($total_page > $page) {
                        echo "<li class='page-item'><a class='page-link' href='{$url}/admin/billing/index.php?page=" . ($page + 1) . "'>Next</a></li>";
                    } else {
                        echo '<li class="page-item disabled"><a class="page-link">Next</a></li>';
                    }

                    echo '</ul>';
                }


                ?>



            </nav>
        </div>
    </div>

</div>
<!-- Billing Modal -->
<form action="save-billing.php" method="post">
    <div class="modal fade" id="billingModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content card-bg">
                <div class="modal-header py-3">
                    <h5 class="modal-title text-warning" id="exampleModalLabel">Billing</h5>
                    <button type="button" class="btn-close btn-secondary" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="container">
                        <div class="card" style="background-color: #181821;">
                            <div class="card-body">
                                <h6 class="card-title">Client Info :</h6>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <small class='text-info' for="inputName">Client Name</small>
                                        <input type="text" class="form-control" name="client_name" required id="inputName" placeholder="client name">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <small class='text-info' for="inputPhone">Client Phone</small>
                                        <input type="tel" required class="form-control" name="client_number" id="inputPhone" placeholder="client phone">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <small class='text-info' for="inputAddress">Address</small>
                                        <input type="text" required class="form-control" name="client_address" id="inputAddress" placeholder="client address">
                                    </div>
                                    <div class="col-md-6 invisible">
                                        <small for="inputAddress">Billing Type</small>
                                        <select id="statusType" class="form-control" name="statusType">
                                            <option value="2">Choose...</option>
                                            <option value="1" selected>GST</option>
                                            <option value="0">Non GST</option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="card" style="background-color: #181821;">
                            <div class="card-body">
                                <h6 class="card-title">Billing Info :</h6>
                                <div class="container" id="tab_logicc">
                                    <div class="row clearfix">
                                        <div class="col-md-12">
                                            <table class="table table-bordered table-hover " id="tab_logic">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center text-info"> # </th>
                                                        <th class="text-center text-info"> Product </th>

                                                        <th class="text-center text-info"> Price </th>
                                                        <th class="text-center d-none"> Qty </th>
                                                        <th class="text-center d-none"> Total </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr id='addr0'>
                                                        <td>1</td>
                                                        <td><input type="text" name='product[]' placeholder='Enter Product Name' required class="form-control" /></td>
                                                        <td><input type="number" name='price[]' placeholder='Enter Unit Price' required class="form-control price" step="0.00" min="0" /></td>
                                                        <td><input type="hidden" name='qty[]' placeholder='Enter Qty' class="form-control qty" step="0" min="0" /></td>
                                                        <td><input type="hidden" name='total[]' placeholder='0.00' class="form-control total" readonly /></td>
                                                    </tr>
                                                    <tr id='addr1'></tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row clearfix">
                                        <div class="col-md-12 d-flex">
                                            <button type="button" id="add_row" class="btn btn-sm btn-outline-primary pull-left">Add Row</button>
                                            <button type="button" id='delete_row' class="pull-right btn btn-sm btn-outline-danger ms-auto">Delete Row</button>
                                        </div>
                                    </div>
                                    <div class="row clearfix" style="margin-top:20px">
                                        <div class="pull-right col-md-12">
                                            <table class="table table-bordered table-hover" id="tab_logic_total">
                                                <tbody>
                                                    <tr class="d-none">
                                                        <th class="text-center">Tax(%)</th>
                                                        <td class="text-center">
                                                            <div class="input-group mb-2 mb-sm-0">
                                                                <input type="number" name="tax" class="form-control" id="tax" placeholder="0" value="0">

                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr class="" id="tax_logic">
                                                        <th class="text-center">TAX</th>
                                                        <td><span class="d-block">CGST(9%)</span> SGST(9%)</td>

                                                        <th class="text-center text-info">Tax Amount</th>
                                                        <td class="text-center"><input type="number" name='tax_amount' id="tax_amount" placeholder='0.00' class="form-control" readonly /></td>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-center sub-total-class text-info">Sub Total</th>
                                                        <td class="text-center sub-total-class">
                                                            <input type="number" name='sub_total' placeholder='0.00' class="form-control" id="sub_total" readonly />
                                                        </td>

                                                        <th class="text-center text-info">Grand Total</th>
                                                        <td class="text-center"><input type="number" name='total_amount' id="total_amount" placeholder='0.00' class="form-control" readonly /></td>
                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="container d-flex align-items-end justify-content-end">
                                        <div class="col-md-6 col-8">
                                            <div class="input-group mb-3">
                                                <label class="mt-2 me-2 text-warning">Amount Paid : </label>

                                                <span class="input-group-text">Rs. </span>

                                                <input type="number" id="amount-paid" required name="amount-paid" class="form-control" aria-label="" placeholder="00">

                                            </div>
                                            <div class="input-group mb-3">
                                                <label class="mt-2 me-2 text-warning">Amount Due : </label>

                                                <span class="input-group-text">Rs. </span>

                                                <input type="number" id="amount-due" required name="amount-due" class="form-control" aria-label="" placeholder="00">

                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                    <input type="submit" name="general_bill" value="save" class="btn btn-primary">
                </div>

            </div>
        </div>

    </div>
</form>
<!-- modal delete  -->
<div class="modal fade" id="staticBackdropDel" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content card-bg">

            <div class="modal-body py-4">
                <h1 class="text-center text-warning display-1"><i class="fas fa-exclamation-circle"></i></h1>
                <h4 class="text-center mt-3 text-light">Are you sure?</h4>
                <p class="text-center text-info">You won't be able to revert this!</p>
                <form action="save-billing.php" method="post">
                    <div class="text-center mt-4">
                        <input type="hidden" name="billid" id="bill-id" value="">
                        <input type="hidden" name="btn_del_billing" value="1">
                        <button type="submit" class="me-2 btn btn-primary text-dark">Yes, delete it!</button>
                        <button type="button" class="ms-2 btn btn-secondary" data-bs-dismiss="modal">close</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<!-- Modal view bill start-->
<div class="modal fade" id="modalViewBill" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen modal-dialog-scrollable">
        <div class="modal-content card-bg">
            <div class="modal-header py-3">
                <h5 class="modal-title" id="exampleModalLabel">Bill</h5>
                <button type="button" class="btn-close btn-secondary" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalViewBillBody">
                <div class="d-flex justify-content-center">
                    <div class="spinner-border text-secondary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer pt-1 pb-3">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                <button type="button" data-id="" data-name="" data-phone="" class="btn btn-success sendwhatsapp"><i class="fab fa-whatsapp fa-lg"></i></button>
                <a target="_blank" href="" type="button" class="btn btn-primary btn-print-bill">Print</a>
            </div>
        </div>
    </div>
</div>
<!-- Modal view bill end-->

<script>
    $(".btn-view-bill").click(function(e) {
        e.preventDefault();
        var bilid = $(this).attr('data-id');
        var cname = $(this).attr('data-name');
        var cphone = $(this).attr('data-phone');
        var purl = "<?php echo $url . "/general-bill.php?bp=" ?>" + bilid;

        $(".btn-print-bill").attr("href", purl);
        $(".sendwhatsapp").attr("data-id", bilid);
        $(".sendwhatsapp").attr("data-name", cname);
        $(".sendwhatsapp").attr("data-phone", cphone);

        $("#modalViewBill").modal('show');
        $.ajax({
            type: "post",
            url: "save-billing.php",
            data: {
                billingviewmodal: true,
                billid: bilid,
            },
            success: function(response) {
                $("#modalViewBillBody").html(response);
            }
        });

    });

    $(".sendwhatsapp").click(function(e) {
        e.preventDefault();
        var eventid = $(this).attr('data-id');

        var name = $(this).attr('data-name');
        var phone = $(this).attr('data-phone');

        var quote = "<?php echo $url . "/general-bill.php?bp=" ?>" + eventid;


        var url = "https://wa.me/+91" + phone + "?text=" +
            "Hello, " + name + "%0a" +
            "Download your Bill from below link:" + "%0a" +
            "" + "%0a" +
            quote;

        window.open(url, '_blank').focus();

    });

    $(document).ready(function() {
        var i = 1;
        $("#add_row").click(function(e) {
            e.preventDefault();
            b = i - 1;
            $('#addr' + i).html($('#addr' + b).html()).find('td:first-child').html(i + 1);
            $('#tab_logic').append('<tr id="addr' + (i + 1) + '"></tr>');
            i++;
        });
        $("#delete_row").click(function(e) {
            e.preventDefault();
            if (i > 1) {
                $("#addr" + (i - 1)).html('');
                i--;
            }
            calc();
        });

        $('#tab_logic tbody').on('keyup change', function() {
            calc();
        });
        $('#tax').on('keyup change', function() {
            calc_total();
        });

        $('#statusType').on('change', function() {
            var statusOption = $('#statusType option:selected').val();

            if (statusOption == 0) {
                $("#tab_logicc").removeClass("d-none");
                $("#tax_logic").addClass("d-none");
                $(".sub-total-class").addClass("d-none");
                $("#tax").val(0);
                calc();
            } else if (statusOption == 1) {
                $("#tab_logicc").removeClass("d-none");
                $("#tax_logic").removeClass("d-none");
                $(".sub-total-class").removeClass("d-none");
                $("#tax").val(18);
                calc();
            } else {
                $("#tab_logicc").addClass("d-none");
                $("#tax").val(0);
            }
        });
        $('#amount-paid').on('keyup', function() {

            var pamount = $(this).val();
            var tamount = parseInt($("#total_amount").val());
            var damount = tamount - pamount;
            $("#amount-due").val(damount);
            // alert(tamount)
        });


    });

    function calc() {
        $('#tab_logic tbody tr').each(function(i, element) {
            var html = $(this).html();
            if (html != '') {
                var qty = $(this).find('.qty').val();
                var price = $(this).find('.price').val();
                if (qty == "") {
                    qty = 1;
                }
                $(this).find('.total').val(qty * price);

                calc_total();
            }
        });
    }

    function calc_total() {
        total = 0;
        $('.total').each(function() {
            total += parseInt($(this).val());
        });
        $('#sub_total').val(total.toFixed(2));
        tax_sum = total / 100 * 18;
        $('#tax_amount').val(tax_sum.toFixed(2));
        $('#total_amount').val((tax_sum + total).toFixed(2));
    }

    $(".btn-del").on('click', function(e) {
        e.preventDefault();
        var ddd = $(this).closest('td').find("input[name='gbid']").val();

        $("#bill-id").val(ddd);

        $("#staticBackdropDel").modal('show');
    });
</script>

<?php include_once('../include/footer.php'); ?>