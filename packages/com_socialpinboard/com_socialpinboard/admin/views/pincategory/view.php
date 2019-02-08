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
class socialpinboardViewpincategory extends SocialpinboardView
{
     protected $canDo;
    function display($tpl=NULL)
    {
        require_once JPATH_COMPONENT . '/helpers/socialpinboard.php';
            $this->canDo = SocialpinboardHelper::getActions();
        if(JRequest::getVar('task')=='edit' ) // THIS CODE IS TO EDIT A PARTICULAR CATEGORY
        {

            JToolBarHelper::title('Social Pinboard Categories'.': [<small>Edit</small>]','category.png');
            JToolBarHelper::save(); //view the save toolbar
            JToolBarHelper::cancel(); //view the cancel toolbar
            $model = $this->getModel();
            $id=JRequest::getVar('cid');
            $editPincategory = $model->editpincategory($id[0]);
            $this->assignRef('editPincategory', $editPincategory);

            parent::display();
        }

        if(JRequest::getVar('task')=='add' ) // THIS CODE IS TO ADD A NEW CATEGORY
        {
            JToolBarHelper::title('Social Pinboard Categories'.': [<small>Add</small>]','category.png');
            JToolBarHelper::save(); //view the save toolbar
            JToolBarHelper::cancel(); //view the cancel toolbar
            $model = $this->getModel();
            $pinCategory = $model->getNewcategory();
            $this->assignRef('pinCategory', $pinCategory);
            parent::display();
        }

        if(JRequest::getVar('task')=='' ) //THIS CODE IS TO DISPLAY LIST OF CATEGORIES
        {
            JToolBarHelper::title('Social Pinboard Categories', 'category.png');
            if ($this->canDo->get('core.create'))
                {
            JToolBarHelper::addNew(); //view the add toolbar
                }
            JToolBarHelper::editList(); //view the edit toolbar
            if ($this->canDo->get('core.edit'))
                {
            JToolBarHelper::publishList(); //view the publish toolbar
                }
            JToolBarHelper::unpublishList(); //view the unpublish toolbar



            $model = $this->getModel('pincategory');
            $getPincategory = $model->getpincategory();
            $status_value = $getPincategory['status_value'];
            if($status_value != '3'){
                if ($this->canDo->get('core.delete'))
                {
               JToolBarHelper::trash(); //view the delete toolbar
                }
            }
            $this->assignRef('getPincategory', $getPincategory);
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
