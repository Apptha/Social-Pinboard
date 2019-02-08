<?php
/**
 * @name        Social Pin Board
 * @version	1.0: com_subinstall.php$
 * @since       Joomla 1.5&1.6&1.7
 * @package	apptha
 * @subpackage	com_socialpinboard
 * @author      Contus Support
 * @copyright   Copyright (C) 2011 powered by Apptha
 * @license     GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Include the actual subinstaller class
jimport('joomla.filesystem.folder');
jimport('joomla.installer.installer');
jimport( 'joomla.environment.uri' );

/**
 * API entry point. Called from main installer.
 */
class mod_socialpinboard_activitiesInstallerScript
{
    

     function preflight($type, $parent){

}
    function install($parent)
		{
       
            $db = JFactory::getDBO();
     $query = "UPDATE #__modules SET published='1', position='socialpinboard_activities' WHERE module='mod_socialpinboard_activities' ";
        $db->setQuery($query);
        $db->query();
$query = "SELECT id FROM #__modules WHERE module = 'mod_socialpinboard_activities' ";
        $db->setQuery($query);
        $db->query();
        $mid4 = $db->loadResult();
        $query = "INSERT INTO #__modules_menu (moduleid) VALUES ('$mid4')";
        $db->setQuery($query);
        $db->query();
}

/**
 * API entry point. Called from main un installer.
 */
function postflight( $type, $parent ) {

                 

}
function uninstall() {
   
}
}