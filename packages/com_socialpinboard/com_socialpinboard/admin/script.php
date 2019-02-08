<?php
/**
 * @name        Social Pin Board
 * @version	1.0: com_subinstall.php$
 * @since       Joomla 1.5&1.6&1.7
 * @package	apptha
 * @subpackage	com_socialpinboard
 * @author      Contus Support
 * @copyright   Copyright (C) 2011 powered by Apptha
 * @license     GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Include the actual subinstaller class
jimport('joomla.filesystem.folder');
jimport('joomla.installer.installer');
jimport( 'joomla.environment.uri' );

/**
 * API entry point. Called from main installer.
 */
class com_socialpinboardInstallerScript
{
    

     function preflight($type, $parent){

}
    function install($parent)
		{
   
        $mainPath = JPATH_ROOT . '/images/socialpinboard/';
            if(!is_dir($mainPath))//Exist or not
            {
                mkdir($mainPath,0755,true);//create a directory
            }
             $mainPath = JPATH_ROOT . '/images/socialpinboard/pin_original';
            if(!is_dir($mainPath))//Exist or not
            {
                mkdir($mainPath,0755,true);//create a directory
            }
             $mainPath = JPATH_ROOT . '/images/socialpinboard/pin_medium';
            if(!is_dir($mainPath))//Exist or not
            {
                mkdir($mainPath,0755,true);//create a directory
            }
            $mainPath = JPATH_ROOT . '/images/socialpinboard/pin_thumb';
            if(!is_dir($mainPath))//Exist or not
            {
                mkdir($mainPath,0755,true);//create a directory
            }
         $profileImagePath = JPATH_ROOT . '/images/socialpinboard/avatars/';
            if(!is_dir($profileImagePath))//Exist or not
            {
                mkdir($profileImagePath,0755,true);//create a directory
            }
  $result = '';
  $db = JFactory::getDBO();
            $query = ' SELECT * FROM #__pin_pins LIMIT 1';
    $db->setQuery($query);
    $result = $db->loadResult();
  if (empty($result)) {

      $db->setQuery("INSERT INTO `#__users` ( `name`, `username`, `email`, `password`, `block`, `sendEmail`, `registerDate`, `lastvisitDate`, `activation`, `params`, `lastResetTime`, `resetCount`) VALUES ('Tester', 'Tester', 'sampl@gmail.com', '7ee83c0ffb80acc8fe026a7a0554798f:wOiOTp7dYOIpdO82itfjRH2LCR4GKAPO', '0', '1', 'NOW()', 'NOW()', '', '', '0000-00-00 00:00:00', '0')");
    $db->query();
    $userId = $db->insertid();


    $db->setQuery("INSERT INTO `#__pin_user_activation` (`email`, `activationcode`, `created date`) VALUES ('sample@gmail.com', '', 'NOW()');");
    $db->query();
    $db->setQuery("INSERT INTO `#__pin_boards` (`user_id`, `board_name`, `board_description`, `board_category_id`, `board_access`, `cuser_id`, `status`, `created_date`, `published`, `updated_date`) VALUES ( '$userId', 'Sample', '', '1', '1', '', '1', '', '0', CURRENT_TIMESTAMP);");
    $db->query();
    $db->setQuery("INSERT INTO `#__pin_user_settings` (`user_id`, `facebook_profile_id`, `twitter_id`, `first_name`, `last_name`, `email`, `username`, `about`, `location`, `website`, `user_image`, `count`, `status`, `published`, `created_date`, `updated_date`) VALUES (' $userId', '', '', 'Tester', 'Tester', 'Sample@gmail.com', 'Tester', '', 'chennai', '', '', '', '1', '0', '', 'NOW()')");
    $db->query();

      $db->setQuery("INSERT INTO `#__pin_pins` (`pin_board_id`, `pin_user_id`, `pin_category_id`, `pin_description`, `pin_type_id`, `pin_url`, `pin_image`, `pin_repin_id`, `pin_real_pin_id`, `pin_repin_count`, `pin_likes_count`, `pin_comments_count`, `pin_views`, `link_type`, `status`, `rgbcolor`, `published`, `gift`, `price`, `created_date`, `updated_date`) VALUES
                ('1', '$userId', '1', 'Sample', '1', '', '1.jpg', '0', '0', '0', '0', '0', '0', '', '1', '', '0', '', '', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
            ('1', '$userId', '1', 'Sample', '1', '', '2.jpg', '0', '0', '0', '0', '0', '0', '', '1', '', '0', '', '', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
            ('1', '$userId', '1', 'Sample', '1', '', '3.jpg', '0', '0', '0', '0', '0', '0', '', '1', '', '0', '', '', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
            ('1', '$userId', '1', 'Sample', '1', '', '4.jpg', '0', '0', '0', '0', '0', '0', '', '1', '', '0', '', '', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
            ('1', '$userId', '1', 'Sample', '1', '', '5.jpg', '0', '0', '0', '0', '0', '0', '', '1', '', '0', '', '', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
            ('1', '$userId', '1', 'Sample', '1', '', '6.jpg', '0', '0', '0', '0', '0', '0', '', '1', '', '0', '', '', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
            ('1', '$userId', '1', 'Sample', '1', '', '7.jpg', '0', '0', '0', '0', '0', '0', '', '1', '', '0', '', '', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
            ('1', '$userId', '1', 'Sample', '1', '', '8.jpg', '0', '0', '0', '0', '0', '0', '', '1', '', '0', '', '', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
            ('1', '$userId', '1', 'Sample', '1', '', '9.jpg', '0', '0', '0', '0', '0', '0', '', '1', '', '0', '', '', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
            ('1', '$userId', '1', 'Sample', '1', '', '10.jpg', '0', '0', '0', '0', '0', '0', '', '1', '', '0', '', '', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
            ('1', '$userId', '1', 'Sample', '1', '', '11.jpg', '0', '0', '0', '0', '0', '0', '', '1', '', '0', '', '', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
            ('1', '$userId', '1', 'Sample', '1', '', '12.jpg', '0', '0', '0', '0', '0', '0', '', '1', '', '0', '', '', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
            ('1', '$userId', '1', 'Sample', '1', '', '13.jpg', '0', '0', '0', '0', '0', '0', '', '1', '', '0', '', '', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
            ('1', '$userId', '1', 'Sample', '1', '', '14.jpg', '0', '0', '0', '0', '0', '0', '', '1', '', '0', '', '', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
            ('1', '$userId', '1', 'Sample', '1', '', '15.jpg', '0', '0', '0', '0', '0', '0', '', '1', '', '0', '', '', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
            ('1', '$userId', '1', 'Sample', '1', '', '16.jpg', '0', '0', '0', '0', '0', '0', '', '1', '', '0', '', '', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
            ('1', '$userId', '1', 'Sample', '1', '', '17.jpg', '0', '0', '0', '0', '0', '0', '', '1', '', '0', '', '', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
            ('1', '$userId', '1', 'Sample', '1', '', '18.jpg', '0', '0', '0', '0', '0', '0', '', '1', '', '0', '', '', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
            ('1', '$userId', '1', 'Sample', '1', '', '259519997246917732_Lk9ZVE5W_b28.jpeg', '0', '0', '0', '0', '0', '0', '', '1', '', '0', '', '', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
            ('1', '$userId', '1', 'Sample', '1', '', 'ml443g18_hol06pom_snowman_xl82.jpeg', '0', '0', '0', '0', '0', '0', '', '1', '', '0', '', '', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)");
    $db->query();

    $srcDir_original = JPATH_SITE . "/components/com_socialpinboard/images/pin_original/";
$srcDir_medium = JPATH_SITE . "/components/com_socialpinboard/images/pin_medium/";
$srcDir_thumb = JPATH_SITE . "/components/com_socialpinboard/images/pin_thumb/";

$destDir_med = JPATH_SITE . "/images/socialpinboard/pin_medium/";
$destDir_thumb = JPATH_SITE . "/images/socialpinboard/pin_thumb/";
$destDir_original = JPATH_SITE . "/images/socialpinboard/pin_original/";

$files = JFolder::files($srcDir_original);
foreach ($files as $file) {
    @copy($srcDir_original . $file, $destDir_original . $file);
    @copy($srcDir_thumb . $file, $destDir_thumb . $file);
    @copy($srcDir_medium . $file, $destDir_med . $file);
}

  }


}

/**
 * API entry point. Called from main un installer.
 */
function postflight( $type, $parent ) {

                 

}
function uninstall() {
   
}
}