<?php

/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component home model
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');
jimport('joomla.user.helper');

class socialpinboardModelhome extends SocialpinboardModel {

    function getCategories() {
        $db = $this->getDBO();
        $query = "select category_id,category_name from #__pin_categories where status = 1";
        $db->setQuery($query);
        $categories = $db->loadObjectList();
        return $categories;
    }

    function getPinscount() {
        $db = $this->getDBO();
        $query = "select count(pin_id) from #__pin_pins";
        $db->setQuery($query);
        $total = $db->loadResult();
        return $total;
    }

    function getPins() {
        $db = $this->getDBO();
        $pageno = 0;
        $length = 20;
        $pins = array();
        $followpins = array();
        $user = JFactory::getUser();
        $userId = $user->id;
        if (JRequest::getVar('page')) {
            $pageno = JRequest::getVar('page') - 1;
            $pageno = $pageno * 20;
        }
        $colorwhere = '';
        if (JRequest::getVar('color')) {
            $color = JRequest::getVar('color');

            $color = explode(" ", $color);

            for ($i = 0; $i < count($color); $i++) {
                $colorwhere .= " AND rgbcolor LIKE '%$color[$i]%'";
            }
        }

        $db = $this->getDBO();
        $query = "SELECT follow_user_board FROM #__pin_follow_users WHERE user_id='$userId' and follow_user_id != '' ";
        $db->setQuery($query);
        $userboards = $db->loadObject();

        $query = "SELECT follow_user_board,id FROM `#__pin_follow_users`  WHERE user_id = '$userId' ";
        $db->setQuery($query);
        $user_follows = $db->loadColumn();
        $user_follows = implode(',', $user_follows);

        $category_name = rawurldecode(JRequest::getVar('category'));
        $category_id = JRequest::getInt('cat_id', '0', 'default');

        if ($category_name != '' || $category_id != '0') {


            if ($category_name == 'all') {

                $query = "SELECT distinct(a.pin_id) , a.pin_board_id ,  a.pin_user_id ,  a.pin_category_id ,  a.pin_description ,  a.pin_type_id ,  a.pin_url ,  a.pin_image ,  a.pin_repin_id ,  a.pin_real_pin_id ,  a.pin_repin_count ,  a.pin_likes_count ,  a.pin_comments_count ,  a.pin_views ,  a.link_type ,  a.status ,  a.gift ,  a.price ,  a.created_date ,  a.updated_date ,d.username,d.user_image,d.first_name,d.last_name,e.board_id,e.board_name
                          FROM #__pin_pins a
                          INNER JOIN #__pin_user_settings d on a.pin_user_id=d.user_id
                          INNER JOIN #__pin_boards e on e.board_id=a.pin_board_id
                          WHERE a.status='1' AND e.status=1 $colorwhere
                          ORDER BY a.created_date desc
                          LIMIT $pageno,$length";
            } else {

                $query = "SELECT distinct(a.pin_id) ,  a.pin_board_id ,  a.pin_user_id ,  a.pin_category_id ,  a.pin_description ,  a.pin_type_id ,  a.pin_url ,  a.pin_image ,  a.pin_repin_id ,  a.pin_real_pin_id ,  a.pin_repin_count ,  a.pin_likes_count ,  a.pin_comments_count ,  a.pin_views ,  a.link_type ,  a.status ,  a.gift ,  a.price ,  a.created_date ,  a.updated_date ,d.username,d.user_image,d.first_name,d.last_name,e.board_id,e.board_name
                          FROM #__pin_pins a
                          INNER JOIN #__pin_user_settings d on a.pin_user_id=d.user_id
                          INNER JOIN #__pin_boards e on e.board_id=a.pin_board_id

                          INNER JOIN #__pin_categories b on e.board_category_id=b.category_id
                          WHERE b.category_id = '$category_id'
                          AND a.status='1' AND e.status=1 $colorwhere
                          ORDER BY  a.created_date desc
                          LIMIT $pageno,$length";
            }
        } else if (JRequest::getVar('pinners') == 'youfollow') {

            $query = "SELECT a.`pin_id`, a.`pin_board_id`, a.`pin_user_id`, a.`pin_category_id`, a.`pin_description`, a.`pin_type_id`, a.`pin_url`, a.`pin_image`, a.`pin_repin_id`, a.`pin_real_pin_id`, a.`pin_repin_count`, a.`pin_likes_count`, a.`pin_comments_count`, a.`pin_views`, a.`link_type`, a.`status`, a.`published`, a.`gift`, a.`price`, a.`created_date`, a.`updated_date`,f.`id`, f.`user_id`, f.`follow_user_id`, f.`follow_user_board`, f.`status`, f.`follow_categories`, f.`created_date`, f.`updated_date`,d.username,d.user_image,d.first_name,d.last_name,e.board_id,e.board_name
                      FROM #__pin_pins a
                      INNER JOIN #__pin_user_settings d
                      ON a.pin_user_id=d.user_id
                      INNER JOIN #__pin_follow_users f
                      ON a.pin_user_id=f.follow_user_id
                      INNER JOIN #__pin_boards e
                      ON e.board_id=a.pin_board_id
                      WHERE a.status='1'
                      AND e.status=1
                      AND  f.user_id=$userId ";
            if ($user_follows)
                $query .= " AND a.pin_board_id IN ($user_follows) ";

            $query .= "
                      ORDER BY a.created_date desc LIMIT $pageno,$length";
        } else {
            if ($userId && $userboards != '') {

                $query = "(SELECT distinct(a.pin_id) , a.pin_board_id ,  a.pin_user_id ,  a.pin_category_id ,  a.pin_description ,  a.pin_type_id ,  a.pin_url ,  a.pin_image ,  a.pin_repin_id ,  a.pin_real_pin_id ,  a.pin_repin_count ,  a.pin_likes_count ,  a.pin_comments_count ,  a.pin_views ,  a.link_type ,  a.status ,  a.gift ,  a.price ,  a.created_date ,  a.updated_date ,d.username,d.user_image,d.first_name,d.last_name,e.board_id,e.board_name
                FROM #__pin_pins a
                INNER JOIN #__pin_user_settings d
                ON a.pin_user_id=d.user_id
                INNER JOIN #__pin_boards e
                ON e.board_id=a.pin_board_id
                WHERE  a.status='1'
                AND  (a.pin_user_id = '$userId'
                 OR e.cuser_id IN ($userId) OR e.user_id =$userId)AND e.status=1 $colorwhere)";
            } else if (($userboards == '' || !$userId || $userId == '') && $user_follows != "") {

                $query = "SELECT distinct(a.pin_id) ,  a.pin_board_id ,  a.pin_user_id ,  a.pin_category_id ,  a.pin_description ,  a.pin_type_id ,  a.pin_url ,  a.pin_image ,  a.pin_repin_id ,  a.pin_real_pin_id ,  a.pin_repin_count ,  a.pin_likes_count ,  a.pin_comments_count ,  a.pin_views ,  a.link_type ,  a.status ,  a.gift ,  a.price ,  a.created_date ,  a.updated_date ,d.username,d.user_image,d.first_name,d.last_name,e.board_id,e.board_name
                from #__pin_pins a
                INNER JOIN #__pin_user_settings d on a.pin_user_id=d.user_id
                INNER JOIN #__pin_boards e on e.board_id=a.pin_board_id
                WHERE a.status='1'
                AND e.status=1 $colorwhere
                ORDER BY a.created_date desc
                LIMIT $pageno,$length";
            } else if ($userboards == '' && $userId != '') {

                $query = "SELECT distinct(a.pin_id) ,  a.pin_board_id ,  a.pin_user_id ,  a.pin_category_id ,  a.pin_description ,  a.pin_type_id ,  a.pin_url ,  a.pin_image ,  a.pin_repin_id ,  a.pin_real_pin_id ,  a.pin_repin_count ,  a.pin_likes_count ,  a.pin_comments_count ,  a.pin_views ,  a.link_type ,  a.status ,  a.gift ,  a.price ,  a.created_date ,  a.updated_date ,d.username,d.user_image,d.first_name,d.last_name,e.board_id,e.board_name
                from #__pin_pins a
                INNER JOIN #__pin_user_settings d on a.pin_user_id=d.user_id
                INNER JOIN #__pin_boards e on e.board_id=a.pin_board_id
                WHERE a.status='1'
                AND a.pin_user_id =$userId AND e.status=1 $colorwhere
                ORDER BY a.created_date desc
                LIMIT $pageno,$length";
            } else if ($userboards == '' || !$userId || $userId == '') {

                $query = "SELECT distinct(a.pin_id) ,  a.pin_board_id ,  a.pin_user_id ,  a.pin_category_id ,  a.pin_description ,  a.pin_type_id ,  a.pin_url ,  a.pin_image ,  a.pin_repin_id ,  a.pin_real_pin_id ,  a.pin_repin_count ,  a.pin_likes_count ,  a.pin_comments_count ,  a.pin_views ,  a.link_type ,  a.status ,  a.gift ,  a.price ,  a.created_date ,  a.updated_date ,d.username,d.user_image,d.first_name,d.last_name,e.board_id,e.board_name
                from #__pin_pins a
                INNER JOIN #__pin_user_settings d on a.pin_user_id=d.user_id
                INNER JOIN #__pin_boards e on e.board_id=a.pin_board_id
                WHERE a.status='1'
                AND e.status=1 $colorwhere
                ORDER BY a.created_date desc
                LIMIT $pageno,$length";
            }

            if ($userId && $userboards != '') {


                $query = $query . "UNION (SELECT distinct(c.pin_id), c.pin_board_id,  c.pin_user_id, c.pin_category_id, c.pin_description, c.pin_type_id, c.pin_url, c.pin_image, c.pin_repin_id, c.pin_real_pin_id, c.pin_repin_count, c.pin_likes_count, c.pin_comments_count, c.pin_views, c.link_type, c.status, c.gift, c.price, c.created_date, c.updated_date,d.username,d.user_image,d.first_name,d.last_name,b.board_id,b.board_name
                                  FROM `#__pin_follow_users` AS a
                                  INNER JOIN #__pin_boards AS b ON a.follow_user_id = b.user_id
                                  INNER JOIN #__pin_pins AS c ON b.board_id = c.pin_board_id
                                  INNER JOIN #__pin_user_settings d on d.user_id=a.follow_user_id
                                  WHERE a.user_id = '$userId'
                                  AND c.status='1'
                                  AND c.pin_user_id!='$userId'
                                  AND b.status='1'
                                  AND  b.board_id IN ($user_follows) $colorwhere)
                                  ORDER BY created_date desc
                                  LIMIT $pageno,$length";
            }
        }


        $db->setQuery($query);
        $pins = $db->loadObjectList();

        if (isset($userId)) {
            $query = "SELECT f.pin_id
                FROM #__pin_likes  f
                WHERE  f.pin_like_user_id = '$userId' ";

            $db->setQuery($query);
            $like_pins = $db->loadResultArray();

            if (!empty($like_pins)) {

                $pins = array('pins' => $pins, 'likes' => $like_pins);
            } else {

                $pins = array('pins' => $pins);
            }
        } else {
            $pins = array('pins' => $pins);
        }

        return $pins;
    }

    function getBoards() {
        $db = $this->getDBO();
        $user = JFactory::getUser();
        $user_id = $user->id;
        $query = "select board_id,board_name from #__pin_boards where status = 1 and user_id=$user_id";
        $db->setQuery($query);
        $categories = $db->loadObjectList();

        return $categories;
    }

    function setPins($res) {
        $db = $this->getDBO();
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
        if ($pin_real_pin_id != 0) {
            $this->mailNotification($pin_real_pin_id, $pin_user_id, $mail_type = 1);
        } else {
            $pin_real_pin_id = $repin;
        }
        $query = "UPDATE `#__pin_pins` SET `pin_repin_count`=$repin_count,`updated_date`='$current_date' WHERE pin_id=$repin";

        $db->setQuery($query);
        if (!$db->query()) {
            $this->setError($db->getErrorMsg());
            return false;
        }

        $value = (explode(" ", $description));
        $count = count($value);
        for ($i = 0; $i <= $count - 1; $i++) {
            if (preg_match("/^[$][0-9]+$/", $value[$i])) {

                $price = $value[$i];
                if ($price != '' && $price) {
                    if (preg_match("/[$]/", $price)) {
                        $is_price = explode('$', $price);
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

//Function to get pin information
    function getPin($pinId) {
        $db = $this->getDBO();
        $query = "select pin_type_id,pin_repin_id,pin_real_pin_id,pin_url,pin_board_id,pin_description,pin_image,link_type from #__pin_pins where pin_id=$pinId";
        $db->setQuery($query);
        $pins = $db->loadObjectList();
        return $pins;
    }

    //Function to store likes
    function getLike($pin) {
        $db = $this->getDBO();
        $pin_id = $pin['pin_id'];
        $pin_flag = $pin['pin_flag'];
        $user_id = $pin['user_id'];
        $date = JFactory::getDate();
        $current_date = $date->format("Y-m-d H:i:s");
        if ($pin_flag == 0) {
            $query = "INSERT INTO `#__pin_likes` (`pin_like_id`, `pin_id`, `pin_like_user_id`,`pin_like`, `status`, `created_date`, `updated_date`) VALUES
('', $pin_id, $user_id, 1,1,now(), now())";
        } else {
            $query = "DELETE FROM `#__pin_likes` where pin_id=$pin_id and pin_like_user_id=$user_id";
        }


        $db->setQuery($query);

        if (!$db->query()) {
            $this->setError($db->getErrorMsg());
            return false;
        }

        $query = "select count(pin_like_id) as like_count from #__pin_likes where pin_id=$pin_id and pin_like=1 and status=1";
        $db->setQuery($query);
        $pin_count_result = $db->loadObjectList();
        if (!empty($pin_count_result)) {
            $pin_count = $pin_count_result[0]->like_count;
            $query = "UPDATE `#__pin_pins` set `pin_likes_count`=$pin_count,updated_date='$current_date' where pin_id=$pin_id";
            $db->setQuery($query);
            if (!$db->query()) {
                $this->setError($db->getErrorMsg());
                return false;
            }
            if ($pin_flag == 0) {
                $this->mailNotification($pin_id, $user_id, $mail_type = 2);
            }
            if ($pin_count == 1) {
                return $pin_count . ' Like';
            } else if ($pin_count > 1) {
                return $pin_count . ' Likes';
            }
        }
    }

//Function to store comments
    function getComment($pin) {
        $db = $this->getDBO();
        $pin_id = $pin['pin_id'];
        $user = JFactory::getUser();
        $user_id = $user->id;
        $comment = htmlentities(addslashes($pin['comment']));
        $date = JFactory::getDate();
        $current_date = $date->format("Y-m-d H:i:s");
        $query = "INSERT INTO `#__pin_comments` (`pin_comments_id`, `pin_id`, `pin_user_comment_id`, `pin_comment_text`, `status`, `created_date`, `updated_date`) VALUES ('', $pin_id, $user_id, '$comment', '1', now(), now())";
        $db->setQuery($query);


        if (!$db->query()) {
            $this->setError($db->getErrorMsg());
            return false;
        }
        $comment_user_id = $db->insertid();
        $this->mailNotification($pin_id, $user_id, $mail_type = 3);
        $query = "SELECT COUNT(pin_comments_id)
                  AS comments_count,pin_comments_id,pin_user_comment_id
                  FROM #__pin_comments
                  WHERE pin_id=$pin_id
                  AND status=1";
        $db->setQuery($query);
        $pin_count_result = $db->loadObject();
        $pin_count = $pin_count_result->comments_count;
        $query = "UPDATE `#__pin_pins`
                  SET `pin_comments_count`=$pin_count,updated_date='$current_date'
                  WHERE pin_id=$pin_id";
        $db->setQuery($query);

        if (!$db->query()) {
            $this->setError($db->getErrorMsg());
            return false;
        }
        $comment_details = array();
        $comment_details['comment'] = stripslashes($comment);
        $comment_details['comment_id'] = $comment_user_id;
        $comment_details['user_id'] = $pin_count_result->pin_user_comment_id;
        return $comment_details;
    }

//Function to fetch whether the customer already liked the pin or not
    function getLikes($pin, $user) {

        $db = $this->getDBO();
        $query = "select pin_like from #__pin_likes where pin_id=$pin and status=1 and pin_like_user_id=$user";
        $db->setQuery($query);
        $pin_count_result = $db->loadResult();
        if ($pin_count_result == 1) {
            $pin_count = 1;
        } else {
            $pin_count = 0;
        }

        return $pin_count;
    }

    function getPinLike($pin, $user) {

        $db = $this->getDBO();
        $query = "SELECT pin_like,pin_id
               FROM #__pin_likes
               WHERE pin_id IN ($pin)and status=1 and pin_like_user_id=$user ";

        $db->setQuery($query);
        $pin_count_result = $db->loadObjectList();

        return $pin_count_result;
    }

//Function to fetch comments for that pins
    function getComments($pin) {
        $db = $this->getDBO();
        $query = "select a.pin_comments_id,a.pin_user_comment_id,a.pin_comment_text,b.user_image,b.username,b.first_name,b.last_name from #__pin_comments as a inner join #__pin_user_settings as b on a.pin_user_comment_id=b.user_id where a.pin_id=$pin and a.status=1 ORDER BY a.pin_comments_id asc  ";
        $db->setQuery($query);
        $pin_comment_result = $db->loadObjectList();

        return $pin_comment_result;
    }

//function to fetch current user profile info
    function getUserprofile() {
        $user = JFactory::getUser();
        $user_id = $user->id;
        $db = $this->getDBO();
        $query = "select user_image,username,first_name,last_name from #__pin_user_settings where user_id=$user_id and status=1";
        $db->setQuery($query);
        $user_result = $db->loadObjectList();

        return $user_result;
    }

    //Function to fetch the customer's like info

    function getLikesinformation() {
        $db = $this->getDBO();
        $user = JFactory::getUser();
        $user_id = $user->id;
        $query = "select a.*,b.* from #__pin_pins as a inner join #__pin_likes on a.pin_id=b.pin_id where a.status=1 and b.status=1 and b.pin_like_user_id=$user";
        $db->setQuery($query);
        $pin_count_result = $db->loadObjectList();
        if ($pin_count_result[0]->pin_like == 1) {
            $pin_count = 1;
        } else {
            $pin_count = 0;
        }

        return $pin_count;
    }

    function mailNotification($pin_real_pin_id, $pin_user_id, $mail_type) {
        $mailer = JFactory::getMailer(); //define joomla mailer
        $app = JFactory::getApplication();
        $db = $this->getDBO();
        $query = "SELECT a.pin_id,a.pin_description,b.first_name,a.pin_image,a.link_type,b.last_name,b.email
                  FROM #__pin_pins AS a
                  INNER JOIN #__pin_user_settings AS b
                  ON a.pin_user_id=b.user_id
                  WHERE pin_id=$pin_real_pin_id";

        $db->setQuery($query);
        $pin_email = $db->loadObjectList();

        $query = "SELECT first_name,last_name,email,user_image
                  FROM #__pin_user_settings
                  WHERE user_id=$pin_user_id";
        $db->setQuery($query);
        $pin_user_info = $db->loadObjectList();

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
        $user_image = $pin_user_info[0]->user_image;

        $link_type = $pin_email[0]->link_type;
        $strImgName = $pin_email[0]->pin_image;
        if ($link_type == 'youtube' || $link_type == 'vimeo') {
            $strImgPath = $strImgName;
        } else {
            $strImgPath = JURI::base() . '/images/socialpinboard/pin_medium/' . $strImgName;
        }

        if ($user_image != '') {
            $user_image = JURI::base() . 'images/socialpinboard/avatars/' . rawurlencode($user_image);
        } else {
            $user_image = JURI::Base() . '/components/com_socialpinboard/images/no_user.jpg';
        }
        //set recipient
        $mailer->addRecipient($email);
        $Pin_user_name = $pin_email[0]->first_name . ' ' . $pin_email[0]->last_name;
        $pin_affect_user = $pin_user_info[0]->first_name . ' ' . $pin_user_info[0]->last_name;
        $pin_description = $pin_email[0]->pin_description;
        $pin_id = $pin_email[0]->pin_id;
        if ($mail_type == 1) {
            $subject = $pin_user_info[0]->first_name . ' repinned your pin';


            $message = '<table width="100%" cellspacing="0" cellpadding="0" border="0" style="background: url(' . JURI::base() . '/templates/socialpinboard/images/header-bg.jpg) repeat;padding-bottom: 50px;border: 0; margin: 0;">
   <tr>
        <td style="padding: 50px 20px 10px;text-align: center;background: url(' . JURI::base() . '/templates/socialpinboard/images/menu-bg.jpg) top repeat-x;">
            <a style="margin:0;" href="' . JURI::base() . '" target="_blank"><img src="' . $image_source . '" alt="logo" title="logo" style="border:none;"></a>
            </td>
        </tr>
        <tr>
        <td style="text-align:center;border:0;">
            <h1 style="text-align:center;font:bold 18px Arial,Sans-serif;color: #fff;">  Hi, ' . $Pin_user_name . ' </h1>
            </td>
    </tr>

    <tr>
        <td>
            <table width="610" align="center" cellpadding="0" cellspacing="0" style=" background: #eff2f2; padding: 10px; border-top: 2px solid #dc4834; ">
               <tr>
                    <td><a href="' . JURI::base() . 'index.php?option=com_socialpinboard&view=boarddisplay&uid=' . $pin_user_id . '"><img src="' . $user_image . '" width="80" height="80" style="vertical-align:top;outline:none;border:none" alt="' . $pin_affect_user . '"></a></td>
                    <td valign="middle" >
                        <p style="margin: 0;padding: 0 15px;font-family: arial,sans-serif;font-size: 14px;color: #211922;line-height: 20px;margin: 0;text-decoration: none; ">
                            <a href="' . JURI::base() . 'index.php?option=com_socialpinboard&view=boarddisplay&uid=' . $pin_user_id . '" style="font-family: arial,sans-serif;font-size: 14px;color: #211922;line-height: 20px;margin: 0;padding: 0;text-decoration: none;font-weight: bold;">' . $pin_affect_user . '</a>
                            Repinned your
                            <a href="' . JURI::base() . 'index.php?option=com_socialpinboard&view=pin&pinid=' . $pin_id . '" style="font-family: arial,sans-serif;font-size: 14px;color: #211922;line-height: 20px;margin: 0;padding: 0;text-decoration: none;  font-weight: bold;">' . $pin_description . '</a>

                        </p>
                    </td>

                    <td  style="width: 180px;">
                        <a href="' . JURI::base() . 'index.php?option=com_socialpinboard&view=pin&pinid=' . $pin_id . '" style="box-shadow: 0 1px rgba(255,255,255,0.8), inset 0 1px rgba(255,2unfollow55,255,0.35), 0 0 10px rgba(235,82,82,0.25);-moz-box-shadow: 0 1px rgba(255,255,255,0.8), inset 0 1px rgba(255,255,255,0.35), 0 0 10px rgba(235,82,82,0.25);-webkit-box-shadow: 0 1px rgba(255,255,255,0.8), inset 0 1px rgba(255,255,255,0.35), 0 0 10px rgba(235,82,82,0.25);background: #dc4834;border: #AD0303 1px solid;-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;padding: 8px 35px 6px;color: white;text-shadow: 1px 1px #333;font-weight: bold;font-size: 16px;cursor: pointer; display: block;float: right;font-family: arial;text-decoration: none;"> See the Pin</a>
                    </td>
                </tr>
           </table>
        </td>
    </tr>
        </table>';
        } else if ($mail_type == 2) {
            $subject = $pin_user_info[0]->first_name . ' liked your pin';

            $baseurl = JURI::base();
            $liked_user_url = JURI::base() . 'index.php?option=com_socialpinboard&view=boarddisplay&uid=' . $pin_user_id;
            $liked_pin_url = JURI::base() . 'index.php?option=com_socialpinboard&view=pin&pinid=' . $pin_id;
            $liked_user_image = $user_image;
            $liked_user_name = $pin_affect_user;
            $message = file_get_contents(JURI::base() . '/templates/socialpinboard/emailtemplate/likes.html');
            $message = str_replace("{baseurl}", $baseurl, $message);
            $message = str_replace("{site_name}", $site_name, $message);
            $message = str_replace("{site_logo}", $image_source, $message);
            $message = str_replace("{Pin_user_name}", $Pin_user_name, $message);
            $message = str_replace("{liked_user_url}", $liked_user_url, $message);
            $message = str_replace("{liked_user_image}", $liked_user_image, $message);
            $message = str_replace("{liked_user_name}", $liked_user_name, $message);
            $message = str_replace("{liked_pin_url}", $liked_pin_url, $message);
            $message = str_replace("{liked_pin_name}", $pin_description, $message);
            $message = str_replace("{liked_pin_image}", $strImgPath, $message);
        } else if ($mail_type == 3) {
            $subject = $pin_user_info[0]->first_name . ' commented your pin';

            $baseurl = JURI::base();
            $commented_pin = JURI::base() . 'index.php?option=com_socialpinboard&view=pin&pinid=' . $pin_id;
            $commented_user_url = JURI::base() . 'index.php?option=com_socialpinboard&view=boarddisplay&uid=' . $pin_user_id;
            $message = file_get_contents(JURI::base() . '/templates/socialpinboard/emailtemplate/comment.html');
            $message = str_replace("{baseurl}", $baseurl, $message);
            $message = str_replace("{site_name}", $site_name, $message);
            $message = str_replace("{site_logo}", $image_source, $message);
            $message = str_replace("{Pin_user_name}", $Pin_user_name, $message);
            $message = str_replace("{commented_user_url}", $commented_user_url, $message);
            $message = str_replace("{commented_user_name}", $pin_affect_user, $message);
            $message = str_replace("{commented_user_image}", $user_image, $message);
            $message = str_replace("{commented_pin_url}", $commented_pin, $message);
            $message = str_replace("{commented_pin_name}", $pin_description, $message);
        }
        $mailer->isHTML(true);
        $mailer->setSubject($subject);
        $mailer->Encoding = 'base64';
        $mailer->setBody($message);
        $send = $mailer->Send();
    }

    //Add new board
    function addnewboard($res) {
        $db = $this->getDBO();
        $user = JFactory::getUser();
        $user_id = $user->id;
        $date = JFactory::getDate();
        $current_date = $date->format("Y-m-d H:i:s");
        $board_name = $res['board_name'];

        if (strpos($board_name, '_')) {
            $board_name = str_replace('_', ' & ', $board_name);
        }

        $query = "select board_id from #__pin_boards where board_name='$board_name' and (user_id=$user_id or cuser_id=$user_id)";
        $db->setQuery($query);
        $pin_user_info = $db->loadObjectList();
        if (empty($pin_user_info)) {
            $query = 'INSERT INTO `#__pin_boards` (`board_id`, `user_id`, `board_name`, `board_access`, `status`, `created_date`, `updated_date`) VALUES ("", "' . $user_id . '", "' . addslashes($board_name) . '", "1","1",  now() , now())';
            $db->setQuery($query);

            if (!$db->query()) {
                $this->setError($db->getErrorMsg());
                return false;
            }
            $option = $db->insertid();

            return $option;
        } else {
            return;
        }
    }

    function updatePassword($password) {
        $db = $this->getDBO();
        $user = JFactory::getUser();
        $user_id = $user->id;

        if ($user_id != '0' && $user_id != '') {

            $salt = JUserHelper::genRandomPassword(32);
            $crypt = JUserHelper::getCryptedPassword("$password", $salt);
            $newPassword = $crypt . ':' . $salt;
            $query = "UPDATE #__users SET password='$newPassword' WHERE id='$user_id'";
            $db->setQuery($query);
            $db->query();
            $query = "SELECT count from #__pin_user_settings where user_id='$user_id'";
            $db->setQuery($query);
            $count = $db->loadResult();
            $count = $count + 1;
            $query = "UPDATE #__pin_user_settings SET count='$count' WHERE user_id='$user_id'";
            $db->setQuery($query);
            $db->query();
            return $newPassword;
        }
    }

    function getCategoryPins() {


        $db = JFactory::getDBO();
        $catgory_name = JRequest::getVar('category');

        $query = "SELECT a.category_name, b.board_id, c.pin_image
                  FROM #__pin_categories AS a
                  LEFT JOIN #__pin_boards AS b ON a.category_id = b.board_category_id
                  LEFT JOIN #__pin_pins AS c ON b.board_id = c.pin_board_id
                  WHERE a.category_name = '$catgory_name'";
        $db->setQuery($query);
        $category_pins = $db->loadObjectList();
        return $category_pins;
    }

    //add the follow all boards
    function getFollow($follow) {

        $userId = $follow['user_id'];
        $fUserId = $follow['fuser_id'];

        $db = $this->getDBO();
        $query = "select board_id from #__pin_boards where user_id='$fUserId'";

        $db->setQuery($query);
        $results = $db->loadObjectList();
        $inc = 0;
        $boardId = '';
        foreach ($results as $result) {

            if ((count($results) - 1) == $inc) {
                $boardId.= $result->board_id;
            } else {
                $boardId.= $result->board_id . ',';
            }
            $inc++;
        }

        $query = "SELECT id FROM #__pin_follow_users WHERE user_id='$userId' AND follow_user_id='$fUserId'";

        $db->setQuery($query);
        $idAvail = $db->loadResult();


        $query = "DELETE FROM #__pin_follow_users WHERE user_id='$userId' AND follow_user_id='0'";

        $db->setQuery($query);
        $db->query();

        if ($idAvail > 1) {

            $query = "UPDATE  #__pin_follow_users  SET follow_user_board='$boardId' WHERE user_id='$userId' and follow_user_id='$fUserId'";
            $db->setQuery($query);
            $db->query();
        } else {

            $query = "INSERT INTO `#__pin_follow_users` ( `user_id`, `follow_user_id`, `follow_user_board`, `status`, `created_date`, `updated_date`) VALUES ( $userId, $fUserId, '$boardId', '1', NOW(),NOW())";
            $db->setQuery($query);
            $db->query();
        }
        //empty
        $query = "UPDATE  #__pin_user_settings  SET follow_boards='' WHERE user_id='$fUserId'";
        $db->setQuery($query);
        $db->query();

        //update the current follows
        $query = "UPDATE  #__pin_user_settings  SET follow_boards='$boardId' WHERE user_id='$fUserId'";
        $db->setQuery($query);
        $db->query();
    }

// unfollow all the boards
    function getUnFollow($unFollow) {

        $userId = $unFollow['user_id'];
        $fUserId = $unFollow['fuser_id'];
        $db = $this->getDBO();
        $query = "DELETE FROM `#__pin_follow_users` WHERE user_id= $userId and follow_user_id='$fUserId'";
        $db->setQuery($query);
        $db->query();
    }

    //follow all the selected boards
    function getFollowBoard($unFollowBoard) {

        $userId = $unFollowBoard['user_id'];
        $fUserId = $unFollowBoard['fuser_id'];
        $board_id = $unFollowBoard['board_id'];

        //check already recod exits or not
        $db = $this->getDBO();
        $query = "SELECT updated_date,follow_user_id,follow_user_board FROM #__pin_follow_users WHERE user_id='$userId' AND follow_user_id='$fUserId'";
        $db->setQuery($query);
        $idAvail = $db->loadObject();
        $board_ids = $idAvail->follow_user_board . ',' . $board_id;
        $board_ids = rtrim($board_ids, ',');
        $board_ids = ltrim($board_ids, ',');

        //if already record exists, update the details
        if ($idAvail->follow_user_id > 1) {
            $query = "UPDATE  #__pin_follow_users  SET follow_user_board='$board_ids' WHERE user_id='$userId' and follow_user_id='$fUserId'";
            $db->setQuery($query);
            $db->query();
        } else {
            //insert new record
            $db = $this->getDBO();
            $query = "INSERT INTO `#__pin_follow_users` ( `user_id`, `follow_user_id`, `follow_user_board`, `status`, `created_date`, `updated_date`) VALUES ( $userId, $fUserId, '$board_id', '1', NOW(),NOW())";

            $db->setQuery($query);
            $db->query();
        }

        //empty
        $query = "UPDATE  #__pin_user_settings  SET follow_boards='' WHERE user_id='$fUserId'";
        $db->setQuery($query);
        $db->query();
        //update the current follows
        $query = "UPDATE  #__pin_user_settings  SET follow_boards='$board_ids' WHERE user_id='$fUserId'";

        $db->setQuery($query);
        $db->query();
    }

    //unfollow all the selected boards
    function getUnFollowBoard($unFollowBoard) {
        //get the board id&user id to unfollow
        $userId = $unFollowBoard['user_id'];
        $fUserId = $unFollowBoard['fuser_id'];
        $board_id = $unFollowBoard['board_id'];
        //check already recod exits or not
        $db = $this->getDBO();
        $query = "SELECT follow_user_id,follow_user_board FROM #__pin_follow_users WHERE user_id='$userId' AND follow_user_id='$fUserId'";

        $db->setQuery($query);
        $idAvail = $db->loadObject();


        $board_ids = explode(",", $idAvail->follow_user_board);
        $key = array_search($board_id, $board_ids);
        unset($board_ids[$key]);
        $board_ids = implode(',', $board_ids);

        $board_ids = rtrim($board_ids, ',');
        $board_ids = ltrim($board_ids, ',');
        //if already record exists, update the details
        if ($idAvail->follow_user_id > 1) {
            $query = "UPDATE  #__pin_follow_users  SET follow_user_board='$board_ids' WHERE user_id='$userId' and follow_user_id='$fUserId'";
            $db->setQuery($query);
            $db->query();

            $query = "SELECT id,follow_user_board FROM #__pin_follow_users WHERE user_id='$userId' AND follow_user_id='$fUserId'";
            $db->setQuery($query);
            $idAvail = $db->loadObject();
            $follwer_id = $idAvail->id;

            if ($idAvail->follow_user_board == ' ' || $idAvail->follow_user_board == '' || $idAvail->follow_user_board == ',') {

                $query = "DELETE FROM `#__pin_follow_users` WHERE id=$follwer_id LIMIT 1";

                $db->setQuery($query);
                $db->query();
            }
        } else {

            //insert new record
            $db = $this->getDBO();
            $query = "INSERT INTO `#__pin_follow_users` ( `user_id`, `follow_user_id`, `follow_user_board`, `status`, `created_date`, `updated_date`) VALUES ( $userId, $fUserId, '$board_id', '1', NOW(),NOW())";

            $db->setQuery($query);
            $db->query();
        }
    }

    function contributers($contributors) {
        $user = JFactory::getUser();
        $user_id = $user->id;
        $db = JFactory::getDbo();
        $query = "SELECT user_id FROM  #__pin_user_settings WHERE concat_ws(' ',first_name,last_name)='$contributors' OR email = '$contributors'";
        $db->setQuery($query);
        $strContributerId = $db->loadResult();
        return $strContributerId;
    }

    function checkActivation() {
        $user = JFactory::getUser();
        $user_id = $user->id;
        if ($user_id != '') {
            $db = JFactory::getDBO();
            $query = "SELECT a.activationcode
                  FROM  `#__pin_user_activation` AS a
                  LEFT JOIN #__users AS b ON a.email = b.email
                  WHERE id =" . $user_id;

            $db->setQuery($query);
            $show_activation = $db->loadResult();
            return $show_activation;
        }
    }

    function deleteComment($user_comment_id, $comment_pin_id) {
        $user = JFactory::getUser();
        $user_id = $user->id;
        $db = JFactory::getDBO();
        if ($user_id != '') {
            $query = "UPDATE `#__pin_comments`
                     SET `status`=0,`updated_date`=now()
                     WHERE `pin_comments_id`=$user_comment_id";

            $db->setQuery($query);
            $db->query();

            $query = "SELECT  `pin_comments_count`
                   FROM  `#__pin_pins`
                   WHERE  `pin_id` =$comment_pin_id";
            $db->setQuery($query);
            $comment_count = $db->loadResult();
            $comment_count = $comment_count - 1;

            $query = "UPDATE `#__pin_pins`
                   SET `pin_comments_count`=$comment_count
                   WHERE  `pin_id`=$comment_pin_id ";
            $db->setQuery($query);
            $db->query();
        }
        return $comment_count;
    }

    function getBlock($follow) {
        $userId = $follow['user_id'];
        $followUserId = $follow['fuser_id'];
        $db = $this->getDBO();
        $selectQuery = "SELECT status FROM  #__pin_user_block WHERE user_id = $userId and user_block_id = $followUserId";
        $db->setQuery($selectQuery);
        $db->query();
        $block = $db->loadResult();
        $blockCount = $block;
        if ($blockCount == '') {
            $query = "INSERT INTO `#__pin_user_block` (`user_id`, `user_block_id`, `status`) VALUES ( $userId, $followUserId, '1')";
        } else {
            $query = "UPDATE  #__pin_user_block SET status='1' WHERE user_id='$userId' and user_block_id='$followUserId'";
        }
        $db->setQuery($query);
        $db->query();
        $queryDelete = "DELETE FROM `#__pin_follow_users` WHERE user_id= $userId and follow_user_id='$followUserId'";
        $db->setQuery($queryDelete);
        $db->query();
    }

    function getUnblock($unFollow) {
        $userId = $unFollow['user_id'];
        $followUserId = $unFollow['fuser_id'];
        $db = $this->getDBO();
        $query = "UPDATE  #__pin_user_block  SET status='0' WHERE user_id='$userId' and user_block_id='$followUserId'";
        $db->setQuery($query);
        $db->query();
    }
    function getCurrencySymbol() {
        $db = JFactory::getDBO();
        $query = "SELECT setting_currency from #__pin_site_settings";
            $db->setQuery($query);
            $currency = $db->loadResult();
            return $currency;
    }

    //stores values of the board
      function getmobileBoards() {
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

}

?>