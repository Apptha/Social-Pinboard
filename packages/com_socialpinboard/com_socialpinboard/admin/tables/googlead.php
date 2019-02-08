<?php
/**
 * @name        Social Pin Board
 * @version	1.0: pincategory.php$
 * @since       Joomla 1.5&1.6&1.7
 * @package	apptha
 * @subpackage	com_socialpinboard
 * @author      Contus Support
 * @copyright   Copyright (C) 2011 powered by Apptha
 * @license     GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die("Restricted Access");

class Tablegooglead extends JTable
{
    //To create the tables
    public $ad_id=null;
    public $adclient=null;
    public $adslot=null;
    public $adwidth=null;
    public $adheight=null;
    public $pin_ad_position=null;
    public $status=null;
    public $created_date=null;
    public $updated_date=null;

    function Tablegooglead(&$db)
    {
       parent::__construct('#__pin_googlead','ad_id',$db);
        
    }
    
}
?>