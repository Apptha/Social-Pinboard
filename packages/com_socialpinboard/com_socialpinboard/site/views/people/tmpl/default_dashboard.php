<?php
/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component people view
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');

//add css js
$document = JFactory::getDocument();
$cmd = JRequest::getCmd('task', null);

$getSocialUSerDetails = $this->getSocialUSerDetails;
$userName = $getSocialUSerDetails->username;
$Email = $getSocialUSerDetails->email;
?>

<div class="profile clearfix">
    <fieldset class="input">
        <table class="detailpage">
            <tr class="usertr">
                <td class="logintxt">
                    <label class="lable-txt" for="Username"><?php echo JText::_('Username') ?>
                    </label><br />
                </td>
                <td class="logintxt">
                    <label class="lable-txt" id="Username"><?php echo $userName ?>
                    </label>
                </td>
            </tr>
            <tr class="usertr">
                <td class="logintxt">
                    <label class="lable-txt" for="Email"><?php echo JText::_('Email') ?>
                    </label><br />
                </td>
                <td class="logintxt">
                    <label class="lable-txt" id="Username"><?php echo $Email ?>
                    </label>
                </td>
            </tr>
            <tr class="usertr">
                <td class="logintxt">
                    <label class="lable-txt" for="Password"><?php echo JText::_('Password') ?>
                    </label><br />
                </td>
                <td><input name="Password" id="Password" type="password"
                           class="login-inputbox" alt="Password" size="18" />
                </td>

            </tr>
            <tr>
                <td>
                    <input type="submit" name="Register" value="Register" />
                    <input type="hidden" name="task" value="RegisterPassword"/>
                </td>
            </tr>

        </table>
    </fieldset>
</div>
<?php echo JHtml::_('form.token'); ?>
