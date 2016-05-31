<?php session_start(); ?>
<!--
	/****************************************************************************
	* File Name: shoppingCart.php
	* Use-case: customer view inventory
	* Author: Kayoung Kim
	* E-mail: kayoung2@umbc.edu
	*
	* This php file insert previous item into DB table,
	* 	and then display all the items in the shoppinc cart table.
	* 
	* When user clicks "buy item" button, buyItem.php will be loaded.
	* 
	* When user clicks "Empty cart" button, ajax and javascript
	* 	will handle this feature for the final project submition.
	*****************************************************************************/
-->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Your Shopping Cart</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<link href="../styleSheets/masterPageStyle.css" type="text/css" rel="Stylesheet" />
	<script type="text/javascript" src="../validationScripts/kayoung2.js"></script>



</head>

<body>
<!--START HEADER-->
	<?php include '../pageHeaderScripts/header.php'; ?>
<!-- END HEADER-->



<!--STARTS Shopping cart-->


<br />
<table class ="receiptTB" align="center">
	<tr>
		<td colspan="4" height="30px;"><span class="title1">::SHOPPING CART ::</span></td>
	</tr>
	<tr><td colspan="4" class="receiptLine"></td></tr>
	<tr class="reciptHeadlineTR">
		<td width="400px"><span class="title2">Product   </span></td>
		<td width="100px"><span class="title2">Price  </span></td>
		<td width="100px"><span class="title2">Quantity  </span></td>
		<td width="100px"><span class="title2">Item Total </span></td>
	</tr>
	<tr><td colspan="4" class="receiptLine"></td></tr>
	<tr><td colspan="4" height="10"></td></tr>
	<tr><td colspan="4" valign="top"><span id="hidden_emptyCart"></span>
		
<?php
	echo '<table class="reciptDetailTB">';
	$uid = $_SESSION['user_num'];
	//$uid = 6;
	$itemQuantity = $_POST["quantity"];
	$addedItem = $_POST["addedItem"];
	$flagEmpty = $_POST["flagEmpty"];
	$grandTotal = 0;

	$_POST["quantity"] = NULL;

	$servername = "studentdb-maria.gl.umbc.edu";
	$username = "hf28974";
	$password = "hf28974";
	$dbname = "hf28974";

  
	//DB connect info and SELECT Query
	$con = mysql_connect("$servername", "$username", "$password");
	if (!$con){
		echo '<script type="text/javascript">alert("CANNOT CONNECT TO DB: mysql_connect error!")</script>';
		exit;
	}

	$db = mysql_select_db("$dbname", $con);
	if(! $db){

		echo '<script type="text/javascript">alert("CANNOT SELECT DB: mysql_select_db error!")</script>';
		exit;
	}

	if ( ($itemQuantity != 0) || ($itemQuantity != NULL) ) 
	{
		$query = "SELECT quantity FROM shopping_cart WHERE (user_num = $uid AND item_num = $addedItem)";
		$quantityResult = mysql_query($query);
		if (!$quantityResult) 
		{
			print "ERROR SELECT1: ";
			print mysql_error();
			exit;
		}
		$temp2 = mysql_numrows($quantityResult);
		if( mysql_numrows($quantityResult) > 0 ) 
		{
			$temp = mysql_result($quantityResult, 0, "quantity");
			//echo "<br>(temp= ".$temp.", temp2=".$temp2.")<br>";
			$temp = $temp + $itemQuantity;
			//echo "<br>(itemQuantity=".$itemQuantity.", temp= ".$temp.")<br>";
			$query ="UPDATE shopping_cart SET quantity=$temp WHERE (user_num = $uid) AND (item_num = $addedItem)";
			$insertResult = mysql_query($query);
			if (!$insertResult) 
			{
				print "ERROR insertResult1: ";
				print mysql_error();
				exit;
			}
		}
		else
		{
			$query = "INSERT INTO shopping_cart (user_num, item_num, quantity) VALUES ('$uid', '$addedItem', '$itemQuantity')";
			$insertResult = mysql_query($query);
			if (!$insertResult) 
			{
				print "ERROR insertResult2: ";
				print mysql_error();
				exit;
			}
		}
		$_POST["quantity"] = 0;



	}


		$scQ = "SELECT * FROM shopping_cart WHERE user_num = $uid";
		$selectResult = mysql_query($scQ);
		if (!$selectResult){

			if ($uid == NULL) {
				echo '<tr><td colspan="4"><center><br/>USER must login to use shopping cart!<br/></center></td></tr>';
				echo '</table></td></tr></table>';
			} else {
				print "ERROR SELECT2: ";
				print mysql_error();
			}

			include '../pageFooterScripts/footer.php';
			echo '</body></html>';
			exit;
		}

		$num = mysql_numrows($selectResult);

		$i=0;
		$grandTotal = 0;

		while ($i < $num) {
			$eachItemNum = mysql_result($selectResult, $i, "item_num");

			$eachQuantity = mysql_result($selectResult, $i, "quantity");

			if ($eachQuantity == 0) {
				# Do Nothing
			}else{
				$itemQ = "SELECT item_name, sell_price FROM inventory WHERE item_num = $eachItemNum";
				$InvenResult = mysql_query($itemQ);
				if(! $InvenResult){
					print("ERROR 3: QUARY");
					print mysql_error();
					exit;
				}

				$eachItemName = mysql_result($InvenResult, 0, 'item_name');

				$eachPrice = mysql_result($InvenResult, 0, 'sell_price');

				$grandTotal = $grandTotal + ($eachPrice * $eachQuantity);
				echo '<tr class="reciptDetailTR" >';
				echo '<td class="reciptDetailTD" width="450">'.$eachItemName.'</td>';
				echo '<td width="130"> $&nbsp;'.$eachPrice.' </td>';
				echo '<td width="30" id="eachQ'.$i.'">'.'x&nbsp;'.$eachQuantity.'</td>';
				echo '<td width="40"><button onclick="updateQuantity('.$i.','.$uid.','.$eachItemNum.')">update</button>'.' </td>';
				echo '<td width="140"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; $ ';
				echo number_format(($eachPrice * $eachQuantity), 2, '.', '').' </td></tr>';
				}
			$i++;
		}

		echo '</table>';
?>

	</td></tr>

	<tr><td colspan="4" valign="bottom" align="right">
		<br/><span class="title2">
		Total: $&nbsp;
		<?php
		    $grandTotal = number_format($grandTotal, 2, '.', '');
			echo $grandTotal;
		?>
		<br/></span><br/>
	</td></tr>
	<tr><td colspan="4" class="receiptLine"></td></tr>
	<tr><td colspan="4" height="30"></td></tr>
	<tr valing="bottom">
		<td colspan="2" align="right">

			<div width="220">
				<?php echo'<button id="btn1_cart" width="200" height="50" onclick="emptyCart('.$uid.')"/>'; ?>
				&nbsp;&nbsp;
			</div>
		</td><td colspan="2" align="right">
			<div width="220">

				<form action="buyItem.php" method="post">
				<button type="submit" class="btn-link" id="btn2_cart" />
				&nbsp;&nbsp;
				</form></td>
			</div>
		</td>
	</tr>
</table>



<!--ENDS Shopping cart-->

<!--START FOOTER-->
	<br />
	<?php include '../pageFooterScripts/footer.php'; ?>
<!-- END FOOTER-->

</body>
</html>