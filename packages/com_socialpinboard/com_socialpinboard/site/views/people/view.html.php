<?php

/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component people view
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.application');
jimport('joomla.application.component.view');

class socialpinboardViewPeople extends SocialpinboardView {

    function display($tpl = null) {

        $user = JFactory::getUser();
        // Get data from the model
        $model = $this->getModel('people');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }
        $getFacebookDetails = $model->getFacebookDetails();
        $registeration = $model->registeration();
        $this->assignRef('registeration', $registeration);
        $showRequest = $model->showRequest();
        $this->assignRef('showRequest', $showRequest);

        if (JRequest::getVar('email') && JRequest::getVar('activaton')) {

            $createLogin = $model->createLogin();
        }

        // Display the template
        parent::display($tpl);
    }

}