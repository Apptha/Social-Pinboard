<?php
/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component addplus view
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.application');
jimport('joomla.application.component.view');

class socialpinboardViewaddplus extends SocialpinboardView {

    /**
     * Customfields view display methods
     * @return void
     */
    function display($tpl = null) {

        global $mainframe;
                $user=Jfactory::getUser();
        $model = $this->getModel();
        $getCategories = $model->getCategories();//stores category list
        $this->assignRef('getCategories', $getCategories);
        $getBoardname = $model->getBoardname();//stores board name
        $this->assignRef('getBoardname', $getBoardname);
        $getUsergroup = $model->getUsergroup();//stores user group
        $this->assignRef('getUsergroup', $getUsergroup);
        // Display the template
        parent::display($tpl);
    }

}