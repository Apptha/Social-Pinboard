<?php

/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component gettwitterdata model
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');
$library = JPATH_COMPONENT . DS . "lib" . DS . "twitter" . DS . "twitteroauth.php";
require ($library);

class socialpinboardModelGettwitterdata extends SocialpinboardModel {

    function gettwitterSetting() {
        global $mainframe;
        $db = JFactory::getDBO();
        $query = "SELECT setting_twitterapi,setting_twittersecret "
                . "FROM #__pin_site_settings";

        $db->setQuery($query);
        $result = $db->loadObject();

        return $result;
    }

    function gettwiiterdata() {
        $result = $this->gettwitterSetting();
        define('YOUR_CONSUMER_KEY', $result->setting_twitterapi);
        define('YOUR_CONSUMER_SECRET', $result->setting_twittersecret);

        if (!empty($_GET['oauth_verifier']) && !empty($_SESSION['oauth_token']) && !empty($_SESSION['oauth_token_secret'])) {

            // We've got everything we need
            $twitteroauth = new TwitterOAuth(YOUR_CONSUMER_KEY, YOUR_CONSUMER_SECRET, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
// Let's request the access token

            $access_token = $twitteroauth->getAccessToken($_GET['oauth_verifier']);
// Save it in a session var
            $_SESSION['access_token'] = $access_token;
// Let's get the user's info
            $user_info = $twitteroauth->get('account/verify_credentials');
// Print user's info

            $twitter_id = $user_info->id;

            $twitter_name = $user_info->screen_name;


            if (!$twitter_id || $twitter_id == '' || $twitter_id = '0') {
                $app = JFactory::getApplication();
                $redirect = JRoute::_('index.php?com_socialpinboar&view=home');
                $app->redirect($redirect, $msg = 'Check Out the Twitter Login Credentials!', $msgType = 'message');
            } else {
                $db = JFactory::getDBO();
                $query = "select count(id) from #__users WHERE name='$twitter_name' AND block=1";
                $db->setQuery($query);
                $userBlock = $db->loadResult();


                if ($userBlock != 0) {
                    if (isset($_SERVER['HTTP_COOKIE'])) {
                        $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
                        foreach ($cookies as $cookie) {
                            $parts = explode('=', $cookie);
                            $name = trim($parts[0]);
                            setcookie($name, '', time() - 1000);
                            setcookie($name, '', time() - 1000, '/');
                        }
                    }
                    $redirect = JRoute::_('index.php?option=com_socialpinboard&view=people&task=blocked', false);
                    $app = JFactory::getApplication();
                    $app->redirect($redirect, $msg = 'Account Blocked', $msgType = 'message');
                }
                $count = 1;

                $query = "select count(id) from #__users WHERE name='$twitter_name' AND block!='1'";
                $db->setQuery($query);
                $result = $db->loadResult();


                if ($result > 0) {

                    $query = "select name,username,email,id from #__users WHERE name='$twitter_name' AND block!='1'";
                    $db->setQuery($query);
                    $result = $db->loadAssoc();


                    $user_Id = $result['id'];
                    $userEmail = $result['email'];
                    $userName = trim($result['username']);
                    $name = $result['name'];
                    $password = $userEmail;

                    $facebook = 'facebook';
                    $query = "select user_id from #__pin_user_settings WHERE email='$userEmail'";

                    $db->setQuery($query);
                    $result = $db->loadResult();

                    $count = $count + 1;

                    $query = "UPDATE #__pin_user_settings SET count='" . $count . "',user_id='" . $user_Id . "',username='" . $name . "',status='1',created_date='NOW()' WHERE email='" . $userEmail . "'";
                    $db->setQuery($query);
                    $db->query();


                    if ($version != '1.5') {
                        $query = "select rules from #__assets WHERE name='root.1'";
                        $db->setQuery($query);
                        $result = $db->loadObject();
                        $json_a = json_decode($result->rules, true);
                        $accessLevel = isset($json_a['core.login.site']['1']) ? $json_a['core.login.site']['1'] : '0';
                        if ($accessLevel == '1') {
                            $this->twitterLogin($userEmail, $userName, $password, $facebook);
                        } else {
                            $error = JText::_('User Login Restriced');
                            JFactory::getApplication()->redirect('index.php?option=com_users&view=login', $error, 'error');
                            return false;
                        }
                    } else {
                        $this->twitterLogin($userEmail, $userName, $password, $facebook);
                    }
                } else {
                    return $user_info;
                }
            }
        }
    }

    function twitterLogin($userEmail, $userName, $password, $facebook) {

        if (version_compare(JVERSION, '2.5.0', 'ge')) {
            $version = '1.7';
        } else if (version_compare(JVERSION, '1.7.0', 'ge')) {
            $version = '1.7';
        } elseif (version_compare(JVERSION, '1.6.0', 'ge')) {
            $version = '1.6';
        } else {
            $version = '1.5';
        }

        if ($version != '1.5') {
            $app = JFactory::getApplication();
        } else {
            global $mainframe;
        }

        // Populate the data array:
        $data = array();
        $returnURL = 'index.php?option=com_socialpinboard&view=people';
        $data['username'] = $userName;
        $data['password'] = $password;

        // Get the log in options.
        $options = array();
        $options['remember'] = JRequest::getBool('remember', false);
        $options['return'] = $returnURL;

        // Get the log in credentials.
        $credentials = array();
        $credentials['username'] = $data['username'];
        $credentials['password'] = $data['password'];
        $credentials['facebook'] = $facebook;
        // Perform the log in.
        if ($version != '1.5') {
            $error = $app->login($credentials, $options);
        } else {
            $error = $mainframe->login($credentials, $options);
        }

        // Check if the log in succeeded.
        if (!JError::isError($error)) {
            if ($version != '1.5') {
                $app->setUserState('users.login.form.data', array());
                $app->redirect(JRoute::_($returnURL, false));
            } else {

                $mainframe->redirect($returnURL);
            }
        } else {
            if ($version != '1.5') {
                $data['remember'] = (int) $options['remember'];
                $app->setUserState('users.login.form.data', $data);
               $app->redirect(JRoute::_('index.php?option=com_users&view=login', false));
            } else {

                $mainframe->redirect($returnURL);
            }
        }
    }

}