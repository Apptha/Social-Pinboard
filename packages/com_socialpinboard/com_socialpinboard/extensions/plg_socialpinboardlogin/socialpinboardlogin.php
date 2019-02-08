<?php
/**
 * @plugin SocialPinBoard
 * @copyright Copyright (C) 2010 Apptha - All rights reserved.
 * @Website : http://dev.apptha.com
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL 
 **/

defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

class plgAuthenticationSocialpinboardLogin extends JPlugin {

	function onUserAuthenticate($credentials, $options, &$response) {
		jimport('joomla.user.helper');
		$response->type = 'AwoEmailLogin';
		if (empty($credentials['password'])) {
			$response->status = JAuthentication::STATUS_FAILURE;
			$response->error_message = JText::_('JGLOBAL_AUTH_EMPTY_PASS_NOT_ALLOWED');
			return false;
		}


                // get user details
              $db	= JFactory::getDbo();
              $query = 'SELECT id,password,email,name,username'
               . ' FROM #__users'
               . ' WHERE email=' . $db->Quote($credentials['username']) .'or username='. $db->Quote($credentials['username']);
               $db->setQuery( $query );
               $resultVal = $db->loadObject();

               $parts = explode( ':', $resultVal->password );
               $cryptPass = $parts[0];
               $salt = @$parts[1];
               $testcrypt = JUserHelper::getCryptedPassword($credentials['password'], $salt);
		if ($resultVal){
			$crypt=$resultVal->email;
			if (($credentials['password'] == $credentials['username']) && $credentials['facebook'] == 'facebook') {
				$user = JUser::getInstance($resultVal->id);
				$response->username = $user->username;
				$response->email = $user->email;
				$response->fullname = $user->name;
				$response->language = JFactory::getApplication()->isAdmin() ? $user->getParam('admin_language') : $user->getParam('language');
				$response->status = JAuthentication::STATUS_SUCCESS;
				$response->error_message = '';
			}elseif ($cryptPass == $testcrypt){
                                $query = "select count(user_id) from #__pin_user_settings WHERE email='$resultVal->email'";
                                $db->setQuery($query);
                                $resultUser = $db->loadResult();
                                $count = 1;
                                 if ($resultUser > 0){
                                    $query = "select count from #__pin_user_settings WHERE email='$resultVal->email'";
                                    $db->setQuery($query);
                                    $count = $db->loadResult();
                                    $count = $count + 1;
                                    $query = "UPDATE #__pin_user_settings SET count='" . $count . "'  WHERE email='" . $resultVal->email . "'";
                                    $db->setQuery($query);
                                    $db->query();
                                    $user = JUser::getInstance($resultVal->id);
                                    $response->username = $user->username;
                                    $response->email = $user->email;
                                    $response->fullname = $user->name;
                                    $response->language = JFactory::getApplication()->isAdmin() ? $user->getParam('admin_language') : $user->getParam('language');
                                    $response->status = JAuthentication::STATUS_SUCCESS;
                                    $response->error_message = '';
                                } else {
                                    $query = "INSERT INTO #__pin_user_settings(facebook_profile_id,user_id,email,first_name,username,count,status,created_date) VALUES
                                        ('0','" . $resultVal->id . "','" . $resultVal->email . "','" . $resultVal->name . "','" . $resultVal->username . "','1','1',NOW())";
                                    $db->setQuery($query);
                                    $db->query();
                                    $query = "select * from #__pin_boards ORDER BY board_id ASC LIMIT 5";
                                    $db->setQuery($query);
                                    $boardVal = $db->loadObjectList();
                                    
                                    $sql = array();
                                    foreach( $boardVal as $row ) {
                                        $sql[] = '('.$resultVal->id.',"'.$this->phpSlashes($row->board_name).'","'.$this->phpSlashes($row->board_description).'","'.$row->board_category_id.'","'.$row->board_access.'","1",now())';
                                    }
                                    $query='INSERT INTO #__pin_boards (user_id,board_name,board_description,board_category_id,board_access,status,created_date) VALUES '.implode(',', $sql);
                                    $db->setQuery($query);
                                    $db->query();
                                    $user = JUser::getInstance($resultVal->id);
                                    $response->username = $user->username;
                                    $response->email = $user->email;
                                    $response->fullname = $user->name;
                                    $response->language = JFactory::getApplication()->isAdmin() ? $user->getParam('admin_language') : $user->getParam('language');
                                    $response->status = JAuthentication::STATUS_SUCCESS;
                                    $response->error_message = '';
                                }
                                   
                        }
		} 
	}

        function phpSlashes($string,$type='add'){
        if ($type == 'add')
        {
            if (get_magic_quotes_gpc())
            {
                return $string;
            }
            else
            {
                if (function_exists('addslashes'))
                {
                    return addslashes($string);
                }
                else
                {
                    return mysql_real_escape_string($string);
                }
            }
        }
        else if ($type == 'strip')
        {
            return stripslashes($string);
        }
        else
        {
            die('error in PHP_slashes (mixed,add | strip)');
        }
    }

}