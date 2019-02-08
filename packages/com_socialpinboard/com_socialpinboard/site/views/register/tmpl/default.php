<?php
/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component register view
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');
$app = JFactory::getApplication();
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_socialpinboard/css/edit.css');
$document->addStyleSheet('components/com_socialpinboard/css/reset.css');
$document->addStyleSheet('components/com_socialpinboard/css/pinboard.css');
$document->addScript('components/com_socialpinboard/javascript/registration.js');


$config = JFactory::getConfig();
$templateparams = $app->getTemplate(true)->params; // get the tempalte
$sitetitle = $templateparams->get('sitetitle');
if (isset($sitetitle)) {
    $document->setDescription($sitetitle);
    $document->setTitle($sitetitle);
} else {
    $sitetitle = $config->get('sitename');
    $document->setDescription($sitetitle);
    $document->setTitle($sitetitle);
}
?>
<script type="text/javascript">
    var enter_email="<?php echo JText::_('COM_SOCIALPINBOARD_PLEASE_ENTER_THE_EMAIL'); ?>";
    var blocked_email="<?php echo JText::_('COM_SOCIALPINBOARD_BLOCKED_EMAIL_ID'); ?>";
    var enter_username="<?php echo JText::_('COM_SOCIALPINBOARD_PLEASE_ENTER_THE_USERNAME'); ?>";
    var enter_valid_email="<?php echo JText::_('COM_SOCIALPINBOARD_PLEASE_ENTER_THE_VALID_EMAIL'); ?>";
    var min_username_charecter="<?php echo JText::_('COM_SOCIALPINBOARD_USER_SHOULD_HAVE_MINIMUM_SIX_CHARACTERS'); ?>";
    var username_wo_special_charecter="<?php echo JText::_('COM_SOCIALPINBOARD_PLEASE_ENTER_THE_USERNAME_WITHOUT_SPECIAL_CHARACTERS'); ?>";
    var username_wo_space="<?php echo JText::_('COM_SOCIALPINBOARD_PLEASE_ENTER_THE_USERNAME_WITHOUT_SPACE'); ?>";
    var firstname_wo_space="<?php echo JText::_('COM_SOCIALPINBOARD_FIRST_NAME_CANNOT_BE_SPACE'); ?>";
    var lastname_wo_space="<?php echo JText::_('COM_SOCIALPINBOARD_LAST_NAME_CANNOT_BE_SPACE'); ?>";
</script>

<title><?php echo JText::_('COM_SOCIALPINBOARD_SOCIAL_PIN_BOARD'); ?></title>

<div id="wrapper">
    <script type="text/javascript" src="<?php echo $this->baseurl ?>/components/com_socialpinboard/javascript/jquery.min.js"></script>
    <style> #error-to1 , #error-to-email {color: #008000; font-size: 12px;  font-family: "helvetica neue",arial,sans-serif;word-wrap: break-word;}
        #error-to-false ,#error-to-email-false  {color: #FF1900; font-size: 12px;  font-family: "helvetica neue",arial,sans-serif;word-wrap: break-word;}
    </style>

</div>


<form action="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=register'); ?>" method="post" enctype="multipart/form-data" name="uploadphotos">
    <div class="edit_page register_page clearfix">
        <div class="social_sign_up clearfix">
            <div class="fb_sign_up">
<?php
$modules = JModuleHelper::getModules('socialpinboard_login');
foreach ($modules as $module) {
    echo JModuleHelper::renderModule($module);
}
?>
            </div>
            <div class="inset twitter_login">
                <a class="tw login_button" style="margin:0 0;" href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=login_twitter', false); ?>">
                    <div class="logo_wrapper"><span class="logo"></span></div>
                    <span><?php echo JText::_('COM_SOCIALPINBOARD_SIGNUP_WITH_TWITTER'); ?></span>
                </a>
            </div>
        </div>
        
        <div class="login_divider"></div>
        <h2 class="edit_title"><?php echo JText::_('COM_SOCIALPINBOARD_SIGN_UP'); ?></h2>
        <ul>
            <!-- Email -->
            <div class="signup_left">
                <li class="topborder_none">
                    <label><?php echo JText::_('COM_SOCIALPINBOARD_EMAIL'); ?> <em style="color:#FF1900">*</em></label>
                    <div class="edit_input">
                        <input type="text" tabindex="1" name="email" autocomplete="off" value="" id="field1" onblur="return checkEmail();" onkeyup="return checkEmail();"  />
                        <div style="display: none" id="email_help1"> <span style="color:#FF1900;"   class="help_text"><?php echo JText::_('COM_SOCIALPINBOARD_FIELD_REQUIRED'); ?></span></div>
                        <div id="error-to-email"></div>
                        <div id="error-email-false" class="help_text"></div>
                    </div>
                </li>
                <li>
                    <label><?php echo JText::_('COM_SOCIALPINBOARD_FIRST_NAME'); ?> <em style="color:#FF1900">*</em></label>
                    <div class="edit_input">
                        <input type="text" tabindex="3"  name="first_name" value="" onblur="return valFirstName();" onkeyup="return valFirstName();" id="field2"/>
                       <div id="first_error-to-false" class="help_text"></div>
                        <div style="display: none" id="email_help2"> <span style="color:#FF1900;" class="help_text"><?php echo JText::_('COM_SOCIALPINBOARD_FIELD_REQUIRED'); ?></span></div>
                        <div style="display: none" id="first_name_error"><span style="color:#FF1900;"   class="help_text"><?php echo JText::_('COM_SOCIALPINBOARD_FIRST_NAME_CANNOT_BE_GREATER_THAN_THIRTY_CHARACTER'); ?></span></div>
                        <div style="display: none" id="first_name_space_error"><span style="color:#FF1900;"   class="help_text"><?php echo JText::_('COM_SOCIALPINBOARD_FIRST_NAME_CANNOT_BE_SPACE'); ?></span></div>
                    </div>
                </li>
                <li class="edit-add-pass">
                    <label><?php echo JText::_('COM_SOCIALPINBOARD_PASSWORD'); ?> <em style="color:#FF1900">*</em></label>
                    <div class="edit_input">
                        <input type="password" name="new_password" tabindex="5"  value="" id="field3" onblur="return valPasswordlength();" onkeyup="return valPasswordlength();" />
                        <div style="display: none" id="email_help3"> <span style="color:#FF1900;"   class="help_text"><?php echo JText::_('COM_SOCIALPINBOARD_FIELD_REQUIRED'); ?></span></div>
                        <div style="display: none" id="password_error"> <span style="color:#FF1900;"   class="help_text"><?php echo JText::_('COM_SOCIALPINBOARD_PASSWORD_CANNOT_BE_LESS_THAN_FIVE_CHARACTER'); ?></span></div>
                    </div>
                </li>
            </div>
            <!-- First Name -->
            <div class="signup_right">
                <li>
                    <label><?php echo JText::_('COM_SOCIALPINBOARD_USER_NAME'); ?> <em style="color:#FF1900">*</em></label>
                    <div class="edit_input">
                        <input type="text" tabindex="2" name="username" value="" id="field4" autocomplete="off" onblur="return checkusername();" onkeyup="return checkusername();"  />
                        <div id="error-to1"></div>
                        <div id="error-to-false" class="help_text"></div>
                        <div style="display: none" id="email_help4"><span style="color:#FF1900;" class="help_text"><?php echo JText::_('COM_SOCIALPINBOARD_FIELD_REQUIRED'); ?></span></div>
                    </div>
                </li>
                <!-- Last Name -->
                <li>
                    <label><?php echo JText::_('COM_SOCIALPINBOARD_LAST_NAME'); ?> <em style="color:#FF1900">*</em></label>
                    <div class="edit_input">
                        <input type="text" tabindex="4" name="last_name" value="" onblur="return valLastName();" onkeyup="return valLastName();" id="field5" />
                        <div id="last_error-to-false" class="help_text"></div>
                        <div style="display: none" id="email_help5"> <span style="color:#FF1900;"   class="help_text"><?php echo JText::_('COM_SOCIALPINBOARD_FIELD_REQUIRED'); ?></span></div>
                        <div style="display: none" id="last_name_error"> <span style="color:#FF1900;"   class="help_text"><?php echo JText::_('COM_SOCIALPINBOARD_LAST_NAME_CANNOT_BE_GREATER_THAN_THIRTY_CHARACTER'); ?></span></div>
                        <div style="display: none" id="last_name_space_error"><span style="color:#FF1900;"   class="help_text"><?php echo JText::_('COM_SOCIALPINBOARD_LAST_NAME_CANNOT_BE_SPACE'); ?></span></div>
                    </div>

                </li>
                <!-- Last Name -->
                <!-- Confirm Password -->
                <li class="edit-add-pass">
                    <label><?php echo JText::_('COM_SOCIALPINBOARD_CONFIRM_PASSWORD'); ?> <em style="color:#FF1900">*</em></label>
                    <div class="edit_input">
                        <input type="password" tabindex="6" name="confirm_password" value="" id="field6" onblur="return valConfirmPassword();" onkeyup="return valConfirmPassword();"/>
                        <div style="display: none; " id="email_help6"> <span style="color:#FF1900;"   class="help_text"><?php echo JText::_('COM_SOCIALPINBOARD_FIELD_REQUIRED'); ?></span></div>
                        <div style="display: none" id="confirm_password_error"> <span style="color:#FF1900;"   class="help_text"><?php echo JText::_('COM_SOCIALPINBOARD_CONFIRM_PASSWORD_CANNOT_BE_LESS_THAN_FIVE_CHARACTER'); ?></span></div>
                    </div>
                    <div style="display: none; margin-left: 180px;" id="not_match"> <span style="color:#FF1900;"   class="help_text"><?php echo JText::_('COM_SOCIALPINBOARD_PASSWORD_NOT_MATCH'); ?></span></div>
                </li>

            </div>
        </ul>
        <div class="sign-submit">
            <input type="submit" onclick="return checkField();" value="<?php echo JText::_('COM_SOCIALPINBOARD_SIGN_UP'); ?>" />
            <input type="hidden" value="registerComplete" id="registerComplete" name="registerComplete"  />
        </div>

    </div>
</form>
</div>



