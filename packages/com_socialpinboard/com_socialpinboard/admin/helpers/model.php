<?php
/*
 ***********************************************************/
/**
 * @name          : Joomla HD Video Share
 * @version	  : 3.2.1
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
 * @abstract      : Contus HD Video Share Component Administrator modle file
 * @Creation Date : March 2010
 * @Modified Date : March 2013
 * */
/*
 ***********************************************************/
// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.model');

if (version_compare(JVERSION, '3.0', 'ge')) {

    class SocialpinboardModel extends JModelLegacy {
	
        public static function addIncludePath($path = '', $prefix = '') {
            return parent::addIncludePath($path, $prefix);
        }

    }

} else if (version_compare(JVERSION, '2.5', 'ge')) {

    class SocialpinboardModel extends JModel {
	
        public static function addIncludePath($path = '', $prefix = '') {
            return parent::addIncludePath($path, $prefix);
        }

    }

} else if (version_compare(JVERSION, '1.6', 'ge') || version_compare(JVERSION, '1.7', 'ge')) {

    class SocialpinboardModel extends JModel {

        public static function addIncludePath($path = '', $prefix = '') {
            return parent::addIncludePath($path, $prefix);
        }

    }

}else {

    class SocialpinboardModel extends JModel {

        public function addIncludePath($path = '', $prefix = '') {
            return parent::addIncludePath($path);
}

    }

}

?>