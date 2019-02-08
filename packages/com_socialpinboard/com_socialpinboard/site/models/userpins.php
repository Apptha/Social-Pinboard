<?php

/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component userpins model
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');

class socialpinboardModeluserpins extends SocialpinboardModel  {

    function getCategories() {
        $db = $this->getDBO();
        $query = "select category_id,category_name from #__pin_categories where published = 1";
        $db->setQuery($query);
        $categories = $db->loadObjectList(); 
        return $categories;
    }

    function getPinscount() {
        $db = $this->getDBO();
        $query = "select count(*) from #__pin_pins";
        $db->setQuery($query);
        $total = $db->loadResult();
        return $total;
    }

    function getPins() {
        $db = $this->getDBO();
        $user = & JFactory::getUser();
        $user_id = $user->id;
        $userid = '';
        if ($user_id != 0) {
            $userid = ' and a.pin_user_id' . $user_id;
        }
        if (JRequest::getVar('category')) {
            $query = "select count(a.*) from #__pin_pins a left join #__pin_user_settings d on a.pin_user_id=d.user_id left join #__pin_boards e on e.board_id=a.pin_board_id left join #__pin_categories b on a.pin_category_id=b.category_id where a.status='1' $userid and b.category_name='" . JRequest::getVar('category') . "' order by a.created_date desc LIMIT $pageno,$length";
        } elseif (JRequest::getVar('popular')) {
            $query = "select count(a.*) from #__pin_pins a left join #__pin_user_settings d on a.pin_user_id=d.user_id left join #__pin_boards e on e.board_id=a.pin_board_id left join #__pin_categories b on a.pin_category_id=b.category_id  where a.status='1' $userid order by a.pin_repin_count desc LIMIT $pageno,$length";
        } else {
            $query = " select count(*) from #__pin_pins as a where a.status='1' $userid order by a.created_date desc LIMIT $pageno,$length";
        }
        $query = "select count(*) from #__pin_pins";
        $db->setQuery($query);
        $total = $db->loadResult();
        $pageno = 0;
        $length = 20;
        $pins = array();
        if (JRequest::getVar('offset')) {
            $pageno = JRequest::getVar('offset');
        }
        if (JRequest::getVar('offsetaddno')) {
            $length = JRequest::getVar('offsetaddno');
        }

        if (JRequest::getVar('category')) {
            $query = "select a.*,b.category_id,b.category_name,d.username,d.user_image,d.first_name,d.last_name,e.board_id,board_name from #__pin_pins a left join #__pin_user_settings d on a.pin_user_id=d.user_id left join #__pin_boards e on e.board_id=a.pin_board_id left join #__pin_categories b on a.pin_category_id=b.category_id where a.status='1' $userid and b.category_name='" . JRequest::getVar('category') . "' order by a.created_date desc LIMIT $pageno,$length";
        } elseif (JRequest::getVar('popular')) {
            $query = "select a.*,b.category_id,b.category_name,d.username,d.user_image,d.first_name,d.last_name,e.board_id,board_name from #__pin_pins a left join #__pin_user_settings d on a.pin_user_id=d.user_id left join #__pin_boards e on e.board_id=a.pin_board_id left join #__pin_categories b on a.pin_category_id=b.category_id  where a.status='1' $userid order by a.pin_repin_count desc LIMIT $pageno,$length";
        } else {
            $query = "select a.*,b.category_id,b.category_name,d.username,d.user_image,d.first_name,d.last_name,e.board_id,board_name from #__pin_pins a left join #__pin_user_settings d on a.pin_user_id=d.user_id left join #__pin_boards e on e.board_id=a.pin_board_id left join #__pin_categories b on a.pin_category_id=b.category_id  where a.status='1'$userid  order by a.created_date desc LIMIT $pageno,$length";
        }


        $db->setQuery($query);
        $pins = $db->loadObjectList();

        return $pins;
    }

    //Function to get all boards of the user
    function getBoards() {
        $db = $this->getDBO();
        $user = & JFactory::getUser();
        $user_id = $user->id;
        $query = "select board_id,board_name from #__pin_boards where status = 1 and user_id=$user_id";
        $db->setQuery($query);
        $categories = $db->loadObjectList();

        return $categories;
    }

    //Function to store repins
    function setPins($res) {
        $db = $this->getDBO();
        $repin = $res['repin_id'];
        $query = "select pin_category_id,pin_real_pin_id,pin_type_id,pin_url,pin_image from #__pin_pins where pin_id=$repin";
        $db->setQuery($query);
        $pins = $db->loadObjectList();
        $pin_type_id = $pins[0]->pin_type_id;
        $pin_url = $pins[0]->pin_url;
        $pin_image = $pins[0]->pin_image;
        $pin_category_id = $pins[0]->pin_category_id;
        $board_id = $res['board_id'];
        $description = $res['description'];
        $pin_repin_id = $repin;
        $pin_real_pin_id = $pins[0]->pin_real_pin_id;
        $pin_user_id = $res['pin_user_id'];
        $date = & JFactory::getDate();
        $current_date = $date->format("Y-m-d H:i:s");
        $query = "INSERT INTO `#__pin_pins` (`pin_id`, `pin_board_id`, `pin_user_id`, `pin_category_id`, `pin_description`, `pin_type_id`, `pin_url`, `pin_image`, `pin_repin_id`, `pin_real_pin_id`, `pin_repin_count`, `pin_likes_count`, `pin_comments_count`, `pin_views`, `status`, `created_date`, `updated_date`) VALUES
('', $board_id, $pin_user_id, $pin_category_id, '$description', $pin_type_id, '$pin_url', '$pin_image', $pin_repin_id, $pin_real_pin_id, 0, 0, 0, 0, 1, '$current_date', '$current_date')";
        $db->setQuery($query);
        if (!$db->query()) {
            $this->setError($db->getErrorMsg());
            return false;
        }
        return true;
    }

//Function to get pin information
    function getPin($pinId) {
        $db = $this->getDBO();
        $query = "select pin_type_id,pin_repin_id,pin_real_pin_id,pin_url,pin_board_id,pin_description,pin_image from #__pin_pins where pin_id=$pinId";
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
        $date = & JFactory::getDate();
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

            return $pin_count . ' Likes';
        }
    }

//Function to store comments
    function getComment($pin) {
        $db = $this->getDBO();
        $pin_id = $pin['pin_id'];
        $user = & JFactory::getUser();
        $user_id = $user->id;
        $comment = $pin['comment'];
        $date = & JFactory::getDate();
        $current_date = $date->format("Y-m-d H:i:s");
        $query = "INSERT INTO `#__pin_comments` (`pin_comments_id`, `pin_id`, `pin_user_comment_id`, `pin_comment_text`, `status`, `created_date`, `updated_date`) VALUES ('', $pin_id, $user_id, '$comment', '1', '$current_date', '$current_date')";
        $db->setQuery($query);

        if (!$db->query()) {
            $this->setError($db->getErrorMsg());
            return false;
        }

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
        $query = "select pin_like_id,pin_like from #__pin_likes where pin_id=$pin and status=1 and pin_like_user_id=$user";
        $db->setQuery($query);
        $pin_count_result = $db->loadObjectList();
        if ($pin_count_result[0]->pin_like == 1) {
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

//function to fetch current user profile info
    function getUserprofile() {
        $user = & JFactory::getUser();
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
        $user = & JFactory::getUser();
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

}

?>