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
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
            <nav class="navbar navbar-inverse">
            <div class="container-fluid">
                <div class="navbar-header"> <a class="navbar-brand">SCIS Requisition Monitoring</a> </div>
                <ul class="nav navbar-nav">
                    <li class="current"><a href="dashboard.php"><i class="glyphicon glyphicon-home"></i> Dashboard</a></li>
                    <li><a href="" data-toggle="modal" data-target="#addreq"><i class="glyphicon glyphicon-plus"></i> Add New Request</a></li>
                </ul>
                </div>
    </nav>
    <link href="../css/styles.css">
<div class="wrapper2">
  <h2>Dynamic Tabs</h2>
  <p>To make the tabs toggleable, add the data-toggle="tab" attribute to each link. Then add a .tab-pane class with a unique ID for every tab and wrap them inside a div element with class .tab-content.</p>

  <ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#home">Not for PO</a></li>
    <li><a data-toggle="tab" href="#menu1">For PO</a></li>
    <li><a data-toggle="tab" href="#menu2">For Services</a></li>
  </ul>

  <div class="tab-content">
    <div id="home" class="tab-pane fade in active">
        <?php
                if(isset($_POST['request'])){
			$req_no = $_POST['req_no']; // request slip
			$date_needed = $_POST['date_needed']; // request slip
			$purpose = $_POST['purpose']; // request slip
			$description = $_POST['description']; // array per item boyyy
			$quantity = $_POST['quantity']; // array per item boyyy
			$concerned_office = $_POST['concerned_office'];
			$name = $_SESSION['firstname'] . " " . $_SESSION['lastname'];

			$reqno = "INSERT into request_slip (rs_no, requested_by, date_needed, created_at,updated_at,purpose,status,type,ConcernedOffice) VALUES 
			('$req_no', '$name', '$date_needed', now(),now(), '$purpose', 'Pending', 'ItemsNoPO','$concerned_office')";
			$reqQry = mysqli_query($conn, $reqno) or die(mysqli_error($conn));
			$request_form_id2 = mysqli_insert_id($conn);

			for($i = 0; $i < count($quantity); $i++){
				$itemspo = "INSERT INTO itemsnotpo (quantity, description, request_slip_no) VALUES ('$quantity[$i]', '$description[$i]', '$request_form_id2')";
				$itemsresult = mysqli_query($conn, $itemspo) or die(mysqli_error($conn));

			}
                }
?>
        
<h1> Request form for for NOT FOR PO</h1>
<form id='requestForm' method='POST' action="request_form.php">
	Request Number: <input type='number' name='req_no'><br>
	<?php
				echo "Concered Office: <input type='text' name='concerned_office'><br>";
	?>
	Date Needed: <input type="date" name="date_needed"><br>
	Purpose: <textarea rows='4' cols='50' name='purpose' placeholder="What is this request for?.."></textarea><br>
	<table class="table table-hover custab">
				<thead>
					<tr>
					<?php
				    	
				    	
					    	echo "<th>Item</th>";
					    	echo "<th>Quantity</th>"; 
				    		echo "<th>Description</th>";
				    	?>
				  	</tr>
			  	</thead>
			  	<tbody id="items">
				  	<tr>
				  		<td><input type='text' name='description[]'></td>
                        <?php
					  			echo "<td><input type='number' name='quantity[]'></td>";

				  		?>
				  		
				  		<td><button style="display: none;" type="button" onclick="event.srcElement.parentElement.parentElement.remove();rmv()" class='btn btn-danger' >Delete</button></td>
				  	</tr>
			  	</tbody>
			</table>
			<button type="button" class='btn btn-info' onclick="addItem()">Add another Item</button>
		<input type='submit' name='request'>

</form>
    </div>
    <div id="menu1" class="tab-pane fade">
        <?php
        if(isset($_POST['request'])){
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
            } }

        ?>
        
<h1> Request form for PO</h1>
<form id='requestForm' method='POST' action="request_form.php">
	Request Number: <input type='number' name='req_no'><br>
	<?php
				echo "Concered Office: <input type='text' name='concerned_office'><br>";
	?>
	Date Needed: <input type="date" name="date_needed"><br>
	Purpose: <textarea rows='4' cols='50' name='purpose' placeholder="What is this request for?.."></textarea><br>
	<table class="table table-hover custab">
				<thead>
					<tr>
					<?php
				    	
				    	
					    	echo "<th>Item</th>";
					    	echo "<th>Quantity</th>"; 
				    		echo "<th>Description</th>";
				    	?>
				  	</tr>
			  	</thead>
			  	<tbody id="items1">
				  	<tr>
				  		<td><input type='text' name='description[]'></td>
                        <?php
					  			echo "<td><input type='number' name='quantity[]'></td>";

				  		?>
				  		
				  		<td><button style="display: none;" type="button" onclick="event.srcElement.parentElement.parentElement.remove();rmv()" class='btn btn-danger' >Delete</button></td>
				  	</tr>
			  	</tbody>
			</table>
			<button type="button" class='btn btn-info' onclick="addItem1()">Add another Item</button>
		<input type='submit' name='request'>

</form>
    </div>
    <div id="menu2" class="tab-pane fade">
        
        <?php
                        if(isset($_POST['request'])){
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
        ?>
<h1> Request form for SERVICES</h1>
<form id='requestForm' method='POST' action="request_form.php">
	Request Number: <input type='number' name='req_no'><br>
	<?php
				echo "Concered Office: <input type='text' name='concerned_office'><br>";
	?>
	Date Needed: <input type="date" name="date_needed"><br>
	Purpose: <textarea rows='4' cols='50' name='purpose' placeholder="What is this request for?.."></textarea><br>
	<table class="table table-hover custab">
				<thead>
					<tr>
					<?php
				    	
				    	
					    	echo "<th>Item</th>";
					    	echo "<th>Quantity</th>"; 
				    		echo "<th>Description</th>";
				    	?>
				  	</tr>
			  	</thead>
			  	<tbody id="items2">
				  	<tr>
				  		<td><input type='text' name='description[]'></td>
                        <?php
					  			echo "<td><input type='number' name='quantity[]'></td>";

				  		?>
				  		
				  		<td><button style="display: none;" type="button" onclick="event.srcElement.parentElement.parentElement.remove();rmv()" class='btn btn-danger' >Delete</button></td>
				  	</tr>
			  	</tbody>
			</table>
			<button type="button" class='btn btn-info' onclick="addItem2()">Add another Item</button>
		<input type='submit' name='request'>

</form>

			

    </div>
  </div>
</div>

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
    <script type="text/javascript">
(function() {
   // your page initialization code here
   // the DOM will be available here

})();



function addItem1(){
	var tablebody1 = document.getElementById('items1');
	var tablebody1 = document.getElementById('items1');
	if(tablebody1.rows.length == 1){
		tablebody1.rows[0].cells[tablebody1.rows[0].cells.length-1].children[0].style.display="";
	}

    var tablebody1 = document.getElementById('items1');
	var iClone1 = tablebody1.children[0].cloneNode(true);
	for(var i = 0; i< iClone1.cells.length; i++){
		iClone1.cells[i].children[0].value ="";
	}

	tablebody1.appendChild(iClone1);
}

function rmv(){
	var tabRow1 = document.getElementById("items1");
	if(tabRow1.rows.length == 1){
		tabRow1.rows[0].cells[tabRow1.rows[0].cells.length-1].children[0].style.display="none";
	}
	else{
		tabRow1.rows[0].cells[tabRow1.rows[0].cells.length-1].children[0].style.display="";
	}
}

</script>
    <script type="text/javascript">
(function() {
   // your page initialization code here
   // the DOM will be available here

})();



function addItem2(){
	var tablebody2 = document.getElementById('items2');
	var tablebody2 = document.getElementById('items2');
	if(tablebody2.rows.length == 1){
		tablebody2.rows[0].cells[tablebody2.rows[0].cells.length-1].children[0].style.display="";
	}

    var tablebody2 = document.getElementById('items2');
	var iClone2 = tablebody2.children[0].cloneNode(true);
	for(var i = 0; i< iClone2.cells.length; i++){
		iClone2.cells[i].children[0].value ="";
	}

	tablebody2.appendChild(iClone2);
}

function rmv(){
	var tabRow2 = document.getElementById("items2");
	if(tabRow2.rows.length == 1){
		tabRow2.rows[0].cells[tabRow2.rows[0].cells.length-1].children[0].style.display="none";
	}
	else{
		tabRow2.rows[0].cells[tabRow2.rows[0].cells.length-1].children[0].style.display="";
	}
}

</script>
</html>
