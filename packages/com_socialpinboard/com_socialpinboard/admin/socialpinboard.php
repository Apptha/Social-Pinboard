<?php
/**
 * @name        Social Pin Board
 * @version	1.0: admin.socialpinboard.php$
 * @since       Joomla 1.5&1.6&1.7
 * @package	apptha
 * @subpackage	com_socialpinboard
 * @author      Contus Support
 * @copyright   Copyright (C) 2011 powered by Apptha
 * @license     GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined( '_JEXEC' ) or die( 'Restricted access' );
if(!defined('DS')){
    define('DS',DIRECTORY_SEPARATOR);
}
JLoader::register('SocialpinboardController', JPATH_COMPONENT.'/helpers/controller.php');
JLoader::register('SocialpinboardView', JPATH_COMPONENT.'/helpers/view.php');
JLoader::register('SocialpinboardModel', JPATH_COMPONENT.'/helpers/model.php');

if (!JFactory::getUser()->authorise('core.manage', 'com_socialpinboard'))
{
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

$controllerName = JRequest::getCmd( 'layout','controlpanel');
$document = JFactory::getDocument();
$document->addStyleSheet('components'.DS.'com_socialpinboard'.DS.'assets'.DS.'socialpinboard.css');
if($controllerName == 'pincategory') // to enable the user tab when the controller is set as user
{
    JSubMenuHelper::addEntry(JText::_('Categories'), 'index.php?option=com_socialpinboard&layout=pincategory', true );
    JSubMenuHelper::addEntry(JText::_('Member Details'), 'index.php?option=com_socialpinboard&layout=memberdetails');
    JSubMenuHelper::addEntry(JText::_('Manage Board'), 'index.php?option=com_socialpinboard&layout=manageboard');
    JSubMenuHelper::addEntry(JText::_('Manage Pins'), 'index.php?option=com_socialpinboard&layout=managepins');
    JSubMenuHelper::addEntry(JText::_('Site Settings'), 'index.php?option=com_socialpinboard&layout=sitesettings');
    JSubMenuHelper::addEntry(JText::_('Request Approval'), 'index.php?option=com_socialpinboard&layout=requestapproval');
    JSubMenuHelper::addEntry(JText::_('Google Adsense'), 'index.php?option=com_socialpinboard&layout=googlead');
}
else if ($controllerName == 'memberdetails')
{
    JSubMenuHelper::addEntry(JText::_('Categories'), 'index.php?option=com_socialpinboard&layout=pincategory');
    JSubMenuHelper::addEntry(JText::_('Member Details'), 'index.php?option=com_socialpinboard&layout=memberdetails',true);
    JSubMenuHelper::addEntry(JText::_('Manage Board'), 'index.php?option=com_socialpinboard&layout=manageboard');
    JSubMenuHelper::addEntry(JText::_('Manage Pins'), 'index.php?option=com_socialpinboard&layout=managepins');
    JSubMenuHelper::addEntry(JText::_('Site Settings'), 'index.php?option=com_socialpinboard&layout=sitesettings');
    JSubMenuHelper::addEntry(JText::_('Request Approval'), 'index.php?option=com_socialpinboard&layout=requestapproval');
    JSubMenuHelper::addEntry(JText::_('Google Adsense'), 'index.php?option=com_socialpinboard&layout=googlead');
}
else if ($controllerName == 'manageboard')
{
    JSubMenuHelper::addEntry(JText::_('Categories'), 'index.php?option=com_socialpinboard&layout=pincategory');
    JSubMenuHelper::addEntry(JText::_('Member Details'), 'index.php?option=com_socialpinboard&layout=memberdetails');
    JSubMenuHelper::addEntry(JText::_('Manage Board'), 'index.php?option=com_socialpinboard&layout=manageboard',true);
    JSubMenuHelper::addEntry(JText::_('Manage Pins'), 'index.php?option=com_socialpinboard&layout=managepins');
    JSubMenuHelper::addEntry(JText::_('Site Settings'), 'index.php?option=com_socialpinboard&layout=sitesettings');
    JSubMenuHelper::addEntry(JText::_('Request Approval'), 'index.php?option=com_socialpinboard&layout=requestapproval');
    JSubMenuHelper::addEntry(JText::_('Google Adsense'), 'index.php?option=com_socialpinboard&layout=googlead');
}
else if ($controllerName == 'managepins')
{
    JSubMenuHelper::addEntry(JText::_('Categories'), 'index.php?option=com_socialpinboard&layout=pincategory');
    JSubMenuHelper::addEntry(JText::_('Member Details'), 'index.php?option=com_socialpinboard&layout=memberdetails');
    JSubMenuHelper::addEntry(JText::_('Manage Board'), 'index.php?option=com_socialpinboard&layout=manageboard');
    JSubMenuHelper::addEntry(JText::_('Manage Pins'), 'index.php?option=com_socialpinboard&layout=managepins',true);
    JSubMenuHelper::addEntry(JText::_('Site Settings'), 'index.php?option=com_socialpinboard&layout=sitesettings');
    JSubMenuHelper::addEntry(JText::_('Request Approval'), 'index.php?option=com_socialpinboard&layout=requestapproval');
    JSubMenuHelper::addEntry(JText::_('Google Adsense'), 'index.php?option=com_socialpinboard&layout=googlead');
}
else if ($controllerName == 'sitesettings')
{
    JSubMenuHelper::addEntry(JText::_('Categories'), 'index.php?option=com_socialpinboard&layout=pincategory');
    JSubMenuHelper::addEntry(JText::_('Member Details'), 'index.php?option=com_socialpinboard&layout=memberdetails');
    JSubMenuHelper::addEntry(JText::_('Manage Board'), 'index.php?option=com_socialpinboard&layout=manageboard');
    JSubMenuHelper::addEntry(JText::_('Manage Pins'), 'index.php?option=com_socialpinboard&layout=managepins');
    JSubMenuHelper::addEntry(JText::_('Site Settings'), 'index.php?option=com_socialpinboard&layout=sitesettings',true);
    JSubMenuHelper::addEntry(JText::_('Request Approval'), 'index.php?option=com_socialpinboard&layout=requestapproval');
    JSubMenuHelper::addEntry(JText::_('Google Adsense'), 'index.php?option=com_socialpinboard&layout=googlead');
}
else if ($controllerName == 'requestapproval')
{
    JSubMenuHelper::addEntry(JText::_('Categories'), 'index.php?option=com_socialpinboard&layout=pincategory');
    JSubMenuHelper::addEntry(JText::_('Member Details'), 'index.php?option=com_socialpinboard&layout=memberdetails');
    JSubMenuHelper::addEntry(JText::_('Manage Board'), 'index.php?option=com_socialpinboard&layout=manageboard');
    JSubMenuHelper::addEntry(JText::_('Manage Pins'), 'index.php?option=com_socialpinboard&layout=managepins');
    JSubMenuHelper::addEntry(JText::_('Site Settings'), 'index.php?option=com_socialpinboard&layout=sitesettings');
    JSubMenuHelper::addEntry(JText::_('Request Approval'), 'index.php?option=com_socialpinboard&layout=requestapproval',true);
    JSubMenuHelper::addEntry(JText::_('Google Adsense'), 'index.php?option=com_socialpinboard&layout=googlead');
}else if ($controllerName == 'googlead')
{
    JSubMenuHelper::addEntry(JText::_('Categories'), 'index.php?option=com_socialpinboard&layout=pincategory');
    JSubMenuHelper::addEntry(JText::_('Member Details'), 'index.php?option=com_socialpinboard&layout=memberdetails');
    JSubMenuHelper::addEntry(JText::_('Manage Board'), 'index.php?option=com_socialpinboard&layout=manageboard');
    JSubMenuHelper::addEntry(JText::_('Manage Pins'), 'index.php?option=com_socialpinboard&layout=managepins');
    JSubMenuHelper::addEntry(JText::_('Site Settings'), 'index.php?option=com_socialpinboard&layout=sitesettings');
    JSubMenuHelper::addEntry(JText::_('Request Approval'), 'index.php?option=com_socialpinboard&layout=requestapproval');
    JSubMenuHelper::addEntry(JText::_('Google AdSense'), 'index.php?option=com_socialpinboard&layout=googlead',true);
}
switch ($controllerName)
{
    default:
        $controllerName = 'controlpanel';
              case 'pincategory':
                  case 'memberdetails':
                      case 'manageboard':
                          case 'managepins':
                              case 'sitesettings':
                                  case 'requestapproval':
                                      case 'googlead':
                        // Temporary interceptor
                        $task = JRequest::getCmd('task');
                        require_once( JPATH_COMPONENT.DS.'controllers'.DS.$controllerName.'.php' );
                        $controllerName = 'socialpinboardController'.$controllerName;
                        // Create the controller
                        $controller = new $controllerName();
                        // Perform the Request task
                        $controller->execute( JRequest::getCmd('task') );
                        // Redirect if set by the controller
                        $controller->redirect();
                        break;
                    }


                    
?>
