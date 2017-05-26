<?php
	$conn = mysqli_connect("localhost", "root","","scis_requisition_db");

	if (mysqli_connect_errno())
  	{
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  	}
?>