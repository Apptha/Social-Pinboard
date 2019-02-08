<?php

/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component socialregister view
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

class socialpinboardViewSocialregister extends SocialpinboardView {

    public function display($tpl = null) {

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }
        // Assign the Data       
        $model = $this->getModel('socialregister');
        $getSocialUSerDetails = $model->getSocialUSerDetails();
        $this->assignRef('getSocialUSerDetails', $getSocialUSerDetails);
        // Display the template
        parent::display($tpl);
    }

}