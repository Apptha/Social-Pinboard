<?php

/**
 * @name        Social Pin Board
 * @version	1.0: pincategory.php$
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
jimport('joomla.filesystem.file');

class socialpinboardModelpincategory extends SocialpinboardModel {

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
    function getpincategory() {
        global $option, $mainframe;
        $app = JFactory::getApplication();
        $mainframe = JFactory::getApplication();
        $search_filter_category=JRequest::getVar('filter_search_category');//get the values for the search
        $status_filter_category=  JRequest::getInt('filter_category');//get the status of the category
        $db = $this->getDBO(); //to enable db connection
        $limitstart = '';
        $limit = '';
        $where='';
       /*  if($search_filter_category=='')
        {
        $query = "SELECT COUNT(category_id) FROM #__pin_categories"; //query for pagination
        }else
        {
        $query = "SELECT COUNT(category_id) FROM #__pin_categories WHERE category_name LIKE '%$search_filter_category%' "; //query for pagination
        }
        $db->setQuery($query);
        $total = $db->loadresult();
        jimport('joomla.html.pagination');
        $pageNav = new JPagination($total, $this->getState('limitstart'), $this->getState('limit'));*/
         $filter_order_category = $app->getUserStateFromRequest($option . 'filter_order_category', 'filter_order', 'category_id', 'cmd');

        $filter_order_Dir = $app->getUserStateFromRequest($option . 'filter_order_Dir', 'filter_order_Dir', 'asc', 'word');
         $search_filter_category = $this->phpSlashes($search_filter_category);
        if($search_filter_category!='')
        {
        $where = "WHERE category_name LIKE '%$search_filter_category%' and published!='-2' "; //query for displaying the categories

        }else if($status_filter_category=='1')
        {
        $where = "WHERE status=1 and published!='-2'"; //query for displaying the categories
        }
        else if($status_filter_category=='2' )
        {
        $where = "WHERE status=0 and published!='-2' "; //query for displaying the categories
        }
         else if($status_filter_category=='3')
        {
        $where = "WHERE status=0 and published='-2' "; //query for displaying the categories
        }
         else if($status_filter_category=='4')
        {


        $where = "WHERE  published!='-2' "; //query for displaying the categories
        }



        if($filter_order_category and $status_filter_category=='' and $search_filter_category=='')
		{

                    $where1 = ' WHERE published!=-2  ';
                }
                else{
                   $where1 = '';
                }


         $query = "SELECT COUNT(category_id) FROM #__pin_categories $where $where1 "; //query for pagination

        $db->setQuery($query);
        $total = $db->loadresult();
        jimport('joomla.html.pagination');
        $pageNav = new JPagination($total, $this->getState('limitstart'), $this->getState('limit'));


        //Sorting query
		if($filter_order_category and $status_filter_category=='' and $search_filter_category=='')
		{

                    $where .= ' WHERE published!=-2  ORDER BY '.$filter_order_category.' '.$filter_order_Dir.
						  ' LIMIT '.$pageNav->limitstart.','.$pageNav->limit;
                }
                else{
			$where .= '  ORDER BY '.$filter_order_category.' '.$filter_order_Dir.
						  ' LIMIT '.$pageNav->limitstart.','.$pageNav->limit;

		}

        $query = "SELECT category_name,status,created_date,category_image,category_id from #__pin_categories  $where  "; //query for displaying the categories

        $db->setQuery($query);
        $_pin_category = $db->loadObjectList();


        if ($_pin_category === null)
        JError::raiseError(500, 'Error reading db');



        $_pin_category = array('pageNav' => $pageNav, 'limitstart' => $limitstart, 'pin_category' => $_pin_category,'order_Dir'=>$filter_order_Dir,'order'=>$filter_order_category,'status_value'=>$status_filter_category); //merging the pagination values and results
        return $_pin_category; //return the final result
    }

    //Function To edit the pin categories
    function editpincategory($id) {
        global $mainframe;
        $option = JRequest::getCmd('option');
        $db =  JFactory::getDBO();
        $query = "select category_name,status,created_date,category_image,category_id from #__pin_categories where category_id=" . $id;
        $db->setQuery($query);
        $result = $db->loadObject();
        if ($result === null)
            JError::raiseError(500, 'detail with ID: ' . $id . ' not found.');
        else
            return $result;
    }

    //To Add the New categories into the tables
    function getNewcategory() {
        $detailTableRow =  $this->getTable('pincategory');
        $date =  JFactory::getDate();
        $dateTime = $date->format("Y-m-d H:i:s") . "\n";
        $detailTableRow->category_id = 0;
        $detailTableRow->category_name = '';
        $detailTableRow->status = '';
        $detailTableRow->created_date = $dateTime;
        $detailTableRow->updated_date = '';
        $detailTableRow->category_image = '';
        return $detailTableRow;
    }

    //To Save the pin Category
    function savepincategory($detail) {
        $db=JFactory::getDbo();

        $cid = $detail['cid'];

        $file =  JRequest::getVar('category_image', '', 'files', 'array'); //Get File name, tmp_name
        $category_image_name=  JRequest::getVar('category_image_name', '', 'default');
        $category_id= JRequest::getInt('category_id','0', 'default');
        $categoryName =  JRequest::getVar('category_name', '', 'post', 'string'); //Get category_name
        $query = 'SELECT `category_id`, `category_name`, `status`, `published`,  `category_image`
                  FROM `#__pin_categories`
                  WHERE  `category_name`="$categoryName"'; //query for displaying the categories

        $db->setQuery($query);
        $_pin_category = $db->loadObject();

        $row=$_pin_category->category_id;
        $arrayIDs = JRequest::getVar('cid', null, 'default', 'array' );

        $category_image=$_pin_category->category_image;
        $category_db_name=$_pin_category->category_name;


           if($arrayIDs[0] == "" && strtolower($categoryName) == strtolower($category_db_name))
        {

          $app =  JFactory::getApplication();
          $app->redirect(JRoute::_('index.php?option=com_socialpinboard&layout=pincategory',false),"Category Name Already Exists", $msgType = 'message');
       }


        if($category_image=='')
        { $category_image=  JRequest::getVar('category_image_name', '', 'default');
        }


//        if($category_db_name !=$categoryName )
//        {
            $detailTableRow =  $this->getTable('pincategory');
            if (!$detailTableRow->bind($detail)) {
                JError::raiseError(500, 'Error binding data');
            }
            if (!$detailTableRow->check()) {
                JError::raiseError(500, 'Invalid data');
            }
            if (!$detailTableRow->store()) {
                $errorMessage = $detailTableRow->getError();
                JError::raiseError(500, 'Error binding data: ' . $errorMessage);

            }

            $detailTableRow->checkin(); // to get the last inserted id
            $row = $detailTableRow->category_id;
        //}
            if ($file['name'] != '') {
               $userImage = JFile::makeSafe($file['name']);
            //my code
            $image = JFile::makeSafe($file['name']);
            $uploadedfile = $file['tmp_name'];
            $userImageDetails = pathinfo($image);
            $extension = strtolower($userImageDetails['extension']);
            $category_image=$userImageDetails['basename'];

            if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {

                $change = '<div class="msgdiv">Unknown Image extension </div> ';
                $errors = 1;
            } else {

                $size = filesize($file['tmp_name']);
                if ($extension == "jpg" || $extension == "jpeg") {
                    $uploadedfile = $file['tmp_name'];
                    $src = imagecreatefromjpeg($uploadedfile);
                } else if ($extension == "png") {
                    $uploadedfile = $file['tmp_name'];
                    $src = imagecreatefrompng($uploadedfile);
                } else if ($extension == "gif") {
                     $uploadedfile = $file['tmp_name'];
                    $src = imagecreatefromgif($uploadedfile);
                }
                list($width, $height) = getimagesize($uploadedfile);
                $newwidth1 = 150;
                $newheight1 = 150;
                $tmp1 = imagecreatetruecolor($newwidth1, $newheight1);
                if ($extension == "png") {

                    $kek1 = imagecolorallocate($tmp1, 255, 255, 255);
                    imagefill($tmp1, 0, 0, $kek1);
                }
                imagecopyresampled($tmp1, $src, 0, 0, 0, 0, $newwidth1, $newheight1, $width, $height);

                $filename1 = JPATH_SITE . DS . "components" . DS . "com_socialpinboard" . DS . "images" . DS . "category" . DS . $userImageDetails['basename'];
                imagejpeg($tmp1, $filename1, 100);

                imagedestroy($tmp1);
            }
       }

            $db = Jfactory::getDBO();
            $query = "UPDATE #__pin_categories set category_image ='" . $category_image . "' WHERE category_id=" . $row;
            $db->setQuery($query);
            $db->query();

    }

    //
    function deletepincategory($arrayIDs) {
        // function is to delete a particular category
        $db = $this->getDBO();

        $status_filter_category=  JRequest::getInt('filter_category');

        if($status_filter_category=='3')
        {
        $query = "DELETE FROM #__pin_categories WHERE published='-2' AND category_id IN (" . implode(',', $arrayIDs) . ")";
        $db->setQuery($query);
        $db->query();
        }
        else{
        $query = "UPDATE #__pin_categories set published='-2',status=0 WHERE category_id IN (" . implode(',', $arrayIDs) . ")";
        $db->setQuery($query);
        $db->query();
        $query = "UPDATE #__pin_boards set published='-2',status=0 WHERE board_category_id IN (" . implode(',', $arrayIDs) . ")";
        $db->setQuery($query);
        $db->query();
        $query = "UPDATE #__pin_pins set published='-2',status=0 WHERE pin_category_id IN (" . implode(',', $arrayIDs) . ")";
        $db->setQuery($query);
        $db->query();
        if (!$db->query()) {
            $errorMessage = $this->getDBO()->getErrorMsg();
            JError::raiseError(500, 'Error deleting Image: ' . $errorMessage);
        }
    }
    }

    // Function To publish or unpublish the category
    function pubpincategory($arrayIDs) {
        $db = $this->getDBO();
        if ($arrayIDs['task'] == "publish") {
            $publish = 1; //to publish a category
        } else {
            $publish = 0; //to unpublish a category
        }
        $n = count($arrayIDs['cid']);
        for ($i = 0; $i < $n; $i++) {
            $query = "UPDATE #__pin_categories set status =" . $publish . ", published=0 WHERE category_id=" . $arrayIDs['cid'][$i]; //update query for publish and unpublish
            $db->setQuery($query);
            $db->query();
            $query = "UPDATE #__pin_boards set status =" . $publish . ", published=0 WHERE board_category_id =" . $arrayIDs['cid'][$i];
        $db->setQuery($query);
        $db->query();
        $query = "UPDATE #__pin_pins set status =" . $publish . ", published=0 WHERE pin_category_id =" . $arrayIDs['cid'][$i];
        $db->setQuery($query);
        $db->query();
        }
    }

}

?>