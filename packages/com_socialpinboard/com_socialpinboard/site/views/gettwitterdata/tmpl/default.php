<?php
/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component gettwitterdata view
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');
$userInfo = $this->userInfo;
$userName = $userInfo->screen_name;
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_socialpinboard/css/style.css');
$twitter_Id = $userInfo->id;
$profile_Image = $userInfo->profile_image_url;
?>
<script type="text/javascript">
    function Trim(str)
    {
        while (str.substring(0,1) == ' ') // check for white spaces from beginning
        {
            str = str.substring(1, str.length);
        }
        while (str.substring(str.length-1, str.length) == ' ') // check white space from end
        {
            str = str.substring(0,str.length-1);
        }
        return str;

    }
</script>

<div id="new_account_create">
    <h2><?php echo JText::_('COM_SOCIALPINBOARD_SOCIAL_PIN_BOARD_ACCOUNT') ?></h2>
    <form method="post"
          action="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=userlogin', false); ?>" enctype="multipart/form-data">
        <div class="clearfix">
            <fieldset class="input">
                <ul class="detail_page">
                    <li>
                        <label class="lable-txt" for="Username"><?php echo JText::_('COM_SOCIALPINBOARD_USERNAME') ?></label>
                        <label class="lable-txt" id="Username">
                            <input name="twittername"   id="twittername" type="text"  autocomplete=off class="login-inputbox" value="<?php echo $userName; ?>" alt="Password" size="18" onblur="return checkusername();" onkeyup="return checkusername();"/>
                            <div id="error-to-false"></div>
                            <div id="error-to-true"></div>
                        </label>
                    </li>

                    <li>
                        <label class="lable-txt" for="Email"><?php echo JText::_('COM_SOCIALPINBOARD_EMAIL') ?>
                        </label> 
                        <label class="lable-txt" id="Email">
                            <input name="twitterEmail" id="twitterEmail" autocomplete=off type="text"  class="login-inputbox" alt="twitterEmail" onblur="return checkEmail();" onkeyup="return checkEmail();" size="18" />
                            <div id="error-to-email-false"></div>
                            <div id="error-to-email-true"></div>
                        </label>
                    </li>
                    <li>
                        <label class="lable-txt" for="Password"><?php echo JText::_('COM_SOCIALPINBOARD_PASSWORD') ?>
                        </label>
                        <label class="lable-txt" id="Password">
                            <input name="twitterPassword" id="twitterPassword" type="password" class="login-inputbox" alt="Password" size="18" onblur="return checkPassword();" onkeyup="return checkPassword();"/>
                        <div id="password_error" name="password_error" style=" color: #FF1900; font-size: 13px; margin-top: 3px; font-family: arial; font-weight: bold; "></div>
                        </label>

                    </li>
                    <li>
                        <div id="confirm_password" style="display:block">

                            <label class="lable-txt" for="ConfirmPassword"><?php echo JText::_('COM_SOCIALPINBOARD_CONFIRM_PASSWORD') ?>
                            </label>
                            <label class="lable-txt" id="ConfirmPassword">
                                <input name="twitterConfPassword" id="twitterConfPassword" type="password"  class="login-inputbox" alt="Password" size="18" onblur="return checkconfPassword();" onkeyup="return checkconfPassword();"/>
                            <div id="conf_password_error" name="conf_password_error" style=" color: #FF1900; font-size: 13px; max-width: 350px; margin-top: 3px; font-family: arial; font-weight: bold; "></div>

                            </label>
                        </div>
                    </li>
                </ul>
                <div class="clear"></div>
                <div id="twitter_register" style="display:block">
                    <input type="submit" name="Register" value="<?php echo JTEXT::_('COM_SOCIALPINBOARD_REGISTERS'); ?>" id="Register" onclick="return Validate()" class="submit"/>
                </div>

                <div id="twitter_login" style="display:none">
                    <input type="button" name="login" value="<?php echo JTEXT::_('COM_SOCIALPINBOARD_LOGIN'); ?>" id="login"  onclick="return checkLogin(); " />
                </div>

                <input type="hidden" id="Twitter_id" name="Twitter_id" value="<?php echo $twitter_Id; ?>" />
                <input type="hidden" name="profile_image" value="<?php echo $profile_Image; ?>" />
                <input type="hidden" name="twitter" value="<?php echo JText::_('COM_SOCIALPINBOARD_TWITTER'); ?>" id="twitter"  />

            </fieldset>


        </div>
        <?php echo JHtml::_('form.token'); ?>
    </form>
</div>
<script type="text/javascript">
    function hasWhiteSpace(s) {
        return /\s/g.test(s);
    }
    function checkusername()
    {
        var username = document.getElementById('twittername').value;
        var ck_name = /^[A-Za-z0-9 ]{3,20}$/;
        if(username=='')
        {
            document.getElementById("error-to-false").innerHTML='<?php echo JText::_('Please enter the username') ?>';
            document.getElementById("error-to-true").innerHTML='';
            return false;
        }else if(hasWhiteSpace(username)) {
            document.getElementById("error-to-false").innerHTML = 'Please enter the username without space';
            document.getElementById("error-to-true").innerHTML='';
            return false;
        }else if(username.length >= 1 && username.length < 6) {
            document.getElementById("error-to-false").innerHTML = 'Username should have minimum 6 characters';
            document.getElementById("error-to-true").innerHTML='';
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
                if(username!="") {
                    if(xmlhttp.responseText=='Username available')
                    {
                        document.getElementById("error-to-true").innerHTML=xmlhttp.responseText;
                        document.getElementById("error-to-false").innerHTML='';
                    }else
                    {
                        document.getElementById("error-to-false").innerHTML=xmlhttp.responseText;
                        document.getElementById("error-to-true").innerHTML="";
                    }
                }
            
            
            }
        }
                            
        var url = "?option=com_socialpinboard&view=ajaxcontrol&tmpl=component&task=checkUserName&username="+username;
                            
        xmlhttp.open("GET",url,true);
        xmlhttp.send();
                           
                            
                           
     
    }
            
function checkconfPassword() {
var confirmpassword=document.getElementById('twitterConfPassword').value;
var passwordvalue=document.getElementById('twitterPassword').value;
 if(confirmpassword=='')
        {
            document.getElementById("conf_password_error").innerHTML="<?php echo JText::_('COM_SOCIALPINBOARD_PLEASE_ENTER_THE_CONFPASSWORD'); ?>";
             return false;

        }
        else if(confirmpassword.length < 6)
        {
            document.getElementById("conf_password_error").innerHTML="<?php echo JText::_('COM_SOCIALPINBOARD_CONF_PASSWORD_LENGTH_LESS_THAN_SIX'); ?>";
            return false;
        }
        else if(passwordvalue!=confirmpassword)
        {
            document.getElementById("conf_password_error").innerHTML="<?php echo JText::_('COM_SOCIALPINBOARD_PASSWORD_NOT_MATCH'); ?>";

            return false;
        }else{
            document.getElementById("conf_password_error").innerHTML="";
        }
}
        function checkPassword() {
      var passwordvalue=document.getElementById('twitterPassword').value;

        if(passwordvalue=='')
        {
            document.getElementById("password_error").innerHTML="<?php echo JText::_('COM_SOCIALPINBOARD_PLEASE_ENTER_THE_PASSWORD'); ?>";
            return false;

        }
        else if(passwordvalue.length < 6)
        {
            document.getElementById("password_error").innerHTML="<?php echo JText::_('COM_SOCIALPINBOARD_PASSWORD_LENGTH_LESS_THAN_SIX'); ?>";
            return false;
        }else{
            document.getElementById("password_error").innerHTML="";
        }

   }

    function checkEmail() {
                            
        var email = document.getElementById('twitterEmail');
        var emailcheck = document.getElementById('twitterEmail').value;
                    
        var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
        if(emailcheck=='')
        {
                
            document.getElementById("error-to-email-false").innerHTML="<?php echo JText::_('COM_SOCIALPINBOARD_PLEASE_ENTER_THE_EMAIL') ?>";
            document.getElementById("error-to-email-true").innerHTML="";
            document.getElementById("password_error").innerHTML="";
        }    else if(reg.test(emailcheck) == false) {
            document.getElementById("error-to-email-false").innerHTML="<?php echo JText::_('COM_SOCIALPINBOARD_INVALID_EMAIL_ADDRESS') ?>";
            document.getElementById("error-to-email-true").innerHTML="";
            document.getElementById("password_error").innerHTML="";
            return false;
        }else if(reg.test(emailcheck) == true) {
            document.getElementById("error-to-email-false").innerHTML="";
            document.getElementById("error-to-email-true").innerHTML="";
            document.getElementById("password_error").innerHTML="";
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
                                
                    if(xmlhttp.responseText=='Email available')
                    {
                        document.getElementById("error-to-email-true").innerHTML=xmlhttp.responseText;
                        document.getElementById("confirm_password").style.display="block";
                        document.getElementById("twitter_register").style.display="block";
                        document.getElementById("twitter_login").style.display="none";
                        document.getElementById("error-to-email-false").innerHTML="";
                        document.getElementById("password_error").innerHTML="";

                    }else if(xmlhttp.responseText=='Email Already Taken')
                    {
                        document.getElementById("error-to-email-false").innerHTML='This is your Email? Please provide the socialpinboard password to login with twitter';
                        document.getElementById("confirm_password").style.display="none";
                        document.getElementById("twitter_register").style.display="none";
                        document.getElementById("twitter_login").style.display="block";
                        document.getElementById("error-to-email-true").innerHTML="";
                        document.getElementById("password_error").innerHTML="";
                    }
                                        
                }
            
            
            }
        }
        var url = "?option=com_socialpinboard&view=ajaxcontrol&tmpl=component&task=checkEmail&email="+emailcheck;
        xmlhttp.open("GET",url,true);
        xmlhttp.send();
                           
                            
    }
    function Validate()
    {
        var twittername=document.getElementById('twittername').value;
 
        var passwordvalue=document.getElementById('twitterPassword').value;
        var twitterEmail=document.getElementById('twitterEmail').value;
        var confirmpassword=document.getElementById('twitterConfPassword').value;
        var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
        var email=Trim(twitterEmail)
        total=passwordvalue.length;
        if(twittername=='')
        {
            document.getElementById("error-to-false").innerHTML='<?php echo JText::_('Please enter the username') ?>';
            document.getElementById("error-to-true").innerHTML='';
            return false;
          
        }
        if(twitterEmail=='')
        {
            document.getElementById("error-to-email-false").innerHTML="<?php echo JText::_('COM_SOCIALPINBOARD_PLEASE_ENTER_THE_EMAIL') ?>";
            document.getElementById("error-to-email-true").innerHTML="";
            document.getElementById("password_error").innerHTML="";
            return false;
          
        }else if(reg.test(email) == false) {
            document.getElementById("error-to-email-false").innerHTML="<?php echo JText::_('COM_SOCIALPINBOARD_INVALID_EMAIL_ADDRESS') ?>";
            document.getElementById("error-to-email-true").innerHTML="";
            document.getElementById("password_error").innerHTML="";
            return false;
        }
        else if(passwordvalue=='')
        {
            document.getElementById("password_error").innerHTML="<?php echo JText::_('COM_SOCIALPINBOARD_PLEASE_ENTER_THE_PASSWORD'); ?>";
            return false;
          
        }
        else if(confirmpassword=='')
        {
            document.getElementById("password_error").innerHTML="<?php echo JText::_('COM_SOCIALPINBOARD_PLEASE_ENTER_THE_CONFPASSWORD'); ?>";
            return false;

        }
        else if(total < 6)
        {
            $("#passwordfield").append("<?php echo JText::_('COM_SOCIALPINBOARD_PASSWORD_LENGTH_LESS_THAN_SIX'); ?>");
            return false;
        }
        else if(passwordvalue!=confirmpassword)
        {
            document.getElementById("password_error").innerHTML="<?php echo JText::_('COM_SOCIALPINBOARD_PASSWORD_NOT_MATCH'); ?>";

            return false;
        }
    }

    window.onload = function () {
        checkusername();
    }

    function checkLogin()
    {
   
    
        var tpassword=scr('#twitterPassword').val();
        var email = scr('#twitterEmail').val();
        var twitter_id=scr('#Twitter_id').val();
        var username = scr('#twittername').val();
     
        scr.ajax({
            type: "POST",
            url: "?option=com_socialpinboard&view=ajaxcontrol&tmpl=component&task=checkpassword",
            data: {'email':email, 'password':tpassword,'twitter_id':twitter_id,'username':username},
            cache: false,
            success: function(message){
                if(message!='success')
                {
         
                    scr('#password_error').text(message);
                    return false;
                }else
                {
                    window.location.replace('<?php echo JRoute::_('index.php?option=com_socialpinboard&view=home', TRUE); ?>');
                    return true;
                }
    
            }
    
        });

    }
    

</script>