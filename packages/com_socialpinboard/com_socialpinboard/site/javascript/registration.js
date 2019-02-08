function valPasswordlength() {
    var password = document.getElementById('field3').value;
    if(password=='')
    {
        document.getElementById('email_help3').style.display="block";
        document.getElementById('password_error').style.display="none";
        document.getElementById('error-to1').innerTML='';
    }else  if(password.length >= 5) {
        document.getElementById('password_error').style.display="none";
        document.getElementById('email_help3').style.display="none";
        return true;
    }else{
        document.getElementById('password_error').style.display="block";
        document.getElementById('email_help3').style.display="none";
        return false;
    }
}

function valConfirmPassword() {
    var confirm_password = document.getElementById('field6').value;
    if(confirm_password=='')
    {
        document.getElementById('email_help6').style.display="block";
        document.getElementById('confirm_password_error').style.display="none";
        document.getElementById('error-to1').innerTML='';
    }else   if(confirm_password.length >= 5) {
                        
        document.getElementById('confirm_password_error').style.display="none";
        document.getElementById('email_help6').style.display="none";
        document.getElementById('not_match').style.display="none";
        return true;
    }else{
        document.getElementById('confirm_password_error').style.display="block";
        document.getElementById('email_help6').style.display="none";
        document.getElementById('not_match').style.display="none";
        return false;
    }
}
function valFirstName() {
                    
    var first_name = document.getElementById('field2').value;
    if(first_name=='')
    {
        document.getElementById('email_help2').style.display="block";
        document.getElementById('first_error-to-false').innerHTML='';
        document.getElementById('first_name_space_error').style.display="none";
        document.getElementById('error-to1').innerTML='';

        return false;
    }else if(hasWhiteSpace(first_name)) {
        document.getElementById('first_name_space_error').style.display="block";
        document.getElementById('first_name_error').style.display="none";
        document.getElementById('email_help2').style.display='none';
        return false;
    }
    else if(first_name.length < 30) {
        document.getElementById('first_name_space_error').style.display="none";
        document.getElementById('first_name_error').style.display="none";
        document.getElementById('email_help2').style.display='none';
        return false;
    }else{
        document.getElementById('first_name_error').style.display="block";
        document.getElementById('email_help2').style.display='none';
        document.getElementById('first_name_space_error').style.display="none";
        return false;
    }
}
                
function valLastName() {
    var last_name = document.getElementById('field5').value;
    if(last_name=='')
    {
        document.getElementById('email_help5').style.display="block";
        document.getElementById('last_error-to-false').innerTML='';
        document.getElementById('last_name_space_error').style.display="none";
        document.getElementById('error-to1').innerTML='';
        return false;
    }else if(hasWhiteSpace(last_name)) {
        document.getElementById('last_name_space_error').style.display="block";
        document.getElementById('last_name_error').style.display="none";
        document.getElementById('email_help5').style.display="none";
        document.getElementById('error-to1').style.display='none';
        return false;
    } else if(last_name.length<30) {
                  
        document.getElementById('last_name_error').style.display="none";
        document.getElementById('last_name_space_error').style.display="none";
        document.getElementById('email_help5').style.display='none';
                    
        return false;
    }else{
        document.getElementById('last_name_error').style.display="block";
        document.getElementById('last_name_space_error').style.display="none";
        document.getElementById('email_help5').style.display='none';
        document.getElementById('error-to1').innerTML='';
        return false;
    }
}
function valPassword() {
                        
    var confirm_password=document.getElementById('field3').value;
    var new_password=document.getElementById('field6').value;
    if(new_password != confirm_password)
    {
        document.getElementById('not_match').style.display="block";
        document.getElementById('email_help6').style.display="none";
        document.getElementById('confirm_password_error').innerHTML='';
                    
        return false;
                               
    }else{
        document.getElementById('not_match').style.display="none";
                                
        return true;
                               
    }
}
function checkField() {
    var emailcheck = document.getElementById('field1').value;
    var password = document.getElementById('field3').value;
    var first_name = document.getElementById('field2').value;
    var last_name = document.getElementById('field5').value;
    var username=document.getElementById('field4').value;
    var new_password=document.getElementById('field6').value;
    var username_avail_check = document.getElementById("error-to-false").innerHTML;
    var ck_name = /^[A-Za-z0-9 ]{3,20}$/;
    var flag=0;
    var i;
    for(i=1;i<=6;i++)
    {
        var field=document.getElementById('field'+i).value;
        if(field=='')
        {
                                   
            document.getElementById('email_help'+i).style.display="block";
                          
                           
        }
        else
        {
            flag++;
            document.getElementById('email_help'+i).style.display="none";
                   
        }
    }
    if(flag==6)
    {
        if(username.length >= 1 && username.length < 6) {
            document.getElementById("error-to-false").innerHTML = min_username_charecter;
            document.getElementById('error-to1').innerHTML='';
            document.getElementById('email_help4').style.display='none';
            return false;
        }
        else if (!ck_name.test(username)) {
            document.getElementById("error-to-false").innerHTML = username_wo_special_charecter;
            document.getElementById('error-to1').innerHTML='';
            document.getElementById('email_help4').style.display='none';
            return false;
        }
        else if (hasWhiteSpace(first_name)) {
            document.getElementById("first_error-to-false").innerHTML = firstname_wo_space;
            document.getElementById('error-to1').innerHTML='';
            document.getElementById('email_help4').style.display='none';
            return false;
        }
        else if (hasWhiteSpace(last_name)) {
            document.getElementById("last_error-to-false").innerHTML = lastname_wo_space;
            document.getElementById('error-to1').innerHTML='';
            document.getElementById('email_help4').style.display='none';
            return false;
        }
        else if (username_avail_check==username_not_available) {
            document.getElementById('field4').focus();
            return false;
        }
        else if(new_password!=password)
        {
                            
            document.getElementById('not_match').style.display="block";
            document.getElementById('email_help6').style.display="none";
            document.getElementById('confirm_password_error').innerHTML='';
            return false;
        }
                
        var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
        if(emailcheck=='')
        {
            document.getElementById('email_help1').style.display="block";
            document.getElementById('error-to1').innerTML='';
        } else if(reg.test(emailcheck) == false) {
            document.getElementById("error-email-false").innerHTML=enter_valid_email;
            document.getElementById('email_help1').innerHTML='';
            return false;
        }else{
            
            return true;
        }
    }
    else
    {
        return false;
    }
                     
}      
            
function hasWhiteSpace(s) {
    return /\s/g.test(s);
}
function checkusername()
{
    var username = document.getElementById('field4').value;
    var ck_name = /^[A-Za-z0-9 ]{3,20}$/;
    if(username=='')
    {
        document.getElementById('error-to1').innerHTML='';
        document.getElementById('email_help4').style.display='block';
        document.getElementById("error-to-false").innerHTML='';
    }else if(hasWhiteSpace(username)) {
        document.getElementById("error-to-false").innerHTML = username_wo_space;
        document.getElementById('error-to1').innerHTML='';
        document.getElementById('email_help4').style.display='none';
        return false;
    }else if(username.length >= 1 && username.length < 6) {
        document.getElementById("error-to-false").innerHTML = min_username_charecter;
        document.getElementById('error-to1').innerHTML='';
        document.getElementById('email_help4').style.display='none';
        return false;
    }else if (!ck_name.test(username)) {
        document.getElementById("error-to-false").innerHTML = username_wo_special_charecter;
        document.getElementById('error-to1').innerHTML='';
        document.getElementById('email_help4').style.display='none';
        return false;
    }
                    
    var xmlhttp;
    if (window.XMLHttpRequest)
    {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    }
    else
    {// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange=function()
    {
        if (xmlhttp.readyState==4 && xmlhttp.status==200)
        {
            if(document.getElementById("field4").value!="") {
                if(xmlhttp.responseText==username_available)
                {
                    document.getElementById("error-to1").innerHTML=xmlhttp.responseText;
                    document.getElementById("email_help4").innerHTML='';
                    document.getElementById("error-to-false").innerHTML='';
                }else
                {
                    document.getElementById("error-to-false").innerHTML=xmlhttp.responseText;
                    document.getElementById("email_help4").innerHTML='';
                    document.getElementById("error-to1").innerHTML='';

                }
            }
        }
    }
                            
    var url = "?option=com_socialpinboard&view=ajaxcontrol&tmpl=component&task=checkUserName&username="+username;
                            
    xmlhttp.open("GET",url,true);
    xmlhttp.send();
                           
                            
                           
     
}        
            
function checkEmail() {
                            
    var email = document.getElementById('field1');
    var emailcheck = document.getElementById('field1').value;
                
    var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
    if(emailcheck=='')
    {
                        document.getElementById('email_help1').style.display="none";
        //                document.getElementById("error-to-false").innerHTML='';
        document.getElementById("error-to-email").innerHTML='';
        document.getElementById('error-to1').innerTML='';
        document.getElementById("error-email-false").innerHTML=enter_email;
    } else if(reg.test(emailcheck) == false) {
        document.getElementById("error-email-false").innerHTML=enter_valid_email;
        document.getElementById('email_help1').innerHTML='';
        document.getElementById('error-to-email').innerHTML='';
        return false;
    }
    var xmlhttp;
    if (window.XMLHttpRequest)
    {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    }
    else
    {// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
                    
                                   
    xmlhttp.onreadystatechange=function()
    {
        if (xmlhttp.readyState==4 && xmlhttp.status==200)
        {
            if(emailcheck!="") {
                           
                if(xmlhttp.responseText==email_blocked)
                {
                    document.getElementById("error-email-false").innerHTML=blocked_email;
                    document.getElementById("error-to-email").innerHTML='';
                    document.getElementById("email_help1").innerHTML='';
                }
                else if(xmlhttp.responseText==email_available)
                {
                    document.getElementById("error-to-email").innerHTML=xmlhttp.responseText;
                    document.getElementById("error-email-false").innerHTML='';
                    document.getElementById("email_help1").innerHTML='';
                }else
                {
                    document.getElementById("error-email-false").innerHTML=xmlhttp.responseText;
                    document.getElementById("email_help1").innerHTML='';
                    document.getElementById("error-to-email").innerHTML='';
                }
                                        
            }
            
            
        }
    }
    var url = "?option=com_socialpinboard&view=ajaxcontrol&tmpl=component&task=checkEmail&email="+emailcheck;
    xmlhttp.open("GET",url,true);
    xmlhttp.send();
                           
                            
}