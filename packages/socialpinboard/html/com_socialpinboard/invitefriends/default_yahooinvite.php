<?php
/**
 * @name          : Joomla Social Pinboard
 * @version	  : 1.5.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component yahoo invitefriends view
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');

require JPATH_COMPONENT . '/lib/Yahoo.inc';

//for converting xml to array
function XmltoArray($xml) {
    $array = json_decode(json_encode($xml), TRUE);

    foreach (array_slice($array, 0) as $key => $value) {
        if (empty($value))
            $array[$key] = NULL;
        elseif (is_array($value))
            $array[$key] = XmltoArray($value);
    }

    return $array;
}

// debug settings
//error_reporting(E_ALL | E_NOTICE); # do not show notices as library is php4 compatable
//ini_set('display_errors', true);
YahooLogger::setDebug(true);
YahooLogger::setDebugDestination('LOG');

// use memcache to store oauth credentials via php native sessions
//ini_set('session.save_handler', 'files');
//session_save_path('/tmp/');
//session_start();
$yahooinvite = $yahoo_consumer_key = $yahoo_consumer_secret_key = $yahoo_oauth_domain = $yahoo_app_id = '';
// Make sure you obtain application keys before continuing by visiting:
// https://developer.yahoo.com/dashboard/createKey.html
$yahooinvite = $this->getFacebookSettings;
if (!empty($yahooinvite->setting_yahooconsumerkey))
    $yahoo_consumer_key = $yahooinvite->setting_yahooconsumerkey;
if (!empty($yahooinvite->setting_yahooconsumersecretkey))
    $yahoo_consumer_secret_key = $yahooinvite->setting_yahooconsumersecretkey;
if (!empty($yahooinvite->setting_yahoooauthdomain))
    $yahoo_oauth_domain = $yahooinvite->setting_yahoooauthdomain;
if (!empty($yahooinvite->setting_yahoooappid))
    $yahoo_app_id = $yahooinvite->setting_yahoooappid;

define('OAUTH_CONSUMER_KEY', $yahoo_consumer_key);
define('OAUTH_CONSUMER_SECRET', $yahoo_consumer_secret_key);
define('OAUTH_DOMAIN', $yahoo_oauth_domain);
define('OAUTH_APP_ID', $yahoo_app_id);

// check for the existance of a session.
// this will determine if we need to show a pop-up and fetch the auth url,
// or fetch the user's social data.
$hasSession = YahooSession::hasSession(OAUTH_CONSUMER_KEY, OAUTH_CONSUMER_SECRET, OAUTH_APP_ID);

if ($hasSession == FALSE) {
    // create the callback url,
    $callback = YahooUtil::current_url() . "?in_popup";
    $sessionStore = new NativeSessionStore();
    // pass the credentials to get an auth url.
    // this URL will be used for the pop-up.
    $auth_url = YahooSession::createAuthorizationUrl(OAUTH_CONSUMER_KEY, OAUTH_CONSUMER_SECRET, $callback, $sessionStore);
} else {
    // pass the credentials to initiate a session
    $session = YahooSession::requireSession(OAUTH_CONSUMER_KEY, OAUTH_CONSUMER_SECRET, OAUTH_APP_ID);

    // if the in_popup flag is detected,
    // the pop-up has loaded the callback_url and we can close this window.
    if (array_key_exists("in_popup", $_GET)) {
        close_popup();
        exit;
    }

    // if a session is initialized, fetch the user's profile information
    if ($session) {
        // Get the currently sessioned user.
        $user = $session->getSessionedUser();

        // Load the profile for the current user.
        $profile = $user->getProfile();
        $profile_contacts = XmltoArray($user->getContactSync());
        $contacts = array();
        if(!empty($profile_contacts['contactsync']['contacts'])){
        foreach ($profile_contacts['contactsync']['contacts'] as $key => $profileContact) {
            foreach ($profileContact['fields'] as $contact) {
                $contacts[$key][$contact['type']] = $contact['value'];
            }
        }
    }
}
}

/**
 * Helper method to close the pop-up window via javascript.
 */
function close_popup() {
?>
    <script type="text/javascript">
        window.close();
    </script>
<?php
}

$document = JFactory::getDocument();
//$document->addStyleSheet('components/com_socialpinboard/css/invite_frnds.css');
$document->addStyleSheet('templates/socialpinboard/html/com_socialpinboard/css/invite_frnds.css');
$document->addStyleSheet('templates/socialpinboard/html/com_socialpinboard/css/style.css');
$document->addStyleSheet('templates/socialpinboard/html/com_socialpinboard/css/pinboard.css');
$document->addStyleSheet('components/com_socialpinboard/css/reset.css');
$document->addStyleSheet('modules/mod_socialpinboard_menu/css/socialpinboard_menu.css');
$document->addScript('components/com_socialpinboard/javascript/chrome.js');
$document->addScript('components/com_socialpinboard/javascript/yahoo-dom-event.js');
$document->addScript('components/com_socialpinboard/javascript/popupmanager.js');
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
<div class="invite_frnds">
    <div class="about_left">
        <a class="" href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=invitefriends', false) ?>">
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
        <a class="yahoo_invite" href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=invitefriends&task=yahoo', false) ?>">
            <img src="<?php echo JURI::base() . '/components/com_socialpinboard/images/yahoo.png'; ?>" width="12" height="12" alt=""/>
            <?php echo JText::_('COM_SOCIALPINBOARD_YAHOO'); ?>
        </a>
    </div>

    <?php
            if ($hasSession == FALSE) {
                // if a session does not exist, output the
                // login / share button linked to the auth_url.
                ?> <ul>
                    <h2><?php echo JText::_('COM_SOCIALPINBOARD_YAHOO'); ?></h2>
                    <li><a  class="blue_btn" href="<?php echo $auth_url; ?>" id="yloginLink">
<!--                            <img src="<?php echo JURI::base() . '/components/com_socialpinboard/images/yahoo-btn.png'; ?>" title="Find Friends from Yahoo" alt="Find Friends from Yahoo" />-->
                            <strong><?php echo JText::_('COM_SOCIALPINBOARD_FIND_FRIENDS_FROM_YAHOO') ?></strong><img src ="<?php echo JURI::base(); ?>components/com_socialpinboard/images/yahoo-btn.png" alt="<?php echo JText::_('COM_SOCIALPINBOARD_FIND_FRIENDS_FROM_YAHOO') ?>"/>
                        </a><li>
                </ul>
    <?php
            } else if ($hasSession && $profile) {
    ?>

                <div class="invite_textarea_txt single_grid" >

                    <ul  class="yh_frnds">
            <?php
            if(!empty($contacts)){
                foreach ($contacts as $user_friend) {

                    if (isset($user_friend['email'])) {

                        if (!empty($user_friend['name']['givenName']))
                            $gmailuser_name = $user_friend['name']['givenName'];
                        else
                            $gmailuser_name=$user_friend['email'];
            ?>
                        <li class="friend">
                            <div class="individual email-id">
                                <div class="name">
                        <?php echo $gmailuser_name; ?>
                    </div>
                    <div class="email">
<?php echo $user_friend['email']; ?>
                    </div>
                </div>
                            <button class="fb_request_btn" type="button">
                                <a href="#" onclick="Modal.show('EmailModal'); binddata('<?php echo $gmailuser_name; ?>','<?php echo $user_friend['email']; ?>'); return false" target="_blank" class=""><strong>Invite</strong></a>
                            </button>
            </li>

            <?php }
                }  } else {
echo "<li><h3 id='login_error_msg' style='font-size: 24px; font-weight: 300;'>".JText::_('COM_SOCIALPINBOARD_YAHOO_NO_USER_FOUND')."</h3></li>";

                }?>
            </ul>
        </div>
    <?php }
    ?>
            <script type="text/javascript">
                var Event = YAHOO.util.Event;
                var _gel = function(el) {return document.getElementById(el)};

                function handleDOMReady() {
                    if(_gel("yloginLink")) {
                        Event.addListener("yloginLink", "click", handleLoginClick);
                    }
                }

                function handleLoginClick(event) {
                    // block the url from opening like normal
                    Event.preventDefault(event);

                    // open pop-up using the auth_url
                    var auth_url = _gel("yloginLink").href;
                    PopupManager.open(auth_url,600,435);
                }

                Event.onDOMReady(handleDOMReady);
            </script>
        </div>
        <div id="EmailModal" class="ModalContainer">
            <div class="modal wide PostSetup" style="margin-bottom: -138px; ">
                <div id="postsetup">
                    <div class="header lg">
                        <h2><?php echo JText::_('COM_SOCIALPINBOARD_INVITE_FRIEND'); ?></h2>
                        <a class="close-btn" onclick="Modal.close('EmailModal'); return false;"></a>
                    </div>
                    <div id="output"></div>
                    <form action="" method="post" class="Form FancyForm">
                        <ul>
                            <li>
                                <input type="text" id="MessageRecipientName" class="ClearOnFocus" name="MessageRecipientName" maxlength="180" onblur="if (this.placeholder == '') {this.placeholder = '<?php echo JText::_('COM_SOCIALPINBOARD_RECIPIENT_NAME'); ?>';}" onfocus="if (this.placeholder == '<?php echo JText::_('COM_SOCIALPINBOARD_RECIPIENT_NAME'); ?>') {this.placeholder = '';this.style.color ='#000';}" placeholder="<?php echo JText::_('COM_SOCIALPINBOARD_RECIPIENT_NAME'); ?>"/>
                                <span class="helper red"></span>
                                <div id="recipient_name_error"style="display:none"><?php echo JTExt::_('COM_SOCIALPINBOARD_PLEASE_ENTER_THE_RECIPIENT_NAME'); ?></div>
                            </li>
                            <li>
                                <input type="text" id="MessageRecipientEmail" class="ClearOnFocus" name="MessageRecipientEmail" maxlength="180" onblur="if (this.placeholder == '') {this.placeholder = '<?php echo JText::_('COM_SOCIALPINBOARD_RECIPIENT_EMAIL'); ?>';}" onfocus="if (this.placeholder == '<?php echo JText::_('COM_SOCIALPINBOARD_RECIPIENT_EMAIL'); ?>') {this.placeholder = '';this.style.color ='#000';}" placeholder="<?php echo JText::_('COM_SOCIALPINBOARD_RECIPIENT_EMAIL'); ?>" value="" />
                                <span class="helper red"></span>
                                <div id="recipient_email_error"style="display:none"><?php echo JTExt::_('COM_SOCIALPINBOARD_PLEASE_ENTER_THE_RECIPIENT_EMAIL'); ?></div>
                                <div id="recipient_invalid_email_error"style="display:none"><?php echo JTExt::_('COM_SOCIALPINBOARD_INVALID_EMAIL_ADDRESS'); ?></div>
                            </li>
                            <li class="optional">
                                <textarea id="MessageBody" class="ClearOnFocus" name="MessageBody" maxlength="180" onblur="if (this.placeholder == '') {this.placeholder = '<?php echo JText::_('COM_SOCIALPINBOARD_MESSAGE'); ?>';}" onfocus="if (this.placeholder == '<?php echo JText::_('COM_SOCIALPINBOARD_MESSAGE'); ?>') {this.placeholder = '';this.style.color ='#000';}" placeholder="<?php echo JText::_('COM_SOCIALPINBOARD_MESSAGE'); ?>"></textarea>
                                <div id="recipient_message_error"style="display:none"><?php echo JTExt::_('COM_SOCIALPINBOARD_PLEASE_ENTER_THE_RECIPIENT_MESSAGE'); ?></div>
                            </li>
                        </ul>
                        <div style="display:none"><input type="hidden" name="csrfmiddlewaretoken" value="3b5f5ae1987a0ff12f9b337e5670ae08" /></div>
                        <div><a onclick="sendemail();" class="" data-form="EmailModal" id="report_send_mail_btn"><?php echo JText::_('COM_SOCIALPINBOARD_SEND_EMAIL'); ?></a></div>
                        <div class="inputstatus error" ></div>
                    </form>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            function trim(stringToTrim) {
                return stringToTrim.replace(/^\s+|\s+$/g,"");
            }
            function binddata(name,email) {
                document.getElementById("MessageRecipientName").value=name;
                document.getElementById("MessageRecipientEmail").value=email;
            }
            function sendemail()
            {

                if(document.getElementById("MessageRecipientName").value=="")
                {
                    document.getElementById("recipient_name_error").style.display="block";
                    document.getElementById("MessageRecipientName").focus();
                    return false;
                }else
                {
                    document.getElementById("recipient_name_error").style.display="none";
                }
                if(document.getElementById("MessageRecipientEmail").value=="")
                {
                    document.getElementById("recipient_email_error").style.display="block";
                    document.getElementById("MessageRecipientEmail").focus();
                    return false;
                }else
                {
                    document.getElementById("recipient_email_error").style.display="none";
                }
                var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
                var address = trim(document.getElementById("MessageRecipientEmail").value);
                if(reg.test(address) == false) {
                    document.getElementById("recipient_invalid_email_error").style.display="block";
                    document.getElementById("recipient_email_error").style.display="none";
                    document.getElementById("MessageRecipientEmail").focus();
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
                        document.getElementById("output").innerHTML=xmlhttp.responseText;
                    }
                }
                xmlhttp.open("POST","<?php echo JRoute::_('index.php?option=com_socialpinboard&view=emailshare&tmpl=component&invite=invitefriends'); ?>",true);
                xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                var name = document.getElementById('MessageRecipientName').value;
                var email = document.getElementById('MessageRecipientEmail').value;
                var body = document.getElementById('MessageBody').value;
        xmlhttp.send('name=' + name+'&email=' + email+'&body=' + body);

    }
</script>



