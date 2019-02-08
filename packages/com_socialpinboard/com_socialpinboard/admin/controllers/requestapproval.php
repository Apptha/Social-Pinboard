<?php
/**
 * @name        Social Pin Board
 * @version	1.0: memberdetails.php$
 * @since       Joomla 1.5&1.6&1.7
 * @package	apptha
 * @subpackage	com_socialpinboard
 * @author      Jeyakiruthika
 * @copyright   Copyright (C) 2011 powered by Apptha
 * @license     GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');
class socialpinboardControllerrequestapproval extends SocialpinboardController
{
   function display($cachable = false, $urlparams = false) {
        $viewName = JRequest::getVar('view', 'requestapproval');
        $viewLayout = JRequest::getVar('layout', 'requestapproval');
        $view = $this->getView($viewName);
        if ($model =  $this->getModel('requestapproval')) {
            $view->setModel($model, true);
        }
        $view->setLayout($viewLayout);
        $view->display();
    }

     function remove($cachable = false, $urlparams = false) // Function to delete a request
    {
        
        $arrayIDs = JRequest::getVar('cid', null, 'default', 'array' ); //Reads cid as an array
        if($arrayIDs === null)
        { //Make sure the cid parameter was in the request
            JError::raiseError(500, 'cid parameter missing from the request');
        }
        $model =  $this->getModel('requestapproval');
        $model->deleterequestdetails($arrayIDs);
        
        $this->setRedirect('index.php?layout=requestapproval&option='.JRequest::getVar('option'), 'Deleted Successfully');
    }
    
    function publish() // Function to publish a category
    {
        $detail = JRequest::get('POST');

   
        $model =  $this->getModel('requestapproval');
        $model->pubmemberdetails($detail);
        $this->setRedirect('index.php?option='.JRequest::getVar('option').'&layout=requestapproval');
    }
    function unpublish() // Function to publish a category
    {
        $detail = JRequest::get('POST');


        $model =  $this->getModel('requestapproval');
        $model->unpubmemberdetails($detail);
        $this->setRedirect('index.php?option='.JRequest::getVar('option').'&layout=requestapproval');
    }
}
?>
