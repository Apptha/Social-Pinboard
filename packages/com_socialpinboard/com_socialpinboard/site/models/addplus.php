<?php
/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component addplus model
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');
jimport('joomla.user.helper');

class socialpinboardModeladdplus extends SocialpinboardModel {

    function getUsergroup() {
        $user = JFactory::getUser();
        $memberId = $user->get('id');
        $db = $this->getDBO();
        $query = "SELECT group_id FROM `#__user_usergroup_map` WHERE user_id = $memberId";
        $db->setQuery($query);
        $userGroup = $db->loadResult();
        return $userGroup;
    }
    function getCategories() {
        $user = JFactory::getUser();
        $memberId = $user->get('id');
        if ($memberId != '0') {
            $db = $this->getDBO();
            $query = "select category_id,category_name from #__pin_categories where status = 1 order by category_name";
            $db->setQuery($query);
            $categories = $db->loadObjectList();//print_r($categories);
            return $categories;
        }
    }

    function getcurrency() {
        $db = $this->getDBO();
        $query = "SELECT setting_currency from #__pin_site_settings";
        $db->setQuery($query);
        $currency = $db->loadResult();
        return $currency;
    }

    function getBoardname() {
        $user = JFactory::getUser();
        $memberId = $user->get('id');
        if ($memberId != '0') {
            $db = $this->getDBO();
            $query = "select user_id,board_id,board_name,cuser_id from #__pin_boards where status=1 and (user_id=" . $memberId . " OR cuser_id LIKE '%$memberId%')";
            $db->setQuery($query);
            $boardsName = $db->loadObjectList();
            return $boardsName;
        }
    }
    

    /* function to save channel */

    function saveBoard() {
        if (JRequest::getVar('board_category_id')) {
            $user = JFactory::getUser();
           $db = $this->getDBO();
            $memberId = $user->get('id');
            JRequest::setVar('user_id', $memberId, 'post');
            $btnBoard = JRequest::getVar('btnBoard');
            $board_name = JRequest::getVar('board_name');
            $contributers = JRequest::getVar('id_contributers');
            $board_name = addslashes($board_name);
            $board_description = addslashes(JRequest::getVar('board_description'));
            $board_category_id = JRequest::getVar('board_category_id');
            $board_access = JRequest::getVar('board_access');
            $app = JFactory::getApplication();


            if (isset($btnBoard)) {
                $query = "select board_id from #__pin_boards where board_name='$board_name' and status=1 and user_id='$memberId'";

                $db->setQuery($query);
                $pin_user_info = $db->loadObjectList();

                if (empty($pin_user_info)) {

                    $query = "INSERT INTO `#__pin_boards` (`board_id`, `user_id`, `board_name`, `board_description`, `board_category_id`, `board_access`, `status`,`cuser_id`, `created_date`, `updated_date`)
            VALUES (NULL, '$memberId', '$board_name', '$board_description', '$board_category_id', ' $board_access', '1', '$contributers',NOW(), '');";
                    $db->setQuery($query);
                    $InsertBoard = $db->query();
                    $last_insert_id = $db->insertid();

                    $query = "select user_id from #__pin_follow_users where status =1 and follow_user_id='$memberId'";
                    $db->setQuery($query);
                    $follow_user = $db->loadResult();

                    if ($follow_user) {


                        $query = "select board_id from #__pin_boards where status=1 and user_id='$memberId'";
                        $db->setQuery($query);
                        $boardids = $db->loadResultArray();
                        $val = implode(',', $boardids);


                        $query1 = "UPDATE #__pin_follow_users SET `follow_user_board`= '$val' WHERE user_id = '$follow_user'";
                        $db->setQuery($query1);
                        $InsertBoard1 = $db->query();
                    }
                    $app->redirect(JRoute::_('index.php?option=com_socialpinboard&view=addplus&bId=' . $last_insert_id));
                } else {
                    $app->redirect(JRoute::_('index.php?option=com_socialpinboard&view=addplus'), "Board Name Already Exists", $msgType = 'message');
                }
            }
        }
    }

    function uploadPin() {

        if (JRequest::getVar('uploadPin')) {

            $category_id = JRequest::getVar('category_upload');
            $uploaddir = JPATH_BASE . DS . 'modules' . DS . 'mod_socialpinboard_menu' . DS . 'images' . DS . 'socialpinboard' . DS . 'temp' . DS;

            $userImage = $uploaddir . JRequest::getVar('image_id');
            $info = getimagesize($userImage);
            //my code
            $image = $uploaddir . JRequest::getVar('image_id');
            $uploadedfile = $uploaddir . JRequest::getVar('image_id');
            $userImageDetails = pathinfo($image);

            $extension = strtolower($userImageDetails['extension']);
            $user = JFactory::getUser();
            $db = $this->getDBO();
            $memberId = $user->get('id');
            $btnBoard = JRequest::getVar('uploadPin');
            $BoardName = JRequest::getVar('upload_board');
            $pin_desc = JRequest::getVar('pin_desc');
            $gift = 0;
            $price = '';
            $value = (explode(" ", $pin_desc));

            $count = count($value);
            $query = "SELECT setting_currency from #__pin_site_settings";
            $db->setQuery($query);
            $currency = $db->loadResult();

            for ($i = 0; $i <= $count - 1; $i++) {
                if (preg_match("/^[" . $currency . "]|[0-9]+[.]+$/", $value[$i])) {
                    $price = $value[$i];
                    if ($price != '' && $price) {
                        $query = "SELECT setting_currency from #__pin_site_settings";
                        $db->setQuery($query);
                        $currency = $db->loadResult();

                        if (preg_match("/[" . $currency . "]/", $price)) {
                            $query = "SELECT setting_currency from #__pin_site_settings";
                            $db->setQuery($query);
                            $currency = $db->loadResult();
                            $is_price = explode($currency, $price);
                            if (is_numeric($is_price[1])) {
                                $gift = '1';
                            }
                        }
                    } else {
                        $gift = '0';
                    }
                }
            }


            $btnBoard = JRequest::getVar('uploadPin');
            $app = JFactory::getApplication();
            if (isset($btnBoard)) {
                $select = "select board_category_id from #__pin_boards where board_id=" . addslashes($BoardName);
                $db->setQuery($select);
                $catid = $db->loadObjectList();
                $cater = $catid[0]->board_category_id;


                ini_set('memory_limit', '-1');
                $size = filesize($uploadedfile);
                list($width, $height, $type) = getimagesize($uploadedfile);
                if ($type == 2) {

                    $src = imagecreatefromjpeg($uploadedfile);
                } else if ($type == 3) {

                    $src = imagecreatefrompng($uploadedfile);
                } else if ($type == 1) {

                    $src = imagecreatefromgif($uploadedfile);
                }
                $newwidth = $width;
                $newheight = $height;
                $tmp = imagecreatetruecolor($newwidth, $newheight);


                $newwidth1 = 60;
                $newheight1 = 60;
                $tmp1 = imagecreatetruecolor($newwidth1, $newheight1);

                $newwidth2 = $width;
                if (($width > '192') || ($width == '192')) {
                    $newwidth2 = '220';
                }
                $newheight2 = ($height / $width) * $newwidth2;
                $tmp2 = imagecreatetruecolor($newwidth2, $newheight2);

                if ($type == 3 || $type == 1) {
                    $color = imagecolorallocatealpha($tmp, 0, 0, 0, 127);
                    imagefill($tmp, 0, 0, $color);
                    imagesavealpha($tmp, true);
                    imagealphablending($tmp, false);
                    $color = imagecolorallocatealpha($tmp2, 0, 0, 0, 127);
                    imagefill($tmp2, 0, 0, $color);
                    imagesavealpha($tmp2, true);
                    imagealphablending($tmp1, false);
                    $color = imagecolorallocatealpha($tmp1, 0, 0, 0, 127);
                    imagefill($tmp1, 0, 0, $color);
                    imagesavealpha($tmp1, true);
                }
                $query = "SELECT website from #__users where id = $memberId";
                $db->setQuery($query);
                $website = $db->loadResult();
                $queryInsert = 'INSERT INTO `#__pin_pins` ( `pin_board_id`, `pin_user_id`, `pin_category_id`, `pin_description`,`pin_url`,`price`,`gift`,`created_date`,`updated_date`)
                           VALUES ("' . addslashes($BoardName) . '", "' . $memberId . '","' . $category_id . '","' . addslashes($pin_desc) . '", "' . $website . '","' . $price . '","' . $gift . '",NOW(),NOW());';

                $db->setQuery($queryInsert);
                $InsertPin = $db->query();
                $last_insert_id = $db->insertid();

                imagecopyresampled($tmp, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
                imagecopyresampled($tmp1, $src, 0, 0, 0, 0, $newwidth1, $newheight1, $width, $height);
                imagecopyresampled($tmp2, $src, 0, 0, 0, 0, $newwidth2, $newheight2, $width, $height);
                $filename = JPATH_SITE . DS . "images" . DS . "socialpinboard" . DS . "pin_original" . DS . $last_insert_id . '.' . $extension;
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
                $filename1 = JPATH_SITE . DS . "images" . DS . "socialpinboard" . DS . "pin_thumb" . DS . $last_insert_id . '.' . $extension;
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
                $filename2 = JPATH_SITE . DS . "images" . DS . "socialpinboard" . DS . "pin_medium" . DS . $last_insert_id . '.' . $extension;
                switch ($info[2]) {
                    case IMAGETYPE_GIF:
                        imagegif($tmp2, $filename2);
                        break;
                    case IMAGETYPE_JPEG:
                        imagejpeg($tmp2, $filename2);
                        break;
                    case IMAGETYPE_PNG:
                        imagepng($tmp2, $filename2);
                        break;
                }

                imagedestroy($src);
                imagedestroy($tmp);
                imagedestroy($tmp1);
                imagedestroy($tmp2);



                $image_name = $last_insert_id . '.' . $extension;
                $rgbcolor = '';
                $PREVIEW_WIDTH = 150;  //WE HAVE TO RESIZE THE IMAGE, BECAUSE WE ONLY NEED THE MOST SIGNIFICANT COLORS.
                $PREVIEW_HEIGHT = 150;

                $size = GetImageSize($filename);
                $scale = 1;
                if ($size[0] > 0)
                    $scale = min($PREVIEW_WIDTH / $size[0], $PREVIEW_HEIGHT / $size[1]);
                if ($scale < 1) {
                    $width = floor($scale * $size[0]);
                    $height = floor($scale * $size[1]);
                } else {
                    $width = $size[0];
                    $height = $size[1];
                }
                $image_resized = imagecreatetruecolor($width, $height);
                if ($size[2] == 1)
                    $image_orig = imagecreatefromgif($filename);
                if ($size[2] == 2)
                    $image_orig = imagecreatefromjpeg($filename);
                if ($size[2] == 3)
                    $image_orig = imagecreatefrompng($filename);
                imagecopyresampled($image_resized, $image_orig, 0, 0, 0, 0, $width, $height, $size[0], $size[1]); //WE NEED NEAREST NEIGHBOR RESIZING, BECAUSE IT DOESN'T ALTER THE COLORS
                $im = $image_resized;
                $imgWidth = imagesx($im);
                $imgHeight = imagesy($im);
                $image_granularity = 5;
                for ($y = 0; $y < $imgHeight; $y+=$image_granularity) {
                    for ($x = 0; $x < $imgWidth; $x += $image_granularity) {
                        $index = imagecolorat($im, $x, $y);
                        $Colors = imagecolorsforindex($im, $index);
                        $Colors['red'] = intval((($Colors['red']) + 15) / 32) * 32;    //ROUND THE COLORS, TO REDUCE THE NUMBER OF COLORS, SO THE WON'T BE ANY NEARLY DUPLICATE COLORS!
                        $Colors['green'] = intval((($Colors['green']) + 15) / 32) * 32;
                        $Colors['blue'] = intval((($Colors['blue']) + 15) / 32) * 32;
                        if ($Colors['red'] >= 256)
                            $Colors['red'] = 240;
                        if ($Colors['green'] >= 256)
                            $Colors['green'] = 240;
                        if ($Colors['blue'] >= 256)
                            $Colors['blue'] = 240;
                        $imgcolors[] = substr("0" . dechex($Colors['red']), -2) . substr("0" . dechex($Colors['green']), -2) . substr("0" . dechex($Colors['blue']), -2);
                    }
                }
                $imgcolors = array_unique($imgcolors);

                foreach ($imgcolors as $key => $value) {

                    $rgbcolor .= $value . ",";
                }

                //$rgbcolor = str_replace('#','',modsocialpinboard_menu::rgb2html($color['red'],$color['green'],$color['blue']));
                $queryUpdate = 'UPDATE  `#__pin_pins` SET `pin_image` ="' . $image_name . '",rgbcolor = "' . $rgbcolor . '" where pin_id = "' . $last_insert_id . '" ;';
                $db->setQuery($queryUpdate);
                $updatePinImage = $db->query();


                //ends here
                $app->redirect(JRoute::_('index.php?option=com_socialpinboard&view=boarddisplay&pId=' . $BoardName));
            }
        }
    }

    function addPins() {
        if (JRequest::getVar('pin_board')) {
            $user = JFactory::getUser();
            $category_id = JRequest::getVar('categories');
            $db = $this->getDBO();
            $srcimg = JRequest::getVar('srcimg');
            $app = JFactory::getApplication();
            $memberId = $user->get('id');
            $btn = JRequest::getVar('add_pin');
            $dropBoard = JRequest::getVar('pin_board');
            $txtarea = JRequest::getVar('txtpin');
            $youtubeurl = JRequest::getVar('ScrapePinInput');
                if (preg_match("#https?://#", $youtubeurl) === 0) {
                    $youtubeurl = 'http://' . $youtubeurl;
                }
                //Added by Sathish to check the url is vimeo or not
                //start
                $targetstring_1 = 'youtube'; //stores the youtube value to be find in the url
                $targetstring_2 = 'vimeo';//stores the vimeo value to be find in the url
                $youtube_check = strstr($youtubeurl, $targetstring_1);//it used to check the url has youtube string in url or not
                $vimeo_check = strstr($youtubeurl, $targetstring_2);//it used to check the url has vimeo string in url or not
                if (empty($youtube_check) && empty($vimeo_check)) { // if the url doesn't has vimeo or youtube in url means the url stores the website of the user
                   $query = "SELECT website from #__users where id = $memberId";
                    $db->setQuery($query);
                    $website = $db->loadResult();
                    $youtubeurl = $website;
                    if (preg_match("#https?://#", $youtubeurl) === 0) {
                    $youtubeurl = 'http://' . $youtubeurl;
                }
            }
            //end
            $type = JRequest::getVar('type');


            $value = (explode(" ", $txtarea));
            $count = count($value);
            $query = "SELECT setting_currency from #__pin_site_settings";
            $db->setQuery($query);
            $currency = $db->loadResult();

            for ($i = 0; $i <= $count - 1; $i++) {
                if (preg_match("/^[" . $currency . "]|[0-9]+[.]+$/", $value[$i])) {
                    $price = $value[$i];
                    if ($price != '' && $price) {
                        $query = "SELECT setting_currency from #__pin_site_settings";
                        $db->setQuery($query);
                        $currency = $db->loadResult();

                        if (preg_match("/[" . $currency . "]/", $price)) {
                            $query = "SELECT setting_currency from #__pin_site_settings";
                            $db->setQuery($query);
                            $currency = $db->loadResult();
                            $is_price = explode($currency, $price);
                            if (is_numeric($is_price[1])) {
                                $gift = '1';
                            }
                        }
                    } else {
                        $gift = '0';
                    }
                }
            }

            if ($type != 'youtube' && $type != 'vimeo') {


                $fullpath = 'basename';

                if ($fullpath == 'basename') {
                    $fullpath = basename($srcimg);
                    $info = getimagesize($srcimg);
                    $userImageDetails = pathinfo($fullpath);

                    $extension = strtolower($userImageDetails['extension']);

                    $rand_num = rand(0, 99);
                }
                $ch = curl_init($srcimg);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
                $rawdata = curl_exec($ch);
                curl_close($ch);
                if (file_exists($fullpath)) {
                    unlink($fullpath);
                }
                $image_name = $userImageDetails['filename'] . $rand_num . '.' . $extension;
                $image_original = JPATH_SITE . DS . "images" . DS . "socialpinboard" . DS . "pin_original" . DS . $image_name;
                $pin_original = fopen($image_original, 'a');
                fwrite($pin_original, $rawdata);
                switch ($info[2]) {
                    case IMAGETYPE_GIF:
                        $uploadedfile = $image_original;
                        $src = imagecreatefromgif($uploadedfile);
                        break;
                    case IMAGETYPE_JPEG:
                        $uploadedfile = $image_original;
                        $src = imagecreatefromjpeg($uploadedfile);
                        break;
                    case IMAGETYPE_PNG:
                        $uploadedfile = $image_original;
                        $src = imagecreatefrompng($uploadedfile);
                        break;
                }
                // Get new sizes
                list($width, $height) = getimagesize($image_original);
                $newwidth = $width;
                $newheight = $height;
                $tmp = imagecreatetruecolor($newwidth, $newheight);


                $newwidth1 = 60;
                $newheight1 = 60;
                $tmp1 = imagecreatetruecolor($newwidth1, $newheight1);

                $newwidth2 = $width;
                if (($width > '192') || ($width == '192')) {
                    $newwidth2 = '220';
                }
                $newheight2 = ($height / $width) * $newwidth2;
                $tmp2 = imagecreatetruecolor($newwidth2, $newheight2);

                if ($extension == "png" || $extension == "gif") {
                    $color = imagecolorallocatealpha($tmp2, 0, 0, 0, 127);
                    imagefill($tmp2, 0, 0, $color);
                    imagesavealpha($tmp2, true);
                    imagealphablending($tmp1, false);
                    $color = imagecolorallocatealpha($tmp1, 0, 0, 0, 127);
                    imagefill($tmp1, 0, 0, $color);
                    imagesavealpha($tmp1, true);
                }
                imagecopyresampled($tmp1, $src, 0, 0, 0, 0, $newwidth1, $newheight1, $width, $height);
                imagecopyresampled($tmp2, $src, 0, 0, 0, 0, $newwidth2, $newheight2, $width, $height);

                $filename1 = JPATH_SITE . DS . "images" . DS . "socialpinboard" . DS . "pin_thumb" . DS . $image_name;
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

                $filename2 = JPATH_SITE . DS . "images" . DS . "socialpinboard" . DS . "pin_medium" . DS . $image_name;
                switch ($info[2]) {
                    case IMAGETYPE_GIF:
                        imagegif($tmp2, $filename2);
                        break;
                    case IMAGETYPE_JPEG:
                        imagejpeg($tmp2, $filename2);
                        break;
                    case IMAGETYPE_PNG:
                        imagepng($tmp2, $filename2);
                        break;
                }
                imagedestroy($tmp1);
                imagedestroy($tmp2);
            }

            if ($type == 'youtube' || $type == 'vimeo') {
                $image_name = $srcimg;
            }
            if (isset($dropBoard)) {
                $select = "select board_category_id from #__pin_boards where board_id=" . $dropBoard;
                $db->setQuery($select);
                $catid = $db->loadObjectList();
                $cater = $catid[0]->board_category_id;
                if ($type == 'youtube' || $type == 'vimeo') {
                    $image_original = $srcimg;
                }

                $queryInsert = "INSERT INTO `#__pin_pins` ( `pin_board_id`, `pin_user_id`, `pin_category_id`, `pin_description`,`price`,`gift`,`pin_url`,`pin_image`,`link_type`,`created_date`,`updated_date`)
                           VALUES ( '$dropBoard', '$memberId','$category_id'," . $db->quote($txtarea) . ",'$price','$gift','$youtubeurl','$image_name','$type',NOW(),NOW());";

                $db->setQuery($queryInsert);
                $InsertPin = $db->query();
                $app->redirect(JRoute::_('index.php?option=com_socialpinboard&view=boarddisplay', false));
            }
        }
    }

    function topMenuAssign() {
        $field = 'title,link,browserNav';
        if (version_compare(JVERSION, '1.7.0', 'ge')) {
            $version = '1.7';
        } elseif (version_compare(JVERSION, '1.6.0', 'ge')) {
            $version = '1.6';
        } else {
            $version = '1.5';
            $field = 'name,link,browserNav';
        }
        $query_menu = "select $field from #__menu WHERE published =1 AND menutype LIKE '%top%' ORDER BY lft"; //Query to get the category id from category value passing in the url
        $db = $this->getDBO();
        $db->setQuery($query_menu);
        $menu = $db->loadObjectList();
        return $menu;
    }

    function userLogin() {
        $user = JFactory::getUser();
        $memberId = $user->get('id');
        if ($memberId != '0') {
            $db = $this->getDBO();
            $query = "select first_name,user_image from #__pin_user_settings where user_id=" . $memberId;
            $db->setQuery($query);
            $userlogin = $db->loadObjectList();
            return $userlogin;
        }
    }

function rgb2html($r, $g=-1, $b=-1)
{
    if (is_array($r) && sizeof($r) == 3)
        list($r, $g, $b) = $r;

    $r = intval($r); $g = intval($g);
    $b = intval($b);

    $r = dechex($r<0?0:($r>255?255:$r));
    $g = dechex($g<0?0:($g>255?255:$g));
    $b = dechex($b<0?0:($b>255?255:$b));

    $color = (strlen($r) < 2?'0':'').$r;
    $color .= (strlen($g) < 2?'0':'').$g;
    $color .= (strlen($b) < 2?'0':'').$b;
    return '#'.$color;
}
 function showRequest() {

         $db = $this->getDBO();
        $query = "SELECT setting_show_request FROM #__pin_site_settings";
        $db->setQuery($query);
        $show_request = $db->loadResult();

         return $show_request;
}

}