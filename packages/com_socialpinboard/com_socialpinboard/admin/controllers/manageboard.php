<?php
/**
 * @name        Social Pin Board
 * @version	1.0: manageboard.php$
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
class socialpinboardControllermanageboard extends SocialpinboardController
{
    function display($cachable = false, $urlparams = false) //Function to display the Category list
       {

        $viewName   = JRequest::getVar( 'view', 'manageboard' );
        $viewLayout = JRequest::getVar( 'layout', 'manageboard' );
        $view =  $this->getView($viewName);
        if ($model =  $this->getModel('manageboard'))
        {
            $view->setModel($model, true);
        }
        $view->setLayout($viewLayout);
        $view->display();
    }
    function cancel($cachable = false, $urlparams = false) // Function to cancel some operation
    {
        $this->setRedirect('index.php?layout=manageboard&option='.JRequest::getVar('option'), 'Cancelled...');
    }
    function remove($cachable = false, $urlparams = false) // Function to delete a category
    {
        $arrayIDs = JRequest::getVar('cid', null, 'default', 'array' ); //Reads cid as an array
        if($arrayIDs === null)
        { //Make sure the cid parameter was in the request
            JError::raiseError(500, 'cid parameter missing from the request');
        }
        $model =  $this->getModel('manageboard');
        $model->deletemanageboard($arrayIDs);
        $this->setRedirect('index.php?layout=manageboard&option='.JRequest::getVar('option'), 'Trashed');
    }
    function publish($cachable = false, $urlparams = false) // Function to publish a category
    {
        $detail = JRequest::get('POST');//print_r($detail);exit;
        $model =  $this->getModel('manageboard');
        $model->pubmanageboard($detail);
        $this->setRedirect('index.php?option='.JRequest::getVar('option').'&layout=manageboard');
    }
    function unpublish($cachable = false, $urlparams = false) // Function to unpublish a category
    {
        $detail = JRequest::get('POST');
        $model =  $this->getModel('manageboard');
        $model->pubmanageboard($detail);
        $this->setRedirect('index.php?option='.JRequest::getVar('option').'&layout=manageboard');
    }
}
?>
