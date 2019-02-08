<?php

/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component pinitdisplay view
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class socialpinboardViewpinitdisplay extends SocialpinboardView {

    public function display($tpl = null) {
        global $mainframe;

        $model = $this->getModel();

        //get boards name
        $boardname = $model->getBoards();
        $this->assignRef('boardname', $boardname);

        $savepinit = $model->Savepinit();
        $this->assignRef('savepinit', $savepinit);

        $currency = $model->getcurrency();
        $this->assignRef('currency', $currency);

        parent::display($tpl);
    }

}
