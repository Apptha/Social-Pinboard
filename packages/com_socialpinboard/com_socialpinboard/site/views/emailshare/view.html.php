<?php

/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component emailshare view
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class socialpinboardViewEmailShare extends SocialpinboardView {

    public function display($tpl = null) {
        global $mainframe;
        $model = $this->getModel('emailshare');
        if (JRequest::getVar('report')) {
            $successMeassage = $model->sendReport();
        } elseif (JRequest::getVar('reporttype')) {
            $successMeassage = $model->sendReporttype();
        } else if (JRequest::getVar('invite') == 'invitefriends') {
            $successMeassage = $model->Invitefriends();
        }else {
            //get pin categories
            $document = JFactory::getDocument();
            $document->addStyleSheet(JURI::base(true) . '/components/com_socialpinboard/css/pinboard.css', 'text/css', 'all');
            $successMeassage = $model->requestMail();
        }
        $this->assignRef('successMessage', $successMeassage);
        parent::display($tpl);
    }

}
