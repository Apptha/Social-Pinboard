<?php

/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component boarddisplay controller file
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');

// import Joomla controllerform library
jimport('joomla.application.component.controllerform');

class socialpinboardControllerboarddisplay extends SocialpinboardController {

    function display($cachable = false, $urlparams = false) {
        $user = JFactory::getUser();
        $users = JRequest::getVar('uid');
        if ($users != '' || $user->id != 0) {
            JRequest::setVar('view', 'boarddisplay');
            parent::display();
        } else {
            $redirectTo = JRoute::_('index.php?option=com_socialpinboard&view=people', false);
            $this->setRedirect($redirectTo, $msg);
        }
    }

}