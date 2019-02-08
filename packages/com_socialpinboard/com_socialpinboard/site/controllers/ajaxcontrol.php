<?php

/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component ajaxcontroller file
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');

class socialpinboardControllerajaxcontrol extends SocialpinboardController {

    /**
     * Method to display the view
     */
    function display($cachable = false, $urlparams = false) {
        //get user id for logincheck
        parent::display();
    }

    function getrepin() {
        $res['board_id'] = JRequest::getVar('board_id');
        $res['description'] = JRequest::getVar('description');
        $res['repin_id'] = JRequest::getVar('repin_id');
        $res['pin_real_pin_id'] = JRequest::getVar('pin_real_pin_id');
        $res['pin_user_id'] = JRequest::getVar('pin_user_id');
        $model = $this->getModel('home');
        $result = $model->setPins($res);
        echo $result;
        die;
    }

    function getpininfo() {
        $mainframe = JFactory::getApplication();
        $pin_id = JRequest::getVar('pin_id');
        $model = $this->getModel('home');
        $res = $model->getPin($pin_id);

        $pin['pin_type_id'] = $res[0]->pin_type_id;
        $pin['pin_repin_id'] = $pin_id;
        $pin['pin_real_pin_id'] = $res[0]->pin_real_pin_id;
        $pin['pin_url'] = $res[0]->pin_url;
        if ($res[0]->link_type == 'youtube' || $res[0]->link_type == 'vimeo') {
            $pin['image'] = $res[0]->pin_image;
        } else {
            $pin['image'] = JURI::base() . 'images/socialpinboard/pin_medium/' . $res[0]->pin_image;
        }
        $pin['description'] = $res[0]->pin_description;
        $pin['board_id'] = $res[0]->pin_board_id;
        echo $result = implode("*&@#@%asdfbk", $pin);
        die;
    }

    function getlikeinfo() {

        $mainframe = JFactory::getApplication();
        $pin['pin_id'] = JRequest::getVar('pin_id');
        $pin['user_id'] = JRequest::getVar('user_id');
        $pin['pin_flag'] = JRequest::getVar('pin_flag');
        $model = $this->getModel('home');
        $res = $model->getLike($pin);
        echo $res;
        die;
    }

    function getcommentinfo() {

        $mainframe = JFactory::getApplication();
        $pin['pin_id'] = JRequest::getVar('pin_id');
        $pin['comment'] = JRequest::getVar('comment');
        $model = $this->getModel('home');
        $res = $model->getComment($pin);
        echo json_encode($res);
        die;
    }

    function get_newpin_notification() {

        $model = $this->getModel('home');
        $res = $model->getPinscount();
        echo $res;
        die;
    }

    function addnewboard() {

        $pin['user_id'] = JRequest::getVar('user_id');
        $pin['board_name'] = JRequest::getVar('board_name');

        $model = $this->getModel('home');
        $res = $model->addnewboard($pin);
        echo $res;
        die;
    }

    function followall() {
        $mainframe = JFactory::getApplication();
        $follow['user_id'] = JRequest::getVar('user_id');
        $follow['fuser_id'] = JRequest::getVar('fuser_id');
        $model = $this->getModel('home');
        $result = $model->getFollow($follow);
        echo $result;
        die;
    }

    function unfollowall() {
        $mainframe = JFactory::getApplication();
        $unFollow['user_id'] = JRequest::getVar('user_id');
        $unFollow['fuser_id'] = JRequest::getVar('fuser_id');
        $model = $this->getModel('home');
        $result = $model->getUnFollow($unFollow);
        echo $result;
        die;
    }

    function followboard() {
        $mainframe = JFactory::getApplication();
        $unFollowBoard['user_id'] = JRequest::getVar('user_id');
        $unFollowBoard['fuser_id'] = JRequest::getVar('fuser_id');
        $unFollowBoard['board_id'] = JRequest::getVar('boardid');
        $model = $this->getModel('home');
        $result = $model->getFollowBoard($unFollowBoard);
        echo $result;
        die;
    }

    function unfollowboard() {
        $mainframe = JFactory::getApplication();
        $unFollowBoard['user_id'] = JRequest::getVar('user_id');
        $unFollowBoard['fuser_id'] = JRequest::getVar('fuser_id');
        $unFollowBoard['board_id'] = JRequest::getVar('boardid');

        $model = $this->getModel('home');
        $result = $model->getUnFollowBoard($unFollowBoard);
        echo $result;
        die;
    }

    function getcategoryimage() {
        $mainframe = JFactory::getApplication();
        $category['userId'] = JRequest::getVar('userId');
        $category['category_id'] = JRequest::getVar('cid');

        $model = $this->getModel('userfollow');
        $result = $model->getFollowersCategory($category);
        echo $result;
        die;
    }

    function getuncategoryimage() {
        $mainframe = JFactory::getApplication();
        $category['userId'] = JRequest::getVar('userId');
        $category['category_id'] = JRequest::getVar('cid');

        $model = $this->getModel('userfollow');
        $result = $model->getFollowersUnCategory($category);
        echo $result;
        die;
    }

    function checkUserName() {

        $mainframe = JFactory::getApplication();
        $user_name = JRequest::getVar('username');

        $model = $this->getModel('mailfriends');
        $results = $model->checkUserName($user_name);
        echo $results;
        die();
    }

    function checkEmail() {

        $mainframe = JFactory::getApplication();

        $email = JRequest::getVar('email');

        $model = $this->getModel('mailfriends');
        $results = $model->checkEmail($email);
        echo $results;
        die();
    }

    function addcontributers() {
        $mainframe = JFactory::getApplication();
        $contributors = JRequest::getVar('name_contributers');
        $model = $this->getModel('home');
        $result = $model->contributers($contributors);
        echo $result;
        die;
    }

    function getcontributers() {
        $mainframe = JFactory::getApplication();

        $user_contributers['user'] = JRequest::getVar('user');
        $user_contributers['bidd'] = JRequest::getVar('bidd');

        $model = $this->getModel('boardedit');
        $results = $model->getContributers($user_contributers);

        $inc = 0;
        $username = '';
        if ($results != '') {
            foreach ($results as $result) {

                if ((count($results) - 1) == $inc) {
                    $username['username'][] = $result->username;
                    $username['userid'][] = $result->user_id;
                } else {

                    $username['username'][] = $result->username;
                    $username['userid'][] = $result->user_id;
                }
                $inc++;
            }
        } else {
            $username = "no user";
        }

        echo json_encode($username);

        die;
    }

    function removeContributers() {
        $mainframe = JFactory::getApplication();
        $user_contributers['user_id'] = JRequest::getVar('user_id');
        $user_contributers['bidd'] = JRequest::getVar('bidd');
        $model = $this->getModel('boardedit');
        $results = $model->removeContributers($user_contributers);
        echo $results;
        die;
    }

    function addBoardContributers() {
        $mainframe = JFactory::getApplication();

        $user_contributers['name_contributer'] = JRequest::getVar('name_contributer');
        $user_contributers['user_id_contributors'] = JRequest::getVar('user_id_contributors');
        $user_contributers['bidd'] = JRequest::getVar('bidd');

        $model = $this->getModel('boardedit');
        $results = $model->addBoardContributers($user_contributers);


        echo $results;
        die;
    }

    function justMeContributors() {
        $mainframe = JFactory::getApplication();
        $user_contributers_bidd = JRequest::getVar('bidd');
        $model = $this->getModel('boardedit');
        $results = $model->justMeContributors($user_contributers_bidd);
        echo $results;
        die;
    }

    function deleteComment() {
        $user_comment_id = JRequest::getVar('comment_id');
        $comment_pin_id = JRequest::getVar('comment_pin_id');
        $model = $this->getModel('home');
        $results = $model->deleteComment($user_comment_id, $comment_pin_id);
        echo $results;
        die;
    }

    function checkPassword() {
        $user_email = JRequest::getVar('email');
        $user_password = JRequest::getVar('password');
        $twitter_id = JRequest::getVar('twitter_id');
        $model = $this->getModel('userlogin');
        $username = JRequest::getVar('username');
        $results = $model->checkPassword($user_email, $user_password, $twitter_id, $username);
        echo $results;
        die;
    }

    function block() {
        $mainframe = JFactory::getApplication();
        $follow['user_id'] = JRequest::getVar('user_id');
        $follow['fuser_id'] = JRequest::getVar('fuser_id');
        $model = $this->getModel('home');
        $result = $model->getUnFollow($follow);
        $result = $model->getBlock($follow);
        echo $result;
        die;
    }

    function unblock() {
        $mainframe = JFactory::getApplication();
        $unFollow['user_id'] = JRequest::getVar('user_id');
        $unFollow['fuser_id'] = JRequest::getVar('fuser_id');
        $model = $this->getModel('home');
        $result = $model->getUnblock($unFollow);
        echo $result;
        die;
    }

}

?>