<?php /** * Component Qlue 404 * * @version $Id: view.html.php 2010-11-30 12:52:08 svn $ * @author Aaron Harding - Qlue * @package Joomla * @subpackage Qlue 404 * @license GNU/GPL * * Qlue 404 will detect all the major errors usually found on a website (404, 500) errors. This extension will allow you to custom these custom error pages with ease while still maintaining the proper error codes for seo purposes.  * */$document = JFactory::getDocument();$document->addStyleDeclaration('#tq_float_container,#bdshare{display:none;}');defined('_JEXEC') or die('Restricted access'); ?><div style="margin:120px auto 0;width:500px;height:200px;">	<div><a href="<?php JURI::base(); ?>"><img src="<?php echo JURI::base(); ?>/templates/system/images/404.jpg"/></a></div></div>