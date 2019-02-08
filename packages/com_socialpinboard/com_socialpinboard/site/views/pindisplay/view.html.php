<?php

/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component pindisplay view
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class socialpinboardViewpindisplay extends SocialpinboardView {

    public function display($tpl = null) {
        global $mainframe;
        $fuser = JRequest::getInt('uid');
        $user = JFactory::getUser();
        $userId = $user->id;
        $model = $this->getModel();

        if($userId!=0 || $fuser!=0)
        {
        // Added by Sathish for get block status of the user
        $getblockStatus = $model->getblockStatus();
        $this->assignRef('blockStatus', $getblockStatus);
        //get pin categories
        $getPindisplay = $model->getPindisplay();
        $this->assignRef('getPindisplay', $getPindisplay);
        //get pins
        $displayBoard = $model->displayBoard();
        $this->assignRef('displayBoard', $displayBoard);
        $getLikes = $model->getLikes();
        $this->assignRef('getLikes', $getLikes);
        $totalBoard = $model->totalBoard();
        $this->assignRef('totalBoard', $totalBoard);
        $pinCounts = $model->countPins();
        $this->assignRef('pinCounts', $pinCounts);
         $getmobileBoards = $model->getmobileBoards();
        $this->assignRef('getmobileBoards', $getmobileBoards);

        $followers = $model->followUser($userId, $fuser);
        $this->assignRef('followers', $followers);
        // Added by Sathish for get user details
        $userDetails = $model->getProfile();
        $this->assignRef('user_profile', $userDetails);
        $boards = $model->getBoards();
        $this->assignRef('boards', $boards);
        $FollowingInformation = $model->getFollowingInformation();
        $this->assignRef('FollowingInformation', $FollowingInformation);
        $followerInformation = $model->getFollowerInformation();
        $this->assignRef('followerInformation', $followerInformation);
        // Added by Sathish for get Repin activities of the users
        $repinactivities = $model->getRepinActivities();
        $this->assignRef('repinactivities', $repinactivities);
        // Added by Sathish for get the reported users get by others
        $getReportUsers = $model->getReportUsers();
        $this->assignRef('getReportUsers', $getReportUsers);
        // Added by Sathish for get users details
        $getUserprofile = $model->getUserprofile();
        $this->assignRef('userprofile', $getUserprofile);

        if ($userId) {
            $follow_user_details = $model->followUser($userId, $getUserprofile[0]->user_id);
            $this->assignRef('follow_user_details', $follow_user_details);
        }
        $getCurrencySymbol = $model->getCurrencySymbol();
 $this->assignRef('getCurrencySymbol', $getCurrencySymbol);
        parent::display($tpl);
        if (JRequest::getVar('offset')) {
            die ();
        }
    }else{
        $app = JFactory::getApplication();
     $app->redirect(JURI::base());
    }
    }

}

?>