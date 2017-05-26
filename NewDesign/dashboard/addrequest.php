<?php
include '../authorization.php';
include '../shared/connection.php';
$user = $_SESSION['username'];
$userdetails = "SELECT * from users inner join user_details on users.id=user_details.user_id where username='$user'";
$userdetailsqry = mysqli_query($conn,$userdetails);
$userArray = mysqli_fetch_array($userdetailsqry);
$firstname =  $userArray['firstname'];
$lastname =  $userArray['lastname'];


if(isset($_POST['type'])){
	$type = $_POST['type'];
	$_SESSION['type'] = $type;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Request</title>
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <!-- styles -->
        <link rel="stylesheet" href="../assets/css/style.css">
        <link rel="stylesheet" href="../assets/css/font-awesome.css">
        <link href="../css/styles.css" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
    <?php
                include "../header.php";
    ?>
   
    <section class="menu-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="navbar-collapse collapse ">
                        <ul id="menu-top" class="nav navbar-nav navbar-right">
                            <li><a class="" href="dashboard.php"><i class="glyphicon glyphicon-home"></i> Dashboard</a></li>
                            <li><a href="addrequest.php"><i class="glyphicon glyphicon-plus"></i> Add New Request</a></li>
                            <li><a data-toggle="modal" data-target="#changepass"><i class="glyphicon glyphicon-lock"></i> Change password</a></li>
                            <li><a href=""><i class="fa fa-info-circle"></i> About Us</a></li>
                            <li><a href="../logout.php">Log out</a></li>
                            

                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </section> 
    
    
    <div class="wrapper">
    <div class="content">
            <div class="details">
                <div class="requester">
                   
                    <div class="panel-body">
               <div class="col-md-14">
                        <h4 class="page-head-line">Add New Request</h4> </div>
                </div>
                        <ul class="nav nav-tabs">
    <li class="active" class><a data-toggle="tab" href="#home">Not for PO</a></li>
    <li><a data-toggle="tab" href="#menu1">For PO</a></li>
    <li><a data-toggle="tab" href="#menu2">For Services</a></li>
  </ul>
                   
                    <div class="tab-content">
    <div id="home" class="tab-pane fade in active">
        <?php
            if(isset($_POST['requestNoPO'])){
				$req_no = $_POST['req_no']; // request slip
				$date_needed = $_POST['date_needed']; // request slip
				$purpose = $_POST['purpose']; // request slip
				$description = $_POST['description']; // array per item boyyy
				$quantity = $_POST['quantity']; // array per item boyyy
				$supplier_nopo = $_POST['supplier_nopo'];
				$concerned_office = $_POST['concerned_office'];
				$name = $firstname . " " . $lastname;

				$reqno = "INSERT into request_slip (rs_no, requested_by, date_needed, created_at,updated_at,purpose,status,type,ConcernedOffice) VALUES 
				('$req_no', '$name', '$date_needed', now(),now(), '$purpose', 'Pending', 'ItemsNoPO','$concerned_office')";
				$reqQry = mysqli_query($conn, $reqno) or die(mysqli_error($conn));
				$request_form_id2 = mysqli_insert_id($conn);

				for($i = 0; $i < count($quantity); $i++){
					$itemspo = "INSERT INTO itemsnotpo (quantity, description, request_slip_no, supplier) VALUES ('$quantity[$i]', '$description[$i]', '$request_form_id2', '$supplier_nopo[$i]');";
					$itemsresult = mysqli_query($conn, $itemspo) or die(mysqli_error($conn));

				}
				echo "<script> window.location='dashboard.php'; </script>";
            }
?>
        
<h3>NOT FOR PO</h3>
<form id='requestForm' method='POST'>
	
	
        <div class='row'>
        <div class='col-lg-3'>
        <strong>Request Number</strong>
        </div>
        <div class='col-md-5'>
        <input type='number' name='req_no' required>
        </div>
        </div>
    
        <div class='row'>
        <div class='col-lg-3'>
        <strong>Concerned Office</strong>
        </div>
        <div class='col-md-5'>
        <input type='text' name='concerned_office' onkeyup="this.value = this.value.replace(/[&*<>,'.]/g, '')">
        </div>
        </div>
		
				
	
	    <div class="row">
        <div class="col-lg-3">
            <strong>Date Needed</strong>
        </div>
        <div class="col-md-5">
            <input type="date" name="date_needed">
        </div>    
	    </div>
	    
	    <div class="row">
        <div class="col-lg-3">
            <strong>Purpose</strong>
        </div>
        <div class="col-md-5">
            <textarea rows='4' cols='50' name='purpose' onkeyup="this.value = this.value.replace(/[&*<>,'.]/g, '')" placeholder="What is this request for?.."></textarea>
        </div>    
	    </div>
	
	<table class="table table-hover custab">
				<thead>
					<tr>
					<?php
				    	
				    	
					    	echo "<th>Item</th>";
					    	echo "<th>Quantity</th>";
					    	echo "<th>Supplier</th>"
				    	?>
				  	</tr>
			  	</thead>
	  		  	<tbody id="items">
				  	<tr>
				  		<td><input type='text' name='description[]' placeholder='itemname(pcs/box/rim)' onkeyup="this.value = this.value.replace(/[&*<>,'.]/g, '')" required></td>
                        <?php
					  			echo "<td><input type='number' name='quantity[]' required></td>";

				  		?>
				  		<td><input type='text' name='supplier_nopo[]' onkeyup="this.value = this.value.replace(/[&*<>,'.]/g, '')"></td>
				  		
				  		<td><button style="display: none;" type="button" onclick="event.srcElement.parentElement.parentElement.remove();rmv()" class='btn btn-danger' >Delete</button></td>
				  	</tr>
			  	</tbody>
			</table>
			<button type="button" class='btn btn-info' onclick="addItem()">Add another Item</button>
		
		<button type="submit" class='btn btn-info'  name='requestNoPO'>Submit</button>

</form>
    </div>
    <div id="menu1" class="tab-pane fade">
        <?php
        if(isset($_POST['requestPO'])){
			$req_no = $_POST['req_no']; // request slip
			$date_needed = $_POST['date_needed']; // request slip
			$purpose = $_POST['purpose']; // request slip
			$quantity = $_POST['quantity']; // array per item boyyy
			$description = $_POST['description']; // array per item boyyy
			$supplier_po = $_POST['supplier_po'];
			$name = $firstname . " " . $lastname;

			
			$reqno = "INSERT into request_slip (rs_no, requested_by, date_needed, created_at,updated_at,purpose,status,type) VALUES 
			('$req_no', '$name', '$date_needed', now(),now(), '$purpose', 'Pending', 'PO')";
			$reqQry = mysqli_query($conn, $reqno);
			
			$request_form_id = mysqli_insert_id($conn);
			$poqry = "INSERT into purchase_order (request_id) VALUES ('$request_form_id')";
			$poResult = mysqli_query($conn, $poqry);

			$request_form_id2 = mysqli_insert_id($conn);
			for($i = 0; $i < count($quantity); $i++){
				$itemspo = "INSERT INTO itemspo (quantity, description, poid, supplier_po) VALUES ('$quantity[$i]', '$description[$i]', '$request_form_id2', '$supplier_po[$i]');";
				$itemsresult = mysqli_query ($conn, $itemspo) or die(mysqli_error($conn));
            }
            echo "<script> window.location='dashboard.php'; </script>";
            }

        ?>
        
<h3> For PO</h3>
<form id='requestForm' method='POST'>

        <div class='row'>
        <div class='col-lg-3'>
        <strong>Request Number</strong>
        </div>
        <div class='col-md-5'>
        <input type='number' name='req_no'>
        </div>
        </div>
    
        <div class='row'>
        <div class='col-lg-3'>
        <strong>Concerned Office</strong>
        </div>
        <div class='col-md-5'>
        <input type='text' name='concerned_office' onkeyup="this.value = this.value.replace(/[&*<>,'.]/g, '')">
        </div>
        </div>
    
	
	<div class="row">
        <div class="col-lg-3">
            <strong>Date Needed</strong>
        </div>
        <div class="col-md-5">
            <input type="date" name="date_needed">
        </div>    
	    </div>
	    
	    <div class="row">
        <div class="col-lg-3">
            <strong>Purpose</strong>
        </div>
        <div class="col-md-5">
            <textarea rows='4' cols='50' name='purpose' placeholder="What is this request for?.." onkeyup="this.value = this.value.replace(/[&*<>,'.]/g, '')"></textarea>
        </div>    
	    </div>
	
	<table class="table table-hover custab">
				<thead>
					<tr>
					<?php
				    	
					    	echo "<th>Item</th>";
					    	echo "<th>Quantity</th>"; 
					    	echo "<th>Supplier</th>";
				    	?>
				  	</tr>
			  	</thead>
			  	<tbody id="items1">
				  	<tr>
				  		<td><input type='text' name='description[]'  placeholder='itemname(pcs/box/rim)' onkeyup="this.value = this.value.replace(/[&*<>,'.]/g, '')"></td>
                        <?php
					  			echo "<td><input type='number' name='quantity[]'></td>";

				  		?>
				  		<td><input type='text' name='supplier_po[]' onkeyup="this.value = this.value.replace(/[&*<>,'.]/g, '')"></td>

				  		<td><button style="display: none;" type="button" onclick="event.srcElement.parentElement.parentElement.remove();rmv()" class='btn btn-danger' >Delete</button></td>
				  	</tr>
			  	</tbody>
			</table>
			<button type="button" class='btn btn-info' onclick="addItem1()">Add another Item</button>
			
			<button type="submit" class='btn btn-info'  name='requestPO'>Submit</button>

</form>
    </div>
    <div id="menu2" class="tab-pane fade">
        
        <?php
            if(isset($_POST['requestService'])){
			$req_no = $_POST['req_no']; // request slip
			$date_needed = $_POST['date_needed']; // request slip
			$purpose = $_POST['purpose']; // request slip
			$description = $_POST['description']; // array per item boyyy
			$concerned_office = $_POST['concerned_office'];
			$time = $_POST['time_srv'];
			$supplier_srv = $_POST['supplier_srv'];
			$name = $firstname . " " . $lastname;

		    list($hours, $minutes) = split(':',$time);
		    $timeFinal;
		    if($hours >= 12){
		        if($hours > 12){
		          $hours -= 12;
		        }
		          $timeFinal = $hours.':'.$minutes." PM";

		    }
		    else{
		      if($hours == 0){
		        $hours = 12;
		        $timeFinal = $hours.':'.$minutes." AM";
		      }
		      else{
		        $timeFinal = $hours.':'.$minutes." AM";
		      }
		  }

			$reqno = "INSERT into request_slip (rs_no, requested_by, date_needed, time_needed, created_at,updated_at,purpose,status,type,ConcernedOffice) VALUES 
			('$req_no', '$name', '$date_needed', '$timeFinal', now(),now(), '$purpose', 'Pending', 'Service','$concerned_office')";
			$reqQry = mysqli_query($conn,$reqno) or die (mysqli_error($conn));
			$request_form_id =mysqli_insert_id($conn);

			for($x = 0; $x < count($description); $x++){
				var_dump(count($description));
				var_dump($x);
				$description1 = "INSERT INTO services (description, requestID, status, service_provider) VALUES ('$description[$x]', '$request_form_id', 'Pending', '$supplier_srv[$x]');";
				$descresult = mysqli_query($conn,$description1) or die (mysqli_error($conn));

			}
			echo "<script> window.location='dashboard.php'; </script>";
            }
        ?>
<h3> Services</h3>
<form id='requestForm' method='POST'>
	
    
        <div class='row'>
        <div class='col-lg-3'>
        <strong>Request Number</strong>
        </div>
        <div class='col-md-5'>
        <input type='number' name='req_no'>
        </div>
        </div>
    
        <div class='row'>
        <div class='col-lg-3'>
        <strong>Concerned Office</strong>
        </div>
        <div class='col-md-5'>
        <input type='text' name='concerned_office' onkeyup="this.value = this.value.replace(/[&*<>,'.]/g, '')">
        </div>
        </div>
			
	
	
	 <div class="row">
        <div class="col-lg-3">
            <strong>Date Needed</strong>
        </div>
        <div class="col-md-5">
            <input type="date" name="date_needed" onkeyup="this.value = this.value.replace(/[&*<>,'.]/g, '')" required>
        </div>    
	    </div>

	    <div class="row">
        <div class="col-lg-3">
            <strong>Time needed</strong>
        </div>
        <div class="col-md-5">
            <input type='time' name='time_srv' required>
        </div>    
	    </div>               
				  		

	    <div class="row">
        <div class="col-lg-3">
            <strong>Purpose</strong>
        </div>
        <div class="col-md-5">
            <textarea rows='4' cols='50' name='purpose' placeholder="What is this request for?.." onkeyup="this.value = this.value.replace(/[&*<>,'.]/g, '')"></textarea>
        </div>    
	    </div>
	    
	    <table class="table table-hover custab">
				<thead>
					<tr>
					<?php
				    	
				    	
					    	echo "<th>Service</th>";
					    	echo "<th>Service Provider</th>";
					    
				    	?>
				  	</tr>
			  	</thead>
			  	<tbody id="items2">
				  	<tr>
				  		<td><input type='text' name='description[]' onkeyup="this.value = this.value.replace(/[&*<>,'.]/g, '')" required></td>
				  		<td><input type='text' name='supplier_srv[]' onkeyup="this.value = this.value.replace(/[&*<>,'.]/g, '')" required></td>
				  		
				  		<td><button style="display: none;" type="button" onclick="event.srcElement.parentElement.parentElement.remove();rmv()" class='btn btn-danger' >Delete</button></td>
				  	</tr>
			  	</tbody>
			</table>
			<button type="button" class='btn btn-info' onclick="addItem2()">Add another Item</button>
		<button type="submit" class='btn btn-info'  name='requestService'>Submit</button>

</form>

			

    </div>
  </div>
                    </div>
               
           </div>
        </div>
    </div>]
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