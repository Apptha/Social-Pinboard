<?php

/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component helper file
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');

abstract class socialpinboardHelper {

    public static function getKey() {
        global $option, $mainframe;
        $db = Jfactory::getDBO(); //to enable db connection
        $query = "SELECT lkey FROM #__pin_site_settings";
        $db->setQuery($query);
        $siteSettings = $db->loadResult();
        return $siteSettings; //return the final result
    }
}