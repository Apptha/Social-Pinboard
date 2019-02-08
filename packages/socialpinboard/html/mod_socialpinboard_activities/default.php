<?php
/**
 * @version	1.0: default.php$
 * @package	apptha
 * @copyright   Copyright (C) 2011 powered by Apptha
 * @license     GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
$app = JFactory::getApplication();
$templateName = $app->getTemplate();
$serachVal = JRequest::getVar('serachVal');
$serachVal = isset($serachVal) ? $serachVal : '';
$value = JRequest::getVar('value');
$session =  JFactory::getSession();
//get the current URl
$uri =  JFactory::getURI();
$CurrentURL = $uri->toString();

if ($serachVal == '') {
    $searchname = $value;
} else {
    $searchname = $serachVal;
}
$user =  JFactory::getUser();
$user_id = $user->id;
$userId = JRequest::getVar('uid');
if (!empty($userdetails)) {
    if ($user_id) {
        if ($userId == '')
            $userId = $user_id;
        if ($userdetails[0]->user_image != '') {
            $userImageDetails = pathinfo($userdetails[0]->user_image);
            $userProfileImage = $userImageDetails['filename'] . '_o.' . $userImageDetails['extension'];
        }
        ?>

        <script language='javascript'>  
            function loaddefimages(Id)  
            {  
                document.getElementById(Id).src="<?php echo JURI::base() . "components/com_socialpinboard/images/no_user.jpg"; ?>";  
                
            }   
          
        	    
        </script> 


        <div class="board_top_pin">


            <div class="ProfileSidebar profileslider_user">
                <div class="profile_content_img"
                <!-- ProfileImage-->
                <div class="ProfileImage">  
                    <?php
                    if ($userdetails[0]->user_image != '') {
                        ?>
                        <img src="<?php echo JURI::Base(); ?>/images/socialpinboard/avatars/<?php echo $userProfileImage ?>"  style="width: 15em;" />        
                        <?php
                    } else {
                        ?>


                        <img src="<?php echo JURI::Base(); ?>/components/com_socialpinboard/images/no_user.jpg"  style="width: 15em;" />        

            <?php
        }
        if ($user_id == $userId) {
            ?>

                        <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=profile'); ?>" class="recent_activities_edit">
                        <?php echo JTEXT::_('MOD_SOCIALPINBOARD__ACTIVITIES_EDIT_PROFILE'); ?>
                        </a>
                            <?php
                        }
                        ?>

                        <p  class="profrile_grid"><?php echo $userdetails[0]->about; ?></p>
                        <p class="profrile_grid"><?php echo $userdetails[0]->location; ?></p>
        <?php
        if (!preg_match("~^(?:f|ht)tps?://~i", $userdetails[0]->website)) {
            $url = "http://" . $userdetails[0]->website;
        } else {
            $url = $userdetails[0]->website;
        }
        ?>
                    <p style="text-align: left; text-decoration:underline;"><a href="<?php echo $url; ?>" target="_blank"><?php echo $userdetails[0]->website; ?></a></p>
                </div>
                 <div class="profile_content">
                    <h1><?php echo ucwords($userdetails[0]->username); ?></h1>
                </div>
                </div>
                <div class="profile_activity">
                <!-- ProfileLinks-->
                    <ul class="user-activity">

                        <?php
                        $followers = modSocialpinboardActivities::getFollowerDetails();
                        ?>

                        <?php
                        $i = 0;
                        if (!empty($followers)) {
                            $count = 0;
                            ?>
                            <div class="recent_activity_bg">
                                <h2 class="recent_activity_title">
                                    <?php echo JTEXT::_('MOD_SOCAILPINBOARD_FOLLOWERS'); ?>
                                </h2>
                                <?php
                                foreach ($followers as $follower) {
                                    ?>

                                    <?php
                                    $follow_Boards = $follower['board_name'];
                                    if ($follower['all'] == 'all') {
                                        $count++;
                                        $userLink = $follower['user_id'];
                                        ?>

                                        <li>
                                        <?php
                                        if ($follower['user_image'] == '') {
                                            ?>
                                                <img src="<?php echo JURI::base() . "/components/com_socialpinboard/images/no_user.jpg"; ?>"  id="followerallpins" width="30px" height="30px"/>
                                                <?php
                                            } else {
                                                ?>
                                                <img src="<?php echo JURI::base() . "/images/socialpinboard/avatars/" . $follower['user_image']; ?>"  id="followerallpins" width="30px" height="30px"/>
                                                <?php
                                            }
                                            ?>
                                            <div class="pro-side-comment">
                                                <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=boarddisplay&uid=' . $userLink) ?>" class="user_name"><?php echo $follower['username'] . ' '; ?> </a>  <?php echo JTEXT::_('MOD_SOCIALPINBOARD_IS_FOLLOWING_YOUR_PIN'); ?>
                                            </div>
                                        </li>

                        <?php
                        if ($count == 5) {
                            break;
                        }
                    } else if ($follow_Boards != '') {

                        foreach ($follow_Boards as $follow_Board) {
                            $userLink = $follower['user_id'];
                            $count++;
                            ?>
                                            <li>
                                            <?php
                                            if ($follower['user_image'] == '') {
                                                ?>
                                                    <img src="<?php echo JURI::base() . "/components/com_socialpinboard/images/no_user.jpg"; ?>"  id="followerallimages" width="30px" height="30px"/>
                                                <?php
                                            } else {
                                                ?>
                                                    <img src="<?php echo JURI::base() . "/images/socialpinboard/avatars/" . $follower['user_image']; ?>"   id="followerallimages" width="30px" height="30px"/>
                                                    <?php
                                                }
                                                ?>
                                                <div class="pro-side-comment">
                                                    <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=boarddisplay&uid=' . $userLink) ?>" class="user_name"><?php echo $follower['username']; ?> </a> <?php echo JTEXT::_('MOD_SOCIALPINBOARD_IS_FOLLOWING') . ' '; ?><?php echo $follow_Board; ?>.
                                                </div>
                                            </li>

                                                <?php
                                                if ($count == 5) {
                                                    break;
                                                }
                                            }
                                        }
                                        $i++;

                                        if ($count == 5) {
                                            break;
                                        }
                                        ?>
                                    <?php
                                }
                                ?>
                            </div>
                                <?php
                            }
                            ?>
                        <div class="recent_activity_bg">
                            <?php
                            if (!empty($activities)) {
                                ?>
                                <h2 class="recent_activity_title">
                                <?php echo JTEXT::_('MOD_SOCIALPINBOARD_YOUR_ACTIVITY'); ?>
                                </h2>
                            <?php
                            foreach ($activities as $statusUpdate) {

                                $result = modSocialpinboardActivities::getpinUserdetails($statusUpdate->pin_id);

                                if ($statusUpdate->link_type == 'youtube'|| $statusUpdate->link_type == 'vimeo') {
                                    $src_path = $statusUpdate->pin_image;
                                } else {
                                    $src_path = JURI::base() . "images/socialpinboard/pin_thumb/" . $statusUpdate->pin_image;
                                }
                                $userLink = $statusUpdate->pin_user_id;
                                ?>

                                    <li>
                                    <?php if ($statusUpdate->type == 'Comments') { ?>
                                            <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=pin&pinid=' . $statusUpdate->pin_id); ?>" class="imglink"><img src="<?php echo $src_path; ?>" width="75" height="75"/></a>
                                            <div class="pro-side-comment">
                                                <b>  <?php if($user_id!='' && $user_id!=0 && $userId!=$user_id)
                                                {
                                                    echo $userdetails[0]->username;

                                                }else
                                                {
                                                    echo JTEXT::_('MOD_SOCIALPINBOARD_ACTIVITIES_YOU');
                                                }
                                                ?></b> <?php echo JTEXT::_('MOD_SOCIALPINBOARD_ACTIVITIES_COMMENTED_ON') . ' '; ?><a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=pin&pinid=' . $statusUpdate->pin_id); ?>">
                                                <?php  if (str_word_count($statusUpdate->pin_description) < 25) {
                                                    echo $statusUpdate->pin_description;
                                                    }else
                                                    {
                                                        $pin_description = substr($statusUpdate->pin_description, 0, 25);
                                                        echo $pin_description . ' .... ';
                                                    }
                                                    ?>

                                                </a>
                                            </div>
                                    <?php } elseif ($statusUpdate->type == 'Repin') { ?>
                                            <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=pin&pinid=' . $statusUpdate->pin_id); ?>" class="imglink"><img src="<?php echo $src_path; ?>" width="75" height="75"/></a>
                                            <div class="pro-side-comment">
                                                <b> <?php if($user_id!='' && $user_id!=0 && $userId!=$user_id)
                                                {
                                                    echo $userdetails[0]->username;

                                                }else
                                                {
                                                    echo JTEXT::_('MOD_SOCIALPINBOARD_ACTIVITIES_YOU');
                                                }
                                                ?></b> <?php echo JTEXT::_('MOD_SOCIALPINBOARD_ACTIVITIES_REPIN') . ' '; ?> <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=pin&pinid=' . $statusUpdate->pin_id); ?>"><?php  if (str_word_count($statusUpdate->pin_description) < 25) {
                                                    echo $statusUpdate->pin_description;
                                                    }else
                                                    {
                                                        $pin_description = substr($statusUpdate->pin_description, 0, 25);
                                                        echo $pin_description . ' .... ';
                                                    }
                                                    ?></a>
                                            </div>
                    <?php } elseif ($statusUpdate->type == 'Likes') { ?>
                                            <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=pin&pinid=' . $statusUpdate->pin_id); ?>" class="imglink"><img src="<?php echo $src_path; ?>" width="75" height="75"/></a>
                                            <div class="pro-side-comment">
                                                <b> <?php if($user_id!='' && $user_id!=0 && $userId!=$user_id)
                                                {
                                                    echo $userdetails[0]->username;

                                                }else
                                                {
                                                    echo JTEXT::_('MOD_SOCIALPINBOARD_ACTIVITIES_YOU');
                                                }
                                                ?></b> </strong><?php echo JTEXT::_('MOD_SOCIALPINBOARD_ACTIVITIES_LIKE') . ' '; ?> <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=pin&pinid=' . $statusUpdate->pin_id); ?>">
                        <?php
                        if (str_word_count($statusUpdate->pin_description) < 100) {
                            echo $statusUpdate->pin_description;
                        } else {
                            $pin_description = substr($statusUpdate->pin_description, 0, 25);
                            echo $pin_description . ' .... ';
                                            }
                                            ?></a>
                                            </div>
                                                <?php } ?>
                                    </li>
                                            <?php }
                                        } ?>
                        </div>
                    </ul>
                </div>
            </div>
        </div>
<div style="clear: both;"></div>
                                    <?php
                                }
                            }

                            if (!empty($googlead)) {
                                ?>

    <div id="sidead" class="pin">

        <iframe id="google_ads_frame1" height="<?php echo $googlead[0]->adheight; ?>" width="<?php echo $googlead[0]->adwidth; ?>" frameborder="0"  scrolling="no" vspace="0"
                src="http://googleads.g.doubleclick.net/pagead/ads?client=ca-<?php echo $googlead[0]->adclient; ?>&output=html&h=60&slotname=<?php echo $googlead[0]->adslot; ?>&w=600&lmt=1333622642&flash=11.2.202&url=<?php echo $CurrentURL; ?>&dt=1333622642977&bpp=6&shv=r20120328&jsv=r20110914&correlator=1333622643047&frm=20&adk=2492369040&ga_vid=1889973982.1333622643&ga_sid=1333622643&ga_hid=582102500&ga_fc=0&u_tz=330&u_his=1&u_java=1&u_h=768&u_w=1366&u_ah=738&u_aw=1366&u_cd=24&u_nplug=8&u_nmime=59&dff=helvetica%20neue&dfs=13&adx=437&ady=51&biw=1349&bih=605&oid=3&fu=0&ifi=1&dtd=230&xpc=c7YrckmnJi&p=<?php echo JURI::base(); ?><" name="google_ads_frame1" marginwidth="0" marginheight="0" hspace="0" allowtransparency="true"><base target="_parent" /></iframe>

    </div>
    <?php
}
?>