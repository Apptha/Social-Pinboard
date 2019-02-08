<?php
/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component boardedit view
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_socialpinboard/css/facebox.css');
$document->addStyleSheet('components/com_socialpinboard/css/style.css');
$document->addStyleSheet('components/com_socialpinboard/css/edit.css');
$document->addStyleSheet('components/com_socialpinboard/css/reset.css');
$document->addStyleSheet('components/com_socialpinboard/css/pinboard.css');
$document->addScript('https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js');
$document->addScript('components/com_socialpinboard/javascript/jquery.ui.core.js');
$document->addScript('components/com_socialpinboard/javascript/chrome.js');
$document->addScript('components/com_socialpinboard/javascript/facebox.js');
$document->addScript('components/com_socialpinboard/javascript/scroll/jquery.isotope.min.js');
$document->addScript('components/com_socialpinboard/javascript/scroll/jquery.infinitescroll.min.js');
//Get a application object

$app = JFactory::getApplication();
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
$editBoard = $this->boardEdit;
$contributers = $this->Contributers;
$bidd = JRequest::getVar('bidd');
$pincate = $this->pincate;
$getUserBoardDetails = $this->getUserBoardDetails;

$user = JFactory::getUser();
?>
<script>
    
    function remove_contributers(remove_con,bidd)
    {   
    
    
        var remove_con; 
    
        document.getElementById('contributer_new'+remove_con).style.display="none";
    

        if (window.XMLHttpRequest)
        {// code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp=new XMLHttpRequest();
        }
        else
        {// code for IE6, IE5
            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange=function()
        {
            if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
    
                try
                {
             
                }
                catch(e) {
                    alert(e.message)
                }
            }
    
        }
        var url = "?option=com_socialpinboard&view=ajaxcontrol&tmpl=component&task=removeContributers&user_id="+remove_con+"&bidd="+bidd;
        xmlhttp.open("GET",url,true);
        xmlhttp.send(null);
        return true;
    
    }

   
    

</script>
<style> 
    #livesearchVal
    {
        border: 1px solid #CCCACA;
        border-radius: 6px 6px 6px 6px;
        font-weight: normal;
        list-style: none outside none;
        overflow: hidden;
        width: 270px;
        background: white;
        z-index: 999;
        margin-top: -17px;
        position: absolute;
        padding: 5px;
    }
    #add_contributers
    {
        float: none;
        margin: 10px;
        padding: 9px 24px 7px;
        font-size: 16px;
        text-align: center;
        text-shadow: 0 0px rgba(34, 25, 25, 0.5);
        *padding: 5px 14px;
        padding: 10px 40px 5px \0/;
    }
    #contributer_new{
        font-size: 18px;
    }
    .contributer_li{
        clear: both;
        color: #8C7E7E;
        margin: 20px 0;
        text-shadow: 0 1px rgba(255, 255, 255, 0.9);
    }

    #static_li
    {
        cursor: pointer;    
    }


</style>
<!-- Load CSS and JS -->

<?php
$img_link = JURI::base() . 'images/socialpinboard/avatars/';
$board_cuser_id = explode(',', $editBoard->cuser_id);
?>
<div class="board_edit_wrapper">
<?php
if (in_array($user->id, $board_cuser_id) && $user && $editBoard->user_id != $user->id) {
?>
    <h3>
    <?php echo JText::_('COM_SOCIALPINBOARD_EDIT_BOARD'); ?>
    </h3>
    <div class="static-form clearfix" id="form_contributes">
        <div class="board_edit_wrapper_left">
            <ul>
                <li>
                    <label><?php echo JText::_('COM_SOCIALPINBOARD_WHO_CAN_PIN'); ?></label>
                </li>
            </ul>
        </div>
        <div class="board_edit_wrapper_right">
            <ul>
                <li>
                    <input type="text" name="name_contributer" id="name_contributer" autocomplete="off" style="color: #C9C8C8" value="<?php echo JText::_('COM_SOCIALPINBOARD_NAME_OR_EMAIL_ADDRESS'); ?>" onFocus="onFocusEvent(this,'Name or Email Address');" onBlur="onBlurEvent(this,'Name or Email Address');"  onkeyup="showSearchResult(this.value);"/>
                    <input type="button" class="contributer_boardEdit"  name="add_contributers" id="add_contributers" onclick="userContributer('<?php echo $img_link ?>','<?php echo $bidd ?>');" value="<?php echo JText::_('COM_SOCIALPINBOARD_ADD'); ?> "/>
                    <div id="username_error"></div>
                </li>

            </ul>
            <div id="SearchVal">
                <ul id="livesearchVal" style="display:none;"></ul>
            </div>

            <div id="contributer_add">
<?php
    foreach ($contributers as $contributer) {
?>
                <div id="contributer_new<?php echo $contributer->user_id; ?>" class="contributer_new_tag clearfix" >
                <?php
                if ($contributer->user_image == '') {

                    $profile_avatar = JURI::base() . '/components/com_socialpinboard/images/no_user.jpg';
                } else {

                    $profile_avatar = JURI::base() . 'images/socialpinboard/avatars/' . $contributer->user_image;
                }
                ?>
                    <img src="<?php echo $profile_avatar; ?>" title="<?php echo $contributer->first_name . ' ' . $contributer->last_name; ?>" id='photoUrl' alt="<?php echo $contributer->first_name . ' ' . $contributer->last_name; ?>" class="avatar" />
                    <a href="" class="contributer_new_name">
                    <?php echo $contributer->first_name . ' ' . $contributer->last_name; ?>
                    </a>
                    <a href="javascript:void(0)" class="contributer_new_name_remove" onclick="remove_contributers('<?php echo $contributer->user_id ?>','<?php echo $bidd ?>');">
                    </a>
                </div>

<?php
                }
                if ($getUserBoardDetails->user_image == '') {

                    $profile_avatar = JURI::base() . '/components/com_socialpinboard/images/no_user.jpg';
                } else {

                    $profile_avatar = JURI::base() . 'images/socialpinboard/avatars/' . $getUserBoardDetails->user_image;
                }
?>
                <div>
                    <input type="hidden" value="" id="searchUserId" name="searchUserId" />
                </div>


                <ul>
                    <li>
                        <img height="40px" src="<?php echo $profile_avatar; ?>" title="<?php echo $getUserBoardDetails->username; ?>" id='photoUrl' alt="<?php echo $getUserBoardDetails->username; ?>" width="40px" class="avatar" /> <?php echo $getUserBoardDetails->username; ?> Creator
                    <li>
                </ul>
<?php
            }
?>
            </div>

        </div>

    </div>
</div>

                <?php
                if ($editBoard->user_id == $user->id && $user->id != '0') {
                ?>
    <div class="board_edit_wrapper" style="min-height:600px;margin-top:50px;">
        <div class="static-form">
            <h3 class="edit-board-title"><?php echo JText::_('COM_SOCIALPINBOARD_EDIT'); ?> / <font><?php echo JText::_($editBoard->board_name); ?></font></h3>
            <form action="" method="post">
                <ul class="edit-board">
                    <!--Title-->
                    <li>
                        <label><?php echo JText::_('COM_SOCIALPINBOARD_TITLE'); ?></label>
                                        <input class="title-edit" type="text" name="editboard_name" id="editboard_name" value="<?php echo $editBoard->board_name; ?>"/>
                                    </li>
                                    <!--Description-->
                                    <li>
                                        <label><?php echo JText::_('COM_SOCIALPINBOARD_DESCRIPTION'); ?></label>
                                        <textarea name="board_description" id="board_description"><?php echo $editBoard->board_description; ?></textarea>
                                    </li>

                                    <li>
                                        <label> <?php echo JText::_('COM_SOCIALPINBOARD_WHO_CAN_PIN'); ?></label>
                                        <div >
                                            <ul class="pinability">

<?php
                    if ($editBoard->board_access == "0") {
                        $just = JText::_('COM_SOCIALPINBOARD_CHECKED');
                        $contributors = "";
?>
                                                    <li>
                                                        <label>
                                                            <input class="Just-pin" type="radio" name="editboard_board_access" onclick=" return contributersoff(('<?php echo $bidd ?>'));" id="editboard_board_access" value="0" <?php echo $just; ?> /><span class="justpin-img"><?php echo JText::_('COM_SOCIALPINBOARD_JUST_ME'); ?></span>
                                                        </label>
                                                    </li>
                                                    <li class="last-child" style="width:auto;">
                                                        <label style="width:auto;">
                                                            <input class="Just-pin" type="radio" name="editboard_board_access" onclick=" return contributerson();" id="editboard_board_access" value="1" <?php echo $contributors; ?>/>
                                </label>
                            </li>
                            
                            <li><span class="mepin-img"><?php echo JText::_('COM_SOCIALPINBOARD_ME_CONTRIBUTERS'); ?></span></li>
                        </ul>
                    </div>
                </li>
                <li>
                    <div class="static-form static-form-board-edit" id="form_contributes" style="display:none;">
                        <ul>
                            <li>
                                <input type="text" name="name_contributer" id="name_contributer" style="color: #C9C8C8" value="<?php echo JText::_('COM_SOCIALPINBOARD_NAME_OR_EMAIL_ADDRESS'); ?>" onFocus="onFocusEvent(this,'<?php echo JText::_('COM_SOCIALPINBOARD_NAME_OR_EMAIL_ADDRESS'); ?>');" onBlur="onBlurEvent(this,'<?php echo JText::_('COM_SOCIALPINBOARD_NAME_OR_EMAIL_ADDRESS'); ?>');"  onkeyup="showSearchResult(this.value);"/> <input type="button" class="contributer_boardEdit"  name="add_contributers" id="add_contributers" onclick="userContributer('<?php echo $img_link ?>','<?php echo $bidd ?>');" value="<?php echo JText::_('COM_SOCIALPINBOARD_ADD'); ?>" />
                                <div id="username_error"></div>
                            </li>
                        </ul>
                        <div id="SearchVal">
                            <ul id="livesearchVal" style="display:none;">

                            </ul>
                        </div>
                    </div>


                    <div id="contributer_add">
<?php
                            if (!empty($contributers)) {

                                foreach ($contributers as $contributer) {
?>
                                <div id="contributer_new<?php echo $contributer->user_id; ?>" >
<?php
                                    if ($contributer->user_image == '') {

                                        $profile_avatar = JURI::base() . '/components/com_socialpinboard/images/no_user.jpg';
                                    } else {

                                        $profile_avatar = JURI::base() . 'images/socialpinboard/avatars/' . $contributer->user_image;
                                    }
?>
                                        <img src="<?php echo $profile_avatar; ?>" title="<?php echo $contributer->first_name . ' ' . $contributer->last_name; ?>" id='photoUrl' alt="<?php echo $contributer->first_name . ' ' . $contributer->last_name; ?>" class="avatar" /> <?php echo $contributer->first_name . ' ' . $contributer->last_name; ?>
                                        <a href="javascript:void(0)" onclick="remove_contributers('<?php echo $contributer->user_id ?>','<?php echo $bidd ?>');" class="contributer_new_name_remove"></a>
                                </div>

                            <?php
                                }
                            }
                            ?></div>           </li>
                <li>
                    <input type="hidden" value="" id="searchUserId" name="searchUserId" />
                            <?php
                        } else {
                            $just = "";
                            $contributors = JText::_('COM_SOCIALPINBOARD_CHECKED');
                            ?>
                <li>
                    <label >
                        <input class="Just-pin" type="radio" name="editboard_board_access" onclick=" return contributersoff(('<?php echo $bidd ?>'));" id="editboard_board_access" value="0" <?php echo $just; ?> /><span class="justpin-img"><?php echo JText::_('COM_SOCIALPINBOARD_JUST_ME'); ?></span>
                    </label>
                </li>
                <li class="last-child" style="width:auto;">
                    <label style="width:auto;">
                        <input class="Just-pin" type="radio" name="editboard_board_access"  onclick=" return contributerson();" id="editboard_board_access" value="1" <?php echo $contributors; ?>/>
                        </label>
                    </li>
                    
                    <li><span class="mepin-img"><?php echo JText::_('COM_SOCIALPINBOARD_ME_CONTRIBUTERS'); ?></span></li>
                </ul>
        </div>
    </li>
    <li>
        <div class="static-form static-form-board-edit" id="form_contributes" >
            <ul>
                <li>
                    <input type="text" name="name_contributer" id="name_contributer" style="color: #C9C8C8" value="<?php echo JText::_('COM_SOCIALPINBOARD_NAME_OR_EMAIL_ADDRESS'); ?>" onFocus="onFocusEvent(this,'<?php echo JText::_('COM_SOCIALPINBOARD_NAME_OR_EMAIL_ADDRESS'); ?>');" onBlur="onBlurEvent(this,'<?php echo JText::_('COM_SOCIALPINBOARD_NAME_OR_EMAIL_ADDRESS'); ?>');"  onkeyup="showSearchResult(this.value);"/> <input type="button" class="contributer_boardEdit"  name="add_contributers" id="add_contributers" onclick="userContributer('<?php echo $img_link ?>','<?php echo $bidd ?>');" value="<?php echo JText::_('COM_SOCIALPINBOARD_ADD'); ?>" />
                        <div id="username_error"></div>
                    </li>
                </ul>
                <div id="SearchVal">
                    <ul id="livesearchVal" style="display:none;">

                    </ul>
                </div>
            </div>


            <div id="contributer_add">
<?php
                            if (!empty($contributers)) {

                                foreach ($contributers as $contributer) {
?>
                        <div id="contributer_new<?php echo $contributer->user_id; ?>" >
<?php
                                    if ($contributer->user_image == '') {

                                        $profile_avatar = JURI::base() . '/components/com_socialpinboard/images/no_user.jpg';
                                    } else {

                                        $profile_avatar = JURI::base() . 'images/socialpinboard/avatars/' . $contributer->user_image;
                                    }
?>
                                        <img src="<?php echo $profile_avatar; ?>" title="<?php echo $contributer->first_name . ' ' . $contributer->last_name; ?>" id='photoUrl' alt="<?php echo $contributer->first_name . ' ' . $contributer->last_name; ?>" class="avatar" /> <?php echo $contributer->first_name . ' ' . $contributer->last_name; ?>
                                        <a href="javascript:void(0)" onclick="remove_contributers('<?php echo $contributer->user_id ?>','<?php echo $bidd ?>');" class="contributer_new_name_remove"></a>
                                    </div>

<?php
                                }
                            }
?></div>           </li>
                <li>
            <?php
                        }
            ?>



                <label> <?php echo JText::_('COM_SOCIALPINBOARD_CATEGORY'); ?></label>

                <select name="editboard_category" id="editboard_category" class="BoardPicker customStyleSelectBox">
<?php foreach ($pincate as $cate) { ?>
                        <option value="<?php echo $cate->category_id; ?>" <?php
                            if ($editBoard->board_category_id == $cate->category_id) {
                                echo 'selected=selected';
                            }
?>><?php echo $cate->category_name; ?></option>
        <?php } ?>

                    </select>
                    <input type="hidden" value="" id="searchUserId" name="searchUserId" />
                    <span class="down-arrow"></span>

                </li>
                <li>

<?php
                        if ($getUserBoardDetails->user_image == '') {

                            $profile_avatar = JURI::base() . '/components/com_socialpinboard/images/no_user.jpg';
                        } else {

                            $profile_avatar = JURI::base() . 'images/socialpinboard/avatars/' . $getUserBoardDetails->user_image;
                        }
?>
            <label> &nbsp;</label>
            <img height="40px" style="float: left;vertical-align: middle; margin-right:10px" src="<?php echo $profile_avatar; ?>" title="<?php $getUserBoardDetails->username; ?>" id='photoUrl' alt="<?php $getUserBoardDetails->username; ?>" width="40px" class="avatar" />
            <div class="coard_edit_creator floatleft">
<?php
                        echo $getUserBoardDetails->username;
?>
            </div>

            <span class="note">
                (Creator)
            </span>


        </li>
        <!-- Delete-->
        <li>
            <label>&nbsp;</label>
            <a href="#" class="delete-board" onclick="Modal.show('pinDiv'); return false"><?php echo JText::_('COM_SOCIALPINBOARD_DELETE'); ?></a>
                        <button type="submit" onclick="return valBoard();" name="update_board" id="update_board" value="<?php echo JText::_('COM_SOCIALPINBOARD_SAVE_CHANGES'); ?>" class="save-editboard"><?php echo JText::_('COM_SOCIALPINBOARD_SAVE_SETTINGS'); ?></button>
                    </li>
                    </ul>
                    </form>
                    </div>
                    </div>
                    <!-- Delete Board Alert -->
                    <div id="pinDiv" class="ModalContainer">
                        <div class="modal wide">

                            <p style="font-size: 18px;font-weight: bold; margin-bottom: 20px;"><?php echo JText::_('COM_SOCIALPINBOARD_DELETE_ALERT'); ?></p>
                        <form action="" method="post">
                            <input type="submit" name="delete_board" id="delete_board" value="<?php echo JText::_('COM_SOCIALPINBOARD_DELETE_BOARD'); ?>"/>
                            <input type="button" onclick="Modal.close('pinDiv'); return false" name="no_delete" id="no_delete" value="<?php echo JText::_('COM_SOCIALPINBOARD_CANCEL'); ?>"/>

                        </form>
                    </div>
                </div>

<?php
                    }
?>
            <script type="text/javascript">

                function valBoard() {
                    if(document.getElementById("editboard_name").value=="")
                    {
                        alert('<?php echo JText::_('COM_SOCIALPINBOARD_PLEASE_ENTER_BOARD_NAME'); ?>');
                        document.getElementById("editboard_name").focus();
                        return false;
                    }
                }
                function contributerson()
                {
                    document.getElementById('form_contributes').style.display="block";
                }
                function contributersoff(bidd)
                {
                    document.getElementById('form_contributes').style.display="none";
                    document.getElementById('contributer_add').style.display="none";
                    if (window.XMLHttpRequest)
                    {// code for IE7+, Firefox, Chrome, Opera, Safari
                        xmlhttps=new XMLHttpRequest();
                    }
                    else
                    {// code for IE6, IE5
                        xmlhttps=new ActiveXObject("Microsoft.XMLHTTP");
                    }
                    xmlhttps.onreadystatechange=function()
                    {
                        if (xmlhttps.readyState==4 && xmlhttps.status==200)
                        {


                            try
                            {
                            }

                            catch(e) {
                                alert(e.message)
                            }
                        }
                    }

                    var url = "?option=com_socialpinboard&view=ajaxcontrol&tmpl=component&task=justMeContributors&bidd="+bidd;
                    xmlhttps.open("GET",url,true);
                    xmlhttps.send(null);
                }
                function showSearchResult(str)
                {
                    document.getElementById("loadingImage").style.display="block";

                    str = jQuery.trim(str);
                    if (str.length==0 || str == '<?php echo JText::_('COM_SOCIALPINBOARD_NAME_OR_EMAIL_ADDRESS') ?>')
                            {
                                document.getElementById("loadingImage").style.display="none";
                                return;
                            }
                            if (window.XMLHttpRequest)
                            {// code for IE7+, Firefox, Chrome, Opera, Safari
                                xmlhttp=new XMLHttpRequest();
                            }
                            else
                            {// code for IE6, IE5
                                xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                            }
                            xmlhttp.onreadystatechange=function()
                            {
                                if (xmlhttp.readyState==4 && xmlhttp.status==200)
                                {

                                    if(xmlhttp.responseText.length == 2)
                                    {
                                        document.getElementById("livesearchVal").innerHTML="";
                                        document.getElementById("livesearchVal").style.display="none";
                                        document.getElementById("loadingImage").style.display="none";
                                    }
                                    else{
                                        document.getElementById("livesearchVal").style.display="block";
                                        document.getElementById("loadingImage").style.display="none";


                                        var searchValue=xmlhttp.responseText;

                                        var obj = jQuery.parseJSON(searchValue);
                                        var nameArray = obj.username;
                                        var idArray=obj.userid

                                        var b='';
                                        for(var i=0;i< nameArray.length;i++)
                                        {
                                            b+='<li id="static_li" style="padding-left:5px;" onclick="userSelect(\''+nameArray[i]+'\',\''+idArray[i]+'\')" >'+nameArray[i]+'</li>';

                                        }
                                        if(searchValue==',')
                                        {
                                            document.getElementById("livesearchVal").style.display='none';
                                            return false;

                                        }

                                        var a=searchValue.split("^abc^");



                                        document.getElementById("livesearchVal").innerHTML=b;

                                    }

                                }
                            }

                            var url = "?option=com_socialpinboard&view=ajaxcontrol&tmpl=component&task=getcontributers&user="+str;
                            xmlhttp.open("GET",url,true);
                            xmlhttp.send();
                        }
                        function userSelect(userCon,userId)
                        {

                            document.getElementById('name_contributer').value=userCon;
                            document.getElementById('livesearchVal').style.display='none';
                            document.getElementById('searchUserId').value=userId;
                        }
                        function userContributer(img_link,bidd)
                        {
                            var name_contributers=document.getElementById('name_contributer').value;
                            var user_id_contributors=document.getElementById('searchUserId').value;
                            if(name_contributers=='<?php echo JText::_('COM_SOCIALPINBOARD_NAME_OR_EMAIL_ADDRESS') ?>')
                            {
                                alert('<?php echo JText::_('COM_SOCIALPINBOARD_PLEASE_ENTER_THE_NAME') ?>');
                                return false;
                            }
                            var name_contributer = name_contributers.replace(' ', '%20');

                            if (window.XMLHttpRequest)
                            {// code for IE7+, Firefox, Chrome, Opera, Safari
                                xmlhttps=new XMLHttpRequest();
                            }
                            else
                            {// code for IE6, IE5
                                xmlhttps=new ActiveXObject("Microsoft.XMLHTTP");
                            }
                            xmlhttps.onreadystatechange=function()
                            {
                                if (xmlhttps.readyState==4 && xmlhttps.status==200)
                                {


                                    try
                                    {

                                        var user_contributers = xmlhttps.responseText;
                                        if(user_contributers!='Username not exist'){
                                            if(user_contributers=='' || user_contributers=='0')
                                            {
                                                alert('<?php echo JText::_('COM_SOCIALPINBOARD_LOOK_LIKE_SOMETHING_WENT_WRONG') ?>');
                                                document.getElementById('name_contributer').value='';
                                                document.getElementById('searchUserId').value='';
                                                return false;
                                            }
                                            var contributer_details=user_contributers.split(",");

                                            if(user_contributers==','  )
                                            {
                                                alert('<?php echo JText::_('COM_SOCIALPINBOARD_LOOK_LIKE_SOMETHING_WENT_WRONG') ?>');
                                                document.getElementById('name_contributer').value='';
                                                document.getElementById('searchUserId').value='';
                                                return false;
                                            }

                                            var ni = document.getElementById('contributer_add');
                                            var newdiv = document.createElement('div');
                                            var contributer_new='contributer_new'+contributer_details[1];
                                            newdiv.setAttribute('id',contributer_new);
                                            if(contributer_details[0]!='')
                                            {

                                                var imagelink=img_link.concat(contributer_details[0]);
                                            }
                                            else
                                            {
                                                var imagelink='<?php echo JURI::base() . '/components/com_socialpinboard/images/no_user.jpg ' ?>';
                        }
                        content = '<img src="'+imagelink+'" class="avatar" />'+name_contributers.replace('%20', ' ')+' '+'<a href="javascript:void(0)" class="contributer_new_name_remove" onclick="remove_contributers('+contributer_details[1]+','+bidd+');" ></a>';
                    
                        newdiv.innerHTML = content;
                        ni.appendChild(newdiv);
                        document.getElementById('name_contributer').value='';
                        document.getElementById('searchUserId').value='';
                    }else{
                        document.getElementById('username_error').innerHTML=user_contributers;
                    }
                }
                
                catch(e) {
                    alert(e.message)
                }
            }
        }
    
        var url = "?option=com_socialpinboard&view=ajaxcontrol&tmpl=component&task=addBoardContributers&name_contributer="+name_contributer+"&user_id_contributors="+user_id_contributors+"&bidd="+bidd;
        xmlhttps.open("GET",url,true);
        xmlhttps.send(null);
    }
</script>


