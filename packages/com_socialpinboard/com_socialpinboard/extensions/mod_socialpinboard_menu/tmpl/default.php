<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
defined('_JEXEC') or die('Restricted access');
$user = JFactory::getUser();
$id = $user->id;
$show_request;
$app = JFactory::getApplication();
$document = JFactory::getDocument();
$document->addStyleSheet('modules/mod_socialpinboard_menu/css/socialpinboard_menu.css');
$document->addScript('https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js');
//$document->addScript('components/com_socialpinboard/javascript/jquery.js');
//$document->addScript('components/com_socialpinboard/javascript/jquery.styleSelect.js');
$document->addScript('modules/mod_socialpinboard_menu/js/ajaxupload.js');
//$document->addStyleSheet('components/com_socialpinboard/css/main.css');
$document->addScript('modules/mod_socialpinboard_menu/js/loginvalidation.js');
?>
<script type="text/javascript">
//    var scr = jQuery.noConflict();
//	scr(document).ready(function() {
//		scr(".contricutor_select_upload").styleSelect({styleClass: "selectDarkMenu"});
//		scr(".contricutor_select_addpin").styleSelect({styleClass: "selectDarkMenuaddpin"});
//		scr(".contricutor_select_repin").styleSelect({styleClass: "selectDarkMenuaddpin"});
//	});
</script>
<div id="social_menu">
    <ul id="pin-header-menu">
        <?php if ($id != '0') {
 ?>

            <li>
                <a href="#" class="" onclick="Modal.show('Add'); return false">
<?php echo JTEXT::_('MOD_SOCAILPINBOARD_MENU_ADD_PLUS') ?>
                <span class=""></span>
            </a>
        </li>

<?php } ?>
        <li style="cursor: pointer;"> <a href="#" class="nav about "><?php echo JTEXT::_('MOD_SOCAILPINBOARD_MENU_ABOUT') ?><span></span></a>
            <ul>
                <?php
                $field = 'title,link';
                if (version_compare(JVERSION, '1.7.0', 'ge')) {
                    $version = '1.7';
                } elseif (version_compare(JVERSION, '1.6.0', 'ge')) {
                    $version = '1.6';
                } else {
                    $version = '1.5';
                    $field = 'name,link';
                }


                foreach ($top_menu as $value) {
                    if ($version == '1.5') {
                        $menu_name = $value->name;
                    } else {
                        $menu_name = $value->title;
                    }
                    $target1 = $value->browserNav;

                    if ($target1 == 0) {
                        $target = '';
                    }
                    if ($target1 == 1) {
                        $target = 'target="_blank"';
                    }
                    if ($target1 == 2) {
                        $wind = "'targetWindow'";
                        $param = "'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,'";
                        $target = 'onclick="window.open(this.href,' . $wind . ',' . $param . ');return false;"';
                    }

                    echo '<li  class="hover-me" > <a href="' . $value->link . '" ' . $target . '  >' . $menu_name . '</a> </li>';
                }
                ?>
            </ul>
        </li>
        <?php
                if (!$user->id && $show_request == 0) {
        ?>
                    <li class="customer_grid"><a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=register'); ?>" class="nav"><?php echo JText::_('MOD_SOCIALPINBOARD_REGISTER'); ?></a></li>
        <?php
                } else {
                    if (!$user->id) {
        ?>

                        <li class="customer_grid"><a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=inviterequest'); ?>" class="nav"><?php echo JText::_('MOD_SOCAILPINBOARD_INVITE'); ?></a></li>
<?php
                    }
                }
?>

                <li class="customer_grid" style="cursor: pointer;">
<?php if ($id != 0) { ?>
                        <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=boarddisplay') ?>" class="nav LoginNav">
<?php
                    if ($userLogin[0]->user_image == '') {
?>
                        <img src="<?php echo JURI::base() ?>/components/com_socialpinboard/images/no_user.jpg" width="30" height="30"/>
<?php
                    } else {
?>
                        <img src="<?php echo JURI::base() ?>/images/socialpinboard/avatars/<?php echo $userLogin[0]->user_image; ?>" width="30" height="30"/>
<?php
                    }
?>
                    <h6><?php echo ucfirst($userLogin[0]->first_name); ?></h6>
                </a>
                <ul class="pin-login-drop">
                    <li><a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=invitefriends') ?>"><?php echo JTEXT::_('MOD_SOCAILPINBOARD_MENU_INVITE_FRIENDS') ?></a></li>
                    <li><a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=invitefriends&task=facebook') ?>"><?php echo JTEXT::_('MOD_SOCAILPINBOARD_MENU_FIND_FRIENDS') ?></a></li>
                    <li class="beforeDivider"><a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=boarddisplay') ?>"><?php echo JTEXT::_('MOD_SOCAILPINBOARD_MENU_BOARDS') ?></a></li>
                    <li class="divider"><a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=pindisplay') ?>"><?php echo JTEXT::_('MOD_SOCAILPINBOARD_MENU_PINS') ?></a></li>
                    <li><a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=likes') ?>"><?php echo JTEXT::_('MOD_SOCAILPINBOARD_MENU_LIKES') ?></a></li>
                    <li><a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=profile') ?>"><?php echo JTEXT::_('MOD_SOCAILPINBOARD_MENU_SETTINGS') ?></a></li>
                    <li><a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=people&task=logout') ?>"><?php echo JTEXT::_('MOD_SOCAILPINBOARD_MENU_LOGOUT') ?></a></li>
                </ul>
<?php
                } else {

                    $uri = JFactory::getURI();
                    $return = $uri->toString();
                    $url = "index.php?option=com_socialpinboard&view=people";
                    $url .= '&returnURL=' . base64_encode($return);
                    $linkurl = JRoute::_($url);
?>
                    <a href="<?php echo $linkurl; ?>" class="nav"><?php echo JTEXT::_('MOD_SOCAILPINBOARD_MENU_LOGIN') ?></a>
<?php } ?>
            </li>

        </ul>
        <ul id="solcial_links">
            <li><a href="<?php echo $facebook_url; ?>" target="_blank"><img src="<?php echo JURI::base();
                ; ?>/templates/socialpinboard/images/facebook-btn.png" title="Facebook" alt="Facebook" /></a></li>
            <li><a href="<?php echo $twitter_url; ?>" target="_blank"><img src="<?php echo JURI::base();
                ; ?>/templates/socialpinboard/images/twitter-btn.png" title="Twitter" alt="Twitter" /></a></li>
        </ul>
    <?php
                if ($id != '') {
    ?>

                    <div id="Add" class="ModalContainer">
                        <div class="modal wide PaddingLess">
                            <div class="header lg"> <a href="#" class="close" onclick="Modal.close('Add'); return false"><strong><?php echo JTEXT::_('MOD_SOCAILPINBOARD_MENU_CLOSE') ?></strong><span></span></a>
                                <h2><?php echo JTEXT::_('MOD_SOCAILPINBOARD_MENU_ADD') ?></h2>
                            </div>
                            <p id="PinIt"><?php echo JTEXT::_('MOD_SOCAILPINBOARD_MENU_PIN_IMAGES_FROM_THE_WEBSITE'); ?> <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=pinit'); ?>" onclick="load_url(); return false">&ldquo;<?php echo JTEXT::_('MOD_SOCAILPINBOARD_MENU_PIN_IT') ?>&rdquo; <?php echo JTEXT::_('MOD_SOCAILPINBOARD_MENU_BUTTON') ?></a>.</p>
                            <div id="OpenLinks"> <a href="#" id="OpenScrapePin" class="cell" onclick="addClear();AddDialog.close('Add', 'ScrapePin'); return false">
                                    <div class="menuAddUrl" id="scrape"></div>
                                    <span><?php echo JTEXT::_('MOD_SOCAILPINBOARD_MENU_ADD_A_PIN') ?></span> </a>
                                <script type="text/javascript">

                                    function addClear()
                                    {
                                        document.getElementById('getImg').innerHTML = '';
                                        document.getElementById('txtpin').style.color="#C9C8C8";
                                        document.getElementById('txtpin').value = "Please Enter Description";
                                        document.getElementById('srcimg').value='';
                                    }
                                </script>
                                <a id="OpenUploadPin" class="cell" onclick="AddDialog.close('Add', 'UploadPin'); return false">
                                    <div class="imageUpload" id="upload"></div>
                                    <span><?php echo JTEXT::_('MOD_SOCAILPINBOARD_MENU_UPLOAD_A_PIN') ?></span> </a> <a id="OpenCreateBoard" class="cell" onclick="AddDialog.close('Add', 'CreateBoard'); CreateBoardDialog.reset(); return false">
                                    <div class="menuCreateBoard" id="board"></div>
                                    <span><?php echo JTEXT::_('MOD_SOCAILPINBOARD_MENU_CREATE_A_BOARD') ?></span> </a> </div>
                        </div>
                        <div class="overlay"></div>
                    </div>
                    <div id="ScrapePin" class="ModalContainer">
                        <div class="modal wide">
                            <div class="header lg"> <a href="#" class="close" onclick="return addPinclose();" ><strong>Close</strong><span></span></a>
                                <h2><?php echo JTEXT::_('MOD_SOCAILPINBOARD_MENU_ADD_A_PIN') ?></h2>
                            </div>
                            <div class="PinTop inputHolder scrapePin">
                                <form method="post" action="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=boarddisplay'); ?>" name="frmUrl" id="frmUrl">
                                    <ul class="Form FancyForm">
                                        <li>
                                            <input id="ScrapePinInput" name="ScrapePinInput" type="text" value="" onblur="if (this.placeholder == '') {this.placeholder = '<?php echo JTEXT::_('MOD_SOCIALPINBOARD_MENU_ENTER_THE_URL_TO_FIND_IMAGES'); ?>';}" onfocus="if (this.placeholder == '<?php echo JTEXT::_('MOD_SOCIALPINBOARD_MENU_ENTER_THE_URL_TO_FIND_IMAGES'); ?>') {this.placeholder = '';this.style.color ='#000';}" placeholder="<?php echo JTEXT::_('MOD_SOCIALPINBOARD_MENU_ENTER_THE_URL_TO_FIND_IMAGES'); ?>" autocomplete="off"/>
                                            <a href="javascript:void(0);" id="ScrapeButton" onclick="loadXMLDoc(); return false;" class="Button WhiteButton Button18 floatRight"><strong><?php echo JTEXT::_('MOD_SOCIALPINBOARD_MENU_FIND_IMAGES'); ?></strong></a>
                                            <a href="javascript:void(0);" style="display: none;" id="ScrapeButton1" class="Button WhiteButton Button18 floatRight"><strong><?php echo JTEXT::_('MOD_SOCIALPINBOARD_MENU_FIND_IMAGES') . '...'; ?></strong></a>
                                            <div style="clear:both;"></div>
                                            <div class="example_link"><?php echo JText::_('MOD_SOCAILPINBOARD_MENU_EXAMPLE_COM'); ?></div>
                                            <div id="urlImages" style="display:none;">
                                                <div class="site_img">
                                                    <div class="ImagePicker">
                                                        <div class="price"></div>
                                                        <div class="Images pin" id="getImg"> </div>
                                                    </div>
                                                    <div class="navigation"> <a type="button" onclick="return nextPrev('prev')" name="prev" id="prev">&#171; <?php echo JTEXT::_('MOD_SOCAILPINBOARD_MENU_PREVIOUS') ?></a> <a type="button" onclick="return nextPrev('next')" name="next" id="next"><?php echo JTEXT::_('MOD_SOCAILPINBOARD_MENU_NEXT') ?> &#187;</a> </div>
                                                </div>
                                                <input type="hidden" name="imgcnt" id="imgcnt" value="0"/>
                                                <div id="getimg_desc">
                                                    <select name="pin_board" id="pin_board" class="customStyleSelectBox">
                                        <?php foreach ($resultboards as $boards) {
 ?>
                                            <option value="<?php echo $boards->board_id; ?>" ><?php echo ucfirst($boards->board_name); ?></option>
<?php } ?>
                                    </select>
                                                    <div class="uploadtext">
                                            <input style="color:#C9C8C8" type="text" name="uploadboardtxted" id="uploadboardtxted"onFocus="onFocusMenu(this,'<?php echo JTEXT::_('MOD_SOCAILPINBOARD_MENU_ENTER_NEW_BOARD_NAME') ?>');" onBlur="onBlurEvent(this,'<?php echo JTEXT::_('MOD_SOCAILPINBOARD_MENU_ENTER_NEW_BOARD_NAME') ?>');" value="<?php echo JTEXT::_('MOD_SOCAILPINBOARD_MENU_ENTER_NEW_BOARD_NAME') ?>" />
                                        </div>
                                    <div class="clearfix">
                                        
                                        <div class="special" style=""><a href="javascript:void(0);" class="creat_bttn" id="board_creat_btn" onclick="addnewmenupin(<?php echo $user->id; ?>)" style="display:block">
                                                Create</a></div>
                                        <div id="boardErrorpin" class="clearfix"></div>
                                    </div>
                                    <div class="clear"></div>
                                    <div class="clearfix" id="add_pin_desc">
                                        <div class="InputArea">
                                            <ul class="Form FancyForm">
                                                <li class="noMarginBottom ">
                                                    <textarea class="DescriptionTextarea" value="<?php echo JTEXT::_('MOD_SOCAILPINBOARD_MENU_PLEASE_ENTER_DESCRITION') ?>" style="color:grey; resize: none;" id="txtpin" rows="2" name="txtpin"  onFocus="onFocusMenu(this,'<?php echo JTEXT::_('MOD_SOCAILPINBOARD_MENU_PLEASE_ENTER_DESCRITION'); ?>');" onBlur="onBlurEvent(this,'<?php echo JTEXT::_('MOD_SOCAILPINBOARD_MENU_PLEASE_ENTER_DESCRITION'); ?>');" ><?php echo JTEXT::_('MOD_SOCAILPINBOARD_MENU_PLEASE_ENTER_DESCRITION'); ?></textarea>
                                                    <span class="fff"></span> </li>
                                            </ul>
                                        </div>
                                        <div id="addpinerror"></div>
                                    </div>
                                    <div id="addError"></div>
                                    <input type="hidden" name="srcimg" id="srcimg" value=""/>
                                    <div id="downloadImages"></div>
                                    <input type="hidden" name="create_dates" id="create_dates" value="<?php echo date("Y-m-d H:i:s"); ?>"/>
                                    <input type="submit"  name="add_pin" onclick="return validateImageUrl();" style="display: block" id="add_pin" value="<?php echo JTEXT::_('MOD_SOCAILPINBOARD_MENU_PIN_IT'); ?>"/>

                                    <strong class="edit-board-btn pinning" id="add_pining" style="display:none;"><?php echo JTEXT::_('MOD_SOCAILPINBOARD_MENU_PINNING'); ?></strong> </div>
                            </div>
                        </li>
                    </ul>
                </form>
            </div>
        </div>
        <div class="overlay"></div>
    </div>

    <!-- End of the Add pin-->
    <!-- Upload the pin-->
    <div id="UploadPin" class="ModalContainer">
        <div class="modal wide" id="upload_pin_pic">
            <div class="header lg"> <a href="#" class="close" onclick="javascript:document.getElementById('upload_image_popup').style.display = 'none';javascript: document.getElementById('pricetags').style.display='none';javascript:document.getElementById('boardError').innerHTML = '';AddDialog.childClose('Add','UploadPin'); return false"><strong><?php echo JTEXT::_('MOD_SOCAILPINBOARD_MENU_CLOSE'); ?></strong><span></span></a>
                <h2><?php echo JTEXT::_('MOD_SOCAILPINBOARD_MENU_UPLOAD_A_PIN'); ?></h2>
                <div id="file_ext_error" style="display: none"></div>
            </div>
            <div class="PinTop">
                <form action="" id="imageform" method="POST" enctype="multipart/form-data">
                    <div id="me" class="styleall" style=" cursor:pointer;"><span> <?php echo JText::_('MOD_SOCAILPINBOARD_MENU_UPLOAD_IMAGE'); ?></span></div>
                    <span id="mestatus" ></span>
                    <div id="upload_image_popup" style="display:none;">
                        <div id="upload_pin" style="display:none;">
                            <div class="ImagePicker" id="pricetags" > <img class="Images pin" id="upload_img_popup" name="upload_img_popup"  alt="change image"  /> </div>
                            <ul class="img_upload">
                                <li>
                                    <select name="upload_board" id="upload_board" class="customStyleSelectBox" onchange="changeselected(this)">
                                        <?php
                                        $boardid = 0;
                                        $i = 0;
                                        foreach ($resultboards as $board) {
                                            if ($i == 0) {
                                                $boardid = $board->board_id;
                                                $i = 1;
                                            }
                                        ?>
                                            <option value="<?php echo $board->board_id; ?>" > <?php echo $board->board_name; ?></option>
<?php } ?>
                                    </select>
                                    <input type="hidden" name="board_selection" id="board_selection" value="<?php echo $boardid; ?>"/>
                                    <input type="hidden" name="image_id" id="image_id" value=""/>
                                    <div class="uploadtext">
                                        <input style="color:#C9C8C8" type="text" name="uploadboardtxt" id="uploadboardtxt" onFocus="onFocusMenu(this,'<?php echo JTEXT::_('MOD_SOCAILPINBOARD_MENU_ENTER_NEW_BOARD_NAME'); ?>');" onBlur="onBlurEvent(this,'<?php echo JTEXT::_('MOD_SOCAILPINBOARD_MENU_ENTER_NEW_BOARD_NAME'); ?>');" value="<?php echo JTEXT::_('MOD_SOCAILPINBOARD_MENU_ENTER_NEW_BOARD_NAME'); ?>"/>
                                    </div>
                                    <div class="special" style=""><a href="javascript:void(0);" class="creat_bttn" onclick="addnewmenuboard(<?php echo $user->id; ?>)" style="display:block">
<?php echo JText::_('MOD_SOCAILPINBOARD_MENU_CREATE'); ?>
                                        </a></div>
                                    <div id="boardError" style="clear: both; margin: 0 0 0 23px; "></div>
                                </li>
                                <li>
                                    <div class="InputArea">
                                        <ul class="Form FancyForm">
                                            <li class="noMarginBottom ">
                                                <textarea  class="DescriptionTextarea" name="pin_desc" style="color:#C9C8C8; resize: none;" id="pin_desc" row="3" onFocus="onFocusMenu(this,'<?php echo JTEXT::_('MOD_SOCAILPINBOARD_MENU_PLEASE_ENTER_DESCRITION'); ?>');" onBlur="onBlurEvent(this,'<?php echo JTEXT::_('MOD_SOCAILPINBOARD_MENU_PLEASE_ENTER_DESCRITION'); ?>');" ><?php echo JTEXT::_('MOD_SOCAILPINBOARD_MENU_PLEASE_ENTER_DESCRITION'); ?></textarea>
                                            </li>
                                        </ul>
                                    </div>
                                    <div id="pinError"></div>
                                </li>
                                <li>
                                    <input type="submit" onclick ="return validatePin()" value="<?php echo JText::_('MOD_SOCAILPINBOARD_MENU_PIN_IT'); ?>" name="uploadPin" id="uploadPin"/>
                                </li>
                                <div class="clear"></div>
                            </ul>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="overlay"></div>
    </div>
    <!-- End of Upload the Pin-->

    <!-- To Create the Board -->
    <div id="CreateBoard" class="ModalContainer">
        <div class="modal wide" style="margin-bottom: -80px !important;">
            <div class="header lg"> <a href="#" class="close" onclick="javascript:document.getElementById('boardnameerror').innerHTML = '';document.getElementById('contributer').style.display = 'none';document.getElementById('menuSearchVal').style.display = 'none' ;document.getElementById('contributers_name_addboard').value = '';AddDialog.childClose('Add','CreateBoard'); return false"><strong>Close</strong><span></span></a>
                <h2><?php echo JText::_('MOD_SOCAILPINBOARD_MENU_CREATE_A_BOARD'); ?></h2>
            </div>
            <form action="" method="post" class="Form StaticForm noMargin">
                <ul>
                    <li class="noBorderTop">
                        <label><?php echo JText::_('MOD_SOCAILPINBOARD_MENU_BOARD_NAME'); ?></label>
                        <div class="floatright" style="width: 370px;">
                            <input id="board_name" type="text" name="board_name"  style="width: 370px;"/>
                            <div class="clear"></div>
                            <div id="boardnameerror"></div>
                            <span class="fff"></span> </div>
                    </li>
                    <li>
                        <input id="board_description" type="hidden" name="board_description" value="" />
                        <input id="created_date" type="hidden" name="created_date" value="<?php echo date("Y-m-d H:i:s"); ?>" />
                        <div class="BoardListOverlay"></div>
                        <div id="CategoryPicker" class="BoardSelector BoardPickerselect">
                            <select name="board_category_id" id="board_category_id" class="customStyleSelectBox">
<?php foreach ($result as $pincategory) { ?>
                                            <option  value=" <?php echo $pincategory->category_id; ?>"> <?php echo $pincategory->category_name; ?></option>
<?php } ?>
                                    </select>
                                </div>
                                <label><?php echo JTEXT::_('MOD_SOCAILPINBOARD_MENU_BOARD_CATEGORY'); ?></label>
                                <div id="categorynameerror"></div>
                            </li>
                            <li>
                                <label class="radio"><?php echo JTEXT::_('MOD_SOCAILPINBOARD_MENU_WHO_CAN_PIN'); ?></label>
                                <div class="Right" style="float: right;">
                                    <div style="display:none; border-top: 0;"></div>
                                    <ul class="pinability">
                                        <li>
                                            <label>
                                                <input type="radio" name="board_access" value="0" checked="checked" onclick="contributersoff();" />
                                                <span><?php echo JTEXT::_('MOD_SOCAILPINBOARD_MENU_JUST_ME'); ?></span> </label>
                                        </li>
                                        <li class="last-child">
                                            <label>
                                                <input type="radio" name="board_access" value="1" onclick="contributers();"/>
                                                <span> <?php echo JTEXT::_('MOD_SOCAILPINBOARD_MENU_ME_PLUS_CONTRIBUTERS'); ?> </span> </label>
                                        </li>
                                    </ul>
                                    <div style="clear:  both;"></div>
                                    <div id="contributer" style="display: none;  float: right; margin: 10px 0 0; overflow: hidden;">
                                        <input type="text" name="contributers_name_addboard" id="contributers_name_addboard" autocomplete="off" style="color: #999; margin-bottom:0px; float: left ; width: 280px;" value="<?php echo JTEXT::_('MOD_SOCAILPINBOARD_MENU_NAME_OR_EMAIL_ADDRESS'); ?>" onkeyup="showResult(this.value);" onFocus="onFocusEvent(this,'<?php echo JTEXT::_('MOD_SOCAILPINBOARD_MENU_NAME_OR_EMAIL_ADDRESS'); ?>');" onBlur="onBlurEvent(this,'<?php echo JTEXT::_('MOD_SOCAILPINBOARD_MENU_NAME_OR_EMAIL_ADDRESS'); ?>');"   />
                                        <input type="button" name="add_board_contributers" id="add_board_contributers" class="add_btn" onclick="userContributers();" value="<?php echo JTEXT::_('MOD_SOCAILPINBOARD_MENU_ADD'); ?>"  style="float:right; margin: 0px 0 0 10px; padding: 5px 23px;"/>
                                    </div>
                                    <div class="clear"></div>
                                    <div id="SearchVal">
                                        <ul id="menuSearchVal" class="static-form" style="display:none;">
                                        </ul>
                                    </div>
                                    <div id="loadingImage" style="display:none;"> <img src="<?php echo JURI::base() . '/modules/mod_socialpinboard_menu/images/loading.gif' ?>" width="20" height="20" alt="" /> </div>
                                    <div id="contributer_name"> </div>
                                </div>
                            </li>
                        </ul>
                        <div >
                            <input type="submit" onclick="return validateBoard();" name="btnBoard" id="btnBoard"  class="create_board" value="<?php echo JTEXT::_('MOD_SOCAILPINBOARD_MENU_CREATE_BOARD'); ?>" />
                            <input type="hidden" name="id_contributers" id="id_contributers" value="" />
                        </div>
                        <div class="CreateBoardStatus error"></div>
                    </form>
                </div>
                <div class="overlay"></div>
            </div>
            <!-- Repin contnet starts here -->
            <div id="Repin" class="ModalContainer">
                <div class="modal wide PostSetup" style="margin-bottom: -138px; ">
                    <div id="postsetup">
                        <div class="header lg">
                            <h2><?php echo JText::_('COM_SOCIALPINBOARD_REPIN'); ?></h2>
                            <a href="#" class="close-btn" onclick=" document.getElementById('descriptionerror').innerHTML='';document.getElementById('Header').style.zIndex = 999;Modal.close('Repin'); return false"></a> </div>
                        <div class="ImagePicker">
                            <div class="Images" id="imagepin"><img src="" name="pinImage" id="pinImage"></div>
                        </div>
                        <div class="PinForm">
                            <select name="repin_board" id="repin_board" class="customStyleSelectBox">
                        <?php foreach ($resultboards as $board) { ?>
                                            <option class="BoardCategory" value=" <?php echo $board->board_id; ?>"> <?php echo $board->board_name;
                                            ; ?></option>
                        <?php } ?>
                                    </select>
                                    <input type="hidden" name="board_selection" id="board_selection" value="<?php if (isset($boards))
                                            echo $boards->board_id; ?>"/>
                                 <div class="uploadtext">
                                     <input type="text" name="boardtxt" id="boardtxt" value=""/>
                                 </div>
                                 <div class="special"><a href="javascript:void(0);" class="creat_bttn" onclick="addrepinmenuboard(<?php echo $user->id; ?>)" style="display:block">
                            <?php //echo JText::_('COM_SOCIALPINBOARD_CREATE');     ?> Create
                                    </a></div>
                                <div id="boardError"></div>
                                <div class="repin-text InputArea" style="float: left;width: 350px;">
                                    <ul class="Form FancyForm">
                                        <li class="noMarginBottom  val">
                                            <textarea class="DescriptionTextarea" id="DescriptionTextarea" rows="2" data-text-error-empty="Please enter a description." name="caption"></textarea>
                                            <div class="tagmate-menu" style="position: absolute; display: none; "></div>
                                            <span class="fff"></span> </li>
                                    </ul>
                                    <span id="descriptionerror"></span>
                                    <input type="hidden" id="currencyvalue" value="<?php echo $currency; ?>">
                                    <input type="hidden" name="pin_type_id" id="pin_type_id" value=""/>
                                    <input type="hidden" name="pin_repin_id" id="pin_repin_id" value=""/>
                                    <input type="hidden" name="pin_real_pin_id" id="pin_real_pin_id" value=""/>
                                    <input type="hidden" name="pin_url" id="pin_url" value=""/>
                                    <input type="hidden" name="pin_user_id" id="pin_user_id" value="<?php echo $user->id; ?>"/>
                                </div>
                                <div class="CreateBoardStatus error mainerror" id="CreateBoardStatus"> </div>
                                <div class="Buttons" style="clear:both;">
                                    <input type="button" id="uploadPin" onclick="return ajxGetBoards('<?php echo JURI::base(); ?>');" value="Pin It"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="overlay"></div>
                    <div id="PostSuccess" class="modal wide PostSuccess" style="display:none;margin-bottom: -138px;margin-top:225px;text-align: center;overflow: hidden; font-size: 20px;">
                        <div class="header lg" style="text-align: left;">
                            <h2><a href="#" class="close" onclick="document.getElementById('Header').style.zIndex = 3;RepinDialog.reset(); return false"><strong><?php echo JText::_('COM_SOCIALPINBOARD_CLOSE'); ?></strong><span></span></a><?php echo JText::_('COM_SOCIALPINBOARD_REPIN'); ?></h2>
                        </div>
            <?php echo JText::_('MOD_SOCAILPINBOARD_MENU_REPINNING_PLEASE_WAIT'); ?> <span><a href="#" class="BoardLink" id="boardLink"></a></span>. </div>
                                </div>
                                <script type="text/javascript">
                                    var currencyvalue = document.getElementById("currencyvalue").value;
                                     scr(document).ready(function($){

                                        ScrapePinDialog.setup();
                                        UploadPinDialog.setup();
                                        CreateBoardDialog.setup();
                                        RepinDialog.setup();
                                        FancyForm.setup();
                                    });
                                </script>
                                <!-- Repin contenet ends here -->
                                <script type="text/javascript">

                                    function changeselected(obj)
                                    {

                                        var strUser = obj.options[obj.selectedIndex].value;

                                        document.getElementById('board_selection').value = strUser;
                                    }
                                    function contributers()
                                    {
                                        document.getElementById('contributer').style.display="block";
                                    }
                                    function contributersoff()
                                    {
                                        document.getElementById('contributer').style.display="none";
                                    }
                                    function showResult(str)
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
                                                    {  document.getElementById("menuSearchVal").innerHTML="";
                                                        document.getElementById("menuSearchVal").style.display="none";
                                                        document.getElementById("loadingImage").style.display="none";

                                                    }
                                                    else{
                                                        document.getElementById("loadingImage").style.display="none";
                                                        document.getElementById("menuSearchVal").style.display="block";


                                                        var searchValue=xmlhttp.responseText;

                                                        //alert(searchValue.length);
                                                        var b='';
                                                        if(searchValue !='""'){

                                                            var obj = jQuery.parseJSON(searchValue);
                                                            if(searchValue==',')
                                                            {
                                                                document.getElementById("menuSearchVal").style.display='none';
                                                                return false;

                                                            }
                                                            var nameArray = obj.username;
                                                            var idArray=obj.userid


                                                            for(var i=0;i< nameArray.length;i++)
                                                            {
                                                                b+='<li id="static_li" style="padding-left:5px;" onclick="userSelectBoard(\''+nameArray[i]+'\',\''+idArray[i]+'\')" >'+nameArray[i]+'</li>';
                                                            }
                                                            document.getElementById("contributer_name").innerHTML = '';
                                                            document.getElementById("menuSearchVal").innerHTML=b;
                                                        }
                                                        else{
                                                            document.getElementById("menuSearchVal").innerHTML = '';
                                                            document.getElementById("menuSearchVal").innerHTML ='No users found';
                                                        }

                                                    }

                                                }
                                            }
                                            var url = "?option=com_socialpinboard&view=ajaxcontrol&tmpl=component&task=getcontributers&user="+str;
                                            xmlhttp.open("GET",url,true);
                                            xmlhttp.send();
                                        }
                                        else{
                                            document.getElementById("menuSearchVal").style.display= "none";
                                        }
                                    }

                                    function userSelectBoard(userCon)
                                    {
                                        document.getElementById('contributers_name_addboard').value=userCon;

                                    }

                                </script>
                                <script type="text/javascript" >
                                    scr(function(){
                                        var btnUpload=scr('#me');
                                        var mestatus=scr('#mestatus');
                                        var files=scr('#files');
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
                                                    document.getElementById('upload_img_popup').src = '<?php echo JURI::base(); ?>/modules/mod_socialpinboard_menu//images/socialpinboard/temp/'+response;
                                                    document.getElementById('upload_image_popup').style.display="block";
                                                    document.getElementById('upload_pin').style.display="block";
                                                    document.getElementById('pricetags').style.display="block";
                                                    document.getElementById('image_id').value = response;
                                                }
                                            }
                                        });

                                    });

                                    function ajxGetBoards(baseurl){

                                        var xhr = getXhr();
                                        var board=document.getElementById('repin_board').value;
                                        if(board=='')
                                        {

                                            document.getElementById('descriptionerror').innerHTML='<label style="color:red">Please Select A Board </label>';
                                            return false;
                                        }
                                        var descriptions = document.getElementById('DescriptionTextarea').value;
                                        var a = document.getElementById('boardLink');
                                        a.href = baseurl+"?option=com_socialpinboard&view=boardpage&bId="+board;
                                        //    var selectedindex=document.getElementById('board_selection').selectedIndex;
                                        // a.textContent = document.getElementById('one-ddheader').innerHTML;

                                        if(descriptions=='')
                                        {
                                            document.getElementById('descriptionerror').innerHTML='<label style="color:red">Please enter description</label>';
                                            return false;
                                        }
                                        var pin_repin_id = document.getElementById('pin_repin_id').value;
                                        var pin_real_pin_id=document.getElementById('pin_real_pin_id').value;
                                        var pin_user_id=document.getElementById('pin_user_id').value;


                                        if (pin_user_id!=0)
                                        {
                                            xhr.onreadystatechange = function(){
                                                if(xhr.readyState == 4){
                                                    try
                                                    {
                                                        var options = xhr.responseText;
                                                        // var a = document.getElementById('PinLink');
                                                        // a.href = baseurl+"?option=com_socialpinboard&view=pin&pinid="+options;

                                                        if(options)
                                                        {
                                                            var span;
                                                            var count = 0

                                                            if(isNaN(scr("#repincountspan"+pin_repin_id).text())){

                                                                count =parseInt(scr("#repincountspan"+pin_repin_id).text().substring(0,scr("#repincountspan"+pin_repin_id).text().indexOf(" ")))+1;
                                                                span = count+" Repins ";
                                                            }else{
                                                                span= "1 Repin ";
                                                            }
                                                            scr("#repincountspan"+pin_repin_id).text(span);

                                                            RepinDialog.append();
                                                        }
                                                    }
                                                    catch(e) {
                                                        alert(e.message)
                                                    }
                                                }
                                            }

                                            var url = baseurl+'index.php?board_id='+board+'&description='+descriptions+'&repin_id='+pin_repin_id+'&real_pin_id='+pin_real_pin_id+'&pin_user_id='+pin_user_id;

                                            xhr.open("GET",url,true);
                                            xhr.send(null);
                                        }

                                    }



                                </script>
    <?php
                                    }
    ?>
                                </div>
                                <script type="text/javascript">
                                    function validateBoard(){

                                        var boardtitle = document.getElementById('board_name').value;
                                        var categorytitle = document.getElementById('board_category_id').value;
                                        document.getElementById('categorynameerror').innerHTML='';
                                        document.getElementById('boardnameerror').innerHTML='';
                                        if(categorytitle == '0' ||categorytitle == '' ){
                                            document.getElementById('categorynameerror').innerHTML='<label style="color:red; margin-left: 180px;"><?php echo JText::_('MOD_SOCAILPINBOARD_MENU_PLEASE_SELECT_THE_CATEGORY'); ?></label>';
                                            return false;
                                        }

                                        if(boardtitle == ''){
                                            document.getElementById('boardnameerror').innerHTML='<label style="color: red; font-size:12px; width:auto; font-weight:bold; float: left;"><?php echo JText::_('MOD_SOCAILPINBOARD_MENU_PLEASE_ENTER_THE_BOARD_NAME'); ?></label>';
                                            return false;
                                        }
                                    }

                                    function validatePin(){


                                        var boardName = document.getElementById('upload_board').value;
                                        var pinDesc = document.getElementById('pin_desc').value;

                                        if(boardName == '' || boardName=='0'){

                                            document.getElementById('boardError').innerHTML='<label id="login_error_msg" style="clear: both; margin: 0 0 0 1px; "><?php echo JText::_('MOD_SOCAILPINBOARD_MENU_PLEASE_ENTER_THE_BOARD_NAME'); ?></label>';
                                            return false;
                                        }

                                        if(pinDesc == '' || pinDesc == '<?php echo JTEXT::_('MOD_SOCAILPINBOARD_MENU_PLEASE_ENTER_DESCRITION'); ?>'){
                                            document.getElementById('pinError').innerHTML='<label id="login_error_msg" style="margin: 10px 0 0 -128px;display: block;clear: both;"><?php echo JText::_('MOD_SOCAILPINBOARD_MENU_PLEASE_ENTER_THE_DESCRITION'); ?></label>';
                                            return false;
                                        }
                                        document.getElementById('uploadPin').style.display='none';
                                        document.getElementById('addpinloader').style.display='block';

                                    }


                                    function validateImageUrl()
                                    {


                                        var desc = document.getElementById('txtpin').value;
                                        var str = document.getElementById('srcimg').value;
                                        if(str=='')
                                        {
                                            document.getElementById('addpinerror').innerHTML='<label id="login_error_msg" style="margin: 10px 0 0 22px;display: block;clear: both;"><?php echo JTEXT::_('MOD_SOCIALPINBOARD_MENU_IMAGE_DOESNOT_EXIST'); ?></label>';
                                            return false;
                                        }


                                        if(desc == ''|| desc=='<?php echo JText::_('MOD_SOCAILPINBOARD_MENU_PLEASE_ENTER_DESCRITION'); ?>'){
                                            document.getElementById('addpinerror').innerHTML='<label id="login_error_msg" style="padding: 0px;display: block;clear: both;position: relative;margin: 0;text-align: left;"><?php echo JTEXT::_('MOD_SOCAILPINBOARD_MENU_PLEASE_ENTER_THE_DESCRITION'); ?></label>';
                                            return false;
                                        }
                                        document.getElementById('add_pin').style.display="none";
                                        document.getElementById('add_pining').style.display="block";
                                    }
                                </script>
                                <script type="text/javascript">
                                    function loadXMLDoc()
                                    {
                                        var url;
                                        var content;
                                        var filename = document.getElementById('ScrapePinInput').value;
                                        if( filename.indexOf('http://') !== 0  && filename.indexOf('https://') !== 0){
                                            filename = 'http://' + filename;
                                        }

                                        document.getElementById('imgcnt').value=0;
                                        if(filename == ''){
                                            alert('<?php echo JTEXT::_('MOD_SOCIALPINBOARD_MENU_PLEASE_ENTER_VALID_URL'); ?>');
                                            return false;
                                        }else if (!isUrl(filename))
                                        {
                                            alert("<?php echo JTEXT::_('MOD_SOCIALPINBOARD_MENU_PLEASE_ENTER_VALID_URL'); ?>");
                                            return false;
                                        }
                                        document.getElementById('ScrapeButton').style.display="none";
                                        document.getElementById('ScrapeButton1').style.display="block";


                                        var matches = filename.match(/watch\?v=([a-zA-Z0-9\-_]+)/); //check for the youtube
                                        var valid_extensions = /(\.jpg|\.jpeg|\.png|\.gif)$/i;   //regular expression for images
                                        if(valid_extensions.test(filename)) //check the extensions available in the url
                                        {

                                            content = '<img id="imgsrc0" src="'+filename+'" />:;'; // if available bind the image url with the src tag

                                            bindimage(content);

                                        }
                                        else if(matches) // if the url is the youtube
                                        {
                                            var regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/; //regular expression for youtube
                                            var match = filename.match(regExp);  // get the array value of the url
                                            if (match&&match[7].length==11){
                                                youtubeId =  match[7];
                                                content = '<input type="hidden" name="type" id="type" value="youtube"/>';
                                                content += '<img id="youtubeimg" src="http://img.youtube.com/vi/' +youtubeId+'/0.jpg" width="198" height="150"/>';
                                                content += '<input type="hidden" name="youtube_image" id="youtube_image" value="http://img.youtube.com/vi/' +youtubeId+ '/0.jpg"/>';
                                                bindimage(content);
                                            }

                                        }
                                        else if (filename.indexOf('vimeo.com') > -1) {  //Checks for the vimeo condition
                                            id = filename.match(/http:\/\/(?:www.)?(\w*).com\/(\d*)/)[2];
                                            scr.ajax({
                                                url: 'http://vimeo.com/api/v2/video/' + id + '.json',
                                                dataType: 'jsonp',
                                                success: function(data) {

                                                    content = '<input type="hidden" name="type" id="type" value="vimeo"/>';
                                                    content += '<img id="youtubeimg" src="' +data[0].thumbnail_large+'" width="198" height="150"/>';
                                                    content += '<input type="hidden" name="youtube_image" id="youtube_image" value="' +data[0].thumbnail_large+ '"/>';
                                                    bindimage(content);
                                                }
                                            });
                                        }
                                        else
                                        {
                                            url = filename;

                                            content = ajaxinclude(url);

                                        }

                                    }


                                    function ajaxinclude(url)
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
                                        var strval=url.split('').reverse().join('');
                                        pageurl = '<?php echo JURI::base(); ?>/modules/mod_socialpinboard_menu/saveimagefromurl.php?url='+strval;
        xmlhttp.open("GET",pageurl,true);

        xmlhttp.send();
    }
                  
    function bindimage(content)
    {
        var Images = content.split(':;');
        document.getElementById("getImg").innerHTML='';
        for(var i=0;i<Images.length;i++){ 
            document.getElementById("getImg").innerHTML += Images[i];
            if(document.getElementById("youtubeimg"))
                document.getElementById("srcimg").value = document.getElementById("youtubeimg").src;
            document.getElementById('urlImages').style.display='block';
            document.getElementById('ScrapeButton1').style.display = "none";
            document.getElementById('ScrapeButton').style.display = "block";
            if(i == 0){
    									
                if(document.getElementById("imgsrc"+i))
                    document.getElementById("imgsrc"+i).style.display='block';
                document.getElementById('urlImages').style.display='block';
                if(document.getElementById("imgsrc"+i))
                    document.getElementById('srcimg').value = document.getElementById("imgsrc"+i).src;
            }
            else
            {
                document.getElementById("imgsrc"+i).style.display='none';

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
    function nextPrev(type){
        var imgcount = document.getElementById('imgcnt').value;
        var orig = imgcount;
        if(type == 'prev'){
            imgcount--;
        }
        else if(type == 'next'){
            imgcount++;
        }
                         
        if( document.getElementById("imgsrc"+imgcount))
        {
            document.getElementById("imgsrc"+orig).style.display='none';
            document.getElementById('imgcnt').value = imgcount;
            document.getElementById("imgsrc"+imgcount).style.display='block';
            document.getElementById('srcimg').value = document.getElementById("imgsrc"+imgcount).src;
                        
        }

    }
    function addPinclose()
    {
                            
        document.getElementById('urlImages').style.display = 'none';
        document.getElementById('ScrapePinInput').value = '';
        document.getElementById('boardErrorpin').innerHTML = '';
        AddDialog.childClose('Add','ScrapePin');
        document.getElementById('ScrapeButton1').style.display = 'none';
        document.getElementById('ScrapeButton').style.display = 'block';
        return false;
                            
    }
</script>
<script>
    var Modal=Modal||{
        setup:function(){
            $(document).keydown(function(a){
                if(a.keyCode==27){
                    var c=$(".ModalContainer:visible").attr("id");
                    if(c)Modal.close(c);else $("#zoomScroll").length&&window.History.back();
                    a.preventDefault()
                }
            })
        },
        show:function(a){
            if(a=='EmailModal')
            {
                document.getElementById('MessageRecipientName').value='';
                document.getElementById('MessageRecipientEmail').value='';
                document.getElementById('MessageBody').value='';
                document.getElementById('output').innerHTML='';
            }
            var c=scr("#"+a);

            if(a == 'Repin')Tagging.priceTag("#DescriptionTextarea","#imagepin");

            a=scr(".modal:first",c);
            if(scr('body').hasClass('noscrollf'))
            {
            }
            else
            {
                scr("body").addClass("noscroll");
            }
            c.show();
            var d=a.outerHeight()-50;
            a.css("margin-bottom","-"+d/2+"px");
            setTimeout(function(){
                c.addClass("visible");
                c.css("-webkit-transform","none")
            },1);
            return false
        },
        close:function(a){
            var c=
                scr("#"+a);
            scr("#zoomScroll").length===0&&scr("body").removeClass("noscroll");
            c.removeClass("visible");
            setTimeout(function(){
                c.hide();
                c.css("-webkit-transform","translateZ(0)")
            },251);
            return false
        }
    };

    var RepinDialog=RepinDialog||{
        setup:function(){

            var a=scr("#Repin"),c=scr("form",a),d=scr(".Buttons .Button",a),f=scr("strong",d),g=scr(".DescriptionTextarea",a),b=scr(".mainerror",a);

            Tagging.initTextarea("#DescriptionTextarea");
            Tagging.priceTag("#DescriptionTextarea","#imagepin");
            scr("#Repin").submit(function(){
                Tagging.loadTags("#DescriptionTextarea","#id_pin_replies","#pin_tags","#id_buyable")
            });
            scr("#DescriptionTextarea").keyup(function(){
                scr("#postDescription").html(scr(this).val())
            })



            AddDialog.shareCheckboxes("Repin");




        },
        grid:function(){
            $(".repin_link").live("click",function(){

                pinID=$(this).parents(".pin").attr("data-id");
                RepinDialog.show(pinID);
                return false
            })
        },
        show:function(a){


        },
        reset:function(){
            document.getElementById('Header').style.zIndex = 7;
            var a=scr("#Repin");
            Modal.close("Repin");
            a.removeClass("visible").removeClass("super");
            scr(".PostSuccess",a).hide();
            scr("form",a).attr("action","");
            scr(".DescriptionTextarea",a).val("");
            scr(".ImagePicker .Images",a).html("");
            scr(".price",a).removeClass("visible").html("");
            scr(".mainerror",a).html("");
            scr(".Buttons .RedButton",a).removeClass("disabled");
            scr(".Buttons .RedButton strong",a).html("Pin It");
            scr("#repin_pin_id",a).val("")
        },
        append:function()
        {

            var a=scr("#Repin");
            //                        trackGAEvent("repin_submit","success","dialogue");
            var h=scr("#PostSuccess");

            h.show();
            setTimeout(function(){
                a.addClass("super")
            },1);
            setTimeout(function(){
                RepinDialog.reset()
            },2500);

        }
    };
    var BoardPicker=function(){
        return{
            setup:function(a,c,d){
                a=scr(a);
                var f=scr(".BoardListOverlay",a.parent()),g=scr(".BoardList",a),b=scr(".CurrentBoard",a),e=scr("ul",g);
                a.click(function(){
                    g.show();
                    f.show()
                });
                f.click(function(){
                    g.hide();
                    f.hide()
                });
                scr("li",e).live("click",function(){
                    b.text(scr(this).text());
                    f.hide();
                    g.hide();
                    c&&c(scr(this).attr("data"));
                    return false
                });
                a=scr(".CreateBoard",g);
                var h=scr("input",a),k=scr(".Button",a);
                scr("strong",k);
                var l=scr(".CreateBoardStatus",a);

                k.click(function(){
                    if(k.attr("disabled")==
                        "disabled")return false;
                    if(h.val()=="Create New Board"){
                        l.html("Enter a board name").css("color","red").show();
                        return false
                    }
                    l.html("").hide();
                    k.addClass("disabled").attr("disabled","disabled");

                    return false
                })
            }
        }
    }();
    var AddDialog=function(){
        return{
            setup:function(a){
                var c="#"+a,d=scr(c);
                BoardPicker.setup(c+" .BoardPicker",function(f){
                    scr(c+" #id_board").val(f)
                },function(f){
                    scr(c+" #id_board").val(f)
                });
                AddDialog.shareCheckboxes(a);
                Tagging.initTextarea(c+" .DescriptionTextarea");
                Tagging.priceTag(c+" .DescriptionTextarea",c+" .ImagePicker");

            },
            reset:function(a){
                a==="CreateBoard"&&CreateBoardDialog.reset();
                a==="ScrapePin"&&ScrapePinDialog.reset();
                a==="UploadPin"&&UploadPinDialog.reset();
                AddDialog._resets[a]&&AddDialog._resets[a]()
            },
            close:function(a,c){
                scr("#"+a).addClass("super");
                Modal.show(c)
            },
            childClose:function(a,
            c){
                var d=this,f=scr("#"+c);
                scr(".ModalContainer",f);

                //d.reset(c);

                scr("#"+a).removeClass("super");

                Modal.close(a);
                Modal.close(c)
            },
            pinBottom:function(a){
                var c=scr("#"+a);
                scr(".PinBottom",c).slideDown(300,function(){
                    var d=scr(".modal:first",c);
                    d.css("margin-bottom","-"+d.outerHeight()/2+"px")
                })
            },
            shareCheckboxes:function(a){
                function c(g){
                    var b=scr("#"+a+" .publish_to_"+g),e=scr("#"+a+" #id_publish_to_"+g);
                    b.change(function(){
                        if(b.is(":checked")){
                            e.attr("checked","checked");
                            b.parent().addClass("active")
                        }else{
                            e.removeAttr("checked");
                            b.parent().removeClass("active")
                        }
                    });
                    var h=b.is(":checked");
                    return function(){
                        if(h){
                            b.parent().addClass("active");
                            b.attr("checked","checked")
                        }else{
                            b.parent().removeClass("active");
                            b.removeAttr("checked")
                        }
                    }
                }
                var d=c("facebook"),f=c("twitter");
                AddDialog._resets=AddDialog._resets||{};

                AddDialog._resets[a]=function(){
                    d();
                    f()
                }
            }
        }
    }();
    var EditPin=function(){
        return{

            setup:function(){

                Tagging.initTextarea("#description_pin_edit");
                Tagging.priceTag("#description_pin_edit","#PinEditPreview");
                scr("#PinEdit").submit(function(){
                    Tagging.loadTags("#description_pin_edit","#id_pin_replies","#pin_tags","#id_buyable")
                });
                scr("#description_pin_edit").keyup(function(){
                    scr("#postDescription").html(scr(this).val())
                })
            }
        }
    }();




    var CreateBoardDialog=function(){
        return{
            setup:function(){
                function a(){
                    if(!g){
                        g=true;
                        Tagging.initInput("#CreateBoard #collaborator_name",function(b){
                            f=b
                        },function(){
                            $("#CreateBoard #submit_collaborator").click()
                        })
                    }
                }
                function c(){
                    var b=[];
                    $("#CurrentCollaborators .Collaborator",d).each(function(){
                        b.push($(this).attr("username"))
                    });
                    return b
                }
                var d=scr("#CreateBoard"),f=null,g=false;


                BoardPicker.setup("#CreateBoard .BoardPicker",function(b){
                    $("#id_category",d).val(b)
                });
                scr("#BoardName",d).keyup(function(){
                    scr(".board_name.error",
                    d).html()!==""&&scr(".board_name.error",d).html("")
                });
                scr(".Submit .Button",d).click(function(){

                    if(scr("#BoardName",d).val()=="Board Name"||$("#BoardName",d).val()==""){
                        scr(".board_name.error",d).html("Please enter a board name").show();
                        return false
                    }
                    if(!scr("#id_category",d).val()){
                        scr(".board_category.error",d).html("Please select a category").show();
                        return false
                    }
                    var b=scr(".Submit .Button",d),e=b.children("strong");
                    b.attr("disabled","disabled").addClass("disabled");
                    e.html("Creating &hellip;");

                    return false
                })
            },
            reset:function(){
                $("#BoardName").val("");
                $("input[value='me']").attr("checked",true);
                $("#CurrentCollaborators").empty()
            }
        }
    }();



    var CropImage=function(){
        this.initialize.apply(this,arguments)
    };
    var BoardCoverSelector=function(){
        this.initialize.apply(this,arguments)
    };
    var Tagging=function(){
        return{
            friends:null,
            friendsLinks:{},
            getFriends:function(a,c,d){
                var e=a.term;
                (function(f){
                    Tagging.friends?f():$.get("/x2ns4tdf0cd7cc9b/_getfriends/",function(b){
                        Tagging.friends=[];
                        $.each(b,function(g,h){
                            Tagging.friends.push({
                                label:h.name,
                                value:h.username,
                                image:h.image,
                                link:"/"+h.username+"/",
                                category:"People"
                            });
                            Tagging.friendsLinks["/"+h.username+"/"]=1
                        });
                        f()
                    })
                })(function(){
                    var f=[];
                    if(d)for(name in d)Tagging.friendsLinks[name]||!d.hasOwnProperty(name)||f.push(d[name]);f=f.concat(Tagging.friends);
                    if(e)f=tagmate.filter_options(f,e);
                    c(f)
                })
            },
            initInput:function(a,c,d){
                a=$(a);
                var e=$("<div class='CollabAutocompleteHolder'></div>");
                a.after(e);
                a.autocomplete({
                    source:Tagging.getFriends,
                    minLength:1,
                    delay:5,
                    appendTo:e,
                    change:function(f,b){
                        c&&c(b.item)
                    },
                    select:function(f,b){
                        c&&c(b.item);
                        return false
                    },
                    position:{
                        my:"left top",
                        at:"left bottom",
                        offset:"0 -1"
                    }
                }).keydown(function(f){
                    f.which==13&&d&&d()
                });

            },
            initTextarea:function(a,c){
                a=scr(a);
                var d={};

                d["@"]=tagmate.USER_TAG_EXPR;
                d["#"]=tagmate.HASH_TAG_EXPR;
                d.$=tagmate.USD_TAG_EXPR;
                d["\u00a3"]=tagmate.GBP_TAG_EXPR;
                a.tagmate({
                    tagchars:d,
                    sources:{
                        "@":function(e,f){
                            Tagging.getFriends(e,f,c)
                        }
                    }
                })
            },
            loadTags:function(a,c,d,e){
                a=$(a).getTags();
                for(var f=[],b=[],g=null,h=0;h<a.length;h++){
                    a[h][0]==
                        "@"&&f.push(a[h].substr(1));
                    a[h][0]=="#"&&b.push(a[h].substr(1));
                    if(a[h][0]=="$"||a[h][0]=="\u00a3")g=a[h]
                }
                $(c).val(f.join(","));
                $(d).val(b.join(","));
                $(e).val(g)
            },
            priceTag:function(a,c){

                function d(){
                    var e=scr(".price",c);
                    if(e.length<=0){
                        e=scr("<div class='price'></div>");
                        c.prepend(e)
                    }
                    var f=a.getTags({
                        $:tagmate.USD_TAG_EXPR,
                        "\u00a3":tagmate.GBP_TAG_EXPR
                    });
                    if(f&&f.length>0){
                        e.text(f[f.length-1]);
                        e.addClass("visible");
                    }else{
                        e.removeClass("visible");
                        e.text("")
                    }
                }
                a=scr(a);
                c=scr(c);
                a.unbind(".priceTag").bind("keyup.priceTag",
                d).bind("focus.priceTag",d).bind("change.priceTag",d);
                d()
            }
        }
    }();
    var ScrapePinDialog=ScrapePinDialog||{
        id:"ScrapePin",
        setup:function(){
            var a=this;
            AddDialog.setup(a.id);
            a.initScraperInput()
        },
        initScraperInput:function(){
            function a(j){
                return/(http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/.test(j)
            }
            function c(j){
                var k=true;
                if(j.indexOf("http")!=0)j="http://"+j;
                if(j=="")k=false;
                if(j=="http://")k=false;
                if(j.length<2)k=false;
                if(j.indexOf(".")==-1)k=false;
                a(j)||(k=false);
                return k
            }
            function d(){
                var j=scr("#"+ScrapePinDialog.id),k=scr("#ScrapePinInput").val();

            }
            function e(){
                if(images_count>0){
                    images_count=
                        -1;
                    f()
                }
            }
            function f(){
                strHtml="";
                imgFound=false;
                for(var j=foundCtr=0;j<imagesArray.length;j++){
                    img=imagesArray[j];
                    if(img.width>=150&&img.height>=50){
                        imgFound=true;
                        foundCtr++;
                        strHtml+="<li>"+(is_video(img.src)?"<img src='"+media_url+"images/VideoIndicator.png' alt='Video Icon' class='video' />":"")+"<img src='"+img.src+"' width='156px' alt='' /></li>"
                    }
                }
                if(strHtml!=""){
                    $("#ScrapePin .ImagePicker .Images ul").html(strHtml);
                    b(foundCtr)
                }else alert("No Large Images Found.")
            }
            function b(){
                var j=function(p,
                s){
                    im=$(s).find("img")[0];
                    if($(im).hasClass("video"))im=$(s).find("img")[1];
                    src=$(im).attr("src");
                    $("#id_img_url").val(src);
                    $("#id_link").val($("#ScrapePinInput").val())
                },k=$("#ScrapePin .ImagePicker .Images").jcarousel({
                    buttonNextHTML:null,
                    buttonPrevHTML:null,
                    initCallback:function(p){
                        $("#ScrapePin .imagePickerNext").click(function(){
                            p.next();
                            return false
                        });
                        $("#ScrapePin .imagePickerPrevious").click(function(){
                            p.prev();
                            return false
                        })
                    },
                    animation:"fast",
                    itemVisibleInCallback:{
                        onAfterAnimation:j
                    },
                    scroll:1
                });
                j(k,scr("#ScrapePin .ImagePicker").find("li")[0],1,"next")
            }
            function g(){
                var j=scr("#ScrapeButton");
                if(c(scr("#ScrapePinInput").val())){
                    j.addClass("disabled");
                    d()
                }else{
                    alert("Please enter a valid website URL");
                    j.removeClass("disabled")
                }
            }
            var h="";
            scr("#ScrapePinInput").bind("keydown",function(j){
                j.keyCode===13&&g()
            });
            scr("#ScrapeButton").click(function(){
                g();
                return false
            })
        },
        reset:function(){
            var a=$("#"+this.id);
            $("#ScrapePinInput",a).val("");
            $(".PinBottom",a).hide();
            $(".modal",a).css("margin-bottom","0");
            $(".Buttons .Button",
            a).removeClass("disabled");
            $(".Buttons .Button strong",a).html("Pin It");
            ScrapePinDialog.initScraperInput()
        }
    };
    var UploadPinDialog=UploadPinDialog||{
        id:"UploadPin",
        setup:function(){
            var a=this,c=scr("#"+a.id);
            AddDialog.setup(a.id);

        },
        reset:function(){
            var a=$("#"+this.id);
            $("input[type=file]",a).val("");
            $(".PinBottom",a).hide();
            $(".modal",a).css("margin-bottom","0");
            $(".Buttons .Button",a).removeClass("disabled");
            $(".Buttons .Button strong",a).html("Pin It")
        }
    };
    var FancyForm=function(){
        return{
            inputs:".Form input, .Form textarea",
            button:".SubmitButton",
            setup:function(){
                var a=this;
                this.inputs=scr(this.inputs);
                a.inputs.each(function(){
                    var c=scr(this);
                    a.checkVal(c)
                });
                a.inputs.live("keyup blur",function(){
                    var c=scr(this);
                    a.checkVal(c);
                    var d=c.parents("ul"),e=c.parents(".Form").find(a.button);
                    c.parents("li").hasClass("NoCheck")||a.checkDisabled(d,e)
                });
                scr(a.button).live("click",function(){
                    var c=scr(this).attr("data-form");
                    if(scr(this).hasClass("disabled"))return false;else scr("#"+
                        c+" form").submit()
                })
            },
            checkVal:function(a){
                a.val().length>0?a.parent("li").addClass("val"):a.parent("li").removeClass("val")
            },
            checkDisabled:function(a,c){
                a.children("li:not(.optional)").length<=a.children("li.val").length?c.removeClass("disabled"):c.addClass("disabled")
            }
        }
    }();
    (function(){
        jQuery.each({
            getSelection:function(){
                var a=this.jquery?this[0]:this;
                return("selectionStart"in a&&function(){
                    var c=a.selectionEnd-a.selectionStart;
                    return{
                        start:a.selectionStart,
                        end:a.selectionEnd,
                        length:c,
                        text:a.value.substr(a.selectionStart,c)
                    }
                }||document.selection&&function(){
                    a.focus();
                    var c=document.selection.createRange();
                    if(c==null)return{
                        start:0,
                        end:a.value.length,
                        length:0
                    };

                    var d=a.createTextRange(),e=d.duplicate();
                    d.moveToBookmark(c.getBookmark());
                    e.setEndPoint("EndToStart",d);
                    var f=
                        e.text.length,b=f;
                    for(d=0;d<f;d++)e.text.charCodeAt(d)==13&&b--;
                    f=e=c.text.length;
                    for(d=0;d<e;d++)c.text.charCodeAt(d)==13&&f--;
                    return{
                        start:b,
                        end:b+f,
                        length:f,
                        text:c.text
                    }
                }||function(){
                    return{
                        start:0,
                        end:a.value.length,
                        length:0
                    }
                })()
            },
            setSelection:function(a,c){
                var d=this.jquery?this[0]:this,e=a||0,f=c||0;
                return("selectionStart"in d&&function(){
                    d.focus();
                    d.selectionStart=e;
                    d.selectionEnd=f;
                    return this
                }||document.selection&&function(){
                    d.focus();
                    var b=d.createTextRange(),g=e;
                    for(i=0;i<g;i++)if(d.value[i].search(/[\r\n]/)!=
                        -1)e-=0.5;g=f;
                    for(i=0;i<g;i++)if(d.value[i].search(/[\r\n]/)!=-1)f-=0.5;b.moveEnd("textedit",-1);
                    b.moveStart("character",e);
                    b.moveEnd("character",f-e);
                    b.select();
                    return this
                }||function(){
                    return this
                })()
            },
            replaceSelection:function(a){
                var c=this.jquery?this[0]:this,d=a||"";
                return("selectionStart"in c&&function(){
                    c.value=c.value.substr(0,c.selectionStart)+d+c.value.substr(c.selectionEnd,c.value.length);
                    return this
                }||document.selection&&function(){
                    c.focus();
                    document.selection.createRange().text=d;
                    return this
                }||
                    function(){
                    c.value+=d;
                    return this
                })()
            }
        },function(a){
            jQuery.fn[a]=this
        })
    })();

    var tagmate=tagmate||{
        USER_TAG_EXPR:"@\\w+(?: \\w*)?",
        HASH_TAG_EXPR:"#\\w+",
        USD_TAG_EXPR:"\\"+currencyvalue+"(?:(?:\\d{1,3}(?:\\,\\d{3})+)|(?:\\d+))(?:\\.\\d{2})?",

        filter_options:function(a,c){
            for(var d=[],e=0;e<a.length;e++){
                var f=a[e].label.toLowerCase(),b=c.toLowerCase();
                b.length<=f.length&&f.indexOf(b)==0&&d.push(a[e])
            }
            return d
        },
        sort_options:function(a){
            return a.sort(function(c,d){
                c=c.label.toLowerCase();
                d=d.label.toLowerCase();
                if(c>
                    d)return 1;
                else if(c<d)return-1;
                return 0
            })
        }
    };
    (function(a){
        function c(b,g,h){
            b=b.substring(h||0).search(g);
            return b>=0?b+(h||0):b
        }
        function d(b){
            return b.replace(/[-[\]{}()*+?.,\\^$|#\s]/g,"\\$&")
        }
        function e(b,g,h){
            var j={};

            for(tok in g)if(h&&h[tok]){
                var k={},p={};

                for(key in h[tok]){
                    var s=h[tok][key].value,o=h[tok][key].label,l=d(tok+o),q=["(?:^(",")$|^(",")\\W|\\W(",")\\W|\\W(",")$)"].join(l),u=0;
                    for(q=new RegExp(q,"gm");(u=c(b.val(),q,u))>-1;){
                        var v=p[u]?p[u]:null;

                        if(!v||k[v].length<o.length)p[u]=s;
                        k[s]=o;
                        u+=o.length+1
                    }
                }
                for(u in p)j[tok+p[u]]=
                    tok
            }else{
                k=null;
                for(q=new RegExp("("+g[tok]+")","gm");k=q.exec(b.val());)j[k[1]]=tok
            }
            b=[];
            for(l in j)b.push(l);return b
        }
        var f={
            "@":tagmate.USER_TAG_EXPR,
            "#":tagmate.HASH_TAG_EXPR,
            $:tagmate.USD_TAG_EXPR,
            "\u00a3":tagmate.GBP_TAG_EXPR
        };

        a.fn.extend({
            getTags:function(b,g){
                var h=a(this);
                b=b||h.data("_tagmate_tagchars");
                g=g||h.data("_tagmate_sources");
                return e(h,b,g)
            },
            tagmate:function(b){
                function g(o,l,q){
                    for(l=new RegExp("["+l+"]");q>=0&&!l.test(o[q]);q--);
                    return q
                }
                function h(o){
                    var l=o.val(),q=o.getSelection(),
                    u=-1;
                    o=null;
                    for(tok in s.tagchars){
                        var v=g(l,tok,q.start);
                        if(v>u){
                            u=v;
                            o=tok
                        }
                    }
                    l=l.substring(u+1,q.start);
                    if((new RegExp("^"+s.tagchars[o])).exec(o+l))return o+l;
                    return null
                }
                function j(o,l,q){
                    var u=o.val(),v=o.getSelection();
                    v=g(u,l[0],v.start);
                    var z=u.substr(0,v);
                    u=u.substr(v+l.length);
                    o.val(z+l[0]+q+u);
                    u=v+q.length+1;
                    o.setSelection(u,u);
                    s.replace_tag&&s.replace_tag(l,q)
                }
                function k(o,l){
                    l=tagmate.sort_options(l);
                    for(var q=0;q<l.length;q++){
                        var u=l[q].label,v=l[q].image;
                        q==0&&o.html("");
                        var z="<span>"+
                            u+"</span>";
                        if(v)z="<img src='"+v+"' alt='"+u+"'/>"+z;
                        u=s.menu_option_class;
                        if(q==0)u+=" "+s.menu_option_active_class;
                        o.append("<div class='"+u+"'>"+z+"</div>")
                    }
                }
                function p(o,l){
                    var q=l=="down"?":first-child":":last-child",u=l=="down"?"next":"prev";
                    l=o.children("."+s.menu_option_active_class);
                    if(l.length==0)l=o.children(q);
                    else{
                        l.removeClass(s.menu_option_active_class);
                        l=l[u]().length>0?l[u]():l
                    }
                    l.addClass(s.menu_option_active_class);
                    u=o.children();
                    var v=Math.floor(a(o).height()/a(u[0]).height())-
                        1;
                    if(a(o).height()%a(u[0]).height()>0)v-=1;
                    for(q=0;q<u.length&&a(u[q]).html()!=a(l).html();q++);
                    q>v&&q-v>=0&&q-v<u.length&&o.scrollTo(u[q-v])
                }
                var s={
                    tagchars:f,
                    sources:null,
                    capture_tag:null,
                    replace_tag:null,
                    menu:null,
                    menu_class:"tagmate-menu",
                    menu_option_class:"tagmate-menu-option",
                    menu_option_active_class:"tagmate-menu-option-active"
                };

                return this.each(function(){
                    function o(){
                        v.hide();
                        var B=h(l);
                        if(B){
                            var F=B[0],n=B.substr(1),m=l.getSelection(),y=g(l.val(),F,m.start);
                            m.start-y<=B.length&&function(A){
                                if(typeof s.sources[F]===
                                    "object")A(tagmate.filter_options(s.sources[F],n));else typeof s.sources[F]==="function"?s.sources[F]({
                                    term:n
                                },A):A()
                            }(function(A){
                                if(A&&A.length>0){
                                    k(v,A);
                                    v.css("top",l.outerHeight()-1+"px");
                                    v.show();
                                    for(var D=l.data("_tagmate_sources"),E=0;E<A.length;E++){
                                        for(var K=false,L=0;!K&&L<D[F].length;L++)K=D[F][L].value==A[E].value;
                                        K||D[F].push(A[E])
                                    }
                                }
                                B&&s.capture_tag&&s.capture_tag(B)
                            })
                        }
                    }
                    b&&a.extend(s,b);
                    var l=a(this);
                    l.data("_tagmate_tagchars",s.tagchars);
                    var q={};

                    for(var u in s.sources)q[u]=[];l.data("_tagmate_sources",
                    q);
                    var v=s.menu;
                    if(!v){
                        v=a("<div class='"+s.menu_class+"'></div>");
                        l.after(v)
                    }
                    l.offset();
                    v.css("position","absolute");
                    v.hide();
                    var z=false;
                    a(l).unbind(".tagmate").bind("focus.tagmate",function(){
                        o()
                    }).bind("blur.tagmate",function(){
                        setTimeout(function(){
                            v.hide()
                        },300)
                    }).bind("click.tagmate",function(){
                        o()
                    }).bind("keydown.tagmate",function(B){
                        if(v.is(":visible"))if(B.keyCode==40){
                            p(v,"down");
                            z=true;
                            return false
                        }else if(B.keyCode==38){
                            p(v,"up");
                            z=true;
                            return false
                        }else if(B.keyCode==13){
                            B=v.children("."+
                                s.menu_option_active_class).text();
                            var F=h(l);
                            if(F&&B){
                                j(l,F,B);
                                v.hide();
                                z=true;
                                return false
                            }
                        }else if(B.keyCode==27){
                            v.hide();
                            z=true;
                            return false
                        }
                    }).bind("keyup.tagmate",function(){
                        if(z){
                            z=false;
                            return true
                        }
                        o()
                    });
                    a("."+s.menu_class+" ."+s.menu_option_class).die("click.tagmate").live("click.tagmate",function(){
                        var B=a(this).text(),F=h(l);
                        j(l,F,B);
                        v.hide();
                        z=true;
                        return false
                    })
                })
            }
        })
    })(jQuery);

</script>