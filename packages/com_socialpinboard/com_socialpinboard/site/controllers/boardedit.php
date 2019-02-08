<?php

/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component boardedit controller file
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');

// import Joomla controllerform library
jimport('joomla.application.component.controllerform');

class socialpinboardControllerboardedit extends SocialpinboardController {

    function display($cachable = false, $urlparams = false) {
        $user = JFactory::getUser();

        JRequest::setVar('view', 'boardedit');
        $vName = $mName = 'boardedit';
        //assign layout
        $vLayout = 'default';
        if (!$user->id) {
            $app = JFactory::getApplication();
            $app->redirect(JRoute::_('index.php?option=com_socialpinboard&view=people', false));
        }
        $document = JFactory::getDocument();
        $vType = $document->getType();
        $view = $this->getView($vName, $vType);
        // Get/Create the model
        if ($model = $this->getModel($mName)) {
            // Push the model into the view (as default)
            $view->setModel($model, true);
        }
        // Set the layout

        $view->setLayout($vLayout);

        $view->display();
    }

}