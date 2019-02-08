<?php
/**
 * @name          : Joomla Social Pinboard
 * @version	  : 1.5.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component Pin view
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');
$document = JFactory::getDocument();

//$document->addStyleSheet('components/com_socialpinboard/css/style.css');
$document->addStyleSheet('templates/socialpinboard/html/com_socialpinboard/css/style.css');
$document->addStyleSheet('templates/socialpinboard/html/com_socialpinboard/css/pinboard.css');
$document->addStyleSheet('components/com_socialpinboard/css/reset.css');
//$document->addStyleSheet('components/com_socialpinboard/css/pinboard.css');
$document->addScript('components/com_socialpinboard/javascript/chrome.js');
$app = JFactory::getApplication();
$pinDetails = $this->pindetails;
$pinlikes = $pinDetails->pin_likes_count;
$boardPins = $this->boardpins;
$uploadpins = $this->uploadpins;
$bookpins = $this->bookpins;
$urlpins = $this->urlpins;
//print_r($urlpins);exit;
$user = JFactory::getUser();
$user_id = $user->id;
$crt_board = '';
$userfollow = "";
$user_result = $this->user_profile;
$result = $this->profile;
$pinnedpins = $this->pinnedpins;
if ($user_id) {
    $like_res = $this->like_details;
    $userfollow = $this->follow_user_details;
}


$current_pinboard = $pinDetails->pin_board_id;
if ($userfollow != "all" && $userfollow != "") {
    if (in_array($current_pinboard, $userfollow)) {
        $crt_board = "1";
    } else {

        $crt_board = "0";
    }
}
$comment_res = $this->comment_details;

if (empty($user_result[1])) {
    $user_res = $user_result[0]; //pinned by information
    $user_res1 = $user_result[0]; //commenting user information
} else if ($user_id == $user_result[0]->user_id) {
    $user_res = $user_result[1]; //pinned by information
    $user_res1 = $user_result[0]; //commenting user information
} else if ($user_result[1]->user_id == $user_id) {
    $user_res = $user_result[0]; //pinned by information
    $user_res1 = $user_result[1]; //commenting user information
}


$pinlikeuser = $this->pinLikeuser;
$repinuser = $this->repinuser;
$baseurl = JURI::base();
$repindetails = $this->repinBoard;
$pin_id = JRequest::getInt('pinid');
$link_type = $pinDetails->link_type;
$config = JFactory::getConfig();
$siteName = $config->get('sitename');
$strImgName = $pinDetails->pin_image;
$adsvalue = $this->topgooglead;
$bottomad = $this->bottomgooglead;
$document->setTitle($pinDetails->pin_description);
if ($link_type == 'youtube' || $link_type == 'vimeo') {
    $strImgPath = $strImgName;
    $strImgUrl = $strImgName;
} else {
    $strImgPath = JPATH_BASE . '/images/socialpinboard/pin_original/' . $strImgName;
    $strImgUrl = $baseurl . '/images/socialpinboard/pin_original/' . $strImgName;
}

$document = JFactory::getDocument();
$document->setMetaData("keywords", $pinDetails->board_name);
$document->setDescription($pinDetails->pin_description);

list($strImgOriWidth, $strImgOriHeight) = getimagesize($strImgPath);

if (!$pin_id) {

    JError::raiseError(404, implode('<br />', $errors));
    return false;
}

$boards = $this->boards;
$siteSettings = $this->siteSettings;
$getmobileBoards = $this->getmobileBoards; //Stores the details of the pin(Repin)
?>

<style type="text/css">
    .close_new{
        position: absolute;
        top: 11px;
        z-index: 999;
        left: 615px;
    }

    .PriceContainer{position: absolute;z-index: 2;top: 153px;left: 15px;width: 110px;height: 110px;overflow: hidden;}
    .price{position: absolute;z-index: 2;top: 44px;left: 0;width: 125px;height: 22px;padding: 8px 10px 5px 5px;text-align: center;font-size: 14px;color: #524D4D;text-shadow: 0 1px rgba(255, 255, 255, 1);font-weight: bold;background-color: #F2F0F0;overflow: hidden;-webkit-transform: rotate(-45deg);-moz-transform: rotate(-45deg);-o-transform: rotate(-45deg);-ms-transform: rotate(-45deg);}

</style>
<div  class="clearfix pinpage">
<?php if (!JRequest::getVar('page')) { ?>
        <div class="CloseupLeft" style="float: left;" id="boarddiv">
            <div class=" pinBoard WhiteContainer clearfix" id="sideboard">
                <h3><?php echo $pinDetails->board_name; ?></h3>
                <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=boardpage&bId=' . $pinDetails->pin_board_id) ?>" class="link ImgLink" style="float: left;">
<?php
    $i = 0;
    foreach ($boardPins as $arrPins) {
        if ($i == 9) {
            break;
        }
        if ($arrPins->link_type == 'youtube' || $arrPins->link_type == 'vimeo') {
            $srcPath = $arrPins->pin_image;
        } else {
            $srcPath = "images/socialpinboard/pin_thumb/" . rawurlencode($arrPins->pin_image);
        }
?>
                <img src="<?php echo $srcPath; ?>" alt="" >
                <?php $i++;
            } ?>
            </a>
            <div class="clearfix"></div>
            <div class="followBoard" >
                <?php if ($user_id != 0 && $pinDetails->pin_user_id == $user_id) {
 ?>
                    <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=boardedit&bidd=' . $pinDetails->pin_board_id) ?>" class="Button13 Button WhiteButton"><strong><?php echo JText::_('COM_SOCIALPINBOARD_EDIT'); ?></strong></a>
<?php } ?>
            </div>
        </div>

    </div>
<?php } ?>

<?php
            if (JRequest::getVar('page')) {
                ob_clean();
?>
                <div id="ColumnContainer" style="margin: 0px auto 0;" >
    <?php
            }
            $config = JFactory::getConfig();
            $siteName = $config->get('config.sitename');
    ?>
        <?php
            if ($pinDetails->link_type == 'youtube') {
                $url = $pinDetails->pin_url;
                $fbshare_image = $pinDetails->pin_image;
                $youtube_temp = explode('&', $url);
                foreach ($youtube_temp as $youtubes) {

                    if (strpos($youtubes, "v=") !== false) {
                        $youtube = substr($youtubes, strpos($youtubes, 'v=') + 2);
                    }
                }
        ?>
        <?php
            } elseif ($pinDetails->link_type == 'vimeo') {
                $url = $pinDetails->pin_url;
                $fbshare_image = $pinDetails->pin_image;
                $explodeVimeo = explode('/', $url);
                $image_url = parse_url($url);
                if ($explodeVimeo[2] == 'vimeo.com') {


                    $youtube = substr($image_url['path'], 1);
                }
            } else {
                $src_path = JURI::base() . "images/socialpinboard/pin_original/" . rawurlencode($pinDetails->pin_image);
                $fbshare_image = JURI::base() . "images/socialpinboard/pin_original/" . rawurlencode($pinDetails->pin_image);
                if ($pinDetails->pin_url != '') {
                    $url = $pinDetails->pin_url;
                    if (!strstr($pinDetails->pin_url, 'http') && !strstr($pinDetails->pin_url, 'https')) {
                        $url = "http://" . $pinDetails->pin_url;
                    }

                    $tab = 'target="_blank"';
                    $target = "_blank";
                } else {

                    $url = JRoute::_('index.php?option=com_socialpinboard&view=pin&pinid=' . $pin_id);
                    $tab = 'target="_self"';
                    $target = "_self";
                }
        ?>


        <?php } ?>


            <div class="WhiteContainer user_board " style="" id="pindiv">
                <div id="zoom" class="PinPinner">
                    <div id="PinPinner" style="text-align: left;">
                        <div class="floatleft" style="width: 75%;">
                            <div class="pin_pagetop_left">
                                <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=boarddisplay&uid=' . $user_res->user_id) ?>" id="PinnerImage" class="ImgLink">

<?php
            if ($user_res->user_image != '') {
?>


                                        <img src="<?php echo JURI::base() . 'images/socialpinboard/avatars/' . $user_res->user_image; ?>" alt="<?php echo $pinDetails->first_name . ' ' . $pinDetails->last_name; ?>" />
                                <?php
                            } else {
                                ?>
                                    <img src="<?php echo JURI::Base(); ?>/components/com_socialpinboard/images/no_user.jpg" title="<?php echo $pinDetails->first_name . ' ' . $pinDetails->last_name; ?>" alt="<?php echo $pinDetails->first_name . ' ' . $pinDetails->last_name; ?>" height="30px" width="30px" class="avatar" />
<?php
                            }
?>  
                            </a>
                        </div>
                        <div class="pin_pagetop_right">
                            <p class="grid_name" id="PinnerName"><a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=boarddisplay&uid=' . $user_res->user_id) ?>"><?php echo ucwords($user_res->first_name) . ' ' . $user_res->last_name; ?></a></p>

                            <div class="clear"></div>
                            <div id="Pindate">
                                <p id="PinnerStats" class="colorless">
<?php
                                $createddate = $pinDetails->created_date;

                                $str = strtotime(date($createddate));
                                $today = strtotime(date('Y-m-d H:i:s'));

                                // It returns the time difference in Seconds...
                                $time_differnce = $today - $str;

                                // To Calculate the time difference in Years...
                                $years = 60 * 60 * 24 * 365;

                                // To Calculate the time difference in Months...
                                $months = 60 * 60 * 24 * 30;

                                // To Calculate the time difference in Days...
                                $days = 60 * 60 * 24;

                                // To Calculate the time difference in Hours...
                                $hours = 60 * 60;

                                // To Calculate the time difference in Minutes...
                                $minutes = 60;

                                if (intval($time_differnce / $years) > 1) {
                                    $datediff = intval($time_differnce / $years) . ' ' . JText::_('COM_SOCIALPINBOARD_YEARS_AGO');
                                } else if (intval($time_differnce / $years) > 0) {
                                    $datediff = intval($time_differnce / $years) . ' ' . JText::_('COM_SOCIALPINBOARD_YEAR_AGO');
                                } else if (intval($time_differnce / $months) > 1) {
                                    $datediff = intval($time_differnce / $months) . ' ' . JText::_('COM_SOCIALPINBOARD_MONTHS_AGO');
                                } else if (intval(($time_differnce / $months)) > 0) {
                                    $datediff = intval(($time_differnce / $months)) . ' ' . JText::_('COM_SOCIALPINBOARD_MONTH_AGO');
                                } else if (intval(($time_differnce / $days)) > 1) {
                                    $datediff = intval(($time_differnce / $days)) . ' ' . JText::_('COM_SOCIALPINBOARD_DAYS_AGO');
                                } else if (intval(($time_differnce / $days)) > 0) {
                                    $datediff = intval(($time_differnce / $days)) . ' ' . JText::_('COM_SOCIALPINBOARD_DAY_AGO');
                                } else if (intval(($time_differnce / $hours)) > 1) {
                                    $datediff = intval(($time_differnce / $hours)) . ' ' . JText::_('COM_SOCIALPINBOARD_HOURS_AGO');
                                } else if (intval(($time_differnce / $hours)) > 0) {
                                    $datediff = intval(($time_differnce / $hours)) . ' ' . JText::_('COM_SOCIALPINBOARD_HOUR_AGO');
                                } else if (intval(($time_differnce / $minutes)) > 1) {
                                    $datediff = intval(($time_differnce / $minutes)) . ' ' . JText::_('COM_SOCIALPINBOARD_MINUTES_AGO');
                                } else if (intval(($time_differnce / $minutes)) > 0) {
                                    $datediff = intval(($time_differnce / $minutes)) . ' ' . JText::_('COM_SOCIALPINBOARD_MINUTE_AGO');
                                } else if (intval(($time_differnce)) > 1) {
                                    $datediff = intval(($time_differnce)) . ' ' . JText::_('COM_SOCIALPINBOARD_SECONDS_AGO');
                                } else {
                                    $datediff = ' ' . JText::_('COM_SOCIALPINBOARD_FEW_SECONDS_AGO');
                                }
                                echo JText::_('COM_SOCIALPINBOARD_PINNED') . ' ' . $datediff;
?>

                                    <?php
                                    $pinned_from = $pinDetails->pin_url;

                                    if ($pinned_from) {
                                        $pinned_parts = explode('/', $pinned_from);
                                        if (strstr($pinned_from, "http://")) {
                                            $pinned_from = $pinned_from;
                                        } else {

                                            $pinned_from = 'http://' . $pinned_from;
                                        }
                                    }

                                    $pinned_parts = explode('/', $pinned_from);
                                    if (strstr($pinned_from, "https://")) {
                                        $pinned_parts = $pinned_parts[2] . "//" . $pinned_parts[4];
                                        echo JText::_('COM_SOCIALPINBOARD_PINNED_FROM') . $pinned_parts;
                                    } else if ($pinned_parts[0] != '') {

                                        if ($pinDetails->pin_real_pin_id != '0') {
                                            echo JText::_('COM_SOCIALPINBOARD_REPINNED_BY_USER');
                                        } else {
                                            echo JText::_('COM_SOCIALPINBOARD_PINNED_FROM') . $pinned_parts[2];
                                        }
                                    } else if ($pinned_from != '') {
                                        if ($pinDetails->pin_real_pin_id != '' && $pinDetails->pin_real_pin_id != 0) {
                                            echo JText::_('COM_SOCIALPINBOARD_REPINNED_BY_USER');
                                        } else {
                                            echo JText::_('COM_SOCIALPINBOARD_PINNED_FROM') . $pinned_from;
                                        }
                                    } else if ($pinDetails->pin_real_pin_id != '' && $pinDetails->pin_real_pin_id != 0) {
                                        echo JText::_('COM_SOCIALPINBOARD_REPINNED_BY_USER');
                                    } else {
                                        echo JText::_('COM_SOCIALPINBOARD_UPLOADED_BY_USER');
                                    }
                                    ?>
                                </p>
                            </div>
                        </div>

                        <div class="clear"></div>
                    </div>

                    <div class="floatright ">

                                    <?php

                                    if ($user_id != $user_res->user_id && $user_id != '' && $user_id != '0') {
                                    ?>
                            <div id="category_user_follow<?php echo $user_res->user_id ?>" style="padding-right: 5px;" >
                                    <?php
                                        if (($userfollow == 'all') || ($crt_board == '1')) {
                                    ?>
                                    <input type="button" name="unFollowuser" id="unFollowuser" class="follow" onclick="unfollowusers('<?php echo $user_id; ?>','<?php echo $user_res->user_id; ?>');" value="<?php echo JText::_('COM_SOCIALPINBOARD_UNFOLLOW') ?>" />
                                    <?php
                                        } else if ($crt_board == '0') {
                                    ?>

                                    <input type="button" name="followuser" id="followuser" class="follow" onclick="followusers('<?php echo $user_id; ?>','<?php echo $user_res->user_id; ?>');" value="<?php echo JText::_('COM_SOCIALPINBOARD_FOLLOW_USER') ?>" />

                                    <?php
                                        } else {
                                    ?>

                                    <input type="button" name="followuser" id="followuser" class="follow" onclick="followusers('<?php echo $user_id; ?>','<?php echo $user_res->user_id; ?>');" value="<?php echo JText::_('COM_SOCIALPINBOARD_FOLLOW_USER') ?>" />

                                    <?php
                                        }
                                    ?> </div>

<?php
                                    }
?>
<input type="hidden" name="followerscount" id="followerscount" value="0"/>
                    </div>
                    <div class="clear"></div>
                </div>

                <!-- google Ad -->
                        <?php
                                    if (!empty($adsvalue)) {
                        ?>

                                    <div class="mob-video" style="margin: 0 auto; width: 468px;">


                                    <iframe id="google_ads_frame1" height="<?php echo $adsvalue[0]->adheight; ?>" frameborder="0" width="<?php echo $adsvalue[0]->adwidth; ?>" scrolling="no" vspace="0"
                                        src="http://googleads.g.doubleclick.net/pagead/ads?client=ca-<?php echo $adsvalue[0]->adclient; ?>&output=html&h=60&slotname=<?php echo $adsvalue[0]->adslot; ?>&w=600&lmt=1333622642&flash=11.2.202&url=<?php echo JURI::base() . "index.php?option=com_socialpinboard&view=pin&pinid=" . $pinDetails->pin_id ?>&dt=1333622642977&bpp=6&shv=r20120328&jsv=r20110914&correlator=1333622643047&frm=20&adk=2492369040&ga_vid=1889973982.1333622643&ga_sid=1333622643&ga_hid=582102500&ga_fc=0&u_tz=330&u_his=1&u_java=1&u_h=768&u_w=1366&u_ah=738&u_aw=1366&u_cd=24&u_nplug=8&u_nmime=59&dff=helvetica%20neue&dfs=13&adx=437&ady=51&biw=1349&bih=605&oid=3&fu=0&ifi=1&dtd=230&xpc=c7YrckmnJi&p="<?php echo JURI::base(); ?> name="google_ads_frame1" marginwidth="0" marginheight="0" hspace="0" allowtransparency="true"><base target="_parent" />
                                </iframe>

                            </div>
<?php
                                    }
?>
                        <!-- ends here google -->
                        <!-- #PinPinner -->

                        <div id="PinActionButtons">

                            <div class="btn-pinlist btn-repin" id="websiteRepin" style="">
                                <input type="button" class="report_repin" onclick="getpin(<?php echo $pin_id; ?>,'<?php echo JURI::base(); ?>',<?php echo $user_id; ?>)" title="Repin" id="showrepindiv<?php echo $pin_id; ?>" value="<?php echo JText::_('COM_SOCIALPINBOARD_REPIN'); ?>" />
                            </div>
                                    <div class="btn-pinlist btn-repin" id="mobileRepin" style="display:none;">
                                        <input type="button" class="report_repin" onclick="mobpingetpin(<?php echo $pin_id; ?>,'<?php echo JURI::base(); ?>',<?php echo $user_id; ?>)" title="Repin" id="showrepindiv<?php echo $pin_id; ?>" value="<?php echo JText::_('COM_SOCIALPINBOARD_REPIN'); ?>" />
                                    </div>

                            <!-- Pin page and popup like comments start here-->
                        <?php
                                    if ($user->get('id') != 0) {
                                        if ($like_res == 1) {
                                            $likestyle = 'display:none';
                                            $unlikestyle = 'display:block';
                                        } else {
                                            $unlikestyle = 'display:none';
                                            $likestyle = 'display:block';
                                        }
                                        $userids = $user->id;
                                    } else {
                                        $userids = 0;
                                        $likestyle = 'display:block';
                                        $unlikestyle = 'display:none';
                                    }
                        ?>
<?php if ($user_id != $pinDetails->pin_user_id) { ?>
                                            <div class="btn-pinlist btn-like" id="likebtn<?php echo $pin_id; ?>">
                                                <input type="button" class="pin_like" onclick="getlikepin(<?php echo $pin_id; ?>,<?php echo $userids; ?>,<?php echo $flag = 0; ?>)" title="like" id="like-<?php echo $pin_id; ?>" style="<?php echo $likestyle; ?>" value="<?php echo JText::_('COM_SOCIALPINBOARD_LIKE'); ?>" />
                                                <input type="button" class="pin_unlike" onclick="getlikepin(<?php echo $pin_id; ?>,<?php echo $userids; ?>,<?php echo $flag = 1; ?>)" title="unlike" id="unlike-<?php echo $pin_id; ?>" style="<?php echo $unlikestyle; ?>" value="<?php echo JText::_('COM_SOCIALPINBOARD_UNLIKE'); ?>" />
                                            </div>
                <?php
                                    }
                ?>
<?php
                                    if ($user_id == $pinDetails->pin_user_id) {
?>

                                            <div class="btn-pinlist btn-edit">
                                                <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=pinedit&pinId=' . $pin_id); ?>" class=""  title="<?php echo JText::_('COM_SOCIALPINBOARD_EDIT'); ?>" id="Editpin"><?php echo JText::_('COM_SOCIALPINBOARD_EDIT'); ?></a>
                                            </div>
<?php
                                    }
?>
                                        <!-- Pins page and popup like ends here-->

                                        <!-- Youtube information starts here-->
                    <?php
                                    if ($pinDetails->link_type == 'youtube') {
                    ?>
                                        <div id="PinImageHolder" class="mob-video">
                                            <iframe class="mob-you_tube" width="585" height="415" src="http://www.youtube-nocookie.com/embed/<?php echo $youtube; ?>?autohide=1&amp;theme=light&amp;hd=1&amp;modestbranding=1&amp;rel=0&amp;showinfo=0&amp;showsearch=0&amp;wmode=transparent&amp;autoplay=1" frameborder="0" allowfullscreen=""></iframe>
                                        </div>
                    <?php
                                    } elseif ($pinDetails->link_type == 'vimeo') {
                    ?>
                                        <div id="PinImageHolder" class="mob-video">
                                            <iframe src="http://player.vimeo.com/video/<?php echo $youtube; ?>" class="mob-vimeo" width="585" height="415" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
                                        </div>
                    <?php
                                    } else {
                    ?>

                                        <div id="PinImageHolder" class="ImgLink" style="margin-top: 30px; position: relative;">

                                            <!-- Image url -->


                                            <a class="facebox_image"  id="faceboxhref" onclick="linkpage('<?php echo $target ?>','<?php echo $url ?>');"  rel="nofollow" <?php echo $tab; ?> >



                                                <img src="<?php echo $src_path; ?>" id="pinCloseupImage" alt="Pinned Image" /></a>
<?php
                                        if ($pinDetails->gift == '1') {
                                            if (JRequest::getVar('page')) {
?> 
                                                    <strong class="PriceContainer_pop">
                                                        <strong class="price"><?php echo $pinDetails->price; ?></strong></strong>
                    <?php
                                            } else {
                    ?>

                                                    <strong class="PriceContainer_normal">
                                                        <strong class="price"><?php echo $pinDetails->price; ?></strong></strong>
                    <?php
                                            }
                                        }
                    ?>
                                        </div>

                    <?php } ?>

                                </div>



                                <div id="PinCaption">
                    <?php $pinDetails->pin_description; ?>
                                </div>
                                <!-- #PinCaption -->

                                <!-- bottom google ad -->
<?php
                                    if (!empty($bottomad)) {
?>
                                    <div id="bottomad" class="mob-video" style="margin: 0 auto; width: 470px;">

                                        <iframe id="google_ads_frame1" height="<?php echo $bottomad[0]->adheight; ?>" frameborder="0" width="<?php echo $bottomad[0]->adwidth; ?>" scrolling="no" vspace="0"
                                                src="http://googleads.g.doubleclick.net/pagead/ads?client=ca-<?php echo $bottomad[0]->adclient; ?>&output=html&h=60&slotname=<?php echo $bottomad[0]->adslot; ?>&w=600&lmt=1333622642&flash=11.2.202&url=<?php echo JURI::base() . "index.php?option=com_socialpinboard&view=pin&pinid=" . $pin_id ?> &dt=1333622642977&bpp=6&shv=r20120328&jsv=r20110914&correlator=1333622643047&frm=20&adk=2492369040&ga_vid=1889973982.1333622643&ga_sid=1333622643&ga_hid=582102500&ga_fc=0&u_tz=330&u_his=1&u_java=1&u_h=768&u_w=1366&u_ah=738&u_aw=1366&u_cd=24&u_nplug=8&u_nmime=59&dff=helvetica%20neue&dfs=13&adx=437&ady=51&biw=1349&bih=605&oid=3&fu=0&ifi=1&dtd=230&xpc=c7YrckmnJi&p=<?php echo JURI::base(); ?>" name="google_ads_frame1" marginwidth="0" marginheight="0" hspace="0" allowtransparency="true"><base target="_parent" /></iframe>


                                    </div>
<?php
                                    }
?>
                            <!-- ends google ad -->
                            <div class="PinComments">
<?php echo $pinDetails->pin_description; ?>

                            </div>


                            <div class="comments board_comments" id="commentPDiv<?php echo $pin_id; ?>">
                                <ul class="c-list">
<?php
                                    if ($comment_res != '') {
                                        foreach ($comment_res as $comment) {
?>
                                                <li onmouseover="className='current'" onmouseout="className='span'" id="homecommentli<?php echo $comment->pin_comments_id; ?>">
                                                    <div class="pin_board_user_left">
                                                        <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=boarddisplay&uid=' . $comment->pin_user_comment_id); ?>">
<?php
                                            if ($comment->user_image != '') {
?>
                                                                <img height="50" src="<?php echo JURI::base() . 'images/socialpinboard/avatars/' . $comment->user_image; ?>" title="<?php echo $comment->username; ?>" id='photoUrl'
                                                                     alt="<?php echo $comment->username; ?>" width="50" class="avatar"/>
<?php
                                            } else {
?>
                                           <img src="<?php echo JURI::Base(); ?>/components/com_socialpinboard/images/no_user.jpg" title="<?php echo $comment->username; ?>" alt="<?php echo $comment->username; ?>" height="30px" width="30px" class="avatar" />
                <?php
                                            }
                ?>
                                                            </a>
                                                        </div>
                                                        <div class="pin_board_user_right">
                                                            <a class="board_user_name"  href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=boarddisplay&uid=' . $comment->pin_user_comment_id); ?>">
                                                                <span class="user"><?php echo $comment->first_name . ' ' . $comment->last_name; ?></span>
                                                            </a>
                                                            <span ><?php echo $comment->pin_comment_text ?></span>
                <?php
                                            if (($pinDetails->pin_user_id == $comment->pin_user_comment_id && $comment->pin_user_comment_id == $user->id) || $comment->pin_user_comment_id == $user->id || $pinDetails->pin_user_id == $user->id) {
                ?>
                                                                <input type="button" class="comment_delt_icon" value="X" onClick="return deleteComment('<?php echo $comment->pin_comments_id ?>','<?php echo $pinDetails->pin_id ?>','<?php echo $pinDetails->pin_user_id; ?>');" />
<?php
                                            }
?>
                                                    </div>
                                                </li>
<?php }
                                    } ?>

                                </ul>

                            </div>
<?php if ($user_id != 0) { ?>
                                <div id="PinAddComment">

                                    <div id="PinInputArea">
                                    <?php
                                        if ($user_res1->user_image != '') {
                                    ?>


                                <img src="<?php echo JURI::base() . 'images/socialpinboard/avatars/' . $user_res1->user_image; ?>" alt="<?php echo $user_res1->first_name; ?>" width="50" height="50" class="CommenterImage"/>
                                         <?php
                                        } else {
                                         ?>
                                            <img src="<?php echo JURI::Base(); ?>/components/com_socialpinboard/images/no_user.jpg" title="<?php echo $user_res1->first_name; ?>" alt="<?php echo $user_res1->first_name; ?>" height="50" width="50" class="avatar" />
                                    <?php
                                        }
                                    ?>
                            <div class="InputContainer board_InputContainer">
                                <textarea onclick="document.getElementById('post_comment').style.display='block';if(this.value=='<?php echo JText::_('COM_SOCIALPINBOARD_ADD_COMMENT'); ?>') { this.value=''; }" id="commentContent" name="commentContent" maxlength="1000"><?php echo JText::_('COM_SOCIALPINBOARD_ADD_COMMENT'); ?></textarea><div class="tagmate-menu" style="position: absolute; display: none; "></div>
                            </div>
                            <input type="button" name="post_comment" id="post_comment" style="display:none;"
                                   class="button" onclick="doPinComment(<?php echo $pinDetails->pin_id; ?>,'<?php echo $user_res1->first_name; ?>','<?php echo $user_res1->last_name; ?>','<?php echo $user_res1->user_image; ?>','<?php echo JRoute::_('index.php?option=com_socialpinboard&view=boarddisplay&uid=' . $user->id); ?>','<?php echo $pinDetails->pin_user_id; ?>')" value="<?php echo JText::_('COM_SOCIALPINBOARD_COMMENT'); ?>" />

                        </div><!-- #PinInputArea -->

                    </div>
<?php } ?>
                                <?php if (JRequest::getVar('page')) {
 ?>
                        <div class="PinInfo">
                            <p class="colorless"><?php echo JText::_('COM_SOCIALPINBOARD_PINNED_ON_TO_THE_BOARD'); ?></p>

                            <h3><a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=boardpage?bId=' . $pinDetails->pin_board_id); ?>" ><?php echo $pinDetails->board_name; ?></a></h3>
                                    <ul id="BoardThumbs">
<?php
                                        if (count($boardPins) != 0) {
                                            $pinid = JRequest::getInt('pinid');
                                            foreach ($boardPins as $arrPins) {
                                                if ($arrPins->link_type == 'youtube' || $arrPins->link_type == 'vimeo') {
                                                    $src_path = $arrPins->pin_image;
                                                } else {
                                                    $src_path = JURI::base() . "images/socialpinboard/pin_thumb/" . rawurlencode($arrPins->pin_image);
                                                }
                                                if ($arrPins->pin_id != $pinid) {
?>
                                                    <li> <a target="_blank" title="<?php echo $arrPins->pin_description; ?>" href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=pin&pinid=' . $arrPins->pin_id); ?>" ><img src="<?php echo $src_path; ?>" alt="" width="49" height="49" /></a></li>
<?php }
                                            } ?>


                        <?php } ?>
                                    </ul>
                        <?php } ?>
                            </div>
                            <div class="pinned_by_details">
                                <div class="originally_pinned_by">
<?php
                                    foreach ($pinnedpins as $arrPins) {
                                        $uname = $arrPins->username;
                                        $user_id = $arrPins->pin_user_id;
                                    }
                                    echo "<span class='pinned_title'>Originally Pinned by</span>";
?>
                                    <a class="by_name" href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=boarddisplay&uid=' . $user_id); ?>"><?php echo $uname; ?></a>

                                    <ul id="BoardThumbs">
<?php
                                    //print_r($uploadpins);
                                    if (count($pinnedpins) != 0) {
                                        foreach ($pinnedpins as $arrPins) {
                                            if ($arrPins->link_type == 'youtube' || $arrPins->link_type == 'vimeo') {
                                                $src_path = $arrPins->pin_image;
                                            } else {
                                                $src_path = JURI::base() . "images/socialpinboard/pin_thumb/" . rawurlencode($arrPins->pin_image);
                                            }
?>
                                        <li> <a target="_blank" title="<?php echo $arrPins->pin_description; ?>" href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=pin&pinid=' . $arrPins->pin_id); ?>" ><img src="<?php echo $src_path; ?>" alt="" width="49" height="49" /></a></li>
                        <?php } ?>


                        <?php
                                    }
                        ?>


                                    </ul>



                                </div>
<?php
                                    if (!empty($bookpins)) {
?>
                                        <div class="pinned_via">
                                            <span class="pinned_title">Pinned via
                                                <a target="_blank" class="by_link" href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=pinit'); ?>">
                                                Pin IT Button
                                            </a>
                                            <span class="from_text"><?php echo "from"; ?></span>
                                        </span>
                                            <?php $url_host= parse_url($url);

                                            ?>
                                        <a target="_blank" class="by_name" href="<?php echo $url ?>"><?php echo $url_host['host'];?></a></span>
                                        <ul id="BoardThumbs">
                            <?php

                                        foreach ($bookpins as $pins) {
                                            $pin_type_id = $pins->pin_type_id;
                                            if ($pins->pin_url != "" && $pin_type_id == '0') {
                                                if (strstr($pins->pin_url, 'http://')) {
                                                    $url = str_replace("http://", "", "$pins->pin_url");
                                                    $url = explode("/", $url);
                                                }
                                                if ($pins->link_type == 'youtube' || $pins->link_type == 'vimeo') {
                                                    $src_path = $pins->pin_image;
                                                } else {
                                                    $src_path = JURI::base() . "images/socialpinboard/pin_thumb/" . rawurlencode($pins->pin_image);
                                                }
                            ?>
                                                <li> <a target="_blank" title="<?php echo $pins->pin_description; ?>" href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=pin&pinid=' . $pins->pin_id); ?>" ><img src="<?php echo $src_path; ?>" alt="" width="49" height="49" /></a></li>

                            <?php
                                            }
                                        }
                            ?>


                                    </ul>


                                </div>






<?php
                                    }
?>
                                </div>
                            <?php
                                    if ($pinDetails->pin_repin_count == 1) {
                                        echo '<div class="pin_page_repins clearfix">
        <h3>' . $pinDetails->pin_repin_count . ' ' . JText::_('COM_SOCIALPINBOARD_REPIN') . '</h3>';
                                    } else if ($pinDetails->pin_repin_count > 1) {
                                        echo '<div class="pin_page_repins clearfix">
        <h3>' . $pinDetails->pin_repin_count . ' ' . JText::_('COM_SOCIALPINBOARD_REPINS') . '</h3>';
                                    }
                            ?>
                        <ul>
                            <?php
                                    $total = ceil(count($repindetails) / 2);
                                    $i = 0;
                                    if (count($repindetails) != 0) {
                                        foreach ($repindetails as $repinuserimage) {
                            ?>
                                    <li  >
                            <?php
                                            if ($i == 0)
                                                echo '<span class="SubmenuColumn">';
                                            if ($i == $total)
                                                echo '</span><span class="SubmenuColumn">';
                            ?>
                            <?php
                                            if ($repinuserimage->user_image != '') {
                            ?>

                                            <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=boarddisplay&uid=' . $repinuserimage->pin_user_id); ?>">
                                                <img alt="" src="<?php echo JURI::base() . 'images/socialpinboard/avatars/' . $repinuserimage->user_image; ?> " width="40" height="40"/>
<?php
                                            } else {
?>
                                                <img src="<?php echo JURI::Base(); ?>/components/com_socialpinboard/images/no_user.jpg" title="" alt="" height="30" width="30" class="avatar" />
<?php
                                            }
?>  
<?php echo ucfirst($repinuserimage->first_name); ?></a> onto <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=boardpage&bId=' . $repinuserimage->pin_board_id); ?>"><?php echo $repinuserimage->board_name; ?></a>


                    <?php
                                            if ($i == count($repindetails) - 1)
                                                echo '</span>';

                                            $i++;
                    ?>
                                                </li>
                <?php
                                        }
                                    }
                ?>
                                    </ul>
                    <?php
                                    if ($pinDetails->pin_repin_count == 1) {
                                        echo '</div>';
                                    } else if ($pinDetails->pin_repin_count > 1) {
                                        echo '</div>';
                                    }
                    ?>
<?php
                                    $like = '';
                                    if ($pinDetails->pin_likes_count != 0 && $pinDetails->pin_likes_count == 1) {
                                        $like = '<div class="pin_page_likes">
                <h3>' . $pinDetails->pin_likes_count . JText::_('COM_SOCIALPINBOARD_LIKE') . '</h3>';
                                    } else if ($pinDetails->pin_likes_count > 1) {
                                        $like = '<div class="pin_page_likes">
                <h3>' . $pinDetails->pin_likes_count . JText::_('COM_SOCIALPINBOARD_LIKES') . '</h3>';
                                    }
                                    echo $like;
?>

<?php
                                    foreach ($pinlikeuser as $likeuserimg) {
?>
            <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=boarddisplay&uid=' . $likeuserimg->user_id); ?>">
                                        <?php
                                        if ($likeuserimg->user_image != '') {
?>
                                    <img alt="<?php echo $likeuserimg->first_name; ?>" src="<?php echo JURI::base() . 'images/socialpinboard/avatars/' . $likeuserimg->user_image; ?>" width="40" height="40"/>
                            <?php
                                        } else {
                            ?>
                                            <img src="<?php echo JURI::Base(); ?>/components/com_socialpinboard/images/no_user.jpg" title="<?php echo $likeuserimg->first_name; ?>" alt="<?php echo $likeuserimg->first_name; ?>" width="40" height="40" class="avatar" />
                        <?php
                                        }
                        ?>
                                </a>
                        <?php } ?>

                    <?php
                                    $like = '';

                                    if ($pinDetails->pin_likes_count != 0 && $pinDetails->pin_likes_count == 1) {
                                        $like = '</div>';
                                    } else if ($pinDetails->pin_likes_count > 1) {
                                        $like = '</div>';
                                    }
                                    echo $like;
                    ?>
                                </div>
                <?php if (JRequest::getVar('page')) {
 ?>
                                </div>
                <?php
                                    }
                ?>
                            <div id="SocialShare">
                <?php
                                    $pageURl = JURI::base() . 'index.php?option=com_socialpinboard&view=pin&pinid=' . $pin_id;
                ?>
                <?php
                                    $url_fb = "http://www.facebook.com/sharer/sharer.php?s=100&amp;p%5Btitle%5D=" . $pinDetails->pin_description . "&amp;p%5Bsummary%5D=" . strip_tags($pinDetails->pin_description) . "&amp;p%5Burl%5D=" . urlencode($pageURl) . "&amp;p%5Bimages%5D%5B0%5D=" . urlencode($fbshare_image);
                ?>
                                <ul>
                                    <li><a href="<?php echo $url_fb; ?>" class="fbshare" id="fbshare" target="_blank" ></a></li>
                                    <li><a href="http://twitter.com/share" class="twitter-share-button" data-count="none" data-url="<?php echo $pageURl; ?>" data-via="<?php echo $siteName; ?>" data-text="<?php echo $pinDetails->pin_description; ?>"><?php echo JText::_('COM_SOCIALPINBOARD_TWEET'); ?></a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script></li>
<div id="additinalFeatures" style="display:none;">
                    <?php if (!empty($user->id)) { ?>
                                        <li><a id="PinReport" style=" cursor: pointer; " class="Button WhiteButton Button11" onclick="Modal.show('report');"><strong><?php echo JText::_('COM_SOCIALPINBOARD_REPORT_PIN'); ?></strong></a></li>

                                        <li><a href="#" onclick="Modal.show('EmailModal'); return false" target="_blank" id="EmailShare" class="Button WhiteButton Button11"><strong>@<?php echo JText::_('COM_SOCIALPINBOARD_EMAIL'); ?></strong></a></li>
                                        <li><a href="#" onclick="Modal.show('EmbedModal'); return false" target="_blank" id="EmailShare" class="Button WhiteButton Button11"><strong><><?php echo JText::_('COM_SOCIALPINBOARD_EMBED'); ?></strong></a></li>
                    <?php } ?>
</div>
                            </ul>
                        </div>

                        <div id="EmailModal" class="ModalContainer">
                            <div class="modal wide PostSetup slidup" style="margin-bottom: -138px; ">
                                <div id="postsetup">
                                    <div class="header lg">
                                        <h2><?php echo JText::_('COM_SOCIALPINBOARD_EMAIL_THIS_PIN'); ?></h2>
                                            <a class="close-btn" onclick="Modal.close('EmailModal'); return false"></a>
                                        </div>
                                        <div id="output"></div>
                                        <form action="" method="post" class="Form FancyForm">
                                            <ul>
                                                <li>
                                                    <input type="text" id="MessageRecipientName" class="ClearOnFocus" name="MessageRecipientName" maxlength="180" onblur="if (this.placeholder == '') {this.placeholder = '<?php echo JText::_('COM_SOCIALPINBOARD_RECIPIENT_NAME'); ?>';}" onfocus="if (this.placeholder == '<?php echo JText::_('COM_SOCIALPINBOARD_RECIPIENT_NAME'); ?>') {this.placeholder = '';this.style.color ='#000';}" placeholder="<?php echo JText::_('COM_SOCIALPINBOARD_RECIPIENT_NAME'); ?>"/>
                                <!--                    <label><?php echo JText::_('COM_SOCIALPINBOARD_RECIPIENT_NAME'); ?></label>-->
                    <!--                                    <span class="fff"></span>-->
                                                    <span class="helper red"></span>
                                                    <div id="recipient_name_error"style="display:none"><?php echo JTExt::_('COM_SOCIALPINBOARD_PLEASE_ENTER_THE_RECIPIENT_NAME'); ?></div>
                                                </li>
                                                <li>
                                                    <input type="text" id="MessageRecipientEmail" class="ClearOnFocus" name="MessageRecipientEmail" maxlength="180" onblur="if (this.placeholder == '') {this.placeholder = '<?php echo JText::_('COM_SOCIALPINBOARD_RECIPIENT_EMAIL'); ?>';}" onfocus="if (this.placeholder == '<?php echo JText::_('COM_SOCIALPINBOARD_RECIPIENT_EMAIL'); ?>') {this.placeholder = '';this.style.color ='#000';}" placeholder="<?php echo JText::_('COM_SOCIALPINBOARD_RECIPIENT_EMAIL'); ?>" value="" />
                        <!--                                    <span class="fff"></span>-->
                                                        <span class="helper red"></span>
                                                        <div id="recipient_email_error"style="display:none"><?php echo JTExt::_('COM_SOCIALPINBOARD_PLEASE_ENTER_THE_RECIPIENT_EMAIL'); ?></div>
                                                            <div id="recipient_invalid_email_error"style="display:none"><?php echo JTExt::_('COM_SOCIALPINBOARD_INVALID_EMAIL_ADDRESS'); ?></div>

                                                        </li>
                                                        <li class="optional">
                                                            <textarea id="MessageBody" class="ClearOnFocus" name="MessageBody" maxlength="180" onblur="if (this.placeholder == '') {this.placeholder = '<?php echo JText::_('COM_SOCIALPINBOARD_MESSAGE'); ?>';}" onfocus="if (this.placeholder == '<?php echo JText::_('COM_SOCIALPINBOARD_MESSAGE'); ?>') {this.placeholder = '';this.style.color ='#000';}" placeholder="<?php echo JText::_('COM_SOCIALPINBOARD_MESSAGE'); ?>"></textarea>
                                    <!--                    <label><?php echo JText::_('COM_SOCIALPINBOARD_MESSAGE'); ?></label>-->
                                                        <span class="fff"></span>
                                                        <div id="recipient_message_error"style="display:none"><?php echo JTExt::_('COM_SOCIALPINBOARD_PLEASE_ENTER_THE_RECIPIENT_MESSAGE'); ?></div>
                                                    </li>
                                                </ul>
                                                <div style="display:none"><input type="hidden" name="csrfmiddlewaretoken" value="3b5f5ae1987a0ff12f9b337e5670ae08" /></div>
                                                <div><a onclick="sendemail();" class="" data-form="EmailModal" id="report_send_mail_btn"><?php echo JText::_('COM_SOCIALPINBOARD_SEND_EMAIL'); ?></a></div>

                                            <div class="inputstatus error" ></div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!--Embed Popupbox Begin-->
                            <div id="EmbedModal" class="ModalContainer">
                                <div class="modal wide emailwide">
                                    <div class="header lg">
                                        <a href="#" class="close_embed" onclick="Modal.close('EmbedModal'); return false"><strong>Close</strong><span></span></a>
                                        <h2><?php echo JText::_('COM_SOCIALPINBOARD_EMBEDD_YOUR_BLOG') ?></h2>
                                    </div>
                                    <form action="#" method="post" class="Form FancyForm" name="Embedcode">
                                        <ul>
                                            <li>
                                                <input type="text" id="EmbedImageWidth" class="ClearOnFocus" name="EmbedImageWidth" maxlength="180" style="display: inline-block; width: 25%; min-width: 25%;" onkeyup="embedcoding();"/> <strong class="colorlight" style="font-weight: 300;">px &mdash; Image Width</strong>
                                                <span class="fff" style="right: 75%"></span>
                                            </li>
                                            <li>
                                                <input type="text" id="EmbedImageHeight" class="ClearOnFocus" name="EmbedImageHeight" maxlength="180" style="display: inline-block; width: 25%; min-width: 25%;" onkeyup="embedcoding();"/> <strong class="colorlight" style="font-weight: 300;">px &mdash; Image Height</strong>
                                                <span class="fff" style="right: 75%"></span>
                                            </li>
                                            <li>
                                                <textarea id="EmbedHTMLCode" class="ClearOnFocus" name="EmbedHTMLCode" maxlength="180" onfocus="this.select()" wrap="virtual"></textarea>
                                            </li>
                                        </ul>
                                    </form>
                                </div>
                                <div class="overlay"></div>
                            </div>
                            <!--Embed Popupbox End-->
                            <div id="report" class="ModalContainer">
                                <div class="modal wide PostSetup slidup" style="margin-bottom: -138px; ">
                                    <div id="postsetup">
                                        <div class="header lg">
                                            <h2><?php echo JText::_('COM_SOCIALPINBOARD_REPORT_A_PIN'); ?></h2>
                                            <a class="close-btn" onclick="javascript:document.getElementById('outputs').innerHTML = '';Modal.close('report'); return false"></a>
                                        </div>
                                        <form name="report1" id="report1" action="#" method="post" class="Form FancyForm">
                                            <div id="outputs"></div>
                                            <p id="ReportLabel"><?php echo JText::_('COM_SOCIALPINBOARD_WHY_REPORTING_PIN'); ?> ?</p>

                                            <ul>
                                                <li>
                                                    <input type="radio" name="nudi" id="nudi" value="<?php echo JText::_('COM_SOCIALPINBOARD_NUDITY_PORNOGRAPHY'); ?>"/><?php echo JText::_('COM_SOCIALPINBOARD_NUDITY_PORNOGRAPHY'); ?><br>
                                                    <input type="radio" name="nudi" id="grp" value="<?php echo JText::_('COM_SOCIALPINBOARD_ATTACKS_GROUP_INDIVIDUAL'); ?>"/><?php echo JText::_('COM_SOCIALPINBOARD_ATTACKS_GROUP_INDIVIDUAL'); ?><br>
                                                    <input type="radio" name="nudi" id="violence" value="<?php echo JText::_('COM_SOCIALPINBOARD_GRAPHICS_VIOLENCE'); ?>"/><?php echo JText::_('COM_SOCIALPINBOARD_GRAPHICS_VIOLENCE'); ?><br>
                                                    <input type="radio" name="nudi" id="spam" value="<?php echo JText::_('COM_SOCIALPINBOARD_SPAM'); ?>"/><?php echo JText::_('COM_SOCIALPINBOARD_SPAM'); ?><br/>
                                                </li>
                                            </ul>


                                            <input type="button" id="report_sbumit_btn" onclick="sendReport(); return false"  data-form="ReportModal" value="<?php echo JText::_('COM_SOCIALPINBOARD_REPORT_A_PIN'); ?>" />
                                        </form>
                                    </div>
                                </div>
                            </div>


                    <!-- Mobile Repin Starts here-->
                    <div id="mobrepin" style="display:none;">
                        <h2><?php echo JText::_('COM_SOCIALPINBOARD_REPIN'); ?></h2>
                        <div class="ImagePicker">
                            <div class="Images" id="mobimagepin"><img src="" name="pinImage" id="mobpinImage"></div>
                        </div>
                        <ul>
                            <li>
                                <select name="repin_board" id="mobrepin_board" style="margin-top: 150px;">
                        <?php foreach ($getmobileBoards as $getmobileBoards) {
 ?>
                                    <option value=" <?php echo $getmobileBoards->board_id; ?>"> <?php echo $getmobileBoards->board_name; ?></option>
<?php } ?>
                            </select>
                        </li>
                        <li>
                            <div class="uploadtext">
                                <input type="text" name="boardtxt" id="mobboardtxt" value=""/>
                            </div>
                        </li>
                        <li>
                            <div class="special">
                                <a href="javascript:void(0);" class="creat_bttn" onclick="mobaddrepinmenuboard(<?php echo $user->id; ?>)" style="display:block">
<?php echo JText::_('COM_SOCIALPINBOARD_CREATE'); ?>
                            </a>
                        </div>
                    </li>
                    <li>
                        <textarea class="DescriptionTextarea" id="mobDescriptionTextarea" rows="2" data-text-error-empty="Please enter a description." name="caption"></textarea>
                        <div id="mobdescriptionerror"></div>
                    </li>
                    <input type="hidden" name="pin_type_id" id="mobpin_type_id" value=""/>
                    <input type="hidden" name="pin_repin_id" id="mobpin_repin_id" value=""/>
                    <input type="hidden" name="pin_real_pin_id" id="mobpin_real_pin_id" value=""/>
                    <input type="hidden" name="pin_url" id="mobpin_url" value=""/>
                    <input type="hidden" name="pin_user_id" id="mobpin_user_id" value="<?php echo $user->id; ?>"/>
                    <li>
                        <div class="Buttons" style="clear:both;">
                            <input type="button" id="mobuploadPin" onclick="return mobajxGetBoards('<?php echo JURI::base(); ?>');" value="<?php echo JText::_('COM_SOCIALPINBOARD_PIN_IT'); ?>"/>
                            <input type="button" id="mobcancel" onclick="pinrepinCancel();" value="<?php echo JText::_('COM_SOCIALPINBOARD_CANCEL'); ?>"/>
                        </div>
                    </li>
                    <li>
                        <div id="mobPostwait" class="PostSuccess" style="display:none;font-size: 20px;">
<?php echo JText::_('COM_SOCIALPINBOARD_REPINNING_PLEASE_WAIT'); ?>
                            </div>
                            <div id="mobPostsuccess" class="PostSuccess" style="display:none;font-size: 20px;">
<?php echo JText::_('COM_SOCIALPINBOARD_REPIN_SUCCESSFULLY'); ?>
                            </div>
                        </li>
                    </ul>
                </div>
                <!--Mobile Repin End Here -->
<?php if (JRequest::getVar('page')) { ?>
                            </div>
                        </div>

<?php
                                    }
?>
                    
                    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
                    <script type="text/javascript">
                        var follow_user_lang="<?php echo JText::_('COM_SOCIALPINBOARD_FOLLOW_USER'); ?>";
                         var un_follow_lang="<?php echo JText::_('COM_SOCIALPINBOARD_UNFOLLOW'); ?>";
                                    var txt =  navigator.platform ;
                                    if(txt =='iPod'|| txt =='iPhone'|| txt =='Linux armv7l' || txt =='Linux armv6l')
                                    {
                                        document.getElementById('additinalFeatures').style.display = "none";
                                        document.getElementById('websiteRepin').style.display = "none";
                                        document.getElementById('mobileRepin').style.display = "block";
                                    }else{
                                        document.getElementById('additinalFeatures').style.display = "block";
                                        document.getElementById('websiteRepin').style.display = "block";
                                        document.getElementById('mobileRepin').style.display = "none";
                                    }
                                    function pinrepinCancel(){
                                        document.getElementById('mobrepin').style.display = "none";
                                        document.getElementById('boarddiv').style.display = 'block';
                                        document.getElementById('sideboard').style.display = 'block';
                                        document.getElementById('pindiv').style.display = 'block';
                                        document.getElementById('SocialShare').style.display = 'block';
                                    }


                        //function assignImgWH(){
                        document.getElementById('EmbedImageWidth').value = '<?php echo $strImgOriWidth ?>';
                        document.getElementById('EmbedImageHeight').value = '<?php echo $strImgOriHeight ?>';;
                        document.Embedcode.EmbedHTMLCode.value = createEmbedCode();
                        //}
                        function trim(stringToTrim) {
                            return stringToTrim.replace(/^\s+|\s+$/g,"");
                        }
                        function sendemail()
                        {

                            if(document.getElementById("MessageRecipientName").value=="")
                            {
                                document.getElementById("recipient_name_error").style.display="block";
                                document.getElementById("MessageRecipientName").focus();
                                return false;
                            }else
                            {
                                document.getElementById("recipient_name_error").style.display="none";
                            }
                            if(document.getElementById("MessageRecipientEmail").value=="")
                            {
                                document.getElementById("recipient_email_error").style.display="block";
                                document.getElementById("MessageRecipientEmail").focus();
                                return false;
                            }else
                            {
                                document.getElementById("recipient_email_error").style.display="none";
                            }
                            var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
                            var address = trim(document.getElementById("MessageRecipientEmail").value);
                            if(reg.test(address) == false) {
                                document.getElementById("recipient_invalid_email_error").style.display="block";
                                document.getElementById("recipient_email_error").style.display="none";
                                document.getElementById("MessageRecipientEmail").focus();
                                return false;
                            }
                            var xmlhttp;
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
                                    document.getElementById("output").innerHTML=xmlhttp.responseText;
                                }
                            }
                            xmlhttp.open("POST","<?php echo JRoute::_('index.php?option=com_socialpinboard&view=emailshare&tmpl=component'); ?>",true);
                                            xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                                            var name = document.getElementById('MessageRecipientName').value;
                                            var email = document.getElementById('MessageRecipientEmail').value;
                                            var body = document.getElementById('MessageBody').value;
                                            var pinid = '<?php echo JRequest::getInt('pinid'); ?>';
                                            xmlhttp.send('name=' + name+'&email=' + email+'&body=' + body+'&pinid=' +pinid);
                                            //document.getElementById("output").style.display="none";

                                        }
                                        function selectReports(val) {
                                            document.getElementById("reportval").value = val;
                                        }
                                        function checkRadio (frmName, rbGroupName) {
                                            var radios = document[frmName].elements[rbGroupName];
                                            for (var i=0; i <radios.length; i++) {
                                                if (radios[i].checked) {
                                                    return true;
                                                }
                                            }
                                            return false;
                                        }
                                        function sendReport()
                                        {
                                            if (!checkRadio("report1","nudi")) {
                                                alert('Please select the reason for reporting this pin');
                                                return false;
                                            }
                                            var xmlhttp;
                                            var reason;
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

                                                    document.getElementById("outputs").innerHTML=xmlhttp.responseText;
                                                }
                                            }
                                            xmlhttp.open("POST","<?php echo JRoute::_('index.php?option=com_socialpinboard&view=emailshare&tmpl=component'); ?>",true);
                                            xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                                            var pinid = '<?php echo JRequest::getInt('pinid'); ?>';
                                            var radioButtons = document.getElementsByName("nudi");
                                            for (var x = 0; x < radioButtons.length; x ++) {
                                                if (radioButtons[x].checked) {
                                                    var reason = radioButtons[x].value;
                                                }
                                            }
                                            xmlhttp.send('report=' + reason+'&pinid='+pinid);
                                            //document.getElementById("output").style.display="none";

                                        }
                                        function doPinComment(pinId,firstName,lastName,userImage,userUrl,pin_user_id) {

                                            var user_id= <?php echo $user_id ?>;
                                            var comment = scr("#commentContent").val().replace(/^\s+|\s+$/g,"");
                                            if(scr("#commentContent").val()!="Add a comment..." && comment!=""){

                                                scr.ajax({
                                                    type:"POST",
                                                    url:"?option=com_socialpinboard&view=ajaxcontrol&tmpl=component&task=getcommentinfo",
                                                    data:{'pin_id':pinId,"comment":comment},
                                                    success:function(message) {

                                                        var obj = jQuery.parseJSON(message);
                                                        var message = obj.comment;
                                                        var user_comment_id=obj.comment_id;
                                                        var comment_user=obj.user_id;
                                                        scr("#commentContent").val("Add a comment...");

                                                        if(message != "error") {


                                                            var message1 ='<li onmouseover="className="current"" onmouseout="className="span" id="homecommentli'+user_comment_id+'" class="span"> <div class="pin_board_user_left"><a href="'+userUrl+'">';
                                                            if(userImage=='')
                                                            {
                                                                message1 += '<img height="30" src="<?php echo JURI::base() ?>components/com_socialpinboard/images/no_user.jpg" title="'+firstName+lastName+'"  alt="'+firstName+lastName+'" width="30" class="avatar"></a>';

                                                            }else
                                                            {

                                                                message1 += '<img height="30" src="<?php echo JURI::base() ?>images/socialpinboard/avatars/'+userImage+'" title="'+firstName+' '+lastName+'" alt="'+firstName+' '+lastName+'" width="30" class="avatar"></a>';
                                                            }

                                                            message1 += '</div><div class="pin_board_user_right"><a class="board_user_name" href="'+userUrl+'"><span class="user">'+firstName+' '+lastName+'</span></a>';
                                                            if(user_id==pin_user_id || user_id==comment_user || user_id!='')
                                                            {
                                                                var delete_comment='<input type="button"  value="X"  class="comment_delt_icon" onClick="return deleteComment('+user_comment_id+','+pinId+');" />';
                                                                message1+=' '+'<span>'+message+'</span></p>'+delete_comment+'</div></div></li>';
                                                            }else
                                                            {
                                                                message1+='<span>'+message+'</span>'
                                                            }
                                                            '<span class="delete-comment" style="display:none"><a href="javascript:void(0)"></a></span></div></li>';
                                                            //scr('#commentPDiv'+pinId+' ul').append(message1);
                                                            var message2 = scr('#commentPDiv'+pinId+' ul').html();
                                                            var message3 =message2+message1;
                                                            scr('#commentPDiv'+pinId+' ul').html(message3);
                                                            scr("#commentscountspan"+pinId).show();
                                                            scr("#commentContent").val('');

                                                            document.getElementById('post_comment').style.display='none';
                                                            //                    scr('#container').masonry('reload');
                                                            var standard_message = "Add a comment...";
                                                            scr('#commentContent').focus(
                                                            function() {
                                                                if (scr(this).val() == standard_message)
                                                                    scr(this).val("");
                                                            }
                                                        );
                                                            scr('#commentContent').blur(
                                                            function() {
                                                                if (scr(this).val() == "")
                                                                    scr(this).val(standard_message);
                                                            }
                                                        );
                                                            var span;
                                                            var count = 0;
                                                            if( scr("#commentscountspan"+pinId).text()){

                                                                count =parseInt(scr("#commentscountspan"+pinId).text().substring(0,scr("#commentscountspan"+pinId).text().indexOf(" ")))+1;
                                                                span = count+" Comments ";
                                                            }else{
                                                                span= "1 Comment ";
                                                            }
                                                            scr("#commentscountspan"+pinId).text(span);


                                                        }
                                                    }
                                                });
                                            }
                                        }
                                        function deleteComment(comment_id,comment_pin_id,pin_user_id)
                                        {

                                            scr('#homecommentli'+comment_id).css('display', 'none');
                                            scr.ajax({
                                                type: "POST",
                                                url: "?option=com_socialpinboard&view=ajaxcontrol&tmpl=component&task=deleteComment",
                                                data: {'comment_id':comment_id, 'comment_pin_id':comment_pin_id},
                                                cache: false,
                                                success: function(comment_count){
                                                    var comment_count;
                                                    if(comment_count>1)
                                                    {
                                                        scr('#commentscountspan'+comment_pin_id).html(comment_count+" <?php echo JText::_('COM_SOCIALPINBOARD_COMMENTS') ?> ");

                                                    }
                                                    else
                                                    {
                                                        scr('#commentscountspan'+comment_pin_id).html(comment_count+" <?php echo JText::_('COM_SOCIALPINBOARD_COMMENT') ?> ");
                                                    }

                                                }

                                            });
                                            scr('#container').masonry( 'reload' );
                                        }
                                        function getlikepin(pinId,userId,pinFlag)
                                        {

                                            var xhr = getXhr();
                                            if(userId==0)
                                            {
                                                window.open('?option=com_socialpinboard&view=people','_self');
                                                return false;
                                            }
                                            xhr.onreadystatechange = function(){
                                                if(pinFlag==0)
                                                {
                                                    document.getElementById('like-'+pinId).style.display='none';
                                                    document.getElementById('unlike-'+pinId).style.display='block';
                                                }
                                                else if(pinFlag==1){
                                                    document.getElementById('unlike-'+pinId).style.display='none';
                                                    document.getElementById('like-'+pinId).style.display='block';
                                                }
                                                if(xhr.readyState == 4 ){

                                                    try
                                                    {
                                                        var options = xhr.responseText;
                                                        if(document.getElementById('likescountspan'+pinId))
                                                            document.getElementById('likescountspan'+pinId).innerHTML = options;
                                                    }
                                                    catch(e) {
                                                        //alert(e.message)
                                                    }
                                                }
                                            }

                                            var url = "?option=com_socialpinboard&view=ajaxcontrol&tmpl=component&task=getlikeinfo&pin_id="+pinId+'&user_id='+userId+'&pin_flag='+pinFlag;

                                            xhr.open("GET",url,true);
                                            xhr.send(null);
                                            return true;
                                        }


                                        function linkpage(pageop,url)
                                        {


                                            window.open(url,pageop);


                                        }

                                        function embedcoding(){

                                            var strImgOriWidth = '<?php echo $strImgOriWidth; ?>';
                                            strImgOriWidth = parseInt(strImgOriWidth);
                                            //alert(strImgOriWidth);
                                            var strImgOriHeight = '<?php echo $strImgOriHeight; ?>';
                                            strImgOriHeight = parseInt(strImgOriHeight);

                                            var strImgWidth = document.getElementById('EmbedImageWidth').value;
                                            var strImgHeight = document.getElementById('EmbedImageHeight').value;

                                            if (isNaN(strImgWidth)) document.getElementById('EmbedImageWidth').value = strImgOriWidth;
                                            if (isNaN(strImgHeight)) document.getElementById('EmbedImageHeight').value = strImgOriHeight;

                                            if (strImgWidth > strImgOriWidth) document.getElementById('EmbedImageWidth').value = strImgOriWidth;
                                            if (strImgHeight > strImgOriHeight) document.getElementById('EmbedImageHeight').value = strImgOriHeight;

                                            var strPer;
                                            if(strImgOriWidth > strImgOriHeight){

                                                strPer = (strImgOriWidth/strImgOriHeight);
                                                strImgNewWidth = (strPer*strImgHeight);
                                                strImgNewHeight = (strImgWidth/strPer);
                                            }else if(strImgOriWidth < strImgOriHeight){

                                                strPer = (strImgOriHeight/strImgOriWidth);
                                                strImgNewWidth = (strImgHeight/strPer);
                                                strImgNewHeight = (strPer*strImgWidth);

                                            }else{

                                                strPer = (strImgOriWidth/strImgOriHeight);
                                                strImgNewWidth = (strPer*strImgHeight);
                                                strImgNewHeight = (strImgWidth/strPer);
                                            }
                                            //}
                                            var curActiveObj = document.activeElement.id;
                                            if(curActiveObj == 'EmbedImageHeight'){
                                                document.getElementById('EmbedImageWidth').value = Math.round(strImgNewWidth);
                                            }else if(curActiveObj == 'EmbedImageWidth'){
                                                document.getElementById('EmbedImageHeight').value = Math.round(strImgNewHeight);
                                            }
                                            document.Embedcode.EmbedHTMLCode.value = createEmbedCode();
                                        }
                                        function createEmbedCode(){
                                            var strImgWidth = document.getElementById('EmbedImageWidth').value;
                                            var strImgHeight = document.getElementById('EmbedImageHeight').value;
                                            embedc= "<div style='padding-bottom: 2px; line-height: 0px'>";
                                            embedc+= "<a href='<?php echo $pageURl; ?>' target='_blank'>";
                                            embedc+= "<img src='<?php echo $strImgUrl; ?>' border='0' width = "+strImgWidth+" height = "+strImgHeight+"/></a>";
                                            embedc+= "</div>";
                                            embedc+= "<div style='float: left; padding-top: 0px; padding-bottom: 0px;'>";
                                            embedc+= "<p style='font-size: 13px; color: #000;'>";
<?php
                                    $pin_url = parse_url($pinDetails->pin_url);
                                    if (!empty($pin_url['host']))
                                        $pin_url = $pin_url['host'];
                                    else
                                        $pin_url = $pinDetails->pin_url;

                                    $url = $pinDetails->pin_url;
                    if (!strstr($pinDetails->pin_url, 'http') && !strstr($pinDetails->pin_url, 'https')) {
                        $url = "http://" . $pinDetails->pin_url;
                    }

?>
                                                embedc+= "&nbsp; Source :&nbsp; <a style='text-decoration: underline; font-size: 13px; color: #000;' href='<?php echo $url; ?>' target=_blank><?php echo $pin_url; ?></a>&nbsp;";
                                                embedc+= "via";
                                                embedc+= "&nbsp;<a style='text-decoration: underline; font-size: 13px; color: #000;' href='<?php echo $baseurl . 'index.php?option=com_socialpinboard&view=boarddisplay&uid=' . $user_res->user_id . '#' ?>' target=_blank><?php echo ($pinDetails->first_name . ' ' . $pinDetails->last_name) ? ucwords($pinDetails->first_name . ' ' . $pinDetails->last_name) : 'Unknown User'; ?></a>&nbsp;";
                                                embedc+="on";
                                                embedc+= "&nbsp;<a style='text-decoration: underline; color: #000;' href='<?php echo $baseurl; ?>' target='_blank'><?php echo $baseurl; ?></a>&nbsp;";
            embedc+= "</p>";
            embedc+= "</div>";
            return embedc;
        }
</script>
