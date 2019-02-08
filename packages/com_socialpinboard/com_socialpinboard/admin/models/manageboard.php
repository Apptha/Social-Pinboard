<?php
/**
 * @name        Social Pin Board
 * @version	1.0: manageboard.php$
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
class socialpinboardModelmanageboard extends SocialpinboardModel
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

  function getPinboard(){
      
       global $option, $mainframe; 
       $app = JFactory::getApplication();
        $search_member_boards=JRequest::getVar('filter_search_board');
        $status_filter_board=  JRequest::getInt('filter_board');//get the status of the board
       
       $db = $this->getDBO(); //to enable db connection
       $limitstart ='';
        $limit ='';
        $where='';
$search_member_boards = $this->phpSlashes($search_member_boards);
        if($search_member_boards!='')
        {

           $where= "WHERE board_name LIKE '%$search_member_boards%'
                  OR board_description LIKE  '%$search_member_boards%' ";

        }else if($status_filter_board=='1')
        {
            
             $where = "WHERE status=1 and published!='-2'"; //query for displaying the board
        }else if($status_filter_board=='2')
        {

             $where = "WHERE status=0 and published!='-2'"; //query for displaying the board
        }else if($status_filter_board=='3')
        {

             $where = "WHERE published='-2'"; //query for displaying the board
        }
        
         $query = "SELECT COUNT(*) FROM #__pin_boards ".$where; //query for pagination

        $db->setQuery( $query );
        $total = $db->loadresult();
        jimport('joomla.html.pagination');
        $pageNav = new JPagination($total,$this->getState('limitstart'), $this->getState('limit')); 
         $filter_order_boards = $app->getUserStateFromRequest($option . 'filter_order_boards', 'filter_order', 'board_id', 'cmd');
        $filter_order_Dir = $app->getUserStateFromRequest($option . 'filter_order_Dir', 'filter_order_Dir', 'asc', 'word');
        if($search_member_boards!='')
        {
            
           $where= "WHERE a.board_name LIKE '%$search_member_boards%'
                  OR a.board_description LIKE  '%$search_member_boards%' ";
        
        }else if($status_filter_board=='1')
        {
           
             $where = "WHERE a.status=1 and a.published!='-2'"; //query for displaying the board
        }else if($status_filter_board=='2')
        {
            
             $where = "WHERE a.status=0 and a.published!='-2'"; //query for displaying the board
        }else if($status_filter_board=='3')
        {
            
             $where = "WHERE a.published='-2'"; //query for displaying the board
                }
                
                 if($filter_order_boards and $status_filter_board=='')
	{
           $where .= ' ORDER BY '.$filter_order_boards.' '.$filter_order_Dir. 
						  ' LIMIT '.$pageNav->limitstart.','.$pageNav->limit;
        }
                $query = "SELECT a.*,b.category_id,b.category_name,c.name,e.username 
                  FROM #__pin_boards a 
                  LEFT JOIN #__pin_categories b 
                  ON a.board_category_id=b.category_id  
                  LEFT JOIN #__users c on  a.user_id=c.id 
                  LEFT JOIN #__pin_user_settings as e 
                  ON a.user_id=e.user_id  $where";
              
        $db->setQuery($query);
        $manageboard = $db->loadObjectList();
         
        $manageboard=array('pageNav' => $pageNav,'limitstart'=>$limitstart,'manageboard'=>$manageboard,'order_Dir'=>$filter_order_Dir,'order'=>$filter_order_boards,'status_value'=>$status_filter_board); //merging the pagination values and results
        return $manageboard; //return the final result
  }

      function deletemanageboard($arrayIDs)
    {
        // function is to delete a particular category
        $db = $this->getDBO();
        $status_filter_board=  JRequest::getInt('filter_board');
        if($status_filter_board=='3')
        {
         $query = "Delete from #__pin_boards  WHERE published='-2' and board_id IN (".implode(',', $arrayIDs).")";
        $db->setQuery($query);
        $db->query();
        $query = "Delete from #__pin_pins WHERE published='-2' and pin_board_id IN (".implode(',', $arrayIDs).")";
        $db->setQuery($query);
        $db->query();
        }
        $query = "UPDATE #__pin_boards set status =0, published='-2' WHERE board_id IN (".implode(',', $arrayIDs).")";
        $db->setQuery($query);
        $db->query();
        $query = "UPDATE #__pin_pins set status =0, published='-2' WHERE pin_board_id IN (".implode(',', $arrayIDs).")";
       
        $db->setQuery($query);
        $db->query();
        if (!$db->query()){
            $errorMessage = $this->getDBO()->getErrorMsg();
            JError::raiseError(500, 'Error deleting board: '.$errorMessage);
        }
    }

   // Function To publish or unpublish the board
    function pubmanageboard($arrayIDs)
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
            $query = "UPDATE #__pin_boards set status =".$publish." ,  published=0 WHERE board_id=".$arrayIDs['cid'][$i];//update query for publish and unpublish
            $db = $this->getDBO();
            $db->setQuery($query);
            $db->query();
            
         $query = "UPDATE #__pin_pins set status ='$publish', published='0' WHERE pin_board_id =".$arrayIDs['cid'][$i];
    
        $db->setQuery($query);
        $db->query();
       
            
        }
    }

}
?>