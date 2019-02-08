<?php
/**
 * @name          : Joomla Social Pinboard
 * @version	  : 1.5.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component Profile view
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_socialpinboard/css/facebox.css');
//$document->addStyleSheet('components/com_socialpinboard/css/style.css');
//$document->addStyleSheet('components/com_socialpinboard/css/edit.css');
$document->addStyleSheet('templates/socialpinboard/html/com_socialpinboard/css/edit.css');
$document->addStyleSheet('components/com_socialpinboard/css/reset.css');
//$document->addStyleSheet('components/com_socialpinboard/css/pinboard.css');
$document->addStyleSheet('templates/socialpinboard/html/com_socialpinboard/css/style.css');
$document->addStyleSheet('templates/socialpinboard/html/com_socialpinboard/css/pinboard.css');
$document->addScript( 'https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js' );
$document->addScript( 'components/com_socialpinboard/javascript/jquery.ui.core.js' );
$document->addScript( 'components/com_socialpinboard/javascript/chrome.js' );
$document->addScript( 'components/com_socialpinboard/javascript/facebox.js' );
$document->addScript( 'components/com_socialpinboard/javascript/scroll/jquery.isotope.min.js' );
$document->addScript( 'components/com_socialpinboard/javascript/scroll/jquery.infinitescroll.min.js' );
$app = JFactory::getApplication();
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
$profileDetails = $this->user_profile;
?>
<style type="text/css">

     #profileimage {
        filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=scale);
    }
</style>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>

<div id="wrapper">
    <form action="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=profile'); ?>"  method="post" enctype="multipart/form-data" name="uploadphotos">
       <div class="edit_page">
           <div class="settings_title">
                <h2 class="edit_title"><?php echo JText::_('COM_SOCIALPINBOARD_EDIT_PROFILE'); ?></h2>
                <div class="delete_acc">
                    <a href="#" id="delete_user_account" class="Button WhiteButton Button18" name="delete_user_account">
                        <strong>
                            <?php echo JText::_('COM_SOCIALPINBOARD_DELETE_ACCOUNT'); ?>
                        </strong>
                        <span></span>
                    </a>
                    <div id="DeleteForm">
                        <p>
                        <span><?php echo JTEXT::_('COM_SOCIALPINBOARD_ACCOUNT_DELETE_SORRY_TO_COME'); ?></span>
                        <?php echo JTEXT::_('COM_SOCIALPINBOARD_ACCOUNT_DELETE_ALERTS'); ?>
                        </p>
                        <input type="submit" class="delete_account" name="delete_account" id="delete_account" value="<?php echo JText::_('COM_SOCIALPINBOARD_DELETE_ACCOUNT'); ?>"/>
                        <a href="#" id="ChangeOfHeart">
                            <?php echo JTEXT::_('COM_SOCIALPINBOARD_ACCOUNT_HAVE_CHANGED_MY_MIND'); ?>
                        </a>
                    </div>
                </div>
            </div>
           
            <ul>

                <!-- Email -->
                <li class="topborder_none">
                    <label>
                        <?php echo JText::_('COM_SOCIALPINBOARD_EMAIL'); ?>
                    </label>
                    <div class="edit_input">
                        <input type="text" disabled="disabled" name="email" value="<?php echo $profileDetails[0]->email; ?>" id="id_email" />
                        <span class="not_shown_pub"><?php echo JText::_('COM_SOCIALPINBOARD_NOT_SHOWN_PUBLICLY'); ?></span>
                    </div>
                </li>


                <!-- Password -->

                <li>
                    <label>
                        <?php echo JText::_('COM_SOCIALPINBOARD_PASSWORD'); ?>
                    </label>
                    <div class="edit_input">
                        <a class="Button WhiteButton " id="passwrod_show" onClick="changePassword();">
                            <strong>
                                <?php echo JText::_('COM_SOCIALPINBOARD_CHANGE_PASSWORD'); ?>
                            </strong>
                            <span></span>
                        </a>
                        <div id="change_password" style="display: none;">
                            <!-- Password -->
                            <ul>
                                <li class="edit-add-pass">
                                    <label>
                                        <?php echo JText::_('COM_SOCIALPINBOARD_CURRENT_PASSWORD'); ?>
                                    </label>
                                    <div class="edit_input">
                                        <input type="password" name="old_password"  value="" id="old_password" />
                                    </div>

                                </li>
                                <!-- First Name -->
                                <li class="edit-add-pass">
                                    <label>
                                        <?php echo JText::_('COM_SOCIALPINBOARD_NEW_PASSWORD'); ?></label>
                                    <div class="edit_input">
                                        <input type="password" name="new_password"  value="" id="new_password"/>

                                    </div>

                                </li>
                                <!-- Last Name -->
                                <li class="edit-add-pass">
                                    <label>
                                        <?php echo JText::_('COM_SOCIALPINBOARD_CONFIRM_PASSWORD'); ?>
                                    </label>
                                    <div class="edit_input">
                                        <input type="password"  name="confirm_password" value="" id="confirm_password"/>

                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </li>
                <!-- First Name -->
                <li>
                    <label>
                        <?php echo  JText::_('COM_SOCIALPINBOARD_FIRST_NAME'); ?>
                    </label>
                    <div class="edit_input">
                        <input type="text" name="first_name" value="<?php echo ucfirst($profileDetails[0]->first_name); ?>" id="first_name" />
                    </div>

                </li>
                <!-- Last Name -->
                <li>
                    <label><?php echo
                        JText::_('COM_SOCIALPINBOARD_LAST_NAME'); ?></label>
                    <div class="edit_input">
                        <input type="text" name="last_name" value="<?php echo ucfirst($profileDetails[0]->last_name); ?>" id="last_name"/>
                    </div>

                </li>
                <!-- Last Name -->
                <li>
                    <label><?php echo
                        JText::_('COM_SOCIALPINBOARD_USER_NAME'); ?></label>
                    <div class="edit_input">
                        <input type="text" disabled="disabled" name="username" value="<?php echo $profileDetails[0]->username; ?>" id="username"/>
                    </div>

                </li>
                <!-- About Name -->
                <li>
                    <label>
                        <?php echo JText::_('COM_SOCIALPINBOARD_ABOUT'); ?>
                    </label>
                    <div class="edit_input">
                        <textarea name="about" onKeyDown="CountLeft(this.form.about,this.form.left,200);"  onKeyUp="CountLeft(this.form.about,this.form.left,200);"   ><?php echo $profileDetails[0]->about; ?></textarea>
                        <div class="clear"></div>
                        <div class="commenttxt">
                            <input readonly type="text" name="left" size=1 maxlength=8 value="200" style="" />
                            characters remaining
                        </div>
                    </div>

                </li>
                <!-- Location -->
                <li>
                    <label><?php echo JText::_('COM_SOCIALPINBOARD_LOCATION'); ?></label>
                    <div class="edit_input">
                        <input type="text" name="location" value="<?php echo $profileDetails[0]->location; ?>" id="location" />
<!--                         <span class="help_text">e.g. Palo Alto, CA</span>-->
                    </div>


                </li>

                <!-- Website -->
                <li>
                    <label><?php echo
                            JText::_('COM_SOCIALPINBOARD_WEBSITE'); ?></label>
                    <div class="edit_input">
                        <input type="text" name="website" value="<?php echo $profileDetails[0]->website; ?>" id="website"/>
                            <span id="WebsiteVerified" style="display:none" class="DomainVerified">Website Verified</span>
<!--                            <a id="VerifyWebsiteButton" href="javascript:" class="Button WhiteButton Button18">Verify Website</a>-->
                    </div>

                </li>
                <!-- Image -->

                <li>
                    <label>
                        <?php echo JText::_('COM_SOCIALPINBOARD_IMAGE'); ?>
                    </label>


                    <div class="edit_input" >
                        <div class="upload_image floatleft" >

                            <?php
                            if (!$profileDetails[0]->user_image) {
                                $srcPath =
                                        "components/com_socialpinboard/images/no_user.jpg";
                            } else {
                                $userImageDetails =
                                        pathinfo($profileDetails[0]->user_image);
                                $userProfileImage = $userImageDetails['filename'] .
                                        '_o.' . $userImageDetails['extension'];
                                $srcPath = "images/socialpinboard/avatars/" .
                                        $userProfileImage;
                            }
                            ?>
                            <img src="<?php echo $srcPath; ?>" id="profileimage" name="profileimage" alt="change image"  />
                        </div>
                        <div class="upload_buttons floatleft">

                            <input type="file" id="user_image" name="user_image"
                                   class="hide" onchange="readURL(this);" />
                            <input type="button" id="changebtn"  alt="" value="<?php echo JTEXT::_('COM_SOCIALPINBOARD_UPLOAD_AN_IMAGE'); ?>"
                                   class="upload-btn" style="cursor:pointer;"/>
                        </div>
                        <div id="preview"></div>
                    </div>

                </li>
                <!-- Delete an account -->
                
            </ul>
            <div class="edit-submit">
                <input type="submit" onclick="return valPassword();" value="<?php echo JTEXT::_('COM_SOCIALPINBOARD_SAVE_PROFILE'); ?>" />
            </div>
            <script type="text/javascript">
                function CountLeft(field, count, max)
                                   {
                                       // if the length of the string in the input field is greater than the max value, trim it
                                       if (field.value.length > max)
                                           field.value = field.value.substring(0, max);
                                       else
                                           count.value = max - field.value.length;
                                   }
                function changePassword() {
                    document.getElementById("change_password").style.display = "block";
                    document.getElementById("passwrod_show").setAttribute("onClick","closePassword();");

                }
                function closePassword() {
                    document.getElementById("change_password").style.display = "none";
                    document.getElementById("passwrod_show").setAttribute("onClick","changePassword();");

                }
                function valPassword() {

                    var website=document.getElementById('website').value;
                    var pattern = /^((http|https):\/\/)|[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/;
                    if(document.getElementById("change_password").style.display  == "block") {
                        if(document.getElementById("old_password").value=="")
                        {
                            alert("Please Enter Current Password");
                            document.getElementById("old_password").focus();
                            return false;
                        }
                        if(document.getElementById("new_password").value=="") {
                            alert("Please Enter New Password");
                            document.getElementById("new_password").focus();
                            return false;
                        }
                        if(document.getElementById("new_password").value.length < 5) {
                            alert("Password cannot be less than 5 characters");
                            document.getElementById("new_password").focus();
                            return false;
                        }
                        if(document.getElementById("confirm_password").value=="") {
                            alert("Please Enter Confirm Password");
                            document.getElementById("confirm_password").focus();
                            return false;
                        }
                        if(document.getElementById("confirm_password").value.length < 5) {
                            alert("Confirm password cannot be less than 5 characters");
                            document.getElementById("confirm_password").focus();
                            return false;
                        }
                    }

                    if(document.getElementById("first_name").value=="") {
                        alert("Please Enter First Name");
                        document.getElementById("first_name").focus();
                        return false;
                    }
                    if(document.getElementById("last_name").value=="") {
                        alert("Please Enter Last Name");
                        document.getElementById("last_name").focus();
                        return false;
                    }
                    if(website!='')
                        {

        if (pattern.test(website)) {
            return true;
        }else
            {
                alert("Enter valid website");
                return false;
            }
                        }

                }

            </script>
            <script>
                function readURL(input) {
                    var extension=getFileExtension(input.value);

                    extension=extension.toLowerCase()

                         if(extension=='jpg' ||extension=='png' || extension=='jpeg' || extension=='gif' )
                        {

                    if(document.all)
    {
        document.getElementById("profileimage").src ='';
        var newPreview = document.getElementById("profileimage");
        newPreview.src = input.value;
//        newPreview.style.width = "150px";
//        newPreview.style.height = "200px";

    }
    else
        {
                    if (input.files && input.files[0]) {
                        var reader = new FileReader();
                        reader.onload = function (e) {
                            $('#profileimage')
                            .attr('src', e.target.result)
//                            .width(150)
//                            .height(200);
                        };
                        reader.readAsDataURL(input.files[0]);
                    }
                }
                }else
                {
                alert("Please upload an image");
                return false;
                }
                }


                function getFileExtension(filename) {
            var ext = /^.+\.([^.]+)$/.exec(filename);
            return ext == null ? "" : ext[1];
        }


            </script>
        </div>
    </form>
</div>

<script>

    $("#VerifyWebsiteButton").on("click", function() {
            var website = $("#website").val();
             $.ajax({
  type: "POST",
  url: "?option=com_socialpinboard&view=ajaxcontrol&tmpl=component&task=websiteverify",
  data: { 'site_name': website }
})
        });


    $('#delete_user_account').click(function(e) {
        e.preventDefault();
        $(this).hide();
        $('#DeleteForm').show();
        return false;
    });
    $('#enable_button').click(function() {
        if ($(this).is(':checked')) {

            $('#delete_user_account_confirm').removeAttr('disabled').removeClass('disabled')
            ;
        } else {
            $('#delete_user_account_confirm').attr('disabled',
            'disabled').addClass('disabled');
        }
    });
    $("#delete_user_account_confirm").click(function() {
        if ($(this).hasClass('disabled')) {
            return false;
        } else {
            $(this).text('DeletingÃ¢â‚¬Â¦');
            $(this).addClass('disabled');

            $.ajax({
                type: 'delete',
                url: '/delete_user/',
                dataType: 'json',
                success: function(data, textStatus) {
                    if (data.status == "success") { window.location="/"; }
                    else { alert(" Oops! Something went wrong! ");
                    }
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    alert(" Oops! The request failed for some reason.");
                }
            });
        }

        return false;
    });
    $('#ChangeOfHeart').click(function() {
        $('#DeleteForm').hide();
        $('#enable_button').removeAttr('checked');
        $('#delete_user_account_confirm').attr('disabled',
        'disabled').addClass('disabled');
        $('#delete_user_account').show();
        return false;
    });
    $('#delete_user_account').click(function() {
        $(this).hide();
        $('#DeleteForm').show();
        return false;
    });


</script>