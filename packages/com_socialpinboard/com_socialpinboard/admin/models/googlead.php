<?php
/**
 * @name        Social Pin Board
 * @version	1.4.5: googlead.php$
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
class socialpinboardModelgooglead extends SocialpinboardModel
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
    //To get the category from the database and display it
  function getgoogleads(){
       global $option, $mainframe;
       $mainframe = JFactory::getApplication();
        $db = $this->getDBO(); //to enable db connection
         $limitstart = '';
        $limit = $where = '';
        $status_filter_approval = JRequest::getInt('filter_google'); //get the status of the category
        $where1 = '';
        if ($status_filter_approval == '1') {
            $where = "WHERE status=1"; //query for displaying the approval
        } else if ($status_filter_approval == '2') {

            $where = "WHERE status=0"; //query for displaying the approval
        } else {

            $where = "WHERE (status=1 OR status=0)"; //query for displaying the approval
        }

        $query = "SELECT COUNT(ad_id) FROM #__pin_googlead $where "; //query for pagination
        $db->setQuery($query);
        $total = $db->loadresult();

        jimport('joomla.html.pagination');
        $pageNav = new JPagination($total,$this->getState('limitstart'), $this->getState('limit'));
        $filter_order = $mainframe->getUserStateFromRequest($option . 'filter_order', 'filter_order', 'ad_id', 'cmd');
        $filter_order_Dir = $mainframe->getUserStateFromRequest($option . 'filter_order_Dir', 'filter_order_Dir', 'asc', 'word');
if ($filter_order and $status_filter_approval == '') {
            $where .= ' ORDER BY ' . $filter_order . ' ' . $filter_order_Dir .
                    ' LIMIT ' . $pageNav->limitstart . ',' . $pageNav->limit;
        }
        $query="SELECT * from #__pin_googlead  $where"; //query for displaying the categories
        $db->setQuery($query);
        $_pin_ads = $db->loadObjectList();
        if ($_pin_ads === null)
        JError::raiseError(500, 'Error reading db');
        $_pin_ads=array('pageNav' => $pageNav,'limitstart'=>$limitstart,'pin_googlead'=>$_pin_ads, 'status_value' => $status_filter_approval); //merging the pagination values and results
        return $_pin_ads; //return the final result
  }
  //Function To edit the pin categories
  function editpinad($id){
        global $mainframe;
        $option = JRequest::getCmd('option');
        $db = JFactory::getDBO();
        $query = "select * from #__pin_googlead where ad_id=".$id;
        $db->setQuery( $query );
        $result = $db->loadObject();
        if ($result === null)
        JError::raiseError(500, 'detail with ID: '.$id.' not found.');
        else
        return $result;
     }

   //To Add the New categories into the tables
  function getNewgooglead(){
        $detailTableRow = $this->getTable ('googlead');
        $date = JFactory::getDate();
        $dateTime = $date->format("Y-m-d H:i:s") . "\n";
        $detailTableRow->ad_id = 0;
        $detailTableRow->pin_googlead = '';
        $detailTableRow->status = '';
        $detailTableRow->created_date = $dateTime;
        $detailTableRow->updated_date = '';
        return $detailTableRow;
    }
  //To Save the pin Category
  function savepinad($detail)
    {

        // this function is to save a category
        $detailTableRow = $this->getTable('googlead');
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
        $detailTableRow->checkin(); // to get the last inserted id
        $row = $detailTableRow->ad_id;
        return $row;
    }
    //
    function deletepinad($arrayIDs)
    {
        // function is to delete a particular category
        $db = $this->getDBO();
        $query = "DELETE FROM #__pin_googlead WHERE ad_id IN (".implode(',', $arrayIDs).")";
        $db->setQuery($query);
        if (!$db->query()){
            $errorMessage = $this->getDBO()->getErrorMsg();
            JError::raiseError(500, 'Error deleting Image: '.$errorMessage);
        }
    }

    // Function To publish or unpublish the category
    function pubpinad($arrayIDs)
    {
        if($arrayIDs['task']=="publish")
        {
            $publish=1; //to publish a category
        }
        else
        {
            $publish=0; //to unpublish a category
        }
        $n= count($arrayIDs['cid']);
        for($i=0;$i<$n;$i++)
        {
            $query = "UPDATE #__pin_googlead set status =".$publish." WHERE ad_id=".$arrayIDs['cid'][$i];//update query for publish and unpublish
            $db = $this->getDBO();
            $db->setQuery($query);
            $db->query();
        }
    }
}
?>