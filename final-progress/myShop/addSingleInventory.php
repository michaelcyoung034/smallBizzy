<?php session_start();
    /****************************************************************************
    * File Name: addInventory.php
    * Use-case: add inventory
    * Author: Derek Wang
    *
	* This page allows sellers to add merchandise to sell. This page is designed for sellers intending to sell an individual item, or low-volume sellers.
    *****************************************************************************/
	//make sure the part is not already in the database.
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
	
	switch ($_POST['instruction'])
	{
		case 'Submit':
		{
		}
		break;
		case 'List Item':
		{
			//generate the statement to check for already existing records
			$name = $_POST['item_name'];
			$query = "SELECT item_name FROM inventory WHERE (supplier = 'USER' AND product = 'SINGLE_ITEM' AND item_name = '$name');";
			
			//run the statement
			$result = mysql_query($query);
			
			if (!$result)
			{
				print mysql_error();
				exit;
			}
			else
			{
				//echo ("query executed<br/>");
			}
			//no returned results means the record can be added
			if (mysql_num_rows($result) == 0)
			{
				$sid = $_SESSION['user_num'];
				$desc = mysql_real_escape_string(htmlspecialchars($_POST['description']));
				$qoh = $_POST['qty'];
				$sell = $_POST['sell'];
				
				//reassign the variable to insert the part
				$query = "INSERT INTO inventory (seller_num, item_name, description, quantity, sell_price) VALUES ('$sid', '$name', '$desc', '$qoh', '$sell');";
				
				//run the statement
				$result = mysql_query($query);
				
				if (!$result)
				{
					print mysql_error();
					exit;
				}
				else
				{
					//echo ("item added<br/>");
				}
				?>
					<script type="text/javascript">
						alert("<?php echo $name ?> successfully listed");
					</script>
				<?php
			}
			else
			{
				?>
					<script type="text/javascript">
						alert("<?php echo $name ?> already exists");
					</script>
				<?php
			}
			mysql_close();
		}
		break;
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Add your listing</title>
		<!-- make sure the style sheet is accessible from all web pages -->
        <link href="../styleSheets/masterPageStyle.css" type="text/css" rel="Stylesheet" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<script type="text/javascript" src="../validationScripts/addInventoryValidation.js"></script>
    </head>
    <body>
	<?php
		include('../pageHeaderScripts/header.php');
    ?>
        <div id="content">
			<form name="additem" method="post" action="addSingleInventory.php" onsubmit="return validSingleItem();">
				<table id="inventoryAddTable">
					<tr>
						<td>
							List your item
						</td>
					</tr>
					<tr>
						<td>
							Item Name
						</td>
					</tr>
					<tr>
						<td>
							<input name="item_name" id="item_name" />
						</td>
					</tr>
					<tr>
						<td>
							Description<br/>
							<textarea name="description" id="description" cols="50" rows="10" ></textarea>
						</td>
					</tr>
					<tr>
						<td>
							Quantity Available
						</td>
					</tr>
					<tr>
						<td>
							<input id="qty" name="qty" value="1" />
						</td>
					</tr>
					<tr>
						<td>
							Sell Price
						</td>
					</tr>
					<tr>
						<td>
							<input name="sell" id="sell" />
						</td>
					</tr>
				</table>
				<input name="instruction" value="List Item" type="submit" /><br/>
			</form>
            <a href="manageMyShop.php">Back to inventory manager</a>
        </div>
		<!-- Like the header, the footer will be present on ALL pages, but it will be unchanged -->
	<?php
		include('../pageFooterScripts/footer.php');
    ?>
    </body>
</html>