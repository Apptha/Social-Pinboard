<?php
/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component emailshare view
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');

ob_clean();
$successMeassage = $this->successMessage;
?>
<p style="text-align: center;font-size: 20px;line-height: 43px;font-weight: 300;color: #0C9102;"><?php echo $successMeassage; ?></p>


<?php exit(); ?>