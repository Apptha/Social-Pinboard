<?php

/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component userlogin view
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.application');
jimport('joomla.application.component.view');

class socialpinboardViewuserlogin extends SocialpinboardView {

    function display($tpl = null) {

        $user = JFactory::getUser();
        // Get data from the model
        $model = $this->getModel('userlogin');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }

        $getTwitterDetails = $model->getTwitterDetails();
        $this->assignRef('getTwitterDetails', $getTwitterDetails);


        if (JRequest::getVar('submit')) {
            $reset_password = JRequest::getVar('password');
            $email = JRequest::getVar('email');
            $createLogin = $model->resetPassword($email, $reset_password);
        }
        if (JRequest::getVar('email') && JRequest::getVar('reset')) {
            $email = JRequest::getVar('email');
            $reset_code = JRequest::getVar('reset');
            $activation_code = $model->getActivationCode($email);
            $this->assignRef('activation_code', $activation_code);
        }
        // Display the template
        parent::display($tpl);
    }

}