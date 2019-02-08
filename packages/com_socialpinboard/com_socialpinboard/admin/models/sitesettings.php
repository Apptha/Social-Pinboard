<?php

/**
 * @name        Social Pin Board
 * @version	1.0: sitesettings.php$
 * @since       Joomla 1.5&1.6&1.7
 * @package	apptha
 * @subpackage	com_socialpinboard
 * @author      Contus Support
 * @copyright   Copyright (C) 2011 powered by Apptha
 * @license     GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
//No direct acesss
defined('_JEXEC') or die();
jimport('joomla.application.component.model');

class socialpinboardModelsitesettings extends SocialpinboardModel {

    //To get the settings from the database and display it
    function getsitesettings() {
        global $option, $mainframe;
        $db = $this->getDBO(); //to enable db connection
        $query = "SELECT * FROM #__pin_site_settings";
        $db->setQuery($query);
        $siteSettings = $db->loadObject();
        return $siteSettings; //return the final result
    }

    //To Add the New settings into the tables
    function getNewsitesettings() {
        $detailsTableRow =  $this->getTable('sitesettings');
        $date =  JFactory::getDate();
        $dateTime = $date->toFormat() . "\n";
        $detailsTableRow->id = 0;
        $detailsTableRow->setting_facebookapi = '';
        $detailsTableRow->setting_facebooksecret = '';
        $detailsTableRow->setting_gmailclientid = '';
        $detailsTableRow->setting_gmailclientsecretkey = '';
        $detailsTableRow->setting_twitterapi = '';
        $detailsTableRow->setting_twittersecret = '';
        $detailsTableRow->setting_yahooconsumerkey = '';
        $detailsTableRow->setting_yahooconsumersecretkey = '';
        $detailsTableRow->setting_yahoooauthdomain = '';
        $detailsTableRow->setting_yahoooappid = '';
        $detailsTableRow->lkey = '';
        $detailsTableRow->created_date = $dateTime;
        $detailsTableRow->setting_user_registration='';
        return $detailsTableRow;
    }

    //To Save the site Settings
    function savesitesettings($detail) {

        // this function is to save a settings
        $detailTableRow = $this->getTable('sitesettings');
        
        if (!$detailTableRow->bind($detail)) {
            JError::raiseError(500, 'Error binding data');
        }
        if (!$detailTableRow->check()) {
            JError::raiseError(500, 'Invalid data');
        }
        if (!$detailTableRow->store()) {
            $errorMessage = $detailTableRow->getError();
            JError::raiseError(500, 'Error binding data: ' . $errorMessage);
        }
    }

    //License key functions


    function domainKey($tkey) {


        $message = "EJ-SPBMP0EFIL9XEV8YZAL7KCIUQ6NI5OREH4TSEB3TSRIF2SI1ROTAIDALG-JW";

        for ($i = 0; $i < strlen($tkey); $i++) {
            $key_array[] = $tkey[$i];
        }

        $enc_message = "";
        $kPos = 0;
        $chars_str = "WJ-GLADIATOR1IS2FIRST3BEST4HERO5IN6QUICK7LAZY8VEX9LIFEMP0";
        for ($i = 0; $i < strlen($chars_str); $i++) {
            $chars_array[] = $chars_str[$i];
        }
        for ($i = 0; $i < strlen($message); $i++) {
            $char = substr($message, $i, 1);

            $offset = $this->getOffset($key_array[$kPos], $char);
            $enc_message .= $chars_array[$offset];
            $kPos++;
            if ($kPos >= count($key_array)) {
                $kPos = 0;
            }
        }

        return $enc_message;
    }

    function getOffset($start, $end) {

        $chars_str = "WJ-GLADIATOR1IS2FIRST3BEST4HERO5IN6QUICK7LAZY8VEX9LIFEMP0";
        for ($i = 0; $i < strlen($chars_str); $i++) {
            $chars_array[] = $chars_str[$i];
        }

        for ($i = count($chars_array) - 1; $i >= 0; $i--) {
            $lookupObj[ord($chars_array[$i])] = $i;
        }

        $sNum = $lookupObj[ord($start)];
        $eNum = $lookupObj[ord($end)];

        $offset = $eNum - $sNum;

        if ($offset < 0) {
            $offset = count($chars_array) + ($offset);
        }

        return $offset;
    }

    function get_domain($domain) {

        $code = $this->domainKey($domain);
        $domainKey = substr($code, 0, 25) . "CONTUS";
        return $domainKey;
    }

    function social_genenrate() {
        $strDomainName = JURI::base();
        preg_match("/^(http:\/\/)?([^\/]+)/i", $strDomainName, $subfolder);
        preg_match("/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i", $subfolder[2], $matches);

        $customerurl = $matches['domain'];
        $customerurl = str_replace("www.", "", $customerurl);
        $customerurl = str_replace(".", "D", $customerurl);
        $customerurl = strtoupper($customerurl);
        $response = $this->get_domain($customerurl);
        return $response;
    }

    function lKey() {
        $db = Jfactory::getDBO(); //to enable db connection
        $userApikey='';
        $query="SELECT lkey FROM #__pin_site_settings";
        $db->setQuery($query);
        $userApikey = $db->loadResult();
        
            $appthaApiKey = $this->social_genenrate();
            if ($userApikey == $appthaApiKey & $userApikey!='') {
                $Social_Pinboard=1;
                return $Social_Pinboard;
            } else {
                $Social_Pinboard=0;
                return $Social_Pinboard;
                
            }
        }
    

}

?>
