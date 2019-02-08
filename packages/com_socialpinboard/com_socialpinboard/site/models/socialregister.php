<?php

/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component socialregister model
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');

// import Joomla modelform library
jimport('joomla.application.component.modeladmin');
jimport('joomla.user.helper');

class socialpinboardModelSocialregister extends SocialpinboardModel {

    function getSocialUSerDetails() {
        $session = JFactory::getSession();
        $userid = $session->get('facebooklogin');

        $db = JFactory::getDBO();
        $query = "select name,email from #__users where id='$userid'";
        $db->setQuery($query);
        $row = $db->loadObject();

        return $row;
    }

}