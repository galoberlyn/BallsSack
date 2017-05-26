<?php 
include '../shared/connection.php';
include '../authorization.php';

$username = $_SESSION['username'];
	
		$user_pass = "Select password from users where username = '$username';";     
        $user_passQ = mysqli_query($conn,$user_pass) or die(mysqli_error($conn));
    
        if (isset($_POST['newpass']) && isset($_POST['connewpass']) && isset($_POST['oldpass'])){
            $newpass = $_POST['newpass'];
            $connewpass = $_POST['connewpass'];
            $oldpass = $_POST['oldpass'];
        }
        
        $user = mysqli_fetch_array($user_passQ);
        
        echo "<h2>Change password</h2>";
        echo "<form method='POST' action = 'changepass.php'>";  
        echo "Current Password:<br>" . "<input type='password' name='oldpass'> <br>";
        echo "New Password:<br>" . "<input type='password' name='newpass'> <br>"; 
        echo "Confirm New Password<br>" . "<input type='password' name='connewpass'> <br>";
        echo "<input type='submit' name='change'  class='btn btn-primary'value='Change'>";
        echo "</form>";
    
        if(isset($_POST['change'])){
    
            if ($newpass == $connewpass &&
            password_verify($oldpass,$user['password'])) {
                
                $newpass = password_hash($_POST['newpass'],PASSWORD_DEFAULT);
                $updatepass = "UPDATE users SET password = '$newpass' where username = '$username'";
                $upadatepassQ = mysqli_query($conn, $updatepass);
                echo "success";
                session_unset();
                session_destroy();
                header("Location: ../index.php");
                exit;

            } else {
                echo "Password DOESN'T match!";
            }
        }

?>