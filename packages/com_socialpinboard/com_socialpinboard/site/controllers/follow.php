<?php

/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component follow controller file
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');

// import Joomla controlleradmin library
jimport('joomla.application.component.controller');
jimport('joomla.application.component.controllerform');

class socialpinboardControllerfollow extends SocialpinboardController {

    function display($cachable = false, $urlparams = false) {
        $app = JFactory::getApplication();

        $user = Jfactory::getUser();
        if (!isset($user)) {
            $redirect = JRoute::_('index.php?option=com_socialpinboard&view=people', false);
            $app = JFactory::getApplication();
            $app->redirect($redirect);
        }
        parent::display();
    }

}