<?php

/**
 * @author      Contus Support - http://www.contussupport.com
 * @copyright   Copyright (C) 2011 Contus Support
 * @license     GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
/*
 */

// No direct Access
//defined('_JEXEC') or die('Restricted access');
jimport('joomla.database.database.mysql');
jimport('joomla.database.database.mysqli');

class modsocialpinboard_menu {

    public static function getCategories() {
        $user = JFactory::getUser();
        $memberId = $user->get('id');
        if ($memberId != '0') {
            $db = JFactory::getDBO();

            $query = "select category_id,category_name from #__pin_categories where status = 1 order by category_name";
            $db->setQuery($query);
            $categories = $db->loadObjectList();
            return $categories;
        }
    }

    public static function getcurrency() {
        $db = JFactory::getDBO();
        $query = "SELECT setting_currency from #__pin_site_settings";
        $db->setQuery($query);
        $currency = $db->loadResult();
        return $currency;
    }

    public static function getBoards() {


        $user = JFactory::getUser();
        $memberId = $user->get('id');
        if ($memberId != '0') {
            $db = JFactory::getDBO();
            $query = "select user_id,board_id,board_name,cuser_id from #__pin_boards where status=1 and (user_id=" . $memberId . " OR cuser_id LIKE '%$memberId%')";
            $db->setQuery($query);
            $boardsName = $db->loadObjectList();
            return $boardsName;
        }
    }

    /* function to save channel */

    public static function saveBoard() {
        if (JRequest::getVar('board_category_id')) {
            $user = JFactory::getUser();
            $db = JFactory::getDBO();
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
                $query = "select board_id from #__pin_boards where board_name='$board_name' and status=1 and (user_id='$memberId' OR cuser_id='$memberId')";

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
                        $boardids = $db->loadColumn();

                        $val = implode(',', $boardids);


                        $query1 = "UPDATE #__pin_follow_users SET `follow_user_board`= '$val' WHERE follow_user_id = '$memberId'";
                        $db->setQuery($query1);
                        $InsertBoard1 = $db->query();
                    }
                    $app->redirect(JRoute::_('index.php?option=com_socialpinboard&view=boardpage&bId=' . $last_insert_id));
                } else {
                    $app->redirect(JRoute::_('index.php?option=com_socialpinboard&view=boarddisplay'), "Board Name Already Exists", $msgType = 'message');
                }
            }
        }
    }

    public static function uploadPin() {

        if (JRequest::getVar('uploadPin')) {
            $uploaddir = JPATH_BASE . DS . 'modules' . DS . 'mod_socialpinboard_menu' . DS . 'images' . DS . 'socialpinboard' . DS . 'temp' . DS;

            $userImage = $uploaddir . JRequest::getVar('image_id');
            $info = getimagesize($userImage);
            //my code
            $image = $uploaddir . JRequest::getVar('image_id');
            $uploadedfile = $uploaddir . JRequest::getVar('image_id');
            $userImageDetails = pathinfo($image);

            $extension = strtolower($userImageDetails['extension']);
            $user = JFactory::getUser();
            $db = JFactory::getDBO();
            $memberId = $user->get('id');
            $btnBoard = JRequest::getVar('uploadPin');
            $BoardName = JRequest::getVar('upload_board');
            $pin_desc = JRequest::getVar('pin_desc');
            preg_match_all('!https?://[\S]+!', $pin_desc, $matches);
            $matchurl = $matches[0];
            $matchurl1 = '';
            if (isset($matchurl['0']))
                $matchurl1 = $matchurl['0'];

            $pin_desc = str_replace($matchurl1, "<a href='$matchurl1'>$matchurl1</a>", $pin_desc);

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
                    $price = (explode($currency, $price));
                    $price=$price[1];
                    if ($price != '' && $price) {
                        $query = "SELECT setting_currency from #__pin_site_settings";
                        $db->setQuery($query);
                        $currency = $db->loadResult();

                        if (preg_match("/[" . $currency . "]/", $value[$i])) {
                            $query = "SELECT setting_currency from #__pin_site_settings";
                            $db->setQuery($query);
                            $currency = $db->loadResult();
                            $is_price = explode($currency, $value[$i]);
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

                $queryInsert = 'INSERT INTO `#__pin_pins` ( `pin_type_id`,`pin_board_id`, `pin_user_id`, `pin_category_id`, `pin_description`,`price`,`gift`,`status`,`created_date`,`updated_date`)
                           VALUES ("1","' . addslashes($BoardName) . '", "' . $memberId . '","' . $cater . '","' . addslashes($pin_desc) . '","' . $price . '","' . $gift . '",1,NOW(),NOW());';

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

    public static function addPins() {
        if (JRequest::getVar('pin_board')) {
            $user = JFactory::getUser();
            $db = JFactory::getDBO();
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
            $type = JRequest::getVar('type');

            $gift = $price = '';
            $value = (explode(" ", $txtarea));
            $count = count($value);
            $query = "SELECT setting_currency from #__pin_site_settings";
            $db->setQuery($query);
            $currency = $db->loadResult();

            for ($i = 0; $i <= $count - 1; $i++) {
                if (preg_match("/^[" . $currency . "]|[0-9]+[.]+$/", $value[$i])) {
                    $price = $value[$i];
                    $price = (explode($currency, $price));
                    $price=$price[1];
                    if ($price != '' && $price) {
                        $query = "SELECT setting_currency from #__pin_site_settings";
                        $db->setQuery($query);
                        $currency = $db->loadResult();

                        if (preg_match("/[" . $currency . "]/", $value[$i])) {
                            $query = "SELECT setting_currency from #__pin_site_settings";
                            $db->setQuery($query);
                            $currency = $db->loadResult();
                            $is_price = explode($currency, $value[$i]);
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
                if ($width > '220') {
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


                $queryInsert = "INSERT INTO `#__pin_pins` ( `pin_type_id`,`pin_board_id`, `pin_user_id`, `pin_category_id`, `pin_description`,`price`,`gift`,`pin_url`,`pin_image`,`link_type`,`status`,`created_date`,`updated_date`)
                           VALUES ('2', '$dropBoard', '$memberId','$cater'," . $db->quote($txtarea) . ",'$price','$gift','$youtubeurl','$image_name','$type','1',NOW(),NOW());";

                $db->setQuery($queryInsert);
                $InsertPin = $db->query();
                $app->redirect(JRoute::_('index.php?option=com_socialpinboard&view=boarddisplay', false));
            }
        }
    }

    public static function topMenuAssign() {
        $field = 'title,link,browserNav';




//            if (version_compare(JVERSION, '1.7.0', 'ge')) {
//                $version = '1.7';
//            } elseif (version_compare(JVERSION, '1.6.0', 'ge')) {
//                $version = '1.6';
//            } else {
//                $version = '1.5';
        $field = 'title,link,browserNav';
//            }
        $query_menu = "select $field from #__menu WHERE published =1 AND menutype LIKE '%top%' ORDER BY lft"; //Query to get the category id from category value passing in the url
        $db = JFactory::getDBO();
        $db->setQuery($query_menu);
        $menu = $db->loadObjectList();
        return $menu;
    }

    public static function userLogin() {
        $user = JFactory::getUser();
        $memberId = $user->get('id');
        if ($memberId != '0') {
            $db = JFactory::getDBO();
            $query = "select first_name,user_image from #__pin_user_settings where user_id=" . $memberId;
            $db->setQuery($query);
            $userlogin = $db->loadObjectList();
            return $userlogin;
        }
    }

    public static function repin() {
        $db = JFactory::getDBO();


        if (JRequest::getVar('repin_id')) {
            ob_start();
            $res['board_id'] = JRequest::getVar('board_id');
            $res['description'] = JRequest::getVar('description');
            $res['repin_id'] = JRequest::getVar('repin_id');
            $res['pin_real_pin_id'] = JRequest::getVar('pin_real_pin_id');
            $res['pin_user_id'] = JRequest::getVar('pin_user_id');
            $repin = $res['repin_id'];
            $query = "select pin_category_id,pin_real_pin_id,pin_repin_count,pin_type_id,pin_url,pin_image,link_type from #__pin_pins where pin_id=$repin";
            $db->setQuery($query);
            $pins = $db->loadObjectList();
            $pin_type_id = $pins[0]->pin_type_id;
            $pin_url = $pins[0]->pin_url;
            $pin_image = $pins[0]->pin_image;
            $pin_link_type = $pins[0]->link_type;
            $pin_category_id = $pins[0]->pin_category_id;
            $board_id = $res['board_id'];
            $description = $res['description'];
            $repin_count = $pins[0]->pin_repin_count + 1;
            $pin_repin_id = $repin;
            $pin_real_pin_id = $pins[0]->pin_real_pin_id;
            $pin_user_id = $res['pin_user_id'];
            $date = JFactory::getDate();
            $current_date = $date->format("Y-m-d H:i:s");
            if ($pin_real_pin_id == 0) {

                $pin_real_pin_id = $repin;
            }
            $mailer = JFactory::getMailer(); //define joomla mailer
            $app = JFactory::getApplication();
            $db = JFactory::getDBO();
            $query = "SELECT  a.pin_board_id,a.pin_description,a.pin_image,a.link_type,a.pin_id,a.pin_description,b.first_name,b.last_name,b.email
                  FROM #__pin_pins AS a
                  INNER JOIN #__pin_user_settings AS b
                  ON a.pin_user_id=b.user_id
                  WHERE pin_id=$pin_real_pin_id";

            $db->setQuery($query);
            $pin_email = $db->loadObjectList();

            $query = "SELECT board_name
                  FROM #__pin_boards
                  WHERE board_id=" . $board_id;

            $db->setQuery($query);
            $board_name = $db->loadResult();



            $query = "SELECT first_name,last_name,email,user_image
                  FROM #__pin_user_settings
                  WHERE user_id=$pin_user_id";
            $db->setQuery($query);
            $pin_user_info = $db->loadObjectList();

            $user_image = $pin_user_info[0]->user_image;

            if ($user_image != '') {
                $user_image = JURI::base() . 'images/socialpinboard/avatars/' . rawurlencode($user_image);
            } else {
                $user_image = JURI::Base() . '/components/com_socialpinboard/images/no_user.jpg';
            }

            //mail functionality
            $config = JFactory::getConfig();
            $sender = $config->get('mailfrom');
            $site_name = $config->get('sitename');
            $mailer->setSender($sender);


            //set the body
            $templateparams = $app->getTemplate(true)->params; // get the tempalte parameters
            $logo = $templateparams->get('logo'); //get the logo

            if ($logo != null) {
                $image_source = JURI::base() . '/' . htmlspecialchars($logo);
            } else {
                $image_source = JURI::base() . '/templates/socialpinboard/images/logo-large.png';
            }

            $email = $pin_email[0]->email;

            $link_type = $pin_email[0]->link_type;
            $strImgName = $pin_email[0]->pin_image;
            $pin_description = $pin_email[0]->pin_description;
            if ($link_type == 'youtube' || $link_type == 'vimeo') {
                $strImgPath = $strImgName;
            } else {
                $strImgPath = JURI::base() . '/images/socialpinboard/pin_thumb/' . $strImgName;
            }

            //set recipient
            $mailer->addRecipient($email);
            $Pin_user_name = $pin_email[0]->first_name . ' ' . $pin_email[0]->last_name;
            $pin_affect_user = $pin_user_info[0]->first_name . ' ' . $pin_user_info[0]->last_name;
            $pin_description = $pin_email[0]->pin_description;
            $pin_id = $pin_email[0]->pin_id;

            $subject = $pin_user_info[0]->first_name . ' repinned your pin';
            $baseurl = JURI::base();
            $repinned_user_url = JURI::base() . 'index.php?option=com_socialpinboard&view=boarddisplay&uid=' . $pin_user_id;
            $repinned_pin_url = JURI::base() . 'index.php?option=com_socialpinboard&view=pin&pinid=' . $pin_id;
            $repinned_new_board_url = JURI::base() . 'index.php?option=com_socialpinboard&view=boardpage&bId=' . $board_id;
            $message = file_get_contents(JURI::base() . '/templates/socialpinboard/emailtemplate/repin.html');
            $message = str_replace("{baseurl}", $baseurl, $message);
            $message = str_replace("{site_name}", $site_name, $message);
            $message = str_replace("{site_logo}", $image_source, $message);
            $message = str_replace("{Pin_user_name}", $Pin_user_name, $message);
            $message = str_replace("{repinned_user_url}", $repinned_user_url, $message);
            $message = str_replace("{repinned_user}", $pin_affect_user, $message);
            $message = str_replace("{repinned_user_image}", $user_image, $message);
            $message = str_replace("{repinned_pin_url}", $repinned_pin_url, $message);
            $message = str_replace("{repin_image}", $strImgPath, $message);
            $message = str_replace("{pin_description}", $pin_description, $message);
            $message = str_replace("{repinned_new_board_url}", $repinned_new_board_url, $message);
            $message = str_replace("{new_board}", $board_name, $message);
            $mailer->isHTML(true);
            $mailer->setSubject($subject);
            $mailer->Encoding = 'base64';
            $mailer->setBody($message);
            $send = $mailer->Send();

            $repin = JRequest::getVar('repin_id');
            $query = "UPDATE `#__pin_pins` SET `pin_repin_count`=$repin_count,`updated_date`='$current_date' WHERE pin_id=$repin";

            $db->setQuery($query);
            if (!$db->query()) {
                $this->setError($db->getErrorMsg());
                return false;
            }
            $price = $gift = '';
            $value = (explode(" ", $description));
            $count = count($value);
            $query = "SELECT setting_currency from #__pin_site_settings";
            $db->setQuery($query);
            $currency = $db->loadResult();

            for ($i = 0; $i <= $count - 1; $i++) {
                if (preg_match("/^[" . $currency . "]|[0-9]+[.]+$/", $value[$i])) {
                    $price = $value[$i];
                    $price = (explode($currency, $price));
                    $price=$price[1];
                    if ($price != '' && $price) {
                        $query = "SELECT setting_currency from #__pin_site_settings";
                        $db->setQuery($query);
                        $currency = $db->loadResult();

                        if (preg_match("/[" . $currency . "]/", $value[$i])) {
                            $query = "SELECT setting_currency from #__pin_site_settings";
                            $db->setQuery($query);
                            $currency = $db->loadResult();
                            $is_price = explode($currency, $value[$i]);
                            if (is_numeric($is_price[1])) {
                                $gift = '1';
                            }
                        }
                    } else {
                        $gift = '0';
                    }
                }
            }

            $query = "INSERT INTO `#__pin_pins` (`pin_id`, `pin_board_id`, `pin_user_id`, `pin_category_id`, `pin_description`,`price`,`gift`, `pin_type_id`, `pin_url`, `pin_image`, `pin_repin_id`, `pin_real_pin_id`, `pin_repin_count`, `pin_likes_count`, `pin_comments_count`, `pin_views`, `status`,`link_type`, `created_date`, `updated_date`) VALUES
('', $board_id, $pin_user_id, $pin_category_id, '$description','$price','$gift', $pin_type_id, '$pin_url', '$pin_image', $pin_repin_id, $pin_real_pin_id, 0, 0, 0, 0, 1,'$pin_link_type', NOW(),NOW())";
            $db->setQuery($query);
            if (!$db->query()) {
                $this->setError($db->getErrorMsg());
                return false;
            }
            return $db->insertid();
        }
    }

    function rgb2html($r, $g=-1, $b=-1) {
        if (is_array($r) && sizeof($r) == 3)
            list($r, $g, $b) = $r;

        $r = intval($r);
        $g = intval($g);
        $b = intval($b);

        $r = dechex($r < 0 ? 0 : ($r > 255 ? 255 : $r));
        $g = dechex($g < 0 ? 0 : ($g > 255 ? 255 : $g));
        $b = dechex($b < 0 ? 0 : ($b > 255 ? 255 : $b));

        $color = (strlen($r) < 2 ? '0' : '') . $r;
        $color .= ( strlen($g) < 2 ? '0' : '') . $g;
        $color .= ( strlen($b) < 2 ? '0' : '') . $b;
        return '#' . $color;
    }

    public static function showRequest() {


        $db = JFactory::getDBO();
        $query = "SELECT setting_show_request,setting_user_registration FROM #__pin_site_settings";
        $db->setQuery($query);
        $show_request = $db->loadObject();

        return $show_request;
    }

}

?>
