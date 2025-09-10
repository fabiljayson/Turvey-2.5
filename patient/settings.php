<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/animations.css">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/admin.css">
    <title>Settings</title>
    <style>
        .dashbord-tables{
            animation: transitionIn-Y-over 0.5s;
        }
        .filter-container{
            animation: transitionIn-X  0.5s;
        }
        .sub-table{
            animation: transitionIn-Y-bottom 0.5s;
        }
    </style>
</head>
<body>
<?php
session_start();

if(isset($_SESSION["user"])){
    if(($_SESSION["user"])=="" or $_SESSION['usertype']!='p'){
        header("location: ../login.php");
    }else{
        $useremail=$_SESSION["user"];
    }
}else{
    header("location: ../login.php");
}

// import database
include("../connection.php");
$sqlmain= "select * from patient where pemail=?";
$stmt = $database->prepare($sqlmain);
$stmt->bind_param("s",$useremail);
$stmt->execute();
$result = $stmt->get_result();
$userfetch=$result->fetch_assoc();
$userid= $userfetch["pid"];
$username=$userfetch["pname"];
?>
<div class="container">
    <div class="menu">
        <table class="menu-container" border="0">
            <tr>
                <td style="padding:10px" colspan="2">
                    <table border="0" class="profile-container">
                        <tr>
                            <td width="30%" style="padding-left:20px" >
                                <img src="../img/user.png" alt="" width="100%" style="border-radius:50%">
                            </td>
                            <td style="padding:0px;margin:0px;">
                                <p class="profile-title"><?php echo substr($username,0,13)  ?>..</p>
                                <p class="profile-subtitle"><?php echo substr($useremail,0,22)  ?></p>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                            <a href="../logout.php" ><input type="button" value="Logout" class="logout-btn btn-primary-soft btn"></a>
                            </td>
                        </tr>
                </table>
                </td>
            </tr>
            <tr class="menu-row" >
                <td class="menu-btn menu-icon-home " >
                    <a href="index.php" class="non-style-link-menu "><div><p class="menu-text">Home</p></div></a>
                </td>
            </tr>
            <tr class="menu-row">
                <td class="menu-btn menu-icon-doctor">
                    <a href="doctors.php" class="non-style-link-menu"><div><p class="menu-text">All Doctors</p></div></a>
                </td>
            </tr>
            <tr class="menu-row" >
                <td class="menu-btn menu-icon-session">
                    <a href="schedule.php" class="non-style-link-menu"><div><p class="menu-text">Scheduled Sessions</p></div></a>
                </td>
            </tr>
            <tr class="menu-row" >
                <td class="menu-btn menu-icon-appoinment">
                    <a href="appointment.php" class="non-style-link-menu"><div><p class="menu-text">My Bookings</p></div></a>
                </td>
            </tr>
            <tr class="menu-row" >
                <td class="menu-btn menu-icon-settings  menu-active menu-icon-settings-active">
                    <a href="settings.php" class="non-style-link-menu  non-style-link-menu-active"><div><p class="menu-text">Settings</p></div></a>
                </td>
            </tr>
        </table>
    </div>
    <div class="dash-body" style="margin-top: 15px">
        <table border="0" width="100%" style=" border-spacing: 0;margin:0;padding:0;" >
            <tr >
                <td width="13%" >
                    <a href="settings.php" ><button  class="login-btn btn-primary-soft btn btn-icon-back"  style="padding-top:11px;padding-bottom:11px;margin-left:20px;width:125px"><font class="tn-in-text">Back</font></button></a>
                </td>
                <td>
                    <p style="font-size: 23px;padding-left:12px;font-weight: 600;">Settings</p>
                </td>
                <td width="15%">
                    <p style="font-size: 14px;color: rgb(119, 119, 119);padding: 0;margin: 0;text-align: right;">
                        Today's Date
                    </p>
                    <p class="heading-sub12" style="padding: 0;margin: 0;">
                        <?php 
                        date_default_timezone_set('Africa/Douala');
                        $today = date('Y-m-d');
                        echo $today;
                        ?>
                    </p>
                </td>
                <td width="10%">
                    <button  class="btn-label"  style="display: flex;justify-content: center;align-items: center;"><img src="../img/calendar.svg" width="100%"></button>
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <center>
                    <table class="filter-container" style="border: none;" border="0">
                        <tr><td colspan="4"><p style="font-size: 20px">&nbsp;</p></td></tr>
                        <tr>
                            <td style="width: 25%;">
                                <a href="?action=edit&id=<?php echo $userid ?>&error=0" class="non-style-link">
                                <div  class="dashboard-items setting-tabs"  style="padding:20px;margin:auto;width:95%;display: flex">
                                    <div class="btn-icon-back dashboard-icons-setting" style="background-image: url('../img/icons/doctors-hover.svg');"></div>
                                    <div>
                                        <div class="h1-dashboard">Account Settings&nbsp;</div><br>
                                        <div class="h3-dashboard" style="font-size: 15px;">Edit account information and change password</div>
                                    </div>
                                </div>
                                </a>
                            </td>
                        </tr>
                        <tr><td colspan="4"><p style="font-size: 5px">&nbsp;</p></td></tr>
                        <tr>
                            <td style="width: 25%;">
                                <a href="?action=view&id=<?php echo $userid ?>" class="non-style-link">
                                <div  class="dashboard-items setting-tabs"  style="padding:20px;margin:auto;width:95%;display: flex;">
                                    <div class="btn-icon-back dashboard-icons-setting " style="background-image: url('../img/icons/view-iceblue.svg');"></div>
                                    <div>
                                        <div class="h1-dashboard">View Account Details</div><br>
                                        <div class="h3-dashboard"  style="font-size: 15px;">View your account's personal information</div>
                                    </div>
                                </div>
                                </a>
                            </td>
                        </tr>
                        <tr><td colspan="4"><p style="font-size: 5px">&nbsp;</p></td></tr>
                        <tr>
                            <td style="width: 25%;">
                                <a href="?action=drop&id=<?php echo $userid.'&name='.$username ?>" class="non-style-link">
                                <div  class="dashboard-items setting-tabs"  style="padding:20px;margin:auto;width:95%;display: flex;">
                                    <div class="btn-icon-back dashboard-icons-setting" style="background-image: url('../img/icons/patients-hover.svg');"></div>
                                    <div>
                                        <div class="h1-dashboard" style="color: #ff5050;">Delete Account</div><br>
                                        <div class="h3-dashboard"  style="font-size: 15px;">Will permanently delete your account</div>
                                    </div>
                                </div>
                                </a>
                            </td>
                        </tr>
                    </table>
                    </center>
                </td>
            </tr>
        </table>
    </div>
</div>

<?php 
if($_GET){
    $id=$_GET["id"];
    $action=$_GET["action"];
    if($action=='drop'){
        $nameget=$_GET["name"];
        echo '
        <div id="popup1" class="overlay">
                <div class="popup">
                <center>
                    <h2>Are you sure?</h2>
                    <a class="close" href="settings.php">&times;</a>
                    <div class="content">
                        You want to delete your account<br>('.substr($nameget,0,40).').
                    </div>
                    <div style="display: flex;justify-content: center;">
                    <a href="delete-account.php?id='.$id.'" class="non-style-link"><button  class="btn-primary btn"  style="display: flex;justify-content: center;align-items: center;margin:10px;padding:10px;">Yes</button></a>&nbsp;&nbsp;&nbsp;
                    <a href="settings.php" class="non-style-link"><button  class="btn-primary btn"  style="display: flex;justify-content: center;align-items: center;margin:10px;padding:10px;">No</button></a>
                    </div>
                </center>
        </div>
        </div>
        ';
    }elseif($action=='view'){
        $sqlmain= "select * from patient where pid=?";
        $stmt = $database->prepare($sqlmain);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row=$result->fetch_assoc();
        $name=$row["pname"];
        $email=$row["pemail"];
        $address=$row["paddress"];
        $dob=$row["pdob"];
        $nic=$row['pnic'];
        $tele=$row['ptel'];
        echo '
        <div id="popup1" class="overlay">
                <div class="popup">
                <center>
                    <a class="close" href="settings.php">&times;</a>
                    <div class="content">
                        DOCTO LINK Web App
                    </div>
                    <div style="display: flex;justify-content: center;">
                    <table width="80%" class="sub-table scrolldown add-doc-form-container" border="0">
                        <tr>
                            <td>
                        <p style="padding: 0;margin: 0;text-align: left;font-size: 25px;font-weight: 500;">View Details.</p><br><br>
                            </td>
                        </tr>
                        <tr>
                            <td class="label-td" colspan="2">
                                <label class="form-label">Name: </label>
                                '.$name.'<br><br>
                            </td>
                        </tr>
                        <tr>
                            <td class="label-td" colspan="2">
                                <label class="form-label">Email: </label>
                                '.$email.'<br><br>
                            </td>
                        </tr>
                        <tr>
                            <td class="label-td" colspan="2">
                                <label class="form-label">NIC: </label>
                                '.$nic.'<br><br>
                            </td>
                        </tr>
                        <tr>
                            <td class="label-td" colspan="2">
                                <label class="form-label">Phone: </label>
                                '.$tele.'<br><br>
                            </td>
                        </tr>
                        <tr>
                            <td class="label-td" colspan="2">
                                <label class="form-label">Address: </label>
                                '.$address.'<br><br>
                            </td>
                        </tr>
                        <tr>
                            <td class="label-td" colspan="2">
                                <label class="form-label">Date of Birth: </label>
                                '.$dob.'<br><br>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <a href="settings.php"><input type="button" value="OK" class="login-btn btn-primary-soft btn" ></a>
                            </td>
                        </tr>
                    </table>
                    </div>
                </center>
                <br><br>
        </div>
        </div>
        ';
    }elseif($action=='edit'){
        $sqlmain= "select * from patient where pid=?";
        $stmt = $database->prepare($sqlmain);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row=$result->fetch_assoc();
        $name=$row["pname"];
        $email=$row["pemail"];
        $address=$row["paddress"];
        $nic=$row['pnic'];
        $tele=$row['ptel'];
        $error_1=$_GET["error"];
        $errorlist= array(
            '1'=>'<label class="form-label" style="color:rgb(255, 62, 62);text-align:center;">An account already exists for this email.</label>',
            '2'=>'<label class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Password confirmation error! Reconfirm the password</label>',
            '3'=>'<label class="form-label" style="color:rgb(255, 62, 62);text-align:center;"></label>',
            '4'=>"",
            '0'=>'',
        );
        echo '
        <div id="popup1" class="overlay">
                <div class="popup">
                <center>
                    <a class="close" href="settings.php">&times;</a> 
                    <div style="display: flex;justify-content: center;">
                    <div class="abc">
                    <table width="80%" class="sub-table scrolldown add-doc-form-container" border="0">
                        <tr>
                            <td class="label-td" colspan="2">'.$errorlist[$error_1].'</td>
                        </tr>
                        <tr>
                            <td>
                            <p style="padding: 0;margin: 0;text-align: left;font-size: 25px;font-weight: 500;">Edit User Account Details.</p>
                                User ID : '.$id.' (Auto-generated)<br><br>
                            </td>
                        </tr>
                        <form action="edit-user.php" method="POST" class="add-new-form">
                        <tr><td class="label-td" colspan="2">
                            <label>Email: </label>
                            <input type="hidden" value="'.$id.'" name="id00">
                            <input type="hidden" name="oldemail" value="'.$email.'" >
                            <input type="email" name="email" class="input-text" placeholder="Email Address" value="'.$email.'" required><br>
                        </td></tr>
                        <tr><td class="label-td" colspan="2">
                            <label>Name: </label>
                            <input type="text" name="name" class="input-text" placeholder="Name" value="'.$name.'" required><br>
                        </td></tr>
                        <tr><td class="label-td" colspan="2">
                            <label>NIC: </label>
                            <input type="text" name="nic" class="input-text" placeholder="NIC Number" value="'.$nic.'" required><br>
                        </td></tr>
                        <tr><td class="label-td" colspan="2">
                            <label>Phone: </label>
                            <input type="tel" name="Tele" class="input-text" placeholder="Phone Number" value="'.$tele.'" required><br>
                        </td></tr>
                        <tr><td class="label-td" colspan="2">
                            <label>Address: </label>
                            <input type="text" name="address" class="input-text" placeholder="Address" value="'.$address.'" required><br>
                        </td></tr>
                        <tr><td class="label-td" colspan="2">
                            <label>Password: </label>
                            <input type="password" name="password" class="input-text" placeholder="Set a password" required><br>
                        </td></tr>
                        <tr><td class="label-td" colspan="2">
                            <label>Confirm Password: </label>
                            <input type="password" name="cpassword" class="input-text" placeholder="Confirm password" required><br>
                        </td></tr>
                        <tr><td colspan="2">
                            <input type="reset" value="Reset" class="login-btn btn-primary-soft btn" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <input type="submit" value="Save" class="login-btn btn-primary btn">
                        </td></tr>
                        </form>
                    </table>
                    </div>
                    </div>
                </center>
                <br><br>
        </div>
        </div>
        ';
    }else{
        echo '
        <div id="popup1" class="overlay">
            <div class="popup">
            <center>
                <br><br><br><br>
                <h2>Modification Successful!</h2>
                <a class="close" href="settings.php">&times;</a>
                <div class="content">
                    If you also change your email, please log out and log in again with your new email.
                </div>
                <div style="display: flex;justify-content: center;">
                    <a href="settings.php" class="non-style-link"><button  class="btn-primary btn"  style="display: flex;justify-content: center;align-items: center;margin:10px;padding:10px;">OK</button></a>
                    <a href="../logout.php" class="non-style-link"><button  class="btn-primary-soft btn"  style="display: flex;justify-content: center;align-items: center;margin:10px;padding:10px;">Logout</button></a>
                </div>
            </center>
            </div>
        </div>
        ';
    }
}
?>

</body>
</html>
