<?php

/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component gettwitterdata view
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

class socialpinboardViewGettwitterdata extends SocialpinboardView {

    function display($tpl = null) {

        $user = JFactory::getUser();
        // Get data from the model
        $model = $this->getModel('gettwitterdata');

        $twitterLogin = $model->gettwiiterdata();
        $this->assignRef('userInfo', $twitterLogin);

        // Display the template
        parent::display($tpl);
    }

}