<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Firsthash</title>
    <link rel="shortcut icon" href="assets/images/favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>

<body>
    <div class="container-fluid d-flex justify-content-center align-items-center" style="height: 100vh;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12 col-lg-4">
                    <div class="card bg-white border-0 shadow rounded">
                        <div class="card-body">
                            <div class="authent-logo text-center">
                                <div class="text-center img-fluid">
                                    <img src="logo-icon.png" width="100px" height="100px" alt="Logo" />
                                </div>
                                <span>Welcome to <b>First Hash</b></span>
                                <p>Please enter your email to reset your password.</p>
                            </div>

                            <form id="resetForm">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com">
                                </div>
                                <div id="alertMessage" class="alert alert-success mb-3" style="display: none;"></div>
                                <button type="submit" class="btn btn-primary">Confirm Email</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
      $(document).ready(function() {
    $('#resetForm').submit(function(e) {
        e.preventDefault();
        var email = $('#email').val();
        if (email === '') {
            alert('Please enter your email');
            return;
        }
        $.ajax({
            url: 'send_reset_link.php',
            type: 'post',
            data: { email: email },
            success: function(response) {
                response = response.trim(); // Trim whitespace
                 console.log('Response:', response); // Debugging
                if (response === 'success') {
                    $('#alertMessage').text('Email sent').removeClass('alert-danger').addClass('alert-success').show();
                } else {
                    $('#alertMessage').text('Email not sent').removeClass('alert-success').addClass('alert-danger').show();
                }
            },
            error: function() {
                $('#alertMessage').text('An error occurred').removeClass('alert-success').addClass('alert-danger').show();
            }
        });
    });
});

    </script>
</body>

</html>