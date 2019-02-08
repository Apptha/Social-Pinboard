<?php

/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component boardpage view
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class socialpinboardViewboardpage extends SocialpinboardView {

    public function display($tpl = null) {
        global $mainframe;
        $model = $this->getModel();

        //get pin categories
        $pinBoard = $model->getPinboard();
        $this->assignRef('pinBoard', $pinBoard);
        $boardname = $pinBoard[0]->board_name;
        $boardUser = $model->getBoardUser($boardname);
        $this->assignRef('boardUser', $boardUser);

        //get pins
        $editboard = $model->editBoard();
        $this->assignRef('editboard', $editboard);

        //get displayBoards
        $displayBoards = $model->displayBoards();
        $this->assignRef('displayBoards', $displayBoards);

        //get User Details
        $userDetails = $model->getUserdetails();
        $this->assignRef('userDetails', $userDetails);
$getCurrencySymbol = $model->getCurrencySymbol();
 $this->assignRef('getCurrencySymbol', $getCurrencySymbol);


        $getmobileBoards = $model->getmobileBoards();
        $this->assignRef('getmobileBoards', $getmobileBoards);


        parent::display($tpl);
        if (JRequest::getVar('offset')) {
            die ();
        }
    }

}
