<?php

/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component invitefriends view
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class socialpinboardViewInvitefriends extends SocialpinboardView {

    public function display($tpl = null) {
        global $mainframe;
        $model = $this->getModel('invitefriends');
        $document = JFactory::getDocument();
        $getFacebookSettings = $model->getFacebookSettings();
        $url = JURI::getInstance();
        if (!JRequest::getVar('task') == "gmail") {
            if (strpos($url, 'code=')) {
                $addFacebookProfile = $model->addFacebookProfile($getFacebookSettings);
            }
        }
        $this->assignRef('getFacebookSettings', $getFacebookSettings);
        $getFacebookprofileId = $model->getFacebookprofileId();
        $this->assignRef('getFacebookprofileId', $getFacebookprofileId);
        parent::display($tpl);
    }

}
