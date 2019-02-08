<?php
/*
 * Author        : Apptha
 * Author E-mail : support@contus.in
 * Author URI    : http://www.apptha.com
 * Description   : Redirect to the mobile using userAgent
 * Created Date  : 20/06/2011
 * @copyright    : Copyright (C) Powered by Apptha
 * @license      : GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
    defined( '_JEXEC' ) or die( 'Restricted access' );
    jimport( 'joomla.html.parameter' );

    class plgSystemAppthaRedirect extends JPlugin
    {

        function plgSystemAppthaRedirect(&$subject, $config)
        {
            parent::__construct($subject, $config);
        }

        // get user agent
        function onAfterInitialise()
        {
            global $mainframe;
            $mainframe = JFactory::getApplication();
            if ($mainframe->isAdmin()) return;

             $component=JRequest::getVar('option');
             $view =JRequest::getVar('view');
             if(($component== 'com_users' || $component== 'com_user') && ($view=='login' || $view=='registration')){
                 $return = JRoute::_('index.php?option=com_socialpinboard&view=home');
                    $url = "index.php?option=com_socialpinboard&view=people";
                    $url .= '&returnURL=' . base64_encode($return);
                    $urlredirect = JRoute::_($url);
                 JApplication::redirect($urlredirect);
             }elseif(($component== 'com_users' || $component== 'com_user') && $view=='profile' && JRequest::getVar('user_id')!=''){
                 $urlredirect=JRoute::_('index.php?option=com_socialpinboard&view=home');
                 JApplication::redirect($urlredirect);
             }
        }

         function onAfterRoute()
        {
             $component=JRequest::getVar('option');
             $view =JRequest::getVar('view');
             if(($component== 'com_users' || $component== 'com_user') && ($view=='login' || $view=='registration')){
                  $return = JRoute::_('index.php?option=com_socialpinboard&view=home');
                    $url = "index.php?option=com_socialpinboard&view=people";
                    $url .= '&returnURL=' . base64_encode($return);
                    $urlredirect = JRoute::_($url);
                 JApplication::redirect($urlredirect);
             }elseif(($component== 'com_users' || $component== 'com_user') && $view=='profile' && JRequest::getVar('user_id')!=''){
                 $urlredirect=JRoute::_('index.php?option=com_socialpinboard&view=home');
                 JApplication::redirect($urlredirect);
             }
        }
       
    }