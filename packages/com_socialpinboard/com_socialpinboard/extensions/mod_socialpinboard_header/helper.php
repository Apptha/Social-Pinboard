<?php

/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Module header
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');

class modsocialpinboard_header {

    public static function getCategories() {
        $db = JFactory::getDBO();
        $query = "select category_id,category_name from #__pin_categories where status = 1 order by category_name";
        $db->setQuery($query);
        $categories = $db->loadObjectList();
        return $categories;
    }

    public static function getCategoryPins() {

        $db = JFactory::getDBO();
        $catgory_name = JRequest::getVar('category');

        $query = "SELECT a.category_name, b.board_id, c.pin_image
                  FROM #__pin_categories AS a
                  LEFT JOIN #__pin_boards AS b ON a.category_id = b.board_category_id
                  LEFT JOIN #__pin_pins AS c ON b.board_id = c.pin_board_id
                  WHERE a.category_name = '$catgory_name'";
        $db->setQuery($query);
        $category_pins = $db->loadObjectList();
        return $category_pins;
    }

    public static function getTopgooglead() {//get top google ad
        $db = JFactory::getDbo();
        $query = "select * from #__pin_googlead where pin_ad_position='home_top' and status=1 Limit 1";
        $db->setQuery($query);
        $result = $db->loadObjectList();
        return $result;
    }

    public static function getcurrencysymbol() {//get top google ad
        $db = JFactory::getDbo();
        $query = "select setting_show_request, setting_currency,setting_user_registration from #__pin_site_settings";
        $db->setQuery($query);
        $result = $db->loadObject();

        return $result;
    }

}

?>