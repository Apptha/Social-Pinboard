<?php
/**
 * @name        Social Pin Board
 * @version	1.0: pincategory.php$
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
class socialpinboardControllergooglead extends SocialpinboardController
{
    function display($cachable = false, $urlparams = false) //Function to display the Category list
       {

        $viewName   = JRequest::getVar( 'view', 'googlead' );
        $viewLayout = JRequest::getVar( 'layout', 'googlead' );
        $view =  $this->getView($viewName);
        if ($model =  $this->getModel('googlead'))
        {
            $view->setModel($model, true);
        }
        $view->setLayout($viewLayout);
        $view->display();
    }

    function edit($cachable = false, $urlparams = false) // Function to edit a particular category
    {
        $view =  $this->getView('googlead');
        if ($model =  $this->getModel('googlead'))
        {
            $view->setModel($model, true);
        }
        $view->setLayout('googleadform');
        $view->display();
    }

    function save($cachable = false, $urlparams = false) // Function to save a new category
    {
        
        $detail = $_POST;
       
        $model =  $this->getModel('googlead');
        $model->savepinad($detail);
        $this->setRedirect('index.php?layout=googlead&option='.JRequest::getVar('option'), 'Google AdSense Saved!');
    }

    function add($cachable = false, $urlparams = false) // Function to add a new category
    {
        $view =  $this->getView('googlead');
        if ($model =  $this->getModel('googlead'))
        {
            $view->setModel($model, true);
        }
        $view->setLayout('googleadform');
        $view->display();
    }

     function remove($cachable = false, $urlparams = false) // Function to delete a category
    {
        $arrayIDs = JRequest::getVar('cid', null, 'default', 'array' ); //Reads cid as an array
        if($arrayIDs === null)
        { //Make sure the cid parameter was in the request
            JError::raiseError(500, 'cid parameter missing from the request');
        }
        $model =  $this->getModel('googlead');
        $model->deletepinad($arrayIDs);
        $this->setRedirect('index.php?layout=googlead&option='.JRequest::getVar('option'), 'Google AdSense Trashed...');
    }
    function apply($cachable = false, $urlparams = false) // Function to store and stay on the same page till we click on save button[Apply]
    {
        //$data['pin_ad']=JRequest::getVar( 'pin_ad', null, 'default', 'none', JREQUEST_ALLOWHTML );
        $detail = $_POST;
        $model =  $this->getModel('googlead');
        $categoryId = $model->savepinad($detail);
        $link='index.php?option=com_socialpinboard&layout=googlead&task=edit&cid[]='.$categoryId;
        $this->setRedirect($link, 'Google AdSense Saved!');
    }
    function cancel($cachable = false, $urlparams = false) // Function to cancel some operation
    {
        $this->setRedirect('index.php?layout=googlead&option='.JRequest::getVar('option'), 'Cancelled...');
    }
    function publish($cachable = false, $urlparams = false) // Function to publish a category
    {
        $detail = JRequest::get('POST');
        $model =  $this->getModel('googlead');
        $model->pubpinad($detail);
        $this->setRedirect('index.php?option='.JRequest::getVar('option').'&layout=googlead');
    }
    function unpublish($cachable = false, $urlparams = false) // Function to unpublish a category
    {
        $detail = JRequest::get('POST');
        $model =  $this->getModel('googlead');
        $model->pubpinad($detail);
        $this->setRedirect('index.php?option='.JRequest::getVar('option').'&layout=googlead');
    }
}
?>
