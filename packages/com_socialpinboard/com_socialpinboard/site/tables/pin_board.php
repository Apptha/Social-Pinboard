<?php

/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component pin board table
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');

class Tablepin_board extends JTable {

    //To create the tables
    public $board_id = null;
    public $user_id = null;
    public $board_name = null;
    public $board_description = null;
    public $board_category_id = null;
    public $board_access = null;
    public $status = null;
    public $created_date = null;
    public $updated_date = null;

    function Tablepin_board(&$db) {
        parent::__construct('#__pin_boards', 'board_id', $db);
    }

}

?>