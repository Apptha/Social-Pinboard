<?php
/**
 * @name        Social Pin Board
 * @version	1.0: managepins.php$
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
class socialpinboardModelmanagepins extends SocialpinboardModel
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

    //To get the category from the database and display it
 function getpins(){
       global $option, $mainframe;
        $search_value=JRequest::getVar('filter_search_pin');
         $app = JFactory::getApplication();
       // filter variable for ads status search
        $status_filter_pins=  JRequest::getInt('filter_pins');//get the status of the category
        $filter_order_pins = $app->getUserStateFromRequest($option . 'filter_order_pins', 'filter_order', 'pin_id', 'cmd');
        $filter_order_Dir = $app->getUserStateFromRequest($option . 'filter_order_Dir', 'filter_order_Dir', 'asc', 'word');
        $search_value = $this->phpSlashes($search_value);
        $db = $this->getDBO(); //to enable db connection
        $limitstart ='';
        $limit ='';
		$where = '';
		$arrStatusFilter['pin_status'] = '';
	//count(pins)
                 if($search_value!='')
        {
            $where= "WHERE a.pin_description  LIKE '%$search_value%' OR e.username LIKE '%$search_value%'";
        }else if($filter_order_pins and $status_filter_pins=='')
		{
                    $where .= " WHERE a.published!= '-2' ";
                }else if($status_filter_pins=='1')
        {
        $where = "WHERE e.status=1 and a.published!='-2'"; //query for displaying the categories
        }
        else if($status_filter_pins=='2')
        {
        $where = "WHERE e.status=0 and a.published!='-2' "; //query for displaying the categories
        }
         else if($status_filter_pins=='3')
        {
        $where = "WHERE e.status=0 and a.published='-2' "; //query for displaying the categories
        }
         else if($status_filter_pins=='4')
        {
        $where = "WHERE  a.published!='-2' "; //query for displaying the categories
        }
        else
        {
            	$where='';
			
        }
             $query="SELECT COUNT(a.pin_id) FROM #__pin_pins as a INNER JOIN #__pin_user_settings AS e 
                ON a.pin_user_id=e.user_id $where ";
             $db->setQuery( $query );
        $total = $db->loadresult();
        jimport('joomla.html.pagination');
        $pageNav = new JPagination($total,$this->getState('limitstart'), $this->getState('limit')); 
              //ends  
         if($search_value!='')
        {
            $where= "WHERE a.pin_description  LIKE '%$search_value%' OR e.username LIKE '%$search_value%'";
        }else if($filter_order_pins and $status_filter_pins=='')
		{
                    $where =  "WHERE a.published!= '-2'" ;
                }else if($status_filter_pins=='1')
        {
        $where = "WHERE a.status=1 and a.published!='-2'"; //query for displaying the categories
        }
        else if($status_filter_pins=='2')
        {
        $where = "WHERE a.status=0 and a.published!='-2' "; //query for displaying the categories
        }
         else if($status_filter_pins=='3')
        {
        $where = "WHERE a.status=0 and a.published='-2' "; //query for displaying the categories
        }
         else if($status_filter_pins=='4')
        {
        $where = "WHERE  a.published!='-2' "; //query for displaying the categories
        }
        else
        {
            	$where="WHERE  a.published!='-2'";
			
        }
   
        		
        
         $query="SELECT a.*,b.category_name,d.board_name,e.username 
                FROM #__pin_pins a LEFT JOIN #__pin_categories b 
                ON a.pin_category_id=b.category_id 
                LEFT JOIN #__pin_boards d on a.pin_board_id=d.board_id 
                LEFT JOIN #__pin_user_settings AS e 
                ON a.pin_user_id=e.user_id $where ORDER BY $filter_order_pins  $filter_order_Dir 
		LIMIT $pageNav->limitstart , $pageNav->limit"; //query for displaying the categories
      
       
	$db->setQuery( $query );
        $managepins = $db->loadObjectList();
          jimport('joomla.html.pagination');
        $pageNav = new JPagination($total,$this->getState('limitstart'), $this->getState('limit'));

       
        if ($managepins === null)
        JError::raiseError(500, 'Error reading db');
          $managepins=array('pageNav' => $pageNav,'limitstart'=>$limitstart,'managepins'=>$managepins,'order_Dir'=>$filter_order_Dir,'order'=>$filter_order_pins,'status_value'=>$status_filter_pins); //merging the pagination values and results
        return $managepins; //return the final result
  }

    function deletemanagepins($arrayIDs)
    {
        // function is to delete a particular category
        $db = $this->getDBO();
        $status_filter_pins=  JRequest::getInt('filter_pins');
        if($status_filter_pins=='3')
        {
        $query = "Delete from #__pin_pins  WHERE published= -2 and pin_id IN (".implode(',', $arrayIDs).")";
        $db->setQuery($query);
         $db->query();
        }
        $query = "UPDATE #__pin_pins set status =0, published= -2 WHERE pin_id IN (".implode(',', $arrayIDs).")";
        $db->setQuery($query);
        
        
        if (!$db->query()){
            $errorMessage = $this->getDBO()->getErrorMsg();
            JError::raiseError(500, 'Error deleting boards: '.$errorMessage);
        }
    }

    // Function To publish or unpublish the category
    function pubmanagepins($arrayIDs)
    {     
        if($arrayIDs['task']=="publish")
        {
            $status=1; //to publish a category
            $publish=0;
        }
        else
        {
            $status=0; //to unpublish a category
            $publish=0;
        }
        $n= count($arrayIDs['cid']);
        for($i=0;$i<$n;$i++)
        {
            $query = "UPDATE #__pin_pins set status =".$status." ,published= $publish WHERE pin_id=".$arrayIDs['cid'][$i];//update query for publish and unpublish
           
            $db = $this->getDBO();
            $db->setQuery($query);
            $db->query();
        }
}
}
?>