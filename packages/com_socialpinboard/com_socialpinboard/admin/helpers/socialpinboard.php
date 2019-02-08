<?php
/**
 * @name          : Apptha Eventz
 * @version       : 1.0
 * @package       : apptha
 * @since         : Joomla 1.6
 * @subpackage    : Apptha Eventz.
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2012 Powered by Apptha
 * @license       : GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @abstract      : Apptha Eventz.
 * @Creation Date : November 3 2012
 **/
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

class SocialpinboardHelper {

    /**
         * Get the actions
         */
        public static function getActions($messageId = 0)
        {
                jimport('joomla.access.access');
                $user   = JFactory::getUser();
                $result = new JObject;

                if (empty($messageId)) {
                        $assetName = 'com_socialpinboard';
                }
                else {
                        $assetName = 'com_socialpinboard.message.'.(int) $messageId;
                }

                $actions = JAccess::getActions('com_socialpinboard', 'component');

                foreach ($actions as $action) {
                        $result->set($action->name, $user->authorise($action->name, $assetName));
                }
                return $result;
        }
        
   
}