<?php
//include('php/connection.php');


$host = 'localhost';
$username = 'root';
$password = '';        
$database = 'forget_pass';

$conn = mysqli_connect($host, $username, $password, $database)or die("connection lost");
$res = '';
if(isset($_POST['forgot-pass'])){
    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
    $email = $_POST['user_email'];
    $select = "SELECT * FROM `user_registration` WHERE `user_email` = '$email'";
    $result = mysqli_query($conn,$select);
    if($result){
        $row = mysqli_fetch_array($result);
        $user_name = $row['user_name'];
        $pass ='';
        for ($i = 0; $i < 8; $i++) {
        $n = rand(0, strlen($alphabet)-1);
        $pass .= $alphabet[$n];
    }
    }
    if($pass){
        $select = "SELECT * FROM `mailer_setting` WHERE `id` = 1";   
        $result = mysqli_query($conn, $select);
        while($row = mysqli_fetch_array($result)){
        $mailer_name = $row['mailer_name'];
        $mailer_email = $row['mailer_email'];
        $mailer_pass = $row['mailer_pass'];
        $mailer_port = $row['smtp_port'];
        $mailer_host = $row['smtp_host'];
        $mailer_secure = $row['smtpsecure'];
        $message = $row['message'];
       
    }
        $subject = 'Reset Your Password';

        $message .='<p></p>';
        $message .= '<br><br><p>user name : '.$user_name.'</p><br><br><p>your New password : '.$pass.'</p><br><br><br>';
        
        require 'PHPMailer-master/PHPMailerAutoload.php';

        $mail = new PHPMailer;

        $mail->isSMTP();                                      
        $mail->Host = $mailer_host;  
        $mail->SMTPAuth = true;                               
        $mail->Username = $mailer_email;                 
        $mail->Password = $mailer_pass;                           
        $mail->SMTPSecure = $mailer_secure;                            
        $mail->Port = $mailer_port;                                   

        $mail->From = $mailer_email;
        $mail->FromName = $mailer_name;
        $mail->addAddress($email, $user_name);     
        
        $mail->isHTML(true);                                  

        $mail->Subject = $subject;
        $mail->Body    = $message;
        if(!$mail->send()) {
            $res =  'Message could not be sent.';
            $res .=  'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            $pass = md5($pass);
            $update = "UPDATE `user_registration` SET `pass` = '$pass' WHERE `user_email` = '$email'";
            $ok = mysqli_query($conn, $update);
            if($ok){
                 $res = 'Message Successfully Send To Your Email';
            }  else {
                $res = 'Error Try Again';
            }
           
        }
    }
} ?>
<!doctype html>
<html>
    <head>
        <title>login Page Poster</title>
        <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
        <link rel="stylesheet" href="style.css">
        <script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
        <script type="text/javascript" src="js/jquery-ui/jquery-ui.min.js"></script>
        <link type="text/css" href="js/jquery-ui/jquery-ui.min.css" rel="stylesheet" /> 
        <script src="js/bootstrap.min.js" type="text/javascript"></script>
        <script type="text/javascript" src="js/validation.js"></script>
       
    </head>
    <body>
     <div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&appId=601136476681840&version=v2.0";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
</script>     
        <!----------------------------- Header Part -------------------------------------->
         <div class="header container" style="min-height: 50px;padding-bottom:50px;">
             <div class="row">                
                 
             </div>
            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-9" style="padding-left: 0px; padding-right: 0px; margin-left: -15px;width: 77.75%">
                    <div class="row brdr headrow">
                <div class="logo col-md-4 nopadding">
                    <!--------- image for logo ------------>
                </div>
                <div class="heading col-md-8">
                    <h1>Forget Password </h1>
                </div>
                </div></div>
            </div>
        </div>
        <!----------------------------- Content Part -------------------------------------->
        <div class="container">
            <div class="row ">
                 <div class="col-md-2"></div>
                <div class="brdr col-md-9">
                    <div class="row">
                <div  class="col-md-5 brdrp">
                  <div class="fb-like-box brdrr" data-href="https://www.facebook.com/pages/Hereindia/1462455613984572?ref=bookmarks" data-width="300" data-colorscheme="light" data-show-faces="true" data-header="true" data-stream="false" data-show-border="false"></div>
                </div>
                 <div class="col-md-7">
                    
            <h3 class="newh3">Reset Password</h3>
            <form class="form-horizontal form_login" role="form" method="post" action="">
                   
                    <div class="form-group has-success has-feedback">
                      <label class="col-sm-12" style="color:#3C763D" for="Email">Your Login E-MAIl :</label>
                      <div class="col-sm-12">
                          <input type="email" class="form-control" id="user_email" name="user_email" aria-describedby="inputSuccess3Status" placeholder="E-mail Address">
                      </div>
                    </div>
                <button class="btn btn-primary" id="forgot-pass" type="submit" name="forgot-pass" style="margin-left: 0px;">Submit Email</button>
                <a class="btn btn-primary" href="index.php">Back to Login</a>
            </form>
                 </div> <div class="col-md-4"><?php echo $res ?></div> </div></div>
                 
            </div>
        </div>
        <!----------------------------Footer Part --------------------------------------->
        <div class="footer">
            
        </div>

    </body>
</html>