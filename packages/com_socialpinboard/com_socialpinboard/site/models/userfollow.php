<?php

/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component userfollow model
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');
jimport('joomla.user.helper');

class socialpinboardModelUserfollow extends SocialpinboardModel {

    function getCategories() {
        $db = JFactory::getDbo();
        $select = "SELECT category_name,category_image,category_id
                   FROM #__pin_categories 
                   WHERE status='1'";
        $db->setQuery($select);
        $category_Name = $db->loadObjectList();
        return $category_Name;
    }

    //Store the user categories 
    function getFollowersCategory($category) {

        $cid = $category['category_id'];
        $user = JFactory::getUser();
        $userId = $user->id;

        //check already recod exits or not
        $db = $this->getDBO();

        $query = "SELECT board_name,user_id,board_id 
                 FROM #__pin_boards 
                 WHERE board_category_id='$cid' 
                 AND status='1'";

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

        $query = "SELECT follow_categories 
                  FROM #__pin_follow_users 
                  WHERE follow_categories='$cid' 
                  AND status='1' ";
        $db->setQuery($query);
        $idAvail = $db->loadResult();

        //if already record exists, update the details
        if ($idAvail < 1 && $boardId != '' && $boardId != '0') {
            //insert new record
            $db = $this->getDBO();
            $query = "INSERT INTO `#__pin_follow_users` ( `user_id`, `follow_categories`, `follow_user_board`, `status`, `created_date`, `updated_date`) VALUES ( $userId, '$cid', '$boardId', '1', NOW(),NOW())";

            $db->setQuery($query);
            $db->query();
        } else {
            $query = "UPDATE  #__pin_follow_users  SET follow_user_board='$boardId' WHERE follow_categories='$cid'";
            $db->setQuery($query);
            $db->query();
        }
    }

    //to unselect the categories of the user while sign up



    function getFollowersUnCategory($category) {

        $cid = $category['category_id'];
        $user = JFactory::getUser();
        $userId = $user->id;

        //check already recod exits or not
        $db = $this->getDBO();
        $query = "DELETE FROM #__pin_follow_users WHERE follow_categories='$cid' AND user_id=$userId AND status='1'";
        $db->setQuery($query);
        $db->query();
    }

    //To select the categories for the newly available  user to follow
    function getCategory() {
        $db = JFactory::getDBO();
        $user = JFactory::getUser();
        $userId = $user->id;

        if ($userId != '' && $userId != '0') {

            $query = "SELECT DISTINCT c.user_id, c.user_image, c.first_name, c.last_name,c.status
                        FROM #__pin_follow_users AS a
                        LEFT JOIN #__pin_boards AS b ON a.follow_categories = b.board_category_id
                        LEFT JOIN #__pin_user_settings AS c ON c.user_id = b.user_id
                        INNER JOIN #__pin_categories AS d ON a.follow_categories = d.category_id where c.status=1 
                        ORDER BY c.created_date desc LIMIT 0,10 ";

            $db->setQuery($query);
            $result = $db->loadObjectList();

            if (empty($result)) {

                $result = "No Categories";
                return $result;
            } else {

                return $result;
            }
        }
    }

    function createBoards() {
        $db = JFactory::getDBO();
        $user = JFactory::getUser();
        $userId = $user->id;

        $board1 = JRequest::getVar('board1');
        $board2 = JRequest::getVar('board2');
        $board3 = JRequest::getVar('board3');
        $board4 = JRequest::getVar('board4');
        $board5 = JRequest::getVar('board5');
        $boards = array($board1, $board2, $board3, $board4, $board5);
        $query = "SELECT  `category_id`
                FROM  `#__pin_categories` 
                WHERE  `status` =1
                LIMIT 1";
        $db->setQuery($query);
        $category_id = $db->loadResult();
        foreach ($boards as $board) {
            $query = "INSERT INTO `#__pin_boards` (`board_id`, `user_id`, `board_name`, `board_description`, `board_category_id`, `board_access`, `status`, `created_date`, `updated_date`) VALUES ('','$userId', '" . $board . "', '', '$category_id', '1', '1', NOW(), NOW())";
            $db->setQuery($query);
            $db->query();
        }

        $query = "SELECT count(id)
                  FROM #__pin_follow_users 
                  WHERE follow_user_id	='0' 
        AND user_id=$userId ";
        $db->setQuery($query);
        $idAvail = $db->loadResult();
        if ($idAvail >= 1) {
            $db = $this->getDBO();
            $query = "DELETE FROM #__pin_follow_users WHERE follow_user_id	='0' AND user_id=$userId ";
            $db->setQuery($query);
            $db->query();
        }
    }

    function updateActivation() {

        $user = JFactory::getUser();
        $user_id = $user->id;
        if ($user_id != '') {
            $db = JFactory::getDBO();
            $query = "UPDATE `#__pin_user_activation` AS a 
                      LEFT JOIN #__users AS b ON (a.email = b.email) 
                      SET a.activationcode='2' WHERE b.id =" . $user_id;

            $db->setQuery($query);
            $db->query();
            $redirect = JRoute::_('index.php?option=com_socialpinboard&view=home', false);
            $app = JFactory::getApplication();
            $app->redirect($redirect);
        }
    }

}