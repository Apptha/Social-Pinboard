<?php
/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component pinit view
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');
$base = JURI::base();

$document = JFactory::getDocument();
$document->addStyleSheet('components/com_socialpinboard/css/pinit.css');
$document->addStyleSheet('components/com_socialpinboard/css/reset.css');
$document->addStyleSheet('components/com_socialpinboard/css/style.css');
$document->addScript('components/com_socialpinboard/javascript/chrome.js');
$document->addStyleSheet('modules/mod_socialpinboard_menu/css/socialpinboard_menu.css');
$document->addStyleSheet('components/com_socialpinboard/css/pinboard.css');
$app = JFactory::getApplication();
$templateparams = $app->getTemplate(true)->params; // get the tempalte parameters
$logo = $templateparams->get('logo'); //get the logo
?>
<div class="pin-it-button">
    <h2><?php echo JTEXT::_('COM_SOCIALPINBOARD_PIN_IT_BOOKMARK') ?></h2>
    <div class="ButtonHolder ">
        <a href="javascript:void((function(){var e=document.createElement('script');e.setAttribute('type','text/javascript');e.setAttribute('charset','UTF-8'),e.setAttribute('src','<?php echo $base; ?>/components/com_socialpinboard/javascript/socialmarklet.js'),document.body.appendChild(e);})());">Pin It</a>
        <h3><?php echo JTEXT::_('COM_SOCIALPINBOARD_ADD_THIS_LINK_BOOKMARK') ?></h3>
    </div>
    <?php
    $u_agent = $_SERVER['HTTP_USER_AGENT'];
    if (preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent)) {
        $bname = 'Internet Explorer';
        $ub = "MSIE";
        $description = "Place the cursor on the pinit button, right click and click add to favorites toolbar.";
    } elseif (preg_match('/Firefox/i', $u_agent)) {
        $bname = 'Mozilla Firefox';
        $ub = "Firefox";
        $description = 'Drag the Pin It? button to your Bookmarks bar';
    } elseif (preg_match('/Chrome/i', $u_agent)) {
        $bname = 'Google Chrome';
        $ub = "Chrome";
        $description = 'Drag the Pin It? button to your Bookmarks bar';
    } elseif (preg_match('/Safari/i', $u_agent)) {
        $bname = 'Apple Safari';
        $ub = "Safari";
        $description = 'Drag the Pin It? button to your Bookmarks bar';
    } elseif (preg_match('/Opera/i', $u_agent)) {
        $bname = 'Opera';
        $ub = "Opera";
        $description = 'Drag the Pin It? button to your Bookmarks bar';
    } elseif (preg_match('/Netscape/i', $u_agent)) {
        $bname = 'Netscape';
        $ub = "Netscape";
        $description = 'Drag the Pin It? button to your Bookmarks bar';
    }
    ?>
    <h4><?php echo 'To install the Pin It? button in ' . $bname; ?></h4>
    <div><?php echo $description; ?></div>
    <div class="pin-it-hints">
        <ul>
            <li><?php echo JTEXT::_('COM_SOCIALPINBOARD_DISPLAY_BOOKMARK_BY_CLICKING_ME') ?> <strong><?php echo JTEXT::_('COM_SOCIALPINBOARD_WRENCH_ICON_TOOLS_ALWAYS_BOOKMARK') ?></strong></li>
            <li><?php echo JTEXT::_('COM_SOCIALPINBOARD_DRAG_THE_PIN_BUTTON_TO_YOUR_BOOKMARK') ?></li>
            <li><?php echo JTEXT::_('COM_SOCIALPINBOARD_BROWSING_THE_WEB_PUSH_IT') ?></li>
        </ul>
        <p><?php echo JTEXT::_('COM_SOCIALPINBOARD_PIN_IT_BOOKMARK_DESCRIPTION') ?>
        </p>
    </div>

</div>