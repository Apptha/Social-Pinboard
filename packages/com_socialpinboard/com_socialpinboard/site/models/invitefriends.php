<?php

/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component invitefriends model
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');
@require_once JPATH_COMPONENT . DS . "lib" . DS . "facebook.php";

class socialpinboardModelInvitefriends extends SocialpinboardModel {

    function getFacebookSettings() {
        $db = $this->getDBO();
        $query = "SELECT setting_yahoooappid,setting_yahoooauthdomain,setting_yahooconsumersecretkey,setting_yahooconsumerkey,setting_gmailclientid,setting_gmailclientsecretkey,setting_facebookapi,setting_facebooksecret from #__pin_site_settings";
        $db->setQuery($query);
        $facebookSettings = $db->loadObject();
        return $facebookSettings;
    }

    function getFacebookprofileId() {
        $db = $this->getDBO();
        $user = JFactory::getUser();
        $userId = $user->id;
        $query = "SELECT facebook_profile_id FROM #__pin_user_settings WHERE user_id = '$userId' ORDER BY created_date DESC LIMIT 1";
        $db->setQuery($query);
        $facebookProfileId = $db->loadResult();
        return $facebookProfileId;
    }

    function addFacebookProfile($getFacebookSettings) {

        $facebook = new Facebook(array(
                    'appId' => "$getFacebookSettings->setting_facebookapi",
                    'secret' => "$getFacebookSettings->setting_facebooksecret",
                    'cookie' => true,
                ));
        $user_info = $facebook->api('/me');
        $facebook_profile_id = $user_info['id'];

        $db = $this->getDBO();
        $user = JFactory::getUser();
        $userId = $user->id;
        $query = "UPDATE #__pin_user_settings SET facebook_profile_id='$facebook_profile_id'  WHERE user_id = '$userId'";
        $db->setQuery($query);
        $db->query();
    }

}

?>
