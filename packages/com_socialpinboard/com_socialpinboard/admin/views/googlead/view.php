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
class socialpinboardViewgooglead extends SocialpinboardView
{
     protected $canDo;
    function display($tpl=NULL)
    {
        require_once JPATH_COMPONENT . '/helpers/socialpinboard.php';
            $this->canDo = SocialpinboardHelper::getActions();
        if(JRequest::getVar('task')=='edit' ) // THIS CODE IS TO EDIT A PARTICULAR CATEGORY
        {

            JToolBarHelper::title('Social Pinboard Google AdSense'.': [<small>Edit</small>]','google-ad-icon.png');
            JToolBarHelper::save(); //view the save toolbar
            JToolBarHelper::apply(); //view the apply toolbar
            JToolBarHelper::cancel(); //view the cancel toolbar
            $model = $this->getModel();
            $id=JRequest::getVar('cid');
            $editPinad = $model->editpinad($id[0]);
            $this->assignRef('editPinad', $editPinad);

            parent::display();
        }

        if(JRequest::getVar('task')=='add' ) // THIS CODE IS TO ADD A NEW CATEGORY
        {
            JToolBarHelper::title('Social Pinboard Google AdSense'.': [<small>Add</small>]','google-ad-icon.png');
            JToolBarHelper::save(); //view the save toolbar
            JToolBarHelper::apply(); //view the apply toolbar
            JToolBarHelper::cancel(); //view the cancel toolbar
            $model = $this->getModel();
            $pinCategory = $model->getNewgooglead();
            $this->assignRef('pinCategory', $pinCategory);
            parent::display();
        }

        if(JRequest::getVar('task')=='' ) //THIS CODE IS TO DISPLAY LIST OF CATEGORIES
        {
            JToolBarHelper::title('Social Pinboard Google AdSense', 'google-ad-icon.png');
            if ($this->canDo->get('core.delete'))
                {
            JToolBarHelper::deleteList('', 'remove'); //view the delete toolbar
                }
            JToolBarHelper::publishList(); //view the publish toolbar
            JToolBarHelper::unpublishList(); //view the unpublish toolbar
            if ($this->canDo->get('core.create'))
                {
            JToolBarHelper::addNew(); //view the add toolbar
                }
                 if ($this->canDo->get('core.edit'))
                {
            JToolBarHelper::editList(); //view the edit toolbar
                }
                if ($this->canDo->get('core.admin'))
                {
                        JToolBarHelper::divider();
                        JToolBarHelper::preferences('com_socialpinboard');
                }
            $model = $this->getModel('googlead');
            $getGoogleads = $model->getgoogleads();
            $this->assignRef('getGooglead', $getGoogleads);
            parent::display();
        }
    }

}
?>
