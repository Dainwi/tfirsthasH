<?php

include 'includes/header.php';
// session_start();
?>
<!--<?php echo $_SESSION['user_id']; ?>-->

<div class="container-fluid">
  <div class="container">
    <div class="row py-3">
      <h1 class="fw-bolder">You’re almost there! Complete your order</h1>
    </div>
    <div class="row">
      <div class="col-md-4 border-end info">
        <h5>
          <i class="fa-brands fa-rocketchat"></i> <b>All-in-one solution</b>
        </h5>
        <ul>
          <li><b>100</b> Websites</li>
          <li><b>Unmetered Traffic</b> (Unlimited GB)</li>
          <li><b>24/7</b> Customer Support</li>
        </ul>
      </div>
      <div class="col-md-4 border-end info">
        <h5><i class="fa-solid fa-lock"></i> Security</h5>
        <ul>
          <li><b>100</b> Websites</li>
          <li><b>Unmetered Traffic</b> (Unlimited GB)</li>
          <li><b>24/7</b> Customer Support</li>
        </ul>
      </div>
      <div class="col-md-4 info">
        <h5><i class="fa-brands fa-wordpress"></i> WordPress</h5>
        <ul>
          <li><b>100</b> Websites</li>
          <li><b>Unmetered Traffic</b> (Unlimited GB)</li>
          <li><b>24/7</b> Customer Support</li>
        </ul>
      </div>
    </div>




    <div class="row pt-2">
      <h1 class="fw-bolder period">1. Choose a Period</h1>

      <?php
      // Fetch plans from the database
      // include "../config.php";
      
      $query = "SELECT * FROM `plan`";
      $result = mysqli_query($con, $query);

      if (mysqli_num_rows($result) > 0) {
        ?>
        <?php
        while ($plan = mysqli_fetch_assoc($result)) {
          ?>

          <div class="col-md-3 custom-card" plan-n="<?php echo $plan['p_name']; ?>"
            plan-d="<?php echo $plan['p_duration']; ?>" data-id="<?php echo $plan['p_id']; ?>"
            price-c="<?php echo $plan['p_price_c']; ?>" price-s="<?php echo $plan['p_price_s']; ?>">
            <div class="card shadow sel-plan">
              <div class="card-body">
                <h5 class="card-title fw-bold">
                  <?php echo $plan['p_name']; ?>
                </h5>
                <h1 class="fw-bolder mb-0 mt-4 price"> ₹
                  <?php echo $plan['p_price_s']; ?>
                </h1>
                <h1 class="fw-bolder mb-0 mt-4 price-m">₹
                  <?php echo $plan['p_price_s'] . "/" . $plan['p_name']; ?>
                </h1>
                <p class="card-text fw-bold mt-0 text-secondary currency">
                  INR/month
                </p>
                <p class="fw-normal text-secondary plan-renew-d">
                  Your plan will seamlessly renew at
                  <?php echo $plan['p_price_s'] . "/" . $plan['p_name']; ?> on
                  <?php echo $renewalDate = date('d/m/y', strtotime("+{$plan['p_duration']} days")); ?>
                </p>
              </div>
              <div class="card-footer fw-bold">Save ₹
                <?php echo $plan['p_price_c'] - $plan['p_price_s'] . "/" . $plan['p_name']; ?>
              </div>
            </div>
          </div>

          <?php
        }
        ?>

        <?php
      }
      ?>
    </div>

    <div class="row mt-4">
      <h1 class="fw-bolder period">2. Complete Your Payment</h1>
      <div class="col-md-6 col-sm-6 bg-white rounded shadow py-3 mt-2">
        <div class="row px-3">
          <div class="col-md-6 col-7 d-flex align-items-center">
            <p class="mb-0 fw-bold">Selected Plan: <span id="selectedPlan"></span></p>
          </div>
          <div class="col-md-6 col-5 d-flex justify-content-end align-items-center">
            <p class="mb-0" id="selectedPlan"><s>₹<span id="cprice"></span> </s> <b>₹<span id="totalAmount"></span></b>
            </p>
          </div>
        </div>
        <hr />
        <div class="row px-3">
          <div class="col-md-6">
            <div class="mb-2">
              <div class="form-floating">
                <input disabled type="text" class="form-control" id="floatingInput"
                  value="<?php echo $_SESSION['user_name']; ?>" placeholder="Name" />
                <label for="floatingInput">Full Name</label>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="mb-2">
              <div class="form-floating">
                <input disabled type="tel" class="form-control" id="floatingInput"
                  value="+91 <?php echo $_SESSION['user_phone']; ?>" placeholder="Mobile" />
                <label for="floatingInput">Mobile Number</label>
              </div>
            </div>
          </div>
          <div class="col-md-12">
            <div class="mb-1">
              <div class="form-floating">
                <input disabled type="email" class="form-control" id="floatingInput"
                  value="<?php echo $_SESSION['user_email']; ?>" placeholder="email" />
                <label for="floatingInput">Email Address</label>
              </div>
            </div>
          </div>
        </div>
        <hr />
        <div class="row px-3">
          <div class="col-md-6 col-7 d-flex align-items-center">
            <p class="mb-0 fw-bold">Plan Discount</p>
          </div>
          <div class="col-md-6 col-5 d-flex justify-content-end align-items-center">
            <p style="color: #00b090;" class="mb-0" id="selectedPlan"><b>-₹<span id="planDiscount"></span></b></p>
          </div>
        </div>
        <div class="row px-3">
          <div class="col-md-6 col-7 d-flex align-items-center">
            <p class="mb-0 fw-bold">Coupon Discount</p>
          </div>
          <div class="col-md-6 col-5 d-flex justify-content-end align-items-center">
            <p style="color: #00b090;" class="mb-0"><b>-₹<span id="couponDiscount"></span></b></p>
          </div>
        </div>
        <div class="row px-3">
          <div class="col-md-6 col-7 d-flex align-items-center">
            <p class="mb-0 fw-bold">Taxes & Fees</p>
          </div>
          <div class="col-md-6 col-5 d-flex justify-content-end align-items-center">
            <p style="color: #00b090;" class="mb-0"><b>₹0</b></p>
          </div>
        </div>
        <hr>
        <div class="row px-3">
          <div class="col-md-6 col-8 d-flex align-items-center">
            <h4 class="mb-0 fw-bolder">Payable Amount</h4>
          </div>



          <div class="col-md-6 col-4 d-flex justify-content-end align-items-center">
            <p class="mb-0"><b>₹<span id="payable"></span></b></p>
          </div>
        </div>

        <div class="row px-3">
          <div class="col-md-6 col-8 d-flex align-items-center">
            <p style="color: #00b090;" class="mb-0 fw-bolder" id="couponResponse"></p>
          </div>
        </div>
        <div class="row px-3 mt-2">
          <div class="col-md-6">
            <p id="haveacoupon" style="color: #6747c7;" class="fw-bolder cursor-pointer">Have a Coupon Code?</p>
          </div>
          <div id="couponInput" class="coupon-container my-1 hidden col-md-12">
            <div class="row">
              <div class="col-md-7 col-12">
                <input type="text" value="" class="form-control py-2 ps-3" placeholder="Enter a Coupon Code"
                  id="couponEntered">
              </div>
              <div class="col-md-5 col-12">
                <button id="couponSub" class="btn">Apply Coupon</button>
              </div>
            </div>
          </div>
          <!-- <button id="payButton" name="submit" type="submit" class="btn m-b-xs py-2">
              Pay now
            </button> -->
        </div>
        <hr>
        <div class="row">
          <div class="col-md-6 px-3">
            <button id="payButton" name="submit" class="btn py-2 w-100 fw-bolder" type="submit">Submit Secure
              Payment</button>
          </div>
          <div class="col-md-6">
            <p class="mb-0"><i class="fa-solid fa-clock-rotate-left text-success"></i> 7-Day Money-Back Guarantee</p>
            <p><i class="fa-solid fa-lock text-success"></i> Encrypted and Secure Payments</p>
          </div>
        </div>

      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
    crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
    integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

  <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
  <script>
    $(document).ready(function () {

      var offer;
      var planId;
      var planDuration;
      var planName;
      var defaultPayableAmount; // Store the default payable amount
      var couId;
      var userId = "<?php echo $_SESSION['user_id']; ?>";
      var urL = "<?php echo $url ?>";
      console.log(userId);
      console.log("url is: " + urL);


      $(".custom-card").click(function (e) {
        e.preventDefault();


        $(".card").removeClass("card-active");
        $(".card-footer").removeClass("footer-active");

        $(this).find(".card").addClass("card-active");
        $(this).find(".card-footer").addClass("footer-active");

        planId = $(this).attr('data-id');
        offer = $(this).attr('price-s');
        planDuration = $(this).attr('plan-d');
        planName = $(this).attr('plan-n');
        var price = $(this).attr('price-c');

        $("#selectedPlan").html(planName);
        $("#totalAmount").html(offer);
        $("#planDiscount").html(price - offer);
        $("#payable").html(offer);
        $("#couponDiscount").html("0");
        $("#cprice").html(price);


        // Update the defaultPayableAmount when the user selects a plan
        defaultPayableAmount = parseFloat(offer);
        planId = parseFloat(planId);
      });

      $("#haveacoupon").click(function (e) {
        e.preventDefault();
        $("#couponInput").removeClass("hidden");
      });
      
      var couponname = 'Coupon -';

      $("#couponSub").click(function (e) {
        e.preventDefault();

        var couponE = $("#couponEntered").val();

        $.ajax({
          type: "post",
          url: "action.php",
          data: {
            checkCoupon: true,
            couponE: couponE,
          },
          success: function (response) {
            // Parse the response as JSON
            var responseData = JSON.parse(response);
            couponname += couponE;

            // Check the numeric response code
            switch (responseData.code) {
              case 1:
                // Coupon exists and cou_status is 1
                // Calculate the new payableAmount after applying the coupon
                couId = responseData.couponId;
                var couponDiscount = responseData
                  .price; // Get the discount from the response
                var newPayableAmount = defaultPayableAmount - couponDiscount;
                $("#payable").html(newPayableAmount);
                $("#couponDiscount").html(couponDiscount);

                // Hide the coupon input after applying the coupon
                $("#couponInput").addClass("hidden");



                // console.log("Coupon Applied!!");
                break;
              case 2:
                // Coupon exists and cou_status is 0
                // Set the payableAmount back to default if the coupon is expired
                $("#couponDiscount").html("0");
                $("#payable").html(defaultPayableAmount);
                // console.log("Coupon Expired!!");
                break;
              case 3:
                // Coupon not found
                // Set the payableAmount back to default if the coupon is not found
                $("#couponDiscount").html("0");
                $("#payable").html(defaultPayableAmount);
                // console.log("Coupon not found.");
                break;
              default:
                // Handle other response codes if needed
                console.log("Unknown response code: " + responseData.code);
            }

            // Display the text response to the user
            $("#couponResponse").html(responseData.text);
          }
        });
      });

     $("#payButton").click(function () {
    var payableAmount = parseFloat($("#payable").text()); // Get the updated payable amount

    // Check if payable amount is zero
    if (payableAmount === 0) {
        // Directly make an AJAX call to payment.php
        $.ajax({
            type: "POST",
            url: "payment.php",
            data: {
                paymentStatus: true,
                payment_id: couponname, // No payment id as no payment was processed
                payableAmount: payableAmount,
                planId: planId,
                planDuration: planDuration,
                couId: couId,
                userId: userId,
            },
            success: function (response) {
                // Redirecting to the admin page
                window.location.href = 'https://tfirsthash.triplehash.in/admin';
                // Assuming this line is meant to be on the server side and not here:
                $_SESSION['login_admin'] = true;
                console.log("User created successfully without payment");
            },
            error: function (xhr, status, error) {
                console.error("AJAX request to payment.php failed:", error);
            }
        });
    } else {
        // Proceed with Razorpay payment process
        var options = {
            key: '<?php echo $rkey ?>', // Replace with your actual key
            amount: payableAmount * 100, // Amount in paise
            currency: 'INR',
            name: '<?php echo $rname ?>',
            handler: function (response) {
                // ... Other variables ...

                // AJAX request with payment details
                $.ajax({
                    type: "POST",
                    url: "payment.php",
                    data: {
                        paymentStatus: true,
                        payment_id: response.razorpay_payment_id,
                        payableAmount: payableAmount,
                        planId: planId,
                        planDuration: planDuration,
                        couId: couId,
                        userId: userId,
                    },
                    success: function (response) {
                        window.location.href = 'https://tfirsthash.triplehash.in/admin';
                        $_SESSION['login_admin'] = true;
                        // Server-side code, not for client-side
                        console.log("Payment success");
                    },
                    error: function (xhr, status, error) {
                        console.error("AJAX request to payment.php failed:", error);
                    }
                });
            },
            // ... Rest of your Razorpay options ...
        };
        var rzp = new Razorpay(options);
        rzp.open();
    }
});

    });
  </script>

  <?php
  include('includes/footer.php');
  ?>