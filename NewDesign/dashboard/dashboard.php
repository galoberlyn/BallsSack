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
        <!-- styles -->
        <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../assets/css/style.css">
        <link rel="stylesheet" href="../assets/css/font-awesome.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>

    <?php
                include "../header.php";
    ?>
    
    
    <!-- LOGO HEADER END-->
    <section class="menu-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="navbar-collapse collapse ">
                        <ul id="menu-top" class="nav navbar-nav navbar-right">
                            <li><a class="menu-top-active" href="dashboard.php"><i class="glyphicon glyphicon-home"></i> Dashboard</a></li>
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
    <br>
<div class="page-content">
           
           <div class="row">
                <div class="col-md-12">
                    <?php echo"<h1 class='page-head-line'>Welcome, ".$queryArr['firstname'] . ' ' . $queryArr['lastname'] . '</h1>';?>

                </div>

            </div>
                <div class="content-box-large">
                    <div class="row">
                 <div class="col-md-3 col-sm-3 col-xs-6">
                    <div class="dashboard-div-wrapper bk-clr-one">
                        <i  class="fa fa-check dashboard-div-icon" ></i>
                         <h5>Completed </h5>
                    </div>
                </div>   
                 <div class="col-md-3 col-sm-3 col-xs-6">
                    <div class="dashboard-div-wrapper bk-clr-one">
                        <i  class="fa fa-clock-o dashboard-div-icon" ></i>
                         <h5>Pending </h5>
                    </div>
                </div>
                 <div class="col-md-3 col-sm-3 col-xs-6">
                    <div class="dashboard-div-wrapper bk-clr-two">
                        <i class="fa fa-close dashboard-div-icon" ></i>
                         <h5>Cancelled </h5>
                    </div>
                </div>
                 <div class="col-md-3 col-sm-3 col-xs-6">
                    <div class="dashboard-div-wrapper bk-clr-three">
                        <i  class="fa fa-exclamation dashboard-div-icon" ></i>
                         <h5>Kalbo </h5>
                    </div>
                </div>
                
</div>
            <div class="row">
                       <form method="POST" class="form-inline">
    <span>
        <div class="col-md-7">
	<input type="text" name="searchBar" placeholder="Search">
            <button class="btn" type="submit" name="searchButton" value="Search">Search</button>
        </div>
    <div class="col-md-5" id="spacer">
	Date From
	<input type="date" name="dateFrom" >
        
	Date To
	<input type="date" name="dateTo">
        <Button class="btn" type="submit" name="searchDate" value="Search Date" >Search Date</Button>
            </div>
    </span>
    </form>
                  <br>
<br>                   
                    <div class="panel-body">
                                     <?php
	if (isset($_POST['searchButton'])) {
		$request_search = "SELECT 
    rs.id,
    rs_no,
    requested_by,
    date_needed,
    purpose,
    rs.status,
    type,
    srv.description as serviceDes,
    po.description as poDes,
    npo.description as npoDes
FROM
    request_slip rs
        LEFT JOIN
    services srv ON rs.id = srv.requestID
        LEFT JOIN
    purchase_order pur ON rs.id = pur.request_id
        LEFT JOIN
    itemspo po ON po.poid = pur.id
        LEFT JOIN
    itemsnotpo npo ON npo.request_slip_no = rs.id
WHERE
    rs_no LIKE '%".$searchBar. "%'
        OR requested_by LIKE '%".$searchBar. "%'
        OR purpose LIKE '%".$searchBar. "%'
        OR rs.status LIKE '%".$searchBar. "%'
        OR rs.type LIKE '%".$searchBar. "%'

        OR srv.description LIKE '%".$searchBar. "%'
        OR srv.remarks LIKE '%".$searchBar. "%'
        OR srv.service_provider LIKE '%".$searchBar. "%'

        OR po.description LIKE '%".$searchBar. "%'
        OR po.remarks LIKE '%".$searchBar. "%'
        OR po.supplier_po LIKE '%".$searchBar. "%'

        OR npo.description LIKE '%".$searchBar. "%'
        OR npo.supplier LIKE '%".$searchBar. "%'
        OR npo.remarks LIKE '%".$searchBar. "%' group by rs_no";
        $request_searchQ = mysqli_query($conn,$request_search) or die(mysqli_error($conn));
?>

<br>

<?php
        while($searchrow = mysqli_fetch_array($request_searchQ)){
            ?>
                        <div class='table-responsive'>
                            <table class='table table-hover'>
                                <thead>
                                    <tr>
                                        <th>Request Slip No.</th>
                                        <th>Date Needed</th>
                                        <th>Description of Request</th>
                                       <th>Item Name</th>
                                        <th id="fakelink">Requested By</th>
                                        <th>Status</th>
                                        <th>Request Type</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                
<?php
            echo "<tr><td> " . $searchrow['rs_no'] . "</td>";
            echo "<td>" . $searchrow['date_needed'] . "</td>";
            echo "<td>" . $searchrow['purpose'] . "</td>";
            if($searchrow['type'] == 'ItemsNoPO'){
                echo "<td>" . $searchrow['npoDes'] . "</td>";          
            }else if($searchrow['type'] == 'PO'){
                echo "<td>" . $searchrow['poDes'] . "</td>";

            }else{
                echo "<td>" . $searchrow['serviceDes'] . "</td>";
            }

            echo "<td>" . $searchrow['requested_by']. " </td>";
            echo "<td>" . $searchrow['status'] . "</td>";
            if($searchrow['type'] == 'ItemsNoPO'){          
                echo "<td> Not For Purchase Order </td>";
            }else if($searchrow['type'] == 'PO'){
                echo "<td> For Purchase Order </td>";
            }else{
                echo "<td> Service </td>";
            }
            
            echo "<td><a href='view_details.php?request_id=". $searchrow['id'] . "'>View Details </a></td>";
            echo "<form method ='POST'  onsubmit='return delConfirm(".$searchrow['rs_no'].")' >";
            echo "<td><button type='submit' class ='btn btn-danger' name ='requestDel' value='". $searchrow['id'] . "'>Delete Request</button></td></tr></form>";
        }
	} elseif (isset($_POST['searchDate'])) {
		$all_search = "SELECT * from request_slip where (date_needed between '$dateFrom' and '$dateTo') or date_needed like '%".$dateFrom."%'";
		$all_searchQ = mysqli_query($conn,$all_search) or die(mysqli_error($conn));
        ?>
                    <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Request Slip No.</th>
                                        <th>Date Needed</th>
                                        <th>Purpose</th>
                                        <th id="fakelink">Requested By</th>
                                        <th>Status</th>
                                        <th>Request Type</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
        <?php
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

			echo "<td><a href='view_details.php?request_id=". $searchrow['id'] . "'>View Details </a></td>";
            echo "<form onsubmit='return delConfirm(".$searchrow['rs_no'].")' method ='POST'>";
            echo "<td><button type='submit' class ='btn btn-danger' name ='requestDel' value='". $searchrow['id'] . "'>Delete Request</button></td></tr></form>";
		}
	
	} else {
	    if(isset($_GET['order'])){
	        $order = $_GET['order'];
        }else{
	        $order = 'updated_at';
        }

        if(isset($_GET['sort'])){
	        $sort = $_GET['sort'];
        }else{
            $sort = 'DESC';
        }

		$request_search = "SELECT * from request_slip order by $order $sort";
	$request_searchQ = mysqli_query($conn,$request_search) or die(mysqli_error($conn));
	if(mysqli_num_rows($request_searchQ) > 0){
    $sort == 'DESC' ? $sort = 'ASC' : $sort = 'DESC';

        ?>
                        <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                        <tr>
                                        <th><a href='?order=rs_no&&sort=<?php echo $sort?>'>Request Slip No.</a></th>
                                            <th><a href='?order=date_needed&&sort=<?php echo $sort?>'>Date Needed</a></th>
                                            <th><a href='?order=purpose&&sort=<?php echo $sort?>'>Description of Request</a></th>
                                        <th id="fakelink">Requested By</th>
                                            <th><a href='?order=status&&sort=<?php echo $sort?>'>Status</a></th>
                                            <th><a href='?order=type&&sort=<?php echo $sort?>'>Request Type</a></th>
                                        <th>Action</th>
                                        </tr>
                                        </thead>

                                        <?php
                                        while ($searchrow = mysqli_fetch_array($request_searchQ)) {
                                            echo "<tr><td> " . $searchrow['rs_no'] . "</td>";
                                            echo "<td>" . $searchrow['date_needed'] . "</td>";
                                            echo "<td>" . $searchrow['purpose'] . "</td>";
                                            echo "<td>" . $searchrow['requested_by'] . " </td>";
                                            echo "<td>" . $searchrow['status'] . "</td>";
                                            if ($searchrow['type'] == 'ItemsNoPO') {
                                                echo "<td> Not For Purchase Order </td>";
                                            } else if ($searchrow['type'] == 'PO') {
                                                echo "<td> For Purchase Order </td>";
                                            } else {
                                                echo "<td> Service </td>";
                                            }

                                            echo "<td><a href='view_details.php?request_id=" . $searchrow['id'] . "'>View Details </a></td>";
                                            echo "<form onsubmit='return delConfirm(" . $searchrow['rs_no'] . ")' method ='POST'>";
                                            echo "<td><button type='submit' class ='btn btn-danger' name ='requestDel' value='" . $searchrow['id'] . "'>Delete Request</button></td></tr></form>";
                                        }
                                        }
	}
	?>
                            </table>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>    

<footer>
         <div class="container">
         
            <div class="copy text-center">
               Saint Louis University SCIS
            </div>
            
         </div>
      </footer>
    
    
    <div class="container">

  <!-- Modal -->
  <div class="modal fade" id="changepass" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Change Password</h4>
        </div>
        <div class="modal-body">
          <?php
            $user_pass = "Select password from users where username = '$username';";     
        $user_passQ = mysqli_query($conn,$user_pass) or die(mysqli_error($conn));
    
        if (isset($_POST['newpass']) && isset($_POST['connewpass']) && isset($_POST['oldpass'])){
            $newpass = $_POST['newpass'];
            $connewpass = $_POST['connewpass'];
            $oldpass = $_POST['oldpass'];
        }
        
        $user = mysqli_fetch_array($user_passQ);
        
        echo "<form method='POST' action = 'changepass.php' class='form-horizontal'>";
           
        echo "<div class='row'>";    
        echo "<label class='col-lg-4 control-label'>Current Password </label>";
        echo "<div class='col-lg-7'>";
        echo "<input type='password' name='oldpass' class='form-control'>";
        echo "</div>";
        echo "</div>";
            
            
        echo "<div class='row'>";   
        echo "<label class='col-lg-4 control-label'>New Password </label>";
        echo "<div class='col-lg-7'>";
        echo "<input type='password' name='newpass' class='form-control'>";
        echo "</div>";
        echo "</div>";
                    
        echo "<div class='row'>";   
        echo "<label class='col-lg-4 control-label'>Confirm New Password </label>";
        echo "<div class='col-lg-7'>";
        echo "<input type='password' name='connewpass' class='form-control'>";
        echo "</div>";
        echo "</div>";
        echo "</div>";     
            
        echo "<div class='modal-footer'>";
        echo "<input type='submit' name='change'  class='btn btn-primary'value='Change'>";
            
        echo "<button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>";  
        echo "</div>";
            
        echo "</form>";
        
        if(isset($_POST['change'])){
    
            if ($newpass == $connewpass &&
            password_verify($oldpass,$user['password'])) {
                
                $newpass = password_hash($_POST['newpass'],PASSWORD_DEFAULT);
                $updatepass = "UPDATE users SET password = '$newpass' where username = '$username'";
                $upadatepassQ = mysqli_query($conn, $updatepass);
                echo "success";
                session_unset();
                session_destroy();
                header("Location: ../index.php");
                exit;

            } else {
                echo "Password DOESN'T match!";
            }
        }
            ?>
      </div>
      
    </div>
  </div>
  
</div>
    
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

function delConfirm(rsnNum){
    var conf = confirm("Are you sure You want to delete Request Slip Number: "+rsnNum);
    if( conf == true){

        return true;
    }
    else{
        
        return false;
    }
}

</script>

<?php

if(isset($_POST['requestDel'])){
    $reqID = $_POST['requestDel'];

    $queryStmt  = "SELECT * FROM request_slip where id = '$reqID' ";
    $query = mysqli_query($conn,$queryStmt) or die(mysqli_error($conn));
    $querRow = mysqli_fetch_array($query);

    $requestNo = $querRow['rs_no'];
    if($querRow['type'] == 'ItemsNoPO'){
        mysqli_query($conn, "DELETE FROM itemsnotpo WHERE request_slip_no = '$reqID'") or die(mysqli_error($conn));

    }
    else if($querRow['type'] == 'PO'){
        $queryStmt1 = "SELECT * FROM purchase_order where request_id = '$reqID'";
        $query1 = mysqli_query($conn, $queryStmt1) or die(mysqli_error($conn));
        $querRow1 = mysqli_fetch_array($query1);
        $poID = $querRow1['id'];

        mysqli_query($conn, "DELETE FROM itemspo WHERE poid = '$poID'") or die(mysqli_error($conn));
        mysqli_query($conn, "DELETE FROM purchase_order WHERE request_id = '$reqID'") or die(mysqli_error($conn));
    }
    else{
        mysqli_query($conn, "DELETE FROM services WHERE requestID='$reqID'") or die(mysqli_error($conn));
    }
    $delQueryStmt = "DELETE FROM request_slip WHERE id ='$reqID'";
    mysqli_query($conn, $delQueryStmt) or die(mysqli_error($conn));
    echo "<html><head><script>alert('Successfully Deleted Request No:".$requestNo."'); window.location = 'dashboard.php'</script></head></html>";
}

?>