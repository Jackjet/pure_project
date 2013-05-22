<?php
/**
 * @version		$Id: view.html.php 20196 2011-01-09 02:40:25Z ian $
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * HTML View class for the hrs component
 *
 * @package		Joomla.Site
 * @subpackage	com_hr
 * @since		1.5
 */
class HrViewHr extends JView
{
	protected $state;
	protected $item;
	protected $pre_item;
	protected $next_item;
	
	function display($tpl = null)
	{
		// Get some data from the models
		$this->state		= $this->get('State');
		$this->item			= $this->get('Item');
		
		$this->_prepareDocument();
		
//		$this->pre_item		= $this->get('PreOne');
//		$this->next_item	= $this->get('NextOne');
//		$this->start		= $this->get('Start');
//		$this->start		= floor($this->start/$this->params->get('limit'))*$this->params->get('limit');
		
		parent::display($tpl);
	}
	
	/**
	 * Prepares the document
	 */
	protected function _prepareDocument()
	{
		$app			= JFactory::getApplication();
		$this->params	= $app->getParams('com_hr');
		
		$parentTitle = $this->params->get('page_title', '');
		$this->document->setTitle($this->item->title.'-'.$parentTitle .'-'. $app->getCfg('sitename'));
		
		if ($this->item->metadesc)
		{
			$this->document->setDescription($this->item->metadesc);
		}
		elseif (!$this->item->metadesc && $this->params->get('menu-meta_description'))
		{
			$this->document->setDescription($this->params->get('menu-meta_description'));
		}

		if ($this->item->metakey)
		{
			$this->document->setMetadata('keywords', $this->item->metakey);
		}
		elseif (!$this->item->metakey && $this->params->get('menu-meta_keywords'))
		{
			$this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
		}

		if ($this->item->metadata->author) {
			$this->document->setMetadata('author', $this->item->metadata->author);
		}
		
		if ($this->item->metadata->robots) {
			$this->document->setMetadata('robots', $this->item->metadata->robots);
		}
	}
}
