<?php
/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component invitefriends view
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');

$app = JFactory::getApplication();
$user = JFactory::getUser();
?>

<div class="invite_frnd clearfix">
<?php
if (JRequest::getVar('task') == "facebook") {

    echo $this->loadTemplate('facebookinvite');
} else if (JRequest::getVar('task') == "gmail") {

    echo $this->loadTemplate('gmailinvite');
} else if (JRequest::getVar('task') == "yahoo") {

    echo $this->loadTemplate('yahooinvite');
} else {
    echo $this->loadTemplate('invitefriends');
}
?>
    <div class="clear"></div>
</div>

