<?php
/**
 * @name        Social Pin Board
 * @version	1.0: memberdetails.php$
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
class socialpinboardControllermemberdetails extends SocialpinboardController
{
    function display($cachable = false, $urlparams = false) //Function to display the Category list
       {

        $viewName   = JRequest::getVar( 'view', 'memberdetails' );
        $viewLayout = JRequest::getVar( 'layout', 'memberdetails' );
        $view =  $this->getView($viewName);
        if ($model =  $this->getModel('memberdetails'))
        {
            $view->setModel($model, true);
        }
        $view->setLayout($viewLayout);
        $view->display();
    }

    
    function publish($cachable = false, $urlparams = false) // Function to publish a category
    {
        $detail = JRequest::get('POST');
        $model =  $this->getModel('memberdetails');
        $model->pubmemberdetails($detail);
        $this->setRedirect('index.php?option='.JRequest::getVar('option').'&layout=memberdetails');
    }
    function unpublish($cachable = false, $urlparams = false) // Function to unpublish a category
    {
        $detail = JRequest::get('POST');
        $model =  $this->getModel('memberdetails');
        $model->pubmemberdetails($detail);
        $this->setRedirect('index.php?option='.JRequest::getVar('option').'&layout=memberdetails');
    }
}
?>
