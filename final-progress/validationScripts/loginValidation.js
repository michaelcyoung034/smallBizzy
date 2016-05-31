//loginValidation.js

function validInput()
{
	var username = document.getElementById("login_username").value;
	var pass = document.getElementById("login_pass").value;
	
	//empty error message string
	var err = '';
	var valid = true;
	
	//username and password fields cannot be left blank
	if (username == "" || pass == "")
	{
		err += "username and password cannot be blank\n";
		//do not do anything if the user left the fields blank
		valid = false;
	}
	//username cannot contain whitespace characters
	var forbidden = /\s/;
	if (forbidden.test(username) || forbidden.test(pass))
	{
		err += "Username or password cannot be contain white space\n";
		valid = false;
	}
	
	if (!valid)
	{
		alert(err);
	}
	//alert("this form is valid: " + !error);
	return valid;
}

function checkUsername()
{
	var u = $("login_username").value;
	
	new Ajax.Request("availability.php",
	{
		method: "post",
		parameters: {uname: u},
		onSuccess: displayResult,
		onFailure: notifyFailure
	});
}

function notifyFailure(ajax)
{
	alert("Failure: " + ajax.responseText)
}

function displayResult(ajax)
{
	var result = ajax.responseText;
	
	if (result == 'valid')
	{
		//the user will be allowed to log in
		$("Submit").disabled = false;
	}
	else if (result == 'invalid')
	{
		$("Submit").disabled = true;
	}
}

function showUsername()
{
	$("loginInfo").innerHTML = "username";
}

function showPassword()
{
	$("loginInfo").innerHTML = "password";
}

function showLogin()
{
	$("loginInfo").innerHTML = "log in";
}

function revert()
{
	$("loginInfo").innerHTML = "";
}