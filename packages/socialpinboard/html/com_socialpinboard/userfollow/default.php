<?php
/**
 * @name          : Joomla Social Pinboard
 * @version	  : 1.5.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component Userfollow view
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');
$document = JFactory::getDocument();
$document->addStyleSheet('templates/socialpinboard/html/com_socialpinboard/css/style.css');
$document->addScript( 'components/com_socialpinboard/javascript/chrome.js' );
$user = JFactory::getUser();
$userID = $user->id;
$category_details = $this->getCategories;
$category_users = $this->getCategory;

$followusercompl = JRequest::getVar('followusercompl');
$user = JFactory::getUser();

?>

<script language='javascript'>
        var follow_all_lang="<?php echo JText::_('COM_SOCIALPINBOARD_FOLLOW_ALL'); ?>";
                var un_follow_board_lang="<?php echo JText::_('COM_SOCIALPINBOARD_UNFOLLOW_BOARD'); ?>";
                var un_follow_lang="<?php echo JText::_('COM_SOCIALPINBOARD_UNFOLLOW'); ?>";
                var follow_board_lang="<?php echo JText::_('COM_SOCIALPINBOARD_FOLLOW_BOARD'); ?>";
                var follow_user_lang="<?php echo JText::_('COM_SOCIALPINBOARD_FOLLOW_USER'); ?>";
   function loaddefimages(Id)  
    {  
        document.getElementById(Id).src="<?php echo JURI::base() . "components/com_socialpinboard/images/category/no_category.png"; ?>";  
    }    
</script> 
<form action="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=userfollow'); ?>" method="post" name="userfollow" id="userfollow" >
<?php
if ($followusercompl == '' && $category_details!='') {
    ?>
    <label id="categoryvalidation_error_msg" style="display: none;color: red;font-size: 16px;font-weight: bold;text-align: center;"><?php echo JText::_('COM_SOCIALPINBOARD_SELECT_MINIMUM_CATEGORIES'); ?></label>
        <div id="category" >
            <h1 style="margin: 0 auto 28px; text-align: center; font-weight: 300; font-size: 32px;line-height: 43px;"><?php echo JText::_('COM_SOCIALPINBOARD_CLICK_A_FEW_THINGS_SUGGEST_PEOPLE_TO_FOLLOW'); ?></h1>
            <div class="click_category clearfix">
        <?php
        foreach ($category_details as $category_detail) {
            ?>
                <div class="tickcategory_categoryimage">
                   
               
                    <?php
                    
                  
                      //display the pin description with readmore option if it exceeds 250 chars
                    if (strlen($category_detail->category_name) < 15) {
                        echo $category_detail->category_name;
                    } else {
                        $category_name = substr($category_detail->category_name, 0, 15);
                        echo $category_name . ' .... ';
                      
                    }
                    ?>
                    <div id="tickcategory<?php echo $category_detail->category_id; ?>"  style="display:none">
                        <img src="<?php echo JURI::base() . "components/com_socialpinboard/images/tick.png"; ?>" id="tick_image<?php echo $category_detail->category_id; ?>" height="150"  onclick="getUncategory('<?php echo $category_detail->category_id; ?>','<?php echo $userID; ?>');" width="150" />
                    </div>

                    <div id="categoryimage<?php echo $category_detail->category_id; ?>" style="display:block" >
                        <?php if($category_detail->category_image=='')
                        {
                            ?>
                    <img src="<?php echo JURI::base() . "components/com_socialpinboard/images/category/no_category.png"; ?>" title="<?php echo $category_detail->category_name; ?>" id="category_image<?php echo $category_detail->category_id; ?>"  height="150" width="150" onclick="getCategory('<?php echo $category_detail->category_id; ?>','<?php echo $userID; ?>');"/>
                    <?php
                        }
                        else
                        {
                        ?>
                        <img src="<?php echo JURI::base() . "components/com_socialpinboard/images/category/" . $category_detail->category_image; ?>" title="<?php echo $category_detail->category_name; ?>" onerror="javascript:loaddefimages(this.id);" id="category_image<?php echo $category_detail->category_id; ?>" height="150" width="150" onclick="getCategory('<?php echo $category_detail->category_id; ?>','<?php echo $userID; ?>');"/>
                        <?php
                        }
                        ?>
                    </div>
                </div>

        <?php
    }
    ?>
                <div class="clear"></div>
            </div>
            <input type="hidden" name="categorycount" id="categorycount" value="0"/>
            <input type="hidden" name="followusercompl" id="followusercompl" value="Notcomplete"/>
        </div>
        <input type="submit" onclick="return validatecategory();"  value="<?php echo JText::_('COM_SOCIALPINBOARD_FOLLOW_PEOPLE').' ' ?> &#187;" id="next" name="next" />
            <?php
        }
        if ($followusercompl == 'Notcomplete') {
?>
         
        <?php
        
            if (empty($category_users) || $category_users=='' ) {
         
        echo '<label id="login_error_msg" style="display: block;">'.JText::_('COM_SOCIALPINBOARD_SORRY_NO_USER_SELECTED_FOR_CATEGORY').'</label>';
        ?>
            <input type="hidden" name="followusercompl" id="followusercompl" value="Complete"/>
            <input type="submit" value="<?php echo JText::_('COM_SOCIALPINBOARD_SKIP').' ' ?> &#187;" id="next" name="next" style="margin:10px 35%;" />
            <?php
        } else {
            ?>
            <label id="followervalidation_error_msg" style="display: none;color: red;font-size: 16px;font-weight: bold;text-align: center;"><?php echo JText::_('COM_SOCIALPINBOARD_FOLLOW_ATLEAST_ONE_USER'); ?></label>
            <h1 style="margin: 0 auto 28px; text-align: center; font-weight: 300; font-size: 32px;line-height: 43px;"><?php echo JText::_('COM_SOCIALPINBOARD_SELECT_FEW_USERS_TO_FOLLOW'); ?> </h1>
            <?php

            if($category_users !='No Categories')
            {
$i=0;
                foreach ($category_users as $category_user) {
                    if($category_user->user_image!='')
                    {
                    $userImageDetails = pathinfo($category_user->user_image);
            $user_Image = $userImageDetails['filename'] . '_o.' . $userImageDetails['extension'];
                    }
                    $style='';
                    if($i%5==0)
                        $style='style=" clear: both; "';
                    ?>
         
                <div class="tickcategory_categoryimage" <?php echo $style; ?>>
  <?php                  
                    if($category_user->user_image=='')
                    {
                        ?>
                     <img src="<?php echo JURI::base() . "components/com_socialpinboard/images/no_user.jpg" ?>" id="category_user_image<?php echo $category_user->user_id; ?>" height="150" width="150" />
                    <?php
                    
                      
                        
                    }
 else {
     ?><img src="<?php echo JURI::base() . "images/socialpinboard/avatars/" . $user_Image; ?>" id="category_user_image<?php echo $category_user->user_id; ?>" height="150" width="150" />
       <?php
 }
 ?>
                      
 
                   
                    <div id="category_user_follow<?php echo $category_user->user_id; ?>" class="user_follow_title" style="padding-top:5px;">
                        
                        <input type="button" name="followuser" class="follow" value="<?php echo JTEXT::_('COM_SOCIALPINBOARD_FOLLOW_USER'); ?>" id="followuser" onclick="followusers(<?php echo $user->id . ',' . $category_user->user_id ?>)" />

                    </div>
                        <h1 class="user_follow_name">
                            <?php echo $category_user->first_name.' '.$category_user->last_name; ?>
                        </h1>
                </div>




            <?php
            $i++;
            }
        }
        ?>
            <input type="hidden" name="followerscount" id="followerscount" value="0"/>
            <input type="hidden" name="followusercompl" id="followusercompl" value="Complete"/>
            <input type="submit" onclick="return validatefollowers();"   value="<?php echo JTEXT::_('COM_SOCIALPINBOARD_CREATE_BOARDS').' '; ?> &#187;" id="next" name="next" />
        <?php
    }
    }
    if ($followusercompl == 'Complete') {
        ?>
        
        <div class="follow_create_Board" style=" ">
            <div id="new_account_create" style="">
                <h1><?php echo JText::_('COM_SOCIALPINBOARD_CREATE_YOUR_FIRST_PINBOARDS'); ?></h1>

                <fieldset class="input">  
                    <div class="details_page clearfix">
                        <img src="<?php echo JURI::base() . "components/com_socialpinboard/images/pin_img.jpg"; ?>" width="256px" height="156px" class="floatleft"  style="margin-top: 5px;"/>
                        <ul class="floatleft">
                        <li>
                            <input type="text" name="board1" class="login-inputbox" id="board1" value="<?php echo JText::_('COM_SOCIALPINBOARD_ME_AND_ME') ?>" />
                        </li>
                        <li>
                            <input type="text" name="board2" class="login-inputbox" id="board2" value="<?php echo JText::_('COM_SOCIALPINBOARD_FAVOURITE_PLACES_AND_SPACES') ?>" /></li> <li>
                            <input type="text" name="board3" class="login-inputbox" id="board3" value="<?php echo JText::_('COM_SOCIALPINBOARD_BOOKS_WORTH_READING') ?>" /> </li> <li>
                            <input type="text" name="board4" class="login-inputbox" id="board4" value="<?php echo JText::_('COM_SOCIALPINBOARD_MY_STYLES') ?>" /> </li> <li>
                            <input type="text" name="board5" class="login-inputbox" id="board5" value="<?php echo JText::_('COM_SOCIALPINBOARD_FOR_THE_HOME') ?>" />  </li>
                    </ul>
                      <input type="hidden" name="followusercompl" id="followusercompl" value="CreateBoards"/>
                    <input type="submit" value="<?php echo JText::_('COM_SOCIALPINBOARD_FINISH'); ?> &#187;" onclick="return validateBoards();" id="next" name="next" style=" margin: 10px 0 0 40%;" /> 
                    
                    
                    </div>

                  

                </fieldset>
            </div>
        </div>


    <?php
}
?>

</form>
<script type="text/javascript">
    window.onload = myOnloadFunc
    function myOnloadFunc()
    {
        document.getElementById('Header').style.display='none';
        document.getElementById('CategoriesBar').style.display='none';
    }
    function validateBoards()
    {
        var board_array = new Array();
for(var i=1;i<=5;i++)
                {
                     var board1=document.getElementById('board'+i).value;
board_array[i-1] = board1;
                }
                            for(i=1;i<=5;i++)
                            {
                                var board=document.getElementById('board'+i).value; 
                                if(board=='')
                                {
                                    
                                alert("<?php echo JText::_('COM_SOCIALPINBOARD_PLEASE_ENTER_BOARD_NAME'); ?>");
                                return false;
                                
                                }    
                                if(board_array.indexOf(board) != -1 && (i-1)!=board_array.indexOf(board)){
                alert("<?php echo JText::_('COM_SOCIALPINBOARD_BOARD_ALREADY_EXIST'); ?>");
                return false;
                            }
                            }
                            
                            
                        }
   function validatecategory()
    {
var board=document.getElementById('categorycount').value;
                   
                                if(board<5)
                                {
                   
                                document.getElementById('categoryvalidation_error_msg').style.display="block";
                                return false;

                                }

                        }
   function validatefollowers()
    {
var followerscount=document.getElementById('followerscount').value;

                                if(followerscount==0)
                                {

                                document.getElementById('followervalidation_error_msg').style.display="block";
                                return false;

                                }

                        }

</script>