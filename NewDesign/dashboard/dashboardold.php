<?php
include '../shared/connection.php';
include '../authorization.php';
$username=$_SESSION['username'];
$query = "SELECT * from users inner join user_details on users.id=user_details.id where username='$username'";
$queryAct = mysqli_query($conn, $query);
$queryArr = mysqli_fetch_array($queryAct);

if (isset($_POST['searchBar']) || isset($_POST['dateTo']) || isset($_POST['dateFrom'])) {
	$searchBar = $_POST['searchBar'];
	$dateFrom = $_POST['dateFrom'];
	$dateTo = $_POST['dateTo'];

}
?>
    <!DOCTYPE html>
    <html>

    <head>
        <title>REQUISITION MONITORING/TRACKING</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Bootstrap -->
        <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <!-- styles -->
        <link href="../css/styles.css" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    </head>

    <body>
    <div>
        <nav class="navbar navbar-inverse">
            <div class="container-fluid">
                <div class="navbar-header"> <a class="navbar-brand">SCIS Requisition Monitoring</a> </div>
                <ul class="nav navbar-nav">
                    <li class="current"><a href="index.html"><i class="glyphicon glyphicon-home"></i> Dashboard</a></li>
                    <li><a href="" data-toggle="modal" data-target="#addreq"><i class="glyphicon glyphicon-plus"></i> Add New Request</a></li>
                </ul>
                <!-- Add New Request Modal -->
                <div class="modal fade" id="addreq" role="dialog">
                    <div class="modal-dialog modal-lg">
                        <!-- Modal content-->
                        <div class="lgmodal">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Add New Request</h4> </div>
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
                                                    <td>
                                                        <input type="text" name="quantity[]">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="item[]">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="description[]">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="concerned_office[]">
                                                    </td>
                                                    <td>
                                                        <select name="status[]">
                                                            <option value="Pending">Pending</option>
                                                            <option value="Canceled">Canceled</option>
                                                            <option value="For PO">For PO</option>
                                                            <option value="In-Progress">In-Progress</option>
                                                            <option value="Completed">Completed</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <textarea rows='4' cols='50' name="remarks[]"></textarea>
                                                    </td>
                                                    <td>
                                                        <button style="display: none;" type="button" onclick="event.srcElement.parentElement.parentElement.remove();rmv()" class='btn btn-danger'>Delete</button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <button type="reset" class='btn btn-warning' onclick="addItem()">Add another Item</button>
                                    <h5 id="para">Use of Item:</h5>
                                    <br>
                                    <textarea name="reason" rows="4" cols="50" id="use"></textarea>
                                    <br>
                                    <h6 id="para">Date needed: </h6>
                                    <input type="date" name="date_needed ">
                                    <br>
                                    <br>
                                    <input type="reset" name="addrequest" class='btn btn-warning' class="request_form" value="Submit Request">
                                    <br>
                                    <br> </form>
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
                    <li class="dropdown" class="nav-item "> <a href='../logout.php' style="float:right;">Logout</a> </li>
                </ul>
            </div>
        </nav>
        
        
    </div>    
        <div class="page-content">
            <div class="row">
                <div class="content-box-large">
                    
    <form method="POST" class="form-inline">
    <span>
	<input type="text" name="searchBar" placeholder="Search">
	<input type="submit" name="searchButton" value="Search">
    
	Date From
	<input type="date" name="dateFrom" >
	
	Date To
	<input type="date" name="dateTo" class="form-control">
	<input type="submit" name="searchDate" value="Search Date">
    </span>
    </form>
                    <div class="panel-heading">
                        <div class="panel-title">
                            <?php echo "<h1>Welcome, ".$queryArr['firstname'] . ' ' . $queryArr['lastname'] . '</h1>';?></div>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Request Slip No.</th>
                                        <th>Date Needed</th>
                                        <th>Description of Request</th>
                                        <th>Requested By</th>
                                        <th>Status</th>
                                        <th>Request Type</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
	if (isset($_POST['searchButton'])) {
		$request_search = "SELECT * from request_slip where rs_no like '%".$searchBar. "%' or requested_by like '%".$searchBar. "%' or status like '%".$searchBar. "%' or type like '%".$searchBar."%'";
		$request_searchQ = mysqli_query($conn,$request_search) or die(mysqli_error($conn));
		while($searchrow = mysqli_fetch_array($request_searchQ)){
			echo "<tr><td> " . $searchrow['rs_no'] . "</td>";
			echo "<td>" . $searchrow['date_needed'] . "</td>";
			echo "<td>" . $searchrow['purpose'] . "</td>";
			echo "<td>" . $searchrow['requested_by']. " </td>";
			echo "<td>" . $searchrow['status'] . "</td>";
			if($searchrow['type'] == 'ItemsNoPO'){
				echo "<td> Not For Purchase Order </td>";
			}else if($searchrow['type'] == 'PO'){
				echo "<td> For Purchase Order </td>";
			}else{
				echo "<td> Service </td>";
			}
			
			echo "<td><a href='view_details.php?request_id=". $searchrow['id'] . "'>View Details </a></td></tr>";
		}
	} elseif (isset($_POST['searchDate'])) {
		$all_search = "SELECT * from request_slip where (date_needed between '$dateFrom' and '$dateTo') or date_needed like '%".$dateFrom."%'";
		$all_searchQ = mysqli_query($conn,$all_search) or die(mysqli_error($conn));
		while($searchrow = mysqli_fetch_array($all_searchQ)){
			echo "<tr><td> " . $searchrow['rs_no'] . "</td>";
			echo "<td>" . $searchrow['date_needed'] . "</td>";
			echo "<td>" . $searchrow['purpose'] . "</td>";
			echo "<td>" . $searchrow['requested_by']. " </td>";
			echo "<td>" . $searchrow['status'] . "</td>";
			if($searchrow['type'] == 'ItemsNoPO'){
				echo "<td> Not For Purchase Order </td>";
			}else if($searchrow['type'] == 'PO'){
				echo "<td> For Purchase Order </td>";
			}else{
				echo "<td> Service </td>";
			}
			
			echo "<td><a href='view_details.php?request_id=". $searchrow['id'] . "'>View Details </a></td></tr>";
		}
	
	} else {
		$all_search = "SELECT * from request_slip";
		$all_searchQ = mysqli_query($conn,$all_search) or die(mysqli_error($conn));
		while($searchrow = mysqli_fetch_array($all_searchQ)){
			echo "<tr><td> " . $searchrow['rs_no'] . "</td>";
			echo "<td>" . $searchrow['date_needed'] . "</td>";
			echo "<td>" . $searchrow['purpose'] . "</td>";
			echo "<td>" . $searchrow['requested_by']. " </td>";
			echo "<td>" . $searchrow['status'] . "</td>";
			if($searchrow['type'] == 'ItemsNoPO'){
				echo "<td> Not For Purchase Order </td>";
			}else if($searchrow['type'] == 'PO'){
				echo "<td> For Purchase Order </td>";
			}else{
				echo "<td> Service </td>";
			}
			
			echo "<td><a href='view_details.php?request_id=". $searchrow['id'] . "'>View Details </a></td></tr>";
		}
	}
	?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <footer>
         <div class="container">
         
            <div class="copy text-center">
               Copyright Kool Kidz
            </div>
            
         </div>
      </footer>
    </body>

    </html>
    
   <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://code.jquery.com/jquery.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="../bootstrap/js/bootstrap.min.js"></script>
    <script src="../js/custom.js"></script>
    
   <script type="text/javascript">
(function() {
   // your page initialization code here
   // the DOM will be available here

})();


function addItem(){
	var tablebody = document.getElementById('items');
	if(tablebody.rows.length == 1){
		tablebody.rows[0].cells[6].children[0].style.display="";
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
		tabRow.rows[0].cells[6].children[0].style.display="none";
	}
	else{
		tabRow.rows[0].cells[6].children[0].style.display="";
	}
}

</script>




