<?php

/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component userfollow controller file
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');

// import Joomla controlleradmin library
jimport('joomla.application.component.controller');
jimport('joomla.application.component.controllerform');

class socialpinboardControllerUserfollow extends SocialpinboardController {

    function display($cachable = false, $urlparams = false) {

        $followusercompl = JRequest::getVar('followusercompl');

        if ($followusercompl == 'CreateBoards') {

            //insert boards and redirect the user to home
            $model = $this->getModel('userfollow');
            $createBoards = $model->createBoards();
            $updateActivation = $model->updateActivation();
        }

        parent::display();
    }
}