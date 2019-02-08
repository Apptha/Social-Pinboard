<?php
/**
 * @name        Social Pin Board
 * @version	1.0: managepins.php$
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
class socialpinboardControllermanagepins extends SocialpinboardController
{
    function display($cachable = false, $urlparams = false) //Function to display the Category list
       {

        $viewName   = JRequest::getVar( 'view', 'managepins' );
        $viewLayout = JRequest::getVar( 'layout', 'managepins' );
        $view =  $this->getView($viewName);
        if ($model =  $this->getModel('managepins'))
        {
            $view->setModel($model, true);
        }
        $view->setLayout($viewLayout);
        $view->display();
    }
  
    function remove($cachable = false, $urlparams = false) // Function to delete a category
    {
        $arrayIDs = JRequest::getVar('cid', null, 'default', 'array' ); //Reads cid as an array
        if($arrayIDs === null)
        { //Make sure the cid parameter was in the request
            JError::raiseError(500, 'cid parameter missing from the request');
        }
        $model =  $this->getModel('managepins');
        $model->deletemanagepins($arrayIDs);
        $this->setRedirect('index.php?layout=managepins&option='.JRequest::getVar('option'), 'Trashed');
    }
    function publish($cachable = false, $urlparams = false) // Function to publish a category
    {
        $detail = JRequest::get('POST');//print_r($detail);exit;
        $model =  $this->getModel('managepins');
        $model->pubmanagepins($detail);
        $this->setRedirect('index.php?option='.JRequest::getVar('option').'&layout=managepins');
    }
    function unpublish($cachable = false, $urlparams = false) // Function to unpublish a category
    {
        $detail = JRequest::get('POST');
        $model =  $this->getModel('managepins');
        $model->pubmanagepins($detail);
        $this->setRedirect('index.php?option='.JRequest::getVar('option').'&layout=managepins');
    }
}
?>
