<?php
$con = mysqli_connect("localhost", "root", "", "guestbk");
 
if (!$con) {
  die("Database is not connected successfully! Errors: ") . mysqli_error($con);
}
?>