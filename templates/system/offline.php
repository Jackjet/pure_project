<?php
/**
 * @version		$Id: offline.php 20717 2011-02-15 16:50:33Z infograf768 $
 * @package		Joomla.Site
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
$app = JFactory::getApplication();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<jdoc:include type="head" />
	<script src="<?php echo JURI::base(); ?>media/system/js/jquery.js" type="text/javascript"></script>
	<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/system/css/offline.css" type="text/css" />
	<?php if ($this->direction == 'rtl') : ?>
	<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/system/css/offline_rtl.css" type="text/css" />
	<?php endif; ?>
	<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/system/css/general.css" type="text/css" />
</head>
<body>
	<jdoc:include type="message" />
	<div id="frame" class="outline">
		<h1>
			<?php echo $app->getCfg('sitename'); ?>
		</h1>
		<p>
			<?php echo $app->getCfg('offline_message'); ?>
		</p>
		<form action="<?php echo JRoute::_('index.php', true); ?>" method="post" id="form-login">
			<fieldset class="input">
				<p id="form-login-username">
					<label for="username"><?php echo JText::_('JGLOBAL_USERNAME') ?></label>
					<input name="username" id="username" type="text" class="inputbox" alt="<?php echo JText::_('JGLOBAL_USERNAME') ?>" size="18" />
				</p>
				<p id="form-login-password">
					<label for="passwd"><?php echo JText::_('JGLOBAL_PASSWORD') ?></label>
					<input type="password" name="password" class="inputbox" size="18" alt="<?php echo JText::_('JGLOBAL_PASSWORD') ?>" id="passwd" />
				</p>
				<p id="form-login-checkcode">
					<label for="checkcode"><?php echo JText::_('JGLOBAL_CHECKCODE') ?></label>
					<input name="checkcode" id="checkcode" type="text" class="inputbox checkcode-input" maxlength="4"/>
					<img title="点击刷新验证码" id="codeimg" class="check_code" onclick="changeCode('<?php echo JURI::base(true); ?>',this)" src="index.php?option=com_users&task=displaycaptcha"/>
					<div style="clear:both;"></div>
				</p>
				<p id="form-login-remember">
					<label for="remember"><?php echo JText::_('JGLOBAL_REMEMBER_ME') ?></label>
					<input type="checkbox" name="remember" class="inputbox" value="yes" alt="<?php echo JText::_('JGLOBAL_REMEMBER_ME') ?>" id="remember" />
				</p>
				<input type="submit" name="Submit" class="button" value="<?php echo JText::_('JLOGIN') ?>" />
				<input type="hidden" name="option" value="com_users" />
				<input type="hidden" name="task" value="user.login" />
				<input type="hidden" name="return" value="<?php echo base64_encode(JURI::base()) ?>" />
				<?php echo JHtml::_('form.token'); ?>
			</fieldset>
		</form>
	</div>
	<script type="text/javascript">
		function changeCode(url,ele){
			var src=url+'/index.php?option=com_users&task=displaycaptcha&ran='+(new Date().getTime().toString(36))+'';
			var checkImage=$(ele).parent().find('.check_code').attr('class')==undefined?$(ele).parent().parent().find('.check_code'):$(ele).parent().find('.check_code');
			if($(checkImage).attr('class')!=undefined){
				$(checkImage).attr('src',src);
			}
		}
	</script>
</body>
</html>
