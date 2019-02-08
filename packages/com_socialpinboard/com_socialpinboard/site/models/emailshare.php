<?php

/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component emailshare model
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');

class socialpinboardModelEmailshare extends SocialpinboardModel {

    function requestMail() {
        $user = JFactory::getUser();
        $db = JFactory::getDBO();
        $userName = $user->name;
        $user_name = $user->name;
        $config = JFactory::getConfig();
        $siteName = $config->get('sitename');
        $mailFrom = $config->get('mailfrom');
        $email = JRequest::getVar('email');
        $recipient_name = JRequest::getVar('name');
        $body = JRequest::getVar('body');
        $pinId = JRequest::getVar('pinid');
        $pageURL = JURI::base() . 'index.php?option=com_socialpinboard&view=pin&pinid=' . $pinId;
        $app = Jfactory::getApplication();
        $templateparams = $app->getTemplate(true)->params; // get the tempalte parameters
        $logo = $templateparams->get('logo'); //get the logo

        if ($logo != null) {


            $image_source = JURI::base() . '/' . htmlspecialchars($logo);
        } else {
            $image_source = JURI::base() . '/templates/socialpinboard/images/logo-large.png';
        }
        if ($body != '') {
            $said = $userName . ' said,  "' . $body . '"';
        } else {
            $said = '';
        }
        $userId = $user->id;
        $query = "SELECT user_image FROM  #__pin_user_settings WHERE user_id =$userId ";
        $db->setQuery($query);
        $user_image = $db->loadResult();
        $query = "SELECT pin_likes_count,pin_image,link_type,pin_description FROM  #__pin_pins WHERE pin_id =$pinId ";
        $db->setQuery($query);
        $pin_details = $db->loadObject();

        $pin_name = $pin_details->pin_description;
        $pin_likes_count = $pin_details->pin_likes_count;
        $link_type1 = $pin_details->link_type;
        $strImgName1 = $pin_details->pin_image;
        if ($link_type1 == 'youtube' || $link_type1 == 'vimeo') {
            $liked_pin_image = $strImgName1;
        } else {
            $liked_pin_image = JURI::base() . '/images/socialpinboard/pin_medium/' . $strImgName1;
        }

        if ($user_image != '') {
            $user_image = JURI::base() . 'images/socialpinboard/avatars/' . rawurlencode($user_image);
        } else {
            $user_image = JURI::Base() . '/components/com_socialpinboard/images/no_user.jpg';
        }


        $mailer = JFactory::getMailer();

        $mailer->setSender($mailFrom);
        $mailer->setSubject("Pin Shared via " . $siteName);
        $mailer->addRecipient($email);

        $baseurl = JURI::base();
        $sender_url = JURI::base() . 'index.php?option=com_socialpinboard&view=boarddisplay&uid=' . $userId;
        $accept_invite_url = JURI::base() . 'index.php?option=com_socialpinboard&view=register';
        $inviting_user_url = JURI::base() . 'index.php?option=com_socialpinboard&view=boarddisplay&uid=' . $userId;
        $content = file_get_contents(JURI::base() . '/templates/socialpinboard/emailtemplate/Emailshare.html');
        $content = str_replace("{baseurl}", $baseurl, $content);
        $content = str_replace("{site_logo}", $image_source, $content);
        $content = str_replace("{site_name}", $siteName, $content);
        $content = str_replace("{recipient_name}", $recipient_name, $content);
        $content = str_replace("{sender_name}", $userName, $content);
        $content = str_replace("{sender_url}", $sender_url, $content);
        $content = str_replace("{sender_image}", $user_image, $content);
        $content = str_replace("{pin_url}", $pageURL, $content);
        $content = str_replace("{pin_name}", $pin_name, $content);
        $content = str_replace("{liked_pin_image}", $liked_pin_image, $content);
        $content = str_replace("{pin_likes_count}", $pin_likes_count, $content);
        $content = str_replace("{sender_message}", $said, $content);

        $mailer->isHTML(true);
        $mailer->Encoding = 'base64';
        $mailer->setBody($content);
        $send = $mailer->Send();
        $successMessage = 'Email Sent Successfully.';
        return $successMessage;
    }
function Invitefriends() {
        $conf = JFactory::getConfig();
        $output = '';
        $siteName = $conf->get('sitename');
        $mailFrom = $conf->get('mailfrom');
        $userName = $conf->get('fromname');
        $user = JFactory::getUser();
        $userId = $user->id;
        $baseurl = JURI::base();
        $mailer = JFactory::getMailer(); //define joomla mailer
        $app = JFactory::getApplication();
        $db = JFactory::getDBO();
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
$sendEmail = JREQUEST::getVar('email');
$recipient_name=JREQUEST::getVar('name');
$userContent = JREQUEST::getVar('body');
        if ($userContent == 'Add Personal Note (Optional)' || $userContent=='') {
            $userContent = '';
        } else {
            $userContent = JREQUEST::getVar('body');
            $userContent = '<br>
                             <br>
                             ' . $userName . ' has a message for you:<br>
                                 <br><span style="font-style: italic;color: rgb(201, 128, 157);">
                              ' . $userContent . '</span>';
        }
        $subject = " Check out my stuff on " . $siteName;

                $mailer->addRecipient($sendEmail);


                $accept_invite_url = JURI::base() . 'index.php?option=com_socialpinboard&view=register';
                $inviting_user_url = JURI::base() . 'index.php?option=com_socialpinboard&view=boarddisplay&uid=' . $userId;
                $content = file_get_contents(JURI::base() . '/templates/socialpinboard/emailtemplate/Invitefriends.html');
                $content = str_replace("{baseurl}", $baseurl, $content);
                $content = str_replace("{site_logo}", $image_source, $content);
                $content = str_replace("{site_name}", $siteName, $content);
                $content = str_replace("{recipient_name}", $recipient_name, $content);
                $content = str_replace("{inviting_user_url}", $inviting_user_url, $content);
                $content = str_replace("{inviting_user_image}", $user_image, $content);
                $content = str_replace("{inviting_username}", $userName, $content);
                $content = str_replace("{inviting_user_messsage}", $userContent, $content);
                $content = str_replace(" {inviting_checkout_head}", $checkout_head, $content);
                $content = str_replace("{accept_invite_url}", $accept_invite_url, $content);
                $content = str_replace("{inviting_checkout_content}", $checkout_content, $content);

                $mailer->isHTML(true);
                $mailer->setSubject($subject);
                $mailer->Encoding = 'base64';
                $mailer->setBody($content);
                $send = $mailer->Send();
        $successMessage = 'Email Sent Successfully.';
        return $successMessage;
    }
    //function to send report
    function sendReport() {
        $config = JFactory::getConfig();
        $siteName = $config->get('sitename');
        $fromname = $config->get('fromname');
        $adminEmail = $config->get('mailfrom');
        $reportName = JREQUEST::getVar('report');
        $pinId = JRequest::getVar('pinid');
        $user = JFactory::getUser();
        $userEmail = $user->email;
        $pageURl = JURI::base() . 'index.php?option=com_socialpinboard&view=pin&pinid=' . $pinId;
        $userName = $user->name;
        $app = Jfactory::getApplication();
        $templateparams = $app->getTemplate(true)->params; // get the tempalte parameters
        $logo = $templateparams->get('logo'); //get the logo
        if ($logo != null) {
            $image_source = JURI::base() . '/' . htmlspecialchars($logo);
        } else {
            $image_source = JURI::base() . '/templates/socialpinboard/images/logo-large.png';
        }
        $mailer = JFactory::getMailer();

        $db = JFactory::getDBO();

        $userId = $user->id;
        $query = "SELECT user_image FROM  #__pin_user_settings WHERE user_id =$userId ";
        $db->setQuery($query);
        $user_image = $db->loadResult();
        $query = "SELECT pin_likes_count,pin_image,link_type,pin_description FROM  #__pin_pins WHERE pin_id =$pinId ";
        $db->setQuery($query);
        $pin_details = $db->loadObject();

        $pin_name = $pin_details->pin_description;
        $pin_likes_count = $pin_details->pin_likes_count;
        $link_type1 = $pin_details->link_type;
        $strImgName1 = $pin_details->pin_image;
        if ($link_type1 == 'youtube' || $link_type1 == 'vimeo') {
            $reported_pin_image = $strImgName1;
        } else {
            $reported_pin_image = JURI::base() . '/images/socialpinboard/pin_medium/' . $strImgName1;
        }

        if ($user_image != '') {
            $user_image = JURI::base() . 'images/socialpinboard/avatars/' . rawurlencode($user_image);
        } else {
            $user_image = JURI::Base() . '/components/com_socialpinboard/images/no_user.jpg';
        }

        $mailer->setSender($userEmail);
        $mailer->setSubject("Report on SocialPin Board");
        $mailer->addRecipient($adminEmail);

        $baseurl = JURI::base();
        $sender_url = JURI::base() . 'index.php?option=com_socialpinboard&view=boarddisplay&uid=' . $userId;
        $accept_invite_url = JURI::base() . 'index.php?option=com_socialpinboard&view=register';
        $inviting_user_url = JURI::base() . 'index.php?option=com_socialpinboard&view=boarddisplay&uid=' . $userId;
        $content = file_get_contents(JURI::base() . '/templates/socialpinboard/emailtemplate/reportpin.html');
        $content = str_replace("{baseurl}", $baseurl, $content);
        $content = str_replace("{site_logo}", $image_source, $content);
        $content = str_replace("{site_name}", $siteName, $content);
        $content = str_replace("{report_type}", $reportName, $content);
        $content = str_replace("{sender_name}", $userName, $content);
        $content = str_replace("{sender_url}", $sender_url, $content);
        $content = str_replace("{sender_image}", $user_image, $content);
        $content = str_replace("{pin_url}", $pageURl, $content);
        $content = str_replace("{pin_name}", $pin_name, $content);
        $content = str_replace("{reported_pin_image}", $reported_pin_image, $content);
        $content = str_replace("{pin_likes_count}", $pin_likes_count, $content);

        $mailer->isHTML(true);
        $mailer->Encoding = 'base64';
        $mailer->setBody($content);
        $send = $mailer->Send();
        echo 'Report sent to administrator';
    }

    function sendReporttype() {
        $db = $this->getDBO();
        $config = JFactory::getConfig();
        $siteName = $config->get('sitename');
        $fromname = $config->get('fromname');
        $adminEmail = $config->get('mailfrom');
        $reportName = JRequest::getVar('reporttype');
        $userId = JRequest::getInt('uservalue');
        $user = JFactory::getUser();
        $user_id = $user->id;
        $queryString = "select * from #__pin_user_settings where user_id = $user_id";
        $db->setQuery($queryString);
        $user_Details = $db->loadObjectList();

        $queryString = "select username from #__pin_user_settings where user_id = $userId";
        $db->setQuery($queryString);
        $report_user_Details = $db->loadResult();

        $user_image = $user_Details[0]->user_image;
        if ($user_image != '') {
            $user_image = JURI::base() . 'images/socialpinboard/avatars/' . rawurlencode($user_image);
        } else {
            $user_image = JURI::Base() . '/components/com_socialpinboard/images/no_user.jpg';
        }


        $user_Email = $user_Details[0]->email;
        $pageURl = JURI::base() . 'index.php?option=com_socialpinboard&view=boarddisplay&uid=' . $userId;
        $user_Name = $user_Details[0]->username;
        $report = 1;
        $app = Jfactory::getApplication();
        $templateparams = $app->getTemplate(true)->params; // get the tempalte parameters
        $logo = $templateparams->get('logo'); //get the logo
        if ($logo != null) {
            $image_source = JURI::base() . '/' . htmlspecialchars($logo);
        } else {
            $image_source = JURI::base() . '/templates/socialpinboard/images/logo-large.png';
        }
        $mailer = JFactory::getMailer();
        $mailer->setSender($user_Email);
        $mailer->setSubject("Report on SocialPin Board");
        $mailer->addRecipient($adminEmail);

        $baseurl = JURI::base();
        $sender_url = JURI::base() . 'index.php?option=com_socialpinboard&view=boarddisplay&uid=' . $user_id;
        $accept_invite_url = JURI::base() . 'index.php?option=com_socialpinboard&view=register';
        $report_user_url = JURI::base() . 'index.php?option=com_socialpinboard&view=boarddisplay&uid=' . $userId;
        $content = file_get_contents(JURI::base() . '/templates/socialpinboard/emailtemplate/reportuser.html');
        $content = str_replace("{baseurl}", $baseurl, $content);
        $content = str_replace("{site_logo}", $image_source, $content);
        $content = str_replace("{site_name}", $siteName, $content);
        $content = str_replace("{report_type}", $reportName, $content);
        $content = str_replace("{report_user_name}", $report_user_Details, $content);
        $content = str_replace("{report_user_url}", $report_user_url, $content);
        $content = str_replace("{sender_name}", $user_Name, $content);
        $content = str_replace("{sender_name}", $user_Name, $content);
        $content = str_replace("{sender_url}", $sender_url, $content);
        $content = str_replace("{sender_image}", $user_image, $content);

        $query = $db->setQuery("SELECT COUNT(*) FROM #__pin_userreports WHERE report_receiver=" . $userId . " AND report_sender=" . $user_id);
        $result = $db->loadResult();
        if ($result == 0) {
            $mailer->isHTML(true);
            $mailer->Encoding = 'base64';
            $mailer->setBody($content);
            $send = $mailer->Send();
            echo 'Report sent to administrator';
            $queryreport = "INSERT INTO `#__pin_userreports` (`id`,`report_sender`, `report_receiver`) VALUES ('', $user_id, $userId)";
            $db->setQuery($queryreport);
            if (!$db->query()) {
                $this->setError($db->getErrorMsg());
                return false;
            }
        } else {
            echo 'User Reported Already';
        }
    }

}

?>
