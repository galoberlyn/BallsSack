<?php
if(isset($_POST['save'])){
$itemsId = $_POST['save'];

	if($reqSlipArr['type'] == 'PO'){
		$ismt = "SELECT description FROM itemspo where iditemspo= '$itemsId'";
		$ismtQry = mysqli_query($conn, $ismt);
		$ismtArr = mysqli_fetch_array($ismtQry);

		echo '<script type="text/javascript">
            alert("Successfully Edited Item:'.$ismtArr['description'].'");
            </script>';

		echo '<script type="text/javascript">
                    setTimeout(function() {location.href="view_details.php?request_id='. $request_id.'"},0);
                    </script>';
                    
		if(isset($_POST['description'])){
			$desc = htmlspecialchars($_POST['description'], ENT_QUOTES);
			$stmt = "UPDATE itemspo SET description='$desc' WHERE iditemspo='$itemsId'";
			mysqli_query($conn, $stmt);
		}
		if(isset($_POST['spo'])){
			$spo = htmlspecialchars($_POST['spo'], ENT_QUOTES);
			$stmt = "UPDATE itemspo SET supplier_po='$spo' WHERE iditemspo='$itemsId'";
			mysqli_query($conn, $stmt);

		}

		if(isset($_POST['quantity'])){
			$qty = htmlspecialchars($_POST['quantity'], ENT_QUOTES);
			$stmt = "UPDATE itemspo SET quantity='$qty' WHERE iditemspo='$itemsId'";
			mysqli_query($conn, $stmt);

		}

		if(isset($_POST['location'])){
			$loc = htmlspecialchars($_POST['location'], ENT_QUOTES);
			$stmt = "UPDATE itemspo SET Location='$loc' WHERE iditemspo='$itemsId'";
			mysqli_query($conn, $stmt);
			
		}

		if(isset($_POST['unitPrice'])){
			$price = htmlspecialchars($_POST['unitPrice'], ENT_QUOTES);
			$stmt = "UPDATE itemspo SET unitprice='$price' WHERE iditemspo='$itemsId'";
			mysqli_query($conn, $stmt);
			
		}

		if(isset($_POST['status'])){
			$status = htmlspecialchars($_POST['status'], ENT_QUOTES);
			$stmt = "UPDATE itemspo SET itemspostatus='$status' WHERE iditemspo='$itemsId'";
			mysqli_query($conn, $stmt);
		}

		if(isset($_POST['remarks'])){
			$rmrks = htmlspecialchars($_POST['remarks'], ENT_QUOTES);
			$stmt = "UPDATE itemspo SET remarks='$rmrks' WHERE iditemspo='$itemsId'";
			mysqli_query($conn, $stmt);
		}

		if(isset($_POST['dateDeli'])){
			$dateDel = htmlspecialchars($_POST['dateDeli'], ENT_QUOTES);
			$stmt = "UPDATE itemspo SET date_complete='$dateDel' WHERE iditemspo='$itemsId'";
			mysqli_query($conn, $stmt) or die(mysqli_error($conn));
		}

		if(isset($_POST['quantity']) && isset($_POST['unitPrice'])){
			$quantity = htmlspecialchars($_POST['quantity'], ENT_QUOTES);
			$uPrice = htmlspecialchars($_POST['unitPrice'], ENT_QUOTES); 

			if(is_numeric($quantity) && is_numeric($uPrice)){
				$totalAmt = $quantity * $uPrice;
				$stmt = "UPDATE itemspo SET amount='$totalAmt' WHERE iditemspo='$itemsId'";
				mysqli_query($conn, $stmt) or die(mysqli_query($conn));
			}
		}



	}
	else if($reqSlipArr['type'] == 'ItemsNoPO'){
		$ismt = "SELECT description FROM  itemsnotpo where id = '$itemsId'";
		$ismtQry = mysqli_query($conn, $ismt);
		$ismtArr = mysqli_fetch_array($ismtQry);

				echo '<script type="text/javascript">
                    alert("Successfully Edited Item:'.$ismtArr['description'].'");
                    </script>';

		if(isset($_POST['description'])){
			$desc1 = htmlspecialchars($_POST['description'], ENT_QUOTES);
			$stmt = "UPDATE itemsnotpo SET description='$desc1' WHERE id='$itemsId'";
			mysqli_query($conn, $stmt);
		}

		if(isset($_POST['supplierNoPO'])){
			$supp = htmlspecialchars($_POST['supplierNoPO'], ENT_QUOTES);
			$stmt = "UPDATE itemsnotpo SET supplier='$supp' WHERE id='$itemsId'";
			var_dump($supp);
			mysqli_query($conn, $stmt);

		}

		if(isset($_POST['quantity'])){
			$qtyy = htmlspecialchars($_POST['quantity'], ENT_QUOTES);
			$stmt = "UPDATE itemsnotpo SET quantity='$qtyy' WHERE id='$itemsId'";
			mysqli_query($conn, $stmt);
		}


		if(isset($_POST['dateDel']) && $_POST['dateDel'] != '' && $_POST['dateDel'] != ' '){
			$stmt = "UPDATE itemsnotpo SET date_accomplished='$_POST[dateDel]' WHERE id='$itemsId'";		
			mysqli_query($conn, $stmt);
		}

		if(isset($_POST['amount'])){
			$stmt = "UPDATE itemsnotpo SET amount='$_POST[amount]' WHERE id='$itemsId'";
			mysqli_query($conn, $stmt);
		}

		if(isset($_POST['status'])){
			$status = htmlspecialchars($_POST['status'], ENT_QUOTES);
			$stmt = "UPDATE itemsnotpo SET itemStatus='$status' WHERE id='$itemsId'";
			mysqli_query($conn, $stmt);
		}

		if(isset($_POST['remarks'])){
			$rmrks = htmlspecialchars($_POST['remarks'], ENT_QUOTES);
			$stmt = "UPDATE itemsnotpo SET remarks='$rmrks' WHERE id='$itemsId'";
			mysqli_query($conn, $stmt);
		}

		echo '<script type="text/javascript">
                    setTimeout(function() {location.href="view_details.php?request_id='. $request_id.'"},0);
                    </script>';


	}
	else{


		$ismt = "SELECT description FROM  services where idServices = '$itemsId'";
		$ismtQry = mysqli_query($conn, $ismt);
		$ismtArr = mysqli_fetch_array($ismtQry);

		echo '<script type="text/javascript">
	    alert("Successfully Edited Item:'.$ismtArr['description'].'");
	    </script>';

		if(isset($_POST['description'])){
			$desc = htmlspecialchars($_POST['description'], ENT_QUOTES);
			$stmt = "UPDATE services SET description='$desc' WHERE idServices='$itemsId'";
			mysqli_query($conn, $stmt);
		}

		if(isset($_POST['serviceprovider'])){
			$sp = htmlspecialchars($_POST['serviceprovider'], ENT_QUOTES);
			$stmt = "UPDATE services SET service_provider='$sp' WHERE idServices='$itemsId'";
			mysqli_query($conn, $stmt);

		}


		if(isset($_POST['dateComp']) && $_POST['dateComp'] != '' && $_POST['dateComp'] != ' ' ){
			$stmt = "UPDATE services SET date_completed ='$_POST[dateComp]' WHERE idServices='$itemsId'";
			mysqli_query($conn, $stmt);
		}
		if(isset($_POST['theTime'])){
			$stmt = "UPDATE services SET `time`='$_POST[theTime]' WHERE idServices='$itemsId'";
			mysqli_query($conn, $stmt);

		}


		if(isset($_POST['status'])){
			$sts = htmlspecialchars($_POST['status'], ENT_QUOTES);
			$stmt = "UPDATE services SET status='$sts' WHERE idServices='$itemsId'";
			mysqli_query($conn, $stmt);
			
		}

		if(isset($_POST['remarks'])){
			$rmrks = htmlspecialchars($_POST['remarks'], ENT_QUOTES);
			$stmt = "UPDATE services SET remarks='$rmrks' WHERE idServices='$itemsId'";
			mysqli_query($conn, $stmt);
			
		}

		echo '<script type="text/javascript">
                    setTimeout(function() {location.href="view_details.php?request_id='. $request_id.'"},0);
                    </script>';
 	}
}

if(isset($_GET['total']) && isset($_GET['request_id'])){
	$tot = $_GET['total'];
	$reqId = $_GET['request_id'];
	if(is_numeric($tot)){
		$stmt = "UPDATE purchase_order SET totalamt='$tot' WHERE request_id='$reqId'";
		mysqli_query($conn, $stmt) or die(mysqli_error($conn));
	}
}



?>