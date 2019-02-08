<?php

/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component search model
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class socialpinboardModelSearch extends SocialpinboardModel {

    public function getSearchCount() {
        // Create a new query object.
        $db = JFactory::getDBO();
        // get search value

        $search = JRequest::getVar('serachVal');
        $value = JRequest::getVar('q');

        if ($value == '') {
            $searchname = $search;
        } else {

            $searchname = $value;
        }
        $session = JFactory::getSession();

        $search = $session->get('searchVal');

        $query = "select count(*) from #__pin_boards where board_name='$searchname'";

        //execute query
        $db->setQuery($query);
        $result = $db->loadResult();

        $totalpage = ceil($result / 15);

        return $totalpage;
    }

    public function getSearchlist() {

        // Create a new query object.
        $db = JFactory::getDBO();
        $where = NULL;
        $search = JRequest::getString('serachVal');
        $type = JRequest::getVar('search');
        $value = JRequest::getVar('q');
        $pageno = 0;
        $length = 20;
        $pins = array();
        $result = array();
        if (JRequest::getVar('page')) {
            $pageno = JRequest::getVar('page') - 1;
            $pageno = $pageno * 20;
        }

        if ($value == '') {
            $searchname = $search;
        } else {

            $searchname = $value;
        }
        if ($type == 1) {

            $query = "select board_id,board_name from #__pin_boards where status=1 and board_name  LIKE  '%$searchname%' LIMIT $pageno,$length";
            $db->setQuery($query);
            $boardPins = $db->loadObjectList(); //print_r($boardPins);

            foreach ($boardPins as $boardPin) {

                $query = "select b.user_id,a.pin_id,a.pin_url,a.pin_image,a.link_type,a.gift,a.price,b.first_name,b.last_name
            from #__pin_pins a
            LEFT JOIN #__pin_user_settings b on a.pin_user_id=b.user_id
            where a.status=1 and a.pin_board_id=$boardPin->board_id
                   order by a.pin_id desc
                   limit 0,4";
                $db->setQuery($query);
                $pin_res = $db->loadObjectList();
                $i = 0;
                foreach ($pin_res as $results) {
                    $result[$i][$boardPin->board_id]['pin_image'] = $results->pin_image;
                    $result[$i][$boardPin->board_id]['user_id'] = $results->user_id;
                    $result[$i][$boardPin->board_id]['link_type'] = $results->link_type;
                    $result[$i][$boardPin->board_id]['pin_url'] = $results->pin_url;
                    $result[$i][$boardPin->board_id]['pin_id'] = $results->pin_id;
                    $result[$i][$boardPin->board_id]['gift'] = $results->gift;
                    $result[$i][$boardPin->board_id]['price'] = $results->price;
                    $i++;
                }
            }
        } else if ($type == 0) {
            $searchname = explode(" ", $searchname);
            $likequery = '';

            for ($i = 0; $i < count($searchname); $i++) {
                $likequery.="a.pin_description LIKE '%$searchname[$i]%' ";
                if (($i + 1) != count($searchname)) {
                    $likequery.=" OR ";
                }
            }
            $query = "select a.*,b.category_id,b.category_name,d.username,d.user_image,d.first_name,d.last_name,e.board_id,board_name from #__pin_pins a left join #__pin_user_settings d on a.pin_user_id=d.user_id left join #__pin_boards e on e.board_id=a.pin_board_id left join #__pin_categories b on a.pin_category_id=b.category_id  where a.status='1' and $likequery LIMIT $pageno,$length";
            $db->setQuery($query);
            $result = $db->loadObjectList();
        } else if ($type == 2) {
            $searchname = str_replace(' ', '', $searchname);
            $query = "SELECT * FROM  #__pin_user_settings WHERE  (first_name LIKE  '%$searchname%' OR  `last_name` LIKE  '%$searchname%' OR `username` LIKE '%$searchname%') AND status=1 LIMIT $pageno,$length";

            $db->setQuery($query);
            $result = $db->loadObjectList();
        }

        return $result;
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

    //Function to fetch comments for that pins
    function getComments($pin) {
        $db = $this->getDBO();
        $query = "select a.pin_comments_id,a.pin_user_comment_id,a.pin_comment_text,b.user_image,b.username,b.first_name,b.last_name from #__pin_comments as a inner join #__pin_user_settings as b on a.pin_user_comment_id=b.user_id where a.pin_id=$pin and a.status=1";
        $db->setQuery($query);
        $pin_comment_result = $db->loadObjectList();

        return $pin_comment_result;
    }

    //function to getboardpins
    function getBoardPins() {
        $db = $this->getDBO();
        $user = JFactory::getUser();
        $userId = $user->get('id');
        $query = "select * from #__pin_pins where status=1";
        $db->setQuery($query);
        $boardPins = $db->loadObjectList(); //print_r($boardPins);
        $i = 0;
        $arr = array();
        foreach ($boardPins as $boardPin) {
            $arr[$i][$boardPin->pin_board_id]['pin_id'] = $boardPin->pin_id;
            $arr[$i][$boardPin->pin_board_id]['pin_img'] = $boardPin->pin_image;
            $i++;
        }

        return $arr;
    }

    //function to display boards
    function displayBoard() {
        $db = $this->getDBO();
        $pageno = 0;
        $length = 20;
        $search = JRequest::getString('serachVal');
        $type = JRequest::getVar('search');
        $value = JRequest::getVar('q');
        $pins = array();
        if ($value == '') {
            $searchname = $search;
        } else {

            $searchname = $value;
        }
        if (JRequest::getVar('page')) {
            $pageno = JRequest::getVar('page') - 1;
            $pageno = $pageno * 20;
        }

        $query = "select a.board_id,a.board_name,a.user_id,a.cuser_id,b.first_name,b.last_name,b.user_image
        from #__pin_boards a
        LEFT JOIN #__pin_user_settings b
        on a.user_id=b.user_id
        where a.status=1 and a.board_name
        LIKE '%$searchname%' LIMIT $pageno,$length";

        $db->setQuery($query);
        $boardDiaplay = $db->loadObjectList();
        return $boardDiaplay;
    }

    //function to fetch current user profile info
    function getUserprofile() {
        $user = JFactory::getUser();
        $user_id = $user->id;
        $db = $this->getDBO();
        $query = "select user_id,user_image,username,first_name,last_name from #__pin_user_settings where user_id=$user_id and status=1";
        $db->setQuery($query);
        $user_result = $db->loadObjectList();
        return $user_result;
    }

    function userDetails() {
        $db = $this->getDBO();
        $query = "select * from #__pin_user_settings where status=1";
        $db->setQuery($query);
        $user_result = $db->loadObjectList();
        return $user_result;
    }

    function getBoards() {
        $user = JFactory::getUser();
        $user_id = $user->id;
        $db = $this->getDBO();
        $query = "select * from #__pin_boards where user_id=$user_id and status=1";
        $db->setQuery($query);
        $user_result = $db->loadObjectList();
        return $user_result;
    }

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

    static function followUser($fuser) {

        //select the boards from the logged user
        $db = JFactory::getDbo();
        $query = "SELECT board_id
                  FROM #__pin_boards
                  WHERE user_id='$fuser'";
        $db->setQuery($query);
        $results = $db->loadColumn();
        $user = JFactory::getUser();
        $userId = $user->id;
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
                  AND user_id='$userId'
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

    static function followUserBoard($user, $fuser) {

        //select the boards from the logged user
        $db = JFactory::getDbo();
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
function getCurrencySymbol() {
        $db = JFactory::getDBO();
        $query = "SELECT setting_currency from #__pin_site_settings";
            $db->setQuery($query);
            $currency = $db->loadResult();
            return $currency;
}
}