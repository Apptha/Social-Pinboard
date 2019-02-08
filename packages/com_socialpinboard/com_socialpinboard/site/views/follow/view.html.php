<?php

/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component follow view
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.application');
jimport('joomla.application.component.view');

class socialpinboardViewfollow extends SocialpinboardView {

    function display($tpl = null) {
        global $mainframe;
        $fuser = JRequest::getInt('uid');
        $user = JFactory::getUser();
        $userId = $user->id;
        $model = $this->getModel();
        $getFollowing = $model->getFollowing();
        $this->assignRef('getFollowing', $getFollowing);
        $getFollower = $model->getFollower();
        $this->assignRef('getFollower', $getFollower);
        $countPins = $model->countPins();
        $this->assignRef('pincount', $countPins);
        //get Likes
        $getLikes = $model->getLikes();
        $this->assignRef('getLikes', $getLikes);
        //get total pins
        $totalBoard = $model->totalBoard();
        $this->assignRef('totalBoard', $totalBoard);
        $displayBoard = $model->displayBoard();
        $this->assignRef('displayBoard', $displayBoard);
        // Added by Sathish for get user details
        $userDetails = $model->getProfile();
        $this->assignRef('user_profile', $userDetails);
        // Added by Sathish for get block status of the user
        $getblockStatus = $model->getblockStatus();
        $this->assignRef('blockStatus', $getblockStatus);
        // Added by Sathish for get the reported users get by others
        $getReportUsers = $model->getReportUsers();
        $this->assignRef('getReportUsers', $getReportUsers);
        // Added by Sathish for get Repin activities of the users
        $repinactivities = $model->getRepinActivities();
        $this->assignRef('repinactivities', $repinactivities);
        // Added by Sathish for get users details
        $getUserprofile = $model->getUserprofile();
        $this->assignRef('userprofile', $getUserprofile);
        if ($userId) {
            $follow_user_details = $model->followUser($userId, $fuser);
            $this->assignRef('follow_user_details', $follow_user_details);
        }

        // Display the template
        parent::display($tpl);
    }

}