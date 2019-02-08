<?php

/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component like model
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');

//to get data for design details from database.
class socialpinboardModellikes extends SocialpinboardModel {

    //Function to fetch the customer's like info
    function getLikesinformation() {
        $db = $this->getDBO();
        $user = JFactory::getUser();
        $users = JRequest::getVar('uid');

        if ($users != '') {
            $userId = $users;
        } else {
            $userId = $user->get('id');
        }
        $pageno = 0;
        $length = 20;
        $pins = array();
        if (JRequest::getVar('page')) {
            $pageno = JRequest::getVar('page') - 1;
            $pageno = $pageno * 20;
        }
        $colorwhere = '';
        if (JRequest::getVar('color')) {
            $color = JRequest::getVar('color');

            $colorwhere = " AND rgbcolor IN ($color) ";
        }
        if (isset($users)) {
            $userId = $users;
        } else {
            $userId = $user->id;
        }

        $query = "SELECT a . * , b . * , c . * , d . *
                FROM #__pin_likes AS a
                LEFT JOIN #__pin_pins AS b ON a.pin_id = b.pin_id
                LEFT JOIN #__pin_boards AS c ON b.pin_board_id = c.board_id
                LEFT JOIN #__pin_user_settings AS d ON c.user_id = d.user_id
                WHERE a.pin_like_user_id =$userId and a.status=1 and b.status=1 and  a.pin_like = 1 $colorwhere ORDER BY b.created_date DESC LIMIT $pageno,$length";

        $db->setQuery($query);
        $pin_count_result = $db->loadObjectList();
        return $pin_count_result;
    }

    //Function to fetch comments for that pins
    function getComments($pin) {
        $db = $this->getDBO();
        $query = "select a.pin_comments_id,a.pin_user_comment_id,a.pin_comment_text,b.user_image,b.username,b.first_name,b.last_name from #__pin_comments as a inner join #__pin_user_settings as b on a.pin_user_comment_id=b.user_id where a.pin_id=$pin and a.status=1 and b.status=1";
        $db->setQuery($query);
        $pin_comment_result = $db->loadObjectList();

        return $pin_comment_result;
    }

    //Function to get all boards of the user
    function getBoards() {
        $db = $this->getDBO();
        $user = JFactory::getUser();
        $user_id = $user->id;
        $query = "select board_id,board_name from #__pin_boards where status = 1 and user_id=$user_id";
        $db->setQuery($query);
        $categories = $db->loadObjectList();

        return $categories;
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
            $like = 1;
        } else {
            $like = 0;
        }
        $query = "select pin_like from #__pin_likes where pin_id=$pin_id and pin_like_user_id=$user_id";
        $db->setQuery($query);
        $pins = $db->loadObjectList();
        if (!empty($pins)) {

            $query = "UPDATE `#__pin_likes` set `pin_like`=$like where pin_id=$pin_id and pin_like_user_id=$user_id";
        } else {
            $query = "INSERT INTO `#__pin_likes` (`pin_like_id`, `pin_id`, `pin_like_user_id`,`pin_like`, `status`, `created_date`, `updated_date`) VALUES
('', $pin_id, $user_id, $like,1,'$current_date', '$current_date')";
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
            // $this->mailNotification($pin_id,$user_id,$mail_type=2);
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
        $comment = $pin['comment'];
        $date = JFactory::getDate();
        $current_date = $date->format("Y-m-d H:i:s");
        $query = "INSERT INTO `#__pin_comments` (`pin_comments_id`, `pin_id`, `pin_user_comment_id`, `pin_comment_text`, `status`, `created_date`, `updated_date`) VALUES ('', $pin_id, $user_id, '$comment', '1', '$current_date', '$current_date')";
        $db->setQuery($query);

        if (!$db->query()) {
            $this->setError($db->getErrorMsg());
            return false;
        }
        $this->mailNotification($pin_id, $user_id, $mail_type = 3);
        $query = "select count(pin_comments_id) as comments_count from #__pin_comments where pin_id=$pin_id and status=1";
        $db->setQuery($query);
        $pin_count_result = $db->loadObjectList();
        $pin_count = $pin_count_result[0]->comments_count;
        $query = "UPDATE `#__pin_pins` set `pin_comments_count`=$pin_count,updated_date='$current_date' where pin_id=$pin_id";
        $db->setQuery($query);

        if (!$db->query()) {
            $this->setError($db->getErrorMsg());
            return false;
        }

        return $comment;
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

    //Function to fetch the customer's like info


    function mailNotification($pin_real_pin_id, $pin_user_id, $mail_type) {
        $db = $this->getDBO();
        $query = "select a.pin_id,a.pin_description,b.first_name,b.last_name,b.email from #__pin_pins as a
            inner join #__pin_user_settings as b on a.pin_user_id=b.user_id
            where pin_id=$pin_real_pin_id";

        $db->setQuery($query);
        $pin_email = $db->loadObjectList();

        $query = "select first_name,last_name,email from #__pin_user_settings where user_id=$pin_user_id";
        $db->setQuery($query);
        $pin_user_info = $db->loadObjectList();
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "From: webmaster@example.com" . "\r\n Content-Type: text/html; charset=ISO-8859-1\r\n";
        $email = $pin_email[0]->email;
        $subject = $pin_user_info[0]->first_name . ' repinned your pin';
        $Pin_user_name = $pin_email[0]->first_name . ' ' . $pin_email[0]->last_name;
        $pin_description = $pin_email[0]->pin_description;
        if ($mail_type == 1) {
            $message = '<html><head><body><p>Hi' . $Pin_user_name . '</p><br/>' . $Pin_user_name . ' Repinned your ' . $pin_description;
        } else if ($mail_type == 2) {
            $message = '<html><head><body><p>Hi' . $Pin_user_name . '</p><br/>' . $Pin_user_name . ' Liked your ' . $pin_description;
        } else if ($mail_type == 3) {
            $message = '<html><head><body><p>Hi' . $Pin_user_name . '</p><br/>' . $Pin_user_name . ' commented your ' . $pin_description;
        }
    }

    //Add new board
    function addnewboard($res) {
        $db = $this->getDBO();
        $user = JFactory::getUser();
        $user_id = $user->id;
        $date = JFactory::getDate();
        $current_date = $date->format("Y-m-d H:i:s");
        $board_name = $res['board_name'];
        $query = "INSERT INTO `#__pin_boards` (`board_id`, `user_id`, `board_name`, `board_access`, `status`, `created_date`, `updated_date`) VALUES ('', $user_id, '$board_name', '1','1', '$current_date', '$current_date')";
        $db->setQuery($query);

        if (!$db->query()) {
            $this->setError($db->getErrorMsg());
            return false;
        }
        $option = $sb->insertid();

        return $option;
    }

    //function to display boards
    function displayBoard() {
        $db = $this->getDBO();
        $user = JFactory::getUser();
        $users = JRequest::getVar('uid');
        if (isset($users)) {
            $userId = $users;
        } else {
            $userId = $user->get('id');
        }
        $query = "select count(*) from #__pin_boards where status=1 and user_id=" . $userId;
        $db->setQuery($query);
        $total = $db->loadResult();
        $pageno = 0;
        $length = 20;
        $pins = array();
        if (JRequest::getVar('page')) {
            $pageno = JRequest::getVar('page') - 1;
            $pageno = $pageno * 20;
        }
        $query = "select board_id,board_name from #__pin_boards where status=1 and user_id=" . $userId . " LIMIT $pageno,$length";
        $db->setQuery($query);
        $boardDiaplay = $db->loadObjectList();
        return $boardDiaplay;
    }

    function countPins() {
        $db = $this->getDBO();
        $user = JFactory::getUser();
        $users = JRequest::getVar('uid');
        if (isset($users)) {
            $userId = $users;
        } else {
            $userId = $user->get('id');
        }
        $query = "select * from #__pin_pins where status=1 and pin_user_id=" . $userId;
        $db->setQuery($query);
        $pinCount = $db->loadObjectList();
        return $pinCount;
    }

    function getLikescount() {
        $db = $this->getDBO();
        $user = JFactory::getUser();
        $users = JRequest::getVar('uid');

        if (isset($users)) {
            $userId = $users;
        } else {
            $userId = $user->get('id');
        }
        $query = "select DISTINCT a.* from #__pin_likes as a left join #__pin_pins as b on a.pin_id=b.pin_id where a.status=1 and b.status=1 and a.pin_like='1' and a.pin_like_user_id=" . $userId;
        $db->setQuery($query);
        $pinLikes = $db->loadObjectList();
        return $pinLikes;
    }

    function totalBoard() {
        $db = $this->getDBO();
        $user = JFactory::getUser();
        $users = JRequest::getInt('uid');

        if (isset($users) && $users != '0') {
            $userId = $users;
        } else {
            $userId = $user->get('id');
        }
        $query = "select count(*) from #__pin_boards where status=1 and (user_id=" . $userId . " OR cuser_id LIKE " . $userId . ")";

        $db->setQuery($query);
        $total = $db->loadResult();

        return $total;
    }

    //function to fetch current user profile info
    // Added by Sathish for get user details
    function getProfile() {
        $db = $this->getDBO();
        $user = JFactory::getUser();
        $users = JRequest::getInt('uid');

        if (isset($users) && $users != '0') {
            $userId = $users;
        } else {
            $userId = $user->get('id');
        }
        $query = "select * from #__pin_user_settings where user_id = $userId";
        $db->setQuery($query);
        $userDetails = $db->loadObjectList();
        return $userDetails;
    }

    function followUser($user, $fuser) {
        //select the boards from the logged user
        $db = $this->getDBO();
        $query = "SELECT board_id
                  FROM #__pin_boards
                  WHERE user_id='$fuser'";
        $db->setQuery($query);
        $results = $db->loadColumn();
        $boardId = implode(',', $results);
        //select the followed boards from the user
        $inc = 0;

        $query = "SELECT follow_user_board
                  FROM #__pin_follow_users
                  WHERE status=1
                  AND user_id='$user'
                  AND follow_user_id='$fuser'";

        $db->setQuery($query);
        $followBoard = $db->loadResult();

        //explode the followed boards into arrays for boards selected invidually
        $boards = explode(",", $followBoard);
//if same return flag all
        if ($boardId == $followBoard) {
            $result = 'all';
        } else {
//return the selected boards alone
            $result = $boards;
        }
        return $result;
    }

    function getFollowingInformation() {
        $db = JFactory::getDBO();
        $user = JFactory::getUser();
        $user_id = $user->id;
        $userID = JRequest::getInt('uid');

        if ($userID != '') {
            $user_id = $userID;
        } else {
            $user_id = $user_id;
        }
        $query = "SELECT count(b.user_id)
                FROM #__pin_follow_users AS a
                INNER JOIN #__pin_user_settings AS b
                ON a.follow_user_id = b.user_id
                WHERE a.user_id =$user_id
                AND b.status=1";

        $db->setQuery($query);
        $follower_information = $db->loadResult();

        return $follower_information;
    }

    function getFollowerInformation() {
        $db = JFactory::getDBO();
        $user = JFactory::getUser();
        $user_id = $user->id;
        $userID = JRequest::getInt('uid');
        if ($userID != '') {
            $user_id = $userID;
        } else {
            $user_id = $user_id;
        }
        $query = "SELECT b.user_id,b.username,b.first_name,b.last_name,b.user_image,a.follow_user_board,  (SELECT COUNT( board_id )
                FROM #__pin_boards
                WHERE (user_id = b.user_id OR cuser_id=$user_id) and status=1
                ) AS bid, (

                SELECT COUNT( pin_id )
                FROM #__pin_pins
                WHERE pin_user_id = b.user_id and status=1
                ) AS pid
                  FROM  #__pin_follow_users as a  INNER JOIN #__pin_user_settings as b ON a.user_id=b.user_id
                 WHERE  a.follow_user_id =$user_id
        AND b.status=1 group by b.user_id";


        $db->setQuery($query);
        $follower_information = $db->loadObjectList();
        return count($follower_information);
    }

// Added by Sathish for get block status of the user
    function getblockStatus() {
        $db = $this->getDBO();
        $user = Jfactory::getUser();
        $user_id = $user->id;
        $users = JRequest::getInt('uid');
        $query = "select status from #__pin_user_block where user_id=$user_id and user_block_id = $users";
        $db->setQuery($query);
        $blockStatus = $db->loadObjectList(); //print_r($blockStatus);exit;
        return $blockStatus;
    }

// Added by Sathish for get the reported users reported by the login user
    function getReportUsers() {
        $db = JFactory::getDBO();
        $user = JFactory::getUser();
        $user_id = $user->id;
        $query = "select * from #__pin_userreports where report_sender=$user_id GROUP BY report_receiver";
        $db->setQuery($query);
        $reportuserslist = $db->loadObjectList();
        return $reportuserslist;
    }

    static function getModRepinpinUserdetails($resultmodrepin) {
        $db = JFactory::getDBO();
        $query = "select concat(a.first_name,' ',a.last_name ) as
username,b.pin_user_id,a.user_image from #__pin_user_settings as a inner join #__pin_pins as b
on a.user_id=b.pin_user_id where b.pin_id = $resultmodrepin AND a.status = 1";
        $db->setQuery($query);
        $modrepinuserdetails = $db->loadObjectList();
        return $modrepinuserdetails;
    }

    // Added by Sathish for get Repin activities of the users
    static function getRepinActivities() {
        $db = JFactory::getDBO();
        $user = JFactory::getUser();
        $users = JRequest::getInt('uid');

        if (isset($users) && $users != '0') {
            $userId = $users;
        } else {
            $userId = $user->get('id');
        }
        $repins = array();

        if ($userId) {
            $db = JFactory::getDBO();
            $query = "select distinct
(a.pin_user_id),a.pin_id,a.pin_image,a.pin_description,a.pin_repin_id,a.link_type,a.created_date
,b.username,b.user_id,b.user_image,'Repin'
         AS type  from #__pin_pins as a Left join #__pin_user_settings as b on
b.user_id=a.pin_user_id where (a.pin_user_id=$userId  AND b.status=1 and a.status = 1
           AND a.pin_repin_id <> 0)  order by a.pin_id desc limit 0,3";
            $db->setQuery($query);
            $repinactivities = $db->loadObjectList();
        }

        return $repinactivities;
    }

    function getUserprofile() {
        global $pin_user_id;
        $pin_user_id = JRequest::getInt('uid');
        $user = Jfactory::getUser();
        $user_id = $user->id;
        $db = $this->getDBO();
        if ($user_id) {
            $query = "select user_id,user_image,username,first_name,last_name from #__pin_user_settings where user_id IN ($user_id ,$pin_user_id) ";
        } else {
            $query = "select user_id,user_image,username,first_name,last_name from #__pin_user_settings where user_id =$pin_user_id ";
        }

        $db->setQuery($query);
        $user_result = $db->loadObjectList();

        return $user_result;
    }

}

?>