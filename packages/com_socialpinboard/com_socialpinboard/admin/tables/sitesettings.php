<?php
/**
 * @name        Social Pin Board
 * @version	1.0: sitesettings.php$
 * @since       Joomla 1.5&1.6&1.7
 * @package	apptha
 * @subpackage	com_socialpinboard
 * @author      Contus Support
 * @copyright   Copyright (C) 2011 powered by Apptha
 * @license     GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die("Restricted Access");

class Tablesitesettings extends JTable
{
    //To create the tables
    public $id=null;
    public $setting_facebookapi=null;
    public $setting_facebooksecret=null;
    public $setting_setting_gmailclientid=null;
    public $setting_gmailclientsecretkey=null;
    public $setting_twitterapi=null;
    public $setting_twittersecret=null;
    public $setting_yahooconsumerkey=null;
    public $setting_yahooconsumersecretkey=null;
    public $setting_yahoooauthdomain=null;
    public $setting_setting_yahoooappid=null;
    public $setting_request_approval=null;
    public $setting_currency=null;
    public $lkey=null;
    public $created_date=null;
    public $setting_user_registration=null;
    public $setting_show_request=null;
    function Tablesitesettings(&$db)
    {
        parent::__construct('#__pin_site_settings','id',$db);
    }
}
?>