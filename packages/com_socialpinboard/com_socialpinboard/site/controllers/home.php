<?php

/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component home controller file
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');

// import Joomla controllerform library
jimport('joomla.application.component.controllerform');

class socialpinboardControllerhome extends SocialpinboardController {

    function display($cachable = false, $urlparams = false) {

        $app = JFactory::getApplication();

        JRequest::setVar('view', 'home');
        $flag = JRequest::getVar('newlogin');

        if ($flag == '1') {

            $app->redirect(JRoute::_('index.php?option=com_socialpinboard&view=userfollow', false));
        }
        $model = $this->getModel('home');
        $checkActivation = $model->checkActivation();

        if ($checkActivation == '1') {
            $app->redirect(JRoute::_('index.php?option=com_socialpinboard&view=userfollow', false));
        }

        parent::display();
    }

}