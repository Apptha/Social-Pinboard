<?php

/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component register model
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');
jimport('joomla.user.helper');
jimport('joomla.filesystem.file');

//to get data for design details from database. 
class socialpinboardModelregister extends SocialpinboardModel {

    function sendActivationMail() {
        $mailer = JFactory::getMailer();
        $app = Jfactory::getApplication();
        $detail = JRequest::get('POST');
        $new_email = $detail['email'];
        $first_name = $detail['first_name'];
        $last_name = $detail['last_name'];
        $new_password = $detail['new_password'];
        $confirm_password = $detail['confirm_password'];
        $username = $detail['username'];
        $user = JFactory::getUser();
        $user_id = $user->id;
        $config = JFactory::getConfig();
        $fromEmail = $config->get('config.mailfrom');
        $fromname = $config->get('config.fromname');

        $db = JFactory::getDBO();
        $query = "select block from #__users WHERE username='" . $new_email . "' ";
        $db->setQuery($query);
        $userBlock = $db->loadResult();

        $query = "select status from #__pin_user_settings WHERE username='" . $new_email . "' ";
        $db->setQuery($query);
        $user_setting_status = $db->loadResult();
        if ($userBlock == '' && $user_setting_status == '') {
//mail joomla
            $config = JFactory::getConfig();
            $sender = $config->get('mailfrom');
            $site_name = $config->get('sitename');
            $mailer->setSender($sender);
            $mailer->setSubject('Activation Mail');
            //set recipient
            $recipient = $new_email;
            $mailer->addRecipient($recipient);
            //set the body
            $templateparams = $app->getTemplate(true)->params; // get the tempalte parameters
            $logo = $templateparams->get('logo'); //get the logo

            if ($logo != null) {


                $image_source = JURI::base() . '/' . htmlspecialchars($logo);
            } else {
                $image_source = JURI::base() . '/templates/socialpinboard/images/logo-large.png';
            }
            $query = $db->getQuery(true);
            $activation_code = rand(1, 1000000);
            $adminUrl = JURI::base() . 'index.php?option=com_socialpinboard&view=people&email=' . $new_email . '&activaton=' . $activation_code;

            $baseurl = JURI::base();
            $body = file_get_contents(JURI::base() . '/templates/socialpinboard/emailtemplate/activationmail.html');
            $body = str_replace("{baseurl}", $baseurl, $body);
            $body = str_replace("{site_name}", $site_name, $body);
            $body = str_replace("{site_logo}", $image_source, $body);
            $body = str_replace("{username}", $username, $body);
            $body = str_replace("{activationlink}", $adminUrl, $body);

            $mailer->isHTML(true);
            $mailer->Encoding = 'base64';
            $mailer->setBody($body);

            $send = $mailer->Send();
//ends
            $query = "INSERT INTO `#__pin_user_activation` (`email`, `activationcode`, `created date`) VALUES  ('$new_email','$activation_code',now())";

            $db->setQuery($query);
            $db->query();

            //chk show request
            $query = "SELECT setting_show_request FROM #__pin_site_settings";
            $db->setQuery($query);
            $show_request = $db->loadResult();

            if ($show_request == '0') {
                $userExist = 1;
                $query = "SELECT count(id) from #__pin_user_request WHERE email_id='$new_email'";
                $db->setQuery($query);
                $userInvited = $db->loadResult();

                if ($userInvited == '1') {
                    $query = "UPDATE #__pin_user_request SET approval_status=1 where email_id='$new_email'";
                    $db->setQuery($query);
                    $db->query();
                }
            }
            $query = "SELECT COUNT(user_id) FROM #__pin_user_settings WHERE username='" . $username . "' ";
            $db->setQuery($query);
            $userNameExist = $db->loadResult();

            // if user is already registeres or have not given approval
            $salt = JUserHelper::genRandomPassword(32);
            $crypt = JUserHelper::getCryptedPassword("$confirm_password", $salt);
            $password = $crypt . ':' . $salt;
            $password = trim($password);

            if ($version != '1.5') {
                $query = "INSERT INTO #__users(name,username,email,password,block,sendEmail,registerDate,lastvisitDate)  VALUES
                    ('" . $username . "', '" . $new_email . "' , '" . $new_email . "', '" . $password . "',1,1,now(),now())";
                $db->setQuery($query);
                $db->query();
                $userId = $db->insertid();
                $query = "INSERT INTO #__user_usergroup_map(user_id,group_id)
                          VALUES('" . $userId . "', '2')";
                $db->setQuery($query);
                $db->query();
            } else {
                $query = "INSERT INTO #__users(name,username,email,password,block,sendEmail,registerDate,lastvisitDate)  
                          VALUES ('" . $username . "', '" . $new_email . "' , '" . $new_email . "', '" . $password . "',1,1,now(),now())";
                $db->setQuery($query);
                $db->query();
                $userId = $db->insertid();

                $query = "INSERT INTO #__core_acl_aro(section_value,value,name)  
                          VALUES ('users', '" . $userId . "' , '" . $new_email . "')";
                $db->setQuery($query);
                $db->query();
                $mapId = $db->insertid();

                $query = "INSERT INTO #__core_acl_groups_aro_map(group_id,aro_id)  
                          VALUES ('25', '" . $mapId . "')";
                $db->setQuery($query);
                $db->query();
            }
            $query = "SELECT count(user_id) 
                      FROM #__pin_user_settings 
                      WHERE email='$new_email'";
            $db->setQuery($query);
            $result = $db->loadResult();
            $count = 1;

            if ($result > 0) {

                $query = "SELECT count 
                          FROM #__pin_user_settings 
                          WHERE email='$new_email'";
                $db->setQuery($query);
                $count = $db->loadResult();
                $count = $count + 1;

                $query = "UPDATE #__pin_user_settings 
                          SET status='1'  
                          WHERE email='" . $new_email . "'";

                $db->setQuery($query);
                $db->query();
            } else {

                $query = "INSERT INTO #__pin_user_settings(user_id,first_name,last_name,email,username,count,status,created_date) 
                          VALUES ('" . $userId . "','" . $first_name . "','" . $last_name . "','" . $new_email . "','" . $username . "','1','0',NOW())";
                $db->setQuery($query);
                $db->query();
            }
            $app->redirect(JRoute::_('index.php?option=com_socialpinboard&view=home&signup=success', false), '<p class="signup_success">' . JText::_('COM_SOCIALPINBOARD_REGISTRATION_SUCCESS') . '</p>', '');
        }
    }

    function newUSerLogin($userEmail, $userName, $password) {


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
        $returnURL = JRoute::_('index.php?option=com_socialpinboard&view=home&q=0', false);
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

}

?>