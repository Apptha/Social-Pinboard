<?php

/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component pin model
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');

class socialpinboardModelpin extends SocialpinboardModel {

    function __construct() {
        parent::__construct();
        global $pin_user;
    }

    //function to get pin details
    function getPindetails() {
        global $pin_user;
        $pinId = JRequest::getInt('pinid');
        if (!$pinId && $pinId == '0') {
            JError::raiseError(404, implode('<br />', $errors));

            return false;
        }
        $db = $this->getDBO();
        $query = "SELECT a.pin_id,a.pin_board_id,a.pin_user_id,a.pin_description,a.pin_url,a.pin_image,a.pin_real_pin_id,a.pin_repin_count,a.pin_likes_count,a.link_type,a.gift,a.price,a.created_date,d.first_name,d.last_name,e.board_name
                  FROM #__pin_pins a 
                  INNER JOIN #__pin_user_settings d 
                  ON a.pin_user_id=d.user_id 
                  INNER JOIN #__pin_boards e 
                  ON e.board_id=a.pin_board_id 
                  WHERE a.pin_id=$pinId and a.status=1";
        $db->setQuery($query);
        $pinDetails = $db->loadObject();
        if (empty($pinDetails)) {
            $redirect = JRoute::_('index.php?option=com_socialpinboard&view=home', false);
            $app = JFactory::getApplication();
            $app->redirect($redirect, $msg = 'Invalid URL!');
        } else {

            $pin_user = $pinDetails->pin_user_id;
        }


        return $pinDetails;
    }

    //function to get board pins
    function getBoardpins() {
        $pinId = JRequest::getInt('pinid');
        if (!$pinId && $pinId == '0') {
            JError::raiseError(404, implode('<br />', $errors));
            return false;
        }
        $db = $this->getDBO();
        $query = "SELECT pin_description,link_type,pin_image,pin_id
                  FROM #__pin_pins 
                  WHERE status=1 AND pin_board_id=(select pin_board_id from #__pin_pins WHERE  pin_id=$pinId) 
                  ORDER BY created_date DESC ";
        $db->setQuery($query);
        $pins = $db->loadObjectList();
        return $pins;
    }

    //function to get reports
    function getReports() {

    }

    //function to send report
    function sendReport() {
        $config = JFactory::getConfig();
        $fromname = $config->get('fromname');
        $adminEmail = $config->get('mailfrom');
        $reportName = JREQUEST::getVar('report');
        $user = JFactory::getUser();
        $userEmail = $user->email;
        $subject = "Report on SocialPin Board";
        $headers = 'From:' . $userEmail . "\r\n" .
                'Reply-To: noreply@ appthaa.com' . "\r\n" .
                'X-Mailer: PHP/' . phpversion();
        mail($adminEmail, $subject, $reportName, $headers);
        echo 'Report sent to administrator';
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

    //Function to store repins
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
        $query = "INSERT INTO `#__pin_pins` (`pin_id`, `pin_board_id`, `pin_user_id`, `pin_category_id`, `pin_description`, `pin_type_id`, `pin_url`, `pin_image`, `pin_repin_id`, `pin_real_pin_id`, `pin_repin_count`, `pin_likes_count`, `pin_comments_count`, `pin_views`, `status`,`link_type`, `created_date`, `updated_date`) VALUES
('', $board_id, $pin_user_id, $pin_category_id, '$description', $pin_type_id, '$pin_url', '$pin_image', $pin_repin_id, $pin_real_pin_id, 0, 0, 0, 0, 1,'$pin_link_type', '$current_date', '$current_date')";
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

            return $pin_count . ' Likes';
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
    function getLikes() {
        $pin_id = JRequest::getInt('pinid');
        $user = JFactory::getUser();
        $user_id = $user->id;
        $db = $this->getDBO();
        $query = "select pin_like from #__pin_likes where pin_id=$pin_id and status=1 and pin_like_user_id=$user_id";
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
    function getComments() {
        $pin_id = JRequest::getInt('pinid');
        $db = $this->getDBO();
        $query = "SELECT a.pin_comments_id,a.pin_user_comment_id,a.pin_comment_text,b.user_image,b.username,b.first_name,b.last_name 
                  FROM #__pin_comments AS a 
                  INNER JOIN #__pin_user_settings 
                  AS b 
                  ON a.pin_user_comment_id=b.user_id 
                  WHERE a.pin_id=$pin_id 
                  AND a.status=1 
                  ORDER BY a.pin_comments_id ";
        $db->setQuery($query);
        $pin_comment_result = $db->loadObjectList();
        return $pin_comment_result;
    }

//function to fetch current user profile info
    function getUserprofile() {
        global $pin_user;

        $user = Jfactory::getUser();
        $user_id = $user->id;
        $db = $this->getDBO();
        if ($user_id) {
            $query = "select user_id,user_image,username,first_name,last_name from #__pin_user_settings where user_id IN ($user_id ,$pin_user) ";
        } else {
            $query = "select user_id,user_image,username,first_name,last_name from #__pin_user_settings where user_id =$pin_user ";
        }

        $db->setQuery($query);
        $user_result = $db->loadObjectList();

        return $user_result;
    }

    function getProfile() {
        // global $pin_user;

        $user = Jfactory::getUser();
        $user_id = $user->id;
        $db = $this->getDBO();

        $query = "select user_id,user_image,username,first_name,last_name from #__pin_user_settings where user_id= $user_id";
        //$query = "select user_id,user_image,username,first_name,last_name from #__pin_user_settings where user_id IN ($pin_user)" ;

        $db->setQuery($query);
        $result = $db->loadObjectList();

        return $result;
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

    function pinLike() {
        $db = $this->getDBO();
        $pin = JRequest::getInt('pinid');
        $user = JFactory::getUser();
        $user_id = $user->id;
        $query = "select pin_likes_count from #__pin_pins where pin_id=$pin and status=1";
        $db->setQuery($query);
        $pin_count_result = $db->loadResult();
        return $pin_count_result;
    }

    function pinLikeUser() {
        $db = $this->getDBO();
        $pin = JRequest::getInt('pinid');
        $query = "SELECT a.user_id, a.first_name, a.user_image, b.pin_id, b.status, b.pin_like_user_id FROM `#__pin_user_settings` as a inner join `#__pin_likes` as b on a.user_id = b.pin_like_user_id where b.pin_id = " . $pin . " and b.status=1 and b.pin_like='1'";
        $db->setQuery($query);
        $pinlikeuser = $db->loadObjectList();
        return $pinlikeuser;
    }

    function repinUser() {
        $db = $this->getDBO();
        $pin = JRequest::getInt('pinid');
        $query = "SELECT a.user_id, a.first_name, a.user_image, b.pin_id, b.status, b.pin_repin_id FROM `#__pin_user_settings` as a inner join `#__pin_pins` as b on a.user_id = b.pin_user_id where b.pin_repin_id = " . $pin . " and b.status=1";
        $db->setQuery($query);
        $repinuser = $db->loadObjectList();
        return $repinuser;
    }

    function repinBoard() {
        $db = $this->getDBO();
        $pin = JRequest::getInt('pinid');
        $query = "select a.pin_id,a.pin_user_id, a.pin_repin_id, a.pin_board_id, a.status, b.board_id, b.board_name, b.status,c.user_id,c.first_name,c.user_image from #__pin_pins as a inner join #__pin_boards as b on a.pin_board_id = b.board_id inner join #__pin_user_settings as c on c.user_id=a.pin_user_id where a.pin_repin_id=" . $pin . " order by a.created_date desc limit 0,10 ";
        $db->setQuery($query);
        $repinboard = $db->loadObjectList();
        return $repinboard;
    }

    function getPins() {
        $db = $this->getDBO();
        if (JRequest::getVar('category')) {
            $query = "select count(a.*) from #__pin_pins a left join #__pin_user_settings d on a.pin_user_id=d.user_id left join #__pin_boards e on e.board_id=a.pin_board_id left join #__pin_categories b on a.pin_category_id=b.category_id where a.status='1' and b.category_name='" . JRequest::getVar('category') . "' order by a.created_date desc ";
        } elseif (JRequest::getVar('popular')) {
            $query = "select count(a.*) from #__pin_pins a left join #__pin_user_settings d on a.pin_user_id=d.user_id left join #__pin_boards e on e.board_id=a.pin_board_id left join #__pin_categories b on a.pin_category_id=b.category_id  where a.status='1' order by a.pin_repin_count desc";
        } else {
            $query = " select count(*) from #__pin_pins as a where a.status='1' order by a.created_date desc";
        }

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
            $query = "select a.*,b.category_id,b.category_name,d.username,d.user_image,d.first_name,d.last_name,e.board_id,board_name from #__pin_pins a left join #__pin_user_settings d on a.pin_user_id=d.user_id left join #__pin_boards e on e.board_id=a.pin_board_id left join #__pin_categories b on a.pin_category_id=b.category_id where a.status='1' and b.category_name='" . JRequest::getVar('category') . "' order by a.created_date desc LIMIT $pageno,$length";
        } elseif (JRequest::getVar('popular')) {
            $query = "select a.*,b.category_id,b.category_name,d.username,d.user_image,d.first_name,d.last_name,e.board_id,board_name from #__pin_pins a left join #__pin_user_settings d on a.pin_user_id=d.user_id left join #__pin_boards e on e.board_id=a.pin_board_id left join #__pin_categories b on a.pin_category_id=b.category_id  where a.status='1' order by a.pin_repin_count desc LIMIT $pageno,$length";
        } else {
            $query = "select a.*,b.category_id,b.category_name,d.username,d.user_image,d.first_name,d.last_name,e.board_id,board_name from #__pin_pins a left join #__pin_user_settings d on a.pin_user_id=d.user_id left join #__pin_boards e on e.board_id=a.pin_board_id left join #__pin_categories b on a.pin_category_id=b.category_id  where a.status='1' order by a.created_date desc LIMIT $pageno,$length";
        }

        $db->setQuery($query);
        $pins = $db->loadObjectList(); //echo '<pre>';print_r($pins);

        return $pins;
    }

    function getSiteSettings() { //get Facebook api for the Flike
        $db = JFactory::getDbo();
        $query = "select setting_facebookapi from #__pin_site_settings Limit 1";
        $db->setQuery($query);
        $result = $db->loadResult();

        return $result;
    }

    function followUser($user, $fuser) {
        $pin_id = JRequest::getInt('pinid');
        $db = $this->getDBO();
        $query = "SELECT pin_user_id
                  FROM #__pin_pins
                  WHERE pin_id='$pin_id'";
        $db->setQuery($query);
        $fuser = $db->loadResult();



        $query = "SELECT board_id
                  FROM #__pin_boards 
                  WHERE user_id='$fuser'";
        $db->setQuery($query);
        $results = $db->loadcolumn();

        if (isset($results)) {
            $boardId = implode(',', $results);
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
        if (isset($boardId)) {
            if ($boardId == $followBoard) {

                $result = 'all';
                return $result;
            }
        } else {
            $query = "SELECT follow_user_id
                  FROM #__pin_follow_users
                  WHERE status=1
                  AND user_id='$user'";
            $db->setQuery($query);
            $follow_user_id = $db->loadResult();
            $query = "SELECT follow_user_board
                  FROM #__pin_follow_users
                  WHERE status=1
                  AND user_id='$user'
                  AND follow_user_id='$follow_user_id'";
            $db->setQuery($query);
            $followBoard = $db->loadcolumn();
            //return the selected boards alone
            $result = $followBoard;
            return $result;
        }
    }

    function getTopgooglead() {//get top google ad
        $db = JFactory::getDbo();
        $query = "select * from #__pin_googlead where pin_ad_position='pin_top' and status=1 Limit 1";
        $db->setQuery($query);
        $result = $db->loadObjectList();
        return $result;
    }

    function getBottomgooglead() {//get bottom google ad
        $db = JFactory::getDbo();
        $query = "select * from #__pin_googlead where pin_ad_position='pin_bottom' and status=1 Limit 1";
        $db->setQuery($query);
        $result = $db->loadObjectList();
        return $result;
    }

    function getUploadpins() {//get bottom google ad
        $db = JFactory::getDbo();
        $query = "select * from #__pin_pins where pin_url='' and status=1 ORDER BY created_date DESC limit 5";
        $db->setQuery($query);
        $uploadpins = $db->loadObjectList();

        return $uploadpins;
    }

    function getBookpins($url) {//get bottom google ad
        $db = JFactory::getDbo();
        $query = "select * from #__pin_pins where pin_type_id='0' AND pin_url =  '$url'
AND pin_repin_id =0 limit 5";

        $db->setQuery($query);
        $bookpins = $db->loadObjectList();

        return $bookpins;
    }

    function getUrlpins() {//get bottom google ad
        $db = JFactory::getDbo();

        $pinId = JRequest::getInt('pinid');
        $query = "SELECT a.pin_id,a.pin_board_id,a.pin_user_id,a.pin_description,a.pin_url,a.pin_image,a.pin_real_pin_id,a.pin_repin_count,a.pin_likes_count,a.link_type,a.gift,a.price,a.created_date,d.first_name,d.last_name,e.board_name
                  FROM #__pin_pins a
                  INNER JOIN #__pin_user_settings d
                  ON a.pin_user_id=d.user_id
                  INNER JOIN #__pin_boards e
                  ON e.board_id=a.pin_board_id
                  WHERE a.pin_id=$pinId";
        $db->setQuery($query);
        $pinDetails = $db->loadObject();
        $urlpins = '';
        if ($pinDetails->pin_url != '') {
            $query = "select * from #__pin_pins where pin_url='$pinDetails->pin_url' limit 5";
            $db->setQuery($query);
            $urlpins = $db->loadObjectList();
        }

        return $urlpins;
    }

//To get the pin details originally pinned by
    function getPinnedby() {
        $pin_id = JRequest::getInt('pinid');
        $db = JFactory::getDbo();
        $query = "SELECT pin_board_id,pin_user_id
        FROM `#__pin_pins`
        WHERE pin_id = (SELECT pin_real_pin_id FROM `#__pin_pins` WHERE pin_id =$pin_id AND STATUS =1 LIMIT 1)";
        $db->setQuery($query);
        $result = $db->loadObject();

        if ($result != "") {
            $query = "SELECT a.*,b.username FROM #__pin_pins AS a INNER JOIN #__pin_user_settings AS b ON a.pin_user_id=b.user_id WHERE a.pin_board_id=$result->pin_board_id AND a.status=1 LIMIT 5";
            $db->setQuery($query);
            $result = $db->loadObjectList();
        } else {

            $query = "SELECT pin_board_id FROM #__pin_pins where pin_id=$pin_id";
            $db->setQuery($query);
            $res = $db->loadObject();

            $query = "SELECT a.*,b.username FROM #__pin_pins AS a INNER JOIN #__pin_user_settings AS b ON a.pin_user_id=b.user_id WHERE a.pin_board_id =$res->pin_board_id AND a.status=1 LIMIT 5";
            $db->setQuery($query);
            $result = $db->loadObjectList();
        }

        return $result;
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

//Added by sathish for get the block status for the uset
    function getblockStatus() {
        $db = $this->getDBO();
        $user = Jfactory::getUser();
        $user_id = $user->id;
        $pinId = JRequest::getInt('pinid');
        $query = "select pin_user_id from #__pin_pins where pin_id=$pinId and status = 1";
        $db->setQuery($query);
        $users = $db->loadResult();
        $query = "select status from #__pin_user_block where user_id=$user_id and user_block_id = $users";
        $db->setQuery($query);
        $blockStatus = $db->loadObjectList();
        return $blockStatus;
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
    // used to display the orinal pin user name
     function getModRepinpinUserdetails($resultmodrepin) {
     //    $resultmodrepin stores the repinned pin id
        $db =  JFactory::getDBO();
        $query = "select concat(a.first_name,' ',a.last_name ) as
                username,b.pin_user_id,a.user_image from #__pin_user_settings as a inner join #__pin_pins as b
                on a.user_id=b.pin_user_id where b.pin_id = $resultmodrepin AND a.status = 1";
        $db->setQuery($query);
        $modrepinuserdetails = $db->loadObjectList();
        return $modrepinuserdetails;
    }

}
?>