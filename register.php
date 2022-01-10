<?php

include 'config.php';

session_start();

error_reporting(0);

$errors = array();  //  AYAW NI WAD-A DZAE KAY BASIG MAMATAY KAG DEBUG

//if(!isset($_SERVER['HTTP_REFERER'])){
  // redirect them to your desired location
 // header('location: register.php');
 // exit;
//}



if (isset($_POST["signup"])) {
  $email = mysqli_real_escape_string($conn, $_POST["signup_email"]);
  $password = mysqli_real_escape_string($conn, md5($_POST["signup_password"]));
  $cpassword = mysqli_real_escape_string($conn, md5($_POST["signup_cpassword"]));
  $token = md5(rand());
  $status= '0';
  $check_email = mysqli_num_rows(mysqli_query($conn, "SELECT email FROM users WHERE email='$email'"));

  if ($password !== $cpassword) {
    echo "<script>alert('Password did not match.');</script>";
  } elseif ($check_email > 0) {
    echo "<script>alert('Email already exists in our database.');</script>";
  }else {
    $sql = "INSERT INTO users (email, password, token, status) VALUES ('$email', '$password', '$token', '$status')";
    $result = mysqli_query($conn, $sql);
    if ($result) {
      $_POST["signup_email"] = "";
      $_POST["signup_password"] = "";
      $_POST["signup_cpassword"] = "";

      $to = $email;
      $subject = "Email verification - WebMapCSU";

      $message = "
      <html>
      <head>
      <title>{$subject}</title>
      </head>
      <body>
      <p><strong>Email Confirmation Link - WEBMAPCSU</strong></p>
      <p>Thanks for registration! Verify your email to access our website. Click below link to verify your email.</p>
      <p><a href='{$base_url}verify-email.php?token={$token}'>Verify Email</a></p>
      </body>
      </html>
      ";

      // Always set content-type when sending HTML email
      $headers = "MIME-Version: 1.0" . "\r\n";
      $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

      // More headers
      $headers .= "From: ". $my_email;

      if (mail($to,$subject,$message,$headers)) {
        echo "<script>alert('We have sent a verification link to your email - {$email}.');</script>";
      } else {
        echo "<script>alert('Mail not sent. Please try again.');</script>";
      }
    } else {
      echo "<script>alert('User registration failed.');</script>";
    }
  }
}

if (isset($_POST["signin"])) {
  $email = mysqli_real_escape_string($conn, $_POST["signin_email"]);
  $password = mysqli_real_escape_string($conn, md5($_POST["signin_password"]));
  $check_email = mysqli_query($conn, "SELECT id FROM users WHERE email='$email' AND password='$password'");

  if (mysqli_num_rows($check_email) > 0) {
    $row = mysqli_fetch_assoc($check_email);
    $_SESSION["user_id"] = $row['id'];
    if(count($errors) === 0){
      $encpass = password_hash($password, PASSWORD_BCRYPT);
      $code = rand(999999, 111111);
      $status='1';
      $update_data =  "UPDATE users SET code=$code, status = '$status' WHERE email='$email'";
      $data_check = mysqli_query($conn, $update_data);
      if($data_check){
          $subject = "OTP Code - WEBMAPCSU";
          $message = "Your OTP is $code";
          $sender = "From: villacortalynn8@gmail.com";
          if(mail($email, $subject, $message, $sender)){
              $info = "We've sent a verification code to your email - $email";
              $_SESSION['info'] = $info;
              $_SESSION['email'] = $email;
              $_SESSION['password'] = $password;
              header('location: otp.php');
              exit();
          }else{
              $errors['otp-error'] = "Failed while sending code!";
          }
      }else{
          $errors['db-error'] = "Failed while inserting data into database!";
      }
  }
  }else {
    echo "<script>alert('Login details is incorrect. Please try again.');</script>";
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
  
  <!-- Favicons -->
  <link href="assets/img/csu-icon.png" rel="icon">
  <link href="assets/img/csu-icon.png" rel="apple-touch-icon">

  <title>Registration | WebMapCSU</title>
</head>
<body>
  <div class="container">
    <div class="forms-container">
      <div class="signin-signup">
        <form action="" method="post" class="sign-in-form">
          <h2 class="title">Sign in</h2>
          <div class="input-field">
            <i class="fas fa-user"></i>
            <input type="text" placeholder="Email Address" name="signin_email" value="<?php echo $_POST['signin_email']; ?>" required />
          </div>
          <div class="input-field">
            <i class="fas fa-lock"></i>
            <input type="password" placeholder="Password" name="signin_password" value="<?php echo $_POST['signin_password']; ?>" required />
          </div>
          <input type="submit" value="Login" name="signin" class="btn solid" />
          <p style="display: flex;justify-content: center;align-items: center;margin-top: 20px;"><a href="forgot-password.php" style="color: greenyellow;">Forgot Password?</a></p>
        </form>
        <form action="" class="sign-up-form" method="post">
          <h2 class="title">Sign up</h2>
          <div class="input-field">
            <i class="fas fa-user"></i>
            <input type="text" placeholder="Full Name" name="signup_full_name" value="<?php echo $_POST["signup_full_name"]; ?>" required />
          </div>
          <div class="input-field">
            <i class="fas fa-envelope"></i>
            <input type="email" placeholder="Email Address" name="signup_email" value="<?php echo $_POST["signup_email"]; ?>" required />
          </div>
          <div class="input-field">
            <i class="fas fa-lock"></i>
            <input type="password" placeholder="Password" name="signup_password" value="<?php echo $_POST["signup_password"]; ?>" required />
          </div>
          <div class="input-field">
            <i class="fas fa-lock"></i>
            <input type="password" placeholder="Confirm Password" name="signup_cpassword" value="<?php echo $_POST["signup_cpassword"]; ?>" required />
          </div>
          <input type="submit" class="btn" name="signup" value="Sign up" />
        </form>
      </div>
    </div>

    <div class="panels-container">
      <div class="panel left-panel">
        <div class="content">
          <h3>Register</h3>
          <p>
          Create an account to have a virtual tour on our prestigious university. Navigate and track the building you are looking for.
          </p>
          <button class="btn transparent" id="sign-up-btn">
            Sign up
          </button>
        </div>
        <img src="img/log.svg" class="image" alt="" />
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
        <img src="img/c.svg" class="image" alt="" />
      </div>
    </div>
  </div>

  <script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script>
  <script src="js/app.js"></script>
</body>

</html>