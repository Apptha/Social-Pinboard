<?php
/**
 * @name        Social Pin Board
 * @version	1.0: sitesettings.php$
 * @since       Joomla 1.5&1.6&1.7
 * @package	apptha
 * @subpackage	com_socialpinboard
 * @author      Contus Support
 * @copyright   Copyright (C) 2011 powered by Apptha
 * @license     GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
$Social_Pinboard = $this->Social_Pinboard;
?>
<script type="text/javascript">
    Joomla.submitbutton = function(task)
    {

        if(task=='apply' || document.formvalidator.isValid(document.id('adminForm'))){

             Joomla.submitform(task, document.getElementById('adminForm'));
        }

    }
</script>
<fieldset class="adminform">
    <legend>Site Settings</legend>
    <table class="admintable">
        <form action="index.php?option=com_socialpinboard&layout=sitesettings" method="POST" name="adminForm" id="adminForm" enctype="multipart/form-data">
            <tr>
                <td class="key hasTip" title="Enter your Facebook App Id">Facebook App ID</td>
                <td>
                    <table><tr>
                            <td width="10"></td>
                            <td>
                                <input <?php if(!version_compare(JVERSION, '3.0.0', 'ge')){ echo 'size="50px;"'; }?> type="text" placeholder="Your facebook application id" name="setting_facebookapi" id="setting_facebookapi" value="<?php if (isset($this->getsitesettings->setting_facebookapi) && $this->getsitesettings->setting_facebookapi!='Your facebook application id') {
    echo $this->getsitesettings->setting_facebookapi;
} ?>" />
                            </td>
                            <td><a href="http://developers.facebook.com/apps" target="_blank" > <img src="<?php echo str_replace('administrator/', '', JURI::base()) ?>media/system/images/notice-info.png" class="hasTip" title="Click here to create your facebook application id" <?php if(version_compare(JVERSION, '3.0.0', 'ge')){ echo 'style="height:20px;"'; } else { echo 'height="16px" weight="16px"'; } ?>/></a></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="key hasTip" title="Enter your Facebook Secret Key">Facebook App Secret</td>
                <td>
                    <table><tr>
                            <td width="10"></td>
                            <td>
                                <input <?php if(!version_compare(JVERSION, '3.0.0', 'ge')){ echo 'size="50px;"'; }?> type="text" placeholder="Your facebook secret key"  name="setting_facebooksecret" id="setting_facebooksecret" value="<?php if (isset($this->getsitesettings->setting_facebooksecret) && $this->getsitesettings->setting_facebooksecret!='Your facebook secret key') {
    echo $this->getsitesettings->setting_facebooksecret;
} ?>" />
                            </td>
                            <td><a href="http://developers.facebook.com/apps" target="_blank"> <img src="<?php echo str_replace('administrator/', '', JURI::base()) ?>media/system/images/notice-info.png"  height="16px" weight="16px" class="hasTip" title="Click here to create your facebook secret key" <?php if(version_compare(JVERSION, '3.0.0', 'ge')){ echo 'style="height:20px;"'; }?>/></a></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="key hasTip" title="Enter your Gmail Client ID">Gmail Client ID</td>
                <td>
                    <table><tr>
                            <td width="10"></td>
                            <td>
                                <input <?php if(!version_compare(JVERSION, '3.0.0', 'ge')){ echo 'size="50px;"'; }?>  type="text" placeholder="Your Gmail Client ID" name="setting_gmailclientid" id="setting_gmailclientid" value="<?php if (isset($this->getsitesettings->setting_gmailclientid) && $this->getsitesettings->setting_gmailclientid!='Your Gmail Client ID') { echo $this->getsitesettings->setting_gmailclientid; } ?>" />
                            </td>
                            <td><a href="https://code.google.com/apis/console/?pli=1" target="_blank"> <img src="<?php echo str_replace('administrator/', '', JURI::base()) ?>media/system/images/notice-info.png"  height="16px" weight="16px" class="hasTip" title="Click here to create your Gmail Client ID" <?php if(version_compare(JVERSION, '3.0.0', 'ge')){ echo 'style="height:20px;"'; }?>/></a></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="key hasTip" title="Enter your Gmail Client Secret Key">Gmail Client Secret Key</td>
                <td>
                    <table><tr>
                            <td width="10"></td>
                            <td>
                                <input <?php if(!version_compare(JVERSION, '3.0.0', 'ge')){ echo 'size="50px;"'; }?> type="text" placeholder="Your Gmail Client Secret Key"  name="setting_gmailclientsecretkey" id="setting_gmailclientsecretkey" value="<?php if (isset($this->getsitesettings->setting_gmailclientsecretkey) && $this->getsitesettings->setting_gmailclientsecretkey!='Your Gmail Client Secret Key') {
    echo $this->getsitesettings->setting_gmailclientsecretkey;
} ?>" />
                            </td>
                            <td><a href="https://code.google.com/apis/console/?pli=1" target="_blank"> <img src="<?php echo str_replace('administrator/', '', JURI::base()) ?>media/system/images/notice-info.png"  height="16px" weight="16px" class="hasTip" title="Click here to create your Gmail Client Secret Key" <?php if(version_compare(JVERSION, '3.0.0', 'ge')){ echo 'style="height:20px;"'; }?>/></a></td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td class="key hasTip" title="Enter your Twitter Consumer Key">Twitter Consumer Key </td>
                <td>
                    <table><tr>
                            <td width="10"></td>
                            <td>
                                <input <?php if(!version_compare(JVERSION, '3.0.0', 'ge')){ echo 'size="50px;"'; }?> type="text" placeholder="Twitter Consumer Key" name="setting_twitterapi" id="setting_twitterapi" value="<?php if (isset($this->getsitesettings->setting_twitterapi) && $this->getsitesettings->setting_twitterapi!='Twitter Consumer Key') {
    echo $this->getsitesettings->setting_twitterapi;
} ?>"/>
                            </td>
                            <td><a href="http://dev.twitter.com/" target="_blank" > <img src="<?php echo str_replace('administrator/', '', JURI::base()) ?>media/system/images/notice-info.png" height="16px" weight="16px" class="hasTip" title="Click here to create your twitter consumer key" <?php if(version_compare(JVERSION, '3.0.0', 'ge')){ echo 'style="height:20px;"'; }?>/></a></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="key hasTip" title="Enter your Twitter Consumer Secret Key">Twitter Consumer Secret</td>
                <td>
                    <table><tr>
                            <td width="10"></td>
                            <td>
                                <input <?php if(!version_compare(JVERSION, '3.0.0', 'ge')){ echo 'size="50px;"'; }?> type="text" placeholder="Twitter Consumer Secret Key" name="setting_twittersecret" id="setting_twittersecret" value="<?php if (isset($this->getsitesettings->setting_twittersecret) && $this->getsitesettings->setting_twittersecret!='Twitter Consumer Secret Key') {
    echo $this->getsitesettings->setting_twittersecret;
} ?>" />
                            </td>
                            <td><a href="http://dev.twitter.com/" target="_blank"> <img src="<?php echo str_replace('administrator/', '', JURI::base()) ?>media/system/images/notice-info.png"  height="16px" weight="16px" class="hasTip" title="Click here to create your twitter consumer secret key" <?php if(version_compare(JVERSION, '3.0.0', 'ge')){ echo 'style="height:20px;"'; }?>/></a></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="key hasTip" title="Enter your Yahoo Consumer Key">Yahoo Consumer Key </td>
                <td>
                    <table><tr>
                            <td width="10"></td>
                            <td>
                                <input <?php if(!version_compare(JVERSION, '3.0.0', 'ge')){ echo 'size="50px;"'; }?> type="text" placeholder="Your Yahoo Consumer key" name="setting_yahooconsumerkey" id="setting_yahooconsumerkey" value="<?php if (isset($this->getsitesettings->setting_yahooconsumerkey) && $this->getsitesettings->setting_yahooconsumerkey!='Your Yahoo Consumer key') {
    echo $this->getsitesettings->setting_yahooconsumerkey;
} ?>"/>
                            </td>
                            <td><a href="https://developer.apps.yahoo.com/dashboard/createKey.html" target="_blank" > <img src="<?php echo str_replace('administrator/', '', JURI::base()) ?>media/system/images/notice-info.png" height="16px" weight="16px" class="hasTip" title="Click here to create your Yahoo Consumer Key" <?php if(version_compare(JVERSION, '3.0.0', 'ge')){ echo 'style="height:20px;"'; }?>/></a></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="key hasTip" title="Enter your Yahoo Consumer Secret Key">Yahoo Consumer Secret Key</td>
                <td>
                    <table><tr>
                            <td width="10"></td>
                            <td>
                                <input <?php if(!version_compare(JVERSION, '3.0.0', 'ge')){ echo 'size="50px;"'; }?> type="text" placeholder="Your Yahoo Consumer Secret key" name="setting_yahooconsumersecretkey" id="setting_yahooconsumersecretkey" value="<?php if (isset($this->getsitesettings->setting_yahooconsumersecretkey) && $this->getsitesettings->setting_yahooconsumersecretkey!='Your Yahoo Consumer Secret key') {
    echo $this->getsitesettings->setting_yahooconsumersecretkey;
} ?>" />
                            </td>
                            <td><a href="https://developer.apps.yahoo.com/dashboard/createKey.html" target="_blank"> <img src="<?php echo str_replace('administrator/', '', JURI::base()) ?>media/system/images/notice-info.png"  height="16px" weight="16px" class="hasTip" title="Click here to create your Yahoo Consumer Secret Key" <?php if(version_compare(JVERSION, '3.0.0', 'ge')){ echo 'style="height:20px;"'; }?>/></a></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="key hasTip" title="Enter your Yahoo OAuth Domain Name">Yahoo OAuth Domain Name </td>
                <td>
                    <table><tr>
                            <td width="10"></td>
                            <td>
                                <input <?php if(!version_compare(JVERSION, '3.0.0', 'ge')){ echo 'size="50px;"'; }?> type="text" placeholder="Your Yahoo Oauth Domain Name" name="setting_yahoooauthdomain" id="setting_yahoooauthdomain" value="<?php if (isset($this->getsitesettings->setting_yahoooauthdomain) && $this->getsitesettings->setting_yahoooauthdomain!='Your Yahoo Oauth Domain Name') {
    echo $this->getsitesettings->setting_yahoooauthdomain;
} ?>"/>
                            </td>
                            <td><a href="https://developer.apps.yahoo.com/dashboard/createKey.html" target="_blank" > <img src="<?php echo str_replace('administrator/', '', JURI::base()) ?>media/system/images/notice-info.png" height="16px" weight="16px" class="hasTip" title="Click here to create your Yahoo OAuth Domain Name" <?php if(version_compare(JVERSION, '3.0.0', 'ge')){ echo 'style="height:20px;"'; }?>/></a></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="key hasTip" title="Enter your Yahoo APP ID">Yahoo APP ID</td>
                <td>
                    <table><tr>
                            <td width="10"></td>
                            <td>
                                <input <?php if(!version_compare(JVERSION, '3.0.0', 'ge')){ echo 'size="50px;"'; }?> type="text" placeholder="Your Yahoo APP ID" name="setting_yahoooappid" id="setting_yahoooappid" value="<?php if (isset($this->getsitesettings->setting_yahoooappid)  && $this->getsitesettings->setting_yahoooappid!='Your Yahoo APP ID') {
    echo $this->getsitesettings->setting_yahoooappid;
} ?>" />
                            </td>
                            <td><a href="https://developer.apps.yahoo.com/dashboard/createKey.html" target="_blank"> <img src="<?php echo str_replace('administrator/', '', JURI::base()) ?>media/system/images/notice-info.png"  height="16px" weight="16px" class="hasTip" title="Click here to create your Yahoo APP ID" <?php if(version_compare(JVERSION, '3.0.0', 'ge')){ echo 'style="height:20px;"'; }?>/></a></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="key hasTip" title="Choose Your Currency">Choose Your Currency:</td>
                <td>
                    <table><tr>
                            <td width="10"></td>
                            <td>
                                <input type="text" id="setting_currency" name="setting_currency" value="<?php if (isset($this->getsitesettings->setting_currency)) {
    echo $this->getsitesettings->setting_currency;
} ?>">


                            </td>

                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td <?php if(version_compare(JVERSION, '3.0.0', 'ge')){ echo 'style="padding-top:10px;padding-bottom:10px;"'; } ?> class="key hasTip" title="Select whether request approval is needed or not">Mandatory Request Approved By Admin </td>

                <?php
                if (isset($this->getsitesettings->setting_request_approval)) {
                    if ($this->getsitesettings->setting_request_approval == "1") {
                        $y = "checked";
                        $n = "";
                    } else {
                        $y = "";
                        $n = "checked";
                    }
                }
                ?>
                <td>
                    <table><tr>
                            <td width="10"></td>
                            <td>
                                <input <?php if(!version_compare(JVERSION, '3.0.0', 'ge')){ echo 'size="50px;"'; }?> type="radio" name="setting_request_approval" id="setting_request_approval" value="1" <?php if (!empty($y)) {
                    echo $y;
                } ?> checked="checked" style="margin-top:0px;" />Yes</td><td><input type="radio" name="setting_request_approval" id="setting_request_approval" value="0" <?php if (!empty($n)) {
                    echo $n;
                } ?> style="margin-top:0px;" />No </td>
                        </tr>
                    </table>
            </tr>
            <tr>
                <td <?php if(version_compare(JVERSION, '3.0.0', 'ge')){ echo 'style="padding-top:10px;padding-bottom:10px;"'; } ?> class="key hasTip" title="Select whether signup is needed or not">Register</td>

                <?php
                if (isset($this->getsitesettings->setting_user_registration)) {
                    if ($this->getsitesettings->setting_user_registration == "1") {
                        $y = "checked";
                        $n = "";
                    } else {
                        $y = "";
                        $n = "checked";
                    }
                }
                ?>
                <td>
                    <table><tr>
                            <td width="10"></td>
                            <td>
                                <input <?php if(!version_compare(JVERSION, '3.0.0', 'ge')){ echo 'size="50px;"'; }?> type="radio" name="setting_user_registration" id="setting_user_registration" value="1" <?php if (!empty($y)) {
                    echo $y;
                } ?> checked="checked" style="margin-top:0px;" />Yes</td><td><input type="radio" name="setting_user_registration" id="setting_user_registration" value="0" <?php if (!empty($n)) {
                    echo $n;
                } ?> style="margin-top:0px;" />No </td>
                        </tr>
                    </table>
            </tr>
            </td>
            </tr>



            <!-- show request approval in front end -->
            <td <?php if(!version_compare(JVERSION, '3.0.0', 'ge')){ echo 'style="padding-top:10px;padding-bottom:10px;"'; } ?> class="key hasTip" title="Show Request Approval in front end">Show Request Approval</td>

            <?php
            if (isset($this->getsitesettings->setting_show_request)) {
                if ($this->getsitesettings->setting_show_request == "1") {
                    $y = "checked";
                    $n = "";
                } else {
                    $y = "";
                    $n = "checked";
                }
            }
            ?>
            <td>
                <table><tr>
                        <td width="10"></td>
                        <td>
                            <input type="radio" name="setting_show_request" id="setting_show_request" value="1" <?php if (!empty($y)) {
                echo $y;
            } ?> checked="checked" style="margin-top:0px;" />Yes</td><td><input type="radio" name="setting_show_request" id="setting_show_request" value="0" <?php if (!empty($n)) {
                echo $n;
            } ?> style="margin-top:0px;" />No </td>
                    </tr>
                </table>
                </tr>
            </td>
            </tr>
            <input type="hidden" name="option" value="<?php echo JRequest::getVar('option'); ?>"/>
            <input type="hidden" name="id" value="<?php echo $this->getsitesettings->id; ?>"/>
            <input type="hidden" name="created_date" id="created_date" value="<?php echo date("Y-m-d H:i:s"); ?>"/>
            <input type="hidden" name="task" value=""/>
        </form>
    </table>
</fieldset>