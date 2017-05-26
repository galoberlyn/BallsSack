<?php
include '../authorization.php';
include '../shared/connection.php';
$userdetails = "SELECT * from user_details";
$userdetailsqry = mysqli_query($conn,$userdetails);
$userArray = mysqli_fetch_array($userdetailsqry);
$_SESSION['firstname'] =  $userArray['firstname'];
$_SESSION['lastname'] =  $userArray['lastname'];


if(isset($_POST['type'])){
	$type = $_POST['type'];
	$_SESSION['type'] = $type;
}
if(isset($_POST['request'])){
		if($_SESSION['type'] == 'For PO'){
			echo "<script> alert('FOR PO'); </script>";
			$req_no = $_POST['req_no']; // request slip
			$date_needed = $_POST['date_needed']; // request slip
			$purpose = $_POST['purpose']; // request slip
			$description = $_POST['description']; // array per item boyyy
			$quantity = $_POST['quantity']; // array per item boyyy
			$name = $_SESSION['firstname'] . " " . $_SESSION['lastname'];


			$reqno = "INSERT into request_slip (rs_no, requested_by, date_needed, created_at,updated_at,purpose,status,type) VALUES 
			('$req_no', '$name', '$date_needed', now(),now(), '$purpose', 'Pending', 'PO')";
			$reqQry = mysqli_query($conn, $reqno);
			
			$request_form_id = mysqli_insert_id($conn);
			$poqry = "INSERT into purchase_order (request_id) VALUES ('$request_form_id')";
			$poResult = mysqli_query($conn, $poqry);

			$request_form_id2 = mysqli_insert_id($conn);
			for($i = 0; $i < count($quantity); $i++){
			$itemspo = "INSERT INTO itemspo (quantity, description, poid) VALUES ('$quantity[$i]', '$description[$i]', '$request_form_id2')";
			$itemsresult = mysqli_query($conn, $itemspo);

			}
		}else if($_SESSION['type'] == 'Not For PO'){
			echo "<script> alert('NOT FOR PO'); </script>";
			$req_no = $_POST['req_no']; // request slip
			$date_needed = $_POST['date_needed']; // request slip
			$purpose = $_POST['purpose']; // request slip
			$description = $_POST['description']; // array per item boyyy
			$quantity = $_POST['quantity']; // array per item boyyy
			$concerned_office = $_POST['concerned_office'];
			$name = $_SESSION['firstname'] . " " . $_SESSION['lastname'];

			$reqno = "INSERT into request_slip (rs_no, requested_by, date_needed, created_at,updated_at,purpose,status,type,ConcernedOffice) VALUES 
			('$req_no', '$name', '$date_needed', now(),now(), '$purpose', 'Pending', 'ItemsNoPO',$concerned_office)";
			$reqQry = mysqli_query($conn, $reqno);
			$request_form_id2 = mysqli_insert_id($conn);
			for($i = 0; $i < count($quantity); $i++){
			$itemspo = "INSERT INTO itemsnotpo (quantity, description, request_slip_no) VALUES ('$quantity[$i]', '$description[$i]', '$request_form_id2')";
			$itemsresult = mysqli_query($conn, $itemspo);

			}
		}else{
			echo "<script> alert('SERVICES'); </script>";
			$req_no = $_POST['req_no']; // request slip
			$date_needed = $_POST['date_needed']; // request slip
			$purpose = $_POST['purpose']; // request slip
			$description = $_POST['description']; // array per item boyyy
			$concerned_office = $_POST['concerned_office'];
			$name = $_SESSION['firstname'] . " " . $_SESSION['lastname'];


			$reqno = "INSERT into request_slip (rs_no, requested_by, date_needed, created_at,updated_at,purpose,status,type,ConcernedOffice) VALUES 
			('$req_no', '$name', '$date_needed', now(),now(), '$purpose', 'Pending', 'Service','$concerned_office')";
			$reqQry = mysqli_query($conn,$reqno);
			$request_form_id =mysqli_insert_id($conn);

			for($x = 0; $x < count($description); $x++){
				var_dump(count($description));
				var_dump($x);
				$description1 = "INSERT INTO services (description, requestID, status) VALUES ('$description[$x]', '$request_form_id', 'Pending')";
				$descresult = mysqli_query($conn,$description1);

			}


			
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Request Form</title>
</head>
<body>

<h1> Request form for <?php echo $_SESSION['type']?> </h1>
<form id='requestForm' method='POST' action="request_form.php">
	Request Number: <input type='number' name='req_no'><br>
	<?php if($_SESSION['type'] == 'Not For PO'){
				echo "Concered Office: <input type='text' name='concerned_office'><br>";
			} else if ($_SESSION['type'] == 'Service'){
				echo "Concered Office: <input type='text' name='concerned_office'><br>";
				
			}
	?>
	Date Needed: <input type="date" name="date_needed"><br>
	Purpose: <textarea rows='4' cols='50' name='purpose' placeholder="What is this request for?.."></textarea><br>
	<table class="table table-striped custab">
				<thead>
					<tr>
					<?php
				    	
				    	
				    	if($_SESSION['type'] != 'Service'){
					    	echo "<th>Item</th>";
					    	echo "<th>Quantity</th>"; 
				    	}else{
				    		echo "<th>Description</th>";
				    	}
				    	?>
				  	</tr>
			  	</thead>
			  	<tbody id="items">
				  	<tr>
				  		<td><input type='text' name='description[]'></td>
				  		<?php if($_SESSION['type'] != 'Service'){
					  			echo "<td><input type='number' name='quantity[]'></td>";
					  			}
				  		?>
				  		
				  		<td><button style="display: none;" type="button" onclick="event.srcElement.parentElement.parentElement.remove();rmv()" class='btn btn-danger' >Delete</button></td>
				  	</tr>
			  	</tbody>
			</table>
			<button type="button" class='btn btn-info' onclick="addItem()">Add another Item</button>
		<input type='submit' name='request'>

</form>

</body>
<script type="text/javascript">
(function() {
   // your page initialization code here
   // the DOM will be available here

})();



function addItem(){
	var tablebody = document.getElementById('items');
	if(tablebody.rows.length == 1){
		tablebody.rows[0].cells[tablebody.rows[0].cells.length-1].children[0].style.display="";
	}


	var tablebody = document.getElementById('items');
	var iClone = tablebody.children[0].cloneNode(true);
	for(var i = 0; i< iClone.cells.length; i++){
		iClone.cells[i].children[0].value ="";
	}

	tablebody.appendChild(iClone);
}

function rmv(){
	var tabRow = document.getElementById("items");
	if(tabRow.rows.length == 1){
		tabRow.rows[0].cells[tabRow.rows[0].cells.length-1].children[0].style.display="none";
	}
	else{
		tabRow.rows[0].cells[tabRow.rows[0].cells.length-1].children[0].style.display="";
	}
}

</script>
</html>