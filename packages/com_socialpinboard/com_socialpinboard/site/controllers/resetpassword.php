<?php

/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component resetpassword controller file
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');

// import Joomla controllerform library
jimport('joomla.application.component.controllerform');

/**
 *  Socialpinboard task Controller
 */
class socialpinboardControllerresetPassword extends SocialpinboardController {

    function display($cachable = false, $urlparams = false) {
        $user = JFactory::getUser();

        JRequest::setVar('view', 'resetpassword');
        if ($user->id) {

            $vName = $mName = 'home';
            //assign layout
            $vLayout = 'default';
        } else {
            $vName = $mName = 'resetpassword';
            //assign layout
            $vLayout = 'default';
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