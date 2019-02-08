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

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');
class socialpinboardControllersitesettings extends SocialpinboardController
{
    function display($cachable = false, $urlparams = false) //Function to display the Category list
       {

        $viewName   = JRequest::getVar( 'view', 'sitesettings' );
        $viewLayout = JRequest::getVar( 'layout', 'sitesettings' );
        $view =  $this->getView($viewName);
        if ($model =  $this->getModel('sitesettings'))
        {
            $view->setModel($model, true);
        }
        $view->setLayout($viewLayout);
        $view->display();
    }

    function edit() // Function to edit a particular category
    {
        $view =  $this->getView('sitesettings');
        if ($model =  $this->getModel('sitesettings'))
        {
            $view->setModel($model, true);
        }
        $view->setLayout('sitesettingsform');
        $view->display();
    }

    function save() // Function to save a new category
    {
        $detail = JRequest::get( 'POST' );
        $model =  $this->getModel('sitesettings');
        $model->savesitesettings($detail);
        $this->setRedirect('index.php?layout=sitesettings&option='.JRequest::getVar('option'), 'Site Settings Saved!');
    }

    function add() // Function to add a new category
    {
        $view =  $this->getView('sitesettings');
        if ($model =  $this->getModel('sitesettings'))
        {
            $view->setModel($model, true);
        }
        $view->setLayout('sitesettingsform');
        $view->display();
    }

     function remove() // Function to delete a category
    {
        $arrayIDs = JRequest::getVar('cid', null, 'default', 'array' ); //Reads cid as an array
        if($arrayIDs === null)
        { //Make sure the cid parameter was in the request
            JError::raiseError(500, 'cid parameter missing from the request');
        }
        $model =  $this->getModel('sitesettings');
        $model->deletesitesettings($arrayIDs);
        $this->setRedirect('index.php?layout=sitesettings&option='.JRequest::getVar('option'), 'Deleted...');
    }
    function apply() // Function to store and stay on the same page till we click on save button[Apply]
    {
       $detail = JRequest::get( 'POST' );
       $model =  $this->getModel('sitesettings');
       $model->savesitesettings($detail);
       $this->setRedirect('index.php?layout=sitesettings&option='.JRequest::getVar('option'), 'Site Settings Saved!');
    }
    function cancel() // Function to cancel some operation
    {
        $this->setRedirect('index.php?layout=sitesettings&option='.JRequest::getVar('option'), 'Cancelled...');
    }
    function publish() // Function to publish a category
    {
        $detail = JRequest::get('POST');
        $model = $this->getModel('sitesettings');
        $model->pubsitesettings($detail);
        $this->setRedirect('index.php?option='.JRequest::getVar('option').'&layout=sitesettings');
    }
    function unpublish() // Function to unpublish a category
    {
        $detail = JRequest::get('POST');
        $model =  $this->getModel('sitesettings');
        $model->pubsitesettings($detail);
        $this->setRedirect('index.php?option='.JRequest::getVar('option').'&layout=sitesettings');
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
    function get_domain($domain)
    {
     
        $code = $this->domainKey($domain);
        $domainKey = substr($code, 0, 25) . "CONTUS";
        return $domainKey;
      
    }
     function social_genenrate()
    {
		$strDomainName = JURI::base();
		preg_match("/^(http:\/\/)?([^\/]+)/i", $strDomainName, $subfolder);
		preg_match("/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i", $subfolder[2], $matches);
			
		$customerurl = $matches['domain'];
		$customerurl = str_replace("www.", "", $customerurl);
		$customerurl = str_replace(".", "D", $customerurl);
		$customerurl = strtoupper($customerurl);
		$response     = $this->get_domain($customerurl);
		return $response;
    }
    
    
}
?>
