<?php

$host = "localhost";
// $username = 'u472333726_Dainwi';
// $password = 'D123a456@7';
// $dbname = 'u472333726_tfirst_hash';


$username = 'root';
$password = '';
// $dbname = "test";
$dbname = "firsthashnew";

$rkey ="rzp_test_fu30wZNoNncFmY";
$rname = "First Hash";
$raddress = "Road No. 13 E, Om Prakash Nagar Basargarh, Hatia, Ranchi, JH-03";
$rcolor = "#F37254";

// $url = 'https://tfirsthash.triplehash.in';
$url = 'http://localhost/tfirsthash';

$con = mysqli_connect("$host", "$username", "$password", "$dbname") or die("Connection Failed");
