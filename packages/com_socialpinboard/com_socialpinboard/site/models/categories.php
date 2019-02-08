<?php

/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component categories model
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');
jimport('joomla.user.helper');

class socialpinboardModelcategories extends SocialpinboardModel {

    function getCategories() {
        $db = $this->getDBO();
        $query = "select category_id,category_name from #__pin_categories where status = 1 LIMIT 0,32";
        $db->setQuery($query);
        $categories = $db->loadObjectList();
        return $categories;
    }

    static function getPins($catid) {
        $db = Jfactory::getDBO();
        $pageno = 0;
        $length = 6;
        $query = "SELECT distinct(a.pin_id),a.pin_image,a.link_type,a.pin_url
                          FROM #__pin_pins a
                          INNER JOIN #__pin_user_settings d on a.pin_user_id=d.user_id
                          INNER JOIN #__pin_boards e on e.board_id=a.pin_board_id
                          INNER JOIN #__pin_categories b on e.board_category_id=b.category_id
                          WHERE b.category_id = '$catid' AND a.link_type!='youtube' AND a.link_type!='vimeo'
                          AND a.pin_repin_id='0' AND a.status='1' AND e.status=1
                          ORDER BY  a.created_date desc
                          LIMIT $pageno,$length";
        $db->setQuery($query);
        $pins = $db->loadObjectList();
        return $pins;
    }

}

?>