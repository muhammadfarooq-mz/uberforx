var specialKeys = new Array();
specialKeys.push(8); //Backspace
var error_color = "#FF0000";

function ValidateEmail(i)
{
    var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    var mailvalue = document.getElementById('email_check' + i);
    if (mailvalue.value.match(mailformat))
    {
        var eml_err = document.getElementById("no_email_error" + i);
        eml_err.style.color = error_color;
        eml_err.innerHTML = "";
        eml_err.style.display = "none";
        return true;
    } else {
        var eml_err = document.getElementById("no_email_error" + i);
        eml_err.style.color = error_color;
        eml_err.innerHTML = "Not a valid email.<br/>";
        eml_err.style.display = "inline";
        /*var mailvalue2 = document.getElementById('email_check'+i);*/
        setTimeout(function () {
            document.getElementById('email_check' + i).focus();
        }, 100);
        return false;
    }
}
function Ismobile(e, i) {
    var keyCode = e.which ? e.which : e.keyCode
    var ret = ((keyCode == 9) || (keyCode == 43) || (keyCode >= 48 && keyCode <= 57) || specialKeys.indexOf(keyCode) != -1);
    var err_div = document.getElementById("no_mobile_error" + i);
    err_div.style.color = error_color;
    err_div.innerHTML = "Not a valid mobile number.<br/>";
    err_div.style.display = ret ? "none" : "inline";
    return ret;
}
function IsNumeric(e, i) {
    var keyCode = e.which ? e.which : e.keyCode
    var ret = ((keyCode == 9) || (keyCode >= 48 && keyCode <= 57) || specialKeys.indexOf(keyCode) != -1);
    var err_div = document.getElementById("no_number_error" + i);
    err_div.innerHTML = "Not a valid number.<br/>";
    err_div.style.color = error_color;
    err_div.style.display = ret ? "none" : "inline";
    return ret;
}
function Isamount(e, i) {
    var keyCode = e.which ? e.which : e.keyCode
    var ret = ((keyCode == 9) || (keyCode == 46) || (keyCode >= 48 && keyCode <= 57) || specialKeys.indexOf(keyCode) != -1);
    var err_div = document.getElementById("no_amount_error" + i);
    err_div.innerHTML = "Not a valid amount.<br/>";
    err_div.style.color = error_color;
    err_div.style.display = ret ? "none" : "inline";
    return ret;
}
function Isnumamount(e, i) {
    var keyCode = e.which ? e.which : e.keyCode
    var ret = ((keyCode == 9) || (keyCode == 46) || (keyCode >= 48 && keyCode <= 57) || specialKeys.indexOf(keyCode) != -1);
    var err_div = document.getElementById("no_numamount_error" + i);
    err_div.innerHTML = "Not a valid number.<br/>";
    err_div.style.color = error_color;
    err_div.style.display = ret ? "none" : "inline";
    return ret;
}