<?php

/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component resetpassword model
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');
jimport('joomla.user.helper');

class socialpinboardModelResetPassword extends SocialpinboardModel {

    public function resetPassword($email) {
//to get data for design details from database.
        $mailer = JFactory::getMailer();
        $app=Jfactory::getApplication();
        $db =  JFactory::getDBO();
        $query = "select name,password,id,block from #__users where email='$email'";
        $db->setQuery($query);
        $user = $db->loadObject();
        if($user){
        $userId = $user->id;
        $userpassword=$user->password;
           $username=$user->name;
        if (preg_match("/^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$/i", $email)  && $userId != '' && $userId != '0' ) {

            if ($user->block) {
                $this->setError(JText::_('User is Blocked'));
                return false;
            }
            if (!empty($user)) {
                $token = JApplication::getHash(JUserHelper::genRandomPassword());
                $salt = JUserHelper::getSalt('crypt-md5');
                $hashedToken = md5($token . $salt) . ':' . $salt;
            }

            $query = "UPDATE #__users SET activation = '$token' where email='$email'";
            $db->setQuery($query);
            $db->query();

                    $config =  JFactory::getConfig();
                    $sender = $config->get('mailfrom');
                    $site_name = $config->get('sitename');
                    $mailer->setSender($sender);
                    $mailer->setSubject('Reset Password');
                    //set recipient
                    $recipient = $email;
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
$resetUrl= JURI::base().'index.php?option=com_socialpinboard&view=userlogin&email='.$email.'&reset='.$token;

            $baseurl = JURI::base();
            $body = file_get_contents(JURI::base() . '/templates/socialpinboard/emailtemplate/resetpassword.html');
            $body = str_replace("{baseurl}", $baseurl, $body);
            $body = str_replace("{site_name}", $site_name, $body);
            $body = str_replace("{site_logo}", $image_source, $body);
            $body = str_replace("{username}", $username, $body);
            $body = str_replace("{activationlink}", $resetUrl, $body);

                    $mailer->isHTML(true);
                    $mailer->Encoding = 'base64';
                    $mailer->setBody($body);
            $send = $mailer->Send();

//ends
            $successMessage = "1";
        } else {
            $successMessage = "0";
        }
        return $successMessage;
        }
    }

    //Search the user from the table

    public function getUser($email) {
        $db =  JFactory::getDBO();
        $query = "select id,block from #__users where email='$email'";
        $db->setQuery($query);
        $user = $db->loadObject();
        return $user;
    }

}