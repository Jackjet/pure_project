<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.controller');

/**
 * Product Component Controller
 *
 * @package		Joomla.Site
 * @subpackage	com_product
 * @since 1.5
 */
class ProductController extends JController
{
	/**
	 * Method to display a view.
	 *
	 * @param	boolean			If true, the view output will be cached
	 * @param	array			An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return	JController		This object to support chaining.
	 * @since	1.5
	 */
	public function display($cachable = false, $urlparams = false)
	{

		// Initialise variables.
		$cachable	= true;	// Huh? Why not just put that in the constructor?
		$user		= JFactory::getUser();

		// Set the default view name and format from the Request.
		// Note we are using w_id to avoid collisions with the router and the return page.
		// Frontend is a bit messier than the backend.
		$id		= JRequest::getInt('w_id');
		$vName	= JRequest::getCmd('view', 'products');
		JRequest::setVar('view', $vName);

		if ($user->get('id') ||($_SERVER['REQUEST_METHOD'] == 'POST' && $vName = 'products')) {
			$cachable = false;
		}

		$safeurlparams = array(
			'id'				=> 'INT',
			'limit'				=> 'INT',
			'limitstart'		=> 'INT',
			'filter_order'		=> 'CMD',
			'filter_order_Dir'	=> 'CMD',
			'lang'				=> 'CMD'
		);
		return parent::display($cachable,$safeurlparams);
	}
}
