<?php

/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component pin controller file
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');

// import Joomla controllerform library
jimport('joomla.application.component.controllerform');

class socialpinboardControllerpin extends SocialpinboardController {

    function display($cachable = false, $urlparams = false) {
        JRequest::setVar('view', 'pin');
        parent::display();
    }

    function deleteComment($cachable = false, $urlparams = false) {
        $comment_id = JRequest::getVar('comment_id');
        $comment_pin_id = JRequest::getVar('comment_pin_id');
        $model = $this->getModel('pin');
        $getFacebookDetails = $model->deletecomment($comment_id, $comment_pin_id);
    }

}