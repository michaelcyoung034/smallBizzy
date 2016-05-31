//updateValidation.js
function checkFormInput(){
	
	/*
		We will only permit letters (any case) and numbers. We do not want to allow white space, punctuation, or symbols that could be used in HTML or SQL. This will make the user login form more secure from injection attacks.
	*/
	
	//make sure you are instantiating variables appropriate for what you are trying to do. In this case, we are only interrested in the username and password
	//var cFirst = document.getElementById("cFirst").value;
	//var cLast = document.getElementById("cLast").value;
	var username = document.getElementById("username").value;
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
	
	if(result1 == false){
		valid = false;
		error += "The phone number is not in the correct format!\n";
	}
	
	if(result2 == false){
		valid = false;
		error += "The email address is not in the correct format!\n";
	}
	
	if(!valid){
		alert(error);
	}
	
	else
		return valid;
}