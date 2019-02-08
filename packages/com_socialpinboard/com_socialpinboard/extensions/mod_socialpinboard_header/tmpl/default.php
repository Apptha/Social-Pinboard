<?php
/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
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
<form name="category_list" action="<?php echo JRoute::_('index.php?option=com_socialpinboard&amp;view=home', true); ?>" method="post">
    <ul class="catMenu HeaderContainer">
<?php
if ($userId) {
 ?>

        <li>


            <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&amp;view=home&amp;pinners=youfollow', true); ?>" class="<?php echo $class_you_follow; ?>"><?php echo JTEXT::_('MOD_PINNERS_YOU_FOLLOW'); ?></a></li>
<?php
    }
?>
        <li class="submenu">
<?php
    if ($view_name == 'categories') {
        $class_everything = 'nav about active';
    } else {
        $class_everything = 'nav about';
    }
?>

            <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&amp;view=categories', true); ?>" class="<?php echo $class_everything; ?>">
<?php echo JTEXT::_('MOD_SOCAILPINBOARD_HEADER_CATEGORIES'); ?></a>

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
        <li>
<?php
                    if (JRequest::getVar('category', NULL) == 'all') {
                        $class_name = "nav active";
                    }
?>
                    <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&amp;view=home&amp;category=all', true); ?>" class="<?php echo $class_name; ?> "><?php echo JTEXT::_('MOD_SOCAILPINBOARD_HEADER_EVERYTHING'); ?></a>
                </li>
                <li>
<?php
                    if ($view_name == 'video') {
                        $class_name = "nav active";
                    } else {
                        $class_name = "nav";
                    }
?>
                    <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&amp;view=video', true); ?>" class="<?php echo $class_name; ?> "><?php echo JTEXT::_('MOD_SOCAILPINBOARD_HEADER_VIDEOS'); ?></a>
                </li>
                <li>
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
            ?>
                </li>
                <li class="submenu">
            <?php
                    if ($view_name == 'gift') {
                        $class_name = "nav about active";
                    } else {
                        $class_name = "nav about";
                    }
            ?>
                    <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&amp;view=gift', true); ?>" class="<?php echo $class_name; ?>"><?php echo JTEXT::_('MOD_SOCAILPINBOARD_HEADER_GIFTS'); ?></a>
                    <ul id="CategoriesDropdown">

                        <li>
                            <span class="SubmenuColumn">
                                <a style="cursor: pointer" href="<?php echo JURI::base() . 'index.php?option=com_socialpinboard&amp;view=gift&amp;starts=1&amp;ends=20'; ?>"><?php echo $getcurrencysymbol . '1-20'; ?></a>
                                <a style="cursor: pointer" href="<?php echo JURI::base() . 'index.php?option=com_socialpinboard&amp;view=gift&amp;starts=20&amp;ends=50'; ?>"><?php echo $getcurrencysymbol . '20-50'; ?></a>
                                <a style="cursor: pointer" href="<?php echo JURI::base() . 'index.php?option=com_socialpinboard&amp;view=gift&amp;starts=50&amp;ends=100'; ?>"><?php echo $getcurrencysymbol . '50-100'; ?></a>
                                <a style="cursor: pointer" href="<?php echo JURI::base() . 'index.php?option=com_socialpinboard&amp;view=gift&amp;starts=100&amp;ends=200'; ?>"><?php echo $getcurrencysymbol . '100-200'; ?></a>
                                <a style="cursor: pointer" href="<?php echo JURI::base() . 'index.php?option=com_socialpinboard&amp;view=gift&amp;starts=200&amp;ends=500'; ?>"><?php echo $getcurrencysymbol . '200-500'; ?></a>
                                <a style="cursor: pointer" href="<?php echo JURI::base() . 'index.php?option=com_socialpinboard&amp;view=gift&amp;starts=500'; ?>"><?php echo $getcurrencysymbol . '500+'; ?></a>
                            </span>
                        </li>
                    </ul>
                </li>
            </ul>
        </form>
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