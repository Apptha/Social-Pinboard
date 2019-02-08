<?php

/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Module menu
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');
require_once( dirname(__FILE__) . DS . 'helper.php' );
$db = JFactory::getDBO();
if (version_compare(JVERSION, '2.5.0', 'ge')) {
    $version = '2.5';
} else if (version_compare(JVERSION, '1.7.0', 'ge')) {
    $version = '1.7';
} elseif (version_compare(JVERSION, '1.6.0', 'ge')) {
    $version = '1.6';
} else {
    $version = '1.5';
}
if ($version == '1.5') {
    if (!class_exists('JHtmlString')) {
        JLoader::register('JHtmlString', JPATH_SITE . '/components/com_socialpinboard/string.php');
    }
}
$class = $params->get('moduleclass_sfx');
$show_request = modsocialpinboard_menu::showRequest();
$result = modsocialpinboard_menu::getCategories();
$resultboards = modsocialpinboard_menu::getBoards();
$saveboards = modsocialpinboard_menu::saveBoard();
if (JRequest::getVar('uploadPin')) {
    modsocialpinboard_menu::uploadPin();
}
if (JRequest::getVar('pin_board')) {
    $savePins = modsocialpinboard_menu::addPins();
}
$userLogin = modsocialpinboard_menu::userLogin();
$currency = modsocialpinboard_menu::getcurrency();
$top_menu = modsocialpinboard_menu::topMenuAssign();
$repin = modsocialpinboard_menu::repin();
$app = Jfactory::getApplication();
$templateparams = $app->getTemplate(true)->params; // get the tempalte
$facebook_url = $templateparams->get('facebook_url');
$twitter_url = $templateparams->get('twitter_url');
require(JModuleHelper::getLayoutPath('mod_socialpinboard_menu'));
?>
