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
 * @abstract      : Contus HD Video Share Component Administrator view file
 * @Creation Date : March 2010
 * @Modified Date : March 2013
 * */
/*
 ***********************************************************/
// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.view');

if (version_compare(JVERSION, '3.0', 'ge')) {

    class SocialpinboardView extends JViewLegacy { }

} else {

    class SocialpinboardView extends JView { }
	
}

?>