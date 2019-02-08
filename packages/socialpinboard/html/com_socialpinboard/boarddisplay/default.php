<?php
/**
 * @name          : Joomla Social Pinboard
 * @version	  : 1.5.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component Boarddisplay view
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_socialpinboard/css/edit-style.css');
$document->addScript('components/com_socialpinboard/javascript/chrome.js');
$document->addStyleSheet('templates/socialpinboard/html/com_socialpinboard/css/style.css');
$document->addStyleSheet('templates/socialpinboard/html/com_socialpinboard/css/pinboard.css');
$document->addStyleSheet('components/com_socialpinboard/css/reset.css');
//$document->addStyleSheet('components/com_socialpinboard/css/pinboard.css');
$document->addStyleSheet('templates/socialpinboard/css/style.css');
$document->addScript('https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js');
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
$userExist = $this->user_exist;
if ($userExist->block == '1' && $userExist->status == '0') {
    echo "<div id='login_error_msg'> Sorry! This pinner is not available! </div>";
} else {
    $baseurl = JURI::base();
    $user = JFactory::getUser();
    $fBoardId = '';
    $inc = '';
    $totalBoard = $this->totalBoard;
   
    $dispBoard = $this->displayBoard;
    $pinLikes = $this->getLikes;
    $pincounts = $this->pinCounts;
    $followers = $this->followers;
//    echo "<pre>";print_r($followers);exit;
    if ($followers['0'] != '') {
        $follwercount = count($followers);
    } else {
        $follwercount = '0';
    }
     
    $followings = $this->FollowingInformation;
    $follower_count = $this->followerInformation;
    if ($follower_count == '') {
        $follower_count = 0;
    }
    if ($followings == '') {
        $followings = 0;
    }
    $users = JRequest::getInt('uid');
    $userLink = '';
//if user exists assign user id
    if (isset($users) && $users != '0') {
        $userLink = '&amp;uid=' . $users;
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
$user_id = $user->id; //stores user id
    $profileDetails = $this->user_profile; //stores user details
    $repinactivities = $this->repinactivities; //stores repin activities of user
    if($user_id)
    $userfollow = $this->follow_user_details; //stores the following user list
    $userfollowuser = $this->userfollowuser;
    $user_result = $this->userprofile; //stores user details
    $userfollowuserCount = count($userfollowuser);
    $reportedUsers = $this->getReportUsers; //print_r($reportedUsers);
    $blockStatus = $this->blockStatus; //echo($blockStatus[0]->status);exit;
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
    if (!JRequest::getVar('page')) {
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
                    <h1 id="name"><?php echo $profileDetails->first_name . ' ' . $profileDetails->last_name; ?></h1>
                    <p class="colormuted"><?php echo $profileDetails->about; ?></p>
                    <ul id="profilelinks" class="icons">
<?php if ($profileDetails->facebook_profile_id) { ?>
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
                        <?php if ($users != $user->id && $users != '' && $user->id != '') { ?>
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
                                <h2 style="color:black;"><?php echo JText::_('COM_SOCIALPINBOARD_YOU_REPORT'); ?> <?php echo ' ' . $profileDetails->first_name . ' ' . $profileDetails->last_name; ?></h2>
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

<?php if ($user_id != $users && $user_id != '' && $user_id != '0') { ?>
                                    <div id="category_user_follow<?php echo $user_id; ?>" style="padding-right: 5px;" >
<?php if (!empty($blockStatus[0]) && $blockStatus[0]->status == '0') { ?>
                                            <input type="button" name="followuser" id="textdisplay" style="float:right;cursor: pointer;" value="<?php echo 'Block'; ?>" class="Button Button13 RedButton clickable blockuserbutton" onclick="bloguserfully();">

<?php } else if (!empty($blockStatus[0]) && $blockStatus[0]->status == '1') { ?>
                                            <input type="button" style="float:right;cursor: pointer;" id="textdisplay"   value="<?php echo 'Unblock' ?>"class="Button Button13 RedButton clickable blockuserbutton" onclick="unblock('<?php echo $user_id; ?>','<?php echo $user_res->user_id; ?>');"  />

<?php } else if (empty($blockStatus[0])) { ?>
                                        <input type="button" name="followuser" id="textdisplay" style="float:right;cursor: pointer;" value="<?php echo 'Block'; ?>" class="Button Button13 RedButton clickable blockuserbutton" onclick="bloguserfully();">

<?php } ?>
                                    <input type="button" name="followuser" id="nameuser" style="display:none;" value="<?php echo $profileDetails->first_name . ' ' . $profileDetails->last_name; ?>" class="Button Button13 RedButton clickable blockuserbutton" >
                                </div>
<?php } if($user_id == $users) { ?>
    <input type="button" name="followuser" id="textdisplay" style="float:right;cursor: pointer;" value="" class="Button Button13 RedButton clickable blockuserbutton" >
<input type="button" name="followuser" id="nameuser" style="display:none;" value="" class="Button Button13 RedButton clickable blockuserbutton" >

<?php }?>

                            </h3>
                            <h3 class="report-user" id="blockUnblockUsermessagefirst"></h3>
                            <h3 class="report-user" id="blockUnblockUsermessagesecond"></h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="repins">
                <h3><?php echo JText::_('COM_SOCIALPINBOARD_YOU_REPINS_FROM'); ?></h3>
                <ul>
<?php
                            foreach ($repinactivities as $test) {
                                $resultmodrepin = socialpinboardModelboarddisplay::getModRepinpinUserdetails($test->pin_repin_id);
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
                            <li><a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=boarddisplay' . $userLink) ?>" class="selected">
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
                            if ($pincounts > 1)
                                echo $pincounts . ' ' . JText::_('COM_SOCIALPINBOARD_PINS');else
                                echo $pincounts . ' ' . JText::_('COM_SOCIALPINBOARD_PIN');
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
                            <?php if ($user_id) {
 ?>
                    <div  class="center_btn">
                            <?php if (!empty($blockStatus[0]) && $blockStatus[0]->status == '1') {
 ?>
                            <div id="follow" >
<?php if ($users != $user->id && $users != '' && $user->id != '') { ?>
                                    <input type="button" name="unblock" value="<?php echo 'Unblock'; ?>" id="textdisplayUnblock" class="unfollow_btn" onclick="unblock('<?php echo $user_id; ?>','<?php echo $user_res->user_id; ?>');" />
                <?php } ?>
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
                        <?php } ?>
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
                <?php } else { ?>
                                    <a href="<?php echo JRoute::_("index.php?option=com_socialpinboard&view=follow&follower=1", false); ?>" ><?php echo $followings . ' ' . JText::_('COM_SOCIALPINBOARD_FOLLOWING_CAPS'); ?></a>     <a href="<?php echo JRoute::_("index.php?option=com_socialpinboard&view=follow&follower=0", false); ?>" ><?php echo $follower_count . ' ' . JText::_('COM_SOCIALPINBOARD_FOLLOWERS_CAPS'); ?></a>
                    <?php } ?>
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
                    <?php
                        }
                    ?>

        <div id="container">

            <div class="board_display">
<?php
if($user_id && (!JRequest::getVar('page')) ){
?>

                    <div class="pin board_pin_create">
                    <div class="board_pin create">
                        <a class="new_create_board" onclick="AddDialog.close('Add', 'CreateBoard'); CreateBoardDialog.reset(); return false" >
                            <?php echo JText::_('COM_SOCAILPINBOARD_MENU_CREATE_A_BOARD'); ?>
                        </a>
                        <span class="thumbs">
                            <span class="empty"></span>
                            <span class="empty"></span>
                            <span class="empty"></span>
                            <span class="empty"></span>
                         </span>
                    </div>
                    </div>


                <?php
}  ?>
<?php
                        if (count($dispBoard) != 0) {
                            foreach ($dispBoard as $board) {
?>
                                        <div class="pin" id="pin_div_<?php echo $board->board_id; ?>">


                                <div class="board_pin_title">
                                    
                                    <h3 class="board-title">
                                        <a class="new-img" href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=boardpage&bId=' . $board->board_id) ?>">
                                            <?php
                                if (strlen($board->board_name) > 25) {
                    echo JHTML::_('string.truncate', ($board->board_name), 25);
                } else {
                    echo $board->board_name;
                }
?>
                                        </a>
                                    </h3>
                                    <?php
                                    $model = $this->getModel('boarddisplay');
                                    $getBoardPinscount = $model->getBoardPinsCount($board->board_id);
                                    ?>
                                    <h4 class="pin-cnt" >
                                    <?php echo count($getBoardPinscount);
                                    if(count($getBoardPinscount)>1)
                                    echo " Pins";
                                    else
                                    echo " Pin";
                                    ?>
                                    </h4>
                                </div>

                                            <div class="board_pin">

<?php
                                if ($users == '') {
                                    $users = $user->id;
                                }   ?>
                                <div style="height: 15px;"><?php
                                if (!empty($board->cuser_id)) {
?>
                                        <div class="edit-board"></div>
                <?php } ?>
                                            </div>
                                
                                <div class="images pin_no_img b-edit1">
                    <?php
                                $model = $this->getModel('boarddisplay');
                                $getBoardPins = $model->getBoardPins($board->board_id); //print_r($getBoardPins);
                                $i = 0;
                                foreach ($getBoardPins as $pin) {
                                    $imgSrc = '';
                                    if ($pin->link_type == 'youtube' || $pin->link_type == 'vimeo') {
                                        $imgSrc = $pin->pin_image;
                                        $imgSrc1 = $pin->pin_image;
                                    } else {
                                        $imgSrc = "images/socialpinboard/pin_thumb/" . rawurlencode($pin->pin_image);
//                                        $imgSrc = JURI::base() . 'images/socialpinboard/pin_original/' . $pin->pin_image;
                                        $imgSrc1 = JURI::base() . 'images/socialpinboard/pin_original/' . $pin->pin_image;
                                    }
                    ?><?php
                                    $i++;
                                    if ($i == 1) {
                    ?>
                                        <a class="new-img" href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=boardpage&bId=' . $board->board_id) ?>">
                                            <img src="<?php echo $imgSrc1; ?>" alt="" width="190" height=""/>
                                        </a><?php } else {
 ?>
                                        <a class="new-img" href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=boardpage&bId=' . $board->board_id) ?>">
                                            <img src="<?php echo $imgSrc; ?>" alt="" width="60" height="45"/>
                                        </a><?php } ?>
<?php } ?>
                            </div>
<?php
$split = explode(",", $board->cuser_id);
if (($board->cuser_id) && ((in_array($users, $split)) || (in_array($user->id, $split)) )) {
    if(!empty($user->id)){
                    ?>
                                <div class="followBoard">
                                    <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=boardedit&bidd=' . $board->board_id) ?>" class="Button13 Button WhiteButton"><strong><?php echo JText::_('COM_SOCIALPINBOARD_EDIT'); ?></strong></a>
                                </div>
                <?php
    }
                                }  else { ?>
                                <div id="followboard<?php echo $board->board_id; ?>" class="follow_board">
<?php
                                    if ($users != $user->id && $users != '' && $user->id != '') {
                                        $count = count($followers);
                                        $i = 0;
?>
                    <?php if (!empty($blockStatus[0]) && $blockStatus[0]->status == '1') {
 ?>
                                            <div id="newfollowboard<?php echo $board->board_id; ?>">
                                                <input type="button" name="followboard" value="<?php echo JText::_('COM_SOCIALPINBOARD_FOLLOW_BOARD'); ?>" class="followboard followboard-height" id="followboard<?php echo $board->board_id; ?>" onclick="followboardblock();"/>
                                            </div>
                    <?php } else {
 ?>
                                            <div id="newfollowboard<?php echo $board->board_id; ?>">
<?php
                                            if ($followers == 'all' || in_array($board->board_id, $followers)) {
?>
                                                    <input type="button" name="unfollowboard" value="<?php echo JText::_('COM_SOCIALPINBOARD_UNFOLLOW_BOARD'); ?>" class="unfollowboard" id="unfollowboard<?php echo $board->board_id; ?>" onclick="unfollows(<?php echo $user->id . ',' . $users . ',' . $board->board_id; ?>)" />
                        <?php
                                            } else {
                        ?>
                                                <input type="button" name="followboard" value="<?php echo JText::_('COM_SOCIALPINBOARD_FOLLOW_BOARD'); ?>" class="followboard followboard-height" id="followboard<?php echo $board->board_id; ?>" onclick="followboard(<?php echo $user->id . ',' . $users . ',' . $board->board_id; ?>)"/>
                        <?php } ?>
                                        </div><?php } ?>
                        <?php
                                        $i++;
                                    } ?> </div>
<?php } ?>
                    <?php
                                if (($users == $user->id) && ($board->user_id == $users)) {
                    ?>
                                <div class="followBoard">
                                    <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=boardedit&bidd=' . $board->board_id) ?>" class="Button13 Button WhiteButton"><strong><?php echo JText::_('COM_SOCIALPINBOARD_EDIT'); ?></strong></a>
                                    </div>
                <?php
                                } else if ($users == '') {
                ?>
<?php } ?>

                            </div>
                            </div>

                <?php }

                        } ?>

                </div>
                    </div>
            <div id="userreport" class="ModalContainer">
                <div class="modal wide PostSetup" id="reportuser">
                    <div id="postsetup">
                        <div id="outputs"></div>
                        <h2><?php echo JText::_('COM_SOCIALPINBOARD_YOU_REPORT'); ?> <?php echo $profileDetails->first_name . ' ' . $profileDetails->last_name; ?></h2>
                            <a class="closebutton" onclick="javascript:document.getElementById('outputs').innerHTML = '';Modal.close('userreport');"></a>
                            <p id="ReportLabel"><?php echo JText::_('COM_SOCIALPINBOARD_CONFIRM_REPORT_START') . $profileDetails->first_name . ' ' . $profileDetails->last_name; ?> ?</p>
                            <p style="font-size: 18px;font-weight: bold;display:none;"><?php echo JText::_('COM_SOCIALPINBOARD_YOU_REASON'); ?><input type="text" value="" readonly id="inputBlocktype" style="width:50%;margin-left:30px;font-size: 18px;font-weight: bold;" name="blocktype"></p>
                            <div id="reportbutton"><input type="button" style="float: left;" id="report_btn" onclick="reportUser(); return false"  data-form="ReportModal" value="<?php echo JText::_('COM_SOCIALPINBOARD_YOU_REPORT'); ?>" /></div>
                            <input type="button"  style="float: left;margin-left: 5px;" id="report_sbumit_btn" onclick="reportUser();block('<?php echo $user_id; ?>','<?php echo $user_res->user_id; ?>');"  data-form="ReportModal" value="<?php echo JText::_('COM_SOCIALPINBOARD_YOU_REPORT_BLOCK');
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
                            <h2><?php echo JText::_('COM_SOCIALPINBOARD_YOU_BLOCK'); ?> <?php echo $profileDetails->first_name . ' ' . $profileDetails->last_name; ?></h2>
                            <a class="closebutton"  onclick="javascript:document.getElementById('outputs').innerHTML = '';Modal.close('blogdisplay');"></a>
                            <form name="report1" id="report1" action="#" method="post" class="Form FancyForm">
                                <div id="outputs"></div>
                                <p id="ReportLabelblock"><?php echo JText::_('COM_SOCIALPINBOARD_YOU_REPORTED_MESSAGE_START') . $profileDetails->first_name . ' ' . $profileDetails->last_name . JText::_('COM_SOCIALPINBOARD_YOU_REPORTED_MESSAGE_END'); ?> </p>
                                <p style="font-size: 18px;font-weight: bold;display:none;">Reason :<input type="text" value="" readonly id="inputBlocktype" style="width:50%;margin-left:30px;font-size: 18px;font-weight: bold;" name="blocktype"></p>
                                <input type="button" style="float: left;" id="report_sbumit_btn" onclick="block('<?php echo $user_id; ?>','<?php echo $user_res->user_id; ?>');"  data-form="ReportModal" value="<?php echo JText::_('COM_SOCIALPINBOARD_YOU_BLOCK') . '  ' . $profileDetails->first_name . ' ' . $profileDetails->last_name; ?>"/>
                                <input type="button"  id="report_submitwhite_btn" onclick="Modal.close('blogdisplay');"  data-form="ReportModal" value="<?php echo JText::_('COM_SOCIALPINBOARD_YOU_DONT_BLOCK'); ?>" />
                            </form>
                        </div>
                    </div>
                    <div class="overlay" style="opacity: 1.0;"></div>
                </div>
                <div id="followBlockreport" class="ModalContainer">
                    <div class="modal wide PostSetup" id="followblockreportmodal" >
                        <div id="postsetup">
                            <a class="closebutton" onclick="javascript:document.getElementById('outputs').innerHTML = '';Modal.close('followBlockreport');"></a>
                            <h2><p id="ReportLabel"> <?php echo JText::_('COM_SOCIALPINBOARD_REPORT_BLOCK_BOARD'). ' ' . $profileDetails->first_name . ' ' . $profileDetails->last_name; ?>.</p></h2>
                            <input type="button" value="Close" style="float: left;" id="report_sbumit_btn" onclick="Modal.close('userReport');location.reload();" />
                        </div>
                    </div>
                    <div class="overlay" style="opacity: 0.95;"></div>
                </div>
            <nav id="page-nav">
                <a id="navpage" href="<?php echo $this->baseurl ?>/index.php/component/socialpinboard/?view=boarddisplay<?php echo $userLink; ?>&amp;page=1"></a>
            </nav>
            <script>
                var follow_all_lang='<?php echo JText::_('COM_SOCIALPINBOARD_FOLLOW_ALL'); ?>';
                var un_follow_board_lang='<?php echo JText::_('COM_SOCIALPINBOARD_UNFOLLOW_BOARD'); ?>';
                var un_follow_lang='<?php echo JText::_('COM_SOCIALPINBOARD_UNFOLLOW'); ?>';
                var follow_board_lang='<?php echo JText::_('COM_SOCIALPINBOARD_FOLLOW_BOARD'); ?>';
                var userid = '<?php echo $user_id;?>';
                if(userid !='0'){
                jQuery(document).ready(function(){
                    var unblock = document.getElementById('textdisplay').value;
                    var name = document.getElementById('nameuser').value;
                    if(unblock == 'Unblock'){
                        document.getElementById('userblock').innerHTML = "<?php echo JText::_('COM_SOCIALPINBOARD_YOU_UNBLOCK'); ?> "+name;
                        document.getElementById('blockUnblockUsermessagefirst').innerHTML = "<?php echo JText::_('COM_SOCIALPINBOARD_IF_YOU_UNBLOCK'); ?> "+name;
                        document.getElementById('blockUnblockUsermessagesecond').innerHTML ="<?php echo JText::_('COM_SOCIALPINBOARD_FOLLOW_EACH_OTHER'); ?>";
                        return;
                    }
                    else if(unblock == 'Block'){
                        document.getElementById('userblock').innerHTML = "<?php echo JText::_('COM_SOCIALPINBOARD_YOU_BLOCK'); ?> "+name;
                        document.getElementById('blockUnblockUsermessagefirst').innerHTML = "<?php echo JText::_('COM_SOCIALPINBOARD_IF_YOU_BLOCK'); ?> "+name;
                        document.getElementById('blockUnblockUsermessagesecond').innerHTML = "<?php echo JText::_('COM_SOCIALPINBOARD_CANT_FOLLOW_EACH_OTHER'); ?>";
                        return;
                    }
               });
                }
                function textdisplay()
                {
                    var unblock = document.getElementById('textdisplay').value;
                    var name = document.getElementById('nameuser').value;
                    if(unblock == 'Unblock'){
                        document.getElementById('userblock').innerHTML = "<?php echo JText::_('COM_SOCIALPINBOARD_YOU_UNBLOCK'); ?> "+name;
                        document.getElementById('blockUnblockUsermessagefirst').innerHTML = "<?php echo JText::_('COM_SOCIALPINBOARD_IF_YOU_UNBLOCK'); ?> "+name;
                        document.getElementById('blockUnblockUsermessagesecond').innerHTML ="<?php echo JText::_('COM_SOCIALPINBOARD_FOLLOW_EACH_OTHER'); ?>";
                        return;
                    }
                    else if(unblock == 'Block'){
                        document.getElementById('userblock').innerHTML = "<?php echo JText::_('COM_SOCIALPINBOARD_YOU_BLOCK'); ?> "+name;
                        document.getElementById('blockUnblockUsermessagefirst').innerHTML = "<?php echo JText::_('COM_SOCIALPINBOARD_IF_YOU_BLOCK'); ?> "+name;
                        document.getElementById('blockUnblockUsermessagesecond').innerHTML = "<?php echo JText::_('COM_SOCIALPINBOARD_CANT_FOLLOW_EACH_OTHER'); ?>";
                        return;
                    }
                }
                function bloguser(){
                    document.getElementById('blogdialogbox').style.display = "block";
                }
                function userReport(){
                    document.getElementById('userReport').style.display = "block";
                }
                function followboardblock(){
                    document.getElementById('followBlockreport').style.display = "block";
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
                var scr = jQuery.noConflict();
                scr(document).ready(function($) {



                    var $container = scr('.board_display');
                    $container.imagesLoaded(function(){
                        $container.masonry({
                            itemSelector : '.pin',

                            isFitWidth: true,
                            isResizable: true,
                            columnWidth: 222,
                            gutterWidth: 15
                        });

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
                            msgText:"<em><?php echo JText::_('COM_SOCAILPINBOARD_LOADING_NEXT_BOARDS'); ?></em>",
                            finishedMsg: "<?php echo JText::_('COM_SOCAILPINBOARD_NO_MORE_BOARDS'); ?>",
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
                        });
                    }
                );

                });


            </script>
<?php
                    }
?>

