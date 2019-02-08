<?php
/**
 * @name          : Joomla Social Pinboard
 * @version	  : 1.5.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component Follow view
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');
$document = JFactory::getDocument();
//$document->addStyleSheet('components/com_socialpinboard/css/style.css');
$document->addStyleSheet('components/com_socialpinboard/css/reset.css');
$document->addStyleSheet('templates/socialpinboard/html/com_socialpinboard/css/style.css');
$document->addStyleSheet('templates/socialpinboard/css/style.css');
$document->addScript('https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js');
$document->addScript('components/com_socialpinboard/javascript/chrome.js');
$document->addScript('components/com_socialpinboard/javascript/facebox.js');

$document->addStyleSheet('components/com_socialpinboard/css/edit-style.css');
$document->addStyleSheet('templates/socialpinboard/html/com_socialpinboard/css/pinboard.css');
$user = JFactory::getUser();
$userID = $user->get('id');
$fBoardId = '';
$inc = '';
$followings = $this->getFollowing;
$follow = JRequest::getvar('follower');
$pincount = $this->pincount;
$totalBoard = $this->totalBoard;
$dispBoard = $this->displayBoard;
$followers = $this->getFollower;
if ($userID) {
    $follow_user_details = $this->follow_user_details;
    if ($follow_user_details['0'] != '') {
        $follwercount = count($follow_user_details);
    } else {
        $follwercount = '0';
    }
}
//     echo "<pre>";print_r($follwercount);exit;
$pinLikes = $this->getLikes;
$users = JRequest::getInt('uid');
if (isset($users) && $users != '0') {
    $IDuser = $users;
} else {
    $IDuser = $userID;
}
$userLink = '';
if (isset($users) && $users != '0') {
    $userLink = '&uid=' . $users;
}
$profileDetails = $this->user_profile; //stores user details
$repinactivities = $this->repinactivities; //stores repin activities of user
$reportedUsers = $this->getReportUsers; //stores reported users list
$blockStatus = $this->blockStatus; //echo($blockStatus[0]->status);exit;
$user_result = $this->userprofile; //stores user details
if (empty($user_result[1])) {
    $user_res = $user_result[0]; //pinned by information
    $user_res1 = $user_result[0]; //commenting user information
} else if ($IDuser == $user_result[0]->user_id) {
    $user_res = $user_result[1]; //pinned by information
    $user_res1 = $user_result[0]; //commenting user information
} else if ($user_result[1]->user_id == $IDuser) {
    $user_res = $user_result[0]; //pinned by information
    $user_res1 = $user_result[1]; //commenting user information
}
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
 <script>
             var follow_all_lang="<?php echo JText::_('COM_SOCIALPINBOARD_FOLLOW_ALL'); ?>";
                var un_follow_board_lang="<?php echo JText::_('COM_SOCIALPINBOARD_UNFOLLOW_BOARD'); ?>";
                var un_follow_lang="<?php echo JText::_('COM_SOCIALPINBOARD_UNFOLLOW'); ?>";
                var follow_board_lang="<?php echo JText::_('COM_SOCIALPINBOARD_FOLLOW_BOARD'); ?>";
                var follow_user_lang="<?php echo JText::_('COM_SOCIALPINBOARD_FOLLOW_USER'); ?>";
                </script>
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
                        $value_receiver = $value_sender = '';
                        if (!empty($reportedUsers)) {
                            $value_receiver = $reportedUsers[0]->report_receiver;
                            $value_sender = $reportedUsers[0]->report_sender;
                        }

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


<?php if ($users != $user->id && $users != '' && $user->id != '') { ?>
                            <div id="category_user_follow<?php echo $user_result[0]->user_id ?>" style="padding-right: 5px;" >
<?php if (!empty($blockStatus[0]) && $blockStatus[0]->status == '0') { ?>
                                <input type="button" name="followuser" id="textdisplay" style="float:right;cursor: pointer;" value="<?php echo 'Block'; ?>" class="Button Button13 RedButton clickable blockuserbutton" onclick="bloguserfully();">
<?php } else if (!empty($blockStatus[0]) && $blockStatus[0]->status == '1') { ?>
                                <input type="button" style="float:right;cursor: pointer;" id="textdisplay"   value="<?php echo 'Unblock' ?>"class="Button Button13 RedButton clickable blockuserbutton" onclick="unblock('<?php echo $user_id; ?>','<?php echo $user_result[0]->user_id; ?>');"  />
                                <?php } else if (empty($blockStatus[0])) { ?>
                                <input type="button" name="followuser" id="textdisplay" style="float:right;cursor: pointer;" value="<?php echo 'Block'; ?>" class="Button Button13 RedButton clickable blockuserbutton" onclick="bloguserfully();">
                                <?php } ?>
                            </div>
                            <?php } ?>
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
                                $resultmodrepin = socialpinboardModelfollow::getModRepinpinUserdetails($test->pin_repin_id);
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
                            if ($totalBoard > 1)
                                echo $totalBoard . ' ' . JText::_('COM_SOCIALPINBOARD_BOARDS');else
                                echo $totalBoard . ' ' . JText::_('COM_SOCIALPINBOARD_BOARD');
                        ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=pindisplay' . $userLink) ?>">
                        <?php
                            if (($pincount) > 1)
                                echo ($pincount) . ' ' . JText::_('COM_SOCIALPINBOARD_PINS');else
                                echo ($pincount) . ' ' . JText::_('COM_SOCIALPINBOARD_PIN');
                        ?></a>
                    </li>
                    <li>
                        <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=likes' . $userLink) ?>">
                        <?php
                            if (count($pinLikes) > 1)
                                echo count($pinLikes) . ' ' . JText::_('COM_SOCIALPINBOARD_LIKES');else
                                echo count($pinLikes) . ' ' . JText::_('COM_SOCIALPINBOARD_LIKE');
                        ?></a>
                    </li>
                </ul>
            <?php if ($userID) { 
 ?>
                                <div  class="center_btn">
                <?php if (!empty($blockStatus[0]) && $blockStatus[0]->status == '1') { ?>
                                    <div id="follow" >
                    <?php if ($users != $user->id && $users != '' && $user->id != '') {
 ?>
                                        <input type="button" name="unblock" value="<?php echo 'Unblock'; ?>" id="textdisplayUnblock" class="unfollow_btn" onclick="unblock('<?php echo $user_id; ?>','<?php echo $users; ?>');" />
<?php } ?>
                                </div>
                <?php } else { ?>
                                    <div id="follow" >
                    <?php
                                    if ($users != $user->id && $users != '' && $user->id != '') {
                                        if (($follow_user_details == 'all') || ($totalBoard == $follwercount)) {
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
                    <?php } ?>
                                </div>
                <?php } ?>
                            </div>
            <?php } ?>
                            <div class="following-details">
                <?php
                            if (JRequest::getInt('uid')) {
                                $uuserId = JRequest::getInt('uid');
                ?>
                                <a <?php if ($follow == '1') echo 'class="selected"'; ?> href="<?php echo JRoute::_("index.php?option=com_socialpinboard&view=follow&follower=1&uid=" . $uuserId, false); ?>" ><?php echo count($followings) . ' ' . JText::_('COM_SOCIALPINBOARD_FOLLOWING_CAPS'); ?></a>     <a <?php if ($follow == '0') echo 'class="selected"'; ?> href="<?php echo JRoute::_("index.php?option=com_socialpinboard&view=follow&follower=0&uid=" . $uuserId, false); ?>" ><?php echo count($followers) . ' ' . JText::_('COM_SOCIALPINBOARD_FOLLOWERS_CAPS'); ?></a>
                <?php } else {
 ?>
                                <a <?php if ($follow == '1') echo 'class="selected"'; ?> href="<?php echo JRoute::_("index.php?option=com_socialpinboard&view=follow&follower=1", false); ?>" ><?php echo count($followings) . ' ' . JText::_('COM_SOCIALPINBOARD_FOLLOWING_CAPS'); ?></a>     <a <?php if ($follow == '0') echo 'class="selected"'; ?> href="<?php echo JRoute::_("index.php?option=com_socialpinboard&view=follow&follower=0", false); ?>" ><?php echo count($followers) . ' ' . JText::_('COM_SOCIALPINBOARD_FOLLOWERS_CAPS'); ?></a>
<?php } ?>
                        </div>
                    </div>
                    <div class="clear">
                    </div></div> </div>
<?php
                            if ($follow == '1') {
                                if (empty($followings)) {
                                    echo '<div id="login_error_msg" style="min-height:300px;">' . JText::_('COM_SOCIALPINBOARD_SORRY_NO_FOLLOWINGS') . '</div>';
                                } else {
?>

                                    <div  class=" board-followers ">
                                        <div class="follow-head">
<?php echo JText::_('COM_SOCIALPINBOARD_FOLLOWING_CAPS'); ?>
                                </div>
    <?php
                                    foreach ($followings as $following) {
    ?>
                                        <div class="board-followername">
                                            <div class="board-followername_left">
                                                <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=boarddisplay&uid=' . $following->user_id); ?>">

                <?php
                                        if ($following->user_image == '') {
                ?>
                                            <img alt="" src="<?php echo JURI::base() . '/components/com_socialpinboard/images/no_user.jpg' ?>" height="75" width="75" />
                <?php
                                        } else {
                ?>

                                            <img alt="" src="<?php echo JURI::base() . '/images/socialpinboard/avatars/' . $following->user_image; ?>" height="75" width="75" />
                <?php
                                        }
                ?>
                                    </a>
                                </div>
                                <div class="board-followername_right">
                                    <h4>
                                        <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=boarddisplay&uid=' . $following->user_id); ?>">
<?php echo ucfirst($following->first_name) . " " . ucfirst($following->last_name); ?>
                                    </a>
                                </h4>
                                <div class="count_items">
                <?php echo JText::_('COM_SOCIALPINBOARD_BOARDS'). ' : ' . $following->bid.' '; ?>
<?php echo JText::_('COM_SOCIALPINBOARD_PINS'). ' : '. $following->pid; ?>
                                    </div>
                                </div>
        <?php
                                        $resultuserfollow = socialpinboardModelfollow::followUser($user->id, $following->user_id);
                                        $Userboardscount = socialpinboardModelfollow::Userboardscount($user->id, $following->user_id);
                                        if ($resultuserfollow['0'] != '') {
                                            $follwercount = count($resultuserfollow);
                                        } else {
                                            $follwercount = '0';
                                        }
                                        if ($following->user_id != $user->id && $user->id != 0 && $following->user_id != '') {
                                            if (($resultuserfollow == 'all') || ($totalBoard == $follwercount)) {
        ?>
                                            <div id="category_user_follow<?php echo $following->user_id; ?>">
                                                <input type="button" name="unfollow" value="<?php echo JText::_('COM_SOCIALPINBOARD_UNFOLLOW'); ?>" id="unfollow" class="unfollow_btn" onclick="unfollowusers(<?php echo $user->id . ',' . $following->user_id ?>)" />
                                                </div>
         <?php
                                            } else {
        ?> <div id="category_user_follow<?php echo $following->user_id; ?>">
                                                    <input type="button" name="followall" value="<?php echo JText::_('COM_SOCIALPINBOARD_FOLLOW'); ?>" id="followall" onclick="followusers(<?php echo $user->id . ',' . $following->user_id ?>)" />
                                                </div> <?php
                                            }
                                        } else if($user->id != 0){
        ?>
                                            <div class="action-btn">
                                                <button class="unfollow_btn thats_btn" type="button"> <?php echo JText::_('COM_SOCIALPINBOARD_THATS_YOU'); ?></button>
                                            </div>
<?php } ?>



                                    </div>
    <?php
                                    }
                                }
    ?>
                            </div>

<?php
                            } else if ($follow == '0') {
                                if (!empty($followers)) {
?>
                                    <div class="board-followers">
                                        <div class="follow-head">
<?php echo JText::_('COM_SOCIALPINBOARD_FOLLOWERS_CAPS'); ?>
                                </div>
    <?php
                                    foreach ($followers as $follower) {
                                        if ($follower->user_image != '') {
                                            $image_url = JURI::base() . '/images/socialpinboard/avatars/' . $follower->user_image;
                                        } else {
                                            $image_url = JURI::base() . '/components/com_socialpinboard/images/no_user.jpg';
                                        }
    ?>

                                        <div class="board-followername">
                                            <div class="board-followername_left">
                                                <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=boarddisplay&uid=' . $follower->user_id); ?>">
                                                    <img src="<?php echo $image_url; ?>" height="75" width="75" />
                                                </a>
                                            </div>
                                            <div class="board-followername_right">
                                                <h4>
                                                    <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=boarddisplay&uid=' . $follower->user_id); ?>">
<?php echo ucfirst($follower->first_name) . " " . ucfirst($follower->last_name); ?>
                                    </a>
                                </h4>
                                <div class="count_items">
                <?php echo JText::_('COM_SOCIALPINBOARD_BOARDS') . ' : ' . $follower->bid.' '; ?>
<?php echo JText::_('COM_SOCIALPINBOARD_PINS') . ' : ' . $follower->pid; ?>
                                    </div>
                                </div>
        <?php
                                        $resultuserfollow = socialpinboardModelfollow::followUser($user->id, $follower->user_id);
                                        $Userboardscount = socialpinboardModelfollow::Userboardscount($user->id, $follower->user_id);

                                        if ($resultuserfollow['0'] != '') {
                                            $follwercount = count($resultuserfollow);
                                        } else {
                                            $follwercount = '0';
                                        }
                                        if ($follower->user_id != $user->id && $user->id != '' && $follower->user_id != '') {
                                            if (($resultuserfollow == 'all') || ($totalBoard == $Userboardscount[1])) {
        ?>
                                            <div id="category_user_follow<?php echo $follower->user_id; ?>">
                                                <input type="button" name="unfollow" value="<?php echo JText::_('COM_SOCIALPINBOARD_UNFOLLOW'); ?>" id="unfollow" class="unfollow_btn" onclick="unfollowusers(<?php echo $user->id . ',' . $follower->user_id ?>)" />
                                                </div>
        <?php
                                            } else {
        ?><div id="category_user_follow<?php echo $follower->user_id; ?>">
                                                    <input type="button" name="followall" value="<?php echo JText::_('COM_SOCIALPINBOARD_FOLLOW'); ?>" id="followall" onclick="followusers(<?php echo $user->id . ',' . $follower->user_id ?>)" />
                                                </div> <?php
                                            }
                                        } else if($user->id != 0){
        ?>
                                            <div class="action-btn">
                                                <button class="unfollow_btn thats_btn" type="button"> <?php echo JText::_('COM_SOCIALPINBOARD_THATS_YOU'); ?></button>
                                            </div>
<?php } ?>



                                    </div>






    <?php
                                    }
    ?>
                                </div>
<?php
                                } else {
?>
                                    <div style="min-height:300px;" id="login_error_msg"> <?php echo JText::_('COM_SOCIALPINBOARD_SORRY_NO_FOLLOWERS'); ?></div>
<?php
                                }
                            }
?>
                                    <input type="hidden" name="followerscount" id="followerscount" value="0"/>
                            <div id="userreport" class="ModalContainer">
                                <div class="modal wide PostSetup" id="reportuser">
                                    <div id="postsetup">
                                        <div id="outputs"></div>
                                        <h2>Report <?php echo $profileDetails->first_name . ' ' . $profileDetails->last_name; ?></h2>
                                        <a class="closebutton" onclick="javascript:document.getElementById('outputs').innerHTML = '';Modal.close('userreport');"></a>
                                        <p id="ReportLabel"><?php echo JText::_('COM_SOCIALPINBOARD_CONFIRM_REPORT_START') . $profileDetails->first_name . ' ' . $profileDetails->last_name; ?> ?</p>
                                        <p style="font-size: 18px;font-weight: bold;display:none;"><?php echo JText::_('COM_SOCIALPINBOARD_YOU_REASON'); ?><input type="text" value="" readonly id="inputBlocktype" style="width:50%;margin-left:30px;font-size: 18px;font-weight: bold;" name="blocktype"></p>
                                        <div id="reportbutton"><input type="button" style="float: left;" id="report_btn" onclick="reportUser(); return false"  data-form="ReportModal" value="<?php echo JText::_('COM_SOCIALPINBOARD_YOU_REPORT'); ?>" /></div>
                                        <input type="button"  style="float: left;margin-left: 5px;" id="report_sbumit_btn" onclick="reportUser();block('<?php echo $user_id; ?>','<?php echo $user_result[0]->user_id; ?>');"  data-form="ReportModal" value="<?php echo JText::_('COM_SOCIALPINBOARD_YOU_REPORT_BLOCK');
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
                         <input type="button" style="float: left;" id="report_sbumit_btn" onclick="block('<?php echo $user_result[0]->user_id; ?>','<?php echo $users; ?>');"  data-form="ReportModal" value="<?php echo JText::_('COM_SOCIALPINBOARD_YOU_BLOCK') . '  ' . $profileDetails->first_name . ' ' . $profileDetails->last_name; ?>"/>
                         <input type="button"  id="report_submitwhite_btn" onclick="Modal.close('blogdisplay');"  data-form="ReportModal" value="<?php echo JText::_('COM_SOCIALPINBOARD_YOU_DONT_BLOCK'); ?>" />
                     </form>
                 </div>
             </div>
             <div class="overlay" style="opacity: 1.0;"></div>
         </div>
         <script>
             var userid = '<?php echo $uuserID; ?>';
             if(userid !='0'){
                 jQuery(document).ready(function(){
                     jQuery('#CategoriesBarPage').css('height', '698px');
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
</script>
