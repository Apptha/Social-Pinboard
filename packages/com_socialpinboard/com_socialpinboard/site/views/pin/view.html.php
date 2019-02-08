<?php

/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component pin view
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class socialpinboardViewpin extends SocialpinboardView {

    public function display($tpl = null) {

        global $mainframe;
        $model = $this->getModel();
        if (JRequest::getVar('reports')) {
            $pinDetails = $model->sendReport();
            die ();
        }

        $user = Jfactory::getUser();
        $user_id = $user->id;

        if (JRequest::getVar('pinid')) {
            //function to get pin details
            $pinDetails = $model->getPindetails();
            $this->assignRef('pindetails', $pinDetails);
            //function to get board pins
            $boardPins = $model->getBoardpins();
            $this->assignRef('boardpins', $boardPins);
            //function to get reports
            $reports = $model->getReports();
            $this->assignRef('reports', $reports);

            $likes = $model->pinLike();
            $this->assignRef('pinLikes', $likes);
            //function to get upload pins
            $uploadpins = $model->getUploadpins();
            $this->assignRef('uploadpins', $uploadpins);
            //function to get book pins

            $url = $pinDetails->pin_url;

            $bookpins = $model->getBookpins($url);
            $this->assignRef('bookpins', $bookpins);

            //  function to get upload pins
            $urlpins = $model->getUrlpins();
            $this->assignRef('urlpins', $urlpins);


            //function to get the originnaly pined by

            $pinnedpins = $model->getPinnedby();
            $this->assignRef('pinnedpins', $pinnedpins);


            $boards = $model->getBoards();
            $this->assignRef('boards', $boards);
            //pin like user image
            $pinLikeUser = $model->pinLikeUser();
            $this->assignRef('pinLikeuser', $pinLikeUser);

            //Repin Board Name
            $repinBoard = $model->repinBoard();
            $this->assignRef('repinBoard', $repinBoard);

            $repinUser = $model->repinUser();
            $this->assignRef('repinuser', $repinUser);

            $getPins = $model->getPins();
            $this->assignRef('getPins', $getPins);

            //function to get facebook api
            $getSiteSettings = $model->getSiteSettings();
            $this->assignRef('siteSettings', $getSiteSettings);

            //google Ad
            $topGooglead = $model->getTopgooglead();

            $this->assignRef('topgooglead', $topGooglead);

            $bottomGooglead = $model->getBottomgooglead();
            $this->assignRef('bottomgooglead', $bottomGooglead);

            $getUserprofile = $model->getUserprofile();
            $this->assignRef('user_profile', $getUserprofile);

            $getProfile = $model->getProfile();
            $this->assignRef('profile', $getProfile);


            $getLikes = $model->getLikes();
            $this->assignRef('like_details', $getLikes);

            //Added by R.Sathish used for mobilerepin
            $getmobileBoards = $model->getmobileBoards();
            $this->assignRef('getmobileBoards', $getmobileBoards);

            if ($user_id) {
                $follow_user_details = $model->followUser($user_id, $getUserprofile[0]->user_id);

                $this->assignRef('follow_user_details', $follow_user_details);
            }
            $comment_details = $model->getComments();
            $this->assignRef('comment_details', $comment_details);
        }


        parent::display($tpl);
        if (JRequest::getVar('page')) {
            die ();
        }
    }

}
