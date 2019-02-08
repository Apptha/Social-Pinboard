<?php

/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component people model
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class socialpinboardModelPeople extends SocialpinboardModel {

    function getFacebookDetails() {
        $mainframe = JFactory::getApplication();

        $user = JFactory::getUser();
        $userId = $user->id;
        if ($userId) {
            $db = JFactory::getDBO();
            $query = "SELECT count 
                      FROM #__pin_user_settings 
                      WHERE user_id=$userId";
            $db->setQuery($query);
            $userid = $db->loadResult();
            return $userid;
        }
    }

    function registeration() {
        $db = JFactory::getDbo();
        $query = "SELECT setting_user_registration
                FROM  `#__pin_site_settings` ";
        $db->setQuery($query);
        $register = $db->loadResult();
        return $register;
    }

    function showRequest() {

        $db = JFactory::getDBO();
        $query = "SELECT setting_show_request 
                  FROM #__pin_site_settings";
        $db->setQuery($query);
        $show_request = $db->loadResult();
        return $show_request;
    }

    //function to update profile
    function createLogin() {

        $email = JRequest::getVar('email');
        $activation_code = JRequest::getVar('activaton');


        $db = JFactory::getDBO();
        $query = "SELECT `activationcode` 
                  FROM `#__pin_user_activation` 
                  WHERE `email`='" . $email . "'";
        $db->setQuery($query);
        $show_activation = $db->loadResult();

        if ($show_activation == $activation_code) {

            $query = "UPDATE #__pin_user_settings
                      SET status='1'  
                      WHERE email='" . $email . "'";
            $db->setQuery($query);
            $db->query();

            $query = "UPDATE #__users
                      SET block='0'  
                      WHERE email='" . $email . "'";
            $db->setQuery($query);
            $db->query();
            $query = "UPDATE `#__pin_user_activation` 
                      SET  activationcode='1'  
                      WHERE email='" . $email . "'";
            $db->setQuery($query);
            $db->query();
        }
    }

}