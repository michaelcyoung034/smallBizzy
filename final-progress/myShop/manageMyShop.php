<?php session_start();?>
<!--
    /****************************************************************************
    * File Name: manageMyShop.php
    * Use-case: vendor update inventory
    * Author: Derek Wang
	*
	* manageMyShop.php is a control panel and portal allowing a vendor to manage and 
	*	modify their inventory
	*
	* This .php file displays any any inventory records previously written to the DB
	*	table.
    * 
    * When the user clicks "Add item(s) to Inventory" button, the user will be 
	*	redirected to addInventory.php where they can insert new 
	*	supplier, product primary key pair (unique inventory item) records into 
	*	their inventory.
    * 
    * When the user clicks "Edit Item(s) in Inventory", the user will be redirected
	* 	to editInventory where they can update existing, related, unique inventory item 
	*	records in their inventory.
    *****************************************************************************/
-->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Inventory Manager</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<!-- make sure the style sheet is accessible from all web pages -->
        <link href="../styleSheets/masterPageStyle.css" type="text/css" rel="Stylesheet" />
		<style type="text/css">
			a {
				text-align: center;
				width: 250px;
			}
		</style>
    </head>
    <body class="manageMyShop">
	<?php
		include('../pageHeaderScripts/header.php');
		$username = $_SESSION['username'];
    ?>
	<div class="manageMyShop_body">
	
		<h2 class="manageMyShop">Welcome <?php echo $username ?></h2>
		
		<br />
		<div class="currentInventory_manageMyShop">
		<h3 class="manageMyShop">Existing Inventory Accounted:</h3>
			<form name="updateParts" method="post" action="editInventory.php">
				<table class="currentInventory_manageMyShop">
					<tr class="currentInventory_manageMyShop">
						<th class="currentInventory_manageMyShop" id="supplier">
							Supplier
						</th>
						<!--<th class="currentInventory_manageMyShop" id="product">
							Product ID
						</th>-->
						<th class="currentInventory_manageMyShop">
							Item Name
						</th>
						<th class="currentInventory_manageMyShop">
							Quantity on hand
						</th>
						<th class="currentInventory_manageMyShop">
							Reorder Point
						</th>
						<th class="currentInventory_manageMyShop"> 
							Purchase Price
						</th>
						<th class="currentInventory_manageMyShop">
							Sell Price
						</th>
						<th class="currentInventory_manageMyShop">
							Description
						</th>
						
					</tr>
					<?php
						//Get the records of all parts under the given manufacturer
						//connect
						$db = mysql_connect("studentdb-maria.gl.umbc.edu","hf28974","hf28974");
						if (!$db)
						{
							exit("Error - could not connect");
						}
						else
						{
							//echo ("Connected".'<br/>');
						}
						
						//select database
						$er = mysql_select_db("hf28974");
						if (!$er)
						{
							exit("Error - could not select");
						}
						else
						{
							//echo ("Selected".'<br/>');
						}
						
						//$mfgCode=strtoupper($_SESSION['code']);
						$uid = $_SESSION['user_num'];
						$query = "SELECT supplier, product, item_name, quantity, max_quantity, purchase_price, sell_price, description FROM inventory WHERE seller_num = '$uid' AND quantity >= 1;";  //;"; //supplier = '$mfgCode' AND 
						
						//execute query
						$result = mysql_query($query);
								
						if (!$result)
						{
							print mysql_error();
							exit;
						}
						else
						{
							//echo ("select query run");
						}
						
						for ($index = 0; $index < mysql_num_rows($result); $index++)
						{
							$row_array=mysql_fetch_array($result);
					?>
					<tr>
						<td class="currentInventory_manageMyShop">
							<?php echo $row_array['supplier'];?>
						</td> 
						<!--<td class="currentInventory_manageMyShop">
							<?php //echo $row_array['product'];?>
						</td>-->
						<td class="currentInventory_manageMyShop">
							<?php echo $row_array['item_name']?>
						</td>
						<td class="currentInventory_manageMyShop">
							<?php echo $row_array['quantity']?>
						</td>
						<td class="currentInventory_manageMyShop">
							<?php echo $row_array['max_quantity']?>
						</td>
						<td class="currentInventory_manageMyShop">
							<?php echo $row_array['purchase_price']?>
						</td>
						<td class="currentInventory_manageMyShop">
							<?php echo $row_array['sell_price']?>
						</td>
						<td class="currentInventory_manageMyShop">
							<?php echo $row_array['description']?>
						</td>
					</tr>
					<?php
						}
						mysql_close();
					?>
				</table>
				<br/>
			</form>
		</div>	

		<br/>
		<br/>
		<br/>

		 <div class = "menuButtons">
			<br/>
	            <a href="addItem.php" class="button">Add <!--List of--> Item to Inventory</a>
				<a href="editInventory.php" class="button">Edit <!--List of--> Item(s) in Inventory</a>
			<br />
        </div>
        
		<p class="spacer"></p>

	</div>	
		<!-- Like the header, the footer will be present on ALL pages, but it will be unchanged -->
	<div class="footer_manageMyShop">
	<?php
		include('../pageFooterScripts/footer.php');
    ?>
	</div>
	
    </body>
</html>