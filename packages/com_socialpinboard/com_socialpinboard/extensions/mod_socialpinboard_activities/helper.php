<?php

/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Module Activities
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');

class modSocialpinboardactivities {

    public static function getRecentActivities() {


        $user = JFactory::getUser();
        $users = JRequest::getVar('uid');
        if (isset($users)) {
            $userId = $users;
        } else {
            $userId = $user->get('id');
        }
        $comments = array();
        $likes = array();
        $repins = array();
        $activities = array();

        if ($userId) {
            $db = JFactory::getDBO();
            $query = "select a.pin_id,a.pin_user_id,a.pin_image,a.pin_description,a.link_type,a.created_date,'Repin' AS type  from #__pin_pins as a where (a.pin_user_id = $userId AND a.status = 1 AND a.pin_repin_id <> 0)  UNION select a.pin_id,a.pin_user_id,a.pin_image,a.pin_description,a.link_type,b.created_date,'Comments' AS type  from #__pin_pins as a  inner join #__pin_comments as b on a.pin_id= b.pin_id where b.pin_user_comment_id = $userId AND a.status = 1 AND b.status=1  UNION select a.pin_id,a.pin_user_id,a.pin_image,a.pin_description,a.link_type,c.created_date,'Likes' AS type  from #__pin_pins as a  inner join #__pin_likes as c on c.pin_id = a.pin_id where c.pin_like_user_id = $userId AND a.status = 1 AND c.status=1 order by created_date desc limit 0,5";
            $db->setQuery($query);
            $activities = $db->loadObjectList(); //echo '<pre>';print_r($categories);
        }

        return $activities;
    }

    public static function getUserdetails() {


        $user = JFactory::getUser();
        $users = JRequest::getVar('uid');
        if (isset($users)) {
            $userId = $users;
        } else {
            $userId = $user->get('id');
        }
        if ($userId) {
            $db = JFactory::getDBO();
            $query = "select concat(first_name,' ',last_name ) as username,user_image,location,about,website from #__pin_user_settings  where user_id = $userId";
            $db->setQuery($query);
            $userdetails = $db->loadObjectList();
            return $userdetails;
        }
    }

    public static function getpinUserdetails($pinId) {

        $db = JFactory::getDBO();
        $query = "select concat(a.first_name,' ',a.last_name ) as username from #__pin_user_settings as a inner join #__pin_pins as b on a.user_id=b.pin_user_id where b.pin_id = $pinId";
        $db->setQuery($query);
        $userdetails = $db->loadResult();
        return $userdetails;
    }

    public static function getRecentUserdetails() {
        $db = JFactory::getDBO();

        $user = JFactory::getUser();

        if ($user) {
            $userId = $user->id;


            $query = "select concat(first_name,' ',last_name ) as username,user_image,location,about from #__pin_user_settings  where user_id = $userId AND status = 1";

            $db->setQuery($query);
            $userdetails = $db->loadObject(); 
            return $userdetails;
        }
    }

    public static function getFollowerDetails() {
        $user = JFactory::getUser();

        $user_id = JRequest::getVar('uid');

        if (isset($user_id)) {

            $userId = $user_id;
        } else {
            $userId = $user->id;
        }


        $followers = array();
        //get the follwer details of current user 
        $db = JFactory::getDbo();
        $query = "SELECT concat(a.first_name,' ',a.last_name ) as username,a.user_image,a.user_id,b.follow_user_board
                    FROM #__pin_user_settings AS a 
                    LEFT JOIN #__pin_follow_users AS b on a.user_id=b.user_id 
                    WHERE b.follow_user_id=$userId ORDER BY b.updated_date DESC limit 0,5";

        $db->setQuery($query);
        $follow_user_details = $db->loadObjectList();

        $i = 0;

        $query = "SELECT  board_id,board_name FROM  #__pin_boards WHERE user_id = '$userId'";

        $db->setQuery($query);
        $results = $db->loadObjectList();


        foreach ($follow_user_details as $follow_user_detail) {

            $inc = 0;
            $boardId = '';
            foreach ($results as $result) {

                if ((count($results) - 1) == $inc) {
                    $boardId.= $result->board_id;
                } else {
                    $boardId.= $result->board_id . ',';
                }
                $boardArray[$result->board_id] = $result->board_name;



                $inc++;
            }
            $followers[$i]['user_image'] = $follow_user_detail->user_image;
            $followers[$i]['username'] = $follow_user_detail->username;
            $followers[$i]['user_id'] = $follow_user_detail->user_id;

            if ($boardId == $follow_user_detail->follow_user_board) {

                $followers[$i]['board_name'] = '';

                $followers[$i]['all'] = 'all';
            } else {

                $user_board_ids = (explode(",", $follow_user_detail->follow_user_board));
                foreach ($user_board_ids as $user_board_id) {
                    if ($user_board_id != '') {

                        $follower[] = $boardArray[$user_board_id];
                    }
                }

                $followers[$i]['board_name'] = $follower;

                $followers[$i]['all'] = '';
            }
            $i++;
        }

        return $followers;
    }

    public static function getSidegooglead() { //get bottom google ad
        $db = JFactory::getDbo();
        $query = "select * from #__pin_googlead where pin_ad_position='home_side' and status=1 Limit 1";
        $db->setQuery($query);
        $result = $db->loadObjectList();
        return $result;
    }

}

?>
