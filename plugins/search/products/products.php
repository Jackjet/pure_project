<?php
/**
 * @version		$Id: products.php 21097 2011-04-07 15:38:03Z dextercowley $
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

require_once JPATH_SITE.'/components/com_product/helpers/route.php';

/**
 * products Search plugin
 *
 * @package		Joomla.Plugin
 * @subpackage	Search.products
 * @since		1.6
 */
class plgSearchProducts extends JPlugin
{
	/**
	 * Constructor
	 *
	 * @access      protected
	 * @param       object  $subject The object to observe
	 * @param       array   $config  An array that holds the plugin configuration
	 * @since       1.5
	 */
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}

	/**
	 * @return array An array of search areas
	 */
	function onContentSearchAreas() {
		static $areas = array(
			'products' => 'PLG_SEARCH_PRODUCTS_PRODUCTS'
			);
			return $areas;
	}

	/**
	 * product Search method
	 *
	 * The sql must return the following fields that are used in a common display
	 * routine: title, addtime, text, browsernav
	 * @param string Target search string
	 * @param string mathcing option, exact|any|all
	 * @param string ordering option, newest|oldest|popular|alpha|category
	 * @param mixed An array if the search it to be restricted to areas, null if search all
	 */
	function onContentSearch($text, $phrase='', $ordering='', $areas=null)
	{
		$db		= JFactory::getDbo();
		$app	= JFactory::getApplication();
		$user	= JFactory::getUser();
		$groups	= implode(',', $user->getAuthorisedViewLevels());

		$searchText = $text;

		if (is_array($areas)) {
			if (!array_intersect($areas, array_keys($this->onContentSearchAreas()))) {
				return array();
			}
		}

		$sContent		= $this->params->get('search_content',		1);
		$sArchived		= $this->params->get('search_archived',		1);
		$limit			= $this->params->def('search_limit',		50);
		$state = array();
		if ($sContent) {
			$state[]=1;
		}
		if ($sArchived) {
			$state[]=2;
		}

		$text = trim($text);
		if ($text == '') {
			return array();
		}
		$section	= JText::_('PLG_SEARCH_PRODUCTS');

		$wheres	= array();
		switch ($phrase)
		{
			case 'exact':
				$text		= $db->Quote('%'.$db->getEscaped($text, true).'%', false);
				$wheres2	= array();
				$wheres2[]	= 'a.title LIKE '.$text;
				$wheres2[]	= 'a.description LIKE '.$text;
				$where		= '(' . implode(') OR (', $wheres2) . ')';
				break;

			case 'all':
			case 'any':
			default:
				$words	= explode(' ', $text);
				$wheres = array();
				foreach ($words as $word)
				{
					$word		= $db->Quote('%'.$db->getEscaped($word, true).'%', false);
					$wheres2	= array();
					$wheres2[]	= 'a.title LIKE '.$word;
					$wheres2[]	= 'a.description LIKE '.$word;
					$wheres[]	= implode(' OR ', $wheres2);
				}
				$where	= '(' . implode(($phrase == 'all' ? ') AND (' : ') OR ('), $wheres) . ')';
				break;
		}

		switch ($ordering)
		{
			case 'oldest':
				$order = 'a.addtime ASC';
				break;

			case 'popular':
				$order = 'a.hits DESC';
				break;

			case 'alpha':
				$order = 'a.title ASC';
				break;

			case 'category':
				$order = 'c.title ASC, a.title ASC';
				break;

			case 'newest':
			default:
				$order = 'a.addtime DESC';
		}
//echo $phrase.':'.$where;
		$return = array();
		if (!empty($state)) {
			$query	= $db->getQuery(true);
			$query->select('a.title AS title, a.description AS text, a.addtime AS created, '
						.'a.id as slug, '
						.'c.id as catslug, '
						.'CONCAT_WS(" / ", '.$db->Quote($section).', c.title) AS section, "1" AS browsernav');
			$query->from('#__product AS a');
			$query->innerJoin('#__categories AS c ON c.id = a.catid');
			$query->where('('.$where.')' . ' AND a.published in ('.implode(',',$state).') AND c.published=1 AND c.access IN ('.$groups.')');
			$query->order($order);

			// Filter by language
			if ($app->isSite() && $app->getLanguageFilter()) {
				$tag = JFactory::getLanguage()->getTag();
				$query->where('a.language in (' . $db->Quote($tag) . ',' . $db->Quote('*') . ')');
				$query->where('c.language in (' . $db->Quote($tag) . ',' . $db->Quote('*') . ')');
			}

			$db->setQuery($query, 0, $limit);
			$rows = $db->loadObjectList();

			$return = array();
			if ($rows) {
				foreach($rows as $key => $row) {
//					echo $row->slug.' --- '.$row->catslug.';<br/>';
					$rows[$key]->href = ProductHelperRoute::getProductRoute($row->slug, $row->catslug);
				}

				foreach($rows AS $key => $product) {
					if (searchHelper::checkNoHTML($product, $searchText, array('url', 'text', 'title'))) {
						$return[] = $product;
					}
				}
			}
		}
		return $return;
	}
}
