<?php
/**
 * @name        Social Pin Board
 * @version	1.0: controlpanel.php$
 * @since       Joomla 1.5&1.6&1.7
 * @package	apptha
 * @subpackage	com_socialpinboard
 * @author      Contus Support
 * @copyright   Copyright (C) 2011 powered by Apptha
 * @license     GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.html.pane');

?>
<style>
    .contus-icon{

margin-right: 15px;
float: left;
margin-bottom: 15px;
border:1px solid #333;
padding:10px;
    }
    .contus-icon:hover{background:#e5e5e5}
    .clear{clear:both;}
    .text{color:#333;margin:10px;line-height: 17px;font-size: 12px;}
    .text a{padding: 7px;font-size: 12px;font-weight: 700;border:1px solid #ccc;text-transform:uppercase }
    .text a:hover{padding: 7px;font-size: 12px;font-weight: 700;color: #000;background: #FAFAFA }
    #toolbar-box{display: none;}
    html, div, span, applet, object, iframe, h1, h2, h3, h4, h5, h6, p, blockquote, pre, a, abbr, acronym, address, big, cite, code, del, dfn, em, font, img, ins, kbd, q, s, samp, small, strike, strong, sub, sup, tt, var, b, u, i, center, dl, dt, dd, ol, ul, li, fieldset, form, label, legend, table, caption, tbody, tfoot, thead, tr, th, td{margin:0; padding:0; border:0; outline:0}

ol, ul{list-style:none}
.floatleft{float:left}
.floatright{float:right}

.clear{clear:both; height:0px; font-size:0px}
.clearfix:after{ clear:both;  display:block;  content:"";  height:0px;  visibility:hidden}
.clearfix{ display:inline-block}

* html .clearfix{ height:1%}
.clearfix{ display:block}
li.clearfix{ display:list-item}

div.cpanel-left {float: left;width:40%;}
.banner{padding-bottom: 10px;}
#cpanel div.icon {background: white;float: left;margin-bottom: 15px;margin-right: 15px;text-align: center;-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;}
#cpanel div.icon a {border: 1px solid #EAEAEA;color: #565656;display: block;float: left;height: 97px;text-decoration: none;vertical-align: middle;width: 108px;-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;}
#cpanel div.icon a:hover {background: #FBFBFB;border-bottom: 1px solid #CCC;border-left: 1px solid #EEE;border-right: 1px solid #CCC;border-top: 1px solid #EEE;}
#cpanel img {margin: 0px auto;padding: 10px 0px;}
#cpanel span {display: block;text-align: center;font-family: 'arial';color:#8D8371;font-size: 12px;}
.heading{ margin-bottom: 10px;  font-family: arial; line-height: 24px;  font-size: 24px;  font-weight: bold;  color: #e25119;    padding: 0;}
.pane-sliders{margin: 0px;padding: 0px;}
</style>
<div class="contus-contropanel">
    <h2 class="heading">Social Pinboard Control Panel</h2>
    </div>
<div class="cpanel-left" >
            <div class="banner"><a href="http://www.apptha.com" target="_blank"><img src="components/com_socialpinboard/assets/apptha-banner.jpg" width="485" height="94" alt=""></a></div>
            <div id="cpanel">
                <div class="icon">
                   <a href="<?php echo JRoute::_("index.php?option=com_socialpinboard&layout=pincategory");?>" title="Categories">
                        <img src="components/com_socialpinboard/assets/category.png" title="Categories" alt="Categories">
                        <span>Categories</span></a>
                </div>

                <div class="icon">
                   <a href="<?php echo JRoute::_("index.php?option=com_socialpinboard&layout=memberdetails");?>" title="Member Details">
                        <img src="components/com_socialpinboard/assets/member-details.png" title="Member Details" alt="Member Details">
                        <span>Member Details</span></a>
                </div>

                <div class="icon">
                    <a href="<?php echo JRoute::_("index.php?option=com_socialpinboard&layout=manageboard");?>" title="Manage Board">
                        <img src="components/com_socialpinboard/assets/manage-boards.png" title="Manage Board" alt="Manage Board"/>
                        <span>Manage Board</span></a>
                </div>

                <div class="icon">
                  <a href="<?php echo JRoute::_("index.php?option=com_socialpinboard&layout=managepins");?>" title="Manage Pins">
                        <img src="components/com_socialpinboard/assets/manege-pins.png" title="Manage Pins" alt="Manage Pins"/>
                        <span>Manage Pins</span></a>
                </div>

                <div class="icon">
                  <a href="<?php echo JRoute::_("index.php?option=com_socialpinboard&layout=sitesettings");?>" title="Site Settings">
                        <img src="components/com_socialpinboard/assets/site-settings-icon.png" title="Site Settings" alt="Site Settings"/>
                        <span>Site Settings</span></a>
                </div>
                <div class="icon">
                  <a href="<?php echo JRoute::_("index.php?option=com_socialpinboard&layout=requestapproval");?>" title="Request Approval">
                        <img src="components/com_socialpinboard/assets/request-approval-icon.png" title="Request Approval" alt="Request Approval"/>
                        <span>Request Approval</span></a>
                </div>
                 <div class="icon">
                  <a href="<?php echo JRoute::_("index.php?option=com_socialpinboard&layout=googlead");?>" title="Google AdSense">
                        <img src="components/com_socialpinboard/assets/google-ad-icon.png" title="Google AdSense" alt="Google AdSense"/>
                        <span>Google AdSense</span></a>
                </div>

            </div>
        </div>

<div style="width:50%;float:right;">
    <?php
    if(version_compare(JVERSION, '3.0.0', 'ge')){
            ?>
    <div class="well well-small"><div class="module-title nav-header">Welcome to Social Pinboard</div><div class="row-striped">
			<div class="row-fluid">
			<div class="span9">

				<strong style=" text-align: justify; display: block; " class="row-title">Social Pinboard is an extension for Joomla CMS. With this Social Pinboard, you can set up a Website with an intuitive design. It allows you to create, share, and manage boards/pins on any topic. It allows you to fetch images from other sites using Pin It Bookmarklet. It lets you to follow users and their boards.
									</strong>


			</div>
			
		</div>
	</div>
</div>
 <?php
   }else if(version_compare(JVERSION, '2.5', 'ge')){
            $pane   = JPane::getInstance('sliders');
//$pane =& JPane::getInstance('tabs', array('startOffset'=>2));
echo $pane->startPane( 'pane' );
echo $pane->startPanel( 'Welcome to Social Pinboard', 'panel1' );?>

    <div class="main-text">

        <div class="text">
            Social Pinboard is an extension for Joomla CMS. With this Social Pinboard, you can set up a Website with an intuitive design. It allows you to create, share, and manage boards/pins on any topic. It allows you to fetch images from other sites using Pin It Bookmarklet. It lets you to follow users and their boards.
</div>
         <div class="text">
     <a href="http://www.apptha.com/forum/viewforum.php?f=96" target="_blank">
     Support</a>
     <a href="http://www.apptha.com/joomla/social-pinboard-script" target="_blank">Documentation</a>
   </div>
    </div>
<?php
echo $pane->endPanel();
echo $pane->endPane();
   }  ?>
</div>



