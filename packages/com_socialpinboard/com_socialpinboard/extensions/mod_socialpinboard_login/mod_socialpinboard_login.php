<?php

/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Module login
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');
require_once( dirname(__FILE__) . DS . 'src/facebook.php' );
require_once( dirname(__FILE__) . DS . 'helper.php' );
$fbResult = modsocialpinboard_login::getFbSetting();
$fbResult->setting_facebookapi = isset($fbResult->setting_facebookapi) ? $fbResult->setting_facebookapi : '';
$fbResult->setting_facebooksecret = isset($fbResult->setting_facebooksecret) ? $fbResult->setting_facebooksecret : '';
$facebook = new Facebook(array(
            'appId' => "$fbResult->setting_facebookapi",
            'secret' => "$fbResult->setting_facebooksecret",
            'cookie' => false,
        ));

$user = $facebook->getUser();
$session = JFactory::getSession();
if ($session->get('fbVal') == '1') {
    $user = '0';
    $session->clear('fbVal');
}

$userId = JFactory::getUser();

// We may or may not have this data based on whether the user is logged in.
//
// If we have a $user id here, it means we know the user is logged into
// Facebook, but we don't know if the access token is valid. An access
// token is invalid if the user logged out of Facebook.
if ($user) {
    try {
        // Proceed knowing you have a logged in user who's authenticated.
        $user_profile = $facebook->api('/me');
    } catch (FacebookApiException $e) {
        error_log($e);
        $user = null;
    }
}

// Login or logout url will be needed depending on current user state.
if ($user) {
    $logoutUrl = $facebook->getLogoutUrl();
} else {
    $loginUrl = $facebook->getLoginUrl(array('scope' => 'email,offline_access,publish_stream,user_birthday,user_location,user_work_history,user_about_me,user_hometown'));
}

if (!empty($user)) {

    $recentresult = modsocialpinboard_login::getFacebookDetails($user, $user_profile);
}
require(JModuleHelper::getLayoutPath('mod_socialpinboard_login'));
?>
