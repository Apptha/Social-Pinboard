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
jimport('joomla.filesystem.file');
   

      
class socialpinboardControllerpincategory extends SocialpinboardController
{
    function display($cachable = false, $urlparams = false) //Function to display the Category list
       {

        $viewName   = JRequest::getVar( 'view', 'pincategory' );
        $viewLayout = JRequest::getVar( 'layout', 'pincategory' );
        $view =  $this->getView($viewName);
        if ($model = $this->getModel('pincategory'))
        {
            $view->setModel($model, true);
        }
        $view->setLayout($viewLayout);
        $view->display();
    }

    function edit($cachable = false, $urlparams = false) // Function to edit a particular category
    {
        $view =  $this->getView('pincategory');
        if ($model =  $this->getModel('pincategory'))
        {
            $view->setModel($model, true);
        }
        $view->setLayout('pincategoryform');
        $view->display();
    }

    function save($cachable = false, $urlparams = false) // Function to save a new category
    {
        $detail = JRequest::get( 'POST' );
        $model =  $this->getModel('pincategory');
        $model->savepincategory($detail);
        $this->setRedirect('index.php?layout=pincategory&option='.JRequest::getVar('option'), 'Category Saved!');
    }

    function add($cachable = false, $urlparams = false) // Function to add a new category
    {
        $view =  $this->getView('pincategory');
        if ($model =  $this->getModel('pincategory'))
        {
            $view->setModel($model, true);
        }
        $view->setLayout('pincategoryform');
        $view->display();
    }

     function remove($cachable = false, $urlparams = false) // Function to delete a category
    {
        $arrayIDs = JRequest::getVar('cid', null, 'default', 'array' ); //Reads cid as an array
        if($arrayIDs === null)
        { //Make sure the cid parameter was in the request
            JError::raiseError(500, 'cid parameter missing from the request');
        }
        $model =  $this->getModel('pincategory');
        $model->deletepincategory($arrayIDs);
        $this->setRedirect('index.php?layout=pincategory&option='.JRequest::getVar('option'), 'Trashed');
    }
    function apply($cachable = false, $urlparams = false) // Function to store and stay on the same page till we click on save button[Apply]
    {
        $detail = JRequest::get( 'POST' );
		
		$model =  $this->getModel('pincategory');
		
        $categoryId = $model->savepincategory($detail);
        $link='index.php?option=com_socialpinboard&layout=pincategory&task=edit&cid[]='.$categoryId;
        $this->setRedirect($link, 'Category Saved!');
    }
    function cancel($cachable = false, $urlparams = false) // Function to cancel some operation
    {
        $this->setRedirect('index.php?layout=pincategory&option='.JRequest::getVar('option'), 'Cancelled...');
    }
    function publish($cachable = false, $urlparams = false) // Function to publish a category
    {
        $detail = JRequest::get('POST');
        $model =  $this->getModel('pincategory');
        $model->pubpincategory($detail);
        $this->setRedirect('index.php?option='.JRequest::getVar('option').'&layout=pincategory');
    }
    function unpublish($cachable = false, $urlparams = false) // Function to unpublish a category
    {
        $detail = JRequest::get('POST');
        $model =  $this->getModel('pincategory');
        $model->pubpincategory($detail);
        $this->setRedirect('index.php?option='.JRequest::getVar('option').'&layout=pincategory');
    }
}
?>
