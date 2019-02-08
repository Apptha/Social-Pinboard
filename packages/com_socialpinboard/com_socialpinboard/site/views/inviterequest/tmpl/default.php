<?php
/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component inviterequest view
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');
$document = JFactory::getDocument();
$document->addScript('https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js');
$document->addScript('components/com_socialpinboard/javascript/loginvalidation.js');
$document->addStyleSheet('components/com_socialpinboard/css/landing.css');

$app = JFactory::getApplication();
$templateparams = $app->getTemplate(true)->params; // get the tempalte parameters
$logo = $templateparams->get('logo'); //get the logo


$user = JFactory::getUser();
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
<div id="wrapper">


    <div class="landing-content">

        <!-- logo -->
        <div class="logo-header">
            <a href="index.php">
                <?php
                if ($logo != null) {
                ?>

                    <img src="<?php echo $this->baseurl ?>/<?php echo htmlspecialchars($logo); ?>" alt="<?php echo htmlspecialchars($templateparams->get('sitetitle')); ?>"   />
<?php
                } else { {
?>
                        <img src="<?php echo JURI::base(); ?>/templates/socialpinboard/images/logo-large.png" alt="Socialpinboard" />
                <?php
                    }
                }
                ?>
            </a>
        </div>

        <!-- Email -->

        <div class="invite_email" id="invite_email">
            <h2><?php echo JText::_('COM_SOCIALPINBOARD_SIGN_UP_REQUEST'); ?></h2>
            <span class="home_page_login"><?php echo JText::_('COM_SOCIALPINBOARD_OR'); ?> <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=people', false); ?>"><?php echo JText::_('COM_SOCIALPINBOARD_LOGIN'); ?></a> <?php echo JText::_('COM_SOCIALPINBOARD_TO_YOUR_ACCOUNT'); ?></span>
            <div class="request_box">

                <input type="text"  value="<?php echo JTEXT::_('COM_SOCIALPINBOARD_EMAIL_ADDRESS') ?>" onFocus="onFocusEvent(this,'<?php echo JTEXT::_('COM_SOCIALPINBOARD_EMAIL_ADDRESS') ?>');" onBlur="onBlurEvent(this,'<?php echo JTEXT::_('COM_SOCIALPINBOARD_EMAIL_ADDRESS') ?>');" name="inviterequest" id="inviterequest" autocomplete="off" />
                <input class="req_invite-bttn" type="button" title="" value="Request Invitation" onclick="return sendemail()">
                <div style="display: none" id="email_help1"> <span style="color:#FF1900;"   class="help_text"><?php echo JText::_('COM_SOCIALPINBOARD_PLEASE_ENTER_EMAIL_ADDRESS'); ?></span></div>
                <div style="display: none" id="email_help2"> <span style="color:#FF1900;"   class="help_text"><?php echo JTEXT::_('COM_SOCIALPINBOARD_INVALID_EMAIL_ADDRESS'); ?></span></div>

            </div>
        </div>
    </div>


    <!-- landing-slider -->
    <div class="landing-slider">

        <span><?php echo JText::_('COM_SOCIALPINBOARD_ORGANIZE'); ?>
            <font><?php echo JText::_('COM_SOCIALPINBOARD_SHARE_LOVE'); ?></font></span>
        <ul>
            <!-- Save Your Recipes! -->
            <li>
                <h4><?php echo JText::_('COM_SOCIALPINBOARD_SAVE_YOUR_RECIPIES'); ?></h4>
                <img src="<?php echo JURI::base(); ?>/components/com_socialpinboard/images/slide-image1.png" alt="Save Your Recipes!" width="405" height="286"  />
            </li>

            <!-- Plan a Wedding! -->
            <li>
                <h4><?php echo JText::_('COM_SOCIALPINBOARD_PLAN_YOUR_WEDDING'); ?></h4>
                <img src="<?php echo JURI::base(); ?>/components/com_socialpinboard/images/slide-image2.png" alt="Plan a Wedding!" width="405" height="286"  />
            </li>

            <!-- Redecorate your Home!-->
            <li>
                <h4><?php echo JText::_('COM_SOCIALPINBOARD_REDECORATE'); ?></h4>
                <img src="<?php echo JURI::base(); ?>/components/com_socialpinboard/images/slide-image3.png" alt="Redecorate your Home!" width="405" height="286"  />
            </li>
        </ul>
    </div>









</div>
<script type="text/javascript">
    function sendemail() {
        var email_address= document.getElementById("inviterequest").value;
        if(email_address == '' || email_address=="<?php echo JTEXT::_('COM_SOCIALPINBOARD_EMAIL_ADDRESS') ?>") {
            document.getElementById("email_help1").style.display="block";
            document.getElementById("email_help2").style.display="none";
            document.getElementById("inviterequest").focus();
            return false;
        }
        var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
        var address = Trim(email_address);
        if(reg.test(address) == false) {
            document.getElementById("email_help2").style.display="block";
            document.getElementById("email_help1").style.display="none";
            document.getElementById("inviterequest").focus();
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
                document.getElementById("invite_email").innerHTML=xmlhttp.responseText;
            }
        }
        xmlhttp.open("POST","index.php?option=com_socialpinboard&view=requestmail&tmpl=component",true);
        xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        var email = email_address;
        xmlhttp.send('email=' + email);
    }
</script>
<div id="output"></div>
<script type="text/javascript">
    $(document).ready(function(){
        $('#inviterequest').focus();
        $("#inviterequest").bind("keydown", function(e) {
            if (e.keyCode == 13) { // enter key
                sendemail();
                return false
            }
        });
    });
</script>
