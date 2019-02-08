<?php

/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component pinedit model
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');

class socialpinboardModelpinedit extends SocialpinboardModel {

    function editPins() {
        $db = $this->getDBO();
        $user = JFactory::getUser();
        $pinid = JRequest::getVar('pinId');
        $userId = $user->get('id');
        $query = "SELECT `pin_id`, `pin_board_id`, `pin_user_id`, `pin_category_id`, `pin_description`, `pin_type_id`, `pin_url`, `pin_image`, `pin_repin_id`, `pin_real_pin_id`, `pin_repin_count`, `pin_likes_count`, `pin_comments_count`, `pin_views`, `link_type`, `status`, `published`, `gift`, `price`, `created_date`
                  FROM #__pin_pins
                  WHERE status=1
                  AND pin_id=" . $pinid . "
                  AND `pin_user_id`=$userId";
        $db->setQuery($query);
        $pinEdit = $db->loadObjectList();
        return $pinEdit;
    }

    function editPinboards() {
        $db = $this->getDBO();
        $user = JFactory::getUser();
        $userId = $user->get('id');
        $query = "select board_name,board_id,cuser_id from #__pin_boards where (user_id=" . $userId . " OR cuser_id =" . $userId . " OR FIND_IN_SET( " . $userId . ", cuser_id ) >0) and status=1";
        $db->setQuery($query);
        $editPinboards = $db->loadObjectList();
        return $editPinboards;
    }

    function updatePins() {
        $db = $this->getDBO();
        $user = JFactory::getUser();
        $userId = $user->get('id');
        $btnEditpin = JRequest::getVar('SavePin');
        $editpindesc = addslashes(JRequest::getVar('description_pin_edit'));
        $editpinlink = JRequest::getVar('pinlink');
        if ($editpinlink != '') {
            if (preg_match("#https?://#", $editpinlink) === 0) {
                $editpinlink = 'http://' . $editpinlink;
            }
        }
$update='';
        // check video url is youtube
		if(strpos($editpinlink,'youtube') > 0)
		{
			$imgstr = explode("v=", $editpinlink);
			$imgval = explode("&", $imgstr[1]);
			$img = "http://img.youtube.com/vi/" . $imgval[0] . "/1.jpg";
                        $update='`pin_image`="' . $img . '",`link_type`="youtube",';
		}
		else if(strpos($editpinlink,'youtu.be') > 0)
		{
			$imgstr = explode("/", $editpinlink);
			$img = "http://img.youtube.com/vi/" . $imgstr[3] . "/1.jpg";
                        $editpinlink="http://www.youtube.com/watch?v=".$imgstr[3];
                        $update='`pin_image`="' . $img . '",`link_type`="youtube",';
		}

		// check video url is youtube
		else if(strpos($editpinlink,'vimeo') > 0)
		{
		 $split=explode("/",$editpinlink);
                 if( ini_get('allow_url_fopen') ) {
			$doc = new DOMDocument();
			$doc->load('http://vimeo.com/api/v2/video/'.$split[3].'.xml');
			$videotags = $doc->getElementsByTagName('video');
			foreach ($videotags as $videotag)
			{
				$imgnode = $videotag->getElementsByTagName('thumbnail_medium');
				$img = $imgnode->item(0)->nodeValue;
			}
                        $update='`pin_image`="' . $img . '",`link_type`="vimeo",';
                }else{
                        $url="http://vimeo.com/api/v2/video/" . $split[3] . ".xml";
                        $curl = curl_init($url);
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                        $result = curl_exec($curl);
                        curl_close($curl);
                        $xml = simplexml_load_string($result);
                        $img = $xml->video->thumbnail_medium;
                        $update='`pin_image`="' . $img . '",`link_type`="vimeo",';
                }
                }


        $editpinboard = JRequest::getVar('boardname');
        $pid = JRequest::getVar('pinId');
        $value = (explode(" ", $editpindesc));

        $count = count($value);
        $query = "SELECT setting_currency from #__pin_site_settings";
        $db->setQuery($query);
        $currency = $db->loadResult();

        for ($i = 0; $i <= $count - 1; $i++) {
            if (preg_match("/^[" . $currency . "]|[0-9]+[.]+$/", $value[$i])) {
                $price = $value[$i];
                $price = (explode($currency, $price));
                    $price=$price[1];
                if ($price != '' && $price) {
                    $query = "SELECT setting_currency from #__pin_site_settings";
                    $db->setQuery($query);
                    $currency = $db->loadResult();

                    if (preg_match("/[" . $currency . "]/", $value[$i])) {
                        $query = "SELECT setting_currency from #__pin_site_settings";
                        $db->setQuery($query);
                        $currency = $db->loadResult();
                        $is_price = explode($currency, $value[$i]);
                        if (is_numeric($is_price[1])) {
                            $gift = '1';
                        }
                    }
                } else {
                    $gift = '0';
                }
            }
        }

        if (isset($btnEditpin)) {
            $query = 'UPDATE `#__pin_pins`
             SET '.$update.' `pin_user_id`="' . $userId . '",`pin_description` ="' . $editpindesc . '",`pin_url`="' . $editpinlink . '",`pin_board_id`="' . $editpinboard . '" ,gift="' . $gift . '", price="' . $price . '"
             WHERE `pin_id`=' . $pid;

            $db->setQuery($query);
            $db->query();
            $msg = "Pin settings has been successfully saved";
            $app = JFactory::getApplication();
            $app->redirect(JRoute::_('index.php?option=com_socialpinboard&view=pin&pinid=' . $pid), $msg, '');
        }
    }

    function deletePin() {

        $db = $this->getDBO();
        $user = JFactory::getUser();
        $userId = $user->get('id');
        $pinid = JRequest::getVar('pinId');
        $btnDelpin = JRequest::getVar('delete_pin');
        $editpinboard = JRequest::getVar('boardname');
        $app = JFactory::getApplication();
        if (isset($btnDelpin)) {

            $query = 'UPDATE `#__pin_pins` SET `status`=0,`published`=-2 WHERE `pin_id`=' . $pinid;
            $db->setQuery($query);
            $db->query();
            $app->redirect(JRoute::_('index.php?option=com_socialpinboard&view=boarddisplay'));
        }
    }

}

?>
