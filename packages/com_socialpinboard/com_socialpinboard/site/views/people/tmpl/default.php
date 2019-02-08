<?php
/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component people view
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');

$cmd = JRequest::getCmd('task', null);
$user = JFactory::getUser();
$document = JFactory::getDocument();

$document->addStyleSheet('components/com_socialpinboard/css/reset.css');
$document->addStyleSheet('components/com_socialpinboard/css/pinboard.css');
?>
<form method="post"
      action="<?php echo JRoute::_('index.php?option=com_socialpinboard', false); ?>" enctype="multipart/form-data">
    <div class="profile clearfix">
<?php
if (strpos($cmd, '.') != false) {
    // We have a defined controller/task pair -- lets split them out
    list($controllerName, $task) = explode('.', $cmd);
    echo $this->loadTemplate($task);
} else {
    echo $this->loadTemplate('dashboard');
}
?>
    </div>
        <?php echo JHtml::_('form.token'); ?>
</form>