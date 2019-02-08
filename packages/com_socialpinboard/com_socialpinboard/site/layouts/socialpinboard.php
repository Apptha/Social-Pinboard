<?php

/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component layout file
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');

class thumb {

    function fthumb() {
        $pinId = JRequest::getVar('pinid');
        $db = JFactory::getDBO();
        if (isset($pinId)) {
            $query = "select a.pin_image,a.link_type,a.pin_description from #__pin_pins a left join #__users d on a.pin_user_id=d.id left join #__pin_boards e on e.board_id=a.pin_board_id left join #__pin_categories b on a.pin_category_id=b.category_id left join #__pin_comments f on a.pin_id=f.pin_id where a.pin_id=$pinId";
            $db->setQuery($query);
            $pinDetails = $db->loadObjectList();
            return $pinDetails;
        } else {
            return;
        }
    }

    function showRequest() {

        $db = JFactory::getDBO();
        $query = "SELECT setting_facebookapi FROM #__pin_site_settings";
        $db->setQuery($query);
        $show_request = $db->loadResult();
        return $show_request;
    }
}
?>