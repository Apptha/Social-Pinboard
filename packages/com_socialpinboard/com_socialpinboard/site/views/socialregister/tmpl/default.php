<?php
/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component socialregister view
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');

//add css js
$document = JFactory::getDocument();
$cmd = JRequest::getCmd('task', null);
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_socialpinboard/css/style.css');
$getSocialUSerDetails = $this->getSocialUSerDetails;

$userName = $getSocialUSerDetails->name;
$Email = $getSocialUSerDetails->email;
if ($getSocialUSerDetails) {
?>
    <div id="new_account_create">
        <h1>Create Social Pin Board Account</h1>
        <form method="post"
              action="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=userlogin', false); ?>" enctype="multipart/form-data">
            <div class="clearfix">
                <fieldset class="input">
                    <div class="detail_page">
                        <ul>
                            <li>
                                <label class="lable-txt" for="Username"><?php echo JText::_('COM_SOCIALPINBOARD_USERNAME') ?>
                                </label>

                                <input name="twittername" readonly="readonly" id="twittername" type="text"  class="login-inputbox" value="<?php echo $userName; ?>" alt="Password" size="18" />

                            </li>
                            <li>
                                <label class="lable-txt" for="Email"><?php echo JText::_('COM_SOCIALPINBOARD_EMAIL') ?>
                                </label>

                                <input name="twitterEmail" readonly="readonly"  id="twitterEmail" type="text"  value="<?php echo $Email; ?>"class="login-inputbox" alt="twitterEmail" size="18" />

                            </li>
                            <div id="passwordfield">
                                <li>


                                    <label class="lable-txt" for="Password"><?php echo JText::_('COM_SOCIALPINBOARD_PASSWORD') ?>
                                    </label>

                                    <input name="twitterPassword" id="twitterPassword" type="password"  class="login-inputbox" alt="Password" size="18" />
                                </li>
                            </div>
                            <div id="passwordfield">
                                <li>


                                    <label class="lable-txt" for="ConfirmPassword"><?php echo JText::_('COM_SOCIALPINBOARD_CONFIRM_PASSWORD') ?>
                                    </label>
                                    <input name="twitterConfPassword" id="twitterConfPassword" type="password"  class="login-inputbox" alt="Password" size="18" />
                                </li>
                            </div>
                        </ul>
                        <div class="clear"></div>

                        <input type="submit" name="Register" value="Register" id="new_acc_submit" onclick="return Validate()" />
                        <input type="hidden" name="facebook" value="facebook" id="facebook"  />
                    </div>



                </fieldset>


            </div>
        <?php echo JHtml::_('form.token'); ?>
    </form>
</div>
<script type="text/javascript">

    function Validate()
    {
        var passwordvalue=document.getElementById('twitterPassword').value;
        var confirmpassword=document.getElementById('twitterConfPassword').value;
        total=passwordvalue.length;
        if(passwordvalue=='')
        {
            alert('Enter A Password');
            return false;
                  
        }
        else if(total < 6)
        {
            alert('Password Cannot be less than 6');
            return false;
        }
        else if(passwordvalue!=confirmpassword)
        {
            alert('Password Doesnot match');
            return false;
        }
    }

</script>
<?php
    }
?>