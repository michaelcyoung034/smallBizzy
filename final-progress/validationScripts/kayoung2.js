/****************************************************************************
* File Name: kayoung2.js
* Use-case: customer view inventory
* Author: Kayoung Kim
* E-mail: kayoung2@umbc.edu
*
* This javascript file is for the customer view inventory use-case.
*****************************************************************************/

/*
* 
* Name: checkQTY
* Param: none
* return: none
* 
* Description: 
* 	This function is for viewItem.php
* 	It checks user validations for user typed quantity.
* 	After check valditaion, it display error message using alert.
*
*/
function checkQTY () {
	var errno = "[ ERROR occured! ]\n\n";
	var flag = false;
	var userQ = document.getElementById("userQty").value;
	userQ = Math.ceil(userQ);
	var availableQ = document.getElementById("qty").innerHTML;


	if (userQ > availableQ) {
		errno = errno+ "Order quantity must be less than available quantity!\n";
		flag = true;
	}
	if (isNaN( userQ )) {
		errno = errno + "Order quantity is not a number!\n";
		flag = true;
	}
	if (userQ == 0) {
		errno = errno + "Order quantity cannot be zero!\n";
		flag = true;
	}

	if(flag){
		errno = errno  + "\n\"Please try again!\"\n";
		document.getElementById("order_qty").style.color = "red";
		document.getElementById("order_qty").style.fontWeight = "bold";
		document.getElementById("qty_img").style.visibility = "visible";

		alert(errno);

		document.getElementById("userQty").value = 0;
	}


}
/*
* 
* Name: checkSoldOut
* Param: none
* return: none
* 
* Description: 
* 	This function is for viewItem.php
* 	It checks available quantity of the item.
* 	If the quantity is equal to zero, then it displays soldout icon.
*
*/
function checkSoldOut () {

	var availableQ = document.getElementById("qty").innerHTML;

	if (availableQ == 0){
		document.getElementById("qty_img2").style.visibility = "visible";
		document.getElementById("submit").type = "hidden";
		document.getElementById("userQty").type = "hidden";
		
	}

}
/*
* 
* Name: updateQuantity
* Param: Ith = span tag id number
* 		user = user number for db
* 		item = item number for db
* return: none
* 
* Description: 
* 	This function is for shoppingCart.php
* 	It gets user input as new quantity for the item that user wants to change.
*	Using Ajax, it opens cartUpdate.php and change db info,
* 	and then refresh shoppingCart.php
*/
function updateQuantity (Ith,user,item) {
	var newQty = prompt(Ith+" Enter new quantity", "1");

	if (user.length == 0) { 
		alert("ERROR: Invalid user!");
		return;
	} else {
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				document.getElementById("eachQ"+Ith).innerHTML = "x "+newQty;
			}
		};

		xmlhttp.open("GET", "cartUpdate.php?q=" + newQty +"&u="+ user +"&i="+ item , true);
		xmlhttp.send();
	}
	window.location.replace('shoppingCart.php');
}

/*
* 
* Name: emptyCart
* Param: user = user number for db
* return: none
* 
* Description: 
* 	This function is for shoppingCart.php
*	Using Ajax, it opens cartEmpty.php and change db info,
* 	and then refresh shoppingCart.php
*/
function emptyCart (user) {

	if (user.length == 0) { 
		document.getElementById("hidden_emptyCart").innerHTML = "";
		return;
	} else {
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.open("GET", "cartEmpty.php?eu=" + user, true);
		xmlhttp.send();
	}

	window.location.replace('shoppingCart.php');
}

