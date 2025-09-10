<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/animations.css">  
    <link rel="stylesheet" href="css/main.css">  
    <link rel="stylesheet" href="css/signup.css">
        
    <title>Create an Account</title>
    <style>
        .container{
            animation: transitionIn-X 0.5s;
        }
    </style>
</head>
<body>
<?php

session_start();

$_SESSION["user"]="";
$_SESSION["usertype"]="";

// Set timezone
date_default_timezone_set('Africa/Douala');
$date = date('Y-m-d');

// Store current date in session
$_SESSION["date"]=$date;

// Import database connection
include("connection.php");

// Check if form submitted
if($_POST){

    // Select all existing users
    $result= $database->query("select * from webuser");

    // Retrieve personal info from session
    $fname=$_SESSION['personal']['fname'];
    $lname=$_SESSION['personal']['lname'];
    $name=$fname." ".$lname;
    $address=$_SESSION['personal']['address'];
    $nic=$_SESSION['personal']['nic'];
    $dob=$_SESSION['personal']['dob'];
    $email=$_POST['newemail'];
    $tele=$_POST['tele'];
    $newpassword=$_POST['newpassword'];
    $cpassword=$_POST['cpassword'];
    
    // Check if password and confirmation match
    if ($newpassword==$cpassword){
        // Check if account already exists with this email
        $sqlmain= "select * from webuser where email=?;";
        $stmt = $database->prepare($sqlmain);
        $stmt->bind_param("s",$email);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows==1){
            $error='<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">An account already exists for this email address.</label>';
        }else{
            // Insert new user into database
            $database->query("insert into patient(pemail,pname,ppassword, paddress, pnic,pdob,ptel) values('$email','$name','$newpassword','$address','$nic','$dob','$tele');");
            $database->query("insert into webuser values('$email','p')");

            // Save session info for logged-in user
            $_SESSION["user"]=$email;
            $_SESSION["usertype"]="p";
            $_SESSION["username"]=$fname;

            // Redirect to patient dashboard
            header('Location: patient/index.php');
            $error='<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;"></label>';
        }
        
    }else{
        // Error message if passwords do not match
        $error='<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Password confirmation error! Please confirm again.</label>';
    }

}else{
    // Form not submitted
    $error='<label for="promter" class="form-label"></label>';
}

?>

<center>
<div class="container">
    <table border="0" style="width: 69%;">
        <tr>
            <td colspan="2">
                <p class="header-text">Let's get started</p>
                <p class="sub-text">Create your user account now.</p>
            </td>
        </tr>
        <tr>
            <form action="" method="POST" >
            <td class="label-td" colspan="2">
                <label for="newemail" class="form-label">Email: </label>
            </td>
        </tr>
        <tr>
            <td class="label-td" colspan="2">
                <input type="email" name="newemail" class="input-text" placeholder="Email address" required>
            </td>
        </tr>
        <tr>
            <td class="label-td" colspan="2">
                <label for="tele" class="form-label">Phone Number: </label>
            </td>
        </tr>
        <tr>
            <td class="label-td" colspan="2">
                <input type="tel" name="tele" class="input-text"  placeholder="ex: 0712345678" pattern="[0]{1}[0-9]{9}" >
            </td>
        </tr>
        <tr>
            <td class="label-td" colspan="2">
                <label for="newpassword" class="form-label">Create a new password: </label>
            </td>
        </tr>
        <tr>
            <td class="label-td" colspan="2">
                <input type="password" name="newpassword" class="input-text" placeholder="New password" required>
            </td>
        </tr>
        <tr>
            <td class="label-td" colspan="2">
                <label for="cpassword" class="form-label">Confirm password: </label>
            </td>
        </tr>
        <tr>
            <td class="label-td" colspan="2">
                <input type="password" name="cpassword" class="input-text" placeholder="Confirm password" required>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <?php echo $error ?>
            </td>
        </tr>
        <tr>
            <td>
                <input type="reset" value="Reset" class="login-btn btn-primary-soft btn" >
            </td>
            <td>
                <input type="submit" value="Sign Up" class="login-btn btn-primary btn">
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <br>
                <label for="" class="sub-text" style="font-weight: 280;">Already have an account? </label>
                <a href="login.php" class="hover-link1 non-style-link">Log in</a>
                <br><br><br>
            </td>
        </tr>
                </form>
        </tr>
    </table>
</div>
</center>
</body>
</html>
