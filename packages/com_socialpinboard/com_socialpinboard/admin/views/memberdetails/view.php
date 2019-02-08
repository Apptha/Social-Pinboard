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
class socialpinboardViewmemberdetails extends SocialpinboardView
{
     protected $canDo;
    function display($tpl=NULL)
    {
        require_once JPATH_COMPONENT . '/helpers/socialpinboard.php';
            $this->canDo = SocialpinboardHelper::getActions();
        if(JRequest::getVar('task')=='edit' ) // THIS CODE IS TO EDIT A PARTICULAR CATEGORY
        {

            JToolBarHelper::title('Social Pinboard Member Details'.': [<small>Edit</small>]','user.png');
            JToolBarHelper::save(); //view the save toolbar
            JToolBarHelper::apply(); //view the apply toolbar
            JToolBarHelper::cancel(); //view the cancel toolbar
            $model = $this->getModel();
            $id=JRequest::getVar('cid');
            $editmemberdetails = $model->editmemberdetails($id[0]);
            $this->assignRef('editmemberdetails', $editmemberdetails);

            parent::display();
        }

        if(JRequest::getVar('task')=='add' ) // THIS CODE IS TO ADD A NEW CATEGORY
        {
            JToolBarHelper::title('Social Pinboard Member Details'.': [<small>Add</small>]','social-member.png');
            JToolBarHelper::save(); //view the save toolbar
            JToolBarHelper::cancel(); //view the cancel toolbar
            $model = $this->getModel();
            $memberdetails = $model->getNewmemberdetails();
            $this->assignRef('memberdetails', $memberdetails);
            parent::display();
        }

        if(JRequest::getVar('task')=='' ) //THIS CODE IS TO DISPLAY LIST OF CATEGORIES
        {
            JToolBarHelper::title('Social Pinboard Member Details', 'social-member.png');
            JToolBarHelper::publishList(); //view the publish toolbar
            JToolBarHelper::unpublishList(); //view the unpublish toolbar
            $model = $this->getModel('memberdetails');
            $memberdetails = $model->getmemberdetails();
            $this->assignRef('memberdetails', $memberdetails);
            parent::display();
        }
        if ($this->canDo->get('core.admin'))
                {
                        JToolBarHelper::divider();
                        JToolBarHelper::preferences('com_socialpinboard');
    }
    }

}
?>
