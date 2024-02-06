<?php
include_once('../../config.php');
session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

$cusID = $_SESSION['user_id'];

//employee type add
if (isset($_POST['type-submit'])) {
    $emptype = mysqli_real_escape_string($con, $_POST['emptype']);
    $ptype = $_POST['ptype'];

    if ($ptype == 2) {
        echo "<script>window.location=document.referrer;</script>";
    } else {

        $sql = "INSERT INTO employee_type(type_name,type_production,cus_id)VALUES('$emptype',$ptype,'$cusID')";
        $res = mysqli_query($con, $sql);
        if ($res) {
            echo "<script>window.location=document.referrer;</script>";
        } else {
            echo "<script>window.location=document.referrer;</script>";
        }
    }
}

//employee type update
if (isset($_POST['type-update'])) {
    $empid = $_POST['type-update'];
    $emptype = mysqli_real_escape_string($con, $_POST['emptype']);

    $sql = "UPDATE employee_type SET type_name='$emptype' WHERE type_id={$empid}";
    $res = mysqli_query($con, $sql);
    if ($res) {
        echo "<script>window.location=document.referrer;</script>";
    } else {
        echo "<script>window.location=document.referrer</script>";
    }
}

//employee type delete
if (isset($_POST['type-delete'])) {
    $empid = $_POST['type-delete'];

    $sql = "DELETE FROM employee_type WHERE type_id={$empid} ;";
    $sql .= "DELETE FROM employee_position WHERE ep_type={$empid}";
    $res = mysqli_multi_query($con, $sql);
    if ($res) {
        echo "<script>window.location=document.referrer;</script>";
    } else {
        echo "<script>window.location=document.referrer;</script>";
    }
}

//add new member
if (isset($_POST['save-member'])) {
    // echo "<pre>";
    // print_r($_POST);
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    $payment_type = mysqli_real_escape_string($con, $_POST['payment_type']);
    $employee_type = mysqli_real_escape_string($con, $_POST['employee_type']);

    if (isset($_POST['position'])) {
        $position = $_POST['position'];
    }

    $sql = "SELECT * FROM users_db WHERE user_email='$email' AND user_active=1 AND cus_id={$cusID}";
    $result = mysqli_query($con, $sql);
    if (mysqli_num_rows($result) > 0) {
        $_SESSION['user_exist'] = "email already exist";
        echo "<script>window.location=document.referrer;</script>";
    } else {
        $sql1 = "INSERT INTO users_db(user_name, user_email, user_phone, user_role, payment_option, cus_id) VALUES('$name', '$email', '$phone', '$employee_type', '$payment_type', '$cusID')";
        $result1 = mysqli_query($con, $sql1);
        if ($result1) {
            $last_id = mysqli_insert_id($con);
            if ($employee_type == 2) {
                foreach ($position as $type) {
                    $query = "INSERT INTO employee_position(ep_user_id,ep_type)VALUES('$last_id','$type')";
                    $ress = mysqli_query($con, $query);
                }
                if ($ress) {
                    $_SESSION['created'] = "user created successfully";
                    header("Location: {$url}/admin/employees/");
                } else {
                    $_SESSION['user_exist'] = "Something Went Wrong";
                    echo "<script>window.location=document.referrer;</script>";
                }
            } else {
                $_SESSION['created'] = "user created successfully";
                header("Location: {$url}/admin/employees/");
            }
        }
    }
}


//add new employee
// if (isset($_POST['emp_save'])) {
//     $employee_type = mysqli_real_escape_string($con, $_POST['empType']);

//     $name = mysqli_real_escape_string($con, $_POST['name']);
//     $username = mysqli_real_escape_string($con, $_POST['username']);
//     $email = mysqli_real_escape_string($con, $_POST['email']);
//     $phone = mysqli_real_escape_string($con, $_POST['phone']);
//     $address = mysqli_real_escape_string($con, $_POST['address']);
//     $password = mysqli_real_escape_string($con, $_POST['password']);
//     $cpassword = mysqli_real_escape_string($con, $_POST['c_password']);
//     $payment_type = mysqli_real_escape_string($con, $_POST['payment_type']);


//     if (isset($_POST['salary_amount'])) {
//         $salary_amount = mysqli_real_escape_string($con, $_POST['salary_amount']);
//     } else {
//         $salary_amount = '0';
//     }

//     $position = $_POST['position'];

//     if (isset($_POST['bank_name'])) {
//         $bank_name = mysqli_real_escape_string($con, $_POST['bank_name']);
//     } else {
//         $bank_name = "";
//     }

//     if (isset($_POST['bank_branch'])) {
//         $bank_branch = mysqli_real_escape_string($con, $_POST['bank_branch']);
//     } else {
//         $bank_branch = "";
//     }

//     if (isset($_POST['bank_ifsc'])) {
//         $bank_ifsc = mysqli_real_escape_string($con, $_POST['bank_ifsc']);
//     } else {
//         $bank_ifsc = "";
//     }

//     if (isset($_POST['bank_account'])) {
//         $bank_account = mysqli_real_escape_string($con, $_POST['bank_account']);
//     } else {
//         $bank_account = "";
//     }

//     if (isset($_POST['bank_holder'])) {
//         $bank_holder = mysqli_real_escape_string($con, $_POST['bank_holder']);
//     } else {
//         $bank_holder = "";
//     }
//     if (isset($_POST['account_type'])) {
//         $account_type = mysqli_real_escape_string($con, $_POST['account_type']);
//     } else {
//         $account_type = "";
//     }
//     if (isset($_POST['vpa'])) {
//         $vpa = mysqli_real_escape_string($con, $_POST['vpa']);
//     } else {
//         $vpa = "";
//     }
//     if (isset($_POST['phonepay'])) {
//         $phonepay = mysqli_real_escape_string($con, $_POST['phonepay']);
//     } else {
//         $phonepay = "";
//     }

//     $password1 = md5($password);

//     $sql = "SELECT * FROM users_db WHERE (user_username='$username' OR user_email='$email') AND user_active=1";
//     $result = mysqli_query($con, $sql);
//     if (mysqli_num_rows($result) > 0) {
//         $_SESSION['user_exist'] = "Username or email already exist";
//         echo "<script>window.location=document.referrer;</script>";
//     } else {
//         $sql1 = "INSERT INTO users_db(user_name, user_email, user_phone, user_username, user_role, user_password,payment_option,payment_amount,user_address) VALUES('$name', '$email', '$phone', '$username','$employee_type', '$password1','$payment_type','$salary_amount','$address')";
//         $result1 = mysqli_query($con, $sql1);

//         if ($result1) {
//             $last_id = mysqli_insert_id($con);
//             if ($employee_type == 2) {
//                 foreach ($position as $type) {
//                     $query = "INSERT INTO employee_position(ep_user_id,ep_type)VALUES('$last_id','$type')";
//                     $ress = mysqli_query($con, $query);
//                 }

//                 if ($ress) {
//                     if (isset($_POST['bank'])) {
//                         $sql2 = "INSERT INTO payment_information(pi_user_id,pi_bank_name,pi_bank_holder,pi_bank_account,pi_bank_ifsc,pi_bank_branch,pi_account_type,pi_vpa,pi_phonepay)VALUES('$last_id','$bank_name','$bank_holder','$bank_account','$bank_ifsc','$bank_branch','$account_type','$vpa','$phonepay')";
//                         $ress2 = mysqli_query($con, $sql2);
//                         if ($ress2) {
//                             $_SESSION['created'] = "user created successfully";
//                             header("Location: {$url}/admin/employees/");
//                         }
//                     } else {
//                         $_SESSION['created'] = "user created successfully";
//                         header("Location: {$url}/admin/employees/");
//                     }
//                 }
//             } else {
//                 if (isset($_POST['bank'])) {
//                     $sql2 = "INSERT INTO payment_information(pi_user_id,pi_bank_name,pi_bank_holder,pi_bank_account,pi_bank_ifsc,pi_bank_branch,pi_account_type,pi_vpa,pi_phonepay)VALUES('$last_id','$bank_name','$bank_holder','$bank_account','$bank_ifsc','$bank_branch','$account_type','$vpa','$phonepay')";
//                     $ress2 = mysqli_query($con, $sql2);
//                     if ($ress2) {
//                         $_SESSION['created'] = "user created successfully";
//                         header("Location: {$url}/admin/employees/");
//                     }
//                 } else {
//                     $_SESSION['created'] = "user created successfully";
//                     header("Location: {$url}/admin/employees/");
//                 }
//             }
//         }
//     }
// }

//update memnber
if (isset($_POST['update_mtype'])) {
    $mid = $_POST['update_mtype'];
    $emposition = $_POST['position'];
    $sql2 = "DELETE FROM employee_position WHERE ep_user_id={$mid}";
    $res2 = mysqli_query($con, $sql2);
    if ($res2) {
        foreach ($emposition as $type) {
            $query = "INSERT INTO employee_position(ep_user_id,ep_type)VALUES('$mid','$type')";
            $ress = mysqli_query($con, $query);
        }

        if ($ress) {
            echo "<script>window.location.reload(history.back());</script>";
            exit(0);
        } else {
            echo "<script>window.location.reload(history.back());</script>";
            exit(0);
        }
    }
}


//update employee

if (isset($_POST['update-emp'])) {
    // echo "<pre>";
    // print_r($_POST);

    $empid = $_POST['emp-id'];
    $empname = $_POST['emp-name'];
    $empemail = $_POST['emp-email'];
    $empphone = $_POST['emp-phone'];

    $emposition = $_POST['position'];

    $sql1 = "UPDATE users_db SET user_name = '$empname', user_phone='$empphone', user_email='$empemail' WHERE u_id={$empid}";
    $res1 = mysqli_query($con, $sql1);
    if ($res1) {

        $sql2 = "DELETE FROM employee_position WHERE ep_user_id={$empid}";
        $res2 = mysqli_query($con, $sql2);

        if ($res2) {
            foreach ($emposition as $type) {
                $query = "INSERT INTO employee_position(ep_user_id,ep_type)VALUES('$empid','$type')";
                $ress = mysqli_query($con, $query);
            }

            if ($ress) {
                echo "<script>window.location.reload(history.back());</script>";
                exit(0);
            } else {
                echo "<script>window.location.reload(history.back());</script>";
                exit(0);
            }
        } else {
            echo "<script>window.location.reload(history.back());</script>";
            exit(0);
        }
    } else {
        echo "<script>window.location.reload(history.back());</script>";
        exit(0);
    }
}

//delete employee

if (isset($_POST['btn_del_user'])) {
    $empid = $_POST['deluserid'];

    $sql = "UPDATE users_db SET user_active = 0 WHERE u_id = {$empid} ;";
    $sql .= "DELETE FROM deliverables_assign WHERE da_assign_to={$empid} AND da_completed=0";

    $res = mysqli_multi_query($con, $sql);
    if ($res) {
        echo "<script>window.location.reload(history.back());</script>";
        exit(0);
    } else {
        echo "<script>window.location.reload(history.back());</script>";
        exit(0);
    }
}

//bank upate
if (isset($_POST['add_membank'])) {

    if (isset($_POST['bank_name']) || isset($_POST['vpa_address']) || isset($_POST['phone_pay'])) {

        $employee = mysqli_real_escape_string($con, $_POST['empid']);

        if (isset($_POST['bank_name'])) {
            $bankName = mysqli_real_escape_string($con, $_POST['bank_name']);
        } else {
            $bankName = "";
        }

        if (isset($_POST['branch_name'])) {
            $branchName = mysqli_real_escape_string($con, $_POST['branch_name']);
        } else {
            $branchName = "";
        }
        if (isset($_POST['ifsc_code'])) {
            $ifscCode = mysqli_real_escape_string($con, $_POST['ifsc_code']);
        } else {
            $ifscCode = "";
        }
        if (isset($_POST['account_number'])) {
            $accountNumber = mysqli_real_escape_string($con, $_POST['account_number']);
        } else {
            $accountNumber = "";
        }
        if (isset($_POST['account_holder'])) {
            $accountHolder = mysqli_real_escape_string($con, $_POST['account_holder']);
        } else {
            $accountHolder = "";
        }
        if (isset($_POST['account_type'])) {
            $accountType = mysqli_real_escape_string($con, $_POST['account_type']);
        } else {
            $accountType = "";
        }
        if (isset($_POST['vpa_address'])) {
            $vpaAddress = mysqli_real_escape_string($con, $_POST['vpa_address']);
        } else {
            $vpaAddress = "";
        }
        if (isset($_POST['phone_pay'])) {

            $phonePay = mysqli_real_escape_string($con, $_POST['phone_pay']);
        } else {
            $phonePay = "";
        }

        $sql_bank = "INSERT INTO payment_information(pi_user_id,pi_bank_name,pi_bank_holder,pi_bank_account,pi_bank_ifsc,pi_bank_branch,pi_account_type,pi_vpa,pi_phonepay) VALUES('$employee','$bankName','$accountHolder','$accountNumber','$ifscCode','$branchName','$accountType','$vpaAddress','$phonePay')";
        $res_bank = mysqli_query($con, $sql_bank);
        if ($res_bank) {
            echo "<script>window.location=document.referrer;</script>";
        } else {
            echo "<script>window.location=document.referrer;</script>";
        }
    }
}

if (isset($_POST['update_membank'])) {

    $employee1 = mysqli_real_escape_string($con, $_POST['empid1']);


    if (isset($_POST['bank_name1'])) {
        $bankName1 = mysqli_real_escape_string($con, $_POST['bank_name1']);
    } else {
        $bankName1 = "";
    }

    if (isset($_POST['branch_name1'])) {
        $branchName1 = mysqli_real_escape_string($con, $_POST['branch_name1']);
    } else {
        $branchName1 = "";
    }
    if (isset($_POST['ifsc_code1'])) {
        $ifscCode1 = mysqli_real_escape_string($con, $_POST['ifsc_code1']);
    } else {
        $ifscCode1 = "";
    }
    if (isset($_POST['account_number1'])) {
        $accountNumber1 = mysqli_real_escape_string($con, $_POST['account_number1']);
    } else {
        $accountNumber1 = "";
    }
    if (isset($_POST['account_holder1'])) {
        $accountHolder1 = mysqli_real_escape_string($con, $_POST['account_holder1']);
    } else {
        $accountHolder1 = "";
    }
    if (isset($_POST['account_type1'])) {
        $accountType1 = mysqli_real_escape_string($con, $_POST['account_type1']);
    } else {
        $accountType1 = "";
    }
    if (isset($_POST['vpa_address1'])) {
        $vpaAddress1 = mysqli_real_escape_string($con, $_POST['vpa_address1']);
    } else {
        $vpaAddress1 = "";
    }
    if (isset($_POST['phone_pay1'])) {

        $phonePay1 = mysqli_real_escape_string($con, $_POST['phone_pay1']);
    } else {
        $phonePay1 = "";
    }

    $sql_update_bank = "UPDATE payment_information SET pi_bank_name = '$bankName1', pi_bank_holder= '$accountHolder1', pi_bank_account = '$accountNumber1', pi_bank_ifsc = '$ifscCode1', pi_bank_branch = '$branchName1', pi_account_type ='$accountType1', pi_vpa = '$vpaAddress1', pi_phonepay ='$phonePay1' WHERE pi_user_id = {$employee1}";
    $res_update_bank = mysqli_query($con, $sql_update_bank);
    if ($res_update_bank) {
        echo "<script>window.location=document.referrer;</script>";
    } else {
        echo "<script>window.location=document.referrer;</script>";
    }
}


if (isset($_POST['editmsalary'])) {
    $salary = $_POST['salary'];
    $uidd = $_POST['uidd'];
    $sql = "UPDATE users_db SET payment_amount='{$salary}' WHERE u_id={$uidd}";
    $res = mysqli_query($con, $sql);
    if ($res) {
        echo 1;
    } else {
        echo 0;
    }
}

if (isset($_POST['updatempersonal'])) {
    $uidd = $_POST['uidd'];
    $fname = $_POST['fname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $sql = "UPDATE users_db SET user_name='{$fname}',user_phone='{$phone}',user_email='{$email}',user_address='{$address}' WHERE u_id={$uidd}";
    $res = mysqli_query($con, $sql);
    if ($res) {
        echo 1;
    } else {
        echo 0;
    }
}

if (isset($_POST['mauthentication'])) {
    $mid = $_POST['mid'];
    $auth = $_POST['auth'];
    $sql = "UPDATE users_db SET user_online='{$auth}' WHERE u_id={$mid}";
    $res = mysqli_query($con, $sql);
    if ($res) {
        echo 1;
    } else {
        echo 0;
    }
}

if (isset($_POST['btn_del_allteam'])) {
    $sql = "DELETE * FROM users_db WHERE user_role=1 OR user_role=2 ;";
    $sql .= "DELETE * FROM salary_details;";
    $sql .= "DELETE * FROM payment_information;";
    $sql .= "DELETE * FROM employee_position;";
    $sql .= "DELETE * FROM deliverables_assign;";
    $sql .= "DELETE * FROM daily_tasks;";
    $sql .= "DELETE * FROM attendance_db;";
    $sql .= "DELETE * FROM assign_requirements;";
    $sql .= "DELETE * FROM assign_manager_db;";

    $res = mysqli_multi_query($con, $sql);
    if ($res) {
        echo "<script>window.location=document.referrer;</script>";
    } else {
        echo "<script>window.location=document.referrer;</script>";
    }
}
