function checkval(){

	var message=document.getElementById("message").value;
	var subject=document.getElementById("title").value;
	var recipient=document.getElementById("recipient").value;
	var err = "";
	var valid = true;
	//alert("checking val");
	if (message == "")
	{
		err += "You did not enter any values in your message, please go back and try again\n";
		//document.getElementById("message").focus();
		valid = false;
	}
	
	if (subject == "")
	{
		err += "You did not enter any values in your subject, please go back and try again\n";
		//document.getElementById("subject").focus();
		valid = false;
	}
	
	if (recipient == "")
	{
		err += "You did not enter any recipient, please go back and try again\n";
		//document.getElementById("subject").focus();
		valid = false;
	}
	
	if(!valid)
	{
		alert(err);

	}
    return valid;
}

function subjects(value) {
	var values = ["Please Enter your subject ","This box provides you with information on how to compose a message at SmallBizzy Messaging"]
  document.getElementById("messageBox").value = values[value];

}
function input(){
	var messBox = document.getElementById("message");
	messBox.style.backgroundColor =green;
}


function validateRecipient(){
	var u = $("recipient").value;
	//alert("function reached");
	//create a new Ajax request to the displayTime.php URL, and no parameters (because parameters are not required by the displayTime.php program)
	new Ajax.Request( "determineRecipient.php", 
	{ 
		method: "get", 
		parameters:{uname: u},
		onSuccess: displayResult,
		onFailure: notifyFailure
	} 
	);
}
function notifyFailure(ajax)
{
	alert(ajax.responseText);
}

//the displayResult function is executed when the Ajax request is successful
//ajax must be sent as parameter to this function
function displayResult(ajax)
{
	var result = ajax.responseText;
	//alert(result);
	$('msgbox').innerHTML = result;
	
	if (result == "valid")
	{
		//alert("valid result");
		$('msgbox').style.backgroundColor = "green";
		//$('msgbox').style.color = "white";
		$('msgbox').focus();
		$('Send').disabled = false;
		//$('Send').style.backgroundColor="green";
	}

	else if (result == "invalid")
	{
		//alert("invalid result");
		$('msgbox').style.backgroundColor = "red";
		//$('msgbox').style.color = "white";
		$('Send').disabled = true;
	}
	else
	{
		alert("Some other error is going on! Go check it out!");
	}
	//$("time").innerHTML = ajax.responseText;
}