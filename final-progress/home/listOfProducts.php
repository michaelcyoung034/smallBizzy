<?php session_start();
    /****************************************************************************
    * File Name: listOfProducts.php
    * Use-case: search results for items from a particular seller
    * Author: Mike Young
    *
	* This page shows the user a list of items available to buy from a particular seller
    *****************************************************************************/
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
	
	//generate the select statement
	//get the inventory data. the seller's ID will be available from  $_GET['seller_num'].
	$seller_num = $_GET['seller_num'];
	$inventoryQuery = "SELECT item_num, item_name, quantity, sell_price FROM inventory WHERE seller_num = '$seller_num' AND quantity >= 1;";
	
	//run messageQuery
	$inventoryResult = mysql_query($inventoryQuery);
	
	if (!$inventoryResult)
	{
		print mysql_error();
		exit;
	}
	else
	{
		//echo ("query run".'<br/>');
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>search results for (user's input)</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <link href="../styleSheets/masterPageStyle.css" type="text/css" rel="Stylesheet" />
    </head>
    <body>
	<?php
		include('../pageHeaderScripts/header.php');
    ?>
        <div id="content">
			<!-- if there are zero results returned, display a message saying nothing was found. Otherwise, display the results in a grid view.

			If our site grows to a considerable size, we will need to break the search results down to 20-record chunks and load the results one chunk at time. At this point, large results are not a concern-->
            Browse products<br />
            <input type="text" /><button>Search</button><br />
			<p class = "searchResultsTitle"> ::SEARCH RESULTS:: </p> <!-- per form processing of user-input. for now, we will display all items the seller has available. -->
			<table class="searchResultsTable">
				<?php
					$itemsPerRow = 4;
					//the result will display 4 items per line. to calculate how many lines there will be, numberOfRows = intval(mysql_num_rows($inventoryResult)/4) + 1. the extra row is to 
					for ($i = 0; $i < mysql_num_rows($inventoryResult)/$itemsPerRow; $i++)
					{
				?>
				<tr>
					<?php
						for ($j = 0; $j < $itemsPerRow; $j++)
						{
							$inventory_row_array = mysql_fetch_array($inventoryResult);
							//we do not want empty or null data appearing on the last line of the page, so immediately exit at the end of the query results
							//echo "num of rows: ".mysql_num_rows($inventoryResult)/4;
							if ($inventory_row_array == false)
							{
								break;
							}
							//if there is a record to be displayed, display it.
							else
							{
								?>
								<td class="itemForSale">
									<table>
										<tr>
											<!-- if we ever get to implement an image for the items, it wil go here. Until then, this table will only show the data. -->
											<th>
												Item Name
											</th>
											<td>
												<a href="viewItem.php?item_num=<?php echo $inventory_row_array['item_num']; ?>"><?php echo $inventory_row_array['item_name']; ?></a>
											</td>
										</tr>
										<tr>
											<th>
												Quantity Available
											</th>
											<td>
												<?php echo $inventory_row_array['quantity']; ?>
											</td>
										</tr>
										<tr>
											<th>
												Price
											</th>
											<td>
												<?php echo $inventory_row_array['sell_price']; ?>
											</td>
										</tr>
									</table>
								</td>
								<?php
							}
						}
					?>
				</tr>
				<?php
					}
				?>
			</table>
		<a href="sellers.php">Back to Sellers</a>
        </div>
	<?php
		include('../pageFooterScripts/footer.php');
    ?>
    </body>
</html>