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

//No direct acesss
defined('_JEXEC') or die();
jimport('joomla.application.component.model');
class socialpinboardModelmemberdetails extends SocialpinboardModel
{


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

    //To get the members from the database and display it
  function getmemberdetails(){
       global $option, $mainframe;
        $db = $this->getDBO(); //to enable db connection
        $search_member_details=JRequest::getVar('filter_search_member');
        $filter_memberdetail =  JRequest::getInt('filter_memberdetail');

        $app = JFactory::getApplication();
        $filter_order_category = $app->getUserStateFromRequest($option . 'filter_order_search', 'filter_order', 'name', 'cmd');
        $filter_order_Dir = $app->getUserStateFromRequest($option . 'filter_order_Dir', 'filter_order_Dir', 'asc', 'word');

         $limitstart ='';
        $limit = '';
        $search_member_details = $this->phpSlashes($search_member_details);
        if($search_member_details!='') {
         $where1 = "WHERE b.username LIKE '%$search_member_details%' OR  b.email LIKE '%$search_member_details%' "; //query for pagination
        }else if( $filter_memberdetail=='0') {
         $where1 = "WHERE b.published!= '-2' ";
        } else if($filter_memberdetail=='1') {
        $where1 = "WHERE b.status=1 and b.published!='-2'"; //query for displaying the categories
        } else if($filter_memberdetail=='2') {
        $where1 = "WHERE b.status=0 and b.published!='-2' "; //query for displaying the categories
        } else if($filter_memberdetail=='3'){
        $where1 = "WHERE b.status=0 and b.published='-2' "; //query for displaying the categories
        } else if($filter_memberdetail=='4'){
        $where1 = "WHERE  b.published!='-2' "; //query for displaying the categories
        }
        else{
        $where1='';
        }

        $query = "SELECT COUNT(*) FROM #__users a inner join #__pin_user_settings b on a.id=b.user_id $where1";
        $db->setQuery( $query );
        $total = $db->loadresult();
        jimport('joomla.html.pagination');
        $pageNav = new JPagination($total,$this->getState('limitstart'), $this->getState('limit'));
        if($search_member_details !='')
        {
              
         $where = "WHERE b.username
                LIKE '%$search_member_details%'
                OR b.email LIKE '%$search_member_details%'";
         //query for displaying the member details
        }
       else if( $filter_memberdetail=='0')
		{
                    $where = "WHERE b.published!= '-2' ";
                }
        else if($filter_memberdetail=='1')
        {
        $where = "WHERE b.status=1 and b.published!='-2'"; //query for displaying the categories
        }
        else if($filter_memberdetail=='2')
        {
        $where = "WHERE b.status=0 and b.published!='-2' "; //query for displaying the categories
        }
         else if($filter_memberdetail=='3')
        {
        $where = "WHERE b.status=0 and b.published='-2' "; //query for displaying the categories
        }
         else if($filter_memberdetail=='4')
        {
        $where = "WHERE  b.published!='-2' "; //query for displaying the categories
        }
        else
        {
            	$where='';

        }
         
       $query="SELECT a.*,b.* from #__users a
                INNER JOIN #__pin_user_settings b
                ON a.id=b.user_id $where ORDER BY $filter_order_category $filter_order_Dir
                LIMIT $pageNav->limitstart,$pageNav->limit";//query for displaying the seacrh member details
        
        
        $db->setQuery($query);
        $memberdetails = $db->loadObjectList();
        if ($memberdetails === null)
        JError::raiseError(500, 'Error reading db');
        $memberdetails=array('pageNav' => $pageNav,'limitstart'=>$limitstart,'memberdetails'=>$memberdetails,'status_value'=>$filter_memberdetail,'order_Dir'=>$filter_order_Dir,'order'=>$filter_order_category); //merging the pagination values and results
        return $memberdetails; //return the final result
  }
  //Function To edit the pin categories
  function editmemberdetails($id){
        global $mainframe;
        $option = JRequest::getCmd('option');
        $db = JFactory::getDBO();
        $query = "select * from #__users where id=".$id;
        $db->setQuery( $query );
        $result = $db->loadObject();
        if ($result === null)
        JError::raiseError(500, 'detail with ID: '.$id.' not found.');
        else
        return $result;
     }

   //To Add the New categories into the tables
  function getNewmemberdetails(){
        $detailsTableRow = $this->getTable ('memberdetails');
        $date = JFactory::getDate();
        $dateTime = $date->toFormat() . "\n";
        $detailsTableRow->category_id = 0;
        $detailsTableRow->category_name = '';
        $detailsTableRow->status = '';
        $detailsTableRow->created_date = $dateTime;
        $detailsTableRow->updated_date = '';
        return $detailsTableRow;
    }
  //To Save the pin Category
  function savememberdetails($detail)
    {

        // this function is to save a category
        $detailTableRow = $this->getTable('memberdetails');
        if (!$detailTableRow->bind($detail)) {
            JError::raiseError(500, 'Error binding data');
        }
        if (!$detailTableRow->check()) {
            JError::raiseError(500, 'Invalid data');
        }
        if (!$detailTableRow->store()) {
            $errorMessage = $detailTableRow->getError();
            JError::raiseError(500, 'Error binding data: '.$errorMessage);
        }
    }
    //
    function deletememberdetails($arrayIDs)
    {
        // function is to delete a particular category
        $db = $this->getDBO();
        $query = "UPDATE #__users SET block=1 WHERE id IN (".implode(',', $arrayIDs).")";
        $db->setQuery($query);
        
        
        if (!$db->query()){
            $errorMessage = $this->getDBO()->getErrorMsg();
            JError::raiseError(500, 'Error deleting : '.$errorMessage);
        }
         
        $db = $this->getDBO();
        $query = "UPDATE #__pin_user_settings SET status=0 WHERE user_id IN (".implode(',', $arrayIDs).")";
        $db->setQuery($query);
        if (!$db->query()){
            $errorMessage = $this->getDBO()->getErrorMsg();
            JError::raiseError(500, 'Error deleting : '.$errorMessage);
        }
        
    }

    // Function To publish or unpublish the Members
       function pubmemberdetails($arrayIDs)
    {
        if($arrayIDs['task']=="publish")
        {
            $publish=0;
            $status=1;//to publish a member
        }
        else
        {
            $publish=1; //to unpublish a member
            $status=0;
        }
        $n= count($arrayIDs['cid']);
        for($i=0;$i<$n;$i++)
        {
            $query = "UPDATE #__users set block =".$publish." WHERE id=".$arrayIDs['cid'][$i];//update query for publish and unpublish
            $db = $this->getDBO();
            $db->setQuery($query);
            $db->query();
             
          $query = "UPDATE `#__pin_user_settings` SET `status`=".$status.",`published`=".$publish." WHERE `user_id`=".$arrayIDs['cid'][$i];
          $db->setQuery($query);
          $db->query();
          $query = "UPDATE `#__pin_pins` SET `status`=".$status." WHERE `pin_user_id`=".$arrayIDs['cid'][$i];
          $db->setQuery($query);
          $db->query();
          $query = "UPDATE `#__pin_boards` SET `status`=".$status." WHERE `user_id`=".$arrayIDs['cid'][$i];
          $db->setQuery($query);
          $db->query();
        }
    }
    
}
?>