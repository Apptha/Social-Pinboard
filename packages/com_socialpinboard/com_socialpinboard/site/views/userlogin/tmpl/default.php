<?php
/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component userlogin view
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');

$document = JFactory::getDocument();
$document->addStyleSheet('components/com_socialpinboard/css/style.css');
$activation_code = $this->activation_code;
if (JRequest::getVar('reset') == $activation_code) {
    $reset_password = JRequest::getVar('reset');
    $email = JRequest::getVar('email');
?>

    <form id="change_password" action="<?php echo JRoute::_("index.php?option=com_socialpinboard&view=userlogin", false); ?>">
        <h2 class="change_password_title">Recover Password</h2>
        <input type="hidden" id="option" name="option" value="com_socialpinboard">
        <input type="hidden" id="view" name="view" value="userlogin">
        <div class="change_password_field">
            <label class="change_password_label" for="Password">
<?php echo JText::_('COM_SOCIALPINBOARD_PASSWORD') ?>
            </label>
            <input name="password" id="password" type="password"  class="change_password_input" alt="Password" size="18" />
        </div>
        <div class="clear"></div>
        <div class="change_password_field">
            <label class="change_password_label" for="ConfirmPassword">
<?php echo JText::_('COM_SOCIALPINBOARD_CONFIRM_PASSWORD') ?>
        </label>
        <input name="ConfPassword" id="ConfPassword" type="password"  class="change_password_input" alt="Password" size="18" />
    </div>
    <div id="password_not_match"></div>
    <div class="clear"></div>
    <div class="change_password_field">
        <input type="hidden" value="<?php echo $email ?>" name="email" id="email" />
        <input type="hidden" value="<?php echo $reset_password ?>" name="reset" id="reset" />
        <input type="submit" value="submit" id="submit" name="submit" onclick="return validatePassword();" class="change_password_submit" />
    </div>
</form>

<?php
} else {
    JError::raiseError(404, implode('<br />', $errors));

    return false;
}
?>
<script>
    function validatePassword()
    {
        
        var reset_password= document.getElementById('password').value;
        var ConfPassword= document.getElementById('ConfPassword').value;
        if(reset_password=='')
        {
            document.getElementById('password_not_match').innerHTML='Please enter password' ;
            return false;
        }
        else if(ConfPassword=='')
        {
            document.getElementById('password_not_match').innerHTML='Please enter confirm password' ;
            return false;
        }else if(reset_password.length<6)
        {
            document.getElementById('password_not_match').innerHTML='Password cannot be less than 6 characters' ;
            return false;
        }else  if(ConfPassword!=reset_password)
        {
            document.getElementById('password_not_match').innerHTML="Password doesn't Match";
            return false;
        }else
        {
            return true;
        }
    }
</script>