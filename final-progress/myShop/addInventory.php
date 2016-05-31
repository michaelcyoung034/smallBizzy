<?php session_start();
    /****************************************************************************
    * File Name: addInventory.php
    * Use-case: add inventory
    * Author: Derek Wang
    *
	* This page allows sellers to add merchandise to sell. This is the page where high-volume sellers can list their catalog of merchandise.
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
			$_SESSION['addMfg'] = strtoupper($_POST['mfg']);
			$_SESSION['partsToAdd'] = 0;
		}
		break;
		case 'Add Part':
		{
			//if the user added other data to previous parts, preserve the input.
			//updateItemData();
			//generate the select statement
			$mfg = $_SESSION['addMfg'];
			$part = strtoupper($_POST['part']);
			$sid = $_SESSION['user_num'];
			$query = "SELECT supplier, product FROM inventory WHERE '$mfg' = supplier AND '$part' = product AND seller_num = '$sid';";
			
			//run the statement
			$result = mysql_query($query);
			
			if (!$result)
			{
				print mysql_error();
				exit;
			}
			else
			{
				//echo ("query run<br/>");
			}
			//no returned results means the record can be added
			if (mysql_num_rows($result) == 0)
			{
				//make sure the part is not already in the current session
				if (!(in_array(strtoupper($_POST['part']),$_SESSION['invAdd']['parts'])))
				{
					//push the given part code to the part stack
					array_push($_SESSION['invAdd']['parts'],strtoupper($_POST['part']));
					$_SESSION['partsToAdd']++;
					//echo ("number of parts: ".$_SESSION['partsToAdd']."<br/>");
				}
				else
				{
					?>
						<script type="text/javascript" >
							alert("Item already in list");
						</script>
					<?php
				}
			}
			//otherwise, the record is already present
			else if (mysql_num_rows($result) >= 1)
			{
				?>
					<script type="text/javascript" >
						alert("Item already exists");
					</script>
				<?php
			}
			
			mysql_close();
		}
		break;
		case 'Delete Item':
		{
			$_SESSION['invAdd']['parts'][$_POST['line_number']-1]="VOID";
			$_SESSION['invAdd']['descs'][$_POST['line_number']-1]="VOID";
			$_SESSION['invAdd']['qohs'][$_POST['line_number']-1]="VOID";
			$_SESSION['invAdd']['opts'][$_POST['line_number']-1]="VOID";
			$_SESSION['invAdd']['purchs'][$_POST['line_number']-1]="VOID";
			$_SESSION['invAdd']['sells'][$_POST['line_number']-1]="VOID";
			//$_SESSION['partsToAdd']--;
		}
		break;
		case 'Add Items to database':
		{
			//update the fields so the data being entered is good.
			updateItemData();
			//if the user forgot to fill in fields, let them know
			$emptyField = false;
			
			//this variable tracks how many items were successfully added to the database. it starts at 0 and increments each time a record is added successfully.
			$itemsAdded = 0;
			for ($index = 0; $index < $_SESSION['partsToAdd']; $index++)
			{
				//echo ("part number ".$index." out of ".$_SESSION['partsToAdd']." to add<br/>");
				$part = $_POST['invAdd']['parts'][$index];
				$mfg = $_SESSION['addMfg'];
				//if the mfg is not voided out...
				if ($part != "VOID")
				{
					//generate the statement to check for already existing records
					$query = "SELECT supplier, product FROM inventory WHERE ('$mfg' = supplier AND '$part' = product);";
					
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
						$mfg = $_SESSION['addMfg'];
						$part = strtoupper($_SESSION['invAdd']['parts'][$index]);
						$desc = $_SESSION['invAdd']['descs'][$index];
						$qoh = $_SESSION['invAdd']['qohs'][$index];
						$opt = $_SESSION['invAdd']['opts'][$index];
						$purch = $_SESSION['invAdd']['purchs'][$index];
						$sell = $_SESSION['invAdd']['sells'][$index];
						
						//reassign the variable to insert the part
						$query = "INSERT INTO inventory (seller_num, supplier, product, item_name, description, quantity, max_quantity, purchase_price, sell_price) VALUES ('$sid', '$mfg', '$part', '$desc', '$desc', '$qoh', '$opt', '$purch', '$sell');";
						
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
							//remember to increment the counter
							$itemsAdded++;
						}
					}
					//otherwise, the record is already present, so do nothing
				}
			}
			mysql_close();
			reset_arrays();
			?>
				<script type="text/javascript">
					alert("<?php echo $itemsAdded ?> items added to inventory");
				</script>
			<?php
		}
		break;
		case 'Update':
		{
			updateItemData();
		}
		break;
		case 'Reset':
		{
			reset_arrays();
		}
		break;
	}
	
	//determine whether the mfg code has been given
	$addInv = isset($_SESSION['addMfg']);
	
	//used to reset the arrays after a set of items has been added to the database or if the session arrays are not properly instantiated or populated
	function reset_arrays()
	{
		unset($_SESSION['addMfg']);
		$_SESSION['invAdd']['parts'] = array();
		$_SESSION['invAdd']['descs'] = array();
		$_SESSION['invAdd']['qohs'] = array();
		$_SESSION['invAdd']['opts'] = array();
		$_SESSION['invAdd']['purchs'] = array();
		$_SESSION['invAdd']['sells'] = array();
		$_SESSION['partsToAdd'] = 0;
	}
	
	//update the user's data input
	function updateItemData()
	{
		if(!($_SESSION['partsToAdd'] == 0))
		{
			$_SESSION['invAdd']['parts'] = $_POST['parts'];
			$_SESSION['invAdd']['descs'] = $_POST['descs'];
			$_SESSION['invAdd']['qohs'] = $_POST['qohs'];
			$_SESSION['invAdd']['opts'] = $_POST['opts'];
			$_SESSION['invAdd']['purchs'] = $_POST['purchs'];
			$_SESSION['invAdd']['sells'] = $_POST['sells'];
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Add Inventory for MFG: <?php echo $_SESSION['addMfg'] ?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<!-- make sure the style sheet is accessible from all web pages -->
        <link href="../styleSheets/masterPageStyle.css" type="text/css" rel="Stylesheet" />
		<script type="text/javascript" src="../validationScripts/addInventoryValidation.js"></script>
    </head>
    <body>
	<?php
		//echo "session:<br/>";
		//var_dump($_SESSION);
		//echo "<br/>";
		//echo "post:<br/>";
		//var_dump($_POST);
		include('../pageHeaderScripts/header.php');
    ?>
        <div id="content">
			<?php
				if (!$addInv)
				{
			?>
			<form name="mfgInput" method="post" action="addInventory.php">
				<input name="mfg" id="mfg" maxlength="4" /><input name="instruction" value="Submit" type="submit" onclick="return validMfgInput()" />
			</form>
			<?php
					reset_arrays();
				}
				else
				{
			?>
            Add product(s) to inventory.<br />
			<form name="addParts" method="post" action="addInventory.php">
				<table id="inventoryAddTable">
					<tr>
						<th colspan="8">
							Add <?php echo $_SESSION['addMfg']; ?> product(s) to inventory
						<th>
					</tr>
					<tr>
						<td>
							Line
						</td>
						<td>
							MFG
						</td>
						<td>
							PART
						</td>
						<td>
							DESC
						</td>
						<td>
							QOH
						</td>
						<td>
							MAX
						</td>
						<td>
							Purchase
						</td>
						<td>
							Sell
						</td>
					</tr>
				<?php
					for($index = 0; $index < $_SESSION['partsToAdd']; $index++)
					{
						$part = $_SESSION['invAdd']['parts'][$index];
						$disabled = ($part == "VOID");
				?>
					<tr>
						<td>
							<?php echo $index + 1;?>
						</td>
						<td>
							<?php echo $_SESSION['addMfg'];?>
						</td>
						<td>
							<input id="parts[<?php echo $index?>]" name="parts[<?php echo $index?>]" value="<?php echo strtoupper($_SESSION['invAdd']['parts'][$index]);?>" maxlength="12" <?php if($disabled)echo 'disabled' ?> size="12" />
						</td>
						<td>
							<input id="descs[<?php echo $index?>]" name="descs[<?php echo $index?>]" value="<?php echo $_SESSION['invAdd']['descs'][$index]?>" maxlength="36" <?php if($disabled)echo 'disabled' ?> size="12" />
						</td>
						<td>
							<input id="qohs[<?php echo $index?>]" name="qohs[<?php echo $index?>]" value="<?php echo $_SESSION['invAdd']['qohs'][$index]?>" <?php if($disabled)echo 'disabled' ?> size="4" />
						</td>
						<td>
							<input id="opts[<?php echo $index?>]" name="opts[<?php echo $index?>]" value="<?php echo $_SESSION['invAdd']['opts'][$index]?>" <?php if($disabled)echo 'disabled' ?> size="4" />
						</td>
						<td>
							<input id="purchs[<?php echo $index?>]" name="purchs[<?php echo $index?>]" value="<?php echo $_SESSION['invAdd']['purchs'][$index]?>" <?php if($disabled)echo 'disabled' ?> size="8" />
						</td>
						<td>
							<input id="sells[<?php echo $index?>]" name="sells[<?php echo $index?>]" value="<?php echo $_SESSION['invAdd']['sells'][$index]?>" <?php if($disabled)echo 'disabled' ?> size="8" />
						</td>
					</tr>
				<?php
					}
				?>
				</table>
				<input name="part" maxlength="12" /><input name="instruction" value="Add Part" type="submit" /><br/>
				<input name="line_number" id="line_number"/><input name="instruction" type="submit" value="Delete Item" onclick="return verifyDelete(<?php echo $_SESSION['partsToAdd']; ?>);"/><br/>
				<input name="instruction" value="Update" type="submit" onclick="return true;" /><br/>
				<input name="instruction" value="Reset" type="submit" onclick="return true;" /><br/>
				<input name="instruction" value="Add Items to database" type="submit" onclick="return validInput(<?php echo $_SESSION['partsToAdd']; ?>);" /><br/>
			</form>
			<?php
				}
			?>
            <a href="manageMyShop.php">Back to inventory manager</a>
        </div>
		<!-- Like the header, the footer will be present on ALL pages, but it will be unchanged -->
	<?php
		include('../pageFooterScripts/footer.php');
    ?>
    </body>
</html>