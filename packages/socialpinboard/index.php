<?php

defined('_JEXEC') or die;
if(!defined('DS')){
    define('DS',DIRECTORY_SEPARATOR);
}

require (JPATH_ROOT.DS.'components' .DS.'com_socialpinboard'. DS . 'layouts/socialpinboard.php');


JHtml::_('behavior.framework', true);
//create instance for the class
$thumb = new thumb();
$fthumb = $thumb->fthumb();

//for show request approval
$showRequest= $thumb->showRequest();

if(count($fthumb)!=0){
 if($fthumb[0]->link_type=='youtube' || $fthumb[0]->link_type=='vimeo')
    {     
    $srcPath = $fthumb[0]->pin_image;
    }
    else{
        $srcPath = JURI::base()."images/socialpinboard/pin_original/" . $fthumb[0]->pin_image;
    }
    
}
$pinId = JRequest::getVar('pinid');
$app= JFactory::getApplication();
$doc= JFactory::getDocument();
$templateparams	= $app->getTemplate(true)->params;
$config=JFactory::getConfig();
$site_name = $config->get('sitename');
$logo= $this->params->get('logo');

?>


	<?php echo '<!DOCTYPE html>'; ?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>" >
    <head>
         <script>
    var loading_next_pins="<?php echo JTEXT::_('COM_SOCAILPINBOARD_HEADER_LOADING_NEXT_PINS'); ?>";
    </script>
        <?php if(count($fthumb)!=0){ ?>
                <link rel="image_src" href="<?php echo $srcPath; ?>"/>
    <link rel="canonical" href="<?php echo JURI::base() .JRoute::_('index.php?option=com_socialpinboard&view=pin&pinid='.$pinId);?>"/>
         <meta property="fb:app_id" content="<?php echo $showRequest;?>"/>
    <meta property="og:site_name" content="<?php echo $site_name; ?>"/>
    <meta property="og:type" content="website"/>
    <meta property="og:url" content="<?php echo JURI::base() .JRoute::_('index.php?option=com_socialpinboard&view=pin&pinid='.$pinId);?>"/>
    <meta property="og:title" content="<?php echo $fthumb[0]->pin_description; ?>"/>
    <meta property="og:description" content="<?php echo $fthumb[0]->pin_description; ?>"/>
    <meta property="og:image" content="<?php echo $srcPath; ?>"/>
         <?php } ?>
            <jdoc:include type="head" />
            <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link href='http://fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css'/>
            
                <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/socialpinboard/css/media-queries.css" type="text/css" />
            <!--[if IE]>
                <link rel="stylesheet" type="text/css" href="<?php echo $this->baseurl ?>/templates/socialpinboard/css/ie.css" />
            <!--<![endif]-->
         <?php
            $headerstuff=$this->getHeadData();
            reset($headerstuff['scripts']);
            foreach($headerstuff['scripts'] as $key=>$value){ 

            if (strstr($key,"/media/system/js/mootools-core.js") || strstr($key,"/media/system/js/mootools-more.js") )
            {
            unset($headerstuff['scripts'][$key]);
            }        }
            $this->setHeadData($headerstuff);
        ?>   
        <?php
            $files = JHtml::_('stylesheet','templates/socialpinboard/css/template.css',null,false,true);
            if ($files):
            if (!is_array($files)):
            $files = array($files);
            endif;
            foreach($files as $file):
        ?>
            <link rel="stylesheet" href="<?php echo $file;?>" type="text/css" />
            <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/socialpinboard/css/reset.css" type="text/css" />
        <?php
            endforeach;
            endif;
        
            $document = JFactory::getDocument();
            $document->addScript('https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js');
            $document->addScript( 'templates/socialpinboard/js/socialpinboard.js' );
            $document->addScript( 'templates/socialpinboard/js/scrolldown.js' );

?>

        <script type="text/javascript">var scr = jQuery.noConflict(); </script>
    </head>

    <?php
    $user = JFactory::getUser();
    if($user->id){
        $style = '.banner_box {display: none; height: 0;}';
$document->addStyleDeclaration($style);
        }else{
            $style = '#wrapper {margin: 105px auto !important;}';
$document->addStyleDeclaration($style);
        }
    ?>

    <?php 
flush();
?>

    

	<body id="CategoriesBarPage">
            <?php  $view = JRequest::getVar('view');
            if($view != 'people'  &&  $view != 'inviterequest'  && $view != 'userfollow'){
//                if($view != 'people' && $view != 'inviterequest') {
            ?>
        <div id="header_container" class="clearfix">
            <div id="Header" class="">
                <div class="logo_search">
                    <div id="Search">
                            <jdoc:include type="modules" name="socialpinboard_search" />
                    </div>
                    <div class="logoheader">
                        <h1 id="logo"> <a href="<?php echo JURI::base(); ?>" >
                            <?php if ($logo != null ): ?>
                                <img src="<?php echo $this->baseurl ?>/<?php echo htmlspecialchars($logo); ?>" alt="<?php echo htmlspecialchars($templateparams->get('sitetitle'));?>" />
                            <?php else: ?>
                            <img src="<?php echo JURI::base(); ?>/templates/socialpinboard/images/logo.png" alt="" />
                            <?php endif; ?>
                             </a>
                        </h1>
                    </div>
                </div>
                <div class="logo_search_mobile">
                    <div class="logoheader">
                        <h1 id="mobile_logo"> <a href="<?php echo JURI::base(); ?>" >
                            <?php if ($logo != null ): ?>
                                <img src="<?php echo $this->baseurl ?>/<?php echo htmlspecialchars($logo); ?>" alt="<?php echo htmlspecialchars($templateparams->get('sitetitle'));?>" />
                            <?php else: ?>
                            <img src="<?php echo JURI::base(); ?>/templates/socialpinboard/images/logo.png" alt="" />
                            <?php endif; ?>
                             </a>
                        </h1>
                    </div>
                    <div id="mobile_Search">
                            <jdoc:include type="modules" name="socialpinboard_search" />
                    </div>
                </div>
                <div id="header_top_right">
                    <jdoc:include type="modules" name="socialpinboard_menu" />
                </div>
                <div class="clear"></div>
            </div>
        </div>
 



        <div id="CategoriesBar_content" class="clearfix">
            <jdoc:include type="modules" name="socialpinboard_header" />
        </div>
        <?php } ?>
<!--        <div id="content_banner">
            <jdoc:include type="modules" name="socialpinboard_banner" />
        </div>-->
        <div id="content_color">
            
        </div>
           <div id="wrapper" >
                <div id="ColumnContainer" >
                   <div id="login_error_msg">
                       <jdoc:include type="message" />
                   </div>
                    <?php $pageoption = JRequest::getVar( 'option');
                        if($pageoption !='com_socialpinboard'){ ?>
                            <div class="template_wrapper">
                                <jdoc:include type="component" />
                            </div>
                       <?php }else{ ?>
                            <jdoc:include type="component" />
                        <?php } ?>
                </div><!-- #ColumnContainer -->  
           </div><!-- #wrapper -->
           
</body>
</html>
