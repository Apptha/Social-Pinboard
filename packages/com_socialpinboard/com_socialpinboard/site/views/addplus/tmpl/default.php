<?php
/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component addplus view
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_socialpinboard/css/style.css');
$document->addStyleSheet('components/com_socialpinboard/css/reset.css');
$document->addScript('https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js');
$document->addScript('modules/mod_socialpinboard_menu/js/pinboard.js');
$document->addScript('modules/mod_socialpinboard_menu/js/ajaxupload.js');
$user = JFactory::getUser();
$userID = $user->id;
$Usergroup = $this->getUsergroup;
$getCategories = $this->getCategories; //stores category list
$getBoardname = $this->getBoardname; //stores board name list
?>
<script src="<?php echo JURI::base() . '/components/com_socialpinboard/javascript/chrome.js' ?>" type="text/javascript"></script>
<div class="mobile-addplus">
    <div id="addpindiv" onclick="addapin();"><span><?php echo JTEXT::_('COM_SOCAILPINBOARD_MENU_ADD_A_PIN') ?></span> </div>
<!--    <div id="uploadpindiv" onclick="uploadapin();"><span><?php echo JTEXT::_('COM_SOCAILPINBOARD_MENU_UPLOAD_A_PIN') ?></span></div>-->
    <div id="createboard" onclick="createboard();"><span><?php echo JTEXT::_('COM_SOCAILPINBOARD_MENU_CREATE_A_BOARD') ?></span></div>
</div>


<!-- Start of Create the Board-->
<div id="mobCreateBoard" style="display:none;">
<h2><?php echo JTEXT::_('COM_SOCAILPINBOARD_MENU_CREATE_BOARD'); ?> </h2>
    <div id="createboardselectdiv">
        <form action="" method="post" class="Form StaticForm noMargin">
            <ul>
                <li class="noBorderTop">
                    <label><?php echo JText::_('COM_SOCAILPINBOARD_MENU_BOARD_NAME'); ?></label>
                    <input id="mobboard_name" type="text" name="board_name"/>
                    <div class="clear"></div>
                    <div id="mobboardnameerror"></div>
                    <span class="fff"></span>
                </li>
                <li>
                    <input id="mobboard_description" type="hidden" name="board_description" value="" />
                    <input id="mobcreated_date" type="hidden" name="created_date" value="<?php echo date("Y-m-d H:i:s"); ?>" />

                    <div id="mobCategoryPicker">
                        <label><?php echo JTEXT::_('COM_SOCAILPINBOARD_MENU_BOARD_CATEGORY'); ?> </label>
                        <select name="board_category_id" id="mobboard_category_id">
                            <?php foreach ($getCategories as $pincategory) {
                            ?>
                                <option  value=" <?php echo $pincategory->category_id; ?>"><?php echo $pincategory->category_name; ?></option>
                            <?php } ?>
                        </select>

                    </div>
                    <div id="mobcategorynameerror"></div>
                </li>
                <li>
                    <label class="radio"><?php echo JTEXT::_('COM_SOCAILPINBOARD_MENU_WHO_CAN_PIN'); ?></label>
                    <div class="Right" >
                        <div style="display:none; border-top: 0;"></div>
                        <ul class="pinability">
                            <li>
                                <label>
                                    <input type="radio" name="board_access" value="0" checked="checked" onclick="mobcontributersoff();" />
                                    <span><?php echo JTEXT::_('COM_SOCAILPINBOARD_MENU_JUST_ME'); ?></span> </label>
                            </li>
                            <li class="last-child">
                                <label>
                                    <input type="radio" name="board_access" value="1" onclick="mobcontributers();"/>
                                    <span> <?php echo JTEXT::_('COM_SOCAILPINBOARD_MENU_ME_PLUS_CONTRIBUTERS'); ?> </span> </label>
                            </li>
                        </ul>
                        <div style="clear:  both;"></div>
                        <div id="mobcontributer" style="display: none;  float: right; margin: 10px 0 0; overflow: hidden;">
                            <input type="text" name="contributers_name_addboard" id="mobcontributers_name_addboard" autocomplete="off" style="color: #999; margin-bottom:0px; float: left ; width: 280px;" value="<?php echo JTEXT::_('COM_SOCAILPINBOARD_MENU_NAME_OR_EMAIL_ADDRESS'); ?>" onkeyup="mobshowResult(this.value);" onFocus="onFocusEvent(this,'<?php echo JTEXT::_('COM_SOCAILPINBOARD_MENU_NAME_OR_EMAIL_ADDRESS'); ?>');" onBlur="onBlurEvent(this,'<?php echo JTEXT::_('COM_SOCAILPINBOARD_MENU_NAME_OR_EMAIL_ADDRESS'); ?>');"   />
                            <input type="button" name="add_board_contributers" id="mobadd_board_contributers" class="add_btn" onclick="mobuserContributers();" value="<?php echo JTEXT::_('COM_SOCAILPINBOARD_MENU_ADD'); ?>"  style="float:right; margin: 0px 0 0 10px; padding: 5px 23px;"/>
                        </div>
                        <div class="clear"></div>
                        <div id="mobSearchVal">
                            <ul id="mobmenuSearchVal" class="static-form" style="display:none;">
                            </ul>
                        </div>
                        <div id="mobloadingImage" style="display:none;"> <img src="<?php echo JURI::base() . '/components/com_socialpinboard/images/loading.gif' ?>" width="20" height="20" alt="" /> </div>
                        <div id="mobcontributer_name"> </div>
                    </div>
                </li>
            </ul>
            <div >
                <input type="submit" onclick="return mobvalidateBoard();" name="btnBoard" id="btnBoard"  class="create_board" value="<?php echo JTEXT::_('COM_SOCAILPINBOARD_MENU_CREATE_BOARD'); ?>" />
                <input type="reset" onclick="return mobcancelBoard();" name="btnBoard" id="btncancelBoard"  class="create_board" value="<?php echo JTEXT::_('COM_SOCIALPINBOARD_CANCEL'); ?>" />
                <input type="hidden" name="id_contributers" id="mobid_contributers" value="" />
            </div>
            <div class="CreateBoardStatus error"></div>
        </form>
    </div>
</div>
<!-- End to Create the Board -->
<!-- Start to Upload the pin-->
<div id="mobUploadPin" style="display:none;">
    <div  id="mobupload_pin_pic">
        <div class="header lg">
            <h2><?php echo JTEXT::_('COM_SOCAILPINBOARD_MENU_UPLOAD_A_PIN'); ?></h2>
            <div id="mobfile_ext_error" style="display: none"></div>
        </div>
        <div class="PinTop">
            <form action="" id="mobimageform" method="POST" enctype="multipart/form-data">
                <div id="mobme" class="styleall" style=" cursor:pointer;"><span> <?php echo JText::_('COM_SOCAILPINBOARD_MENU_UPLOAD_IMAGE'); ?></span></div>
                <span id="mobmestatus" ></span>
                <div id="mobupload_image_popup" style="display:none;">
                    <div id="mobupload_pin" style="display:none;">
                        <div class="ImagePicker" id="mobpricetags" > <img class="Images pin" id="mobupload_img_popup" name="upload_img_popup"  alt="change image"  /> </div>
                        <ul class="img_upload">
                            <li>
                                <select class="customStyleSelectBox" name="upload_board" id="mobupload_board"  onchange="mobchangeselected(this)">
                                    <?php
                                    $boardid = 0;
                                    $i = 0;
                                    foreach ($getBoardname as $board) {
                                        if ($i == 0) {
                                            $boardid = $board->board_id;
                                            $i = 1;
                                        }
                                    ?>
                                        <option value="<?php echo $board->board_id; ?>"> <?php echo $board->board_name; ?></option>
                                    <?php } ?>
                                </select>
                                <input type="hidden" name="board_selection" id="mobboard_selection" value="<?php echo $boardid; ?>"/>
                                <input type="hidden" name="image_id" id="mobimage_id" value=""/>
                                <div class="uploadtext">
                                    <input style="color:#C9C8C8" type="text" name="uploadboardtxt" id="mobuploadboardtxt" onFocus="onFocusMenu(this,'<?php echo JTEXT::_('COM_SOCAILPINBOARD_MENU_ENTER_NEW_BOARD_NAME'); ?>');" onBlur="onBlurEvent(this,'<?php echo JTEXT::_('COM_SOCAILPINBOARD_MENU_ENTER_NEW_BOARD_NAME'); ?>');" value="<?php echo JTEXT::_('COM_SOCAILPINBOARD_MENU_ENTER_NEW_BOARD_NAME'); ?>"/>
                                </div>
                                <div class="createspecial"><a href="javascript:void(0);" class="creat_bttn" onclick="mobaddnewmenuboard(<?php echo $user->id; ?>)" style="display:block">
                                        <?php echo JText::_('COM_SOCAILPINBOARD_MENU_CREATE'); ?>
                                    </a></div>
                                <div id="mobboardError" style="clear: both; margin: 0 0 0 150px; "></div>
                            </li>
                            <li>
                                <div class="InputArea">
                                    <ul class="Form FancyForm">
                                        <li class="noMarginBottom ">
                                            <textarea  class="DescriptionTextarea" name="pin_desc" style="color:#C9C8C8; resize: none;" id="mobpin_desc" row="3" onFocus="onFocusMenu(this,'<?php echo JTEXT::_('COM_SOCAILPINBOARD_MENU_PLEASE_ENTER_DESCRITION'); ?>');" onBlur="onBlurEvent(this,'<?php echo JTEXT::_('COM_SOCAILPINBOARD_MENU_PLEASE_ENTER_DESCRITION'); ?>');" ><?php echo JTEXT::_('COM_SOCAILPINBOARD_MENU_PLEASE_ENTER_DESCRITION'); ?></textarea>
                                        </li>
                                    </ul>
                                </div>
                                <div id="mobpinError"></div>
                            </li>
                            <li>
                                <input type="submit" onclick ="return mobvalidatePin();" value="<?php echo JText::_('COM_SOCAILPINBOARD_MENU_PIN_IT'); ?>" name="uploadPin" id="mobuploadPin"/>
                                <input type="reset" onclick="return mobcancelPin();" name="cancelUpload" id="cancelUpload"  class="create_board" value="<?php echo JTEXT::_('COM_SOCIALPINBOARD_CANCEL'); ?>" />
                            </li>
                            <div class="clear"></div>
                        </ul>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End of Upload the Pin-->
<!--- Start Add pin-->
<div id="mobScrapePin" style="display:none;" >
    <div id="mobscrapepindiv">
        <div class="header lg">
            <h2><?php echo JTEXT::_('COM_SOCAILPINBOARD_MENU_ADD_A_PIN') ?></h2>
        </div>
        <div class="PinTop inputHolder scrapePin">
            <form method="post" action="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=boarddisplay'); ?>" name="frmUrl" id="mobfrmUrl">
                <ul class="Form FancyForm">
                    <li>
                        <input id="mobScrapePinInput" name="ScrapePinInput" type="text" value="" onblur="if (this.placeholder == '') {this.placeholder = '<?php echo JTEXT::_('COM_SOCIALPINBOARD_MENU_ENTER_THE_URL_TO_FIND_IMAGES'); ?>';}" onfocus="if (this.placeholder == '<?php echo JTEXT::_('COM_SOCIALPINBOARD_MENU_ENTER_THE_URL_TO_FIND_IMAGES'); ?>') {this.placeholder = '';this.style.color ='#000';}" placeholder="<?php echo JTEXT::_('COM_SOCIALPINBOARD_MENU_ENTER_THE_URL_TO_FIND_IMAGES'); ?>" autocomplete="off"/>
                        <a href="javascript:void(0);" id="mobScrapeButton" onclick="mobloadXMLDoc(); return false;" class="Button WhiteButton Button18 floatRight"><strong><?php echo JTEXT::_('COM_SOCIALPINBOARD_MENU_FIND_IMAGES'); ?></strong></a>
                        <a href="javascript:void(0);" style="display: none;" id="mobScrapeButton1" class="Button WhiteButton Button18 floatRight"><strong><?php echo JTEXT::_('COM_SOCIALPINBOARD_MENU_FIND_IMAGES') . '...'; ?></strong></a>
                        <div style="clear:both;"></div>
                        <div class="example_link"><?php echo JText::_('COM_SOCAILPINBOARD_MENU_EXAMPLE_COM'); ?></div>
                        <div id="moburlImages" style="display:none;">
                            <div class="site_img">
                                <div class="ImagePicker">
                                    <div class="price"></div>
                                    <div class="Images pin" id="mobgetImg"> </div>
                                </div>
                                <div class="navigation"> <a type="button" onclick="return mobnextPrev('mobprev')" name="prev" id="mobprev">&nbsp;&#171; <?php echo JTEXT::_('COM_SOCAILPINBOARD_MENU_PREVIOUS') ?></a> <a type="button" onclick="return mobnextPrev('mobnext')" name="next" id="mobnext"><?php echo JTEXT::_('COM_SOCAILPINBOARD_MENU_NEXT') ?> &#187;</a> </div>
                            </div>
                            <input type="hidden" name="imgcnt" id="mobimgcnt" value="0"/>
                            <div id="mobgetimg_desc">
                                <select name="pin_board" id="mobpin_board">
                                    <?php foreach ($getBoardname as $boards) {
                                    ?>
                                            <option value="<?php echo $boards->board_id; ?>"><?php echo ucfirst($boards->board_name); ?></option>
                                    <?php } ?>
                                    </select>
                                    <div class="clearfix">
                                        <div class="uploadtext">
                                            <input style="color:#C9C8C8" type="text" name="uploadboardtxted" id="mobuploadboardtxted"onFocus="onFocusMenu(this,'<?php echo JTEXT::_('COM_SOCAILPINBOARD_MENU_ENTER_NEW_BOARD_NAME') ?>');" onBlur="onBlurEvent(this,'<?php echo JTEXT::_('COM_SOCAILPINBOARD_MENU_ENTER_NEW_BOARD_NAME') ?>');" value="<?php echo JTEXT::_('COM_SOCAILPINBOARD_MENU_ENTER_NEW_BOARD_NAME') ?>" />
                                        </div>
                                        <div class="special" style=""><a href="javascript:void(0);" class="creat_bttn" id="mobboard_creat_btn" onclick="mobaddnewmenupin(<?php echo $user->id; ?>)" style="display:block">
                                                <?php echo JTEXT::_('COM_SOCAILPINBOARD_MENU_CREATE') ?></a></div>
                                        <div id="mobboardErrorpin" class="clearfix"></div>
                                    </div>
                                    <div class="clear"></div>
                                    <div class="clearfix" id="add_pin_desc">
                                        <div class="InputArea">
                                            <ul class="Form FancyForm">
                                                <li class="noMarginBottom ">
                                                    <textarea class="DescriptionTextarea" value="<?php echo JTEXT::_('COM_SOCAILPINBOARD_MENU_PLEASE_ENTER_DESCRITION') ?>" style="color:grey; resize: none;" id="mobtxtpin" rows="2" name="txtpin"  onFocus="onFocusMenu(this,'<?php echo JTEXT::_('COM_SOCAILPINBOARD_MENU_PLEASE_ENTER_DESCRITION'); ?>');" onBlur="onBlurEvent(this,'<?php echo JTEXT::_('COM_SOCAILPINBOARD_MENU_PLEASE_ENTER_DESCRITION'); ?>');" ><?php echo JTEXT::_('COM_SOCAILPINBOARD_MENU_PLEASE_ENTER_DESCRITION'); ?></textarea>
                                                    <span class="fff"></span> </li>
                                            </ul>
                                        </div>
                                        <div id="mobaddpinerror"></div>
                                    </div>
                                    <div id="mobaddError"></div>
                                    <input type="hidden" name="srcimg" id="mobsrcimg" value=""/>
                                    <div id="mobdownloadImages"></div>
                                    <input type="hidden" name="create_dates" id="mobcreate_dates" value="<?php echo date("Y-m-d H:i:s"); ?>"/>
                                    <input type="submit"  name="add_pin" onclick="return mobvalidateImageUrl();" style="display: block" id="mobadd_pin" value="<?php echo JTEXT::_('COM_SOCAILPINBOARD_MENU_PIN_IT'); ?>"/>
                                    <input type="reset" onclick="return mobcanceladdPin();" name="cancelAddpin" id="cancelAddpin"  class="create_board" value="<?php echo JTEXT::_('COM_SOCIALPINBOARD_CANCEL'); ?>" />
                                    <strong class="edit-board-btn pinning" id="mobadd_pining" style="display:none;"><?php echo JTEXT::_('COM_SOCAILPINBOARD_MENU_PINNING'); ?></strong> </div>
                            </div>
                        </li>
                    </ul>
                </form>
            </div>
        </div>
    </div>
    <!--- End Add pin-->
    <script type="text/javascript">
        function mobcancelBoard(){
            document.getElementById('createboard').style.display = 'block';
            document.getElementById('mobCreateBoard').style.display = 'none';
            document.getElementById('uploadpindiv').style.display = 'block';
            document.getElementById('addpindiv').style.display = 'block';
        }
        function mobcancelPin(){
            document.getElementById('mobUploadPin').style.display = 'none';
            document.getElementById('uploadpindiv').style.display = 'block';
            document.getElementById('addpindiv').style.display = 'block';
            document.getElementById('createboard').style.display = 'block';
        }
        function mobcanceladdPin(){
            document.getElementById('mobScrapePin').style.display = 'none';
            document.getElementById('uploadpindiv').style.display = 'block';
            document.getElementById('addpindiv').style.display = 'block';
            document.getElementById('createboard').style.display = 'block';
        }
        function addapin(){
            document.getElementById('addpindiv').style.display = 'none';
            document.getElementById('uploadpindiv').style.display = 'none';
            document.getElementById('createboard').style.display = 'none';
            document.getElementById('mobCreateBoard').style.display = 'none';
            document.getElementById('mobUploadPin').style.display = 'none';
            document.getElementById('mobScrapePin').style.display = 'block';

        }
        function uploadapin(){
            document.getElementById('addpindiv').style.display = 'none';
            document.getElementById('uploadpindiv').style.display = 'none';
            document.getElementById('createboard').style.display = 'none';
            document.getElementById('mobCreateBoard').style.display = 'none';
            document.getElementById('mobUploadPin').style.display = 'block';
            document.getElementById('mobScrapePin').style.display = 'none';
        }
        function createboard(){
//            document.getElementById('addpindiv').style.display = 'none';
//            document.getElementById('uploadpindiv').style.display = 'none';
            document.getElementById('createboard').style.display = 'none';
            document.getElementById('mobUploadPin').style.display = 'none';
            document.getElementById('mobCreateBoard').style.display = 'block';
            document.getElementById('mobScrapePin').style.display = 'none';
        }
        function mobvalidateBoard(){
            var boardtitle = document.getElementById('mobboard_name').value;
            var categorytitle = document.getElementById('mobboard_category_id').value;
            document.getElementById('mobcategorynameerror').innerHTML='';
            document.getElementById('mobboardnameerror').innerHTML='';
            if(categorytitle == '0' ||categorytitle == '' ){
                document.getElementById('mobcategorynameerror').innerHTML='<label style="color:red; margin-left: 180px;"><?php echo JText::_('COM_SOCAILPINBOARD_MENU_PLEASE_SELECT_THE_CATEGORY'); ?></label>';
                document.getElementById("mobboard_name").focus();
                return false;
            }

            if(boardtitle == ''){
                document.getElementById('mobboardnameerror').innerHTML='<label style="color: red; font-size:12px; width:auto; font-weight:bold; float: left;"><?php echo JText::_('COM_SOCAILPINBOARD_MENU_PLEASE_ENTER_THE_BOARD_NAME'); ?></label>';
                return false;
            }
        }
        function mobchangeselected(obj)
        {

            var strUser = obj.options[obj.selectedIndex].value;

            document.getElementById('mobboard_selection').value = strUser;
        }
        function mobcontributers()
        {
            document.getElementById('mobcontributer').style.display="block";
        }
        function mobcontributersoff()
        {
            document.getElementById('mobcontributer').style.display="none";
        }
        function mobshowResult(str)
        {

            if(str!= "")
            {
                str = jQuery.trim(str);

                if (window.XMLHttpRequest)
                {// code for IE7+, Firefox, Chrome, Opera, Safari
                    xmlhttp = new XMLHttpRequest();

                }
                else
                {// code for IE6, IE5
                    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");

                }
                xmlhttp.onreadystatechange=function()
                {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200)
                    {

                        if(xmlhttp.responseText.length == 0)
                        {  document.getElementById("mobmenuSearchVal").innerHTML="";
                            document.getElementById("mobmenuSearchVal").style.display="none";
                            document.getElementById("mobloadingImage").style.display="none";

                        }
                        else{
                            document.getElementById("mobloadingImage").style.display="none";
                            document.getElementById("mobmenuSearchVal").style.display="block";


                            var searchValue=xmlhttp.responseText;

                            //alert(searchValue.length);
                            var b='';
                            if(searchValue !='""'){

                                var obj = jQuery.parseJSON(searchValue);
                                if(searchValue==',')
                                {
                                    document.getElementById("mobmenuSearchVal").style.display='none';
                                    return false;

                                }
                                var nameArray = obj.username;
                                var idArray=obj.userid


                                for(var i=0;i< nameArray.length;i++)
                                {
                                    b+='<li id="mobstatic_li" style="padding-left:5px;" onclick="mobuserSelectBoard(\''+nameArray[i]+'\',\''+idArray[i]+'\')" >'+nameArray[i]+'</li>';
                                }
                                document.getElementById("mobcontributer_name").innerHTML = '';
                                document.getElementById("mobmenuSearchVal").innerHTML=b;
                            }
                            else{
                                document.getElementById("mobmenuSearchVal").innerHTML = '';
                                document.getElementById("mobmenuSearchVal").innerHTML ='No users found';
                            }

                        }

                    }
                }
                var url = "?option=com_socialpinboard&view=ajaxcontrol&tmpl=component&task=getcontributers&user="+str;
                xmlhttp.open("GET",url,true);
                xmlhttp.send();
            }
            else{
                document.getElementById("mobmenuSearchVal").style.display= "none";
            }
        }

        function mobuserSelectBoard(userCon)
        {
            document.getElementById('mobcontributers_name_addboard').value=userCon;

        }

        function mobvalidatePin(){


            var boardName = document.getElementById('mobupload_board').value;
            var pinDesc = document.getElementById('mobpin_desc').value;
            if(boardName == '' || boardName=='0'){
                document.getElementById('mobboardError').innerHTML='<label id="login_error_msg" style="clear: both; margin: 0 0 0 1px; "><?php echo JText::_('COM_SOCAILPINBOARD_MENU_PLEASE_ENTER_THE_BOARD_NAME'); ?></label>';
                return false;
            }

            if(pinDesc == '' || pinDesc == '<?php echo JTEXT::_('COM_SOCAILPINBOARD_MENU_PLEASE_ENTER_DESCRITION'); ?>'){
                document.getElementById('mobpinError').innerHTML='<label id="login_error_msg" style="margin: 10px 0 0 -128px;display: block;clear: both;"><?php echo JText::_('COM_SOCAILPINBOARD_MENU_PLEASE_ENTER_DESCRITION'); ?></label>';
                return false;
            }
            document.getElementById('mobuploadPin').style.display='none';
            document.getElementById('mobaddpinloader').style.display='block';

        }
        var scr = jQuery.noConflict();
        scr(function(){
            var btnUpload=scr('#mobme');
            var mestatus=scr('#mobmestatus');
            var files=scr('#mobfiles');//alert(files);
            new AjaxUpload(btnUpload, {
                action: '<?php echo JURI::base(); ?>/modules/mod_socialpinboard_menu/saveimagefromupload.php',
                name: 'uploadfile',
                onSubmit: function(file, ext){
                    if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){
                        // extension is not allowed
                        mestatus.text('Only JPG, PNG or GIF files are allowed');
                        return false;
                    }
                    mestatus.html('');
                },
                onComplete: function(file, response){
                    //On completion clear the status
                    //mestatus.text('Image Uploaded Sucessfully!');
                    //On completion clear the status
                    files.html('');
                    //Add uploaded file to list
                    if(response!=""){
                        document.getElementById('mobupload_img_popup').src = '<?php echo JURI::base(); ?>/modules/mod_socialpinboard_menu//images/socialpinboard/temp/'+response;
                        document.getElementById('mobupload_image_popup').style.display="block";
                        document.getElementById('mobupload_pin').style.display="block";
                        document.getElementById('mobpricetags').style.display="block";
                        document.getElementById('mobimage_id').value = response;
                    }
                }
            });

        });

        function mobvalidateImageUrl()
        {
            var desc = document.getElementById('mobtxtpin').value;
            var str = document.getElementById('mobsrcimg').value;
            if(str=='')
            {
                document.getElementById('mobaddpinerror').innerHTML='<label id="login_error_msg" style="margin: 10px 0 0 22px;display: block;clear: both;"><?php echo JTEXT::_('COM_SOCIALPINBOARD_MENU_IMAGE_DOESNOT_EXIST'); ?></label>';
                return false;
            }


            if(desc == ''|| desc=='<?php echo JText::_('COM_SOCAILPINBOARD_MENU_PLEASE_ENTER_DESCRITION'); ?>'){
                document.getElementById('mobaddpinerror').innerHTML='<label id="login_error_msg" style="padding: 0px;display: block;clear: both;position: relative;margin: 0;text-align: left;"><?php echo JTEXT::_('COM_SOCIALPINBOARD_MENU_PLEASE_ENTER_THE_DESCRIPTION'); ?></label>';
                return false;
            }
            document.getElementById('mobadd_pin').style.display="none";
            document.getElementById('mobadd_pining').style.display="block";
        }


        function mobloadXMLDoc()
        {
            var url;
            var content;
            var filename = document.getElementById('mobScrapePinInput').value;//alert(filename);
            if( filename.indexOf('http://') !== 0  && filename.indexOf('https://') !== 0){
                filename = 'http://' + filename;
            }

            document.getElementById('mobimgcnt').value=0;
            if(filename == ''){
                alert('<?php echo JTEXT::_('COM_SOCIALPINBOARD_MENU_PLEASE_ENTER_VALID_URL'); ?>');
                return false;
            }else if (!isUrl(filename))
            {
                alert("<?php echo JTEXT::_('COM_SOCIALPINBOARD_MENU_PLEASE_ENTER_VALID_URL'); ?>");
                return false;
            }
            document.getElementById('mobScrapeButton').style.display="none";
            document.getElementById('mobScrapeButton1').style.display="block";


            var matches = filename.match(/watch\?v=([a-zA-Z0-9\-_]+)/); //check for the youtube
            var valid_extensions = /(\.jpg|\.jpeg|\.png|\.gif)$/i;   //regular expression for images
            if(valid_extensions.test(filename)) //check the extensions available in the url
            {

                content = '<img id="mobimgsrc0" src="'+filename+'" />,'; // if available bind the image url with the src tag
                bindimage(content);

            }
            else if(matches) // if the url is the youtube
            {
                var regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/; //regular expression for youtube
                var match = filename.match(regExp);  // get the array value of the url
                if (match&&match[7].length==11){
                    youtubeId =  match[7];
                    content = '<input type="hidden" name="type" id="mobtype" value="youtube"/>';
                    content += '<img id="mobyoutubeimg" src="http://img.youtube.com/vi/' +youtubeId+'/0.jpg" width="198" height="150"/>';
                    content += '<input type="hidden" name="youtube_image" id="mobyoutube_image" value="http://img.youtube.com/vi/' +youtubeId+ '/0.jpg"/>';
                    bindimage(content);
                }

            }
            else if (filename.indexOf('vimeo.com') > -1) {  //Checks for the vimeo condition
                id = filename.match(/http:\/\/(?:www.)?(\w*).com\/(\d*)/)[2];
                scr.ajax({
                    url: 'http://vimeo.com/api/v2/video/' + id + '.json',
                    dataType: 'jsonp',
                    success: function(data) {

                        content = '<input type="hidden" name="type" id="mobtype" value="vimeo"/>';
                        content += '<img id="mobyoutubeimg" src="' +data[0].thumbnail_large+'" width="198" height="150"/>';
                        content += '<input type="hidden" name="youtube_image" id="mobyoutube_image" value="' +data[0].thumbnail_large+ '"/>';
                        bindimage(content);
                    }
                });
            }
            else
            {
                url = filename;

                content = mobajaxinclude(url);

            }

        }


        function mobajaxinclude(url)
        {
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
                    bindimage(xmlhttp.responseText);
                }

            }
            pageurl = "<?php echo JURI::base() . 'components/com_socialpinboard/javascript/saveimagethroughurl.php?url='; ?>"+url;
        xmlhttp.open("GET",pageurl,true);

        xmlhttp.send();
    }

    function bindimage(content)
    {
        var Images = content.split(',');
        document.getElementById("mobgetImg").innerHTML='';
        for(var i=0;i<Images.length;i++){
            document.getElementById("mobgetImg").innerHTML += Images[i];
            if(document.getElementById("mobyoutubeimg"))
                document.getElementById("mobsrcimg").value = document.getElementById("mobyoutubeimg").src;
            document.getElementById('moburlImages').style.display='block';
            document.getElementById('mobScrapeButton1').style.display = "none";
            document.getElementById('mobScrapeButton').style.display = "block";
            if(i == 0){

                if(document.getElementById("mobimgsrc"+i))
                    document.getElementById("mobimgsrc"+i).style.display='block';
                document.getElementById('moburlImages').style.display='block';
                if(document.getElementById("mobimgsrc"+i))
                    document.getElementById('mobsrcimg').value = document.getElementById("mobimgsrc"+i).src;
            }
            else
            {
                document.getElementById("mobimgsrc"+i).style.display='none';

            }
        }
    }
    //validate url
    function isUrl(s) {
        var regexp = /^(http|https):\/\/[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(([0-9]{1,5})?\/.*)?$/;
        return regexp.test(s);
    }
</script>
<script type="text/javascript">
    function mobnextPrev(type){
        var imgcount = document.getElementById('mobimgcnt').value;
        var orig = imgcount;
        if(type == 'mobprev'){
            imgcount--;
        }
        else if(type == 'mobnext'){
            imgcount++;
        }

        if( document.getElementById("mobimgsrc"+imgcount))
        {
            document.getElementById("mobimgsrc"+orig).style.display='none';
            document.getElementById('mobimgcnt').value = imgcount;
            document.getElementById("mobimgsrc"+imgcount).style.display='block';
            document.getElementById('mobsrcimg').value = document.getElementById("mobimgsrc"+imgcount).src;
        }
    }
</script>

