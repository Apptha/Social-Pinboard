<?php

/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component file
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');

if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

 JLoader::register('SocialpinboardController', JPATH_COMPONENT_ADMINISTRATOR.'/helpers/controller.php');
JLoader::register('SocialpinboardView', JPATH_COMPONENT_ADMINISTRATOR.'/helpers/view.php');
JLoader::register('SocialpinboardModel', JPATH_COMPONENT_ADMINISTRATOR.'/helpers/model.php');

date_default_timezone_set('UTC');
// Require the helpers
require_once( JPATH_COMPONENT . DS . 'helpers' . DS . 'socialpinboard.php' );
// Require the base controller

jimport('joomla.application.component.controller');
// Require specific controller if requested
if ($controller = JRequest::getWord('controller')) {
    $path = JPATH_COMPONENT . DS . 'controllers' . DS . $controller . '.php';
    if (file_exists($path)) {
        require_once $path;
    } else {
        $controller = '';
    }
}

class SocialpinboardClass {

    protected $_const;

    function get_domain($domain) {

        $code = $this->domainKey($domain);
        $domainKey = substr($code, 0, 25) . "CONTUS";
//        echo $domainKey;exit;
        return $domainKey;
    }

    function social_genenrate() {
        $strDomainName = JURI::base();
//        $strDomainName = "http://iseofirm.net";
        preg_match("/^(http:\/\/)?([^\/]+)/i", $strDomainName, $subfolder);
        preg_match("/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i", $subfolder[2], $matches);

        $customerurl = $matches['domain'];
        $customerurl = str_replace("www.", "", $customerurl);
        $customerurl = str_replace(".", "D", $customerurl);
        $customerurl = strtoupper($customerurl);
        $response = $this->get_domain($customerurl);
        return $response;
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

    function apiKey() {
        if (extension_loaded('mcrypt')) {
            $this->_const = "wk2A7KrQDNlNbYC5dIUPcIcQn43I7oMGPbAOo6tY0ixC801XYqeUP0+ODLabO2X47f4L0vO0xTt1zebQd/jNV2UzZrz7DGsJ3ykUcEtun7I=";
            $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
            $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
            $const = base64_decode($this->_const);
        }
    }
}

$comClass = new SocialpinboardClass();
$comClass->apiKey();

$controllerName = JRequest::getVar('view');
$controller_path = JPATH_COMPONENT . DS . 'controllers' . DS . $controllerName . '.php';

if (file_exists($controller_path)) {
    require_once $controller_path;
} else {
    $controllerName = 'home';
    $controller_path = JPATH_COMPONENT . DS . 'controllers' . DS . $controllerName . '.php';
    require_once $controller_path;
}

$controller_path = JPATH_COMPONENT . DS . 'controllers' . DS . $controllerName . '.php';
// Create the controller
$classname = 'socialpinboardController' . $controllerName;
$controller = new $classname( );
// Perform the Request task
$controller->execute(JRequest::getWord('task'));

// Redirect if set by the controller
$controller->redirect();