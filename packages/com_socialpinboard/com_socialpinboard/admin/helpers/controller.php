<?php

/*
 ***********************************************************/
/**
 * @name          : Joomla HD Video Share
 * @version	      : 3.2.1
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
 * @abstract      : Contus HD Video Share Component Administrator Controller file
 * @Creation Date : March 2010
 * @Modified Date : March 2013
 * */
/*
 ***********************************************************/
// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.controller');

if (version_compare(JVERSION, '3.0', 'ge')) {

    class SocialpinboardController extends JControllerLegacy {
	
        public function display($cachable = false, $urlparams = array()) {
            parent::display($cachable, $urlparams);
        }
		
    }

} else if (version_compare(JVERSION, '2.5', 'ge')) {

    class SocialpinboardController extends JController {
	
        public function display($cachable = false, $urlparams = false) {
            parent::display($cachable, $urlparams);
        }

    }

} else {

    class SocialpinboardController extends JController {

        public function display($cachable = false, $urlparams = false) {
            parent::display($cachable);
        }

    }

}

?>