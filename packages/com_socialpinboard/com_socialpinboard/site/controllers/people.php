<?php

/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component people controller file
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');

// import Joomla controlleradmin library
jimport('joomla.application.component.controller');
jimport('joomla.application.component.controllerform');

class socialpinboardControllerPeople extends SocialpinboardController {

    function display($cachable = false, $urlparams = false) {

        $task = JRequest::getVar('task');
        $flag = JRequest::getVar('q');
        $returnResult = JRequest::getVar('returnURL');
        if (isset($_SERVER['HTTP_REFERER'])) {
            $session = JFactory::getSession();
            $session->set('theReferrer', $_SERVER['HTTP_REFERER'], 'com_socialpinboard');
        }
        $app = JFactory::getApplication();

        if ($task == 'nologin') {
            $redirect = JRoute::_('index.php?option=com_socialpinboard&view=home', false);
            $app = JFactory::getApplication();
            $app->redirect($redirect, $msg = 'Your Email is not associated with any account please request an invite to create an account.', $msgType = 'message');
        } else if ($task == 'blocked') {
            $redirect = JRoute::_('index.php?option=com_socialpinboard&view=home', false);
            $app = JFactory::getApplication();
            $app->redirect($redirect, $msg = 'Please Contact Administrator!You have been Blocked! ', $msgType = 'message');
        } else if ($task == 'available') {
            $redirect = JRoute::_('index.php?option=com_socialpinboard&view=home', false);
            $app = JFactory::getApplication();
            $app->redirect($redirect, $msg = 'This Email or Username is already registered with us!', $msgType = 'message');
        } else {
            $user = JFactory::getUser();
            if ($user->id) {

                $model = $this->getModel('people');
                $getFacebookDetails = $model->getFacebookDetails();

                if ($user->id && $getFacebookDetails > 0) {

                    if ($returnResult != '') {

                        $redirecturl = base64_decode($returnResult);
                        $app->redirect(JRoute::_($redirecturl, false));
                    } else {
                        if ($flag == '0' || $flag == '') {

                            $app->redirect(JRoute::_('index.php?option=com_socialpinboard&view=userfollow', false));
                        } else {
                            if (isset($_SERVER['HTTP_REFERER'])) {

                                $redirectTo = $session->get('theReferrer', '', 'com_socialpinboard');

                                if (!isset($_SESSION['facebook_login']) && !isset($_SESSION['oauth_token_secret'])) {
                                    $redirectTo = base64_decode($redirectTo);
                                }
                                $app->redirect($redirectTo);
                            } else {

                                $app->redirect(JRoute::_('index.php?option=com_socialpinboard&view=home', false));
                            }
                        }
                    }
                } else if ($user->id && $getFacebookDetails == 0) {

                    $app = JFactory::getApplication();
                    $app->redirect(JRoute::_('index.php?option=com_socialpinboard&view=socialregister', false));
                }
            } else {

                $vName = $mName = 'people';
                //assign layout
                $vLayout = 'login';
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

    public function logout($cachable = false, $urlparams = false) {

        $app = JFactory::getApplication();

        // Perform the log in.
        $app->logout();

        if (!($error instanceof Exception)) {

            // Redirect the user.
            $app->redirect(JURI::base());
        } else {
            $app->redirect(JRoute::_('index.php?option=com_users&view=login', false));
        }
    }

}