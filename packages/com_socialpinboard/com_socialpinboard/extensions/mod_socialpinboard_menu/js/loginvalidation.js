function changepass()
{
    var passwordfield;
    passwordfield='<input class="required validate-password" type="password" id="password" name="password" size="40" value="" onblur="passvalidate(); changefield();" style="color:#000000;" />';
    document.getElementById('passwordfield').innerHTML=passwordfield;
    document.getElementById('password').focus();
}

function changefield()
{
    if(document.getElementById('password').value=='')
    {
        var changepassfield='<input type="text" name="pass" id="pass" value="Password" onfocus="changepass();" />';
        document.getElementById('passwordfield').innerHTML=changepassfield;
    }
}

function changeconfirmpass()
{
    var confirmfield;
    confirmfield='<input class="required validate-passverify" type="password" id="password2" equalTo="#password" name="password2" size="40" value="" onblur="confirmvalidate(); changeconfirm();" style="color:#000000;" />';
    document.getElementById('confirmfield').innerHTML=confirmfield;
    document.getElementById('password2').focus();
}

function changeconfirm()
{
    if(document.getElementById('password2').value=='')
    {
        var changeconfirmfield='<input type="text" name="confirmpassword" id="confirmpassword" value="Confirm Password" onfocus="changeconfirmpass();" />';
        document.getElementById('confirmfield').innerHTML=changeconfirmfield;
    }
}

function passvalidate()
{
    passwordvalue=document.getElementById('password').value;

    if(document.getElementById('password').value=='Password')
    {
        $("#passwordfield").append("Enter Password");
        return false;
    }

}


function onFocusEvent(input,txtValue)
{
    if(input.value==txtValue)
    {
        if(input.value=='Password')input.type='password';
        input.value='' ;
        input.style.color='#000000';
        input.style.fontWeight='normal';
    }
}
//onfocus for the menu description
function onFocusMenu(input,txtValue)
{
    if(input.value==txtValue)
    {
        input.value='' ;
        input.style.color='#000000';
        input.style.fontWeight='normal';
    }
}
function onInviteFriends(input,txtValue)
{
    if(input.value==txtValue)
    {
        if(input.value==invitefriend1)
        {
        input.value='' ;
        input.style.color='#000000';
        input.style.fontWeight='normal';
        }
        else if(input.value==invitefriend2)
        {
        input.value='' ;
        input.style.color='#000000';
        input.style.fontWeight='normal';
    }
    else if(input.value==invitefriend3)
        {
        input.value='' ;
        input.style.color='#000000';
        input.style.fontWeight='normal';
    }
        else if(input.value==invitefriend4)
        {
        input.value='' ;
        input.style.color='#000000';
        input.style.fontWeight='normal';
    }else if(input.value==note)
        {
        input.value='' ;
        input.style.color='#000000';
        input.style.fontWeight='normal';
    }
    }
}
function onBlurEvent(input,txtValue)
{
    if(input.value=='')
    {
        input.value=txtValue;
        input.style.color='#C9C8C8';
        input.style.fontWeight='normal';
    }
}

