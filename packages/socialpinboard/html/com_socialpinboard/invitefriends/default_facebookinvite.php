<?php
/**
 * @name          : Joomla Social Pinboard
 * @version	  : 1.5.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component facebook invitefriends view
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');

@require_once JPATH_COMPONENT . "/lib/facebook.php";

$document = JFactory::getDocument();
$document->addStyleSheet('components/com_socialpinboard/css/invite_frnds.css');
$document->addStyleSheet('components/com_socialpinboard/css/pinboard.css');
$document->addStyleSheet('components/com_socialpinboard/css/style.css');
$getFacebookSettings = $this->getFacebookSettings;
$getFacebookprofileId = $this->getFacebookprofileId;
$fbconfig = $getFacebookSettings->setting_facebookapi;
$user_redirect_url = JURI::base() . 'index.php?option=com_socialpinboard&view=people';
$app = JFactory::getApplication();
$templateparams = $app->getTemplate(true)->params; // get the tempalte parameters
$logo = $templateparams->get('logo'); //get the logo
$facebook_image = JURI::base() . '/' . htmlspecialchars($logo);
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
if ($logo == '') {
    $facebook_image = JURI::base() . '/templates/socialpinboard/images/logo.png';
}
?>

<div class="invite_frnds">
    <div class="about_left">
        <a class="" href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=invitefriends', false) ?>">
            <img src="<?php echo JURI::base() . '/components/com_socialpinboard/images/mail.png'; ?>" width="12" height="12" alt=""/>
<?php echo JText::_('COM_SOCIALPINBOARD_EMAIL'); ?>
        </a>
        <a class="facebook_invite" href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=invitefriends&task=facebook', false) ?>">
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

    <div id="fb-root"></div>

    <script type="text/javascript" src="http://connect.facebook.net/en_US/all.js"></script>

    <script type="text/javascript">
        FB.init({ appId: "<?php echo $fbconfig; ?>",  	    status: true,  	    cookie: true, 	    xfbml: true, 	    oauth: true});

    </script>
    <script type="text/javascript">
            
        function newInvite(){
            var receiverUserIds = FB.ui({
                     
                method: 'send',
                name: 'Check Out My Stuff',
                link: '<?php echo $user_redirect_url; ?>',
                picture:'<?php echo $facebook_image; ?>'
            });
           
        }
             
        function sendRequestToRecipients(fb_id) {
            var response= FB.ui({
           
                method: 'send',
                name: 'Checkout my stuff',
                link: '<?php echo $user_redirect_url; ?>',
                picture:'<?php echo $facebook_image; ?>',
                to: fb_id
            },     function(response) {

                if (!response)
                    return;
                var request_id = response.request + "_" + response.to[0];
              
            });
            console.log(response.request_ids);
        }
     </script>

<?php
   $facebook = new Facebook(array(
                'appId' => "$getFacebookSettings->setting_facebookapi",
                'secret' => "$getFacebookSettings->setting_facebooksecret",
                'cookie' => true,
            ));
   $fb_user = $facebook->getUser();
if ($fb_user) {
?>

    <ul class="fb_invite_frnds">
        <a class="invite_fb_frnds" href="#" onclick="newInvite(); return false;"  >
            <strong>Invite Friends</strong>
        </a>

<?php



    $friends = $facebook->api('/' . $getFacebookprofileId . '/friends');
    $friendsList = array();
    $friendsList1 = array();
    foreach ($friends as $key => $value) {
        foreach ($value as $fkey => $fvalue) {
            if (!empty($fvalue['name']) && $fvalue['name'] != 'h') {
?>
                <li>

                    <img alt="" src="<?php echo 'https://graph.facebook.com/' . $fvalue['id'] . '/picture' ?>"  />

                    <span class="fb_users_name">
        <?php
                echo $fvalue['name'];
        ?>
                    </span>
                    <div id="fb-root"></div>

                    <input type="button" class="fb_request_btn" onclick="sendRequestToRecipients('<?php echo $fvalue['id']; ?>'); return false;" value="<?php echo JText::_('COM_SOCIALPINBOARD_INVITE'); ?>"  />

                <?php }
        } ?>
        </li>
<?php
    }
} else {

    $facebook = new Facebook(array(
                'appId' => "$getFacebookSettings->setting_facebookapi",
                'secret' => "$getFacebookSettings->setting_facebooksecret",
            ));
    $loginUrl = $facebook->getLoginUrl(array('scope' => 'email,offline_access,publish_stream,user_birthday,user_location,user_work_history,user_about_me,user_hometown'));
?> 
        <div class="invite_frnds_facebook">
            <h1><?php echo JText::_('COM_SOCIALPINBOARD_FACEBOOK'); ?></h1>
            <a class="blue_btn" href="<?php echo $loginUrl; ?>"><strong><?php echo JText::_('COM_SOCIALPINBOARD_FIND_FRIENDS_FROM_FACEBOOK') ?></strong><img src ="<?php echo JURI::base(); ?>components/com_socialpinboard/images/facebookinvitebtn.png" alt="<?php echo JText::_('COM_SOCIALPINBOARD_FACEBOOK_LOGIN') ?>"/></a>
        </div>

        <?php
    }
        ?>

    </ul>
</div>