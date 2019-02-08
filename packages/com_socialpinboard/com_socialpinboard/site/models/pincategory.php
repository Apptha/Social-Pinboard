<?php

/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component pincategory model
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');
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

    //To get the category from the database and display it
    function getpincategory() {
        global $option, $mainframe;
        $mainframe = JFactory::getApplication();
        $search_filter_category = JRequest::getVar('filter_search_category'); //get the values for the search
        $status_filter_category = JRequest::getVar('filter_category'); //get the status of the category

        $db = $this->getDBO(); //to enable db connection
        $limitstart = '';
        $limit = '';
        if ($search_filter_category == '') {
            $query = "SELECT COUNT(category_id) FROM #__pin_categories"; //query for pagination
        } else {
            $query = "SELECT COUNT(category_id) FROM #__pin_categories WHERE category_name LIKE '%$search_filter_category%' "; //query for pagination    
        }
        $db->setQuery($query);
        $total = $db->loadresult();
        jimport('joomla.html.pagination');
        $pageNav = new JPagination($total, $this->getState('limitstart'), $this->getState('limit'));
        if ($search_filter_category == '') {
            $query = "SELECT * from #__pin_categories ORDER BY category_name LIMIT $pageNav->limitstart,$pageNav->limit"; //query for displaying the categories
        } else {
            $query = "SELECT * from #__pin_categories  WHERE category_name LIKE '%$search_filter_category%'  ORDER BY category_name LIMIT $pageNav->limitstart,$pageNav->limit"; //query for displaying the categories
        }

        $db->setQuery($query);
        $_pin_category = $db->loadObjectList();
        if ($_pin_category === null)
            JError::raiseError(500, 'Error reading db');
        $_pin_category = array('pageNav' => $pageNav, 'limitstart' => $limitstart, 'pin_category' => $_pin_category); //merging the pagination values and results
        return $_pin_category; //return the final result
    }

    //Function To edit the pin categories
    function editpincategory($id) {
        global $mainframe;
        $option = JRequest::getCmd('option');
        $db = & JFactory::getDBO();
        $query = "select * from #__pin_categories where category_id=" . $id;
        $db->setQuery($query);
        $result = $db->loadObject();
        if ($result === null)
            JError::raiseError(500, 'detail with ID: ' . $id . ' not found.');
        else
            return $result;
    }

    //To Add the New categories into the tables
    function getNewcategory() {
        $detailTableRow = & $this->getTable('pincategory');
        $date = & JFactory::getDate();
        $dateTime = $date->toFormat() . "\n";
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

        $db = &JFactory::getDbo();
        $file = & JRequest::getVar('category_image', '', 'files', 'array'); //Get File name, tmp_name
        $categoryName = & JRequest::getVar('category_name', '', 'post', 'string'); //Get category_name
        $query = "SELECT * from #__pin_categories Where category_name = '$categoryName'"; //query for displaying the categories

        $db->setQuery($query);
        $_pin_category = $db->loadObjectList();

        if (empty($_pin_category)) {

            if ($file != '') {
                $userImage = JFile::makeSafe($file['name']);
                //my code
                $image = JFile::makeSafe($file['name']);
                $uploadedfile = $file['tmp_name'];
                $userImageDetails = pathinfo($image);
                $extension = strtolower($userImageDetails['extension']);


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

                //ends
                // this function is to save a category
                $detailTableRow = & $this->getTable('pincategory');
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
                $db = &Jfactory::getDBO();
                $query = "UPDATE #__pin_categories set category_image ='" . $userImageDetails['basename'] . "' WHERE category_id=" . $row;
                $db->setQuery($query);
                $db->query();
                return $row;
            }
        } else {
            $app = & JFactory::getApplication();
            $app->redirect(JRoute::_('index.php?option=com_socialpinboard&layout=pincategory', false), "Category Name Already Exists", $msgType = 'message');
        }
    }

    //
    function deletepincategory($arrayIDs) {
        // function is to delete a particular category
        $db = $this->getDBO();
        $query = "DELETE FROM #__pin_categories WHERE category_id IN (" . implode(',', $arrayIDs) . ")";
        $db->setQuery($query);
        if (!$db->query()) {
            $errorMessage = $this->getDBO()->getErrorMsg();
            JError::raiseError(500, 'Error deleting Image: ' . $errorMessage);
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
            $query = "UPDATE #__pin_categories set status =" . $publish . " WHERE category_id=" . $arrayIDs['cid'][$i]; //update query for publish and unpublish

            $db->setQuery($query);
            $db->query();
        }
    }

}

?>