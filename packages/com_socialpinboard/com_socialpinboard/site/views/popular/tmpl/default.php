<?php
/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component popular view
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');

$app = JFactory::getApplication();
$config = JFactory::getConfig();
$pins = $this->pins;
$model = $this->getModel('popular');
$user_res = $this->user_res;
$user = JFactory::getUser();
$getCurrencySymbol =  $this->getCurrencySymbol;
$u_agent = $_SERVER['HTTP_USER_AGENT'];
$ub = '';
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_socialpinboard/css/facebox.css');
$document->addStyleSheet('components/com_socialpinboard/css/style.css');
$document->addStyleSheet('components/com_socialpinboard/css/reset.css');
$document->addStyleSheet('components/com_socialpinboard/css/pinboard.css');
$document->addScript('https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js');
$document->addScript('components/com_socialpinboard/javascript/jquery.ui.core.js');
$document->addScript('components/com_socialpinboard/javascript/chrome.js');
$document->addScript('components/com_socialpinboard/javascript/facebox.js');
$document->addScript('components/com_socialpinboard/javascript/scroll/jquery.isotope.min.js');
$document->addScript('components/com_socialpinboard/javascript/scroll/jquery.infinitescroll.min.js');

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
<script>
    var scr = jQuery.noConflict(); 
</script>

<div id="container">
<?php
if (count($pins) != 0) {
    if (!JRequest::getVar('page')) {

        $modules = JModuleHelper::getModules('socialpinboard_activities');
        foreach ($modules as $module) {
            echo JModuleHelper::renderModule($module);
        }
    }
?>

    <?php
    $pinid = '';
    foreach ($pins as $arrPins) {
        if ($arrPins->pin_id != $pinid) {
            $pinid = $arrPins->pin_id;

            if ($user->get('id') != 0) {
                $like_res = $model->getLikes($arrPins->pin_id, $user->id);
            }
    ?>
            <div class="pin" id="pin_div_<?php echo $arrPins->pin_id; ?>">
                <div class="pic  pic_show_functional">


<?php
            if ($arrPins->link_type == 'youtube' || $arrPins->link_type == 'vimeo') {
                $src_path = $arrPins->pin_image;
            } else {
                $src_path = JURI::base() . "images/socialpinboard/pin_medium/" . rawurlencode($arrPins->pin_image);
            }
            if (preg_match('/MSIE/i', $u_agent)) {
                if ($arrPins->link_type == 'youtube' || $arrPins->link_type == 'vimeo') {
?>
                    <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=pin&pinid=' . $arrPins->pin_id); ?>" class="PinImage ImgLink"  onclick="popup();" style="position: absolute;z-index: 1;top: 25%;left: 40%;"><img src="<?php echo JURI::base() . "components/com_socialpinboard/images/play_btn.png"; ?>" width="50" height="50" alt="" class="play_button" /></a>
            <?php //if ($arrPins->gift == '1') {
 ?>
<!--                    <strong class="PriceContainer"><strong class="price">-->
                <?php //echo $arrPins->price; ?>
<!--                        </strong></strong>-->
                <?php //} ?>
            <?php } ?>

                <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=pin&pinid=' . $arrPins->pin_id); ?>" class="PinImage ImgLink"  onclick="popup();"><img src="<?php echo $src_path; ?>" alt="" class="PinImageImg" />
            <?php if ($arrPins->gift == '1') {
 ?> <strong class="PriceContainer"><strong class="price"><?php echo $getCurrencySymbol.$arrPins->price; ?></strong></strong><?php } ?>
                </a>

<?php
            } else {
                if ($arrPins->link_type == 'youtube' || $arrPins->link_type == 'vimeo') {
?>
                    <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=pin&pinid=' . $arrPins->pin_id); ?>" class="PinImage ImgLink facebox" onclick="popup();" style="position: absolute;z-index: 1;top: 25%;left: 40%;"><img src="<?php echo JURI::base() . "components/com_socialpinboard/images/play_btn.png"; ?>" width="50" height="50" alt="" class="play_button" /></a>
            <?php //if ($arrPins->gift == '1') {
 ?>
<!--                    <strong class="PriceContainer"><strong class="price">-->
                <?php //echo $arrPins->price; ?>
<!--                        </strong></strong>-->
                <?php// } ?>
            <?php } ?>
                <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=pin&pinid=' . $arrPins->pin_id); ?>" class="PinImage ImgLink facebox" onclick="popup();"><img src="<?php echo $src_path; ?>" alt="" class="PinImageImg"/>
<?php if ($arrPins->gift == '1') { ?> <strong class="PriceContainer"><strong class="price"><?php echo $getCurrencySymbol.$arrPins->price; ?></strong></strong><?php } ?>
                </a>
<?php
            }
?>

            <div class="btns">

<?php
            $userid = $user->id;
            if ($user->get('id') != 0) {
                if ($like_res == 1 && $arrPins->pin_user_id != $userid) {
                    $likestyle = 'display:none';
                    $unlikestyle = 'display:block';
                } else if ($user->get('id') != 0 && $arrPins->pin_user_id == $userid) {

                    $likestyle = 'display:block';
                } else {
                    $unlikestyle = 'display:none';
                    $likestyle = 'display:block';
                }
            } else {
                $userid = 0;
                $likestyle = 'display:block';
                $unlikestyle = 'display:none';
            }
?>

                <div class="btn-pinlist btn-repin">
                    <input type="button" onclick="getpin(<?php echo $arrPins->pin_id; ?>,'<?php echo JURI::base(); ?>',<?php echo $userid; ?>)" title="<?php echo JText::_('COM_SOCIALPINBOARD_REPIN'); ?>" id="showrepindiv<?php echo $arrPins->pin_id; ?>" value="<?php echo JText::_('COM_SOCIALPINBOARD_REPIN'); ?>" class="report_repin" />
                </div>
<?php
                if ($arrPins->pin_user_id != $userid) {
?>
                    <div class="btn-pinlist btn-like" id="likebtn<?php echo $arrPins->pin_id; ?>">

                        <input type="button"  class="pin_like" onclick="getlike(<?php echo $arrPins->pin_id; ?>,<?php echo $userid; ?>,<?php echo $flag = 0; ?>)" title="<?php echo JText::_('COM_SOCIALPINBOARD_LIKE'); ?>" id="like<?php echo $arrPins->pin_id; ?>" style="<?php echo $likestyle; ?>"  value="<?php echo JText::_('COM_SOCIALPINBOARD_LIKE'); ?>" />
                        <input type="button"  class="pin_unlike" onclick="getlike(<?php echo $arrPins->pin_id; ?>,<?php echo $userid; ?>,<?php echo $flag = 1; ?>)" title="<?php echo JText::_('COM_SOCIALPINBOARD_UNLIKE'); ?>" id="unlike<?php echo $arrPins->pin_id; ?>" style="<?php echo $unlikestyle; ?>"  value="<?php echo JText::_('COM_SOCIALPINBOARD_UNLIKE'); ?>" />
                    </div>
<?php
                } elseif ($arrPins->pin_user_id == $userid) {
?>
                    <div class="btn-pinlist btn-like btn-edit" >

                        <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=pinedit&pinId=' . $arrPins->pin_id); ?>" class="pin_edit"   title="<?php echo JText::_('COM_SOCIALPINBOARD_EDIT'); ?>" id="like<?php echo $arrPins->pin_id; ?>" style="<?php echo $likestyle; ?>"><?php echo JText::_('COM_SOCIALPINBOARD_EDIT'); ?></a>

                    </div>
<?php
                }
?>

                <div class="btn-pinlist btn-comment">
                    <input type="button" onclick="comment(<?php echo $arrPins->pin_id; ?>,<?php echo $userid ?>)"  title="<?php echo JText::_('COM_SOCIALPINBOARD_COMMENT'); ?>" id="comment<?php echo $arrPins->pin_id; ?>" value="<?php echo JText::_('COM_SOCIALPINBOARD_COMMENT'); ?>" />
                </div>
            </div>




        </div>

        <p class="description"> <?php
                if (str_word_count($arrPins->pin_description) < 250) {
                    echo $arrPins->pin_description;
                } else {
                    $pin_description = substr($arrPins->pin_description, 0, 250);
                    echo $pin_description . ' .... ';
?>
                    <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=pin&pinid=' . $arrPins->pin_id); ?>">Read more </a><?php
                }
?></p>
            <div class="statistics">

                <span id="likescountspan<?php echo $arrPins->pin_id; ?>" >
<?php
                $like = '';
                if ($arrPins->pin_likes_count != 0 && $arrPins->pin_likes_count == 1) {
                    $like = $arrPins->pin_likes_count . ' ' . JText::_('COM_SOCIALPINBOARD_LIKE');
                } else if ($arrPins->pin_likes_count > 1) {
                    $like = $arrPins->pin_likes_count . ' ' . JText::_('COM_SOCIALPINBOARD_LIKES');
                }
                echo $like;
?> </span>


            <span id="commentscountspan<?php echo $arrPins->pin_id; ?>" ><?php
                if ($arrPins->pin_comments_count != 0 && $arrPins->pin_comments_count == 1) {
                    echo $arrPins->pin_comments_count . ' ' . JText::_('COM_SOCIALPINBOARD_COMMENT');
                } else if ($arrPins->pin_comments_count > 1) {
                    echo $arrPins->pin_comments_count . ' ' . JText::_('COM_SOCIALPINBOARD_COMMENTS');
                }
?></span>

            <span id="repincountspan<?php echo $arrPins->pin_id; ?>" ><?php
                if ($arrPins->pin_repin_count == 1) {
                    echo $arrPins->pin_repin_count . ' ' . JText::_('COM_SOCIALPINBOARD_REPIN');
                } else if ($arrPins->pin_repin_count > 1) {
                    echo $arrPins->pin_repin_count . ' ' . JText::_('COM_SOCIALPINBOARD_REPINS');
                }
?> </span>
            <i class="loading_grid" id="loading_grid_<?php echo $arrPins->pin_id; ?>" style="display:none;"></i>
        </div>

        <div class="convo attribution clearfix">
            <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=boarddisplay&uid=' . $arrPins->pin_user_id); ?>">
<?php
                if ($arrPins->user_image != '') {
?>


                    <img height="30px" src="<?php echo JURI::base() . 'images/socialpinboard/avatars/' . $arrPins->user_image; ?>" title="<?php echo $arrPins->first_name . ' ' . $arrPins->last_name; ?>" id='photoUrl'
                         alt="<?php echo $arrPins->first_name . ' ' . $arrPins->last_name; ?>" width="30px" class="ImgLink thumb-img"/>
<?php
                } else {
?>
                <img src="<?php echo JURI::Base(); ?>/components/com_socialpinboard/images/no_user.jpg" title="<?php echo $arrPins->first_name . ' ' . $arrPins->last_name; ?>" alt="<?php echo $arrPins->first_name . ' ' . $arrPins->last_name; ?>" height="30px" width="30px" class="ImgLink thumb-img" />
                     <?php
                 }
                     ?>       </a>

             <div class="board_grid board_content"> <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=boarddisplay&uid=' . $arrPins->pin_user_id); ?>"><?php echo $arrPins->first_name . " " . $arrPins->last_name; ?></a> <?php echo JText::_('COM_SOCIALPINBOARD_ONTO'); ?> <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=boardpage&bId=' . $arrPins->board_id); ?>"><?php echo $arrPins->board_name; ?></a></div>

         </div>

         <div class="comments clearfix" id="commentDiv<?php echo $arrPins->pin_id; ?>">
             <ul>
<?php
                 $comment_res = $model->getComments($arrPins->pin_id);


                 if ($comment_res != '') {
                     $i = 0;
                     $count = count($comment_res);
                     $new_flag = 0;
                     foreach ($comment_res as $comment) {
                         if ($i < 6) {
?><li>
                                 <div class="comment clearfix ">

                                     <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=boarddisplay&uid=' . $comment->pin_user_comment_id); ?>">
<?php
                             if ($comment->user_image == '') {
?>
                                 <img height="30px" src="<?php echo JURI::base() . 'components/com_socialpinboard/images/no_user.jpg' . $comment->user_image; ?>" title="<?php echo $comment->username; ?>" id='photoUrl'
                                      alt="<?php echo $comment->first_name . " " . $comment->last_name; ?>" width="30px" class="ImgLink thumb-img" />
<?php
                             } else {
?>
                            <img height="30px" src="<?php echo JURI::base() . 'images/socialpinboard/avatars/' . $comment->user_image; ?>" title="<?php echo $comment->username; ?>" id='photoUrl'
                                 alt="<?php echo $comment->first_name . " " . $comment->last_name; ?>" width="30px" class="ImgLink thumb-img" />
<?php
                             }
?>
                        </a>
                        <div class="board_content board_grid">
                            <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=boarddisplay&uid=' . $comment->pin_user_comment_id); ?>">
<?php echo $comment->first_name . " " . $comment->last_name; ?>
                            </a>
                            <span>
<?php echo stripslashes($comment->pin_comment_text); ?> </span></div>

                     </div>
                 </li>
<?php
                             } else {
?>      <?php
                                 $new_flag = 1;
                                 break;
                             }
                             $i++;
                         }
                     }
?>
                 </ul>
                <?php
                     if ($new_flag == 1) {
                ?>

                         <div class="homecomment">
                             <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=pin&pinid=' . $arrPins->pin_id); ?>" >
                                 <span style="color: #999;">View All <span id="commentsspan<?php echo $arrPins->pin_id; ?>" ><?php
                         if ($arrPins->pin_comments_count != 0 && $arrPins->pin_comments_count == 1) {
                             echo $arrPins->pin_comments_count;
                         } else if ($arrPins->pin_comments_count > 1) {
                             echo $arrPins->pin_comments_count;
                         }
                ?></span>  comments </span>

                </a></div>
<?php } ?>
            <div class="write homecommentwrite" id="writecomment<?php echo $arrPins->pin_id; ?>" style="display:none">

                <div id ="add-comment" class="newcomment">

                    <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=boarddisplay&uid=' . $user->id); ?>" class="ImgLink">
<?php
                     if ($user_res[0]->user_image == '') {
?><img height="30px" src="<?php echo JURI::base() . 'components/com_socialpinboard/images/no_user.jpg'; ?>" title="<?php echo $user_res[0]->username; ?>" id='photoUrl'
                              alt="<?php echo $user_res[0]->username; ?>" width="30px" />
                        <?php
                     } else {
                        ?>
                        <img height="30px" src="<?php echo JURI::base() . 'images/socialpinboard/avatars/' . $user_res[0]->user_image; ?>" title="<?php echo $user_res[0]->username; ?>" id='photoUrl'
                             alt="<?php echo $user_res[0]->username; ?>" width="30px" />
<?php
                         }
?>

                    </a>
                    <textarea id="commentContent<?php echo $arrPins->pin_id; ?>" value="<?php echo JText::_('COM_SOCIALPINBOARD_ADD_A_COMMENT'); ?>" name="content"
                              onfocus="if (value == '<?php echo JText::_('COM_SOCIALPINBOARD_ADD_A_COMMENT'); ?>') { value = ''; }"
                              onblur="if (value == '') { value = '<?php echo JText::_('COM_SOCIALPINBOARD_ADD_A_COMMENT'); ?>'; }"  maxlength="200"></textarea>

                    <input type="button" class="button" onclick="doHomeComment(<?php echo $arrPins->pin_id; ?>,'<?php echo $user_res[0]->first_name; ?>','<?php echo $user_res[0]->last_name; ?>','<?php echo $user_res[0]->user_image; ?>','<?php echo JRoute::_('index.php?option=com_socialpinboard&view=boarddisplay&uid=' . $user->id); ?>')" value="<?php echo JText::_('COM_SOCIALPINBOARD_COMMENT'); ?>" />


                </div>

            </div>
            <div class="clear"></div>
        </div>
    </div>




<?php
                         }
                     }
?>

    <?php
                 } else if (count($pins) == 0) {
                     echo '<label id="login_error_msg" style="display: block;">' . JText::_('COM_SOCIALPINBOARD_SORRY_NO_PINS_POPULAR') . '</label>';
                 }
    ?>


             </div>
<?php
                 $querystring = "";
                 if (JRequest::getVar('category')) {
                     $category = str_replace("_", '&', JRequest::getVar('category'));
                     $querystring = "&category=$category";
                 } elseif (JRequest::getVar('popular')) {
                     $querystring = "&popular=JRequest::getVar('popular')";
                 } elseif (JRequest::getVar('gift')) {
                     $querystring = "&gift=JRequest::getVar('gift')";
                 }
?>
                 <nav id="page-nav">
                     <a id="navpage" href="<?php echo $this->baseurl ?>/index.php/component/socialpinboard/?view=popular<?php echo $querystring; ?>&page=1"></a>
                 </nav>



                 <script>
                     var scr = jQuery.noConflict();  scr(document).ready(function($){

                         scr('.facebox').facebox({
                             loadingImage : '<?php echo JURI::base(); ?>/components/com_socialpinboard/images/loading.gif',
                             closeImage   : '<?php echo JURI::base(); ?>/components/com_socialpinboard/images/closelabel.png',
                             currentUrl    :document.location.href
                         }
                     );

                         var $container = scr('#container');

                         /* $container.imagesLoaded(function(){
                             $container.masonry({
                                 itemSelector: '.pin',
                                 gutterWidth: 10,
                                 isFitWidth:true
                             });
                         });*/

                         $container.masonry({
                             itemSelector : '.pin',

                             isFitWidth: true,
                             isResizable: true,
                             columnWidth: 200,
                             gutterWidth: 40
                         });
                         $container.find('div.pin').filter('div.pin').each(function()
                         {
                             var pin = scr(this);

                             var pinHeight = pin.height();

                             function checkSize()
                             {
                                 var currHeight = pin.height();

                                 if (pinHeight != currHeight) {
                                     $container.masonry('reload', function(){
                                         pinHeight = currHeight;
                                         setTimeout(checkSize, 50);
                                     });
                                 } else {
                                     setTimeout(checkSize, 100);
                                 }
                             }

                             checkSize();
                         });
                         $container.infinitescroll({
                             navSelector  : '#page-nav',    // selector for the paged navigation
                             nextSelector : '#page-nav a',  // selector for the NEXT link (to page 2)
                             itemSelector : '#container div.pin',     // selector for all items you'll retrieve
                             // extraScrollPx: 500,
                             loading: {
                                 finishedMsg: 'No more pins to load.',
                                 img: '<?php echo JURI::base(); ?>/components/com_socialpinboard/images/loading.gif'
                             }
                         },
                         // trigger Masonry as a callback
                         function( newElements ) {
                             // hide new items while they are loading
                             var $newElems = scr( newElements ).css({ opacity: 0 });
                             // ensure that images load before adding to masonry layout
                             $newElems.imagesLoaded(function(){
                                 // show elems now they're ready
                                 $newElems.animate({ opacity: 1 });
                                 $container.masonry( 'appended', $newElems, true );


                                 scr(document).bind('beforeReveal.facebox', function() {
                                     scr("#facebox .content").empty();
                                 });
                                 scr('.facebox').facebox({
                                     loadingImage : '<?php echo JURI::base(); ?>/components/com_socialpinboard/images/loading.gif',
                                     closeImage   : '<?php echo JURI::base(); ?>/components/com_socialpinboard/images/closelabel.png',
                                     currentUrl    :document.location.href
                                 }
                             );

                             });
                         }
                     );

                     });





                     function comment(pinId,userId) {


                         if(userId==0)
                         {
                             window.open('?option=com_socialpinboard&view=people','_self');
                             return false;
                         }
                         if(scr("#writecomment"+pinId).css('display')=="none"){
                             scr(".homecommentwrite").hide();
                             scr("#writecomment"+pinId).toggle();
                         }else{
                             scr(".homecommentwrite").hide();
                         }
                         scr("#commentContent"+pinId).focus();

                         scr('#container').masonry( 'reload' );


                     }

                     function doHomeComment(pinId,firstName,lastName,userImage,userUrl) {

                         var comment = scr("#commentContent"+pinId).val().replace(/^\s+|\s+$/g,"");
                         if(scr("#commentContent"+pinId).val()!="<?php echo JText::_('COM_SOCIALPINBOARD_ADD_A_COMMENT'); ?>"&&comment!=""){
                             document.getElementById("loading_grid_"+pinId).style.display="block";
                             scr.ajax({
                                 type:"POST",
                                 url:"?option=com_socialpinboard&view=ajaxcontrol&tmpl=component&task=getcommentinfo",
                                 data:{'pin_id':pinId,"comment":comment},
                                 success:function(message) {
                                     document.getElementById("loading_grid_"+pinId).style.display="none";
                                     var obj = jQuery.parseJSON(message);
                                     var message = obj.comment;
                                     var user_comment_id=obj.comment_id;
                                     scr("#commentContent"+pinId).val('<?php echo JText::_('COM_SOCIALPINBOARD_ADD_A_COMMENT'); ?>');
                                     if(message != "error") {

                                         var message1 ='<li><div class="comment clearfix " ><a href="'+userUrl+'">';
                                         if(userImage=='')
                                         {
                                             message1 += '<img height="30px" src="<?php echo JURI::base() ?>components/com_socialpinboard/images/no_user.jpg" title="'+firstName+lastName+'" id="photoUrl" alt="'+firstName+lastName+'" width="30px" class="ImgLink thumb-img"></a>';

                                         }else
                                         {
                                             message1 += '<img height="30px" src="<?php echo JURI::base() ?>images/socialpinboard/avatars/'+userImage+'" title="'+firstName+lastName+'" id="photoUrl" alt="'+firstName+lastName+'" width="30px" class="ImgLink thumb-img"></a>';
                                         }

                                         message1 += '<p class="board_content"><a href="'+userUrl+'">'+firstName+' '+lastName+'</a>';
                                         message1+=' '+'<span>'+message+'</span></p></div></li>';

                                         scr('#commentDiv' + pinId + ' ul').append(message1);
                                         scr("#commentscountspan"+pinId).show();
                                         scr('#container').masonry( 'reload' );
                                         var span;
                                         var count = 0;
                                         if( scr("#commentscountspan"+pinId).text()){

                                             count =parseInt($("#commentscountspan"+pinId).text().substring(0,scr("#commentscountspan"+pinId).text().indexOf(" ")))+1;
                                             var counts =parseInt($("#commentsspan"+pinId).text().substring(0,scr("#commentscountspan"+pinId).text().indexOf(" ")))+1;
                                             span = count+" <?php echo JText::_('COM_SOCIALPINBOARD_COMMENTS') ?> ";
                                         }else{
                                             span= "1 <?php echo JText::_('COM_SOCIALPINBOARD_COMMENT') ?> ";
                        }
                        scr("#commentscountspan"+pinId).text(span);
                        scr("#commentsspan"+pinId).text(counts);

                    }
                }
            });
        }
    }
                 
</script>