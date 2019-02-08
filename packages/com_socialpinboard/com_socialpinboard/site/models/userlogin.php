<?php

/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component userlogin model
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');
jimport('joomla.user.helper');
jimport('joomla.base.object');
jimport('joomla.error.error');
jimport('joomla.error.exception');

class socialpinboardModeluserlogin extends SocialpinboardModel {

    function getTwitterDetails() {
        $userName = JRequest::getVar('twittername');
        $reset = JRequest::getVar('reset');
        $twitter_id = JRequest::getVar('Twitter_id');
        $email = JRequest::getVar('twitterEmail');
        $oldPassword = JRequest::getVar('twitterPassword');
        $db = JFactory::getDBO();

        $profile_Image = JRequest::getVar('profile_image');
        $facebook = JRequest::getVar('facebook');
        $twitter = JRequest::getVar('twitter');


        //chk user exist
        $query = "SELECT COUNT(id) FROM #__pin_user_request WHERE email_id='" . $email . "' AND approval_status='1'";

        $db->setQuery($query);
        $userExist = $db->loadResult();

        //check the show user request in front end
        $query = "SELECT setting_show_request FROM #__pin_site_settings";

        $db->setQuery($query);
        $show_request = $db->loadResult();

        if ($show_request == '0') {
            $userExist = 1;
            $db = JFactory::getDbo();
            $query = "SELECT count(id) from #__pin_user_request WHERE email_id='$email'";
            $db->setQuery($query);
            $userInvited = $db->loadResult();

            if ($userInvited == '1') {
                $query = "UPDATE #__pin_user_request SET approval_status=1 where email_id='$email'";
                $db->setQuery($query);
                $db->query();
            } else {
                //insert the approval into the user
                $query = "INSERT INTO `#__pin_user_request` (`email_id`,`approval_status`) VALUES ('" . $email . "','1')";
                $db->setQuery($query);
                $db->query();
            }
        }

        $query = "SELECT COUNT(user_id) FROM #__pin_user_settings WHERE username='" . $userName . "'";
        $db->setQuery($query);
        $userNameExist = $db->loadResult();


        if ($facebook) {
            $salt = JUserHelper::genRandomPassword(32);
            $crypt = JUserHelper::getCryptedPassword("$oldPassword", $salt);
            $password = $crypt . ':' . $salt;
            $password = trim($password);
            $query = "UPDATE #__users SET password='" . $password . "' WHERE email='" . $email . "'";
            $db->setQuery($query);
            $db->query();
            $this->twitterLogin($email, $email, $oldPassword);
        } else if ($twitter && $userExist == 0) {
            if (isset($_SERVER['HTTP_COOKIE'])) {
                $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
                foreach ($cookies as $cookie) {
                    $parts = explode('=', $cookie);
                    $name = trim($parts[0]);
                    setcookie($name, '', time() - 1000);
                    setcookie($name, '', time() - 1000, '/');
                }
            }
            $redirect = JRoute::_('index.php?option=com_socialpinboard&view=people', false);
            $app = JFactory::getApplication();
            $app->redirect($redirect, $msg = 'Your Email is not associalted with any account', $msgType = 'message');
        } else if ($userNameExist && $reset=='') {
            $redirect = JRoute::_('index.php?option=com_socialpinboard&view=people&task=available', false);
            $app = JFactory::getApplication();
            $app->redirect($redirect);
        } else {

            if ($userName) {

                $db = JFactory::getDBO();
                $query = "select email from #__users WHERE username='$email' || name='$userName'";
                $db->setQuery($query);
                $userEmail = $db->loadResult();

                if (version_compare(JVERSION, '2.5.0', 'ge')) {
                    $version = '2.5';
                } else if (version_compare(JVERSION, '1.7.0', 'ge')) {
                    $version = '1.7';
                } elseif (version_compare(JVERSION, '1.6.0', 'ge')) {
                    $version = '1.6';
                } else {
                    $version = '1.5';
                }

                //For facebook signup process
                if ($userEmail == '') {
                    $salt = JUserHelper::genRandomPassword(32);
                    $crypt = JUserHelper::getCryptedPassword("$oldPassword", $salt);
                    $password = $crypt . ':' . $salt;
                    $password = trim($password);

                    if ($version != '1.5') {
                        $query = "INSERT INTO #__users(name,username,email,password,sendEmail,registerDate,lastvisitDate)  VALUES
                                ('" . $userName . "', '" . $email . "' , '" . $email . "', '" . $password . "',1,now(),now())";
                        $db->setQuery($query);
                        $db->query();
                        $userId = $db->insertid();
                        $query = "INSERT INTO #__user_usergroup_map(user_id,group_id)  VALUES
                                ('" . $userId . "', '2')";
                        $db->setQuery($query);
                        $db->query();
                    } else {
                        $query = "INSERT INTO #__users(name,username,email,password,sendEmail,registerDate,lastvisitDate)  VALUES
                                  ('" . $userName . "', '" . $email . "' , '" . $email . "', '" . $password . "',1,now(),now())";
                        $db->setQuery($query);
                        $db->query();
                        $userId = $db->insertid();

                        $query = "INSERT INTO #__core_acl_aro(section_value,value,name)  VALUES
                                 ('users', '" . $userId . "' , '" . $email . "')";
                        $db->setQuery($query);
                        $db->query();
                        $mapId = $db->insertid();

                        $query = "INSERT INTO #__core_acl_groups_aro_map(group_id,aro_id)  VALUES
                                  ('25', '" . $mapId . "')";
                        $db->setQuery($query);
                        $db->query();
                    }
                }

                $query = "select username,email,id from #__users WHERE username='$email' and block!=1";
                $db->setQuery($query);
                $result = $db->loadAssoc();
                $user_Id = $result['id'];
                $userEmail = $result['email'];

//twitter exists or not
                $query = "select twitter_id from #__pin_user_settings WHERE username='$userName' || email='" . $email . "'";
                $db->setQuery($query);
                $twitter_availabe = $db->loadResult();

                //See whether already users exists or not

                $query = "select count(user_id) from #__pin_user_settings WHERE username='$userName' || email='" . $email . "'";
                $db->setQuery($query);
                $result = $db->loadResult();

                $userImage = $userName . $user_Id . '.jpg';

                if ($result >= 1) {

                    $query = "SELECT count from #__pin_user_settings where username='$userName' ";
                    $db->setQuery($query);
                    $count = $db->loadResult();
                    $count = $count + 1;

                    $query = "UPDATE #__pin_user_settings SET count='" . $count . "',twitter_id='" . $twitter_id . "',user_id='" . $user_Id . "',username='" . $userName . "',status='1',created_date='NOW()' WHERE email='" . $email . "'";
                    $db->setQuery($query);
                    $db->query();

                    $query = "UPDATE #__users SET name='" . $userName . "' WHERE email='" . $email . "'";
                    $db->setQuery($query);
                    $db->query();
                } else {

                    $query = "INSERT INTO #__pin_user_settings(twitter_id,user_id,first_name,email,username,user_image,count,status,created_date) VALUES
                        ('" . $twitter_id . "','" . $user_Id . "','" . $userName . "','" . $email . "','" . $userName . "','" . $userImage . "','" . $count . "','1',NOW())";
                    $db->setQuery($query);
                    $db->query();

                    // Download Image
                    $thumb_image = file_get_contents($profile_Image);
                    $thumb_file = JPATH_SITE . DS . "images" . DS . "socialpinboard" . DS . "avatars" . DS . $userName . $user_Id . '.jpg';

                    file_put_contents($thumb_file, $thumb_image);


                    $original_image = file_get_contents($profile_Image);
                    $original_file = JPATH_SITE . DS . "images" . DS . "socialpinboard" . DS . "avatars" . DS . $userName . $user_Id . '_o.jpg';
                    file_put_contents($original_file, $original_image);

                    $this->twitterLogin($email, $email, $oldPassword);
                }

                if ($version != '1.5') {
                    $query = "select rules from #__assets WHERE name='root.1'";
                    $db->setQuery($query);
                    $result = $db->loadObject();
                    $json_a = json_decode($result->rules, true);
                    $accessLevel = isset($json_a['core.login.site']['1']) ? $json_a['core.login.site']['1'] : '0';
                    if ($accessLevel == '1') {

                        $this->twitterLogin($email, $email, $oldPassword);
                    } else {
                        $error = JText::_('User Login Restriced');
                        JFactory::getApplication()->redirect('index.php?option=com_socialpinboard&view=people', $error, 'error');
                        return false;
                    }
                } else {

                    $this->twitterLogin($email, $email, $oldPassword);
                }
            }
        }
    }

    function checkPassword($user_email, $user_password, $twitter_id, $username) {
        $db = JFactory::getDBO();
        $app = JFactory::getApplication();
        $options = array();
        $options['remember'] = JRequest::getBool('remember', false);
        $options['return'] = '';

        $credentials = array();
        $credentials['username'] = $user_email;
        $credentials['password'] = $user_password;

        $error = $app->login($credentials, $options);
        $error_login = $app->getMessageQueue();
        if ($error_login) {
            return $error_login[0]['message'];
        } else {


            $query = "UPDATE #__pin_user_settings SET count='2',twitter_id='" . $twitter_id . "',username='" . $username . "',status='1',created_date=NOW() WHERE email='" . $user_email . "'";
            $db->setQuery($query);
            $db->query();

            $query = "UPDATE `#__users` SET `name`='" . $username . "'  WHERE `email`='" . $user_email . "'";
            $db->setQuery($query);
            $db->query();

            return 'success';
        }
    }

    function twitterLogin($userEmail, $userName, $password) {


        if (version_compare(JVERSION, '1.7.0', 'ge')) {
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
        $returnURL = JRoute::_('index.php?option=com_socialpinboard&view=home&newlogin=1', false);
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

                $app->redirect($returnURL);
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

    function twitterAvailableLogin($userEmail, $userName, $password) {

        if (version_compare(JVERSION, '1.7.0', 'ge')) {
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
        $returnURL = JRoute::_('index.php?option=com_socialpinboard&view=home', true);
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

                $app->redirect($returnURL);
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

    function resetPassword($email, $reset_password) {

        $db = & JFactory::getDBO();
        $salt = JUserHelper::genRandomPassword(32);
        $crypt = JUserHelper::getCryptedPassword("$reset_password", $salt);
        $password = $crypt . ':' . $salt;
        $password = trim($password);
        $query = "UPDATE #__users SET password='" . $password . "',activation='' WHERE email='" . $email . "'";
        $db->setQuery($query);
        $db->query();

        $this->twitterAvailableLogin($email, $email, $reset_password);
    }

    function getActivationCode($email) {

        $db = & JFactory::getDBO();
        $query = "SELECT activation
                      FROM #__users 
                      WHERE email='" . $email . "'";

        $db->setQuery($query);
        $activation_code = $db->loadResult();

        return $activation_code;
    }

}