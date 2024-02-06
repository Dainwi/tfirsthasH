<?php
// echo"I am in login-action.php";
include 'config.php';

session_start();

// Redirect if already logged in
if (isset($_SESSION["login_admin"])) {
    header("Location: {$url}/admin/dashboard");
    exit(0);
} elseif (isset($_SESSION["login_manager"])) {
    header("Location: {$url}/manager/dashboard");
    exit(0);
}elseif (isset($_SESSION["login_superadmin"])) {
    header("Location: {$url}/superadmin/dashboard");
    exit(0);
}elseif(isset($_SESSION["login_employee"])) {
    header("Location: {$url}/employee/dashboard");
    exit(0);
}


if (isset($_POST['submit'])) {
    // echo'I am in the if submit wala block';
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);

    $hashedPassword = md5($password); // Consider using password_hash and password_verify

    // echo'Password verify kr liye md5 ka use kr k';

    // Check in the admin table
    $adminSql = "SELECT * FROM admin WHERE c_email='{$email}' AND password='{$hashedPassword}'";
    $adminResult = mysqli_query($con, $adminSql);

    // echo" admin wala table check kr liye";

    // check in superadmin
    $superadminSql = "SELECT * FROM superadmin WHERE email='{$email}' AND password='{$hashedPassword}'";
    $superadminResult = mysqli_query($con, $superadminSql);

    


    if (!$adminResult) {
        // echo "Koi error nhi hai";
        // echo "invalid email";
        error_log("SQL error: " . mysqli_error($con));
        $_SESSION['error'] = "Database query error";
    }

    if ($adminResult && mysqli_num_rows($adminResult) > 0) {
        $row = mysqli_fetch_assoc($adminResult);
        $subsSQL = "SELECT * FROM subscription WHERE c_id='{$row['c_id']}'";
        $subsResult = mysqli_query($con, $subsSQL);
        if($subsResult && mysqli_num_rows($subsResult) > 0){
            $subsRow = mysqli_fetch_assoc($subsResult);
            if($subsRow['sub_status'] == '0'){
                $_SESSION['error'] = "Your subscription is expired. Please renew your subscription.";
                $_SESSION['user_name'] = $row['c_name'];
                $_SESSION['user_phone'] = $row['c_phone'];
                $_SESSION['user_email'] = $row['c_email'];
                // $_SESSION['user_id'] = $row['c_id'];
                header("Location: {$url}/plan.php");
                exit(0);
            }elseif($subsRow['sub_status'] == '1'){
                $_SESSION['user_id'] = $row['c_id'];
                $_SESSION['login_admin'] = true;
                header("Location: {$url}/admin/dashboard");
                exit(0);
            }
        }else{
            $_SESSION['error'] = "Please Buy a Plan to continue.";
            $_SESSION['user_id'] = $row['c_id'];
            $_SESSION['user_name'] = $row['c_name'];
                $_SESSION['user_phone'] = $row['c_phone'];
                $_SESSION['user_email'] = $row['c_email'];
            header("Location: {$url}/plan.php");
            exit(0);
        }
        
    }
    elseif ($superadminResult && mysqli_num_rows($superadminResult) > 0) {
        $row = mysqli_fetch_assoc($superadminResult);
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['login_superadmin'] = true;
        header("Location: {$url}/superadmin/dashboard");
        exit(0);
    } else {
        // Check in the users table
        $userSql = "SELECT u_id, user_role,cus_id FROM users_db WHERE user_email='{$email}' AND user_password='{$hashedPassword}' AND user_active=1";
        $userResult = mysqli_query($con, $userSql);
        // echo" user_db wala table check kr liye";

        if ($userResult && mysqli_num_rows($userResult) > 0) {
            // echo" user mil gaya";
            $row = mysqli_fetch_assoc($userResult);
            $_SESSION['user_id'] = $row['u_id'];
            $userType = $row['user_role'];
            $subsSQL = "SELECT * FROM subscription WHERE c_id='{$row['cus_id']}'";
            $subsResult = mysqli_query($con, $subsSQL);
            $subsRow = mysqli_fetch_assoc($subsResult);
            // check the subscription status is active or not
            if ($subsRow['sub_status'] == '1') {
                if ($userType == 1) {
                    // echo'manager wala type ma hun';
                    $_SESSION['login_manager'] = true;
                    header("Location: {$url}/manager/dashboard");
                } elseif ($userType == 2) {
                    // echo "employee wala type ma hun";
                    $_SESSION['login_employee'] = true;
                    header("Location: {$url}/employee/dashboard");
                }
                exit(0);
            }
            else{
               $_SESSION['error'] = "Renewal Required";
                header("Location: {$url}/index.php");
                exit(0);
            }
           
        } else {
            $_SESSION['error'] = "Username or password not matched";
            header("Location: {$url}/index.php");
            exit(0);
        }
    }
}
// echo"I am at the end of the login-action.php";