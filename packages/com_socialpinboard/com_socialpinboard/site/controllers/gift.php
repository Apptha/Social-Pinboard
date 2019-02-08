<?php

/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component gift controller file
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');

// import Joomla controllerform library
jimport('joomla.application.component.controllerform');

class socialpinboardControllergift extends SocialpinboardController {

    function display($cachable = false, $urlparams = false) {

        $app = JFactory::getApplication();

        JRequest::setVar('view', 'gift');

        $flag = JRequest::getVar('q');


        if ($flag == '0') {
            $app->redirect(JRoute::_('index.php?option=com_socialpinboard&view=userfollow', false));
        }

        $model = $this->getModel('gift');

        $checkActivation = $model->checkActivation();

        if ($checkActivation == '1') {
            $app->redirect(JRoute::_('index.php?option=com_socialpinboard&view=userfollow', false));
        }

        parent::display();
    }

}