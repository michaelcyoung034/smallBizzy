//addInventoryValidation.js
function validInput(numOfItems)
{
	if(numOfItems == 0)
	{
		alert("there is nothing to add");
		validInput = false;
	}
	else
	{
		var validInput = true;
		
		for (var i = 0; i < numOfItems; i++)
		{
			//var part = document.getElementById("parts[" + i + "]").value;
			//var desc = document.getElementById("descs[" + i + "]").value;
			//blank field check
			var pur = document.getElementById("purchase_price[" + i + "]");
			var sell = document.getElementById("sell_price[" + i + "]");
			var fields = [pur, sell];
			
			for (var j = 0; j < fields.length; j++)
			{
				if(fields[j].value == "" || !(/^[0-9]+\.[0-9]{2}$/.test(fields[j].value)))
				{
					validInput = false;
					fields[j].style.backgroundColor = "red";
					//break;
				}
				else
				{
					fields[j].style.backgroundColor = "white";
				}
			}
			
			//blank field check
			var qoh = document.getElementById("qohs[" + i + "]");
			var max = document.getElementById("opts[" + i + "]");
			var intFields = [qoh, max];
			
			for (var k = 0; k < intFields.length; k++)
			{
				if(intFields[k].value == "" || !(/^[0-9]+$/.test(intFields[k].value)))
				{
					validInput = false;
					intFields[k].style.backgroundColor = "red";
					//break;
				}
				else
				{
					intFields[k].style.backgroundColor = "white";
				}
			}
			
		}
		
		//if we find invalid input, 
		if (validInput == false)
		{
				alert("please fill out all fields and correctly format them.");
			//break;
		}
		
		return validInput;
	}
}

function validMfgInput()
{
	var validInput = true;
	var part = document.getElementById("mfg").value;
	
	if(part == "")
	{
		alert("please give a manufacturer code");
		validInput = false;
	}
	
	return validInput;
}

function verifyDelete(numOfItems)
{
	//only numbers are allowed
	var forbidden = /\D/;
	var lineToDelete = document.getElementById("line_number").value;
	//alert("line to delete: " + lineToDelete);
	var valid = true;
	
	if(lineToDelete == "")
	{
		alert("please enter a line number");
		validInput = false;
	}
	
	if (lineToDelete > numOfItems || lineToDelete <= 0)
	{
		
		//alert("invalid line to delete. lineToDelete: " + lineToDelete + ". numOfItems: " + numOfItems);
		valid = false;
	}
	
	if (forbidden.test(lineToDelete))
	{
		alert("lines are identified by number");
		valid = false;
	}
	
	if (valid)
	{
		valid = confirm("are you sure you want to void this item?");
	}
	
	return valid;
}

function validSingleItem()
{
	var name = document.getElementById("item_name");
	var desc = document.getElementById("description");
	var qty = document.getElementById("qty");
	var sell = document.getElementById("sell");

	//DEBUG FROM HERE
	var supplier = document.getElementById("supplier");
	var purchase = document.getElementById("purchase");
	var reorder_point = document.getElementById("reorder_point"); 
	//DEBUG TO HERE

	var valid = true;
	var err = "";
	
	//blank test
	//name cannot be blank
	if(name.value == "")
	{
		err += "please enter an item name\n";
		name.style.borderColor = "red";
		valid = false;
	}
	else
	{
		name.style.borderColor = "initial";
	}
	//description cannot be blank
	if(desc.value == "")
	{
		err += "please enter an item description\n";
		desc.style.borderColor = "red";
		valid = false;
	}
	else
	{
		desc.style.borderColor = "initial";
	}
	//quantity available cannot be blank, even though it is filled when the page loads. The user may still inadvertently delete it.
	if(qty.value == "")
	{
		err += "please enter a quantity for this item\n";
		qty.style.borderColor = "red";
		valid = false;
	}
	else
	{
		qty.style.borderColor = "initial";
	}
	//sell price cannot be blank
	if(sell.value == "")
	{
		err += "please enter a sell price\n";
		sell.style.borderColor = "red";
		valid = false;
	}
	else
	{
		sell.style.borderColor = "initial";
	}

	//DEBUG FROM HERE
	//supplier name cannot be empty
	if(supplier.value == "")
	{
		err += "please enter a supplier name\n";
		supplier.style.borderColor = "red";
		valid = false;
	}
	else
	{
		supplier.style.borderColor = "initial";
	}	

	if(purchase.value == "")
	{
		err += "please enter a purchase price\n";
		purchase.style.borderColor = "red";
		valid = false;
	}
	else
	{
		purchase.style.borderColor = "initial";
	}	

	if(reorder_point.value == "")
	{
		err += "please enter a quantity reorder point AKA minimum quantity in stock\n";
		reorder_point.style.borderColor = "red";
		valid = false;
	}
	else
	{
		reorder_point.style.borderColor = "initial";
	}
	//DEBUG TO HERE
	
	//format test
	var priceFormat = /^[0-9]+.[0-9][0-9]$/;
	
	if(!priceFormat.test(sell.value))
	{
		err += "please use decimal format for the sell price: 0.00\n";
		sell.style.borderColor = "red";
		valid = false;
	}
	else if(sell.value == "0.00")
	{
		err += "please give a sell price for this item that is not $0.00\n";
		sell.style.borderColor = "red";
		valid = false;
	}
	else
	{
		sell.style.borderColor = "initial";
	}

	if(!priceFormat.test(purchase.value))
	{
		err += "please use decimal format for the purchase price: 0.00\n";
		purchase.style.borderColor = "red";
		valid = false;
	}
	else if(purchase.value == "0.00")
	{
		err += "please give a purchase price for this item that is not $0.00\n";
		purchase.style.borderColor = "red";
		valid = false;
	}
	else
	{
		purchase.style.borderColor = "initial";
	}
	
	if(!valid)
	{
		alert(err);
	}
	
	return valid;
}

function checkItemName(){
	var i = $("item_name").value;
	
	//if there is invalid input at this point, disable the 'submit' button and display a message letting the user know how to fix the problem
	
	//the ajax call should be in a method of its own. Also, make sure it is passing the right objects and the .php file is assigning the variables correctly.
	new Ajax.Request("availability.php",
	{
		method: "post",
		parameters: {iname: i},
		onSuccess: displayResult,
		onFailure: notifyFailure
	});
}

function notifyFailure(ajax)
{
	alert(ajax.responseText);
}

function displayResult(ajax){
	var result = ajax.responseText;
	//alert(result);
	$('msgbox').innerHTML = result;
	
	if (result == 'item name used'){
		//if you want to have this box show up, you need to add it to the page where the username field is.
		$('msgbox').style.backgroundColor = "red";
		$('msgbox').style.color = "white";
		$('msgbox').focus();
		$('submit').disabled = true;
	}
	
	else if (result == 'item name available'){
		$('msgbox').style.backgroundColor = "green";
		$('msgbox').style.color = "white";
		$('submit').disabled = false;
	}
	
	else{
		alert("Some other error is going on! Go check it out!");
	}
}


