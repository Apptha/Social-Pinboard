<?php
defined('_JEXEC') or die;

$color = $this->params->get('templatecolor');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<jdoc:include type="head" />
	


<!--[if lte IE 6]>
	<link href="<?php echo $this->baseurl ?>/templates/beez5/css/ieonly.css" rel="stylesheet" type="text/css" />
<![endif]-->
</head>
<body class="contentpane">
	<div id="all">
	<div id="main">
		<jdoc:include type="message" />
		<jdoc:include type="component" />
		</div>
	</div>
</body>
</html>
