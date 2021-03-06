<?php
// No direct access to this file
defined('_JEXEC') or die;
 
/**
 * Integral component helper.
 */
abstract class IntegralHelper
{
	/**
	 * Configure the Linkbar.
	 */
	public static function addSubmenu($submenu) 
	{
		JSubMenuHelper::addEntry(JText::_('COM_INTEGRAL_SUBMENU_MESSAGES'), 'index.php?option=com_integral', $submenu == 'messages');
		JSubMenuHelper::addEntry(JText::_('COM_INTEGRAL_SUBMENU_CATEGORIES'), 'index.php?option=com_categories&view=categories&extension=com_integral', $submenu == 'categories');
		// set some global property
		$document = JFactory::getDocument();
		$document->addStyleDeclaration('.icon-48-integral {background-image: url(../media/com_integral/images/tux-48x48.png);}');
		if ($submenu == 'categories') 
		{
			$document->setTitle(JText::_('COM_INTEGRAL_ADMINISTRATION_CATEGORIES'));
		}
	}
	/**
	 * Get the actions
	 */
	public static function getActions($messageId = 0)
	{
		$user	= JFactory::getUser();
		$result	= new JObject;
 
		if (empty($messageId)) {
			$assetName = 'com_integral';
		}
		else {
			$assetName = 'com_integral.message.'.(int) $messageId;
		}
 
		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.delete', 'core.expexcel'
		);
 
		foreach ($actions as $action) {
			$result->set($action,	$user->authoriseInCustom($action, $assetName));
		}
 
		return $result;
	}
}

