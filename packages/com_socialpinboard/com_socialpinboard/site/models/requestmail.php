<?php

/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component requestmail model
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');

class socialpinboardModelRequestmail extends SocialpinboardModel {

    function requestMail() {
        $conf = JFactory::getConfig();

        $siteName = $conf->get('sitename');
        $mailFrom = $conf->get('mailfrom');
        $email = JREQUEST::getVar('email');
        $mailer = JFactory::getMailer(); //define joomla mailer

        $app = JFactory::getApplication();
        $db = JFactory::getDbo();
        $query = "SELECT count(id) from #__users WHERE email='$email' AND block='0' ";
        $db->setQuery($query);
        $joomlaUserResult = $db->loadResult();

        //check whether the admin wants the request approval or not

        $query = "SELECT setting_request_approval FROM #__pin_site_settings"; //update query for publish and unpublish
        $db = $this->getDBO();
        $db->setQuery($query);
        $approval = $db->loadResult();


        $query = "SELECT count(user_id) from #__pin_user_settings WHERE email='$email' AND status='1' ";
        $db->setQuery($query);
        $socialUserResult = $db->loadResult();

        if ($joomlaUserResult == '1' && $socialUserResult == '1') {
            $successMessage = 'This Email is already registered with us';
            return $successMessage;
        } else if ($joomlaUserResult == '1' && $socialUserResult == '0') {
            $successMessage = 'Please Contact the Site Owner. This Email has been blocked.';
            return $successMessage;
        } else {


            $db = JFactory::getDbo();
            $query = "SELECT count(id) from #__pin_user_request WHERE email_id='$email'";

            $db->setQuery($query);
            $userInvited = $db->loadResult();

            if ($userInvited == '1') {
                $successMessage = 'The Request for this Email has already been submitted to the Site Administrator';
                return $successMessage;
            } else {


                //insert the approval into the user
                $db = JFactory::getDbo();
                $query = "INSERT INTO `#__pin_user_request` (`email_id`,`approval_status`) VALUES ('" . $email . "','0')";
                $db->setQuery($query);
                $db->query();

                $templateparams = $app->getTemplate(true)->params; // get the tempalte parameters
                $logo = $templateparams->get('logo'); //get the logo
                if ($logo != null) {
                    $image_source = JURI::base() . '/' . htmlspecialchars($logo);
                } else {
                    $image_source = JURI::base() . '/templates/socialpinboard/images/logo-large.png';
                }

                $mailer->setSender($mailFrom);
                $mailer->addRecipient($email);

                if ($approval == '0') {
//Activation Mail for the approval
                    $db = JFactory::getDbo();
                    $query = "UPDATE `#__pin_user_request` SET  `approval_status`='1' WHERE `email_id` = '" . $email . "'";
                    $db->setQuery($query);
                    $db->query();
                    $inviteId = $db->insertid();
                    $activationlink = JURI::base() . 'index.php?option=com_socialpinboard&view=register';
                    $baseurl = JURI::base();
                    $subject = "Your request has been accepted to join " . $siteName . " ";
                    $content = file_get_contents(JURI::base() . '/templates/socialpinboard/emailtemplate/requestapproval.html');
                    $content = str_replace("{baseurl}", $baseurl, $content);
                    $content = str_replace("{site_logo}", $image_source, $content);
                    $content = str_replace("{site_name}", $siteName, $content);
                    $content = str_replace("{activationlink}", $activationlink, $content);
                } else {

                    $baseurl = JURI::base();
                    $subject = "Your request has been received to join " . $siteName . " ";
                    $content = file_get_contents(JURI::base() . '/templates/socialpinboard/emailtemplate/requestinvite.html');
                    $content = str_replace("{baseurl}", $baseurl, $content);
                    $content = str_replace("{site_logo}", $image_source, $content);
                    $content = str_replace("{site_name}", $siteName, $content);
                }

                $mailer->isHTML(true);
                $mailer->setSubject($subject);
                $mailer->Encoding = 'base64';
                $mailer->setBody($content);
                $send = $mailer->Send();

                $successMessage = 'Thanks! We will send invite soon.';
                return $successMessage;
            }
        }
    }

}

?>