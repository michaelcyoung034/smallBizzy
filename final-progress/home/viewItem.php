<?php 
	session_start();
	$item = $_GET['item_num'];

?>

<!--
    /****************************************************************************
    * File Name: viewItem.php
    * Use-case: customer view inventory
    * Author: Kayoung Kim
    * E-mail: kayoung2@umbc.edu
    *
	* This page take the item number infomation.
	* Using the item number, it will display details of the time for buyer.
	* When user clicks "add to cart" button,
	* it goes to shoppingCart.php
	* It also checks user order quantity validation using javascript kayoung2.js
    *****************************************************************************/
-->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>smallBizzy : viewItem </title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link href="../styleSheets/masterPageStyle.css" type="text/css" rel="Stylesheet" />
<script type="text/javascript" src="../validationScripts/kayoung2.js"></script>
</head>
<body class="viewItemBody">

 
<!--START HEADER-->
<?php include '../pageHeaderScripts/header.php'; ?>
<!-- END HEADER-->

<!--STARTS customer view inventory-->

	<br />
	<table class="viewItemTable">
	<tr>
		<td class="viewItemPic"> &nbsp;&nbsp;&nbsp;&nbsp; &lt; Item preview picture &gt;
			<br/> &nbsp;&nbsp;&nbsp;&nbsp; item# :<?php echo $item;?>
		</td>
		<td class="viewItemOuterSub">

<?php
  $servername = "studentdb-maria.gl.umbc.edu";
  $username = "hf28974";
  $password = "hf28974";
  $dbname = "hf28974";
  //hf28974
  
    //DB connect info and SELECT Query
    $con = mysql_connect("$servername", "$username", "$password");
    mysql_select_db("$dbname", $con);
    $query = "SELECT item_name, supplier, description, max_quantity, quantity, sell_price FROM inventory WHERE item_num = $item";
    $result = mysql_query($query);
    if(! $result){
		print("ERROR - QUARY");
		$error = mysql_error();
		print"<br>". $error . "<br>";
		exit;
	}

  
    $name=mysql_result($result, 0, 'item_name');

	$supplier=mysql_result($result, 0, 'supplier');;
	$description=mysql_result($result,0, 'description');
    $mQuantity=mysql_result($result,0, 'max_quantity');
    $quantity=mysql_result($result, 0,'quantity');
    $price=mysql_result($result,0, 'sell_price');
  
?>
    <form action="../pos/shoppingCart.php" id="quantity" method="post">
    <table class="viewItemSub" align="center">

 	<tr><td class="viewItemTitle" colspan="2"><span class="title1"> 
 		<?php echo $name; ?></span></td></tr>
	<tr><td class="viewItemSubLeft">Item#: </td><td class="viewItemSubRight"> 
		<?php echo $item; ?></td></tr>

	<tr><td class="viewItemSubLeft">Supplier: </td><td class="viewItemSubRight"> 
		<?php echo $supplier; ?></td></tr>
	
	<tr><td class="viewItemSubLeft">Available quantity: </td>
		<td class="viewItemSubRight">
		<span id="qty"><?php echo $quantity; ?></span>
		&nbsp;<img src="../images/icon-sold-out.png" id="qty_img2">
		</td></tr>

	<tr><td class="viewItemSubLeft" id="order_qty">Order QTY: </td><td class="viewItemSubRight">
    	<input type="text" name="quantity" id="userQty" value="1" onblur="checkQTY()" size="5"/>
    	<img src="../images/high_priority-24.png" id="qty_img">
    </td></tr>
	

	<tr><td class="viewItemSubLeft"><span class="price">Price: </span></td>
	<td class="viewItemSubRight"><span class="price"> $ <?php echo $price; ?></span></td></tr>
	<tr><td class="viewItemBtn" colspan="2">
  	<input type="hidden" name="addedItem" value="<?php echo $item ?>" /> 
	<input type="hidden" name="howManyProducts" value="'<?php echo$item ?>" />
	<input type="hidden" name="flagEmpty" value="1" />
	<input name="image" type="image" id="submit" value="" src="../images/btn_addtocart.jpg" />
</td></tr>
	</table>
</form>
<script type="text/javascript"> checkSoldOut();</script>
	<?php mysql_close(); ?>
			</td>
		</tr>
	</table>
	<div>
		<br/>
		<table class="viewItemDetail"><tr>
			<td>
			<p>
				Detail Description: <br/>
				<?php echo $description;?>
				<br/>
			</p></td></tr>
		</table>
	</div>
			<br />	<br />
		<!--ENDS customer view inventory-->
		<!--START FOOTER-->
			<br />
			<?php include '../pageFooterScripts/footer.php'; ?>
		<!-- END FOOTER-->
	</body>
</html>