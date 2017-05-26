<?php
	if (isset($_POST['login'])){
	include 'connection.php';
		$username = $_POST['username'];
		$password = $_POST['password'];

		$querystring = "SELECT id,username,user_type,password FROM users WHERE username='$username' AND user_status='active' LIMIT 1";
		$query = mysqli_query($conn,$querystring);
		if($query){
			$result = mysqli_num_rows($query);
			if($result > 0){
				$row = mysqli_fetch_array($query,MYSQLI_ASSOC);	
				if(password_verify($password,$row['password'])){
				
						//store sessions
						session_start();
						$_SESSION['user_id'] = $row['id'];
						$_SESSION['user_type'] = $row['user_type'];
						//redirect to dashboard
						header("Location: dashboard/home.php");
						
				}else{ //maling password
					echo "<script> alert('Wrong password!') </script>";
				}
			}else{
					echo "<script> alert('Username disabled, Please contact the administrator!') </script>";
			}

		}else{
			$result = 0;
		}
		
	
	}
?>