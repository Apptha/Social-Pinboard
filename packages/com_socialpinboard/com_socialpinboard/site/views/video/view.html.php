<?php

/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component video view
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class socialpinboardViewvideo extends SocialpinboardView {

    public function display($tpl = null) {
        global $mainframe;
        $model = $this->getModel();

        //get pin categories
        $getPindisplay = $model->getPindisplay();
        $this->assignRef('getPindisplay', $getPindisplay);
        $getmobileBoards = $model->getmobileBoards();
        $this->assignRef('getmobileBoards', $getmobileBoards);

        //get pins
        $boards = $model->getBoards();

        $this->assignRef('boards', $boards);
$getCurrencySymbol = $model->getCurrencySymbol();
 $this->assignRef('getCurrencySymbol', $getCurrencySymbol);
        parent::display($tpl);
        if (JRequest::getVar('offset')) {
            die ();
        }
    }

}

?>