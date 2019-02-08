<?php

/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component userpins view
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class socialpinboardViewuserpins extends SocialpinboardView {

    public function display($tpl = null) {
        global $mainframe;
        $model = $this->getModel();

        //get pin categories
        $categories = $model->getCategories();
        $this->assignRef('categories', $categories);
        $document = &JFactory::getDocument();
        //get pins

        $pins = $model->getPins();
        $this->assignRef('pins', $pins);
        //get pins count
        $pinsCount = $model->getPinscount();
        $this->assignRef('pinscount', $pinsCount);
        $boards = $model->getBoards();

        $this->assignRef('boards', $boards);

        parent::display($tpl);
        if (JRequest::getVar('offset')) {
            die ();
        }
    }

}
