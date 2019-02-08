<?php

/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component home view
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class socialpinboardViewhome extends SocialpinboardView {

    public function display($tpl = null) {
        global $mainframe;
        $user = Jfactory::getUser();
        $model = $this->getModel();

        $document = JFactory::getDocument();

        //get pins
        $pins = $model->getPins();
        $this->assignRef('pins', $pins);
        $getPinscount = $model->getPinscount();
        $this->assignRef('getPinscount', $getPinscount);
        //update Password
        $password = JRequest::getVar('Password');
        if ($password) {
            $updatedPassword = $model->updatePassword($password);
        }

        $user_res = $model->getUserprofile();
        $this->assignRef('user_res', $user_res);

       $getCurrencySymbol = $model->getCurrencySymbol();
        $this->assignRef('getCurrencySymbol', $getCurrencySymbol);

         $getmobileBoards = $model->getmobileBoards();
            $this->assignRef('getmobileBoards', $getmobileBoards);

        parent::display($tpl);
        if (JRequest::getVar('page')) {
            die();
        }
    }

}