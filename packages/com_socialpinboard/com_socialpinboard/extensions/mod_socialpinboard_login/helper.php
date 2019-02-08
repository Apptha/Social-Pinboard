<?php

/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Module login
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.user.helper');
jimport('joomla.user.authentication');

class modsocialpinboard_login {

    public static function getFbSetting() {
        global $mainframe;
        $db = JFactory::getDBO();
        $query = "SELECT setting_facebookapi,setting_facebooksecret,setting_show_request "
                . "FROM #__pin_site_settings";

        $db->setQuery($query);
        $result = $db->loadObject();

        return $result;
    }

    public static function getFacebookDetails($user, $user_profile) {
        $db = JFactory::getDBO();
        $mainframe = JFactory::getApplication();
        $mailer = JFactory::getMailer(); //define joomla mailer
        //declare variables
        $facebook = '';
        $facebook_profile_id = $user_profile['id'];
        $facebook_first_name = $user_profile['first_name'];
        $facebook_last_name = $user_profile['last_name'];
        $name = $user_profile['first_name'] . $user_profile['last_name'];
        $userName = $user_profile['first_name'] . $user_profile['last_name'];
        $userName = strtolower($userName);
        $str = $userEmail = $userBlock = '';
        $user_password = '';
        //togenerate Random Password:
        $str .= "012345678923456789ABCDEFGHJKLMNOPQRSTUVWXYZ";
        for ($i = 0; $i < 5; $i++) {
            $user_password .= $str{rand() % strlen($str)};
        }


        if ($user) {
            //check email exist

            $email = $user_profile['email'];
            $query = "SELECT email,block
                      FROM #__users
                      WHERE username='$email'";
            $db->setQuery($query);
            $user_details = $db->loadObject();
            if (isset($user_details->email))
                $userEmail = $user_details->email;
            if (isset($user_details->block))
                $userBlock = $user_details->block;

            if ($userBlock == 1) {
                if (isset($_SERVER['HTTP_COOKIE'])) {
                    $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
                    foreach ($cookies as $cookie) {
                        $parts = explode('=', $cookie);
                        $name = trim($parts[0]);
                        setcookie($name, '', time() - 1000);
                        setcookie($name, '', time() - 1000, '/');
                    }
                }
                $redirect = JRoute::_('index.php?option=com_socialpinboard&view=home', false);
                $app = JFactory::getApplication();
                $app->redirect($redirect, $msg = 'Please Contact Administrator!You have been Blocked!', $msgType = '');
            }
            //check request by invite or normal sign up
            $query = "SELECT setting_show_request
                            FROM #__pin_site_settings";
            $db->setQuery($query);
            $show_request = $db->loadResult();
            //check it is in user settings

            $query = "SELECT COUNT(id) FROM #__pin_user_request WHERE email_id='" . $email . "' AND approval_status='1'";
            $db->setQuery($query);
            $userExist = $db->loadResult();

            if ($show_request == '0') {
                $userExist = 1;
                $query = "SELECT count(id) from #__pin_user_request WHERE email_id='$email'";
                $db->setQuery($query);
                $userInvited = $db->loadResult();

                if ($userInvited == '1') {
                    $query = "UPDATE #__pin_user_request SET approval_status=1 where email_id='$email'";
                    $db->setQuery($query);
                    $db->query();
                } else {


                    //insert the approval into the user
                    $query = "INSERT INTO `#__pin_user_request` (`email_id`,`approval_status`) VALUES ('" . $email . "','1')";
                    $db->setQuery($query);
                    $db->query();
                }
            }
            $user_id = $user_profile['id'];

            if (version_compare(JVERSION, '2.5.0', 'ge')) {
                $version = '2.5';
            } else if (version_compare(JVERSION, '1.7.0', 'ge')) {
                $version = '1.7';
            } elseif (version_compare(JVERSION, '1.6.0', 'ge')) {
                $version = '1.6';
            } else {
                $version = '1.5';
            }

            //For facebook signup process
            if ($userEmail == '' && $userExist == 1) {

                $salt = JUserHelper::genRandomPassword(32);
                $crypt = JUserHelper::getCryptedPassword($user_password, $salt);
                $password = $crypt . ':' . $salt;

                if ($version != '1.5') {
                    $query = "INSERT INTO #__users(name,username,email,password,sendEmail,registerDate,lastvisitDate)  VALUES
                    ('" . $userName . "', '" . $email . "' , '" . $email . "', '" . $password . "',1,now(),now())";
                    $db->setQuery($query);
                    $db->query();
                    $userId = $db->insertid();
                    $query = "INSERT INTO #__user_usergroup_map(user_id,group_id)  VALUES
                    ('" . $userId . "', '2')";
                    $db->setQuery($query);
                    $db->query();
                } else {
                    $query = "INSERT INTO #__users(name,username,email,password,sendEmail,registerDate,lastvisitDate)  VALUES
                    ('" . $userName . "', '" . $email . "' , '" . $email . "', '" . $password . "',1,now(),now())";
                    $db->setQuery($query);
                    $db->query();
                    $userId = $db->insertid();

                    $query = "INSERT INTO #__core_acl_aro(section_value,value,name)  VALUES
                    ('users', '" . $userId . "' , '" . $email . "')";
                    $db->setQuery($query);
                    $db->query();
                    $mapId = $db->insertid();

                    $query = "INSERT INTO #__core_acl_groups_aro_map(group_id,aro_id)  VALUES
                    ('25', '" . $mapId . "')";
                    $db->setQuery($query);
                    $db->query();
                }
                $thumb_image = file_get_contents('http://graph.facebook.com/' . $user_profile['id'] . '/picture');
                $img = file_get_contents('http://graph.facebook.com/' . $user_profile['id'] . '/picture?type=large');

                $original_file = JPATH_SITE . DS . "images" . DS . "socialpinboard" . DS . "avatars" . DS . $name . $userId . '_o.jpg';
                $thumb_file = JPATH_SITE . DS . "images" . DS . "socialpinboard" . DS . "avatars" . DS . $name . $userId . '.jpg';

                file_put_contents($original_file, $img);
                file_put_contents($thumb_file, $thumb_image);
                $session = JFactory::getSession();
                $userid = $session->set('facebooklogin', $userId);
                $session_register = $session->set('social_register', 'allow');
                $app = JFactory::getApplication();
                $query = "SELECT name,username,email,id
                          FROM #__users
                          WHERE username='$email'";
                $db->setQuery($query);
                $result = $db->loadAssoc();
                $user_Id = $result['id'];
                $userEmail = $result['email'];
                $userName = trim($result['username']);
                $user_activation_name = $result['name'];
                $password = $user_password;
                $userImage = $name . $user_Id . '.jpg';
                $facebook = 'facebook';
                //See whether already Facebook users exits or not

                $query = "SELECT count(user_id) from #__pin_user_settings WHERE email='$email'";
                $db->setQuery($query);
                $result = $db->loadResult();
                $count = 1;
                if ($result > 0) {

                    $query = "select count from #__pin_user_settings WHERE email='$email'";
                    $db->setQuery($query);
                    $count = $db->loadResult();
                    $count = $count + 1;

                    $query = "UPDATE #__pin_user_settings SET facebook_profile_id='" . $facebook_profile_id . "',count='" . $count . "'  WHERE email='" . $email . "'";
                    $db->setQuery($query);
                    $db->query();

                    $returnURL = 'index.php?option=com_socialpinboard&view=home';
                } else {

                    $query = "INSERT INTO #__pin_user_settings(facebook_profile_id,user_id,first_name,last_name,email,username,user_image,count,status,created_date) VALUES
                        ('" . $facebook_profile_id . "','" . $user_Id . "','" . $facebook_first_name . "','" . $facebook_last_name . "','" . $email . "','" . $name . "','" . $userImage . "','" . $count . "','1',NOW())";
                    $db->setQuery($query);
                    $db->query();
                    $returnURL = 'index.php?option=com_socialpinboard&view=userfollow';


                    //mail functionality
                    //set mail sender
                    $config = JFactory::getConfig();
                    $sender = $config->get('mailfrom');
                    $site_name = $config->get('sitename');
                    $mailer->setSender($sender);
                    $mailer->setSubject('Account Details');
                    //set recipient
                    $recipient = $userEmail;
                    $mailer->addRecipient($recipient);
                    //set the body
                    $templateparams = $app->getTemplate(true)->params; // get the tempalte parameters
                    $logo = $templateparams->get('logo'); //get the logo

                    if ($logo != null) {
                        $image_source = JURI::base() . '/' . htmlspecialchars($logo);
                    } else {
                        $image_source = JURI::base() . '/templates/socialpinboard/images/logo.png';
                    }
                    $body = ' Username: <a href="mailto:" ' . $email . '" target="_blank">' . $email . '</a><br>
                                 Password: ' . $user_password;
                    $baseurl = JURI::base();
                    $commented_pin = JURI::base() . 'index.php?option=com_socialpinboard&view=people';
                    $commented_user_url = JURI::base() . 'index.php?option=com_socialpinboard&view=boarddisplay&uid=' . $user_Id;
                    $message = file_get_contents(JURI::base() . '/templates/socialpinboard/emailtemplate/facebook_register_details.html');
                    $message = str_replace("{baseurl}", $baseurl, $message);
                    $message = str_replace("{site_name}", $site_name, $message);
                    $message = str_replace("{site_logo}", $image_source, $message);
                    $message = str_replace("{Pin_user_name}", $user_activation_name, $message);
                    $message = str_replace("{commented_user_url}", $commented_user_url, $message);
                    $message = str_replace("{commented_user_name}", $user_activation_name, $message);
                    $message = str_replace("{commented_user_image}", 'https://graph.facebook.com/' . $user_profile['id'] . '/picture', $message);
                    $message = str_replace("{commented_pin_url}", $commented_pin, $message);
                    $message = str_replace("{details}", $body, $message);
                    $mailer->isHTML(true);
                    $mailer->Encoding = 'base64';
                    $mailer->setBody($message);

                    $send = $mailer->Send();
                    modsocialpinboard_login ::facebookLogin($userEmail, $userName, $password, $facebook, $returnURL);
                }
            } elseif ($userEmail == '' && $userExist == 0) {
                if (isset($_SERVER['HTTP_COOKIE'])) {
                    $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
                    foreach ($cookies as $cookie) {
                        $parts = explode('=', $cookie);
                        $name = trim($parts[0]);
                        setcookie($name, '', time() - 1000);
                        setcookie($name, '', time() - 1000, '/');
                    }
                }
                $redirect = JRoute::_('index.php?option=com_socialpinboard&view=people&task=nologin', false);
                $app = JFactory::getApplication();
                $app->redirect($redirect, $msg = 'Your Email is not associated with any account', $msgType = 'message');
            } else {



                $query = "select username,email,id,block from #__users WHERE username='$email'";
                $db->setQuery($query);
                $result = $db->loadAssoc();
                $user_Id = $result['id'];
                $userEmail = $result['email'];
                $userName = trim($result['username']);
                $block = $result['block'];
                $password = $userEmail;
                $userImage = $name . $user_Id . '.jpg';
                $facebook = 'facebook';

                $returnURL = 'index.php?option=com_socialpinboard&view=home';
                if ($block == '0') {

                    //See whether already Facebook users exits or not

                    $query = "select count(user_id) from #__pin_user_settings WHERE email='$email'";
                    $db->setQuery($query);
                    $result = $db->loadResult();
                    $count = 1;

                    if ($result > 0) {

                        $query = "SELECT count from #__pin_user_settings";
                        $db->setQuery($query);
                        $count = $db->loadResult();
                        $count = $count + 1;

                        $query = "UPDATE #__pin_user_settings SET count='" . $count . "',facebook_profile_id='" . $facebook_profile_id . "',user_id='" . $user_Id . "',username='" . $name . "',status='1',created_date='NOW()' WHERE email='" . $email . "'";
                        $db->setQuery($query);
                        $db->query();
                    }


                    if ($version != '1.5') {
                        $query = "select rules from #__assets WHERE name='root.1'";
                        $db->setQuery($query);
                        $result = $db->loadObject();
                        $json_a = json_decode($result->rules, true);
                        $accessLevel = isset($json_a['core.login.site']['1']) ? $json_a['core.login.site']['1'] : '0';
                        if ($accessLevel == '1') {

                            modsocialpinboard_login::facebookLogin($userEmail, $userName, $password, $facebook, $returnURL);
                        } else {

                            $error = JText::_('User Login Restriced');
                            $session = JFactory::getSession();
                            $session->set('fbVal', '1');
                            JFactory::getApplication()->redirect('index.php?option=com_socialpinboard&view=people', $error, 'error');
                            return false;
                        }
                    } else {
                        modsocialpinboard_login ::facebookLogin($userEmail, $userName, $password, $facebook, $returnURL);
                    }
                } else {
                    if (isset($_SERVER['HTTP_COOKIE'])) {
                        $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
                        foreach ($cookies as $cookie) {
                            $parts = explode('=', $cookie);
                            $name = trim($parts[0]);
                            setcookie($name, '', time() - 1000);
                            setcookie($name, '', time() - 1000, '/');
                        }
                    }
                    $redirect = JRoute::_('index.php?option=com_socialpinboard&view=people&task=blocked', false);
                    $app = JFactory::getApplication();
                    $app->redirect($redirect, $msg = 'Account Blocked', $msgType = 'message');
                }
            }
        }
    }

    static function facebookLogin($userEmail, $userName, $password, $facebook, $returnURL) {


        $_SESSION['facebook_login'] = 'facebook_login';
        if (version_compare(JVERSION, '1.7.0', 'ge')) {
            $version = '1.7';
        } elseif (version_compare(JVERSION, '1.6.0', 'ge')) {
            $version = '1.6';
        } else {
            $version = '1.5';
        }

        if ($version != '1.5') {
            $app = JFactory::getApplication();
        } else {
            global $mainframe;
        }

        // Populate the data array:
        $data = array();

        $data['username'] = $userName;
        $data['password'] = $password;

        // Get the log in options.
        $options = array();
        $options['remember'] = JRequest::getBool('remember', false);
        $options['return'] = $returnURL;

        // Get the log in credentials.
        $credentials = array();
        $credentials['username'] = $data['username'];
        $credentials['password'] = $data['password'];
        $credentials['facebook'] = $facebook;



        // Perform the log in.
        if ($version != '1.5') {
            $error = $app->login($credentials, $options);
        } else {
            $error = $mainframe->login($credentials, $options);
        }



        // Check if the log in succeeded.
        if (!JError::isError($error)) {
            if ($version != '1.5') {

                $app->setUserState('users.login.form.data', array());
                $app->redirect(JRoute::_($returnURL, false));
            } else {

                $mainframe->redirect($returnURL);
            }
        } else {
            if ($version != '1.5') {
                $data['remember'] = (int) $options['remember'];
                $app->setUserState('users.login.form.data', $data);


                $app->redirect(JRoute::_('index.php?option=com_users&view=login', false));
            } else {

                $mainframe->redirect($returnURL);
            }
        }
    }

    public static function FBUserExits() {
        $query = "select facebook_profile_id from #__pin_user_settings WHERE username='$email'";
        $db->setQuery($query);
        $result = $db->loadAssoc();
    }

}

class JAuthenticationResponses extends JObject {

    /**
     * Response status (see status codes)
     *
     * @var    string
     * @since  11.1
     */
    public $status = JAuthentication::STATUS_FAILURE;
    /**
     * The type of authentication that was successful
     *
     * @var    string
     * @since  11.1
     */
    public $type = '';
    /**
     *  The error message
     *
     * @var    string
     * @since  11.1
     */
    public $error_message = '';
    /**
     * Any UTF-8 string that the End User wants to use as a username.
     *
     * @var    string
     * @since  11.1
     */
    public $username = '';
    /**
     * Any UTF-8 string that the End User wants to use as a password.
     *
     * @var    string
     * @since  11.1
     */
    public $password = '';
    /**
     * The email address of the End User as specified in section 3.4.1 of [RFC2822]
     *
     * @var    string
     * @since  11.1
     */
    public $email = '';
    /**
     * UTF-8 string free text representation of the End User's full name.
     *
     * @var    string
     * @since  11.1
     *
     */
    public $fullname = '';
    /**
     * The End User's date of birth as YYYY-MM-DD. Any values whose representation uses
     * fewer than the specified number of digits should be zero-padded. The length of this
     * value MUST always be 10. If the End User user does not want to reveal any particular
     * component of this value, it MUST be set to zero.
     *
     * For instance, if a End User wants to specify that his date of birth is in 1980, but
     * not the month or day, the value returned SHALL be "1980-00-00".
     *
     * @var    string
     * @since  11.1
     */
    public $birthdate = '';
    /**
     * The End User's gender, "M" for male, "F" for female.
     *
     * @var  string
     * @since  11.1
     */
    public $gender = '';
    /**
     * UTF-8 string free text that SHOULD conform to the End User's country's postal system.
     *
     * @var postcode string
     * @since  11.1
     */
    public $postcode = '';
    /**
     * The End User's country of residence as specified by ISO3166.
     *
     * @var string
     * @since  11.1
     */
    public $country = '';
    /**
     * End User's preferred language as specified by ISO639.
     *
     * @var    string
     * @since  11.1
     */
    public $language = '';
    /**
     * ASCII string from TimeZone database
     *
     * @var    string
     * @since  11.1
     */
    public $timezone = '';

    /**
     * Constructor
     *
     * @param   string  $name  The type of the response
     *
     * @return  JAuthenticationResponse
     *
     * @since   11.1
     */
    function __construct() {

    }

}

?>
