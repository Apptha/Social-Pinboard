<?php

/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component mailfriends model
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');

//to get data for design details from database.
class socialpinboardModelMailfriends extends SocialpinboardModel {

    function checkUserName($user_name) {


        $db = JFactory::getDBO();
        $query = "select count(user_id) from #__pin_user_settings WHERE username='" . $user_name . "'";
        $db->setQuery($query);
        $userEmail = $db->loadResult();
        if ($userEmail == 0) {
            $a = JText::_('COM_SOCIALPINBOARD_USERNAME_AVAILBALE');
            return $a;
        } else {
            $a = JText::_('COM_SOCIALPINBOARD_USERNAME_NOT_AVAILBALE');
            return $a;
        }
    }

    function inviteFriend() {

        $conf = JFactory::getConfig();
        $output = '';
        $siteName = $conf->get('sitename');
        $mailFrom = $conf->get('mailfrom');
        $userName = $conf->get('fromname');
        $user = JFactory::getUser();
        $userId = $user->id;
        $mailer = JFactory::getMailer(); //define joomla mailer
        $app = JFactory::getApplication();
        $db = JFactory::getDBO();
        $baseurl = JURI::base();
        $query = "SELECT COUNT(pin_id) FROM  #__pin_pins WHERE  pin_user_id =$userId AND status='1'";


        $db->setQuery($query);
        $pinCount = $db->loadResult();



        $query = "SELECT COUNT(pin_likes_count) FROM  #__pin_pins WHERE  pin_user_id =$userId ";
        $db->setQuery($query);
        $likesCount = $db->loadResult();

        $query = "SELECT  pin_image,link_type,pin_id,pin_description
                  FROM #__pin_pins
                  WHERE pin_user_id=$userId order by rand() LIMIT 0,4";

        $db->setQuery($query);
        $user_pin_details = $db->loadObjectList();

        $query = "SELECT first_name FROM  #__pin_user_settings WHERE user_id =$userId ";
        $db->setQuery($query);
        $userName = $db->loadResult();

        $query = "SELECT user_image FROM  #__pin_user_settings WHERE user_id =$userId ";
        $db->setQuery($query);
        $user_image = $db->loadResult();

        if ($user_image != '') {
            $user_image = JURI::base() . 'images/socialpinboard/avatars/' . rawurlencode($user_image);
        } else {
            $user_image = JURI::Base() . '/components/com_socialpinboard/images/no_user.jpg';
        }

        $templateparams = $app->getTemplate(true)->params; // get the tempalte parameters
        $logo = $templateparams->get('logo'); //get the logo
        if ($logo != null) {
            $image_source = JURI::base() . '/' . htmlspecialchars($logo);
        } else {
            $image_source = JURI::base() . '/templates/socialpinboard/images/logo-large.png';
        }
        $note = JText::_('COM_SOCIALPINBOARD_ADD_PERSONAL_NOTE');
        $invitefriend1 = JREQUEST::getVar('emailto1');
        $invitefriend2 = JREQUEST::getVar('emailto2');
        $invitefriend3 = JREQUEST::getVar('emailto3');
        $invitefriend4 = JREQUEST::getVar('emailto4');
        $userContent = JREQUEST::getVar('content');
        if ($userContent == $note || $userContent == '') {
            $userContent = '';
        } else {
            $userContent = JREQUEST::getVar('content');
            $userContent = '<br>
                             <br>
                             ' . $userName . ' has a message for you:<br>
                                 <br><span style="font-style: italic;color: rgb(201, 128, 157);">
                              ' . $userContent . '</span>';
        }
        $checkout_head = $checkout_content = '';
        $checkout_conten = file_get_contents(JURI::base() . '/templates/socialpinboard/emailtemplate/Invitefriends_checkout.html');
        if (count($user_pin_details) > 0) {
            $checkout_head = "Check out $userName's pins:";

            for ($i = 0; $i < count($user_pin_details); $i++) {

                $link_type = $user_pin_details[$i]->link_type;
                $strImgName = $user_pin_details[$i]->pin_image;
                $pin_description = $user_pin_details[$i]->pin_description;

                if ($link_type == 'youtube' || $link_type == 'vimeo') {
                    $userpin = $strImgName;
                } else {
                    $userpin = JURI::base() . '/images/socialpinboard/pin_medium/' . $strImgName;
                }
                $result = str_replace("{baseurl}", $baseurl, $checkout_conten);
                $result = str_replace("{pin_user}", $userpin, $result);
                $result = str_replace("{pin_desc_user}", $pin_description, $result);

                $checkout_content .= $result;
            }
        }
        $sendEmails = array($invitefriend1, $invitefriend2, $invitefriend3, $invitefriend4);

        $pattern = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i";

        $mailer->setSender($mailFrom);

        foreach ($sendEmails as $sendEmail) {


            if (preg_match($pattern, $sendEmail)) {

                $subject = " Check out my stuff on " . $siteName;

                $mailer->addRecipient($sendEmail);


                $accept_invite_url = JURI::base() . 'index.php?option=com_socialpinboard&view=register';
                $inviting_user_url = JURI::base() . 'index.php?option=com_socialpinboard&view=boarddisplay&uid=' . $userId;
                $content = file_get_contents(JURI::base() . '/templates/socialpinboard/emailtemplate/Invitefriends.html');
                $content = str_replace("{baseurl}", $baseurl, $content);
                $content = str_replace("{site_logo}", $image_source, $content);
                $content = str_replace("{site_name}", $siteName, $content);
                $content = str_replace("{inviting_user_url}", $inviting_user_url, $content);
                $content = str_replace("{inviting_user_image}", $user_image, $content);
                $content = str_replace("{inviting_username}", $userName, $content);
                $content = str_replace(" {inviting_checkout_head}", $checkout_head, $content);
                $content = str_replace("{inviting_user_messsage}", $userContent, $content);
                $content = str_replace("{accept_invite_url}", $accept_invite_url, $content);
                $content = str_replace("{inviting_checkout_content}", $checkout_content, $content);

                $mailer->isHTML(true);
                $mailer->setSubject($subject);
                $mailer->Encoding = 'base64';
                $mailer->setBody($content);
                $send = $mailer->Send();

                $db = JFactory::getDBO();

                $query = "SELECT id
                    FROM #__pin_user_request
                    WHERE email_id='" . $sendEmail . "'";
                $db->setQuery($query);
                $user_request = $db->loadResult();

                if ($user_request == '') {
                    $query = "INSERT INTO `#__pin_user_request` (`email_id`,`approval_status`) VALUES ('" . $sendEmail . "','1')";
                    $db->setQuery($query);
                    $db->query();
                } else {
                    $query = "UPDATE `#__pin_user_request`  SET email_id= '" . $sendEmail . "', `approval_status`=1
                    WHERE `id`=$user_request";
                    $db->setQuery($query);
                    $db->query();
                }
                $output = JText::_('COM_SOCIALPINBOARD_INVITATION_SENT');
            }
        }
        return $output;
    }

    function checkEmail($email) {

        $db = JFactory::getDBO();
        $query = "SELECT b.block,a.status 
                  FROM #__pin_user_settings as a 
                  INNER JOIN #__users as b 
                  ON b.email=a.email 
                  WHERE b.email='" . $email . "'";

        $db->setQuery($query);
        $userEmail = $db->loadObject();
        $a = '';
        if (empty($userEmail)) {
            $a = JText::_('COM_SOCIALPINBOARD_EMAIL_AVAILABLE');
        } else if ($userEmail->block == 0 && $userEmail->status == '1') {
            $a = JText::_('COM_SOCIALPINBOARD_EMAIL_ALREADY_TAKEN');
        } else if ($userEmail->block == 1 && $userEmail->status == '0') {
            $a = JText::_('COM_SOCIALPINBOARD_EMAIL_BLOCKED');
        } else if ($userEmail->block == 0 && $userEmail->status == '0') {
            $a = JText::_('COM_SOCIALPINBOARD_EMAIL_ALREADY_TAKEN_BUT_BLOCKED');
        }
        return $a;
    }

}

?>