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
	<title>Details of Request</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- Bootstrap -->
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <!-- styles -->
        <link href="../css/styles.css" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body onload = 'prepareAmount()'>

<div>
<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand">SCIS Requisition System</a>
    </div>
    
    <ul class="nav navbar-nav"> 
        <li class="current"><a href="dashboard.php"><i class="glyphicon glyphicon-home"></i> Dashboard</a></li>
        <li><a href="addrequest.php" ><i class="glyphicon glyphicon-plus"></i> Add New Request</a></li>
    </ul>
    
    
  </div>
    
    <div class="floatright"> 
        
    <ul class="nav navbar-nav">
	                      <li class="dropdown" class="nav-item ">
                            <a href='../logout.php' style="float:right;">Logout</a>	
	                      </li>
	                    </ul>
    </div>
  
</nav>
    </div>
    
    <div class="wrapper">
        <div class="content">
            <div class="details">
                <div class="requester">

<fieldset>
                       <legend>Request Information</legend>
                   </fieldset>

<div class="row">
                          <div class="col-lg-5">
                              <h4><strong>Items For Request No.</strong></h4>
                          </div>
                          <div class="col-md-5">
                              <h4><?php echo $reqSlipArr['rs_no'] ?></h4>
                          </div> 
                       </div>
                       
                       <div class="row">
                          <div class="col-lg-5">
                              <h4><strong>Requested By:</strong></h4>
                          </div>
                          <div class="col-md-5">
                              <h4><?php echo $reqSlipArr['requested_by'] ?></h4>
                          </div> 
                       </div>

<form method='POST'>
	<div class="row">
                          <div class="col-lg-5">
                              <h4><strong>Date Needed:</strong></h4>
                          </div>
                          <div class="col-md-5">
                              <h4 id ='dateNeeded'><?php echo $reqSlipArr['date_needed'] ?></h4>
                          </div> 
                       </div>
                       
                       <div class="row">
                          <div class="col-lg-5">
                              <h4><strong>Purpose</strong></h4>
                          </div>
                          <div class="col-md-5">
                              <h4 id='purpose'><?php echo $reqSlipArr['purpose'] ?></h4>
                          </div> 
                       </div>
	
	<div class="row">
                          <div class="col-lg-5">
                              <h4><strong>Type:</strong></h4>
                          </div>
                          <div class="col-md-5">
                              <h4><strong><?php 
	if($reqSlipArr['type'] == 'PO'){
		echo "<h4>For Purchase Order</h4>";
	}
	else if($reqSlipArr['type'] == 'ItemsNoPO'){
		echo "<h4>Not For Purchase Order</h4>";
	}
	else {
		echo "Service";
	} 
                                  ?>
                                  </strong></h4>
                          </div> 
                       </div>


	<?php
	if($reqSlipArr['type'] == 'ItemsNoPO' || $reqSlipArr['type'] == 'Service'){
        echo "<div class='row'>";
        echo "<div class='col-lg-5'>";
        echo "<h4><strong>Care of</strong></h4>";
        echo "</div>";
        echo "<div class='col-md-5'>";
        echo "<span id='careOF'><h4>" . $reqSlipArr['ConcernedOffice'] . "</h4><span>";
        echo "</div>";
        echo "</div>";
	}
	else{
        echo "<div class='row'>";
        echo "<div class='col-lg-5'>";
        echo "<h4><strong>Purchase Order Number</strong></h4>";
        echo "</div>";
        echo "<div class='col-md-5'>";
        echo "<span id='poNum'><h4>" .$poArr['po_no']. "</h4><span>";
        echo "</div>";
        echo "</div>";
        
        echo "<div class='row'>";
        echo "<div class='col-lg-5'>";
        echo "<h4><strong>Date of Purchase Order:</strong></h4>";
        echo "</div>";
        echo "<div class='col-md-5'>";
        echo "<span id='poDate'><h4>" .$poArr['date_of_po']. "</h4><span>";
        echo "</div>";
        echo "</div>";
        
        echo "<div class='row'>";
        echo "<div class='col-lg-5'>";
        echo "<h4><strong>Supplier:</strong></h4>";
        echo "</div>";
        echo "<div class='col-md-5'>";
        echo "<span id='poSupp'><h4>" .$poArr['supplier']. "</h4><span>";
        echo "</div>";
        echo "</div>";
        
        
	}
	$inc = 0;
	?>

	<div class="row">
                          <div class="col-lg-5">
                              <h4><strong>Status:</strong></h4>
                          </div>
                          <div class="col-md-5">
                              <span id ='slipStatus'><h4><?php echo $reqSlipArr['status'] ?></h4></span>
                          </div> 
                       </div>

	<button style= 'display:none' class="btn btn-default" name = 'saveInfo'>Save</button>
</form>
                    <button onclick='editInfo()' class="btn btn-default" name = 'editInfo'>Edit</button>


<h2>Total Amount:</h2><p id ="totalAmt"></p>

	<?php
	if($reqSlipArr['type'] != 'Service'){
		echo "<h2>Items: </h2>";
	}
	else{
		echo "<h2>Services: </h2>";
	}

	?>
	

<?php

if($reqSlipArr['type'] == 'PO'){

	while($itemsArr = mysqli_fetch_array($itemsQry)){
		echo "<div>";
		echo "<form method='POST'>";
        
		 echo "<div class='row'>";
        echo "<div class='col-lg-5'>";  
		echo "<h4><strong>Item Name:  </strong></h4>";
        echo "</div>";
        echo "<div class='col-md-5'>";
        echo "<span id ='desc".$inc."'><h4>".$itemsArr['description']."</h4></span>";
		echo "</div>";
        echo "</div>";
        
		echo "<div class='row'>";
        echo "<div class='col-md-5'>";  
		echo "<h4><strong>Quantity:  </strong></h4>";
        echo "</div>";
        echo "<div class='col-md-5'>";
        echo "<span id ='quantity".$inc."'><h4>".$itemsArr['quantity']."</h4></span>";
		echo "</div>";
        echo "</div>";
        
		 echo "<div class='row'>";
        echo "<div class='col-md-5'>";  
		echo "<h4><strong>Location:  </strong></h4>";
        echo "</div>";
        echo "<div class='col-md-5'>";
        echo "<span id ='loc".$inc."'><h4>".$itemsArr['Location']."</h4></span>";
		echo "</div>";
        echo "</div>";
        
		echo "<div class='row'>";
        echo "<div class='col-md-5'>";  
		echo "<h4><strong>Unit Price:  </strong></h4>";
        echo "</div>";
        echo "<div class='col-md-5'>";
        echo "<span id ='uPrice".$inc."'><h4>".$itemsArr['unitprice']."</h4></span>";
		echo "</div>";
        echo "</div>";
        
		echo	"<p>Amount: <span class ='amount'></span></p>";
        
		echo "<div class='row'>";
        echo "<div class='col-md-5'>";  
		echo "<h4><strong>Item Status:  </strong></h4>";
        echo "</div>";
        echo "<div class='col-md-5'>";
        echo "<span id ='iStat".$inc."'><h4>".$itemsArr['itemspostatus']."</h4></span>";
		echo "</div>";
        echo "</div>";
        
		echo 	"<p>Date Delivered: <span id='date_deli".$inc."'>".$itemsArr['date_complete']."</span></p>";
        
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
            
		 echo "<div class='row'>";
        echo "<div class='col-md-5'>";  
		echo    "<h4><strong>Item Name:  </strong></h4>";
        echo "</div>";
        echo "<div class='col-md-5'>";
        echo "<span id ='desc".$inc."'><h4>".$itemsArr['description']."</h4></span>";
		echo "</div>";
        echo "</div>";
            
		echo "<div class='row'>";
        echo "<div class='col-md-5'>";  
		echo "<h4><strong>Quantity:  </strong></h4>";
        echo "</div>";
        echo "<div class='col-md-5'>";
        echo "<span id ='quantity".$inc."'><h4>".$itemsArr['quantity']."</h4></span>";
		echo "</div>";
        echo "</div>";
            
		echo "<div class='row'>";
        echo "<div class='col-md-5'>";  
		echo "<h4><strong>Date Delievered:  </strong></h4>";
        echo "</div>";
        echo "<div class='col-md-5'>";
        echo "<span id ='dateAccomp".$inc."'><h4>".$itemsArr['date_accomplished']."</h4></span>";
		echo "</div>";
        echo "</div>";
            
		echo "<div class='row'>";
        echo "<div class='col-md-5'>";  
		echo "<h4><strong>Amount:  </strong></h4>";
        echo "</div>";
        echo "<div class='col-md-5'>";
        echo "<span id ='amount".$inc."'><h4>".$itemsArr['amount']."</h4></span>";
		echo "</div>";
        echo "</div>";
            
		echo "<div class='row'>";
        echo "<div class='col-md-5'>";  
		echo "<h4><strong>Item Status:  </strong></h4>";
        echo "</div>";
        echo "<div class='col-md-5'>";
        echo "<span id ='iStat".$inc."'><h4>".$itemsArr['itemStatus']."</h4></span>";
		echo "</div>";
        echo "</div>";
            
		echo "<div class='row'>";
        echo "<div class='col-md-5'>";  
		echo "<h4><strong>Remarks:  </strong></h4>";
        echo "</div>";
        echo "<div class='col-md-5'>";
        echo "<span id ='remark".$inc."'><h4>".$itemsArr['remarks']."</h4></span>";
		echo "</div>";
        echo "</div>";
            
		echo    "<button name ='save' style='display:none' id = 'save".$inc."' value = '".$itemsArr['id']."'>Save</button>";
            
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
    
    echo "<div class='row'>";        
		echo "<div class='col-md-5'>";  
		echo    "<h4><strong>Service Name:  </strong></h4>";
        echo "</div>";
        echo "<div class='col-md-5'>";
        echo "<span id ='desc".$inc."'>".$itemsArr['description']."</span>";
		echo "</div>";
        echo "</div>";

            
		echo "<div class='row'>";
        echo "<div class='col-md-5'>";  
		echo "<h4><strong>Service Status:  <strong></h4>";
        echo "</div>";
        echo "<div class='col-md-5'>";
        echo "<span id ='iStat".$inc."'>".$itemsArr['status']."</span>";
		echo "</div>";
        echo "</div>";
            
		echo "<div class='row'>";
        echo "<div class='col-md-5'>";  
		echo "<h4><strong>Date Completed:  </strong></h4>";
        echo "</div>";
        echo "<div class='col-md-5'>";
        echo "<span id ='dateComplete".$inc."'>".$itemsArr['date_completed']."</span>";
		echo "</div>";
        echo "</div>";
            
		echo "<div class='row'>";
        echo "<div class='col-md-5'>";  
		echo "<h4>Remarks:  </strong></h4>";
        echo "</div>";
        echo "<div class='col-md-5'>";
        echo "<span id ='remark".$inc."'>".$itemsArr['remarks']."</span>";
		echo "</div>";
        echo "</div>";
            
		echo    "<button name ='save' style='display:none' id = 'save".$inc."' value = '".$itemsArr['idServices']."'>Save</button>";
		echo "</form>";
		echo    "<button onclick='editInput(".$inc.")' id = 'edit".$inc."'>Edit</button>";
		echo "</div>";
		echo "<br><br>";
		$inc++;
	}

}
?>
<?php
echo "<a href='download_xls.php?request_id=" . $request_id . "'>Generate Excel File </a>";
?>

</div>
            </div>
        </div>
    </div>
<script>
	

function editInput(index){
 	var butt = document.getElementById('edit'+index);
 	butt.style.display = "none";
 	var description = document.getElementById('desc'+index);
 	var descText = description.textContent;

 	var stat = document.getElementById('iStat'+index);

 	if(stat.textContent == "Pending"){
 		var statIndex = 0;
 	}else if(stat.textContent == "Delivered"){
 		var statIndex = 1;
 	}
 	else{
 		var statIndex = 2;
 	}


 	var statText = stat.value;

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

 	var dateDeli;
 	var dateDeliText;


 	var opPend = document.createElement('option');
 	var opDel = document.createElement('option');
 	var opCan = document.createElement('option');
 	var opComp = document.createElement('option');

 	opPend.setAttribute('value','Pending');
 	opDel.setAttribute('value', 'Delivered');
 	opCan.setAttribute('value', 'Canceled');
 	opComp.setAttribute('value','Completed');

 	opPend.textContent = "Pending";
 	opDel.textContent = "Delivered";
 	opCan.textContent = "Canceled";
 	opComp.textContent = "Completed";

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


 	if(document.getElementById('date_deli'+index)){
 		dateDeli = document.getElementById('date_deli'+index);
 		dateDeliText = dateDeli.textContent;
 	}


 	if(quantity != undefined && location != undefined && unitPrice != undefined && dateDeli != undefined){

 		description.innerHTML = "<input name = 'description' type = 'text' value= '"+descText+"'>";
 		quantity.innerHTML = "<input name = 'quantity' type = 'text' value = '"+quantityText+"'>";
 		location.innerHTML = "<input name = 'location' type = 'text' value = '"+locationText+"'>";
 		unitPrice.innerHTML = "<input name = 'unitPrice' type = 'text' value = '"+unitPriceText+"'>";
 		dateDeli.innerHTML = "<input name ='dateDeli' type ='date' value='"+dateDeliText+"'>";
 		stat.innerHTML = "<select name = 'status'></select>";
 		
 		document.querySelector("select[name=status]").appendChild(opPend);
 		document.querySelector("select[name=status]").appendChild(opDel);
 		document.querySelector("select[name=status]").appendChild(opCan);
 		document.querySelector("select[name=status]")[statIndex].setAttribute('selected','selected');

 		remarks.innerHTML = "<input name = 'remarks' type = 'text' value = '"+remarksText+"'>";
 	}
 	else if(quantity != undefined && dateAccomp != undefined && amount != undefined){

 		description.innerHTML = "<input name = 'description' type = 'text' value= '"+descText+"'>";
 		quantity.innerHTML = "<input name = 'quantity' type = 'text' value= '"+quantityText+"'>";
 		dateAccomp.innerHTML = "<input name = 'dateDel' type = 'date' value= '"+dateAccompText+"'>";
 		amount.innerHTML = "<input name = 'amount' type = 'text' value= '"+amountText+"'>";
 		stat.innerHTML = "<select name = 'status'></select>";
 		
 		document.querySelector("select[name=status]").appendChild(opPend);
 		document.querySelector("select[name=status]").appendChild(opDel);
 		document.querySelector("select[name=status]").appendChild(opCan);
 		document.querySelector("select[name=status]")[statIndex].setAttribute('selected','selected');

 		remarks.innerHTML = "<input name = 'remarks' type = 'text' value= '"+remarksText+"'>";

 	}
 	else if(dateComp != undefined){

 		description.innerHTML = "<input name = 'description' type = 'text' value= '"+descText+"'>";
 		dateComp.innerHTML = "<input name ='dateComp' type = 'date' value= '"+dateCompText+"'>";

 		stat.innerHTML = "<select name = 'status'></select>";
 		
 		document.querySelector("select[name=status]").appendChild(opPend);
 		document.querySelector("select[name=status]").appendChild(opComp);
 		document.querySelector("select[name=status]").appendChild(opCan);
 		document.querySelector("select[name=status]")[statIndex].setAttribute('selected','selected');

 		remarks.innerHTML = "<input name ='remarks' type = 'text' value= '"+remarksText+"'>";
 	}

 	document.getElementById('save'+index).style.display = "";

}


function editInfo(){
	var slipDateNeed = document.getElementById('dateNeeded');
	var slipDateNeedText = slipDateNeed.textContent;
	slipDateNeed.innerHTML = "<input type = 'date' name='dateNeeded' value='"+slipDateNeedText+"'>";

	var slipPurpose = document.getElementById('purpose');
	var slipPurposeText = slipPurpose.textContent;
	slipPurpose.innerHTML = "<input type = 'text' name='purpose' value='"+slipPurposeText+"'>";


	var statusSlip = document.getElementById('slipStatus');
	var statusSlipText = statusSlip.textContent;
	statusSlip.innerHTML = "<select name='slipStatus'></select>";

	var parntStatus = document.querySelector("select[name=slipStatus]");
	var pend = document.createElement('option');
	var comp = document.createElement('option');
	var can = document.createElement('option');

	pend.setAttribute('value','Pending');
	comp.setAttribute('value','Completed');
	can.setAttribute('value','Canceled');
	pend.textContent = 'Pending';
	comp.textContent = 'Completed';
	can.textContent = 'Canceled';

	parntStatus.appendChild(pend);
	parntStatus.appendChild(comp);
	parntStatus.appendChild(can);

	if(statusSlipText == 'Pending'){
		pend.setAttribute('selected','selected');
	}
	else if(statusSlipText == 'Completed'){
		comp.setAttribute('selected','selected');

	}
	else{
		can.setAttribute('selected','selected');
	}

	if(document.getElementById('careOF')){
		var care = document.getElementById('careOF');
		var careText = care.textContent;
		care.innerHTML = "<input type = 'text' name='careOF' value='"+careText+"'>";
	}

	if(document.getElementById('poNum')){
		var poNumber = document.getElementById('poNum');
		var poNumberText = poNumber.textContent;
		poNumber.innerHTML = "<input type='text' name='poNum' value='"+poNumberText+"'>";

	}

	if(document.getElementById('poDate')){
		var poDate = document.getElementById('poDate');
		var poDateText = poDate.textContent;
		poDate.innerHTML = "<input type='date' name='poDate' value='"+poDateText+"'>";

	}

	if(document.getElementById('poSupp')){
		var poSupp = document.getElementById('poSupp');
		var poSuppText = poSupp.textContent;
		poSupp.innerHTML = "<input type='text' name='poSupp' value='"+poSuppText+"'>";
	}


	var edBut = document.querySelector('button[name=editInfo]');
	edBut.style.display = 'none';

	var svBut = document.querySelector('button[name=saveInfo]');
	svBut.style.display = '';


}	

function prepareAmount(){

	 	if(document.getElementsByClassName('amount').length >= 0){

 			amount = document.getElementsByClassName('amount');
 			var quantity;
 			var quantityText;
 			var unitPrice;
 			var unitPriceText;
 			var sum;
 			var totalAmount = 0;

 			for(var i = 0; i < amount.length; i++){
			 	if(document.getElementById('quantity'+i)){
			 		quantity = document.getElementById('quantity'+i);
			 		quantityText = quantity.textContent;
			 		
			 	}

			 	if(document.getElementById('uPrice'+i)){
			 		unitPrice = document.getElementById('uPrice'+i);
			 		unitPriceText  = unitPrice.textContent;
			 	}


	 			if(unitPriceText != '' && unitPriceText != null && unitPriceText != undefined && quantityText !='' && quantityText != null && quantityText != undefined){
		 			if(!isNaN(unitPriceText) && !isNaN(quantityText)){
		 				sum = unitPriceText * quantityText;
		 				amount[i].textContent = sum;
		 			}
	 			}

 			}
 		
 		}
 		prepareTotalAmount();
}

function prepareTotalAmount(){

	if(document.getElementsByClassName('amount').length >= 0){
		
		var amountElem = document.getElementsByClassName('amount');
		var totalAmt;

		for(var i = 0; i < amountElem.length; i++){
			var amountVal = amountElem[i].textContent;

			if(amountVal != '' && amountVal != null && amountVal != undefined){
				if(!isNaN(amountVal)){
					if(totalAmt == undefined){
						totalAmt = parseFloat(amountVal);
					}
					else{
						totalAmt = parseFloat(totalAmt)+parseFloat(amountVal);
					}
						
				}			
			}

		}

		if(totalAmt != undefined){
			document.getElementById("totalAmt").textContent = totalAmt;

				if(!window.location.href.includes(totalAmt)){
					<?php echo "window.location.href = 'vd.php?request_id=".$request_id."&total='+totalAmt;"; ?>
				}
		}		
	}

}


</script>

<?php
if(isset($_POST['saveInfo'])){

		echo '<script type="text/javascript">
                    setTimeout(function() {location.href="view_details.php?request_id='. $request_id.'"},0);

                    alert("Successfully Edited Request No.'.$reqSlipArr['rs_no'].'");
                    </script>';

       			echo '<script type="text/javascript">
							
	                    </script>';

	$reqID = $reqSlipArr['id'];
	var_dump($reqID);

	if(isset($_POST['dateNeeded'])){
		$stmt = "UPDATE request_slip SET date_needed='$_POST[dateNeeded]', updated_at=NOW() WHERE id='$reqID'";
		mysqli_query($conn,$stmt) or die(mysqli_error($conn));

	}

	if(isset($_POST['purpose'])){
		$stmt = "UPDATE request_slip SET purpose='$_POST[purpose]', updated_at=NOW() WHERE id='$reqID'";
		mysqli_query($conn,$stmt) or die(mysqli_error($conn));
	}

	if(isset($_POST['slipStatus'])){
		$stmt = "UPDATE request_slip SET status='$_POST[slipStatus]', updated_at=NOW() WHERE id='$reqID'";
		mysqli_query($conn,$stmt) or die(mysqli_error($conn));
	}	

	if($reqSlipArr['type'] == 'PO'){

		if(isset($_POST['poNum'])){
			$stmt = "UPDATE purchase_order SET po_no='$_POST[poNum]' WHERE request_id='$reqID'";
			mysqli_query($conn,$stmt) or die(mysqli_error($conn));
		}

		if(isset($_POST['poDate'])){
			$stmt = "UPDATE purchase_order SET date_of_po='$_POST[poDate]' WHERE request_id='$reqID'";
			mysqli_query($conn,$stmt) or die(mysqli_error($conn));
		}

		if(isset($_POST['poSupp'])){
			$stmt = "UPDATE purchase_order SET supplier='$_POST[poSupp]' WHERE request_id='$reqID'";
			mysqli_query($conn,$stmt) or die(mysqli_error($conn));
		}
	}
	else{

		if(isset($_POST['careOF'])){
			$stmt = "UPDATE request_slip SET ConcernedOffice='$_POST[careOF]' WHERE id='$reqID'";
			mysqli_query($conn,$stmt) or die(mysqli_error($conn));
		}
	}

}
?>

                

</body>

</html>

<?php
include 'editInput.php';
?>