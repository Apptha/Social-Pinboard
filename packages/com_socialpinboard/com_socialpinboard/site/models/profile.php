<?php

/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component profile model
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');
jimport('joomla.user.helper');
jimport('joomla.filesystem.file');
jimport('joomla.database.database.mysqli');

//to get data for design details from database.
class socialpinboardModelprofile extends SocialpinboardModel {

    //function to get user profile
    function getProfile() {
        $user = JFactory::getUser();
        $memberId = $user->get('id');
        $db = $this->getDBO();
        $query = "SELECT `pin_user_settings_id`, `user_id`, `first_name`, `last_name`, `email`, `username`, `about`, `location`, `website`, `user_image`
                  FROM #__pin_user_settings where user_id = $memberId";
        $db->setQuery($query);
        $userDetails = $db->loadObjectList(); 
        return $userDetails;
    }

    function updateProfile() {

        $file = JRequest::getVar('user_image', null, 'files', 'array');
        $userImage = '';

        $user = JFactory::getUser();
        $user_id = $user->id;
        if ($file['name'] != '') {

            //my code
            $image = JFile::makeSafe($file['name']);
            $uploadedfile = $file['tmp_name'];
            $info = getimagesize($uploadedfile);
            $userImageDetails = pathinfo($image);
            $extension = strtolower($userImageDetails['extension']);
            $userImage = $userImageDetails['filename'] . $user_id . '.' . $userImageDetails['extension'];

            if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {

                $change = '<div class="msgdiv">Unknown Image extension </div> ';
                $errors = 1;
            } else {
                ini_set('memory_limit', '-1');
                $size = filesize($file['tmp_name']);
                switch ($info[2]) {
                    case IMAGETYPE_GIF:
                        $uploadedfile = $file['tmp_name'];
                        $src = imagecreatefromgif($uploadedfile);
                        break;
                    case IMAGETYPE_JPEG:
                        $uploadedfile = $file['tmp_name'];
                        $src = imagecreatefromjpeg($uploadedfile);
                        break;
                    case IMAGETYPE_PNG:
                        $uploadedfile = $file['tmp_name'];
                        $src = imagecreatefrompng($uploadedfile);
                        break;
                }

                list($width, $height) = getimagesize($uploadedfile);
                $newwidth = $width;
                $newheight = $height;
                $tmp = imagecreatetruecolor($newwidth, $newheight);
                $newwidth1 = 50;
                $newheight1 = 50;
                $tmp1 = imagecreatetruecolor($newwidth1, $newheight1);
                if ($extension == "png" || $extension == "gif") {

                    imagealphablending($tmp, false);

                    // Create a new transparent color for image
                    $color = imagecolorallocatealpha($tmp, 0, 0, 0, 127);

                    // Completely fill the background of the new image with allocated color.
                    imagefill($tmp, 0, 0, $color);

                    // Restore transparency blending
                    imagesavealpha($tmp, true);
                    imagealphablending($tmp1, false);

                    // Create a new transparent color for image
                    $color = imagecolorallocatealpha($tmp1, 0, 0, 0, 127);

                    // Completely fill the background of the new image with allocated color.
                    imagefill($tmp1, 0, 0, $color);

                    // Restore transparency blending
                    imagesavealpha($tmp1, true);
                }
                imagecopyresampled($tmp, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

                imagecopyresampled($tmp1, $src, 0, 0, 0, 0, $newwidth1, $newheight1, $width, $height);

                $filename = JPATH_SITE . DS . "images" . DS . "socialpinboard" . DS . "avatars" . DS . $userImageDetails['filename'] . $user_id . '_o.' . $userImageDetails['extension'];
                switch ($info[2]) {
                    case IMAGETYPE_GIF:
                        imagegif($tmp, $filename);
                        break;
                    case IMAGETYPE_JPEG:
                        imagejpeg($tmp, $filename);
                        break;
                    case IMAGETYPE_PNG:
                        imagepng($tmp, $filename);
                        break;
                }

                $filename1 = JPATH_SITE . DS . "images" . DS . "socialpinboard" . DS . "avatars" . DS . $userImageDetails['filename'] . $user_id . '.' . $userImageDetails['extension'];
                switch ($info[2]) {
                    case IMAGETYPE_GIF:
                        imagegif($tmp1, $filename1);
                        break;
                    case IMAGETYPE_JPEG:
                        imagejpeg($tmp1, $filename1);
                        break;
                    case IMAGETYPE_PNG:
                        imagepng($tmp1, $filename1);
                        break;
                }

                imagedestroy($src);
                imagedestroy($tmp);
                imagedestroy($tmp1);
            }
        }
        //ends

        $db = $this->getDBO();
        $user = JFactory::getUser();
        $memberId = $user->get('id');
        $firstName = addslashes(JRequest::getVar('first_name'));
        $lastName = addslashes(JRequest::getVar('last_name'));
        $about = addslashes(JRequest::getVar('about', '', 'POST', 'string', JREQUEST_ALLOWRAW));

        $location = JRequest::getVar('location');
        $website = JRequest::getVar('website');
        $oldPassword = JRequest::getVar('old_password');

        $newPassword = JRequest::getVar('new_password');
        $confirmPassword = JRequest::getVar('confirm_password');
        if ($confirmPassword != '') {

            $query = "SELECT password from #__users WHERE id=$memberId";

            $db->setQuery($query);
            $dbPassword = $db->loadResult();
            $parts = explode(':', $dbPassword);
            $crypt = $parts[0];
            $salt = @$parts[1];
            $testcrypt = JUserHelper::getCryptedPassword($oldPassword, $salt);

            if ($testcrypt != $crypt) {
                $app = JFactory::getApplication();
                $app->redirect(JRoute::_('index.php?option=com_socialpinboard&view=profile'), $msg = "Current Password Mismatch");
            }

            if ($newPassword == $confirmPassword) {
                $salt = JUserHelper::genRandomPassword(32);
                $crypt = JUserHelper::getCryptedPassword("$confirmPassword", $salt);
                $confirmPassword = $crypt . ':' . $salt;
                $userquery = "update #__users set name='$firstName' '$lastName',password='$confirmPassword' where id=$memberId";
                $db->setQuery($userquery);
                $db->query();
            } else {
                $app = JFactory::getApplication();
                $app->redirect(JRoute::_('index.php?option=com_socialpinboard&view=profile'), $msg = "Password Doesnot Match.");
            }
        }
        $db = $this->getDBO();
        if ($userImage != '') {
            $query = "update #__pin_user_settings set first_name='$firstName',last_name='$lastName',about=$db->quote('$about'),location='$location',website='$website',user_image='$userImage' where user_id=$memberId";
            $db->setQuery($query);
            $db->query();
        } else {
            $query = "update #__pin_user_settings set first_name='$firstName',last_name='$lastName',about=$db->quote('$about'),location='$location',website='$website' where user_id=$memberId";
            $db->setQuery($query);
            $db->query();
        }

        $app = JFactory::getApplication();
        $app->redirect(JRoute::_('index.php?option=com_socialpinboard&view=profile'), $msg = "Profile Updated Successfully", '');
    }

    function deleteAccount() {
        $db = $this->getDBO();

        //get the current user details
        $user = JFactory::getUser();
        $userId = $user->id;
        $btnDelpin = JRequest::getVar('delete_account');
        $app = JFactory::getApplication();
        if (isset($btnDelpin)) {

            $query = 'UPDATE `#__pin_user_settings` SET `status`=0,`published`=-2 WHERE `user_id`=' . $userId;

            $db->setQuery($query);
            $db->query();
            $query = 'UPDATE `#__users` SET `block`=1 WHERE `id`=' . $userId;
            $db->setQuery($query);
            $db->query();

            $query = 'UPDATE `#__pin_pins` SET `status`=0,`published`=-2 WHERE `pin_user_id`=' . $userId;
            $db->setQuery($query);
            $db->query();

            if (isset($_SERVER['HTTP_COOKIE'])) {
                $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
                foreach ($cookies as $cookie) {
                    $parts = explode('=', $cookie);
                    $name = trim($parts[0]);
                    setcookie($name, '', time() - 1000);
                    setcookie($name, '', time() - 1000, '/');
                }
            }

            $app->redirect(JRoute::_('index.php?option=com_socialpinboard&view=people'));
        }
    }

}

?>