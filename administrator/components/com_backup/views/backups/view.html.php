
<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * Backups View
 */
class BackupViewBackups extends JView
{
	protected $items;
	protected $pagination;
	protected $state;
	/**
	 * Backups view display method
	 * @return void
	 */
	function display($tpl = null) 
	{
		// Get data from the model
		$this->items 		= $this->get('Items');
		$this->pagination 	= $this->get('Pagination');
		$this->state		= $this->get('State');
		// Check for errors.
		if (count($errors =$this->get('Errors'))) 
		{
			JError::raiseError(500, implode('\n', $errors));
			return false;
		}
		
		// Preprocess the list of items to find ordering divisions.
		// TODO: Complete the ordering stuff with nested sets
		foreach ($this->items as &$item) {
			$item->order_up = true;
			$item->order_dn = true;
		}
 
		// Set the toolbar
		$this->addToolBar();
		// Display the template
		parent::display($tpl);
		// Set the document
		$this->setDocument();
	}
 
	/**
	 * Setting the toolbar
	 */
	protected function addToolBar() 
	{
		$canDo = BackupHelper::getActions();
		JToolBarHelper::title(JText::_('COM_BACKUP_MANAGER_BACKUPS'), 'backup');
		if ($canDo->get('core.create')) 
		{
			JToolBarHelper::addNew('backup.add', 'JTOOLBAR_NEW');
		}
		if ($canDo->get('core.edit')) 
		{
			JToolBarHelper::divider();
			JToolBarHelper::custom('backups.topthis','topthis','','JTOOLBAR_TOPTHIS');
			JToolBarHelper::divider();
			JToolBarHelper::editList('backup.edit', 'JTOOLBAR_EDIT');
//			JToolBarHelper::divider();
//			JToolBarHelper::publish('backup.published', 'JTOOLBAR_PUBLISH', true);
//			JToolBarHelper::unpublish('backup.unpublished', 'JTOOLBAR_UNPUBLISH', true);
		}

		if ($canDo->get('core.delete')) 
		{
			JToolBarHelper::deleteList('', 'backups.delete', 'JTOOLBAR_DELETE');
		}
		if ($canDo->get('core.admin')) 
		{
			JToolBarHelper::divider();
			JToolBarHelper::preferences('com_backup');
		}
	}
	/**
	 * Method to set up the document properties
	 *
	 * @return void
	 */
	protected function setDocument() 
	{
		$document = JFactory::getDocument();
		$document->setTitle(JText::_('COM_BACKUP_ADMINISTRATION'));
	}
}
