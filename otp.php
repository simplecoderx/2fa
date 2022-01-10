<?php

include 'config.php';

session_start();

error_reporting(0);

if(!isset($_SERVER['HTTP_REFERER'])){
  // redirect them to your desired location
  header('location: otp.php');
  exit;
}

if(isset($_POST['check'])){
  $_SESSION['info'] = "";
  $otp_code = mysqli_real_escape_string($conn, $_POST['otp']);
  $check_code = "SELECT * FROM users WHERE code = $otp_code";
  $code_res = mysqli_query($conn, $check_code);
  if(mysqli_num_rows($code_res) > 0){
      $fetch_data = mysqli_fetch_assoc($code_res);
      $fetch_code = $fetch_data['code'];
      $email = $fetch_data['email'];
      $code = 0;
      //$status = '1';
      $update_otp = "UPDATE users SET code = $code, status = '$status' WHERE code = $fetch_code";
      $update_res = mysqli_query($conn, $update_otp);
      if($update_res){
          $_SESSION['email'] = $email;
          header('location: viewmap.php');
          exit();
      }else{
          $errors['otp-error'] = "Failed while updating code!";
      }
  }else{
      $errors['otp-error'] = "You've entered incorrect code!";
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="css/styles.css" />
  <link rel="stylesheet" href="assets/css/style.css" />

  <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
  <META HTTP-EQUIV="Expires" CONTENT="-1">
  
  <!-- Favicons -->
  <link href="assets/img/csu-icon.png" rel="icon">
  <link href="assets/img/csu-icon.png" rel="apple-touch-icon">

  <title>Registration | WebMapCSU</title>

  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js"></script>
</head>
<body>

<script>
$(document).ready(function() {
    function disableBack() { window.history.forward() }
    window.onload = disableBack();
    window.onpageshow = function(evt) { if (evt.persisted) disableBack() }
});</script>


  <div class="container">
    <div class="forms-container">
      <div class="signin-signup">
        <form action="" method="post" class="sign-in-form">
          <h2 class="title">Confirm OTP</h2>
          <div class="input-field">
            <i class="fas fa-lock"></i>
            <input type="text" placeholder="Enter code here" name="otp" value="<?php echo $_POST['otp']; ?>" required />
          </div>
          <input type="submit" value="Submit" name="check" class="btn solid" />
        </form>
      </div>
    </div>

    <div class="panels-container">
      <div class="panel left-panel">
        <img src="img/Say.svg" class="image" alt="" />
      </div>
      <div class="panel right-panel">
        <div class="content">
          <h3>Welcome back!</h3>
          <p>
          Continue your experience using our web app by signing in using your saved log in details.
          </p>
          <button class="btn transparent" id="sign-in-btn">
            Sign in
          </button>
        </div>
        <img src="img/Say.svg" class="image" alt="" />
      </div>
    </div>
  </div>

  <script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script>
  <script src="js/app.js"></script>
</body>

</html>