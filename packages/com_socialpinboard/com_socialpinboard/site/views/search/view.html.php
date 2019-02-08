<?php

/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component search view
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

class socialpinboardViewSearch extends SocialpinboardView {

    function display($tpl = null) {

        // Get data from the model
        $model = $this->getModel('search');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }

        $totalpageCount = $model->getSearchCount();
        $this->assignRef('totalPageCount', $totalpageCount);

        $displayBoard = $model->displayBoard();
        $this->assignRef('displayBoard', $displayBoard);

        $mainframe = JFactory::getApplication();
        // Get pagination request variables
        $limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
        $limitstart = JRequest::getInt('limitstart');

        $searches = $model->getSearchlist();
        $this->assignRef('searches', $searches);

        $totalBoard = $model->totalBoard();
        $this->assignRef('totalBoard', $totalBoard);
        $getBoardPins = $model->getBoardPins();

        $this->assignRef('getBoardPins', $getBoardPins);
$getCurrencySymbol = $model->getCurrencySymbol();
 $this->assignRef('getCurrencySymbol', $getCurrencySymbol);
 $getmobileBoards = $model->getmobileBoards();
        $this->assignRef('getmobileBoards', $getmobileBoards);

        // Display the template
        parent::display($tpl);
    }

}