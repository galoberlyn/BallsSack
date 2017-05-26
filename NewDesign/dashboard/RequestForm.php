<?php
include '../authorization.php';
include '../shared/connection.php';
$username=$_SESSION['username'];

if(isset($_POST['request'])){

	$queryName = "SELECT concat(firstname,' ',lastname) FROM users INNER JOIN user_details ON users.id = user_id where username = '$username'";
	$queryRes = mysqli_query($conn, $queryName);
	$queryRow = mysqli_fetch_array($queryRes);

	$username = $queryRow[0];
	$ReqNum =  $_POST['RSN'];
	$needDate = $_POST['date_need'];
	$reason = $_POST['reason'];

	$itemDesc = $_POST['description'];
	$quantity = $_POST['quantity'];

	$amount = $_POST['itemsAmount'];
	$status = $_POST['status'];
	$remarks = $_POST['remarks'];
	var_dump($type);


if( $type == 'Not For PO' ){
	$conOff = $_POST['concerned_office'];
}


	
}
	
?>
<html>
<head>
	<meta charset="UTF-8">
	<title>Request Form</title>
</head>
<?php
$type = $_POST['type'];

if(isset($type)){
	if( $type == 'Service'){
		echo "<h1> Request Form for Service </h1>";
	}
	else{
?>
	<body>
	<h1>Request Form</h1>
	<form id='requestForm' method="POST">
		<p>Request Number: <input name ="RSN" type = "number" required></p>
<?php 
	if( $type == 'Not For PO'){
?>
		<p>Concerned Office: <input name ="concerned_office" type = "number" required></p>
<?php
	}
?>
		<p>Date Needed: <input name = "date_need" type="date" required></p>
		<p>Purpose:<br> <textarea name ="reason" rows='4' cols='50' required></textarea></p>
			<table class="table table-striped custab">
				<thead>
					<tr>
				    	<th>Item</th>
				    	<th>Quantity</th> 
				  	</tr>
			  	</thead>
			  	<tbody id="items">
				  	<tr>
				  		<td><input type="text" name="description[]"></td>
				  		<td><input type="number" name="quantity[]"></td>
				  		<td><button style="display: none;" type="button" onclick="event.srcElement.parentElement.parentElement.remove();rmv()" class='btn btn-danger' >Delete</button></td>
				  	</tr>
			  	</tbody>
			</table>
			<button type="button" class='btn btn-info' onclick="addItem()">Add another Item</button>
		<button name="request">Submit</button>
	</form>
</body>
<?php
	}
}
?>

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