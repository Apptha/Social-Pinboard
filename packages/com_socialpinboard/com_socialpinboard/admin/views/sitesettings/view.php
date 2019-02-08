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
class socialpinboardViewsitesettings extends SocialpinboardView
{
    protected $canDo;
    function display($tpl=NULL)
    {
        require_once JPATH_COMPONENT . '/helpers/socialpinboard.php';
            $this->canDo = SocialpinboardHelper::getActions();
        if(JRequest::getVar('task')=='' )
        {
            JToolBarHelper::title('Social Pinboard Site Settings', 'site-settings-icon.png');
            JToolBarHelper::apply(); //view the edit toolbar
            $model = $this->getModel('sitesettings');
            $getsitesettings = $model->getsitesettings();
            $this->assignRef('getsitesettings', $getsitesettings);

            $Social_Pinboard = $model->lKey();
            $this->assignRef('Social_Pinboard', $Social_Pinboard);
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
