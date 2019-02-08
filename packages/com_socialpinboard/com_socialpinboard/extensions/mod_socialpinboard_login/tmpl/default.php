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
//check signup or login
$sign_up = JRequest::getVar('view', 'register');
?>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
    <?php
    if (!$user && $userId->guest && ($fbResult->setting_show_request == 1 && $sign_up == 'register')) {
    ?>
        <div id="facebook_login_button" class="inset twitter_login" style="margin-left:10px;">
            <a class="fb login_button" style="margin:0 0;" href="<?php echo $loginUrl; ?>">
                <div class="logo_wrapper"><span class="logo"></span></div>
                <span><?php echo JText::_('MOD_SOCIALPINBOARD_SIGNUP_FACEBOOK'); ?></span>
            </a>
        </div>
    <?php
    } else if (!$user && $userId->guest && ($fbResult->setting_show_request == 1 || $sign_up != 'register' )) {
    ?>
        <div id="facebook_login_button" class="inset twitter_login" style="margin-left:10px;">
            <a class="fb login_button" style="margin:0 0;" href="<?php echo $loginUrl; ?>">
                <div class="logo_wrapper"><span class="logo"></span></div>
                <span><?php echo JText::_('MOD_SOCIALPINBOARD_LOGIN_FACEBOOK'); ?></span>
            </a>
        </div>
    <?php
    } else if (!$user && $userId->guest && $sign_up == 'register') {
    ?>
        <div id="facebook_login_button" class="inset twitter_login" style="margin-left:10px;">
            <a class="fb login_button" style="margin:0 0;" href="<?php echo $loginUrl; ?>">
                <div class="logo_wrapper"><span class="logo"></span></div>
                <span><?php echo JText::_('MOD_SOCIALPINBOARD_SIGNUP_FACEBOOK'); ?></span>
            </a>
        </div>
    <?php
    }
    ?>