<?php
/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Module search
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');
$app = JFactory::getApplication();
$templateName = $app->getTemplate();
$serachVal = JRequest::getVar('serachVal');
$serachVal = isset($serachVal) ? $serachVal : '';
$value = JRequest::getVar('value');
$session = JFactory::getSession();
if ($serachVal == '') {
    $searchname = $value;
} else {
    $searchname = $serachVal;
}
?>
<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'/>
<form method="post" name="taskform" action="<?php echo JURI::base() . 'index.php?option=com_socialpinboard&amp;view=search'; ?>">
    <input type="text" name="serachVal" id="serachVal" value="<?php if ($searchname == '') {
    echo 'Search';
} else {
    echo $searchname;
} ?>"  onFocus="if(this.value == 'Search') {this.value = '';}" onBlur="if (this.value == '') {this.value = 'Search';}" />
    <input type="submit" name="search" id="search" value="" />
</form>


