<?php

/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component pinitpage model
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');

class socialpinboardModelpinitpage extends SocialpinboardModel {

    function getBoardsname() {
        $db = JFactory::getDBO();
        $pinid = JRequest::getVar('pin_id');
        $select = "select pin_board_id from #__pin_pins where pin_id=" . $pinid;
        $db->setQuery($select);
        $boardsName = $db->loadObjectList();
        $selectquery = "select board_name from #__pin_boards where board_id=" . $boardsName[0]->pin_board_id;
        $db->setQuery($selectquery);
        $boardName = $db->loadObjectList();
        return $boardName;
    }

}

?>
