<?php
/**
 * @name          : Joomla Social Pinboard
 * @version	  : 1.5.0
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

$enter_anyone_email=JText::_('COM_SOCIALPINBOARD_ENTER_ANYONE_EMAIL');
$invalid_email=JText::_('COM_SOCIALPINBOARD_INVALID_EMAIL_ADDRESS');
$invitefriend1=JText::_('COM_SOCIALPINBOARD_EMAIL_ONE');
$invitefriend2=JText::_('COM_SOCIALPINBOARD_EMAIL_TWO');
$invitefriend3=JText::_('COM_SOCIALPINBOARD_EMAIL_THREE');
$invitefriend4=JText::_('COM_SOCIALPINBOARD_EMAIL_FOUR');
$note=JText::_('COM_SOCIALPINBOARD_ADD_PERSONAL_NOTE');
?>
<script type="text/javascript">
var invitefriend1='<?php echo $invitefriend1; ?>';
var invitefriend2='<?php echo $invitefriend2; ?>';
var invitefriend3='<?php echo $invitefriend3; ?>';
var invitefriend4='<?php echo $invitefriend4; ?>';
var note='<?php echo $note; ?>';
</script>
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
        <a class="" href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=invitefriends&task=gmail', false) ?>">
            <img src="<?php echo JURI::base() . '/components/com_socialpinboard/images/gmail_icon.png'; ?>" width="12" height="12" alt=""/>
<?php echo JText::_('COM_SOCIALPINBOARD_GMAIL'); ?>
        </a>
        <a class="" href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=invitefriends&task=yahoo', false) ?>">
            <img src="<?php echo JURI::base() . '/components/com_socialpinboard/images/yahoo.png'; ?>" width="12" height="12" alt=""/>
<?php echo JText::_('COM_SOCIALPINBOARD_YAHOO'); ?>
        </a>
    </div>

    <ul>
        <h2><?php echo JText::_('COM_SOCIALPINBOARD_INVITE_FRIENDS'); ?></h2>
        <li>

            <input type="text" size="30" id="invitefriend1" style="color:#D7D7D7" value="<?php echo $invitefriend1; ?>" onFocus="onInviteFriends(this,'<?php echo $invitefriend1; ?>');" onBlur="onBlurEvent(this,'<?php echo $invitefriend1; ?>');" /><div class="invation_alert" id="error-to1"></div>

        </li>
        <li>

            <input type="text" size="30" id="invitefriend2" value="<?php echo $invitefriend2; ?>" style="color:#D7D7D7" onFocus="onInviteFriends(this,'<?php echo $invitefriend2; ?>')" onBlur="onBlurEvent(this,'<?php echo $invitefriend2; ?>');" /><div class="invation_alert" style="color:#008000" id="error-to2"></div>

        </li>
        <li>

            <input type="text" size="30" id="invitefriend3" value="<?php echo $invitefriend3; ?>"  style="color:#D7D7D7" onFocus="onInviteFriends(this, '<?php echo $invitefriend3; ?>')" onBlur="onBlurEvent(this,'<?php echo $invitefriend3; ?>');" /><div class="invation_alert" style="color:#008000" id="error-to3"></div>
        </li>
        <li>

            <input type="text" size="30" id="invitefriend4" value="<?php echo $invitefriend4; ?>" style="color:#D7D7D7" onFocus="onInviteFriends(this, '<?php echo $invitefriend4; ?>')" onBlur="onBlurEvent(this,'<?php echo $invitefriend4; ?>');" /><div class="invation_alert" style="color:#008000" id="error-to4"></div>
        </li>

        <li>
            <textarea id="content" rows="7" cols="59" value="<?php echo $note; ?>" style="color:#D7D7D7" onFocus="onInviteFriends(this, '<?php echo $note; ?>')" onBlur="onBlurEvent(this,'<?php echo $note; ?>');"><?php echo JText::_('COM_SOCIALPINBOARD_ADD_PERSONAL_NOTE'); ?></textarea>
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

        if(document.getElementById("invitefriend1").value==invitefriend1&& document.getElementById("invitefriend2").value==invitefriend2 && document.getElementById("invitefriend3").value==invitefriend3 && document.getElementById("invitefriend4").value==invitefriend4)
        {
            alert("<?php echo $enter_anyone_email; ?>");

            return false;
        }
        var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
        var address1 = document.getElementById("invitefriend1").value;
        var address2 = document.getElementById("invitefriend2").value;
        var address3 = document.getElementById("invitefriend3").value;
        var address4 = document.getElementById("invitefriend4").value;
        var cnt = 0;
        if(reg.test(address1) == false && address2!==invitefriend1) {
            cnt++;
        } else if (reg.test(address2) == false && address2!==invitefriend2) {
            cnt++;
        } else if(reg.test(address3) == false && address3!==invitefriend3) {
            cnt++;
        } else if(reg.test(address4) == false && address4!==invitefriend4) {
            cnt++;
        }
        if(cnt == 4){
              alert("<?php echo $invalid_email; ?>");
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
                if(document.getElementById("invitefriend1").value!=invitefriend1) {
                    document.getElementById("error-to1").innerHTML=xmlhttp.responseText;
                    document.getElementById("invitefriend1").value='';
                    document.getElementById("invitefriend1").focus();
                }
                if(document.getElementById("invitefriend2").value!=invitefriend2) {
                    document.getElementById("error-to2").innerHTML=xmlhttp.responseText;
                    document.getElementById("invitefriend2").value='';
                    document.getElementById("invitefriend2").focus();
                }
                if(document.getElementById("invitefriend3").value!=invitefriend3) {
                    document.getElementById("error-to3").innerHTML=xmlhttp.responseText;
                    document.getElementById("invitefriend3").value='';
                    document.getElementById("invitefriend3").focus();
                }
                if(document.getElementById("invitefriend4").value!=invitefriend4) {
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
