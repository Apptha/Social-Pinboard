<?php
/**
 * @name          : Joomla Social Pinboard
 * @version	  : 1.5.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component gmail invitefriends view
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
error_reporting(0);
// No direct Access
defined('_JEXEC') or die('Restricted access');
$document = JFactory::getDocument();
//$document->addStyleSheet('components/com_socialpinboard/css/style.css');
$document->addStyleSheet('templates/socialpinboard/html/com_socialpinboard/css/invite_frnds.css');
$document->addStyleSheet('templates/socialpinboard/html/com_socialpinboard/css/style.css');
$document->addStyleSheet('templates/socialpinboard/html/com_socialpinboard/css/pinboard.css');
$document->addStyleSheet('components/com_socialpinboard/css/reset.css');
$document->addStyleSheet('modules/mod_socialpinboard_menu/css/socialpinboard_menu.css');
$document->addScript('components/com_socialpinboard/javascript/chrome.js');
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
$gmailinvite = $this->getFacebookSettings;
$code = JRequest::getVar('code');

$arrayVal = parse_url(JURI::base());
$base_url = "http://" . $arrayVal['host'];
?><div class="invite_frnds">
    <div class="about_left">
        <a class="" href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=invitefriends', false) ?>">
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
        <a class="" href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=invitefriends&task=yahoo', false) ?>">
            <img src="<?php echo JURI::base() . '/components/com_socialpinboard/images/yahoo.png'; ?>" width="12" height="12" alt=""/>
            <?php echo JText::_('COM_SOCIALPINBOARD_YAHOO'); ?>
        </a>
    </div>
    <?php if (empty($code)) {
    ?><ul>
        <h2><?php echo JText::_('COM_SOCIALPINBOARD_GMAIL'); ?></h2>
        <li>
                        <a class="blue_btn" href="https://accounts.google.com/o/oauth2/auth?client_id=<?php echo $gmailinvite->setting_gmailclientid; ?>&redirect_uri=<?php echo $base_url . JRoute::_('index.php?option=com_socialpinboard&view=invitefriends&task=gmail', false) ?>&scope=https://www.google.com/m8/feeds/&response_type=code" >
                           <strong><?php echo JText::_('COM_SOCIALPINBOARD_FIND_CONTACTS_FROM_GMAIL') ?></strong><img src ="<?php echo JURI::base(); ?>components/com_socialpinboard/images/gmail-btn.png" alt="<?php echo JText::_('COM_SOCIALPINBOARD_FIND_CONTACTS_FROM_GMAIL') ?>"/>


                        </a>
                    </li></ul>
    <?php
            } else {

                function curl_file_get_contents($url) {
                    $curl = curl_init();
                    $userAgent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)';
                    curl_setopt($curl, CURLOPT_URL, $url); //The URL to fetch. This can also be set when initializing a session with curl_init().
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE); //TRUE to return the transfer as a string of the return value of curl_exec() instead of outputting it out directly.
                    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5); //The number of seconds to wait while trying to connect.
                    curl_setopt($curl, CURLOPT_USERAGENT, $userAgent); //The contents of the "User-Agent: " header to be used in a HTTP request.
                    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE); //To follow any "Location: " header that the server sends as part of the HTTP header.
                    curl_setopt($curl, CURLOPT_AUTOREFERER, TRUE); //To automatically set the Referer: field in requests where it follows a Location: redirect.
                    curl_setopt($curl, CURLOPT_TIMEOUT, 10); //The maximum number of seconds to allow cURL functions to execute.
// curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);	//To stop cURL from verifying the peer's certificate.
                    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
                    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
                    $contents = curl_exec($curl);
                    curl_close($curl);
                    return $contents;
                }

                $authcode = $code;
                $clientid = $gmailinvite->setting_gmailclientid;
                $clientsecret = $gmailinvite->setting_gmailclientsecretkey;
                $redirecturi = $base_url . JRoute::_('index.php?option=com_socialpinboard&view=invitefriends&task=gmail', false);
                $fields = array(
                    'code' => urlencode($authcode),
                    'client_id' => $clientid,
                    'client_secret' => $clientsecret,
                    'redirect_uri' => $redirecturi,
                    'grant_type' => urlencode('authorization_code')
                );
//url-ify the data for the POST
                $fields_string = '';
                foreach ($fields as $key => $value) {
                    $fields_string .= $key . '=' . $value . '&';
                }
                $fields_string = rtrim($fields_string, '&');
//open connection
                $ch = curl_init();
//set the url, number of POST vars, POST data
                curl_setopt($ch, CURLOPT_URL, 'https://accounts.google.com/o/oauth2/token');
                curl_setopt($ch, CURLOPT_POST, 5);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
// Set so curl_exec returns the result instead of outputting it.
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//to trust any ssl certificates
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//execute post
                $result = curl_exec($ch);
//close connection
                curl_close($ch);
//extracting access_token from response string
                $response = json_decode($result);
                $accesstoken = $response->access_token;
//passing accesstoken to obtain contact details
                $url = 'https://www.google.com/m8/feeds/contacts/default/full?max-results=5000&oauth_token=' . $accesstoken;
                $xmlresponse = file_get_contents($url);
                $xml = new SimpleXMLElement($xmlresponse);
                $xml->registerXPathNamespace('gd', 'http://schemas.google.com/g/2005');
                $result = $xml->xpath('//gd:email');

                $results = array();
                foreach ($xml->entry as $entry) {
                    $xml = simplexml_load_string($entry->asXML());
                    $ary = array();
                    $ary['name'] = (string) $entry->title;
                    foreach ($xml->email as $e) {
                        $ary['emailAddress'][] = (string) $e['address'];
                    }
                    if (isset($ary['emailAddress'][0])) {
                        $ary['email'] = $ary['emailAddress'][0];
                    }
                    $results[] = $ary;

                }              

    ?>
                <div class="invite_textarea_txt single_grid" >
                    <ul class="yh_frnds">
<?php
               
                foreach ($results as $result) {

                    $title = $result['title'];
                    $emailAddress = $result['email'];

                    $arr = explode('@',$emailAddress);
                    $gmailuser_name = (!empty($title))?$title:ucfirst($arr[0]);
                  
                    $button_eve = "Modal.show('EmailModal'); binddata('" . $gmailuser_name . "','" . $emailAddress . "'); return false";
                    echo '<li class="friend"><div class="individual email-id">
        <div class="name">
            ' . $gmailuser_name . '
        </div>
        <div class="email">
            <div class="email">' . $emailAddress . '</div>
        </div>
    </div>
    <button class="fb_request_btn" type="button">
<a href="#" onclick="' . $button_eve . '" target="_blank" id="EmailShare" class="Button WhiteButton Button11"><strong>Invite</strong></a>
  </button>
    </li>';
                    
                }
?>
            </ul>
        </div>
<?php } ?>
</div>
<div id="EmailModal" class="ModalContainer">
    <div class="modal wide PostSetup" style="margin-bottom: -138px; ">
        <div id="postsetup">
            <div class="header lg">
                <h2><?php echo JText::_('COM_SOCIALPINBOARD_INVITE_FRIEND'); ?></h2>
                        <a class="close-btn" onclick="Modal.close('EmailModal'); return false"></a>
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
                                <span class="fff"></span>
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
                var pinid = '<?php echo JRequest::getInt('pinid'); ?>';
        xmlhttp.send('name=' + name+'&email=' + email+'&body=' + body+'&pinid=' +pinid);

    }
</script>