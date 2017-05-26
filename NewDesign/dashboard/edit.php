 <?php
include '../shared/connection.php';
include '../authorization.php';
$itemQuery = "SELECT * from items";
$itemQry = mysqli_query($conn, $itemQuery);
$itemArr = mysqli_fetch_array($itemQry);
$request_id = $_GET['request_id'];
if(isset($_POST['submit'])){
	$request_id = $_GET['request_id'];
	$quantity = $_POST['quantity'];
	$description = $_POST['description'];
	$concerned = $_POST['office'];
	$status = $_POST['status'];
	$remarks = $_POST['remarks'];
	$date = $_POST['date'];

	$updateStr = "UPDATE items set quantity='$quantity', description='$description', concerned_office='$concerned', status='$status', remarks='$remarks', date_accomplished='$date' WHERE request_slip_no='$request_id'";
	header("Location: view_details.php?request_id=" . $request_id);
	$updateQry = mysqli_query($conn, $updateStr);

}
$heading = "SELECT rs_no from request_slip where id='$request_id'";
$headingQry = mysqli_query($conn,$heading);
$headingArr = mysqli_fetch_array($headingQry);
var_dump($headingArr['rs_no']);
?>



<!DOCTYPE html>
<html>
<head>
	<link rel='stylesheet/css' src='style.css'>
	<title>edit</title>
	<!-- bootstrap -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<!-- bootstrap -->
</head>
<body>
<?php echo "<h1> Edit Items for Request No. " . $headingArr['rs_no'] ."</h1>" ;?>
<?php echo "<a href='view_details.php?request_id=$request_id?'> Back to Details </a><br>" ?>
<a href='dashboard.php'> Home </a>
<a href='../logout.php' style='float:right;'> Logout </a> 
<form method='POST'>
Quantity: <input type='number' name='quantity' value = <?php echo $itemArr['quantity']?>> <br>
Description: <input type='text' name='description' value= <?php echo $itemArr['description']?>><br>
Concerned Office: <input type='text' name='office' value=<?php echo $itemArr['concerned_office']?>><br>
Item Status:
<select name="status" onchange="warningaa(this);">
  <option value="Pending">Pending</option>
  <option value="Canceled">Canceled</option>
  <option value="For PO" id='po'>For PO</option>
  <option value="In-Progress">In-Progress</option>
  <option value="Completed">Completed</option>
</select><br>
Remarks: <input type="text" name="remarks" value=<?php echo $itemArr['remarks']?>><br>
Date Accomplished <input type="date" name="date" value=<?php echo $itemArr['date_accomplished']?>><br>
<input type='submit' name='submit'>
<!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Purchase Order Details</h4>
        </div>
        <div class="modal-body">
          <form>
          Purchase Order Number: <input type='number' name='po_no'><br>
          Date of Purchase Order: <input type='date' name='date_of_po'><br>
          Status: <input type='text' name='status_po'><br>
          Date Delivered: <input type='date' name='date_delivered'><br>
          Remarks: <input type='text' name='remarks_po'><br>
          <input type='submit'>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
</form>
</body>
<script type="text/javascript">
	function warningaa(obj) {
    if(obj.value == "For PO"){
        var button = document.createElement('button');
        button.setAttribute('class', 'btn btn-info btn-lg');
        button.setAttribute('type', 'button');	
        button.setAttribute('data-toggle','modal');
        button.setAttribute('data-target', '#myModal');
        var btnTxt = document.createTextNode("Click for PO Details");
        button.appendChild(btnTxt);
        document.body.appendChild(button);
  
    }
}
</script>
</html>
