<?php
/**
 * @name          : Joomla Social Pinboard
 * @version	  : 1.5.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component People view
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');
$document = JFactory::getDocument();
$document->addStyleSheet('templates/socialpinboard/html/com_socialpinboard/css/login.css');
$document->addStyleSheet('components/com_socialpinboard/css/reset.css');
$document->addStyleSheet('templates/socialpinboard/html/com_socialpinboard/css/pinboard.css');
//$document->addStyleSheet('components/com_socialpinboard/css/pinboard.css');
$document->addScript( 'components/com_socialpinboard/javascript/chrome.js' );
$document->addScript( 'components/com_socialpinboard/javascript/loginvalidation.js' );
$app= JFactory::getApplication();
$templateparams	= $app->getTemplate(true)->params; // get the tempalte parameters
$logo= $templateparams->get('logo');//get the logo
$config=JFactory::getConfig();
$templateparams	= $app->getTemplate(true)->params; // get the tempalte
$sitetitle= $templateparams->get('sitetitle');
if(isset ($sitetitle))
{
 $document->setDescription($sitetitle);
 $document->setTitle($sitetitle);
}
else{
$sitetitle = $config->get('sitename');
 $document->setDescription($sitetitle);
 $document->setTitle($sitetitle);
}
?>
<script type="text/javascript">
function onClickEvent()
{   
document.getElementById("passwordfield").style.display='none';
document.getElementById("login-button").style.display='none';
document.getElementById("forget-list").style.display='none';
document.getElementById("resetButton").style.display='block';
document.getElementById("Back-login").style.display='block';
document.getElementById("login_error").style.display='none';
document.getElementById("invalid_email_address").style.display='none';

}
function onShowEvent()
{   
document.getElementById("passwordfield").style.display='block';
document.getElementById("login-button").style.display='block';
document.getElementById("forget-list").style.display='block';
document.getElementById("resetButton").style.display='none';
document.getElementById("Back-login").style.display='none';
document.getElementById("login_error").style.display='none';
document.getElementById("invalid_email_address").style.display='none';

}
jQuery(document).ready(function(){
var twitter_login_button_width = jQuery("#twitter_login_button").outerWidth();
var facebook_login_button_width = jQuery("#facebook_login_button").outerWidth();
if(twitter_login_button_width>facebook_login_button_width){
   jQuery("#facebook_login_button").css('width', twitter_login_button_width+'px');
}else{
   jQuery("#twitter_login_button").css('width', facebook_login_button_width+'px');
}
});
window.onload=function(){
    var user_name = document.getElementById("username").value;
    if(user_name != "Email Address" )
        {
         // document.getElementById("username").style.color="#000";
        }
    };
</script>
<?php

$returnResult = JRequest::getVar('returnURL');
$mediurl = JRequest::getVar('media');
// get the task
$sign_up=  JRequest::getVar('returnURL','','GET','WORD');


$registeration=$this->registeration;
$showRequest=$this->showRequest;

if($returnResult!='')
{
    $appendURL = '&returnURL='.$returnResult;
}
else
{
    $appendURL = '';
}

//add css js
$document = JFactory::getDocument();
$returnUrl = base64_encode(JRoute::_("index.php?option=com_socialpinboard&view=people".$appendURL, false));
  if(version_compare(JVERSION,'2.5.0','ge')) {
                $version ='2.5';
                 $loginUrl = JRoute::_('index.php?option=com_users', false);
            }
            else if (version_compare(JVERSION, '1.7.0', 'ge')) {
    $version = '1.7';
    $loginUrl = JRoute::_('index.php?option=com_users', false);
} elseif (version_compare(JVERSION, '1.6.0', 'ge')) {
    $version = '1.6';
    $loginUrl = JRoute::_('index.php?option=com_users', false);
} else{
    $version = '1.5';
    $loginUrl = JRoute::_('index.php?option=com_user', false);
}
     if (JRequest::getVar('email') && JRequest::getVar('activaton')) {
     echo '<label id="login_error_msg" style="display: block;">'.JText::_('COM_SOCIALPINBOARD_EMAIL_HAS_BEEN_ACTIVATED_LOGIN').'</label>';
     }

?>


<div class="landing-content">

<!-- logo -->

         <div class="landing-content">

        <!-- logo -->
        <div class="logo-header">
            <a href="index.php">
                <?php 
                if($logo!=null)
                {
                    ?>
                
                 <img src="<?php echo $this->baseurl ?>/<?php echo htmlspecialchars($logo); ?>" alt="<?php echo htmlspecialchars($templateparams->get('sitetitle'));?>" />
<?php 
                }  else {
                {
                    ?>
                 <img src="<?php echo JURI::base(); ?>/templates/socialpinboard/images/logo-large.png" alt="Socialpinboard" />
                 <?php 
                }
                }
                ?>
            </a>
        </div>

<!-- social media -->
<div class="social-media social_sign_up ">
    <?php
    
    if($showRequest=='0' && $sign_up=='signup' )
    {
        
        ?>
        <div class="login_page_fb">
        <?php
           $modules = JModuleHelper::getModules('socialpinboard_login'); 
			foreach ($modules as $module) 
			{ 
			  echo JModuleHelper::renderModule($module); 
			} 
			
            
        ?>
    </div>
    <div id="twitter_login_button" class="inset twitter_login" style="margin-left:10px;">
        <a class="tw login_button" style="margin:0 0;" href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=login_twitter', false); ?>">
            <div class="logo_wrapper"><span class="logo"></span></div>
            <span><?php echo JText::_('COM_SOCIALPINBOARD_SIGNUP_WITH_TWITTER'); ?></span>
        </a>
    </div>
</div>


<div class="login_divider"></div>
<?php
    } else
    {
        
    ?>
    <div class="login_page_fb">
        <?php
           $modules = JModuleHelper::getModules('socialpinboard_login'); 
			foreach ($modules as $module) 
			{ 
			  echo JModuleHelper::renderModule($module); 
			} 
			
        ?>
    </div>
    <div id="twitter_login_button" class="inset twitter_login">
        <a class="tw login_button" href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=login_twitter', false); ?>">
            <div class="logo_wrapper"><span class="logo"></span></div>
            <span><?php echo JText::_('COM_SOCIALPINBOARD_LOGIN_WITH_TWITTER'); ?></span>
        </a>
    </div>
</div>
<div class="login_divider"></div>
 <?php
    }
    ?>
<!-- login -->

<div class="login-entry">

<!-- Login form -->

    <form method="post" name="loginForm" id="loginForm" action="<?php echo $loginUrl; ?>">
                         
        <fieldset>
                     

                  <!-- Login User Name -->
                  
                             
                                 <span id="com-form-login-username" class="logintxt">
                                 </span>
                                 <div id="username-field">
                                     <?php $email_address=JText::_('COM_SOCIALPINBOARD_EMAIL_ADDRESS_LOGIN'); ?>
                                 <input name="username" id="username" type="text" value="<?php echo JText::_('COM_SOCIALPINBOARD_EMAIL_ADDRESS_LOGIN'); ?>" class="login-inputbox"  size="18"  onFocus="onFocusEvent(this,'<?php echo $email_address; ?>');" onBlur="onBlurEvent(this,'<?php echo $email_address; ?>');" />
                                  </div>
                                 <div id="invalid_email_address" style="display:none" > <?php echo JText::_('COM_SOCIALPINBOARD_INVALID_EMAIL_ADDRESS'); ?></div>
                                 <div class="clear"></div>
                             

                  <!-- Login Password -->
                           
                                 <span id="com-form-login-password" class="logintxt">
                              
                                 </span>
                                     <div id="passwordfield">
                                        <input type="text" name="password" id="password" value="<?php echo JText::_('COM_SOCIALPINBOARD_PASSWORD'); ?>" onfocus="return changepass();" />
                                     </div>
                                  <div class="clear"></div>
                              

                 <!-- Login Submit -->
                             <div id="login_error"></div>
                              
                              
                             
                                    <div id="login-button">
                                 <input type="submit" name="Submit"  onclick="return fieldValidation()"  value="<?php echo JText::_('COM_SOCIALPINBOARD_LOGIN'); ?>" />
                                 <a class="registers1" href="javascript:void(0);" onclick=" onClickEvent();"> <?php echo JText::_('COM_SOCIALPINBOARD_FORGOT_PASSWORD'); ?> </a>
                                 </div>
                                 <div id="resetButton" style="display:none; margin-top: 0;">
                                     <input type="button" name="Submit"  onclick="return sendPassword('<?php echo JURI::base();?>');" value="<?php echo JText::_('COM_SOCIALPINBOARD_RESET'); ?>" style="width: 100%; font-weight: bold;color: #524D4D; margin-left: 0;" />
                                 </div>
                                  
                                     <div id="Back-login" style="display: none; margin: 10px 0 0 0;">
                                 
                                            <a style="color:#8C7E7E" href="javascript:void(0);" onclick="onShowEvent();"> <?php echo JText::_('COM_SOCIALPINBOARD_BACK_TO_LOGIN'); ?> </a>
                                            

                                         
                              
                                 </div>
                                
                             
                                
                                  
                                 <div class="register_link" id="forget-list">
                                 <?php  if($registeration==1)
                                {
?>          
<div class="clear"></div>
                                            <a class="register_btn_1"   href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=register'); ?>" > <?php echo JText::_('COM_SOCIALPINBOARD_REGISTER'); ?> </a>
                                    
                                 <?php  } ?>
                                 
                                            
                                 </div>                
           
                              
                              
                 <!-- Forget Password -->
                            
                                 <span class="logintxt"><?php if (JPluginHelper::isEnabled('system', 'remember')) : ?></span>
                
                                 <?php endif; ?>
                             
            
                     
    </fieldset>

    <?php echo JHtml::_('form.token');
    if (version_compare(JVERSION, '1.7.0', 'ge')) { ?>
        <input type="hidden" name="task" value="user.login"/>
    <?php } elseif (version_compare(JVERSION, '1.6.0', 'ge')) { ?>
        <input type="hidden" name="task" value="user.login"/>
<?php } else { ?>
        <input type="hidden" name="task" value="login"/>
<?php } ?>
        <input type="hidden" name="return" value="<?php echo $returnUrl; ?>" />
        <?php echo JHtml::_('form.token'); ?>
        
        
        <div id="output"></div>
    
    <script type="text/javascript">
        function sendPassword(baseUrl)
        {
            var email=document.getElementById("username").value;
            if(email=='Email Address')
                {
                    document.getElementById('invalid_email_address').style.display="block";
                    return false;
                }
            document.loginForm.action = "<?php echo JRoute::_('index.php?option=com_socialpinboard&view=resetpassword'); ?>";
            document.loginForm.submit();
        }
       function  fieldValidation()
       {
              var email=document.getElementById("username").value;
              var lpassword=document.getElementById("password").value;


 var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
         
            if(email=='Email Address') 
               {
                   document.getElementById('login_error').innerHTML="Enter the email address";
                   document.getElementById('login_error').style.display="block";
                   return false;
               }else  if((reg.test(email) == false))
              {
                   document.getElementById('login_error').innerHTML="Invalid email address";
                   document.getElementById('login_error').style.display="block";
                   return false;
              }
              else if( lpassword=='Password' || lpassword==''  )            {
                        document.getElementById('login_error').innerHTML="Enter the password";
                        document.getElementById('login_error').style.display="block";
                   return false;
              }
//                   }else if ((lpassword).length<6)
//                   {
//                       document.getElementById('login_error').innerHTML="Invalid Password";
//
//                   return false;
//                   }
             else
                   {
                       document.getElementById('login_error').style.display="none";
                       return true;
                   }
        
       }
    </script>
        
        
</form>
</div>
</div>


