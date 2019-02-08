<?php
/**
 * @name        Social Pin Board
 * @version	1.0: controlpanel.php$
 * @since       Joomla 1.5&1.6&1.7
 * @package	apptha
 * @subpackage	com_socialpinboard
 * @author      Contus Support
 * @copyright   Copyright (C) 2011 powered by Apptha
 * @license     GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');


class socialpinboardControllercontrolpanel extends SocialpinboardController {

    function display($cachable = false, $urlparams = false) {
        $viewName = JRequest::getVar('view', 'controlpanel');
        $viewLayout = JRequest::getVar('layout', 'controlpanel');
        $view =  $this->getView($viewName);
        if ($model =  $this->getModel('controlpanel')) {
            $view->setModel($model, true);
        }
        $view->setLayout($viewLayout);
        $view->display();
    }


}

?>
