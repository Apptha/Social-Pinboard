<?php
/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component Board Edit model
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */

// No direct Access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');

class socialpinboardModelboardedit extends SocialpinboardModel {

    function getBoardEdit() {
        $db = $this->getDBO();
        $app = JFactory::getApplication();
        $bid = JRequest::getVar('bidd');
        $query = "select user_id,board_id,board_name,board_description,board_category_id,cuser_id,status,board_access from #__pin_boards where status=1 and board_id=" . $bid;
        $db->setQuery($query);
        $BoardEdit = $db->loadObject();
        return $BoardEdit;
    }

    function getCategory() {
        $db = $this->getDBO();
        $query = "SELECT category_name,category_id FROM #__pin_categories WHERE status='1' ORDER BY category_name";
        $db->setQuery($query);
        $category = $db->loadObjectList();
        return $category;
    }

    function updateBoard() {

        $db = $this->getDBO();
        $user = JFactory::getUser();
        $bid = JRequest::getVar('bidd');
        $userId = $user->get('id');
        $boardName = JRequest::getVar('editboard_name');
        $boardDesc = addslashes(JRequest::getVar('board_description'));
        $boardAccess = JRequest::getVar('editboard_board_access');
        $boardcat = JRequest::getVar('editboard_category');
        $saveChanges = JRequest::getVar('update_board');
        $bid = JRequest::getVar('bidd');
        $app = JFactory::getApplication();

        if (isset($saveChanges)) {

            $query = "select board_id from #__pin_boards where board_name='$boardName' and (user_id=$userId or cuser_id=$userId) and board_id!=$bid";
            $db->setQuery($query);
            $pin_user_info = $db->loadObjectList();
            if (empty($pin_user_info)) {

                $query = 'UPDATE `#__pin_boards` SET `user_id`="' . $userId . '",`board_name` ="' . $boardName . '",`board_description`="' . $boardDesc . '",`board_category_id`="' . $boardcat . '",`board_access`="' . $boardAccess . '" WHERE `board_id`=' . $bid;
                $db->setQuery($query);
                $db->query();
                $msg = JText::_('COM_SOCIALPINBOARD_BOARD_SAVED_SUCCESSFULLY');
            } else {
                $msg = JText::_('COM_SOCIALPINBOARD_BOARD_ALREADY_EXIST');
            }

            $app = JFactory::getApplication();
            $app->redirect(JRoute::_('index.php?option=com_socialpinboard&view=boardpage&bId=' . $bid), $msg, '');
        }
    }

    function deleteBoard() {
        $db = $this->getDBO();
        $user = JFactory::getUser();
        $userId = $user->get('id');
        $bid = JRequest::getVar('bidd');
        $btnDelete = JRequest::getVar('delete_board');
        $app = JFactory::getApplication();
        if (isset($btnDelete)) {
            $delete_board = "update  #__pin_boards set status='0' where board_id=" . $bid;
            $db->setQuery($delete_board);
            $db->query();
            $delete_pins = "update  #__pin_pins set status='0' where pin_board_id=" . $bid;
            $db->setQuery($delete_pins);
            $db->query();
            $app->redirect(JRoute::_('index.php?option=com_socialpinboard&view=boarddisplay'));
        }
    }

    function boardContributers() {
        $bid = JRequest::getVar('bidd');
        $db = JFactory::getDbo();

        $query = "select cuser_id from #__pin_boards where status=1 and board_id='$bid'";
        $db->setQuery($query);
        $c_boards = $db->loadResult();

        $c_users = explode(",", $c_boards);

        foreach ($c_users as $c_user) {

            $query = "select user_id,first_name,last_name,user_image,username from #__pin_user_settings where user_id='$c_user'";
            $db->setQuery($query);
            $c_user_details = $db->loadobject();
            $c_user_details_new[] = $c_user_details;
        }
        if ($c_user_details_new[0] != '') {
            return $c_user_details_new;
        }
    }

    function removeContributers($user_contributers) {

        $vari = '';
        $db = JFactory::getDbo();
        $query = "select cuser_id from #__pin_boards where status=1 and board_id='" . $user_contributers['bidd'] . "'";
        $db->setQuery($query);
        $c_boards = $db->loadResult();
        $c_users = explode(",", $c_boards);

        foreach ($c_users as $c_user) {
            if ($c_user == $user_contributers['user_id']) {
                
            } else {

                $vari.=$c_user . ',';
            }
        }
        $vari = rtrim($vari, ",");
        $query = "UPDATE #__pin_boards SET cuser_id='" . $vari . "'  where status=1 and board_id='" . $user_contributers['bidd'] . "'";
        $db->setQuery($query);
        $db->query();
        $query = "select cuser_id from #__pin_boards where status=1 and board_id='" . $user_contributers['bidd'] . "'";

        $db->setQuery($query);
        $c_boards = $db->loadResult();
        if ($c_boards == '') {
            $query = "UPDATE #__pin_boards SET  board_access =0   where status=1 and board_id='" . $user_contributers['bidd'] . "'";
            $db->setQuery($query);
            $db->query();
        }
    }

    function addBoardContributers($user_contributers) {

        $db = JFactory::getDbo();
        $name_contributer = $user_contributers['name_contributer'];
        $name_contributer = str_replace('%20', ' ', $name_contributer);
        $query = "select concat(first_name,' ',last_name ) as username from #__pin_user_settings where user_id=" . $user_contributers['user_id_contributors'];
        $db->setQuery($query);
        $c_user_id = $db->loadResult();
        if (trim($user_contributers['name_contributer']) == trim($c_user_id)) {
            $query = "select user_image,user_id from #__pin_user_settings where user_id= '" . $user_contributers['user_id_contributors'] . "'";
            $db->setQuery($query);
            $c_user_details = $db->loadObject();

            if (count($c_user_details) != 0) {
                $query = "select cuser_id from #__pin_boards  where status=1 and board_id='" . $user_contributers['bidd'] . "'";
                $db->setQuery($query);
                $c_user_id = $db->loadResult();

                $con_user_id = explode(',', $c_user_id);

                if (in_array($c_user_details->user_id, $con_user_id)) {
                    return 0;
                }
                if ($c_user_details != '') {
                    if ($c_user_id == '') {

                        $c_user_id = $c_user_details->user_id;
                    } else {
                        $c_user_id.=',' . $c_user_details->user_id;
                    }
                    $query = "UPDATE #__pin_boards SET cuser_id='" . $c_user_id . "' , board_access =1  where status=1 and board_id='" . $user_contributers['bidd'] . "'";
                    $db->setQuery($query);
                    $db->query();
                    $user = JFactory::getUser();
                    $userID = $user->id;
                    $board_id = $user_contributers['bidd'];
                    $followinguser = $user_contributers['user_id_contributors'];
                    $query = "INSERT INTO `#__pin_follow_users` ( `user_id`, `follow_user_id`, `follow_user_board`, `status`, `created_date`, `updated_date`) VALUES ( $followinguser, $userID, '$board_id', '1', NOW(),NOW())";
                    $db->setQuery($query);
                    $db->query();

                    $c_user_details = array($c_user_details->user_image, $c_user_details->user_id);

                    $c_user_details = implode(",", $c_user_details);
                }
            }
        } else {
            $c_user_details = JText::_('COM_SOCIALPINBOARD_USERNAME_NOT_EXIST');
        }
        return $c_user_details;
    }

    //get the search result
    function getContributers($user_contributers) {

        $username = $user_contributers['user'];
        $boardid = $user_contributers['bidd'];
        $user = JFactory::getUser();
        $userID = $user->id;
        $db = JFactory::getDbo();
        $query = "select concat(first_name,' ',last_name ) as username,user_id from #__pin_user_settings where user_id in (SELECT b.user_id FROM #__users AS a INNER JOIN #__pin_user_settings AS b on b.user_id=a.id INNER JOIN #__pin_follow_users AS c ON c.follow_user_id = b.user_id WHERE c.user_id =  '$userID') and (first_name like '%$username%' or last_name like '%$username%' or email like '$username%') and user_id!='$userID'";
        $db->setQuery($query);
        $db->query();
        $contributer = $db->loadObjectList();



        if (empty($contributer)) {
            return;
        }
        else
            return $contributer;
    }

    function justMeContributors($user_contributers_bidd) {
        $db = JFactory::getDbo();
        $user = JFactory::getUser();
        $userID = $user->id;
        $query = "UPDATE #__pin_boards SET cuser_id='' WHERE status=1 and board_id='" . $user_contributers_bidd . "' AND user_id =$userID";
        $db->setQuery($query);
        $db->query();
    }

    function getUserBoardDetails() {
        $db = JFactory::getDbo();
        $user = JFactory::getUser();
        $userID = $user->id;
        $bid = JRequest::getInt('bidd');
        $query = "SELECT a.user_image, CONCAT( a.first_name,  ' ', a.last_name ) AS username
                FROM #__pin_user_settings AS a
                LEFT JOIN #__pin_boards AS b ON a.user_id = b.user_id
                WHERE a.status =1
                AND b.status =1
                AND b.board_id =$bid";
        $db->setQuery($query);
        $boardUserDetails = $db->loadObject();

        return $boardUserDetails;
    }

}

?>