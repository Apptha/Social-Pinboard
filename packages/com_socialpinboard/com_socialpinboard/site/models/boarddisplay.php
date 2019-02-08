<?php

/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component boarddisplay model
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');
jimport('joomla.database.database.mysql');
jimport('joomla.database.database.mysqli');

class socialpinboardModelboarddisplay extends SocialpinboardModel {

    function userExist() {
        $db = $this->getDBO();
        $user = JFactory::getUser();

        $users = JRequest::getInt('uid');

        if (isset($users) & $users != '0') {
            $userId = $users;
        } else {
            $userId = $user->get('id');
        }
        $query = "SELECT a.block,b.status
                FROM #__users AS a
                INNER JOIN #__pin_user_settings AS b
                ON a.id=b.user_id
                WHERE b.user_id=$userId";

        $db->setQuery($query);
        $user_exists = $db->LoadObject();

        return $user_exists;
    }

    //function to display boards
    function displayBoard() {
        $db = $this->getDBO();
        $user = JFactory::getUser();
        $users = JRequest::getInt('uid');

        if (isset($users) && $users != '0') {
            $userId = $users;
        } else {
            $userId = $user->get('id');
        }

        $pageno = 0;
        $length = 20;
        $pins = array();
        if (JRequest::getVar('page')) {
            $pageno = JRequest::getVar('page')-1;
            $pageno = $pageno * 20;
        }
        $colorwhere = '';
        if (JRequest::getVar('color')) {
            $color = JRequest::getVar('color');


            $colorwhere = " AND rgbcolor IN ($color) ";
        }
        //select the user boards and contributer boards
        $query = "SELECT user_id,board_id,board_name,cuser_id
                  FROM #__pin_boards
                  WHERE status=1
                  AND (user_id=" . $userId . " OR cuser_id LIKE '%$userId%') $colorwhere
                  LIMIT $pageno,$length";

        $db->setQuery($query);
        $boardDisplay = $db->loadObjectList();
        return $boardDisplay;
    }

    function totalBoard() {
        $db = $this->getDBO();
        $user = JFactory::getUser();
        $users = JRequest::getInt('uid');

        if (isset($users) & $users != '0') {
            $userId = $users;
        } else {
            $userId = $user->get('id');
        }
        $query = "SELECT count(board_id)
                  FROM #__pin_boards
                  WHERE status=1
                  AND (user_id=" . $userId . " OR cuser_id LIKE " . $userId . ")";
        $db->setQuery($query);
        $total = $db->loadResult();

        return $total;
    }

    //function to getboardpins
    function getBoardPins($boardid) {
        $db = $this->getDBO();


        $query = "SELECT pin_id,pin_url,pin_image,link_type
                  FROM #__pin_pins
                  WHERE status=1
                  AND pin_board_id=$boardid
                  ORDER BY pin_id ASC limit 0,4";
        $db->setQuery($query);
        $pins = $db->loadObjectList();
        return $pins;
    }

    function getLikes() {
        $db = $this->getDBO();
        $user = JFactory::getUser();
        $users = JRequest::getInt('uid');
        if (isset($users) & $users != '0') {
            $userId = $users;
        } else {
            $userId = $user->get('id');
        }
        $query = "SELECT count(a.pin_id)
                  FROM #__pin_likes
                  AS a
                  INNER JOIN #__pin_pins
                  AS b ON a.pin_id=b.pin_id
                  WHERE a.status=1
                  AND b.status=1
                  AND a.pin_like='1'
                  AND a.pin_like_user_id=" . $userId;
        $db->setQuery($query);
        $pinLikes = $db->loadResult();

        return $pinLikes;
    }

    function countPins() {
        $db = $this->getDBO();
        $user = JFactory::getUser();
        $users = JRequest::getInt('uid');
        if (isset($users) & $users != '0') {
            $userId = $users;
        } else {
            $userId = $user->get('id');
        }
        $query = "SELECT count(pin_id)
                  FROM #__pin_pins
                  WHERE status=1
                  AND pin_user_id=" . $userId;
        $db->setQuery($query);
        $pinCount = $db->loadResult();
        return $pinCount;
    }

    function followUser($user, $fuser) {

        //select the boards from the logged user
        $db = $this->getDBO();
        $query = "SELECT board_id
                  FROM #__pin_boards
                  WHERE user_id='$fuser'";
        $db->setQuery($query);
        $results = $db->loadColumn();

        if (isset($results)) {
            $boardId = implode(',', $results);
        } else {
            $boardId = $results;
        }
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

    static function getModRepinpinUserdetails($resultmodrepin) {
        $db = JFactory::getDBO();
        $query = "select concat(a.first_name,' ',a.last_name ) as username,b.pin_user_id,a.user_image
                  from #__pin_user_settings as a
                  inner join #__pin_pins as b
                  on a.user_id=b.pin_user_id
                  where b.pin_id = $resultmodrepin AND a.status = 1";
        $db->setQuery($query);
        $modrepinuserdetails = $db->loadObjectList();
        return $modrepinuserdetails;
    }

// Added by Sathish for get Repin activities of the users
    function getRepinActivities() {
        $db = $this->getDBO();
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
            $query = "select distinct (a.pin_user_id),a.pin_id,a.pin_image,a.pin_description,a.pin_repin_id,a.link_type,a.created_date,b.username,b.user_id,b.user_image,'Repin' AS type
                        from #__pin_pins as a
                        Left join #__pin_user_settings as b
                        on b.user_id=a.pin_user_id
                        where (a.pin_user_id=$userId AND b.status=1 and a.status = 1 AND a.pin_repin_id <> 0)
                        order by a.pin_id desc limit 0,3";
            $db->setQuery($query);
            $repinactivities = $db->loadObjectList();
        }

        return $repinactivities;
    }

    function getBoardPinsCount($boardid) {
        $db = $this->getDBO();
        $query = "select pin_id,pin_url,pin_image,link_type from #__pin_pins where status=1 and pin_board_id=$boardid order by updated_date desc";
        $db->setQuery($query);
        $pinsbcount = $db->loadObjectList();
        return $pinsbcount;
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

    //function to fetch current user profile info
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

    function getFollowuser() {
        $db = $this->getDBO();
        $user = Jfactory::getUser();
        $user_id = $user->id;
        $users = JRequest::getInt('uid');
        $query = "select follow_user_id from #__pin_follow_users where user_id=$user_id and follow_user_id = $users";
        $db->setQuery($query);
        $userfollowuser = $db->loadObjectList();
        return $userfollowuser;
    }

// Added by Sathish for get block status of the user
    function getblockStatus() {
        $db = $this->getDBO();
        $user = Jfactory::getUser();
        $user_id = $user->id;
        $users = JRequest::getInt('uid');
        $query = "select status from #__pin_user_block where user_id=$user_id and user_block_id = $users";
        $db->setQuery($query);
        $blockStatus = $db->loadObjectList();
        return $blockStatus;
    }

}

?>
