<?php
/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component invitefriends view
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_socialpinboard/css/facebox.css');
$document->addStyleSheet('components/com_socialpinboard/css/invite_frnds.css');
$document->addStyleSheet('components/com_socialpinboard/css/edit.css');
$document->addStyleSheet('components/com_socialpinboard/css/reset.css');
$document->addStyleSheet('components/com_socialpinboard/css/pinboard.css');
$document->addScript('https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js');
$document->addScript('components/com_socialpinboard/javascript/jquery.ui.core.js');
$document->addScript('components/com_socialpinboard/javascript/chrome.js');
$document->addScript('components/com_socialpinboard/javascript/facebox.js');
$document->addScript('components/com_socialpinboard/javascript/scroll/jquery.isotope.min.js');
$document->addScript('components/com_socialpinboard/javascript/scroll/jquery.infinitescroll.min.js');
$app = JFactory::getApplication();
$config = JFactory::getConfig();
$templateparams = $app->getTemplate(true)->params; // get the tempalte
$sitetitle = $templateparams->get('sitetitle');
if (isset($sitetitle)) {
    $document->setDescription($sitetitle);
    $document->setTitle($sitetitle);
} else {
    $sitetitle = $config->get('sitename');
    $document->setDescription($sitetitle);
    $document->setTitle($sitetitle);
}
?>
<div class="invite_frnds" >
    <div class="about_left">
        <a class="social_invite" href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=invitefriends', false) ?>">
            <img src="<?php echo JURI::base() . '/components/com_socialpinboard/images/mail.png'; ?>" width="12" height="12" alt=""/>
<?php echo JText::_('COM_SOCIALPINBOARD_EMAIL'); ?>
        </a>
        <a class="" href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=invitefriends&task=facebook', false) ?>">
            <img src="<?php echo JURI::base() . '/components/com_socialpinboard/images/fb.png'; ?>" width="12" height="12" alt=""/>
<?php echo JText::_('COM_SOCIALPINBOARD_FACEBOOK'); ?>
        </a>
        <a class="gmail_invite" href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=invitefriends&task=gmail', false) ?>">
            <img src="<?php echo JURI::base() . '/components/com_socialpinboard/images/gmail_icon.png'; ?>" width="12" height="12" alt=""/>
<?php echo JText::_('COM_SOCIALPINBOARD_GMAIL'); ?>
        </a>
        <a class="yahoo_invite" href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=invitefriends&task=yahoo', false) ?>">
            <img src="<?php echo JURI::base() . '/components/com_socialpinboard/images/yahoo.png'; ?>" width="12" height="12" alt=""/>
<?php echo JText::_('COM_SOCIALPINBOARD_YAHOO'); ?>
        </a>
    </div>

    <ul>
        <h2><?php echo JText::_('COM_SOCIALPINBOARD_INVITE_FRIENDS'); ?></h2>
        <li>

            <input type="text" size="30" id="invitefriend1" style="color:#D7D7D7" value="<?php echo JText::_('COM_SOCIALPINBOARD_EMAIL_ONE'); ?>" onFocus="onInviteFriends(this,'Email Address 1');" onBlur="onBlurEvent(this,'<?php echo JText::_('COM_SOCIALPINBOARD_EMAIL_ONE'); ?>');" /><div class="invation_alert" id="error-to1"></div>

        </li>
        <li>

            <input type="text" size="30" id="invitefriend2" value="<?php echo JText::_('COM_SOCIALPINBOARD_EMAIL_TWO'); ?>" style="color:#D7D7D7" onFocus="onInviteFriends(this,'Email Address 2')" onBlur="onBlurEvent(this,'Email Address 2');" /><div class="invation_alert" style="color:#008000" id="error-to2"></div>

        </li>
        <li>

            <input type="text" size="30" id="invitefriend3" value="<?php echo JText::_('COM_SOCIALPINBOARD_EMAIL_THREE'); ?>"  style="color:#D7D7D7" onFocus="onInviteFriends(this, 'Email Address 3')" onBlur="onBlurEvent(this,'Email Address 3');" /><div class="invation_alert" style="color:#008000" id="error-to3"></div>
        </li>
        <li>

            <input type="text" size="30" id="invitefriend4" value="<?php echo JText::_('COM_SOCIALPINBOARD_EMAIL_FOUR'); ?>" style="color:#D7D7D7" onFocus="onInviteFriends(this, 'Email Address 4')" onBlur="onBlurEvent(this,'Email Address 4');" /><div class="invation_alert" style="color:#008000" id="error-to4"></div>
        </li>

        <li>
            <textarea id="content" rows="7" cols="59" value="<?php echo JText::_('COM_SOCIALPINBOARD_ADD_PERSONAL_NOTE'); ?>" style="color:#D7D7D7" onFocus="onInviteFriends(this, 'Add Personal Note (Optional)')" onBlur="onBlurEvent(this,'Add Personal Note (Optional)');"><?php echo JText::_('COM_SOCIALPINBOARD_ADD_PERSONAL_NOTE'); ?></textarea>
        </li>
        <li>
            <input type="submit" id="submit-button" value="<?php echo JText::_('COM_SOCIALPINBOARD_SEND_INVITES'); ?>" onclick="return sendemail()"/>
        </li>
    </ul>
    <div id="output"></div>

</div>

<script type="text/javascript">
    function sendemail()
    {
   
        if(document.getElementById("invitefriend1").value=="Email Address 1" && document.getElementById("invitefriend2").value=="Email Address 2" && document.getElementById("invitefriend3").value=="Email Address 3" && document.getElementById("invitefriend4").value=="Email Address 4")
        {
            alert("Please enter any one  email address");
       
            return false;
        }
        var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
        var address1 = document.getElementById("invitefriend1").value;
        if(reg.test(address1) == false && address2!=="Email Address 1") {
            alert('Invalid Email Address');
            return false;
        }
        var address2 = document.getElementById("invitefriend2").value;
        if(reg.test(address2) == false && address2!=="Email Address 2") {
            alert('Invalid Email Address');
            return false;
        }
        
        
        var address3 = document.getElementById("invitefriend3").value;
        if(reg.test(address3) == false && address3!=="Email Address 3") {
            alert('Invalid Email Address');
            return false;
        }
        
        var address4 = document.getElementById("invitefriend4").value;
        if(reg.test(address4) == false && address4!=="Email Address 4") {
            alert('Invalid Email Address');
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
                if(document.getElementById("invitefriend1").value!="Email Address 1") {
                    document.getElementById("error-to1").innerHTML=xmlhttp.responseText;
                    document.getElementById("invitefriend1").value='';
                    document.getElementById("invitefriend1").focus();
                }
                if(document.getElementById("invitefriend2").value!="Email Address 2") {
                    document.getElementById("error-to2").innerHTML=xmlhttp.responseText;
                    document.getElementById("invitefriend2").value='';
                    document.getElementById("invitefriend2").focus();
                }
                if(document.getElementById("invitefriend3").value!="Email Address 3") {
                    document.getElementById("error-to3").innerHTML=xmlhttp.responseText;
                    document.getElementById("invitefriend3").value='';
                    document.getElementById("invitefriend3").focus();
                }
                if(document.getElementById("invitefriend4").value!="Email Address 4") {
                    document.getElementById("error-to4").innerHTML=xmlhttp.responseText;
                    document.getElementById("invitefriend4").value='';
                    document.getElementById("invitefriend4").focus();
                }
                document.getElementById("content").value='';
            }
        }
        xmlhttp.open("POST","<?php echo JURI::base(); ?>index.php?option=com_socialpinboard&view=mailfriends&tmpl=component",true);
        xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        var emailto1 = document.getElementById('invitefriend1').value;
        var emailto2 = document.getElementById('invitefriend2').value;
        var emailto3 = document.getElementById('invitefriend3').value;
        var emailto4 = document.getElementById('invitefriend4').value;
        var content = document.getElementById('content').value;
        xmlhttp.send('emailto1=' + emailto1+'&emailto2=' + emailto2+'&emailto3=' + emailto3+'&emailto4=' + emailto4+'&content=' + content);
     
        
    }
</script>
