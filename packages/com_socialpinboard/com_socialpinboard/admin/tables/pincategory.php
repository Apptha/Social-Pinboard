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

class Tablepincategory extends JTable
{
    //To create the tables
    public $category_id=null;
    public $category_name=null;
    public $status=null;
    public $created_date=null;
    public $updated_date=null;
    public $category_alias=null;
	public $category_image=null;

    function Tablepincategory(&$db)
    {
       parent::__construct('#__pin_categories','category_id',$db);
        
    }
    
}
?>