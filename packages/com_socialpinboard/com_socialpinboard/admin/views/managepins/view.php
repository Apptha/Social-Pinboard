<?php
/**
 * @name        Social Pin Board
 * @version	1.0: view.php$
 * @since       Joomla 1.5&1.6&1.7
 * @package	apptha
 * @subpackage	com_socialpinboard
 * @author      Contus Support
 * @copyright   Copyright (C) 2011 powered by Apptha
 * @license     GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.view');
class socialpinboardViewmanagepins extends SocialpinboardView
{
     protected $canDo;
    function display($tpl=NULL)
    {
       if(JRequest::getVar('task')=='' ) //THIS CODE IS TO DISPLAY LIST OF CATEGORIES
        {

            require_once JPATH_COMPONENT . '/helpers/socialpinboard.php';
            $this->canDo = SocialpinboardHelper::getActions();
            JToolBarHelper::title('Social Pinboard Manage Pins', 'manege-pins.png');
            JToolBarHelper::publishList(); //view the publish toolbar
            JToolBarHelper::unpublishList(); //view the unpublish toolbar
            $model = $this->getModel('managepins');
            $getPins = $model->getPins();
            $status_value = $getPins['status_value'];
            if($status_value != '3'){
                if ($this->canDo->get('core.delete'))
                {
               JToolBarHelper::trash(); //view the delete toolbar
            }
            }
            if ($this->canDo->get('core.admin'))
                {
                        JToolBarHelper::divider();
                        JToolBarHelper::preferences('com_socialpinboard');
                }
            $this->assignRef('getPins', $getPins);
            parent::display();
        }
    }

}
?>
