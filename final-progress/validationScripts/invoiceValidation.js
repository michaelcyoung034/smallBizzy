//invoiceValidation.js
function custNumValidation()
{
	//only numbers are allowed
	var forbidden = /\D/;
	var acctNum = document.getElementById("CustomerID").value;
	var valid = true;
	
	if (forbidden.test(acctNum))
	{
		alert("accounts are identified by number");
		valid = false;
	}
	else
	{
		valid = true;
		//the one exception to this rule is the acct# cannot be 0. We will not be selling to unregistered users.
		if (acctNum <= 0)
		{
			alert("account number cannot be 0");
			valid = false;
		}
	}
	
	return valid;
}

function verifyDelete(numOfItems)
{
	//only numbers are allowed
	var forbidden = /\D/;
	var lineToDelete = document.getElementById("line_number").value;
	//alert("line to delete: " + lineToDelete);
	var valid = true;
	
	if (lineToDelete > numOfItems)
	{
		alert("invalid line to delete");
		valid = false;
	}
	
	
	if (lineToDelete == "")
	{
		alert("delete line cannot be blank");
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

function verifyCancel()
{
	//alert("you are about to cancel");
	return confirm("are you sure you want to cancel this invoice?");
}

function verifyFinalize()
{
	var valid = true;
	//alert("items: " + numOfItems);
	var payment = document.getElementById("paymentType").value;
	//alert("payment: " + payment);
	var err = "";

	if(payment == 'none')
	{
		err += "please select a payment method\n";
		valid = false;
	}
	
	if (!valid)
	{
		alert(err);
	}
	
	return valid;
}

function addItemValidation()
{
	//alert("start add validation");
	var mfg = document.getElementById("mfg").value;
	//alert("mfg: " + mfg);
	var part = document.getElementById("part").value;
	//alert("part: " + part);
	var err = "";
	var error = false;
	
	if (mfg == '')
	{
		err += "supplier cannot be blank\n";
		error = true;
	}
	
	if (part == '')
	{
		err += "part number cannot be blank\n";
		error = true;
	}
	
	if (error)
	{
		alert(err);
	}
	
	return !error;
}