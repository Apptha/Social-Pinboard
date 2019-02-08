<?php
/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component categories view
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');
$document = JFactory::getDocument();
//add the scripts and stylesheets
$document->addStyleSheet('components/com_socialpinboard/css/facebox.css');
$document->addStyleSheet('components/com_socialpinboard/css/style.css');
$document->addStyleSheet('components/com_socialpinboard/css/reset.css');
$document->addStyleSheet('components/com_socialpinboard/css/pinboard.css');

//get the builtin function values
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
$user = JFactory::getUser();
//declare variables
$userid = $user->id;
$categories = $this->categories;
?>
<script>var scr = jQuery.noConflict();</script>
<div id="CategoryIndex">
    <div class="header">
        <h2> <?php echo JText::_('COM_SOCIALPINBOARD_DISCOVER_IMAGES_TO_PIN'); ?></h2>
        <p><?php echo JText::_('COM_SOCIALPINBOARD_BROWSE_CATEGORIES'); ?></p>
    </div>
    <ul class="categories">
<?php
for ($i = 0; $i < count($categories); $i++) {
    if ($i % 2 == 0) {
        $class_type = "odd";
    } else {
        $class_type = "even";
    }
    $catid = $categories[$i]->category_id;
    $resultuserfollow = socialpinboardModelcategories::getPins($catid);
?>
        <li class="category <?php echo $class_type; ?>">
            <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&amp;view=' . $categories[$i]->category_name . '&cat_id=' . $categories[$i]->category_id, true); ?>">
                <div class="title">
                    <h3><?php echo $categories[$i]->category_name; ?></h3>
                    <span class="seeAll"><?php echo JText::_('COM_SOCIALPINBOARD_SEE_ALL'); ?></span>
                </div>
                <ul class="examples">
<?php
        if (!empty($resultuserfollow[$i])) {
            for ($j = 0; $j < count($resultuserfollow); $j++) {
                $src_path = JURI::base() . "images/socialpinboard/pin_medium/" . rawurlencode($resultuserfollow[$j]->pin_image);
?>
                    <li class="example"><img src="<?php echo $src_path; ?>" alt=""></li>
                        <?php }
                } ?>
                </ul>
            </a>
        </li>
<?php } ?>
    </ul>
</div>