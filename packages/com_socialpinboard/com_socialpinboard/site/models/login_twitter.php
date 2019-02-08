<?php

/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component login twitter model
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');
$library = JPATH_COMPONENT . DS . "lib" . DS . "twitter" . DS . "twitteroauth.php";

require ($library);

class socialpinboardModelLogin_twitter extends SocialpinboardModel {

    function gettwitterSetting() {
        global $mainframe;
        $db = JFactory::getDBO();
        $query = "SELECT setting_twitterapi,setting_twittersecret "
                . "FROM #__pin_site_settings";

        $db->setQuery($query);
        $result = $db->loadObject();

        return $result;
    }

    function twitterLogin() {

        $result = $this->gettwitterSetting();
        define('YOUR_CONSUMER_KEY', $result->setting_twitterapi);
        define('YOUR_CONSUMER_SECRET', $result->setting_twittersecret);

        $twitteroauth = new TwitterOAuth(YOUR_CONSUMER_KEY, YOUR_CONSUMER_SECRET);
        $url1 = JURI::base() . 'index.php?option=com_socialpinboard&view=gettwitterdata';

// Requesting authentication tokens, the parameter is the URL we will be redirected to
        $request_token = $twitteroauth->getRequestToken($url1);

// Saving them into the session
        if (!empty($request_token['oauth_token']) && !empty($request_token['oauth_token_secret'])) {
            $_SESSION['oauth_token'] = $request_token['oauth_token'];
            $_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
        }
// If everything goes well..
        if ($twitteroauth->http_code == 200) {

            // Let's generate the URL and redirect
            $url = $twitteroauth->getAuthorizeURL($request_token['oauth_token']);
            header('Location: ' . $url);
        } else {
            // It's a bad idea to kill the script, but we've got to know when there's an error.
            die('Something wrong happened.');
        }
    }

}