<?php

/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component likes view
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class socialpinboardViewlikes extends SocialpinboardView {

    public function display($tpl = null) {
        global $mainframe;
        $fuser = JRequest::getInt('uid');
        $user = JFactory::getUser();
        $userId = $user->id;
        $model = $this->getModel();
        $fuser = JRequest::getInt('uid');
        $user = JFactory::getUser();
        $userId = $user->id;
        $likes = $model->getLikesinformation();
        $this->assignRef('likes', $likes);
        $displayBoard = $model->displayBoard();
        $this->assignRef('displayBoard', $displayBoard);
        $getLikes = $model->getLikescount();
        $this->assignRef('getLikes', $getLikes);
        $pinCounts = $model->countPins();
        $this->assignRef('pinCounts', $pinCounts);
        $totalBoards = $model->totalBoard();
        $this->assignRef('totalBoards', $totalBoards);
        // Added by Sathish for get user details
        $userDetails = $model->getProfile();
        $this->assignRef('user_profile', $userDetails);
        $FollowingInformation = $model->getFollowingInformation();
        $this->assignRef('FollowingInformation', $FollowingInformation);
        $followerInformation = $model->getFollowerInformation();
        $this->assignRef('followerInformation', $followerInformation);
        $followers = $model->followUser($userId, $fuser);
        $this->assignRef('followers', $followers);
        // Added by Sathish for get block status of the user
        $getblockStatus = $model->getblockStatus();
        $this->assignRef('blockStatus', $getblockStatus);
        // Added by Sathish for get the reported users get by others
        $getReportUsers = $model->getReportUsers();
        $this->assignRef('getReportUsers', $getReportUsers);
        $getUserprofile = $model->getUserprofile();
        $this->assignRef('userprofile', $getUserprofile);
        // Added by Sathish for get Repin activities of the users
        $repinactivities = $model->getRepinActivities();
        $this->assignRef('repinactivities', $repinactivities);
        // Added by Sathish for get the list of following users
        if ($userId) {
            $follow_user_details = $model->followUser($userId, $getUserprofile[0]->user_id);
            $this->assignRef('follow_user_details', $follow_user_details);
        }
        parent::display($tpl);
        if (JRequest::getVar('offset')) {
            die ();
        }
    }

}
