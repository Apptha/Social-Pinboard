<!--[if gte IE 9]>
  <style type="text/css">
    .gradient {
       filter: none;
    }
  </style>
<![endif]-->

<?php
/**
 * @name          : Joomla Social Pinboard
 * @version	  : 1.5.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Module header
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access

defined('_JEXEC') or die('Restricted access');
$class_name = '';
$total = ceil(count($result) / 2);
$user = JFactory::getUser();
$show_request = $getcurrencysymbol->setting_show_request;
$show_register_btn = $getcurrencysymbol->setting_user_registration;
$id = $user->id;
$userId = $user->id;
$view_name = JRequest::getVar('view', NULL);
if (JRequest::getVar('pinners', NULL) == 'youfollow') {
    $class_you_follow = 'nav active';
} else {
    $class_you_follow = "nav";
}
if (!empty($googlead)) {
?>
    <div id="headerad" >
        <iframe id="google_ads_frame1" height="<?php echo $googlead[0]->adheight ?>" class="iframeborder"  width="<?php echo $googlead[0]->adwidth; ?>" scrolling="no"
                src="http://googleads.g.doubleclick.net/pagead/ads?client=ca-<?php echo $googlead[0]->adclient; ?>&amp;output=html&amp;h=60&amp;slotname=<?php echo $googlead[0]->adslot; ?>&amp;w=600&amp;lmt=1333622642&amp;flash=11.2.202&amp;url=<?php echo $CurrentURL; ?>&amp;dt=1333622642977&amp;bpp=6&amp;shv=r20120328&amp;jsv=r20110914&amp;correlator=1333622643047&amp;frm=20&amp;adk=2492369040&amp;ga_vid=1889973982.1333622643&amp;ga_sid=1333622643&amp;ga_hid=582102500&amp;ga_fc=0&amp;u_tz=330&amp;u_his=1&amp;u_java=1&amp;u_h=768&amp;u_w=1366&amp;u_ah=738&amp;u_aw=1366&amp;u_cd=24&amp;u_nplug=8&amp;u_nmime=59&amp;dff=helvetica%20neue&amp;dfs=13&amp;adx=437&amp;ady=51&amp;biw=1349&amp;bih=605&amp;oid=3&amp;fu=0&amp;ifi=1&amp;dtd=230&amp;xpc=c7YrckmnJi&amp;p=<?php echo JURI::base(); ?><" name="google_ads_frame1" allowtransparency="true" ><base target="_parent" />
        </iframe></div>
<?php
}
?>
 <script>
    var scroll_to_top="<?php echo JTEXT::_('COM_SOCAILPINBOARD_HEADER_SCROLL_TO_TOP'); ?>";
    </script>
    <div id="CategoriesBar" class="category_mobile">
    <form name="category_list" action="<?php echo JRoute::_('index.php?option=com_socialpinboard&amp;view=home', true); ?>" method="post">
        <ul class="catMenu HeaderContainer">
<?php
if ($userId) {
 ?>
            <li class="top_nav follower">
                <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&amp;view=home&amp;pinners=youfollow', true); ?>" class="<?php echo $class_you_follow; ?>"><?php echo JTEXT::_('MOD_PINNERS_YOU_FOLLOW'); ?></a>
                    <span class="cat_dot">&nbsp;.</span>
                </li>
<?php
        }
?>
            <li class="submenu top_nav">
<?php
    if ($view_name == 'categories') {
            $class_everything = 'nav about active';
        } else {
            $class_everything = 'nav about';
        }
?>

                <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&amp;view=categories', true); ?>" class="<?php echo $class_everything; ?>">
<?php echo JTEXT::_('MOD_SOCAILPINBOARD_HEADER_CATEGORIES'); ?>
                </a>
                    <span class="cat_dot">&nbsp;.</span>
                <ul id="CategoriesDropdown">

                    <li>
<?php
                $i = 0;
                foreach ($result as $arrcategory) {

                    if ($i == 0)
                        echo '<span class="SubmenuColumn">';
                    if ($i == $total)
                        echo '</span><span class="SubmenuColumn">';
?>

                        <a style="cursor: pointer" onclick="return getCategories(' <?php echo $arrcategory->category_id; ?>','<?php echo JURI::base() . 'index.php?option=com_socialpinboard&amp;view=home&amp;category=' . urlencode($arrcategory->category_name); ?>')"><?php echo $arrcategory->category_name; ?></a>
<?php
                        if ($i == count($result) - 1)
                            echo '</span>';

                        $i++;
                    }
?>
                        <input type="hidden" value="<?php echo $arrcategory->category_id ?>" id="cat_id" name="cat_id"/>
                    </li>
                </ul>
            </li>
             <li  class="top_nav">
<?php
                    if (JRequest::getVar('category', NULL) == 'all') {
                        $class_name = "nav active";
                    }
?>
                    <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&amp;view=home&amp;category=all', true); ?>" class="<?php echo $class_name; ?> "><?php echo JTEXT::_('MOD_SOCAILPINBOARD_HEADER_EVERYTHING'); ?></a><span class="cat_dot">&nbsp;.</span>
                </li>
            <li  class="top_nav">
<?php
                        if ($view_name == 'video') {
                            $class_name = "nav active";
                        } else {
                        $class_name = "nav";
                    }
                ?>
                            <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&amp;view=video', true); ?>" class="<?php echo $class_name; ?> "><?php echo JTEXT::_('MOD_SOCAILPINBOARD_HEADER_VIDEOS'); ?></a><span class="cat_dot">&nbsp;.</span>
                    </li>
                    <li class="top_nav">
                <?php
                        if ($view_name == 'popular') {
                ?>
                            <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&amp;view=popular', true); ?>" class="nav active"><?php echo JTEXT::_('MOD_SOCAILPINBOARD_HEADER_POPULAR'); ?></a>
                <?php
                        } else {
                ?>
                            <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&amp;view=popular', true); ?>" class="nav"><?php echo JTEXT::_('MOD_SOCAILPINBOARD_HEADER_POPULAR'); ?></a>
                <?php
                        }
                ?><span class="cat_dot">&nbsp;.</span>
                    </li>
                    <li class="top_nav gifts">
                <?php
                        if ($view_name == 'gift') {
                            $class_name = "nav active";
                        } else {
                            $class_name = "nav ";
                        }
                ?>
                        <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&amp;view=gift', true); ?>" class="<?php echo $class_name; ?>"><?php echo JTEXT::_('MOD_SOCAILPINBOARD_HEADER_GIFTS'); ?></a>
                        <ul id="CategoriesDropdowngift">
                            <li>
                                <span class="SubmenuColumn">
                                    <a style="cursor: pointer" href="<?php echo JURI::base() . 'index.php?option=com_socialpinboard&amp;view=gift&amp;starts=1&amp;ends=20'; ?>"><?php echo $getcurrencysymbol->setting_currency . '1-20'; ?></a>
                                    <a style="cursor: pointer" href="<?php echo JURI::base() . 'index.php?option=com_socialpinboard&amp;view=gift&amp;starts=20&amp;ends=50'; ?>"><?php echo $getcurrencysymbol->setting_currency . '20-50'; ?></a>
                                    <a style="cursor: pointer" href="<?php echo JURI::base() . 'index.php?option=com_socialpinboard&amp;view=gift&amp;starts=50&amp;ends=100'; ?>"><?php echo $getcurrencysymbol->setting_currency . '50-100'; ?></a>
                                    <a style="cursor: pointer" href="<?php echo JURI::base() . 'index.php?option=com_socialpinboard&amp;view=gift&amp;starts=100&amp;ends=200'; ?>"><?php echo $getcurrencysymbol->setting_currency . '100-200'; ?></a>
                                    <a style="cursor: pointer" href="<?php echo JURI::base() . 'index.php?option=com_socialpinboard&amp;view=gift&amp;starts=200&amp;ends=500'; ?>"><?php echo $getcurrencysymbol->setting_currency . '200-500'; ?></a>
                                    <a style="cursor: pointer" href="<?php echo JURI::base() . 'index.php?option=com_socialpinboard&amp;view=gift&amp;starts=500'; ?>"><?php echo $getcurrencysymbol->setting_currency . '500+'; ?></a>
                                </span>
                            </li>
                        </ul>
                    </li>
                </ul>
            </form>
        <div class="banner_box">
            <div class="Nag">
                <div class="Sheet1 Sheet">
                    <p><strong><?php echo JText::_('MOD_SOCIALPINBOARD_ONLINE_PINBOARD'); ?></strong><br>
                        <?php echo JText::_('MOD_SOCIALPINBOARD_ORGANIZE_AND_SHARE'); ?></p>

                    <div class="login_register_button">
<?php
                        if (!$user->id && $show_request == 0 && $show_register_btn ==1) {
?>
                            <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=register'); ?>" class="nav register_btn">
<?php echo JText::_('MOD_SOCIALPINBOARD_REGISTER'); ?>
                            </a>

                <?php
                        } else {
                            if (!$user->id && $show_request == 1) {
                ?>

                            <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=inviterequest'); ?>" class="nav register_btn">
                <?php echo JText::_('MOD_SOCAILPINBOARD_INVITE'); ?>
                                </a>
                <?php
                            }
                        }
                ?>
<?php
                        if ($id == 0) {
                            $uri = JFactory::getURI();
                            $return = $uri->toString();
                            $url = "index.php?option=com_socialpinboard&view=people";
                            $url .= '&returnURL=' . base64_encode($return);
                            $linkurl = JRoute::_($url);
?>
                            <a href="<?php echo $linkurl; ?>" class="nav login_btn">Login</a>
                <?php } ?>

                    </div>
                </div>
                <div class="Sheet2 Sheet"></div>
                <div class="Sheet3 Sheet"></div>
            </div>
        </div>
        </div>
        


<?php
                        $user = JFactory::getUser();
                        if ($user->id != 0) {
?>
            <a id="home_invite_frnds" href="<?php echo JRoute::_('index.php?option=com_socialpinboard&amp;view=invitefriends', true); ?>"><?php echo JTEXT::_('MOD_SOCAILPINBOARD_HEADER_INVITE_FRIENDS'); ?></a>
<?php } ?>
<script type="text/javascript">
    function getCategories(cat_id,cat_name)
    {
        document.getElementById('cat_id').value=cat_id;
        document.category_list.submit();
        return true;
    }
    
</script>