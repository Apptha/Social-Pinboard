<?php

/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component Router file
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');

function socialpinboardBuildRoute(&$query) {
    $segments = array();

    if (isset($query['view'])) {
        $segments[] = $query['view'];
        unset($query['view']);
    }
    if (isset($query['tmpl'])) {
        $segments[] = $query['tmpl'];
        unset($query['tmpl']);
    }
    if (isset($query['category'])) {
        $segments[] = $query['category'];
        unset($query['category']);
    }
    if (isset($query['uid'])) {
        $segments[] = 'uid';
        $segments[] = $query['uid'];
        unset($query['uid']);
    }
    if (isset($query['pin_id'])) {
        $segments[] = $query['pin_id'];
        unset($query['pin_id']);
    }
    if (isset($query['pinid'])) {
        $segments[] = $query['pinid'];
        unset($query['pinid']);
    }
    if (isset($query['pinId'])) {
        $segments[] = $query['pinId'];
        unset($query['pinId']);
    }
    if (isset($query['bId'])) {
        $segments[] = $query['bId'];
        unset($query['bId']);
    }
    if (isset($query['bidd'])) {
        $segments[] = $query['bidd'];
        unset($query['bidd']);
    }
    if (isset($query['media'])) {
        $segments[] = 'media';
        $segments[] = $query['media'];
        unset($query['media']);
    }
    if (isset($query['url'])) {
        $segments[] = $query['url'];
        unset($query['url']);
    }
    if (isset($query['socialregister'])) {
        $segments[] = $query['socialregister'];
        unset($query['socialregister']);
    }
    if (isset($query['is_video'])) {
        $segments[] = $query['is_video'];
        unset($query['is_video']);
    }
    if (isset($query['returnURL'])) {
        $segments[] = 'returnurl';
        $segments[] = $query['returnURL'];
        unset($query['returnURL']);
    }
    if (isset($query['email'])) {
        $segments[] = 'email';
        $segments[] = $query['email'];
        unset($query['email']);
    }
    if (isset($query['activation'])) {
        $segments[] = $query['activation'];
        unset($query['activation']);
    }
    if (isset($query['signup'])) {
        $segments[] = 'signup';
        $segments[] = $query['signup'];
        unset($query['signup']);
    }
    if (isset($query['task'])) {
        $segments[] = $query['task'];
        unset($query['task']);
    }
    if (isset($query['pId'])) {
        $segments[] = 'pId';
        $segments[] = $query['pId'];
        unset($query['pId']);
    }
    if (isset($query['q'])) {
        $segments[] = $query['q'];
        unset($query['q']);
    }
    if (isset($query['search'])) {
        $segments[] = $query['search'];
        unset($query['search']);
    }
    if (isset($query['follower'])) {
        $segments[] = $query['follower'];
        unset($query['follower']);
    }
    if (isset($query['page'])) {
        $segments[] = $query['page'];
        unset($query['page']);
    }
    if (isset($query['reset'])) {
        $segments[] = $query['reset'];
        unset($query['reset']);
    }

    unset($query['view']);
    return $segments;
}

/**
 * @param	array	A named array
 * @param	array
 *
 * Formats:
 *
 * index.php?/banners/task/bid/Itemid
 *
 * index.php?/banners/bid/Itemid
 */
function socialpinboardParseRoute($segments) {


    $vars = array();
    // view is always the first element of the array
    $count = count($segments);
    if ($count) {
        switch ($segments[0]) {
            case 'home':
                $vars['view'] = 'home';

                if (isset($segments[1]) && $segments[1] == 'signup') {
                    $vars['signup'] = $segments[2];
                    unset($segments[2]);
                } elseif (isset($segments[3])) {
                    $vars['category'] = $segments[1];
                    $vars['tmpl'] = $segments[2];
                    $vars['page'] = $segments[3];
                } elseif (isset($segments[2]) && is_numeric($segments[2])) {
                    $vars['tmpl'] = $segments[1];
                    $vars['page'] = $segments[2];
                } elseif (isset($segments[1])) {
                    $vars['category'] = $segments[1];
                }
                break;
            case 'video':
                $vars['view'] = 'video';
                if (isset($segments[1]))
                    $vars['page'] = $segments[1];
                break;
            case 'categories':
                $vars['view'] = 'categories';
                if (isset($segments[1]))
                    $vars['page'] = $segments[1];
                break;
            case 'gift':
                $vars['view'] = 'gift';
                if (isset($segments[1]))
                    $vars['page'] = $segments[1];
                break;
            case 'popular':
                $vars['view'] = 'popular';
                if (isset($segments[1]))
                    $vars['page'] = $segments[1];
                break;
            case 'likes':
                $vars['view'] = 'likes';
                if (isset($segments[1]) && $segments[1] == 'uid') {
                    $segments[1] = $segments[2];
                    unset($segments[2]);
                    $segments = array_values($segments);
                    $vars['uid'] = $segments[1];
                }
                break;
            case 'pindisplay':
                $vars['view'] = 'pindisplay';
                if (isset($segments[1]) && $segments[1] == 'uid') {
                    $segments[1] = $segments[2];
                    unset($segments[2]);
                    $segments = array_values($segments);
                    $vars['uid'] = $segments[1];
                }
                break;
            case 'pinedit':
                $vars['view'] = 'pinedit';
                if (isset($segments[1]))
                    $vars['pinId'] = $segments[1];
                break;
            case 'pinitdisplay':
                $vars['view'] = 'pinitdisplay';
                if (isset($segments[1]))
                    $vars['tmpl'] = $segments[1];
                if (isset($segments[2]))
                    $vars['media'] = $segments[2];
                if (isset($segments[3]))
                    $vars['url'] = $segments[3];
                if (isset($segments[4]))
                    $vars['title'] = $segments[4];
                if (isset($segments[5]))
                    $vars['is_video'] = $segments[5];
                break;
            case 'boarddisplay':
                $vars['view'] = 'boarddisplay';
                if (isset($segments[1]) && $segments[1] == 'uid') {
                    $segments[1] = $segments[2];
                    unset($segments[2]);
                    $segments = array_values($segments);
                    $vars['uid'] = $segments[1];
                } elseif (isset($segments[1]) && $segments[1] == 'pId') {
                    $segments[1] = $segments[2];
                    unset($segments[2]);
                    $segments = array_values($segments);
                    $vars['pId'] = $segments[1];
                } elseif (isset($segments[1]))
                    $vars['page'] = $segments[1];
                if (isset($segments[2]))
                    $vars['page'] = $segments[2];
                break;
            case 'boardedit':
                $vars['view'] = 'boardedit';
                if (isset($segments[1]))
                    $vars['bidd'] = $segments[1];
                break;
            case 'boardpage':
                $vars['view'] = 'boardpage';
                if (isset($segments[1]))
                    $vars['bId'] = $segments[1];
                break;

            case 'pin':
                $vars['view'] = 'pin';
                $vars['pinid'] = $segments[1];
                if (isset($segments[2]))
                    $vars['page'] = $segments[2];
                break;
            case 'people':
                $vars['view'] = 'people';
                $media = '';
                if (isset($segments[1]) && $segments[1] == 'returnurl') {
                    $vars['returnURL'] = $segments[2];
                    unset($segments[2]);
                } elseif (isset($segments[1]) && $segments[1] == 'email') {
                    $vars['email'] = $segments[2];
                    $vars['activation'] = $segments[3];
                    unset($segments[2]);
                    unset($segments[3]);
                } elseif (isset($segments[1]) && $segments[1] == 'media') {
                    for ($i = 2; $i <= count($segments) - 1; $i++) {
                        if ($segments[$i] != 'returnurl') {
                            $media .= $segments[$i];
                            if (strstr($media, 'http') && $i == 2) {
                                $media .= '//';
                            } else {
                                $media .= '/';
                            }
                        } else {
                            $i++;

                            break;
                        }
                    }
                    $vars['media'] = $media;
                    $vars['returnURL'] = $segments[$i];
                    unset($segments[$i]);
                } elseif (isset($segments[1]))
                    $vars['task'] = $segments[1];
                break;

            case 'login_twitter':
                $vars['view'] = 'login_twitter';
                break;
            case 'inviterequest':
                $vars['view'] = 'inviterequest';
                break;

            case 'resetpassword':
                $vars['view'] = 'resetpassword';
                if (isset($segments[1]))
                    $vars['task'] = $segments[1];
                break;
            case 'invitefriends':
                $vars['view'] = 'invitefriends';
                if (isset($segments[1]))
                    $vars['task'] = $segments[1];
                break;
            case 'search':
                $vars['view'] = 'search';
                if (isset($segments[1]))
                    $vars['q'] = $segments[1];
                if (isset($segments[2]))
                    $vars['search'] = $segments[2];
                break;
            case 'follow':
                $vars['view'] = 'follow';
                if (isset($segments[2]) && isset($segments[3])) {

                    $vars['uid'] = $segments[2];
                    $vars['follower'] = $segments[3];

                    unset($segments[2]);
                    unset($segments[3]);
                } else if (isset($segments[1])) {
                    $vars['follower'] = $segments[1];
                    unset($segments[1]);
                }
                break;
                case 'addplus':
                $vars['view'] = 'addplus';
                    break;
            case 'profile':
                $vars['view'] = 'profile';
                break;
            case 'userfollow':
                $vars['view'] = 'userfollow';
                break;
            case 'userlogin':
                $vars['view'] = 'userlogin';
                if (isset($segments[1]) && $segments[1] == 'email') {
                    $vars['email'] = $segments[2];
                    $vars['reset'] = $segments[3];
                    unset($segments[2]);
                    unset($segments[3]);
                }
                break;
            case 'socialregister':
                $vars['view'] = 'socialregister';
                break;
            case 'register':
                $vars['view'] = 'register';
                break;
            case 'pinit':
                $vars['view'] = 'pinit';
                break;
            case 'gettwitterdata':
                $vars['view'] = 'gettwitterdata';
                break;
            case 'requestmail':
                $vars['view'] = 'requestmail';
                break;
            case 'pinitpage':
                $vars['view'] = 'pinitpage';
                if (isset($segments[1]))
                    $vars['tmpl'] = $segments[1];
                if (isset($segments[2]))
                    $vars['pin_id'] = $segments[2];
                break;
            case 'emailshare':
                $vars['view'] = 'emailshare';
                if (isset($segments[1]))
                    $vars['tmpl'] = $segments[1];

                break;
        }
    }

    return $vars;
}

