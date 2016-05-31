/*
    * File Name: acctCheck.js
    * Use-case: user account registration
    * Author: Victoria Phillips
    *
	* This page checks the validity and availability of some parts of the account information.
	  Username, password, phone, and email are used for validity, whereas username is only checked
	  for availability.
*/

//this method should only check to see if the username is available. It should not perform form validataion. The reason is the two functions will run asynchronously. This method will finish running and return a value after the client-side code finishes. If we were to combine both methods into one, it would have to be done in one place, either client- or server-side.
function checkFormInput(){
	
	/*
		We will only permit letters (any case) and numbers. We do not want to allow white space, punctuation, or symbols that could be used in HTML or SQL. This will make the user login form more secure from injection attacks.
	*/
	
	//make sure you are instantiating variables appropriate for what you are trying to do. In this case, we are only interrested in the username and password
	var fname = document.getElementById("f_name").value;
	var lname = document.getElementyById("l_name").value;
	var username = document.getElementById("username").value;
	var uPassword = document.getElementById("uPassword").value;
	var address = document.getElementById("address").value;
	var city = document.getElementById("city").value;
	var state = document.getElementById("state").value;
	var zip = document.getElementById("zip").value;
	var email = document.getElementById("email").value;
	var phone = document.getElementById("phone").value;
	
	var pattern1 = /\s/;
	var pattern2 = /\d{10}/;
	var pattern3 = /\w+@[a-z]+\.[a-z]{3}/;
	var result1 = pattern2.test(phone);
	var result2 = pattern3.test(email);
	var valid = true;
	
	var error = "";
	
	//if you hit an invalid input field, return invalid input immediately. Also, consider making one error message with all points of the invalid input.
	if (pattern1.test(username)){
		valid = false;
		error += "You put a whitespace in your username! Please enter it again.\n";
	}
	
	if(pattern1.test(uPassword)){
		valid = false;
		error += "You put a whitespace in your password! Please enter it again.\n";
	}
	
	if(result1 == false){
		valid = false;
		error += "The phone number is not in the correct format!\n";
	}
	
	if(result2 == false){
		valid = false;
		error += "The email address is not in the correct format!\n";
	}
	
	if (fname == ''){
		valid = false;
		error += "You did not enter your first name. Please enter it. \n";
	}
		
	if (lname == ''){
		valid = false;
		error += "You did not enter your last name. Please enter it. \n";
	}
		
	if (username == ''){
		valid = false;
		error += "You did not enter your username. Please enter it. \n";
	}
		
	if (uPassword == ''){
		valid = false;
		error += "You did not enter your password. Please enter it. \n";
	}

	if (address == ''){
		valid = false;
		error += "You did not enter your address. Please enter it. \n";
	}
		
	if (city == ''){
		valid = false;
		error += "You did not enter your city. Please enter it. \n";
	}
		
	if (state == ''){
		valid = false;
		error += "You did not enter your state. Please enter it. \n";
	}
		
	if (zip == ''){
		valid = false;
		error += "You did not enter your zip code. Please enter it. \n";
	}
		
	if (email == ''){
		valid = false;
		error += "You did not enter your email address. Please enter it. \n";
	}
		
	if (phone == ''){
		valid = false;
		error += "You did not enter your phone number. Please enter it. \n";
	}
	
	if(!valid){
		alert(error);
		return false;
	}
	
	else
		return true;
}

function remember(){
	document.getElementById("remember1").style.color = "red";
	document.getElementById("remember1").style.fontStyle = "italic";
	document.getElementById("remember1").style.backgroundColor = "yellow";
	document.getElementById("remember2").style.color = "red";
	document.getElementById("remember2").style.fontStyle = "italic";
	document.getElementById("remember2").style.backgroundColor = "yellow";
}

function highlight(){
	var highlight1 = document.getElementById("highlight1");
	var highlight2 = document.getElementById("highlight2");
	highlight1.style.backgroundColor = yellow;
	highlight2.style.backgroundColor = yellow;
}

function normal(){
	document.getElementById("remember1").style.color = "black";
	document.getElementById("remember1").style.fontStyle = "normal";
	document.getElementById("remember1").style.backgroundColor = "white";
	document.getElementById("remember2").style.color = "black";
	document.getElementById("remember2").style.fontStyle = "normal";
	document.getElementById("remember2").style.backgroundColor = "white";
}

function checkUName(){
	var u = $("username").value;
	
	//if there is invalid input at this point, disable the 'submit' button and display a message letting the user know how to fix the problem
	
	//the ajax call should be in a method of its own. Also, make sure it is passing the right objects and the .php file is assigning the variables correctly.
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
	alert(ajax.responseText);
}

function displayResult(ajax){
	var result = ajax.responseText;
	//alert(result);
	$('msgbox').innerHTML = result;
	
	if (result == 'taken'){
		//if you want to have this box show up, you need to add it to the page where the username field is.
		$('msgbox').style.backgroundColor = "red";
		$('msgbox').style.color = "white";
		$('msgbox').focus();
		$('submit').disabled = true;
	}
	
	else if (result == 'available'){
		$('msgbox').style.backgroundColor = "green";
		$('msgbox').style.color = "white";
		$('submit').disabled = false;
	}
	
	else{
		alert("Some other error is going on! Go check it out!");
	}
}