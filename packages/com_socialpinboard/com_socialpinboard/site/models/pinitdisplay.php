<?php

/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component pinitdisplay model
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');

//to get data for design details from database.
class socialpinboardModelpinitdisplay extends SocialpinboardModel {

    function getBoards() {
        $db = JFactory::getDBO();
        $user = JFactory::getUser();
        $memberId = $user->get('id');
        $query = "select * from #__pin_boards where status = 1 and (user_id=$memberId OR cuser_id = $memberId)";
        $db->setQuery($query);
        $boardsName = $db->loadObjectList();
        return $boardsName;
    }

    public static function getcurrency() {
        $db = JFactory::getDBO();
        $query = "SELECT setting_currency from #__pin_site_settings";
        $db->setQuery($query);
        $currency = $db->loadResult();
        return $currency;
    }

    function Savepinit() {

        $user = JFactory::getUser();
        $db = JFactory::getDBO();
        $memberId = $user->get('id');
        $rand = rand(10, 100);
        $link_type = '';
        if (JRequest::getVar('pin_desc') != '') {
            $btnBoard = JRequest::getVar('uploadPin');
            $BoardName = JRequest::getVar('upload_board');
            $pin_desc = JRequest::getVar('pin_desc');
            $u_media_url = JRequest::getVar('media');
            $value = (explode(" ", $pin_desc));
            $gift = '0';
            $price = $videoid = '';
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

            $created_date = JRequest::getVar('create_date');
            $pin_img = JRequest::getVar('upload_img');
            $img_height = JRequest::getVar('heights');
            if (!empty($pin_img)) {
                $explode = explode("/", $pin_img);
                $cnt = count($explode);
                $cnt--;
                $images = $explode[$cnt];
                $pin_large_img = $images;
            }


            $image_url = JRequest::getVar('url');

            $images = explode("/", $u_media_url);
            $count = count($images);
            $count--;
            $ext = explode(".", $images[$count]);

            if (strstr($u_media_url, 'http')) {
                $i = count($ext) - 1;
                if (($ext[$i] == 'png') || ($ext[$i] == 'gif') || ($ext[$i] == 'jpg') || ($ext[$i] == 'JPG') || ($ext[$i] == 'JPEG') || ($ext[$i] == 'jpeg') || ($ext[$i] == 'GIF') || ($ext[$i] == 'PNG')) {
                    $baseUrl = $_SERVER['HTTP_HOST'];
                    $pin_large_img1 = $images[$count];
                }
            }
            $newurl = explode("/", $u_media_url);

            $youtube_text = "youtube";
            $u_images = explode("/", $image_url);
            $youtube_url = "http://" . $image_url;
            if ($newurl['0'] == "img.youtube.com" || strpos($newurl['0'], 'ytimg.com') > 0) {
                $videoid = $newurl['2'];
                $fullpath = "http://img.youtube.com/vi/" . $videoid . "/0.jpg";
            } else if (preg_match('/www\.youtube\.com\/watch\?v=[^&]+/', $youtube_url, $vresult)) {
                $urlArray = explode("=", $vresult[0]);
                $videoid = trim($urlArray[1]);
            } elseif (preg_match('/www\.youtube\.com\/watch\?feature=/', $youtube_url, $vresult)) {
                $urlArray = explode("=", $youtube_url);
                $videoid = trim($urlArray[2]);
            }



            $u_video = "http://www.youtube.com/watch?v=" . $videoid;
            if (strpos($image_url, 'vimeo') > 0) {
                $fullpath = $u_media_url;
                $image_url = $image_url;
                $link_type = 'vimeo';
            }

            if (strpos($image_url, 'youtube') > 0) {
                $image_url = $u_video;
                $link_type = 'youtube';
                $pin_large_img1 = "http://img.youtube.com/vi/" . $videoid . "/0.jpg";
                $fullpath = $pin_large_img1;
            }

            if (strpos($u_media_url, 'youtube') > 0) {

                $image_url = $u_video;
                $link_type = 'youtube';
                $fullpath = "http://" . $u_media_url;
            }

            if ($link_type != 'youtube' && $link_type != 'vimeo') {

                $fullpath = 'basename';
                if ($fullpath == 'basename') {
                    $fullpath = basename($u_media_url);
                    $fullpath = urldecode($fullpath);
                    $userImageDetails = pathinfo($fullpath);
                    $info = getimagesize($u_media_url);

                    $userImageDetails['extension'] = 'jpeg';


                    $extension = strtolower($userImageDetails['extension']);
                    $fullpath = $userImageDetails['filename'] . $rand . '.' . $extension;
                    $fullpath = preg_replace('/\s+/', '_', $fullpath);
                }


                if (strstr($u_media_url, 'gallery.ameridane.org') || strstr($u_media_url, 'www.espncricinfo.com')) {
                    $u_media_url = 'http://' . $u_media_url;
                    $image_original = JPATH_SITE . DS . "images" . DS . "socialpinboard" . DS . "pin_original" . DS . $fullpath;
                    $store = file_get_contents($u_media_url);
                    file_put_contents($image_original, $store);
                } else {

                    $ch = curl_init($u_media_url);
                    curl_setopt($ch, CURLOPT_HEADER, 0);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
                    $rawdata = curl_exec($ch);
                    curl_close($ch);
                    if (file_exists($fullpath)) {
                        unlink($fullpath);
                    }
                    $image_original = JPATH_SITE . DS . "images" . DS . "socialpinboard" . DS . "pin_original" . DS . $fullpath;

                    $pin_original = fopen($image_original, 'a');
                    fwrite($pin_original, $rawdata);
                }

                if ($extension == "gif") {
                    $uploadedfile = $image_original;

                    $size = filesize($uploadedfile);

                    $image_medium = JPATH_SITE . DS . "images" . DS . "socialpinboard" . DS . "pin_medium" . DS . $fullpath;
                    $image_thumb = JPATH_SITE . DS . "images" . DS . "socialpinboard" . DS . "pin_thumb" . DS . $fullpath;

                    $gif = file_get_contents($uploadedfile);

                    file_put_contents($image_medium, $gif);
                    file_put_contents($image_thumb, $gif);
                } else if ($extension == "jpg" || $extension == "png" || $extension == "jpeg") {
                    $userImageDetails = pathinfo($image_original);
                    $info = getimagesize($image_original);
                    $extension = strtolower($userImageDetails['extension']);
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

                        imagealphablending($tmp2, false);
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
                    $filename1 = JPATH_SITE . DS . "images" . DS . "socialpinboard" . DS . "pin_thumb" . DS . $fullpath;

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
                    $filename2 = JPATH_SITE . DS . "images" . DS . "socialpinboard" . DS . "pin_medium" . DS . $fullpath;
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
            }
            $btnBoard = JRequest::getVar('uploadPin');
            $app = JFactory::getApplication();
            if (isset($btnBoard)) {
                $select = "select board_category_id from #__pin_boards where board_id=" . addslashes($BoardName);
                $db->setQuery($select);
                $catid = $db->loadObjectList();
                $cater = $catid[0]->board_category_id;
                $pin_descurl = $pin_desc;
                $pin_desc = $db->quote($pin_desc);

                $queryInsert = " INSERT INTO `#__pin_pins` ( `pin_board_id`, `pin_user_id`, `pin_category_id`, `pin_description`,`price`,`gift`,`pin_url`,`pin_image`,`pin_repin_id`,`pin_real_pin_id`,`pin_likes_count`,`pin_comments_count`,`pin_views`,`link_type`,`status`,`created_date`,`updated_date`)
                           VALUES ( $BoardName , $memberId , " . $cater . " , $pin_desc ,'" . $price . "'," . $gift . ",'" . $image_url . "','" . $fullpath . "',0,0,0,0,0,'" . $link_type . "',1,NOW(),NOW());";
                $db->setQuery($queryInsert);
                $InsertPin = $db->query();
                $pinid = $db->insertid();

                $app->redirect(JRoute::_('index.php?option=com_socialpinboard&view=pinitpage&tmpl=component&pin_id=' . $pinid . '&pin_desc=' . $pin_descurl));
            }
        }
    }

}

?>
