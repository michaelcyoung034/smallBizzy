//updateValidation.js
function validInput()
{
	var title = document.getElementById("title").value;
	var message = document.getElementById("message").value;
	
	//empty error message string
	var err = '';
	var valid = true;
	
	//first name
	if (title == '')
	{
		err += "Post must have a title and cannot be blank\n";
		valid = false;
	}
	
	//last name
	if (message == '')
	{
		err += "Post must have a message and cannot be blank\n";
		valid = false;
	}
	
	if (!valid)
	{
		alert(err);
	}
	return valid;
}