<?php
/**
 * @name          : Joomla Social Pinboard
 * @version	  : 1.5.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component Pindisplay view
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_socialpinboard/css/facebox.css');
$document->addStyleSheet('templates/socialpinboard/html/com_socialpinboard/css/style.css');
$document->addStyleSheet('templates/socialpinboard/html/com_socialpinboard/css/pinboard.css');
$document->addStyleSheet('templates/socialpinboard/css/style.css');
$document->addStyleSheet('components/com_socialpinboard/css/reset.css');
//$document->addStyleSheet('components/com_socialpinboard/css/pinboard.css');
$document->addScript('https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js');
$document->addScript('components/com_socialpinboard/javascript/jquery.ui.core.js');
$document->addScript('components/com_socialpinboard/javascript/chrome.js');
$document->addScript('components/com_socialpinboard/javascript/facebox.js');
$document->addScript('components/com_socialpinboard/javascript/scroll/jquery.isotope.min.js');
$document->addScript('components/com_socialpinboard/javascript/scroll/jquery.infinitescroll.min.js');
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
$followers='';
$likes = array();
$fBoardId = '';
    $inc = '';
$pins = $this->getPindisplay;
$getCurrencySymbol =  $this->getCurrencySymbol;
$getmobileBoards = $this->getmobileBoards;
$model = $this->getModel('pindisplay');
$user_res = $model->getUserprofile();
$dispBoard = $this->displayBoard;
$totalBoards = $this->totalBoard;
$pinLikes = $this->getLikes;
$pincounts = $this->pinCounts; //echo $pincounts;exit;
$user = JFactory::getUser();
$boards = $this->boards;
$u_agent = $_SERVER['HTTP_USER_AGENT'];
$users = JRequest::getVar('uid');
$userLink = '';
if (isset($users)) {
    $userLink = '&uid=' . $users;
}
$totalBoard = $this->totalBoard;
$dispBoard = $this->displayBoard;
$profileDetails = $this->user_profile; //stores user details
$followers = $this->followers;
if ($followers['0'] != '') {
    $follwercount = count($followers);
} else {
    $follwercount = '0';
}
$followings = $this->FollowingInformation;
$repinactivities = $this->repinactivities; //stores repin activities of user
$follower_count = $this->followerInformation;
$getReportUsers = $this->getReportUsers; //stores reported users list
$reportedUsers = $this->getReportUsers; //print_r($reportedUsers);
$blockStatus = $this->blockStatus; //echo($blockStatus->status);
if ($follower_count == '') {
    $follower_count = 0;
}
if ($followings == '') {
    $followings = 0;
}
$user_result = $this->userprofile; //stores user details

//Get the follow boards
    if (count($dispBoard) != 0) {
        foreach ($dispBoard as $fboards) {

            if ((count($dispBoard) - 1) == $inc) {
                $fBoardId.= $fboards->board_id;
            } else {
                $fBoardId.= $fboards->board_id . ',';
            }
            $inc++;
        }
    }
?>
<div id="profile-header">
    <div class="fixedcontainer row clearfix">
        <div class="info">
            <?php
            foreach ($profileDetails as $profileDetails) {
                if (!$profileDetails->user_image) {
                    $srcPath = "components/com_socialpinboard/images/no_user.jpg";
                } else {
                    $userImageDetails = pathinfo($profileDetails->user_image);
                    $userProfileImage = $userImageDetails['filename'] . '_o.' . $userImageDetails['extension'];
                    $srcPath = "images/socialpinboard/avatars/" . $userProfileImage;
                }
            ?>
                <a target="_blank" href="<?php echo JURI::base() . $srcPath ?>" class="profileimage" >
                    <img src="<?php echo $srcPath; ?>" width="160" height="100%" alt="User Profile">
                </a>
                <div class="content">
                    <h1><?php echo $profileDetails->first_name . ' ' . $profileDetails->last_name; ?></h1>
                    <p class="colormuted"><?php echo $profileDetails->about; ?></p>
                    <ul id="profilelinks" class="icons">
                    <?php if ($profileDetails->facebook_profile_id) {
                    ?>
                        <li>
                            <a href="http://facebook.com/profile.php?id=<?php echo $profileDetails->facebook_profile_id; ?>" class="icon facebook" target="_blank"></a>
                        </li>
                    <?php
                    }
                    if ($profileDetails->location != '') {
                    ?>
                        <span class="icon website" ></span>
                        <li>
                        <?php echo $profileDetails->location; ?>
                    </li><?php
                    }
                    if ($profileDetails->website != '') {
                        if (!strstr($profileDetails->website, 'http') && !strstr($profileDetails->website, 'https')) {
                        $website = "http://" . $profileDetails->website;
                    }
                else
                    $website=$profileDetails->website;
                        ?>
                        <li>
                            <a href="<?php echo $website; ?>" class="icon location" target="_blank"></a>
                        </li>
                    <?php } ?>
                </ul>
                <?php if ($users != $user->id && $users != '' && $user->id != '') {
                ?>
                        <span class="flag" style="cursor:pointer;" onmouseout="bloguserhide();"  onmouseover="bloguser();">&nbsp;</span>
                <?php
                    }
                }
                ?>
                <div id="blogdialogbox" class="report-user shadow-dropdown" onmouseover="bloguser();" onmouseout="bloguserhide();"  style="display: none;">
                    <div class="arrow up"></div>
                    <div class="section report-head">
                        <?php
                        $value_receiver=$value_sender='';
                    if(!empty($reportedUsers)){
                        $value_receiver = $reportedUsers[0]->report_receiver;
                        $value_sender = $reportedUsers[0]->report_sender;
                    }
                        $users = JRequest::getInt('uid');
                        $user_id = $user->id;
                        if ($value_receiver == $users && $value_sender == $user_id) {
                        ?>
                            <div class="reported" id="reported">
                                <h2 style="color:black;"><?php echo JText::_('COM_SOCIALPINBOARD_REPORTED'); ?></h2>
                            <?php echo JText::_('COM_SOCIALPINBOARD_YOU_REPORTED_START'); ?><?php echo ' ' . $profileDetails->first_name . ' ' . $profileDetails->last_name; ?><?php echo JText::_('COM_SOCIALPINBOARD_YOU_REPORTED_END'); ?>
                        </div>
                        <?php } else {
 ?>
                            <div class="unreported" id="unreported">
                                <h2 style="color:black;">Report <?php echo ' ' . $profileDetails->first_name . ' ' . $profileDetails->last_name; ?></h2>
                            </div>
<?php } ?>
                    </div>
                    <ul class="report-type">
                        <li id="report_action_nudity" onclick="Modal.show('userreport');blocktype('<?php echo JText::_('COM_SOCIALPINBOARD_REPORT_TYPE_NUDITY'); ?>');" class="first"><a report_type="nudity"><?php echo JText::_('COM_SOCIALPINBOARD_REPORT_TYPE_NUDITY'); ?></a></li>
                        <li id="report_action_attacks" onclick="Modal.show('userreport');blocktype('<?php echo JText::_('COM_SOCIALPINBOARD_REPORST_TYPE_ATTACKS'); ?>');" class=""><a report_type="attacks"><?php echo JText::_('COM_SOCIALPINBOARD_REPORST_TYPE_ATTACKS'); ?></a></li>
                        <li id="report_action_graphic-violence" onclick="Modal.show('userreport');blocktype('<?php echo JText::_('COM_SOCIALPINBOARD_REPORT_TYPE_GRAPHIC_VIOLENCE'); ?>');" class=""><a report_type="graphic-violence"><?php echo JText::_('COM_SOCIALPINBOARD_REPORT_TYPE_GRAPHIC_VIOLENCE'); ?></a></li>
                        <li id="report_action_hate-speech" onclick="Modal.show('userreport');blocktype('<?php echo JText::_('COM_SOCIALPINBOARD_REPORT_TYPE_HATEFUL_SPEECH'); ?>');" class=""><a report_type="hate-speech"><?php echo JText::_('COM_SOCIALPINBOARD_REPORT_TYPE_HATEFUL_SPEECH'); ?></a></li>
                        <li id="report_action_self-harm" onclick="Modal.show('userreport');blocktype('<?php echo JText::_('COM_SOCIALPINBOARD_REPORT_TYPE_SELF_HARM'); ?>');" class=""><a report_type="self-harm"><?php echo JText::_('COM_SOCIALPINBOARD_REPORT_TYPE_SELF_HARM'); ?></a></li>
                        <li id="report_action_spam"  onclick="Modal.show('userreport');blocktype('<?php echo JText::_('COM_SOCIALPINBOARD_REPORT_TYPE_SPAM'); ?>');" class=""><a report_type="spam"><?php echo JText::_('COM_SOCIALPINBOARD_REPORT_TYPE_SPAM'); ?></a></li>
                        <li id="report_action_other" onclick="Modal.show('userreport');blocktype('<?php echo JText::_('COM_SOCIALPINBOARD_REPORT_TYPE_OTHER'); ?>');" class=""><a report_type="other"><?php echo JText::_('COM_SOCIALPINBOARD_REPORT_TYPE_OTHER'); ?></a></li>
                    </ul>
                    <div class="section block-head">
                        <h3>
                            <div id="userblock" style="color: #000;text-transform: capitalize;"></div>

                            <?php if ($user_id != $users && $user_id != '' && $users != '0') { ?>
                                <div id="category_user_follow<?php echo $user_id; ?>" style="padding-right: 5px;" >
                                <?php if (!empty($blockStatus) && $blockStatus->status == '0') {
                                ?>
                                    <input type="button" name="followuser" id="textdisplay" style="float:right;cursor: pointer;" value="<?php echo 'Block'; ?>" class="Button Button13 RedButton clickable blockuserbutton" onclick="bloguserfully();">
                                <?php } else if (!empty($blockStatus) && $blockStatus->status == '1') {
 ?>
                                    <input type="button" style="float:right;cursor: pointer;" id="textdisplay"   value="<?php echo 'Unblock' ?>"class="Button Button13 RedButton clickable blockuserbutton" onclick="unblock('<?php echo $user_id; ?>','<?php echo $users; ?>');"  />
<?php } else if (empty($blockStatus)) { ?>
                                    <input type="button" name="followuser" id="textdisplay" style="float:right;cursor: pointer;" value="<?php echo 'Block'; ?>" class="Button Button13 RedButton clickable blockuserbutton" onclick="bloguserfully();">
<?php } ?>
                            </div>
<?php } if($user_id == $users || $users == '0') { ?>
<input type="button" name="followuser" id="textdisplay" style="float:right;cursor: pointer;" value="" class="Button Button13 RedButton clickable blockuserbutton" >
<?php }?>
                            <input type="button" name="followuser" id="nameuser" style="display:none;" value="<?php echo $profileDetails->first_name . ' ' . $profileDetails->last_name; ?>" class="Button Button13 RedButton clickable blockuserbutton" >
                        </h3>
                        <div class="report-user" id="blockUnblockUsermessagefirst"></div>
                        <div class="report-user" id="blockUnblockUsermessagesecond"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="repins">
            <h3><?php echo JText::_('COM_SOCIALPINBOARD_YOU_REPINS_FROM'); ?></h3>
            <ul>
                <?php
                            foreach ($repinactivities as $test) {
                                $resultmodrepin = socialpinboardModelpindisplay::getModRepinpinUserdetails($test->pin_repin_id);
                                foreach ($resultmodrepin as $named) {
                                    if (!$named->user_image) {
                                        $srcPath = "components/com_socialpinboard/images/no_user.jpg";
                                    } else {
                                        $userImageDetails = pathinfo($named->user_image);
                                        $userProfileImage = $userImageDetails['filename'] . '_o.' . $userImageDetails['extension'];
                                        $srcPath = "images/socialpinboard/avatars/" . $userProfileImage;
                                    }
                ?>
                                    <li>
                                        <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=boarddisplay&uid=' . $named->pin_user_id); ?>">
                                            <img src="<?php echo $srcPath; ?>"><strong class="user-pin"><?php echo $named->username; ?></strong></a>
                                    </li>
                <?php }
                            } ?>
                        </ul>
                    </div>
                </div>
                <div id="ContextBar" class="clearfix">
                    <div id="fixed_container">
                        <ul>
                            <li><a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=boarddisplay' . $userLink) ?>" >
                        <?php
                            if ($totalBoards > 1)
                                echo $totalBoards . ' ' . JText::_('COM_SOCIALPINBOARD_BOARDS');else
                                echo $totalBoards . ' ' . JText::_('COM_SOCIALPINBOARD_BOARD');
                        ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=pindisplay' . $userLink) ?>" class="selected">
                        <?php
                            if (count($pincounts) > 1)
                                echo count($pincounts) . ' ' . JText::_('COM_SOCIALPINBOARD_PINS');else
                                echo count($pincounts) . ' ' . JText::_('COM_SOCIALPINBOARD_PIN');
                        ?></a>
                    </li>
                    <li>
                        <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=likes' . $userLink) ?>">
                        <?php
                            if ($pinLikes > 1)
                                echo $pinLikes . ' ' . JText::_('COM_SOCIALPINBOARD_LIKES');else
                                echo $pinLikes . ' ' . JText::_('COM_SOCIALPINBOARD_LIKE');
                        ?></a>
                    </li>
                </ul>
<?php if ($user_id) { ?>
                                <div  class="center_btn">
<?php if (!empty($blockStatus) && $blockStatus->status == '1') { ?>
                                    <div id="follow" >
                    <?php if ($users != $user->id && $users != '' && $user->id != '') { ?>
                                        <input type="button" name="unblock" value="<?php echo 'Unblock'; ?>" id="textdisplayUnblock" class="unfollow_btn" onclick="unblock('<?php echo $user_id; ?>','<?php echo $users; ?>');" />
                    <?php }
                    ?>
                                </div>
                <?php } else {
                ?>
                                    <div id="follow" >
                    <?php
                                    if ($users != $user->id && $users != '' && $user->id != '') {
                                        if (($followers == 'all') || ($totalBoard == $follwercount)) {
                    ?>
                                            <input type="button" name="unfollow" value="<?php echo JText::_('COM_SOCIALPINBOARD_UNFOLLOW'); ?>" id="unfollow" class="unfollow_btn" onclick="unfollowall(<?php echo $user->id . ',' . $users; ?>,'<?php echo $fBoardId; ?>');" />
                    <?php
                                        } else {
                    ?><div id="followalldiv">
                                                <input type="button" name="followall" value="<?php echo JText::_('COM_SOCIALPINBOARD_FOLLOW_ALL'); ?>" id="followall" onclick="followall(<?php echo $user->id . ',' . $users; ?>,'<?php echo $fBoardId; ?>');"/>
                                            </div> <?php
                                        }
                                    } else {
                    ?>
                                        <div class="action-btn">
                                            <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=profile'); ?>" class="profile-edit btn5">
                            <?php echo JText::_('COM_SOCIALPINBOARD_YOU_EDIT_PROFILE'); ?>
                                    </a>
                                </div>
                    <?php }
                    ?>
                                </div>
                <?php } ?>
                            </div>
            <?php } ?>
                            <div class="following-details">
                <?php
                            if (JRequest::getInt('uid')) {
                                $userId = JRequest::getInt('uid');
                ?>
                                <a href="<?php echo JRoute::_("index.php?option=com_socialpinboard&view=follow&follower=1&uid=" . $userId, false); ?>" ><?php echo $followings . ' ' . JText::_('COM_SOCIALPINBOARD_FOLLOWING_CAPS'); ?></a>     <a href="<?php echo JRoute::_("index.php?option=com_socialpinboard&view=follow&follower=0&uid=" . $userId, false); ?>" ><?php echo $follower_count . ' ' . JText::_('COM_SOCIALPINBOARD_FOLLOWERS_CAPS'); ?></a>
                <?php } else {
                ?>
                                <a href="<?php echo JRoute::_("index.php?option=com_socialpinboard&view=follow&follower=1", false); ?>" ><?php echo $followings . ' ' . JText::_('COM_SOCIALPINBOARD_FOLLOWING_CAPS'); ?></a>     <a href="<?php echo JRoute::_("index.php?option=com_socialpinboard&view=follow&follower=0", false); ?>" ><?php echo $follower_count . ' ' . JText::_('COM_SOCIALPINBOARD_FOLLOWERS_CAPS'); ?></a>
                <?php } ?>
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
            <div id="container">


    <?php
                            if (count($pins) != 0) {
    ?>

    <?php
                                foreach ($pins as $arrPins) {

                                    if ($user->get('id') != 0) {
                                        $like_res = $model->getLikes($arrPins->pin_id, $user->id);
                                    }
    ?>
                                    <div class="pin <?php if ($arrPins->gift == '1') { echo"gift_pin"; } ?>" id="pin_div_<?php echo $arrPins->pin_id; ?>">
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
                                            <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=pin&pinid=' . $arrPins->pin_id); ?>" class="PinImage ImgLink"   style="position: absolute;z-index: 1;top: 25%;left: 40%;"><img src="<?php echo JURI::base() . "components/com_socialpinboard/images/play_btn.png"; ?>" width="50" height="50" alt="" class="play_button" ></a>
            <?php //if ($arrPins->gift == '1') {
 ?>
<!--                                            <strong class="PriceContainer"><strong class="price">-->
                <?php //echo $arrPins->price; ?>
<!--                                                </strong></strong>-->
                <?php //} ?>
<?php } ?>

                                        <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=pin&pinid=' . $arrPins->pin_id); ?>" class="PinImage ImgLink"  ><img src="<?php echo $src_path; ?>" alt="" class="PinImageImg">
<?php if ($arrPins->gift == '1') { ?> <strong class="PriceContainer"><strong class="price"><?php echo $getCurrencySymbol.$arrPins->price; ?></strong></strong><?php } ?>
                                    </a>

            <?php
                                    } else {
                                        ?>
<a id="pinnerdiv<?php echo $arrPins->pin_id;?>"  href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=pin&pinid=' . $arrPins->pin_id); ?>" class="PinImage ImgLink" >
                                        <?php
                                        if ($arrPins->link_type == 'youtube' || $arrPins->link_type == 'vimeo') {
            ?>
                                           <img style="position: absolute;z-index: 1;top: 25%;left: 40%;" src="<?php echo JURI::base() . "components/com_socialpinboard/images/play_btn.png"; ?>" width="50" height="50" alt="" class="play_button" >
                <?php //if ($arrPins->gift == '1') {
                ?>
<!--                                            <strong class="PriceContainer"><strong class="price">-->
                    <?php //echo $arrPins->price; ?>
<!--                                                </strong></strong>-->
                    <?php // } ?>
<?php } ?>
                                    <img src="<?php echo $src_path; ?>" alt="" class="PinImageImg">
                <?php if ($arrPins->gift == '1') { ?> <strong class="PriceContainer"><strong class="price"><?php echo $getCurrencySymbol.$arrPins->price; ?></strong></strong><?php } ?>
                                    </a>
            <?php
                                    }
            ?>


 <div id="brw-btn_<?php echo $arrPins->pin_id; ?>" >
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
                        } ?>
                        <div class="btn-pinlist btn-repin" id="websiteRepin">
                                        <input type="button" onclick="getpin(<?php echo $arrPins->pin_id; ?>,'<?php echo JURI::base(); ?>',<?php echo $userid; ?>)" title="<?php echo JText::_('COM_SOCIALPINBOARD_REPIN'); ?>" id="showrepindiv<?php echo $arrPins->pin_id; ?>" value="<?php echo JText::_('COM_SOCIALPINBOARD_REPIN'); ?>" class="report_repin" />
                                    </div>
                <?php
                                    if ($arrPins->pin_user_id != $userid) {
                ?>
                                        <div class="btn-pinlist btn-like" id="likebtn<?php echo $arrPins->pin_id; ?>">

                                            <input type="button" class="pin_like" onclick="getlike(<?php echo $arrPins->pin_id; ?>,<?php echo $userid; ?>,<?php echo $flag = 0; ?>)" title="<?php echo JText::_('COM_SOCIALPINBOARD_LIKE'); ?>" id="like<?php echo $arrPins->pin_id; ?>" style="<?php echo $likestyle; ?>" value="<?php echo JText::_('COM_SOCIALPINBOARD_LIKE'); ?>" />
                                            <input type="button" class="pin_unlike" onclick="getlike(<?php echo $arrPins->pin_id; ?>,<?php echo $userid; ?>,<?php echo $flag = 1; ?>)" title="<?php echo JText::_('COM_SOCIALPINBOARD_UNLIKE'); ?>" id="unlike<?php echo $arrPins->pin_id; ?>" style="<?php echo $unlikestyle; ?>" value="<?php echo JText::_('COM_SOCIALPINBOARD_UNLIKE'); ?>" />
                                        </div>
                <?php
                                    } elseif ($arrPins->pin_user_id == $userid) {
                ?>
                                        <div class="btn-pinlist btn-like btn-edit" >

                                            <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=pinedit&pinId=' . $arrPins->pin_id); ?>" class="pin_edit"   title="<?php echo JText::_('COM_SOCIALPINBOARD_EDIT'); ?>" id="like<?php echo $arrPins->pin_id; ?>" style="<?php echo $likestyle; ?>"><?php echo JText::_('COM_SOCIALPINBOARD_EDIT'); ?></a>

                                        </div>

                <?php
                                    }
                ?> <div class="btn-pinlist btn-comment">
                                        <input type="button" onclick="comment(<?php echo $arrPins->pin_id; ?>)"  title="<?php echo JText::_('COM_SOCIALPINBOARD_COMMENT'); ?>" id="comment<?php echo $arrPins->pin_id; ?>" value="<?php echo JText::_('COM_SOCIALPINBOARD_COMMENT'); ?>" />
                                    </div>
                                </div>


                </div>

<script type="text/javascript">
                        var txt =  navigator.platform ;
           if(txt =='iPod'|| txt =='iPhone'|| txt =='Linux armv7l' || txt == 'Linux armv6l'){}else
                {
                  <?php $test="style=display:none;"?>;
                }
                        </script>
                            <div id="mob-btns_<?php echo $arrPins->pin_id; ?>" class="mob-btns btns" <?php echo $test; ?> >

                        <?php
                        //if the user is logged in and like is set, make the like as unlike
                        $unlikestyle = 'display:none';
                        $likestyle = 'display:block';
                        if ($user->get('id') != 0 && (in_array($arrPins->pin_id,  $likes))) {
                            $likestyle = 'display:none';
                            $unlikestyle = 'display:block';
                        }
                        ?>
                        <!-- Repin button for the all the user -->
                        <div class="btn-pinlist btn-repin"  id="mobileRepin">
                            <input type="button" onclick="mobgetpin(<?php echo $arrPins->pin_id; ?>,'<?php echo JURI::base(); ?>',<?php echo $userid; ?>)" title="<?php echo JText::_('COM_SOCIALPINBOARD_REPIN'); ?>" id="showrepindiv<?php echo $arrPins->pin_id; ?>" value="<?php echo JText::_('COM_SOCIALPINBOARD_REPIN'); ?>" class="report_repin" />
                        </div>
                        <?php
                         //if the pinner and logged in user are same,display edit button, else like/unlike button
                        if ($arrPins->pin_user_id != $userid) {
                            ?>
                            <div class="btn-pinlist btn-like" id="likebtn<?php echo $arrPins->pin_id; ?>">

                                <input type="button" class="pin_like" onclick="getlike(<?php echo $arrPins->pin_id; ?>,<?php echo $userid; ?>,<?php echo $flag = 0; ?>)" title="<?php echo JText::_('COM_SOCIALPINBOARD_LIKE'); ?>" id="like<?php echo $arrPins->pin_id; ?>" style="<?php echo $likestyle; ?>" value="<?php echo JText::_('COM_SOCIALPINBOARD_LIKE'); ?>" />
                                <input type="button" class="pin_unlike" onclick="getlike(<?php echo $arrPins->pin_id; ?>,<?php echo $userid; ?>,<?php echo $flag = 1; ?>)" title="<?php echo JText::_('COM_SOCIALPINBOARD_UNLIKE'); ?>" id="unlike<?php echo $arrPins->pin_id; ?>" style="<?php echo $unlikestyle; ?>" value="<?php echo JText::_('COM_SOCIALPINBOARD_UNLIKE'); ?>" />
                            </div>
                            <?php
                        } elseif ($arrPins->pin_user_id == $userid) {
                            ?>
                            <div class="btn-pinlist btn-like btn-edit" >
                                <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=pinedit&pinId=' . $arrPins->pin_id); ?>" class="pin_edit"   title="<?php echo JText::_('COM_SOCIALPINBOARD_EDIT'); ?>" id="like<?php echo $arrPins->pin_id; ?>" >
                                    <?php echo JText::_('COM_SOCIALPINBOARD_EDIT'); ?>
                                </a>
                            </div>
                            <?php
                        }
                        ?>
                        <!-- display the comment button -->
                        <div class="btn-pinlist btn-comment">
                            <input type="button" onclick="comment(<?php echo $arrPins->pin_id; ?>,<?php echo $userid ?>)"  title="<?php echo JText::_('COM_SOCIALPINBOARD_COMMENT'); ?>" id="comment<?php echo $arrPins->pin_id; ?>" value="<?php echo JText::_('COM_SOCIALPINBOARD_COMMENT'); ?>" />
                        </div>
                    </div>



  <script type="text/javascript">
       function mobileversionbtn(){
            var txt =  navigator.platform ;//alert(txt);
            if(txt =='iPod'|| txt =='iPhone'|| txt =='Linux armv7l' || txt =='Linux armv6l' )
                {
                    document.getElementById('mob-btns_<?php echo $arrPins->pin_id; ?>').style.display = 'block';
                    document.getElementById('brw-btn_<?php echo $arrPins->pin_id; ?>').style.display = 'none';
                    document.getElementById('mobileRepin').style.display = 'block';
                    document.getElementById('websiteRepin').style.display = 'none';
                    document.getElementById('brw-btn_<?php echo $arrPins->pin_id; ?>').innerHTML = ' ';
                }
                else
                {
                var scr2 = jQuery.noConflict();
                    scr2("#pinnerdiv<?php echo $arrPins->pin_id;?>").addClass("facebox");
                    document.getElementById('mob-btns_<?php echo $arrPins->pin_id; ?>').style.visibility = 'hidden';
                    document.getElementById('brw-btn_<?php echo $arrPins->pin_id; ?>').style.display = 'block';
                    document.getElementById('websiteRepin').style.display = 'block';
                    document.getElementById('mobileRepin').style.display = 'none';
                    document.getElementById('mob-btns_<?php echo $arrPins->pin_id; ?>').innerHTML = ' ';
                }
                }

                window.onload = mobileversionbtn();
    </script>

                            </div>

                            <p class="description"><?php
                                    if (str_word_count($arrPins->pin_description) < 250) {
                                        echo $arrPins->pin_description;
                                    } else {
                                        $pin_description = substr($arrPins->pin_description, 0, 250);
                                        echo $pin_description . ' .... ';
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
                                        echo $arrPins->pin_comments_count . ' ' . JText::_('COM_SOCIALPINBOARD_COMMENT');
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
                                        <img height="30" src="<?php echo JURI::base() . 'images/socialpinboard/avatars/' . rawurlencode($arrPins->user_image); ?>" title="<?php echo $arrPins->first_name . ' ' . $arrPins->last_name; ?>"
                                             alt="<?php echo $arrPins->first_name . ' ' . $arrPins->last_name; ?>" width="30" class="ImgLink thumb-img"/>
                     <?php
                                    } else {
                     ?>
                                   <img src="<?php echo JURI::Base(); ?>/components/com_socialpinboard/images/no_user.jpg" title="<?php echo $arrPins->first_name . ' ' . $arrPins->last_name; ?>" alt="<?php echo $arrPins->first_name . ' ' . $arrPins->last_name; ?>" height="30" width="30" class="ImgLink thumb-img" />
                <?php
                                    }
                ?>
                                </a>
                                <div class="board_grid board_content"> <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=boarddisplay&uid=' . $arrPins->pin_user_id); ?>"><?php echo ucfirst($arrPins->first_name) . " " . ucfirst($arrPins->last_name); ?></a> <?php echo JText::_('COM_SOCIALPINBOARD_ONTO') . ' '; ?><a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=boardpage&bId=' . $arrPins->board_id); ?>"><?php echo $arrPins->board_name; ?></a></div>
                            </div>

                            <div class="comments clearfix" id="commentDiv<?php echo $arrPins->pin_id; ?>">
                                <ul>
                <?php
                                    $comment_res = $model->getComments($arrPins->pin_id);


                                    if ($comment_res != '') {
                                        $i = 0;
                                        $new_flag = 0;
                                        foreach ($comment_res as $comment) {
                                            if ($i < 6) {
                ?>
                                                <li>
                                                    <div class="comment clearfix">
                                                        <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=boarddisplay&uid=' . $comment->pin_user_comment_id); ?>">
                            <?php
                                                if ($comment->user_image == '') {
                            ?>
                                                    <img height="30" src="<?php echo JURI::base() . 'components/com_socialpinboard/images/no_user.jpg' ?>" title="<?php echo $arrPins->username; ?>"
                                                         alt="<?php echo $arrPins->username; ?>" width="30" class="ImgLink thumb-img"/>
                                 <?php
                                                } else {
                                 ?><img height="30" src="<?php echo JURI::base() . 'images/socialpinboard/avatars/' . rawurlencode($comment->user_image); ?>" title="<?php echo $arrPins->username; ?>"
                                                         alt="<?php echo $arrPins->username; ?>" width="30" class="ImgLink thumb-img"/>
                                 <?php
                                                }
                                 ?>
                                       </a><div class="board_grid board_content">
                                           <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=boarddisplay&uid=' . $comment->pin_user_comment_id); ?>">
                                               <span class="user"><?php echo $comment->first_name . " " . $comment->last_name; ?></span>
                                           </a>
                                           &nbsp;
                            <?php echo $comment->pin_comment_text ?></div>

                                        </div> </li>
                <?php
                                            } else {
                ?>

                <?php
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

                                        <div class="newcomment">

                                            <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=boarddisplay&uid=' . $user->id); ?>" class="ImgLink ">

                        <?php
                                    if ($user_res[0]->user_image == '') {
                        ?>
                                        <img height="30" src="<?php echo JURI::base() . 'components/com_socialpinboard/images/no_user.jpg' ?>" title="<?php echo $user_res[0]->username; ?>"
                                             alt="<?php echo $user_res[0]->username; ?>" width="30" />
                             <?php
                                    } else {
                             ?>
                                   <img height="30" src="<?php echo JURI::base() . 'images/socialpinboard/avatars/' . rawurlencode($user_res[0]->user_image); ?>" title="<?php echo $user_res[0]->username; ?>"
                                        alt="<?php echo $user_res[0]->username; ?>" width="30" />
                             <?php
                                    }
                             ?>

                           </a>
                    <?php
                                    if ($userid) {
                    ?>


                                        <textarea id="commentContent<?php echo $arrPins->pin_id; ?>" value="<?php echo JText::_('COM_SOCIALPINBOARD_ADD_A_COMMENT'); ?>" name="content"
                                                  onfocus="if (value == '<?php echo JText::_('COM_SOCIALPINBOARD_ADD_A_COMMENT'); ?>') { value = ''; }"
                                                  onblur="if (value == '') { value = '<?php echo JText::_('COM_SOCIALPINBOARD_ADD_A_COMMENT'); ?>'; }"  maxlength="200"></textarea>
                                        <input type="button" class="button" onclick="doHomeComment(<?php echo $arrPins->pin_id; ?>,'<?php echo $user_res[0]->first_name; ?>','<?php echo $user_res[0]->last_name; ?>','<?php echo $user_res[0]->user_image; ?>','<?php echo JRoute::_('index.php?option=com_socialpinboard&view=boarddisplay&uid=' . $user->id); ?>')" value="<?php echo JText::_('COM_SOCIALPINBOARD_COMMENT'); ?>" />
                    <?php
                                    }
                    ?>
                                </div>

                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>




    <?php
                                }
                            } else {
    ?>

                                <div align="center" class="error message" id="login_error_msg">
                                    <ul>
                                        <li><?php echo JText::_('COM_SOCIALPINBOARD_SORRY_NO_PINS'); ?></li>
                                    </ul>
                                </div>
    <?php
                            }
    ?>

                        </div>

                        <div id="userreport" class="ModalContainer">
                            <div class="modal wide PostSetup" id="reportuser">
                                <div id="postsetup">
                                    <div id="outputs"></div>
                                    <h2>Report <?php echo $profileDetails->first_name . ' ' . $profileDetails->last_name; ?></h2>
                                    <a class="closebutton" onclick="javascript:document.getElementById('outputs').innerHTML = '';Modal.close('userreport');"></a>
                                    <p id="ReportLabel"><?php echo JText::_('COM_SOCIALPINBOARD_CONFIRM_REPORT_START') . $profileDetails->first_name . ' ' . $profileDetails->last_name; ?> ?</p>
                                    <p style="font-size: 18px;font-weight: bold;display:none;"><?php echo JText::_('COM_SOCIALPINBOARD_YOU_REASON'); ?><input type="text" value="" readonly id="inputBlocktype" style="width:50%;margin-left:30px;font-size: 18px;font-weight: bold;" name="blocktype"></p>
                                    <div id="reportbutton"><input type="button" style="float: left;" id="report_btn" onclick="reportUser(); return false"  data-form="ReportModal" value="<?php echo JText::_('COM_SOCIALPINBOARD_YOU_REPORT'); ?>" /></div>
                                    <input type="button"  style="float: left;margin-left: 5px;" id="report_sbumit_btn" onclick="reportUser();block('<?php echo $user_id; ?>','<?php echo $users; ?>');"  data-form="ReportModal" value="<?php echo JText::_('COM_SOCIALPINBOARD_YOU_REPORT_BLOCK');
                            ; ?>" />
                                    <input type="button" class="closefollow" id="report_submitwhite_btn" onclick="javascript:document.getElementById('outputs').innerHTML = '';Modal.close('userreport'); return false"  data-form="ReportModal" value="<?php echo JText::_('COM_SOCIALPINBOARD_YOU_DONT_REPORT');
                            ; ?>" />
                                </div>
                            </div>
                            <div class="overlay" style="opacity: 1.0;"></div>
                        </div>
                        <div id="blogdisplay" class="ModalContainer">
                            <div class="modal wide PostSetup" id="blogdisplaymodal">
                                <div id="postsetup">
                                    <h2>Block <?php echo $profileDetails->first_name . ' ' . $profileDetails->last_name; ?></h2>
                                    <a class="closebutton"  onclick="javascript:document.getElementById('outputs').innerHTML = '';Modal.close('blogdisplay');"></a>
                                    <form name="report1" id="report1" action="#" method="post" class="Form FancyForm">
                                        <div id="outputs"></div>
                                        <p id="ReportLabelblock"><?php echo JText::_('COM_SOCIALPINBOARD_YOU_REPORTED_MESSAGE_START') . $profileDetails->first_name . ' ' . $profileDetails->last_name . JText::_('COM_SOCIALPINBOARD_YOU_REPORTED_MESSAGE_END'); ?> </p>
                                        <p style="font-size: 18px;font-weight: bold;display:none;">Reason :<input type="text" value="" readonly id="inputBlocktype" style="width:50%;margin-left:30px;font-size: 18px;font-weight: bold;" name="blocktype"></p>
                                        <input type="button" style="float: left;" id="report_sbumit_btn" onclick="block('<?php echo $user_id; ?>','<?php echo $users; ?>');"  data-form="ReportModal" value="<?php echo JText::_('COM_SOCIALPINBOARD_YOU_BLOCK') . '  ' . $profileDetails->first_name . ' ' . $profileDetails->last_name; ?>"/>
                                        <input type="button"  id="report_submitwhite_btn" onclick="Modal.close('blogdisplay');loadthePage();"  data-form="ReportModal" value="<?php echo JText::_('COM_SOCIALPINBOARD_YOU_DONT_BLOCK'); ?>" />
                                    </form>
                                </div>
                            </div>
                            <div class="overlay" style="opacity: 1.0;"></div>
                        </div>
<?php
                $user = JFactory::getUser();
                $querystring = "";
        $users = JRequest::getInt('uid');
        if($users!=''){
            $userId = $users;
            $querystring = "&uid=$userId";
        }else{
        $userId = $user->get('id');
        $querystring = "&uid=$userId";
        }

$routerUrl=JRoute::_(JURI::base().'index.php?option=com_socialpinboard&amp;view=pindisplay'.$querystring.'&amp;tmpl=component&amp;page=1');
        ?>
<!-- Mobile Repin Starts here-->
    <div id="mobrepin" style="display:none;">
        <h2><?php echo JText::_('COM_SOCIALPINBOARD_REPIN');?></h2>
      <div class="ImagePicker">
         <div class="Images" id="mobimagepin"><img src="" name="pinImage" id="mobpinImage"></div>
      </div>
        <ul>
            <li>
                 <select name="repin_board" id="mobrepin_board" style="margin-top: 150px;">
                   <?php foreach ($getmobileBoards as $getmobileBoards) {?>
                     <option value=" <?php echo $getmobileBoards->board_id; ?>"> <?php echo $getmobileBoards->board_name;?></option>
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
                      <?php echo JText::_('COM_SOCIALPINBOARD_CREATE');?>
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
                 <input type="button" id="mobuploadPin" onclick="return mobajxGetBoards('<?php echo JURI::base(); ?>');" value="<?php echo JText::_('COM_SOCIALPINBOARD_PIN_IT');?>"/>
                 <input type="button" id="mobcancel" onclick="repinCancel();" value="<?php echo JText::_('COM_SOCIALPINBOARD_CANCEL');?>"/>
               </div>
             </li>
             <li>
                 <div id="mobPostwait" class="PostSuccess" style="display:none;font-size: 20px;">
                    <?php echo JText::_('COM_SOCIALPINBOARD_REPINNING_PLEASE_WAIT');?>
                 </div>
                 <div id="mobPostsuccess" class="PostSuccess" style="display:none;font-size: 20px;">
                 <?php echo JText::_('COM_SOCIALPINBOARD_REPIN_SUCCESSFULLY');?>
                 </div>
              </li>
        </ul>
    </div>
   <!--Mobile Repin End Here -->
                        <nav id="page-nav" style="display:none;">
                            <a style="display:none;" id="navpage" href="<?php echo $routerUrl; ?>"></a>
                        </nav>

                        <script>
                            var follow_all_lang="<?php echo JText::_('COM_SOCIALPINBOARD_FOLLOW_ALL'); ?>";
                            var un_follow_lang="<?php echo JText::_('COM_SOCIALPINBOARD_UNFOLLOW'); ?>";
                            var userid = '<?php echo $user_id; ?>';
                            if(userid !='0'){
                                jQuery(document).ready(function(){
                                    var unblock = document.getElementById('textdisplay').value;
                                    var name = document.getElementById('nameuser').value;
                                    if(unblock == 'Unblock'){
                                        document.getElementById('userblock').innerHTML = 'Unblock '+name;
                                        document.getElementById('blockUnblockUsermessagefirst').innerHTML = 'If you unblock '+name;
                                        document.getElementById('blockUnblockUsermessagesecond').innerHTML ='you will be able to Follow each other and interact with each others pins.';
                                        return;
                                    }
                                    else if(unblock == 'Block'){
                                        document.getElementById('userblock').innerHTML = 'block '+name;
                                        document.getElementById('blockUnblockUsermessagefirst').innerHTML = 'If you block '+name;
                                        document.getElementById('blockUnblockUsermessagesecond').innerHTML = 'you wont be able to Follow each other, or interact with each others pins.';
                                        return;
                                    }
                                });
                            }
                            function textdisplay()
                            {
                                var unblock = document.getElementById('textdisplay').value;
                                var name = document.getElementById('nameuser').value;
                                if(unblock == 'Unblock'){
                                    document.getElementById('userblock').innerHTML = 'Unblock '+name;
                                    document.getElementById('blockUnblockUsermessagefirst').innerHTML = 'If you unblock '+name;
                                    document.getElementById('blockUnblockUsermessagesecond').innerHTML ='you will be able to Follow each other and interact with each others pins.';
                                    return;
                                }
                                else if(unblock == 'Block'){
                                    document.getElementById('userblock').innerHTML = 'block '+name;
                                    document.getElementById('blockUnblockUsermessagefirst').innerHTML = 'If you block '+name;
                                    document.getElementById('blockUnblockUsermessagesecond').innerHTML = 'you wont be able to Follow each other, or interact with each others pins.';
                                    return;
                                }
                            }
                            function bloguser(){
                                document.getElementById('blogdialogbox').style.display = "block";
                            }
                            function userReport(){
                                document.getElementById('userReport').style.display = "block";
                            }

                            function bloguserhide(){
                                document.getElementById('blogdialogbox').style.display = "none";
                                return true;
                            }
                            function blocktype(type){
                                document.getElementById('inputBlocktype').value = type;
                            }
                            function bloguserfully(){
                                document.getElementById('blogdisplay').style.display = "block";
                                document.getElementById('blogdialogbox').style.display = "none";
                            }
                            function timeout_trigger() {
                                document.getElementById("outputs").innerHTML="";
                                Modal.close('userreport');
                            }

                            function reportUser(){
                                var reporttype = document.getElementById('inputBlocktype').value;
                                var xmlhttp;
                                //var reason;
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
                                var userid = '<?php echo JRequest::getInt('uid'); ?>';
                                xmlhttp.send('reporttype=' + reporttype+'&uservalue='+userid);
                                setTimeout('timeout_trigger()', 3000);
                            }

                            var scr = jQuery.noConflict();  scr(document).ready(function($){
 var txt =  navigator.platform ;
                                scr('.facebox').facebox({
                                    loadingImage : '<?php echo JURI::base(); ?>/components/com_socialpinboard/images/loading.gif',
                                    closeImage   : '<?php echo JURI::base(); ?>/components/com_socialpinboard/images/closelabel.png',
                                    currentUrl    :document.location.href
                                }
                            );

                                var $container = scr('#container');

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
                                        finishedMsg: "<?php echo JText::_('COM_SOCAILPINBOARD_NO_MORE_PINS'); ?>",
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
if(txt =='iPod'|| txt =='iPhone'|| txt =='Linux armv7l' || txt == 'Linux armv6l'){
                scr('.mob-btns').show();
                }else{
                     scr('.mob-btns').hide();
                }
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
                                if(scr("#commentContent"+pinId).val()!="<?php echo JText::_('COM_SOCIALPINBOARD_ADD_A_COMMENT'); ?>"&&scr("#commentContent"+pinId).val()!=""){
                                    scr.ajax({
                                        type:"POST",
                                        url:"?option=com_socialpinboard&view=ajaxcontrol&tmpl=component&task=getcommentinfo",
                                        data:{'pin_id':pinId,"comment":scr("#commentContent"+pinId).val()},
                                        success:function(message) {
                                            var obj = jQuery.parseJSON(message);
                                            var message = obj.comment;
                                            scr("#commentContent"+pinId).val('<?php echo JText::_('COM_SOCIALPINBOARD_ADD_A_COMMENT'); ?>');
                                            if(message != "error") {

                                                var message1 ='<li><div class="comment clearfix " ><a href="'+userUrl+'">';
                                                if(userImage=='')
                                                {
                                                    message1 += '<img height="30" src="<?php echo JURI::base() ?>components/com_socialpinboard/images/no_user.jpg" title="'+firstName+lastName+'"  alt="'+firstName+lastName+'" width="30" class="ImgLink thumb-img"></a>';

                                                }else
                                                {
                                                    message1 += '<img height="30" src="<?php echo JURI::base() ?>images/socialpinboard/avatars/'+userImage+'" title="'+firstName+lastName+'"  alt="'+firstName+lastName+'" width="30" class="ImgLink thumb-img"></a>';
                                                }

                                                message1 += '<p class="board_grid board_content"><a href="'+userUrl+'"><span class="user">'+firstName+' '+lastName+'</span></a>';
                                                message1+=' &nbsp;'+message+'</p></div></li>';

                                                scr('#commentDiv' + pinId + ' ul').append(message1);
                                                scr("#commentscountspan"+pinId).show();
                                                scr('#container').masonry( 'reload' );
                                                var span;
                                                var count = 0;
                                                if( scr("#commentscountspan"+pinId).text()){

                                                    count =parseInt(scr("#commentscountspan"+pinId).text().substring(0,scr("#commentscountspan"+pinId).text().indexOf(" ")))+1;
                                                    var counts =parseInt(scr("#commentsspan"+pinId).text().substring(0,scr("#commentscountspan"+pinId).text().indexOf(" ")))+1;

                                                    span = count+" <?php echo JText::_('COM_SOCIALPINBOARD_COMMENTS') ?> ";


                                                }else{
                                                    span= "1 <?php echo JText::_('COM_SOCIALPINBOARD_COMMENT') ?> ";
                        }
                        scr("#commentscountspan"+pinId).text(span);
                        scr("#commentsspan"+pinId).text(counts);

                        //scr('#container').masonry( 'reload' );
                    }
                }
            });
        }
    }

</script>


