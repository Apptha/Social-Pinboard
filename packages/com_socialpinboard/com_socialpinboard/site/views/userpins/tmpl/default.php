<?php
/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component userpins view
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');

$app = JFactory::getApplication();
$categories = $this->categories;
$pins = $this->pins;
$model = & $this->getModel('userpins');
$user_res = $model->getUserprofile();

$user = & JFactory::getUser();
$boards = $this->boards;
?>
<input type="hidden" id="offset" value="20"/>
<input type="hidden" id="minfilter" value=""/>
<input type="hidden" id="offsetaddno" value="20"/>
<div id="container">

    <?php
    if (JRequest::getVar('offset')) {
        ob_clean();
    }

    if (count($pins) != 0) {
    ?>
        <script type="text/javascript">
            var resizecount=0;
            function window_on_resize(){
                var max_width;
                if(resizecount==0)
                {
                    max_width=document.body.clientWidth-17;
                    resizecount=resizecount+1;
                }else
                {
                    max_width=document.body.clientWidth;
                }
                var pin_margin=0,pin_width=226;
                var pins=Math.floor((max_width+pin_margin)/(pin_width+pin_margin));
                var row_width=pins*pin_width+(pins-1)*pin_margin;
                var diff_width=max_width-row_width;
                var row_head_width = row_width-10;
                $('.row').css({'width':row_width+'px'});
                var fix_width=216;
                $('.fix').css({'width':(row_width-fix_width)+'px'});
                $(".mainbody.fix #container").css({'width':(row_width-fix_width)+'px'});
                $('.row_head').css({'width':row_head_width+'px','padding-right':10+'px'});
                if( window.nacmain_fixed_position){
                    $('#nacmain').css({'left':$('.head').offset().left+'px'});
                }


            }
            window_on_resize();

        </script>
<?php
        foreach ($pins as $arrPins) {
            if ($user->get('id') != 0) {
                $like_res = $model->getLikes($arrPins->pin_id, $user->id);
            }
?>
            <div class="pin" id="pin_div_<?php echo $arrPins->pin_id; ?>">
                <div class="pic  pic_show_functional">

                    <a href="index.php?option=com_socialpinboard&view=pin&pinid=<?php echo $arrPins->pin_id; ?>" class="PinImage ImgLink" rel="facebox" onclick="popup();">
<?php $src_path = JURI::base() . "images/socialpinboard/" . $arrPins->pin_image; ?>
                        <img src="<?php echo $src_path; ?>" alt="YUMMY" class="pin_pic_img_a" >
            </a>

            <div class="btns">

                <div class="btn-pinlist btn-like" id="likebtn<?php echo $arrPins->pin_id; ?>">
<?php
                if ($user->get('id') != 0) {
                    if ($like_res == 1) {
                        $likestyle = 'display:none';
                        $unlikestyle = 'display:block';
                    } else {
                        $unlikestyle = 'display:none';
                        $likestyle = 'display:block';
                    }
                    $userid = $user->id;
                } else {
                    $userid = 0;
                    $likestyle = 'display:block';
                    $unlikestyle = 'display:none';
                } ?>
                    <a href="javascript:void(0)" onclick="getlike(<?php echo $arrPins->pin_id; ?>,<?php echo $userid; ?>,<?php echo $flag = 0; ?>)" title="Like" id="like<?php echo $arrPins->pin_id; ?>" style="<?php echo $likestyle; ?>"><?php echo JText::_('COM_SOCIALPINBOARD_LIKE'); ?></a>
                    <a href="javascript:void(0)" onclick="getlike(<?php echo $arrPins->pin_id; ?>,<?php echo $userid; ?>,<?php echo $flag = 1; ?>)" title="Like" id="unlike<?php echo $arrPins->pin_id; ?>" style="<?php echo $unlikestyle; ?>"><?php echo JText::_('COM_SOCIALPINBOARD_UNLIKE'); ?></a>
                </div>

                <div class="btn-pinlist btn-repin">
                    <a href="javascript:void(0)" onclick="getpin(<?php echo $arrPins->pin_id; ?>,'<?php echo JURI::base(); ?>',<?php echo $userid; ?>)" title="Repin" id="showrepindiv<?php echo $arrPins->pin_id; ?>"><?php echo JText::_('COM_SOCIALPINBOARD_REPIN'); ?></a>
                </div>
                <div class="btn-pinlist btn-comment">
                    <a href="javascript:void(0)" onclick="comment(<?php echo $arrPins->pin_id; ?>)"  title="Comment" id="comment<?php echo $arrPins->pin_id; ?>"><?php echo JText::_('COM_SOCIALPINBOARD_COMMENT'); ?></a>
                </div>
            </div>

        </div>

        <div class="text"><?php echo $arrPins->pin_description; ?></div>
        <div class="statistics">

            <span id="likescountspan<?php echo $arrPins->pin_id; ?>" ><?php
                    if ($arrPins->pin_likes_count != 0) {
                        echo $arrPins->pin_likes_count . ' ' . JText::_('COM_SOCIALPINBOARD_LIKES');
                    } ?> </span>


                <span id="commentscountspan<?php echo $arrPins->pin_id; ?>" ><?php
                    if ($arrPins->pin_comments_count != 0) {
                        echo $arrPins->pin_comments_count . ' ' . JText::_('COM_SOCIALPINBOARD_COMMENTS');
                    }
?></span>

                <span id="repincountspan<?php echo $arrPins->pin_id; ?>"" ><?php
                    if ($arrPins->pin_repin_count != 0) {
                        echo $arrPins->pin_repin_count . ' ' . JText::_('COM_SOCIALPINBOARD_REPINS');
                    }
?> </span>

          </div>
          <div class="comments" id="commentDiv<?php echo $arrPins->pin_id; ?>">
              <ul class="c-list">
                  <li>
                      <a href="<?php echo $arrPins->username; ?>">


                          <img height="30px" src="<?php echo JURI::base() . 'images/socialpinboard/avatars/' . $arrPins->user_image; ?>" title="<?php echo $arrPins->username; ?>" id='photoUrl'
                               alt="<?php echo $arrPins->username; ?>" width="30px" class="avatar"/>

                      </a>

                      <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=boarddisplay&uid=' . $arrPins->pin_user_id); ?>"><span class="user"><?php echo $arrPins->first_name . " " . $arrPins->last_name; ?></span></a> <?php echo JText::_('COM_SOCIALPINBOARD_ONTO'); ?> <a href="/<?php echo $arrPins->username; ?>/my-collection/"><span><?php echo $arrPins->board_name; ?></span></a>

                    </li>
                <?php
                    $comment_res = $model->getComments($arrPins->pin_id);
                    if ($comment_res != '') {

                        foreach ($comment_res as $comment) {
                ?>
                            <li onmouseover="className='current'" onmouseout="className='span'" id="homecommentli<?php echo $comment->pin_comments_id; ?>">

                                <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=boarddisplay&uid=' . $comment->pin_user_comment_id); ?>">
                                    <img height="30px" src="<?php echo JURI::base() . 'images/socialpinboard/avatars/' . $comment->user_image; ?>" title="<?php echo $arrPins->username; ?>" id='photoUrl'
                                         alt="<?php echo $arrPins->username; ?>" width="30px" class="avatar"/>

                                </a>
                                <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=boarddisplay&uid=' . $comment->pin_user_comment_id); ?>">
                                    <span class="user"><?php echo $arrPins->first_name . " " . $arrPins->last_name; ?></span>
                                </a>
                                &nbsp;
                                <span><?php echo stripslashes($comment->pin_comment_text); ?></span>
                                <span class="delete-comment" style="display:none"><a href="javascript:void(0)" onclick="deleteComment('333081','779188')">X</a></span>
                            </li>
<?php }
                    } ?>
                </ul>
                <div class="write homecommentwrite" id="writecomment<?php echo $arrPins->pin_id; ?>" style="display:none">

                    <div class="newcomment">

                        <img height="30px" src="<?php echo JURI::base() . 'images/socialpinboard/avatars/' . $comment->user_image; ?>" title="<?php echo $arrPins->username; ?>" id='photoUrl'
                             alt="<?php echo $arrPins->username; ?>" width="30px" class="avatar"/>

                        <p class="input" id="add-comment">
                            <input type="text" id="commentContent<?php echo $arrPins->pin_id; ?>" value="Add a comment:" name="content"
                               onfocus="if (value == 'Add a comment:') {
                       value = ''
                   }"
                               onblur="if (value == '') {
                   value = 'Add a comment:'
               }"  maxlength="200"/>
                        <a href="javascript:void(0)"
                           class="button" onclick="doHomeComment(<?php echo $arrPins->pin_id; ?>,'<?php echo $user_res[0]->username; ?>','<?php echo $user_res[0]->user_image; ?>','<?php echo JRoute::_('index.php?option=com_socialpinboard&view=boarddisplay&uid=' . $user->id); ?>')"><?php echo JText::_('COM_SOCIALPINBOARD_COMMENT'); ?></a>
                    </p>
                    <ul class="drop-result"  id="friends"></ul>
                    <input type="hidden" id="friendName" value="" />
                    <script type="text/javascript">userAutoTips({id:'commentContent<?php echo $arrPins->pin_id; ?>',url:'/ajax/mentions'});</script>
                </div>

            </div>
        </div>
    </div>

<?php
                    $lastPinid = $arrPins->pin_id;
                }
?>
                <script type="text/javascript" charset="utf-8">
             latestPinId='<?php echo $lastPinid; ?>';

                </script>
<?php if (!JRequest::getVar('offset')) { ?>
                </div>
                 <div class="loading" style="display:none">
                    <div class="pinload"><?php echo JText::_('COM_SOCIALPINBOARD_FETCHING_PINS'); ?></div>
                </div>
                <script type="text/javascript">

                $("#conversations").css({'font-weight':'bold'});
                   var hasMorePins = true;
                  var $container = $('#container');
                $container.masonry({
                // options
                itemSelector : '.pin',
                 // isFitWidth : true,
                columnWidth : 226
                });
                // ajax refresh lock
                var locked = false;
                var res;

                function loadPins() {
                if(locked==false){
                    locked = true;

                    $.ajax({
                        type: "POST",
                        url: window.location.href,
                        data: 'offset=' + $('#offset').val() + '&offsetaddno='+ $('#offsetaddno').val(),
                        success: function(res) {

                            if(res != 'error') {
                                var $items = $(res);
                                //$container.append($items).imagesLoaded(function(){$container.masonry('appended', $items)});
                                $container.append( $items ).masonry( 'appended', $items );

                            }


                            if(!res.indexOf('<p class="nomorepin">No More Pins</p>')) {

                                hasMorePins = false;
                                $('.loading div').text("No More Pins");
                                $('.loading').hide();
                            } else {


                                $('.loading').hide();
                            }


                            $('#offset').val(parseInt($('#offset').val()) + parseInt($('#offsetaddno').val()));

                            // unlock ajax refresh
                            locked=false;

                            //init_pic_functional();
                        }
                    });
                }
                }

                $(document).ready(function() {
                window.onscroll = function()
                {
                    var scrollpos = getScrollingPosition();
                    if(scrollpos[1]>44)
                    {
                        document.getElementById('Nag').className = 'Nag fixed';
                        document.getElementById('CategoriesBar').className = 'fixed';
                    }
                    else
                    {
                        document.getElementById('Nag').className = 'Nag';
                        document.getElementById('CategoriesBar').className = '';

                    }
                }

                });
                var temp = -1

                $(window).scroll(function() {
                var scrollHeight =$(window).scrollTop();

                if(scrollHeight >= ($(document).height()/2)) {

                    if(hasMorePins && !locked) {

                        $('.loading').show();
                        loadPins();
                    }
                    temp = scrollHeight;
                }
                });
                function comment(pinId) {

                if($("#writecomment"+pinId).css('display')=="none"){
                    $(".homecommentwrite").hide();
                    $("#writecomment"+pinId).toggle();
                }else{
                    $(".homecommentwrite").hide();
                }
                $("#commentContent"+pinId).focus();
                $("#commentContent"+pinId).keypress(function(e){
                    var key = window.event ? e.keyCode:e.which;
                    if(key==13){
                        doHomeComment(pinId);
                        $("#commentContent"+pinId).blur();
                    }
                });
                $('#container').masonry( 'reload' );


                }
                function doHomeComment(pinId,userName,userImage,userUrl) {

                var comment = $("#commentContent"+pinId).val().replace(/^\s+|\s+$/g,"");
                if($("#commentContent"+pinId).val()!="Add a comment..."&&comment!=""){
                    $.ajax({
                        type:"POST",
                        url:"?option=com_socialpinboard&view=ajaxcontrol&tmpl=component&task=getcommentinfo",
                        data:{'pin_id':pinId,"comment":comment},
                        success:function(message) {
                            $("#commentContent"+pinId).val("Add a comment...");
                            if(message != "error") {

                                var message1 ='<li><a href="'+userUrl+'">';
                                message1 += '<img height="30px" src="<?php echo JURI::base() ?>images/socialpinboard/avatars/'+userImage+'" title="'+userName+'" id="photoUrl" alt="'+userName+'" width="30px" class="avatar"></a>';
                                message1 += '<a href="'+userUrl+'"><span class="user">'+userName+'</span></a>';
                                message1+='<span>&nbsp;'+message+'</span>'
                                '<span class="delete-comment" style="display:none"><a href="javascript:void(0)"></a></span></li>';
                                $('#commentDiv' + pinId + ' ul').append(message1);
                                $("#commentscountspan"+pinId).show();
                                scr('#container').masonry( 'reload' );
                                var span;
                                var count = 0;
                                if( $("#commentscountspan"+pinId).text()){

                                    count =parseInt($("#commentscountspan"+pinId).text().substring(0,$("#commentscountspan"+pinId).text().indexOf(" ")))+1;
                                    span = count+" Comments ";
                                }else{
                                    span= "1 Comment ";
                                }
                                $("#commentscountspan"+pinId).text(span);

                            }
                        }
                    });
                }
                }


                </script>
<?php
                }
            } else {
                echo '<p class="nomorepin">No More Pins</p>';
            }
?>