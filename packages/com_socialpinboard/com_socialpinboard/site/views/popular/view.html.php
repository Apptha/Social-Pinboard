<?php

/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component popular view
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class socialpinboardViewpopular extends SocialpinboardView {

    public function display($tpl = null) {
        global $mainframe;
        $model = $this->getModel();


        $document = JFactory::getDocument();

        //get pins
        $pins = $model->getPins();
        $this->assignRef('pins', $pins);
        //update Password
        $password = JRequest::getVar('Password');
        if ($password) {
            $updatedPassword = $model->updatePassword($password);
        }

        $user_res = $model->getUserprofile();
        $this->assignRef('user_res', $user_res);
        $getmobileBoards = $model->getmobileBoards();
        $this->assignRef('getmobileBoards', $getmobileBoards);

$getCurrencySymbol = $model->getCurrencySymbol();
 $this->assignRef('getCurrencySymbol', $getCurrencySymbol);
        parent::display($tpl);
        if (JRequest::getVar('page')) {
            die();
        }
    }

}
