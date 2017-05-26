<?php
 session_start();
    include('/shared/connection.php');
    if(!empty($_POST['myusername']) && !empty($_POST['mypassword'])){
        if(isset($_POST['submit'])){
            
                $username = $_POST['myusername'];
                $password = $_POST['mypassword'];
                $use = "SELECT username, password FROM users where username = '$username'";
                $result = mysqli_query($conn, $use);                
                
                $userow = mysqli_fetch_array($result);

            

                if(($userow['username'] == $username AND password_verify($password, $userow['password'] )) ){

                    $_SESSION['username'] = $userow['username'];
                    $_SESSION['id'] = $userow['id'];
                    
                        $message = "Login Success!";
                        echo "<script type='text/javascript'>
                        alert('$message');
                        
                        </script>";

                }
                else {
                    $message = "Login Failed!";
                        echo "<script type='text/javascript'>
                        var msg;
                        msg = '$message';
                        alert(msg);
                        
                        </script>";
                }
        } 
        else {
                header("Location: index.php");
                exit;
        }


    }
?>