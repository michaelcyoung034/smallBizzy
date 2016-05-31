//addInventoryValidation.js
function validInput(formFields)
{
	//alert("you have this numbe rof parts: " + formFields);
	var validInput = true;
	//for each row of parts entered...
	for (var i = 0; i < formFields; i++)
	{
		var part = document.getElementById("parts[" + i + "]").value;
		var desc = document.getElementById("descs[" + i + "]").value;
		var qoh = document.getElementById("qohs[" + i + "]").value;
		var max = document.getElementById("opts[" + i + "]").value;
		var pur = document.getElementById("purchs[" + i + "]").value;
		var sell = document.getElementById("sells[" + i + "]").value;
		
		//assemble the fields into an array to make ittereting easier
		//blank field check
		var fields = new Array(part, desc, qoh, max, pur, sell);
		
		for (var j = 0; j < fields.length; j++)
		{
			if(fields[j] == "")
			{
				alert("please fill out all fields");
				validInput = false;
				break;
			}
		}
		
		//number check
		fields = new Array(qoh, max, pur, sell);
		//search for a non-digit character
		var pattern = ;
		//var nonDigit = pattern.test();
		
		for (var j = 0; j < fields.length; j++)
		{
			if(/\D/.test(fields[j]))
			{
				alert("quantity, max quantity, purchase price, and sell price must be numbers");
				validInput = false;
				break;
			}
		}
		
		//if we find invalid input, 
		if (validInput == false)
		{
			break;
		}
	}
	
	return validInput;
}