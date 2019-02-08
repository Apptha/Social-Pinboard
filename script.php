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
jimport('joomla.utilities.error');
	jimport('joomla.filesystem.file');

/**
 * API entry point. Called from main installer.
 */
class pkg_SocialPinboardPackageInstallerScript
{
    

     function preflight($type, $parent){

}
    function install($parent)
		{
        
  }


/**
 * API entry point. Called from main un installer.
 */
function postflight( $type, $parent ) {

     echo "<br />";
	//show thanks message
  echo '<p style="font-style:normal;font-size:13px;font-weight:normal; margin-top:10px;margin-left:10px;"><a href="http://www.apptha.com" target="_blank"><img src="components/com_socialpinboard/assets/apptha.gif" alt="Joomla! Apptha Social PinBoard Component Installed Successfully" align="left" />&nbsp;&nbsp;Apptha</a> Social Pin Board</p>
<p> Template Social PinBoard Installed Successfully <img src="components/com_socialpinboard/assets/ok.png" alt="Joomla! Apptha Social PinBoard Template" align="left" /> </p>
<p> Module Social PinBoard Login Installed Successfully <img src="components/com_socialpinboard/assets/ok.png" alt="Joomla! Apptha Social PinBoard" align="left" /> </p>
<p> Module Social PinBoard Header Installed Successfully <img src="components/com_socialpinboard/assets/ok.png" alt="Joomla! Apptha Social PinBoard" align="left" /></p>
<p> Module Social PinBoard Activities Installed Successfully <img src="components/com_socialpinboard/assets/ok.png" alt="Joomla! Apptha Social PinBoard" align="left" /></p>
<p> Module Social PinBoard Menu Installed Successfully <img src="components/com_socialpinboard/assets/ok.png" alt="Joomla! Apptha Social PinBoard" align="left" /></p>
<p> Module Social PinBoard Search Installed Successfully <img src="components/com_socialpinboard/assets/ok.png" alt="Joomla! Apptha Social PinBoard" align="left" /></p>
<p style=" width: 410px; "> Plugin Authentication - SocialPinBoardLogin Installed Successfully <img src="components/com_socialpinboard/assets/ok.png" alt="Joomla! Apptha Social PinBoard" align="left" /></p>
<p> Plugin Authentication - Apptharedirect Installed Successfully <img src="components/com_socialpinboard/assets/ok.png" alt="Joomla! Apptha Social PinBoard" align="left" /></p><br/>';
            

}
function uninstall() {
   
}
}