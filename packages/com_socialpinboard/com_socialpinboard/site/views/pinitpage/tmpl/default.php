<?php
/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component pinitpage view
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');
$pinid = JRequest::getVar('pin_id');
$pin_desc = JRequest::getVar('pin_desc');
$boardnames = $this->boardnames;
$arrayVal       = parse_url(JURI::base());
$base_url= $arrayVal['host'];
?>
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/components/com_socialpinboard/css/style.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/components/com_socialpinboard/css/reset.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>components/com_socialpinboard/css/pinboard.css" type="text/css" />
<style type="text/css">
    .contentpane{overflow: hidden;}
    body{background: #F7F5F5;}
</style>  
<div class="success-pin-it">
    <h2 style="text-align: center;"><?php echo JText::_('COM_SOCIALPINBOARD_SUCCESS'); ?> </h2>
    <p><?php echo JText::_('COM_SOCIALPINBOARD_PIN_PINNED_TO'); ?> <span><?php echo $boardnames[0]->board_name; ?></span></p>
    <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=pin&pinid=' . $pinid); ?>" target="_blank" onclick="test();"><?php echo JText::_('COM_SOCIALPINBOARD_SEE_YOUR_PIN'); ?> </a>
    <a href="http://twitter.com/home/?status=<?php echo $base_url . JRoute::_('index.php?option=com_socialpinboard&view=pin&pinid=' . $pinid); ?>%2F <?php echo $pin_desc ?>" target="_blank" onclick="test();"><?php echo JText::_('COM_SOCIALPINBOARD_TWEET_YOUR_PIN'); ?> </a>
    <a href="http://www.facebook.com/sharer.php?u=<?php echo $base_url . JRoute::_('index.php?option=com_socialpinboard&view=pin&pinid=' . $pinid); ?>%2F&t=<?php echo $pin_desc ?>" target="_blank" onclick="test();"><?php echo JText::_('COM_SOCIALPINBOARD_SHARE_ON_FACEBOOK'); ?> </a>
</div>


<script type="text/javascript">
    function test(){
        setTimeout('self.close();',3000);
    }
</script>