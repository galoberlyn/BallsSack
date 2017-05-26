<?php
include '../shared/connection.php';
include '../authorization.php';

$request_id= $_GET['request_id'];
$requestSlip = "SELECT * from request_slip where id='$request_id'";

$requestSlipQry = mysqli_query($conn,$requestSlip) or die(mysqli_error($conn));
$reqSlipArr = mysqli_fetch_array($requestSlipQry);

if($reqSlipArr['type'] == 'PO'){
	$po = "SELECT * from purchase_order where request_id ='$request_id'";
	$poQry = mysqli_query($conn, $po) or die(mysqli_error($conn));
	$poArr = mysqli_fetch_array($poQry);

	$items = "SELECT * from itemspo where poid = '$poArr[id]'";

}
else if($reqSlipArr['type'] == 'ItemsNoPO'){
	$items = "SELECT * from itemsnotpo where request_slip_no = '$request_id'";

}
else{
	$items = "SELECT * from services where requestID = '$request_id'";
}
$itemsQry = mysqli_query($conn, $items) or die(mysqli_error($conn));


?>
<!DOCTYPE html>
<html>
<head>
	<title>details of request</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- Bootstrap -->
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- styles -->
    <link href="../css/styles.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand">SCIS Requisition System</a>
    </div>
    
    <ul class="nav navbar-nav"> 
        <li class="current"><a href="dashboard.php"><i class="glyphicon glyphicon-home"></i> Dashboard</a></li>
        <li><a href="" data-toggle="modal" data-target="#addreq"><i class="glyphicon glyphicon-plus"></i> Add New Request</a></li>
    </ul>
    
      <!-- Add New Request Modal -->
  <div class="modal fade"  id="addreq" role="dialog">
    <div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="lgmodal">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add New Request</h4>
        </div>
     
     <div class="request_form">
         <form>
            <div class="table-responsive">
                
             <table class="table">
				<thead>
					<tr>
				    	<th>Quantity</th>
				    	<th>Item</th> 
                        <th>Description</th>
                        <th>Concerned Office</th>
				    	<th>Status</th>
				    	<th>Remarks</th>
				    	
				  	</tr>
			  	</thead>
			  	<tbody id="items">
				  	<tr>
				  		<td><input type="text" name="quantity[]"></td>
				  		<td><input type="text" name="item[]"></td>
				  		<td><input type="text" name="description[]"></td>
				  		<td><input type="text" name="concerned_office[]"></td>
				  		<td><select name="status[]">
				  			<option value="Pending">Pending</option>
				  			<option value="Canceled">Canceled</option>
				  			<option value="For PO">For PO</option>
				  			<option value="In-Progress">In-Progress</option>
				  			<option value="Completed">Completed</option>
				  		</select></td>
				  		<td><textarea rows='4' cols='50' name="remarks[]"></textarea></td>
				  		<td><button style="display: none;" type="button" onclick="event.srcElement.parentElement.parentElement.remove();rmv()" class='btn btn-danger' >Delete</button></td>
				  	</tr>
			  	</tbody>
			</table>
           
           
            </div>
			
            <button type="reset" class='btn btn-warning' onclick="addItem()">Add another Item</button>
            
            <h5 id="para">Use of Item:</h5> <br><textarea name="reason" rows="4" cols="50" id="use"></textarea><br>
			<h6 id="para">Date needed: </h6> <input type="date" name="date_needed ">  <br>
            <br>
            <input type="reset" name="addrequest" class='btn btn-warning' class="request_form" value="Submit Request">
            <br><br>
         </form>
             </div>
             
             <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
         
     </div>
      </div>
    </div>
    
    
  </div>
    
    <div class="floatright"> 
        
    <ul class="nav navbar-nav">
	                      <li class="dropdown" class="nav-item ">
                            <a href='../logout.php' style="float:right;">Logout</a>	
	                      </li>
	                    </ul>
    </div>
  
</nav>

<div class="wrapper">
        
        <div class="content">
            <div class="details">
                <div class="requester">
                  
                   <fieldset>
                       <legend>Request Information</legend>
                  
                       <span ><h4>Items for Request No:</h4><?php echo $reqSlipArr['rs_no'] ?></span>
                    
                    <div>
                        <span><h4>Requested By: </h4> <?php echo $reqSlipArr['requested_by'] ?></span>
                    </div>

                   </fieldset>
                    <div>
                        <span><h4>Date Needed: </h4>  <?php echo $reqSlipArr['date_needed'] ?></span>
                    </div>
                    
                    <div>
                        <span><h4>Purpose: </h4> <?php echo $reqSlipArr['purpose'] ?></span>
                    </div>
                    
                    <div>
                        <span><h4>Type: </h4> <?php echo $reqSlipArr['type'] ?></span>
                    </div>

                    <div>
                       <?php
if($reqSlipArr['type'] == 'ItemsNoPO' || $reqSlipArr['type'] == 'Service'){
	echo "<h2>Care of: </h2> ".$reqSlipArr['ConcernedOffice'];
}
else{
	echo "<h4>Purchase Order Number: </h4> ".$poArr['po_no'];
	echo "<h4>Date of Purchase Order: </h4> ".$poArr['date_of_po'];
	echo "<h4>Supplier: </h4> ".$poArr['supplier'];
}
$inc = 0;
?>
<h4>Status: </h4> <?php echo $reqSlipArr['status'] ?>

<br>
<hr>
<fieldset>
<legend>Items:</legend>    
</fieldset>

<div class="row">
  <div class="col-md-4">.col-md-4</div>
  <div class="col-md-4">.col-md-4</div>
  <div class="col-md-4">.col-md-4</div>
</div>
<?php

if($reqSlipArr['type'] == 'PO'){

	while($itemsArr = mysqli_fetch_array($itemsQry)){
		echo "<div>";
		echo "<form method='POST'>";
        echo "<div class='row'>";
        echo "<div class='col-md-4'>";  
		echo    "<p>Item Name:  </p>";
        echo "</div>";
        echo "<div class='col-md-4'>";
        echo "<span id ='desc".$inc."'>".$itemsArr['description']."</span>";
		echo "</div>";
        echo "</div>";
        
        
        echo "<div class='row'>";
        echo "<div class='col-md-4'>";  
		echo "<p>Quantity:  </p>";
        echo "</div>";
        echo "<div class='col-md-4'>";
        echo "<span id ='quantity".$inc."'>".$itemsArr['quantity']."</span>";
		echo "</div>";
        echo "</div>";
        
        
		echo    "<p>Quantity: </p>";
        echo    "<span id ='quantity".$inc."'>".$itemsArr['quantity']."</span>";
		echo	"<p>Location: <span id ='loc".$inc."'>".$itemsArr['Location']."</span> </p>";
		echo	"<p>Unit Price: <span id ='uPrice".$inc."'>".$itemsArr['unitprice']."</span> </p>";
		echo	"<p>Amount: <span id ='amount".$inc."'>".$itemsArr['amount']."</span> </p>";
		echo    "<p>Item Status: <span id ='iStat".$inc."'>".$itemsArr['itemspostatus']."</span> </p>";
		echo	"<p>Remarks: <span id ='remark".$inc."'>".$itemsArr['remarks']."</span> </p>";
		echo    "<button name ='save' style='display:none' id = 'save".$inc."' value = '".$itemsArr['iditemspo']."'>Save</button>";
		echo "</form>";
		echo    "<button onclick='editInput(".$inc.")'id = 'edit".$inc."'>Edit</button>";
        echo "</div>";
		echo "<br><br>";
		$inc++;
	}
}
else if($reqSlipArr['type'] == 'ItemsNoPO'){

		while($itemsArr = mysqli_fetch_array($itemsQry)){
		echo "<div>"; 
		echo "<form method='POST'>";
		echo    "<p>Item Name: <span clas ='desc".$inc."'>".$itemsArr['description']."</span> </p>";
		echo    "<p>Quantity: <span id ='quantity".$inc."'>".$itemsArr['quantity']."</span> </p>";
		echo	"<p>Date Delivered: <span id ='dateAccomp".$inc."'>".$itemsArr['date_accomplished']."</span> </p>";
		echo	"<p>Amount: <span id ='amount".$inc."'>".$itemsArr['amount']."</span> </p>";
		echo    "<p>Item Status: <span id ='iStat".$inc."'>".$itemsArr['itemStatus']."</span> </p>";
		echo	"<p>Remarks: <span id ='remark".$inc."'>".$itemsArr['remarks']."</span> </p>";
		echo    "<input name ='save' style='display:none' type='submit'  id = 'save' value = 'Save'>";
		echo "</form>";
		echo    "<button onclick='editInput(".$inc.")' id = 'edit".$inc."'>Edit</button>";
		echo "</div>";
		echo "<br><br>";
		$inc++;
	}

}
else{

		while($itemsArr = mysqli_fetch_array($itemsQry)){
		echo "<div>";
		echo "<form method='POST'>"; 
		echo    "<p>Service Name: <span id ='desc".$inc."'>".$itemsArr['description']."</span> </p>";
		echo    "<p>Service Status: <span id ='iStat".$inc."'>".$itemsArr['status']."</span> </p>";
		echo 	"<p>Date Completed: <span id ='dateComplete".$inc."'>".$itemsArr['date_completed']."</span></p>";
		echo	"<p>Remarks: <span id ='remark".$inc."'>".$itemsArr['remarks']."</span> </p>";
		echo    "<input name ='save' style='display:none' type='submit'  id = 'save' value = 'Save'>";
		echo "</form>";
		echo    "<button onclick='editInput(".$inc.")' id = 'edit".$inc."'>Edit</button>";
		echo "</div>";
		echo "<br><br>";
		$inc++;
	}

}
?>
<script>
	

function editInput(index){
 	var butt = document.getElementById('edit'+index);
 	butt.style.display = "none";
 	var description = document.getElementById('desc'+index);
 	var descText = description.textContent;

 	var stat = document.getElementById('iStat'+index);
 	var statText = stat.textContent;

 	var remarks = document.getElementById('remark'+index);
 	var remarksText = remarks.textContent;

 	var quantity;
 	var quantityText;

 	var location;
 	var locationText;

 	var unitPrice;
 	var unitPriceText;

 	var amount;
 	var amountText;

 	var dateAccomp;
 	var dateAccompText;

 	var dateComp;
 	var dateCompText;

 	var dateDel;
 	var dateDelText;

 	if(document.getElementById('quantity'+index)){
 		quantity = document.getElementById('quantity'+index);
 		quantityText = quantity.textContent;
 		
 	}
 	if(document.getElementById('loc'+index)){
 		location = document.getElementById('loc'+index);
 		locationText = location.textContent;
 	}

 	if(document.getElementById('uPrice'+index)){
 		unitPrice = document.getElementById('uPrice'+index);
 		unitPriceText  = unitPrice.textContent;
 	}

 	if(document.getElementById('amount'+index)){
 		amount = document.getElementById('amount'+index);
 		amountText = amount.textContent;
 	}

 	if(document.getElementById('dateAccomp'+index)){
 		dateAccomp = document.getElementById('dateAccomp'+index);
 		dateAccompText = dateAccomp.textContent;
 	}

 	if(document.getElementById('dateComplete'+index)){
 		dateComp = document.getElementById('dateComplete'+index);
 		dateCompText = dateComp.textContent;
 	}

 	if(quantity != undefined && location != undefined && unitPrice != undefined && amount != undefined){

 		description.innerHTML = "<input name = 'description' type = 'text' value= '"+descText+"'>";
 		quantity.innerHTML = "<input name = 'quantity' type = 'text' value = '"+quantityText+"'>";
 		location.innerHTML = "<input name = 'location' type = 'text' value = '"+locationText+"'>";
 		unitPrice.innerHTML = "<input name = 'unitPrice' type = 'text' value = '"+unitPriceText+"'>";
 		amount.innerHTML = "<input name = 'amount' type = 'text' value = '"+amountText+"'>";
 		stat.innerHTML = "<input name = 'status' type = 'text' value = '"+statText+"'>";
 		remarks.innerHTML = "<input name = 'remarks' type = 'text' value = '"+remarksText+"'>";
 	}
 	else if(quantity != undefined && dateDel != undefined && amount != undefined){

 		description.innerHTML = "<input name = 'description' type = 'text' value= '"+descText+"'>";
 		quantity.innerHTML = "<input name = 'quantity' type = 'text' value= '"+descText+"'>";
 		dateDel.innerHTML = "<input name = 'dateDel' type = 'text' value= '"+dateDelText+"'>";
 		amount.innerHTML = "<input name = 'amount' type = 'text' value= '"+amountText+"'>";
 		stat.innerHTML = "<input name = 'status' type = 'text' value= '"+statText+"'>";
 		remarks.innerHTML = "<input name = 'remarks' type = 'text' value= '"+remarksText+"'>";

 	}
 	else if(dateComp != undefined){

 		description.innerHTML = "<input name = 'description' type = 'text' value= '"+descText+"'>";
 		dateComp.innerHTML = "<input name ='dateComp' type = 'text' value= '"+dateCompText+"'>";
 		stat.innerHTML = "<input name ='status' type = 'text' value= '"+statText+"'>";
 		remarks.innerHTML = "<input name ='remarks' type = 'text' value= '"+remarksText+"'>";
 	}

 	document.getElementById('save'+index).style.display = "";

}
</script>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>

<?php
include 'editInput.php';
?>