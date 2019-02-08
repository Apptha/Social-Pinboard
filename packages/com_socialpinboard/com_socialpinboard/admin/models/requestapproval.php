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
//No direct acesss
defined('_JEXEC') or die();
jimport('joomla.application.component.model');

class socialpinboardModelrequestapproval extends SocialpinboardModel {

    function __construct() {
        parent::__construct();
        //Get configuration
        $app = JFactory::getApplication();
        $config = JFactory::getConfig();
        $limitstart = '';
        // Get the pagination request variables
        $this->setState('limit', $app->getUserStateFromRequest('socialpinboard.limit', 'limit', $config->get('list_limit'), 'int'));
        $this->setState('limitstart', JRequest::getVar('limitstart', 0, '', 'int'));
    }

    function phpSlashes($string, $type='add') {
        if ($type == 'add') {
            if (get_magic_quotes_gpc ()) {
                return $string;
            } else {
                if (function_exists('addslashes')) {
                    return addslashes($string);
                } else {
                    return mysql_real_escape_string($string);
                }
            }
        } else if ($type == 'strip') {
            return stripslashes($string);
        } else {
            die('error in PHP_slashes (mixed,add | strip)');
        }
    }

    //To get the requested member details from the database and display it
    function getrequestmemberdetails() {

        global $option, $mainframe;
        $app = JFactory::getApplication();
        $db = $this->getDBO(); //to enable db connection
        $limitstart = '';
        $limit = $where = '';
        $search_filter_approval = JRequest::getVar('filter_search_approval'); //get the values for the search
        $status_filter_approval = JRequest::getInt('filter_approval'); //get the status of the category
        $where1 = '';
        $search_filter_approval = $this->phpSlashes($search_filter_approval);
        if ($search_filter_approval != '') {

            $where1 = " AND email_id LIKE '%$search_filter_approval%'";
        }
        if ($status_filter_approval == '1') {
            $where = "WHERE approval_status=1"; //query for displaying the approval
        } else if ($status_filter_approval == '2') {

            $where = "WHERE approval_status=0"; //query for displaying the approval
        } else {

            $where = "WHERE (approval_status=1 OR approval_status=0)"; //query for displaying the approval
        }

        $query = "SELECT COUNT(id) FROM #__pin_user_request $where $where1"; //query for pagination
        $db->setQuery($query);
        $total = $db->loadresult();
        jimport('joomla.html.pagination');
        $pageNav = new JPagination($total, $this->getState('limitstart'), $this->getState('limit'));
        $filter_order = $app->getUserStateFromRequest($option . 'filter_order', 'filter_order', 'id', 'cmd');
        $filter_order_Dir = $app->getUserStateFromRequest($option . 'filter_order_Dir', 'filter_order_Dir', 'asc', 'word');

        $where.=$where1;
        if ($filter_order and $status_filter_approval == '') {
            $where .= ' ORDER BY ' . $filter_order . ' ' . $filter_order_Dir .
                    ' LIMIT ' . $pageNav->limitstart . ',' . $pageNav->limit;
        }

        $query = "SELECT id,email_id,approval_status FROM #__pin_user_request $where "; //query for displaying the categories
        $db->setQuery($query);
        $requestmemberdetails = $db->loadObjectList();
        $requestmemberdetails = array('pageNav' => $pageNav, 'limitstart' => $limitstart, 'requestmemberdetails' => $requestmemberdetails, 'order_Dir' => $filter_order_Dir, 'order' => $filter_order, 'status_value' => $status_filter_approval); //merging the pagination values and results
        return $requestmemberdetails; //return the final result
    }

//To delete requested member details
    function deleterequestdetails($arrayIDs) {

        $db = $this->getDBO();

        $query = "Delete from #__pin_user_request WHERE id IN (" . implode(',', $arrayIDs) . ")";
        $db->setQuery($query);
        if (!$db->query()) {
            $errorMessage = $this->getDBO()->getErrorMsg();
            JError::raiseError(500, 'Error deleting : ' . $errorMessage);
        }

        $db = $this->getDBO();
        $query = "UPDATE #__pin_user_settings SET status=0 WHERE user_id IN (" . implode(',', $arrayIDs) . ")";
        $db->setQuery($query);
        if (!$db->query()) {
            $errorMessage = $this->getDBO()->getErrorMsg();
            JError::raiseError(500, 'Error deleting : ' . $errorMessage);
        }
    }

    // Function To publish or unpublish the Members
    function pubmemberdetails($arrayIDs) {
        if ($arrayIDs['task'] == "publish") {
            $publish = 1; //to publish a member
        } else {
            $publish = 0; //to unpublish a member
        }
        $n = count($arrayIDs['cid']);
        $db = $this->getDBO();
        $query = "SELECT params FROM #__template_styles WHERE template='socialpinboard'"; //update query for publish and unpublish
        $db->setQuery($query);
        $temp_param = $db->loadResult();
        $temp_param = explode(',', $temp_param);
        $temp_param = explode('{"logo":"', $temp_param[0]);
        $temp_param = explode('"', $temp_param[1]);
        $temp_param = str_replace('\/', '/', $temp_param[0]);

        for ($i = 0; $i < $n; $i++) {
            $query = "UPDATE #__pin_user_request set approval_status= $publish WHERE id=" . $arrayIDs['cid'][$i]; //update query for publish and unpublish
            $db->setQuery($query);
            $db->query();

            $query = "SELECT email_id FROM #__pin_user_request  WHERE id=" . $arrayIDs['cid'][$i] . " AND approval_status= '1'"; //update query for publish and unpublish
            $db->setQuery($query);
            $email = $db->loadResult();
            $app = JFactory::getApplication();
            $conf = JFactory::getConfig();
            $inviteId = $arrayIDs['cid'][$i];
            $siteName = $conf->get('sitename');
            $mailFrom = $conf->get('mailfrom');
            $userName = $conf->get('fromname');
            $adminUrl = JURI::base();

            $url = str_replace('administrator/', '', $adminUrl);
            $subject = "Invited to Join " . $siteName;
            if (!empty($temp_param)) {
                $image_source = $url . '/' . htmlspecialchars($temp_param);
            } else {
                $image_source = $url . '/templates/socialpinboard/images/logo-large.png';
            }
            $subject = "Invitation to Join " . $siteName;



            $activationlink = $url . 'index.php?option=com_socialpinboard&view=register';
                    $baseurl = $url;
                    $content = file_get_contents($url . '/templates/socialpinboard/emailtemplate/requestapproval.html');
                    $content = str_replace("{baseurl}", $baseurl, $content);
                    $content = str_replace("{site_logo}", $image_source, $content);
                    $content = str_replace("{site_name}", $siteName, $content);
                    $content = str_replace("{activationlink}", $activationlink, $content);

                $mailer = JFactory::getMailer();
            $config = JFactory::getConfig();
            $sender = $config->get('mailfrom');
            $mailer->addRecipient($email);
            $mailer->setSender($sender);
            $mailer->isHTML(true);
            $mailer->setSubject($subject);
            $mailer->Encoding = 'base64';
            $mailer->setBody($content);
            $send = $mailer->Send();
        }
    }

    function unpubmemberdetails($detail) {
        $app = JFactory::getApplication();
        $app->redirect('index.php?layout=requestapproval&option=' . JRequest::getVar('option'), 'Sorry cannot unpublish already published user');
    }

}

?>