<?php
/**
 * @version		$Id: mod_banner_index.php 20196 2011-01-09 02:40:25Z ian $
 * @package		Joomla.Site
 * @subpackage	mod_banner_index
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

require_once dirname(__FILE__).'/helper.php';
$items = modSwitcherHelper::getItems($params);
require JModuleHelper::getLayoutPath('mod_switcher', $params->get('layout', 'default'));
?>