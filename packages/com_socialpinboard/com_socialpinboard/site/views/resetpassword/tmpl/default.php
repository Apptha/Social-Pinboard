<?php
/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component resetpassword view
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');

$document = JFactory::getDocument();
$document->addStyleSheet('components/com_socialpinboard/css/style.css');
$pattern = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i";
?>

<div class="reset_pass">
    <h1>Reset Password</h1>


    <form method="POST" action="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=resetpassword&task=model'); ?>" />
    <div id="reset-pass">
        <?php
        if (JRequest::getVar('task') == 'model') {
            //Get the Email for sending the password
            $email = JRequest::getvar('resetPasswd');
            $model = $this->getModel('resetPassword');
            $resetPassword = $model->resetPassword($email);
            $getUser = $model->getUser($email);
            //check user is blocked and mail sent
            if ($resetPassword == '1' && $getUser->block == '0') {
        ?>
                <div class="succes_reset"><h2><?php echo JText::_('COM_SOCIALPINBOARD_SUCCESS'); ?></h2> <br/>  Check your email to reset your password.</div>
        <?php
            } else {
                if (preg_match($pattern, $email)) {
                    echo '<p style="color:#FF0000;font-weight:bold;text-shadow:0px 1px 0px #fff;font-size:13px;">' . JText::_('COM_SOCIALPINBOARD_EMAIL_NOT_REGISTERED') . '</p>';
                } else {
                    echo '<p style="color:#FF0000;font-weight:bold;text-shadow:0px 1px 0px #fff;font-size:13px;">' . JText::_('COM_SOCIALPINBOARD_VALID_EMAIL_ADDRESS') . '</p>';
                }
        ?>
                <input type="text" name="resetPasswd" value="<?php echo $email; ?>" id="resetPasswd" />

                <input type="Submit" value="<?php echo JText::_('COM_SOCIALPINBOARD_SUBMIT'); ?>" name="Submit" id="rest_pass_btn" />
        
        <?php
            }
        } else {
            $email = JRequest::getvar('username');
            $userExits = $this->userExist;
            if ($userExits) {
                $userBlock = $userExits->block;
            } else {
                $userBlock = "";
            }
            $resetPassword = $this->resetPassword;
            if ($resetPassword == '1' && $userBlock == '0') {
        ?>
                <div class="succes_reset"><h2><?php echo JText::_('COM_SOCIALPINBOARD_SUCCESS'); ?></h2><?php echo JText::_('COM_SOCIALPINBOARD_CHECK_EMAIL'); ?></div>
        <?php
            } else {
                if (preg_match($pattern, $email) || $userBlock == '1') {
 ?>
                    <p class="" style="float:right;color:#ff0000; margin: 8px 30px 0 0;text-shadow: 0 1px rgba(255, 255, 255, 0.9);"><?php echo JText::_('COM_SOCIALPINBOARD_THIS_EMAIL_DOESNOT_HAVE_USER_ACCOUNT'); ?></p>
        <?php
                } else {
                    echo '<p style="color:#FF0000; float:left;">' . JText::_('COM_SOCIALPINBOARD_VALID_EMAIL_ADDRESS') . '</p>';
                }
        ?>
                <input type="text" name="resetPasswd" value="<?php echo $email; ?>" id="resetPasswd" />

                <input type="Submit" value="<?php echo JText::_('COM_SOCIALPINBOARD_SUBMIT'); ?>" name="Submit" id="rest_pass_btn" />
        <?php
            }
        }
        ?>
    </div>
</form>
</div>