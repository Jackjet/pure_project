<?php
/** * Joomla! 1.5 component Qlue 404 * * @version $Id: qlue404.php 2010-11-30 12:52:08 svn $ * @author Aaron Harding - Qlue * @package Joomla * @subpackage Qlue 404 * @license GNU/GPL * * Qlue 404 will detect all the major errors usually found on a website (404, 500) errors. This extension will allow you to custom these custom error pages with ease while still maintaining the proper error codes for seo purposes.  * */
// No Direct Accessdefined( '_JEXEC' ) or die( 'Restricted access' );// Import Joomla installer classjimport("joomla.installer.installer");jimport('joomla.database.table');class com_qlue404InstallerScript {			function install($parent) {						// Get database object		$db =& JFactory::getDBO();					// Get parent xml object		$parent = $parent->getParent();				// Get the temp source location		$source = $parent->getPath('source');				// Find any plugins to install		$plugins = $parent->manifest->plugins;				// Load our extensions table		$table =& JTable::getInstance('Extension');				// Check to see if any plugins have been set		if( is_a($plugins, "JXMLElement") && count($plugins->children())){						// Foreach plugin			foreach($plugins->children() as $plugin){								// Get the plugin name				$name = (string)$plugin->attributes()->plugin;								// Get the plugin group				$group = (string)$plugin->attributes()->group;								// Path to plugin in installation package				$path = $source.DS."plugins".DS.$group;								// Get an instance of JInstaller class				$installer = new JInstaller();								// Install the plugin and recieve the result				$result = $installer->install($path);								// If installed successfully push into feedback array				if($result === true){											// Try and find our plugin					$where = array('type' => 'plugin', 'element' => $name, 'folder' => $group);					$id = $table->find($where);										// Get the order number					$where = array('type = '. $db->Quote('plugin'), 'folder = '. $db->Quote($group));					$order = $table->getNextOrder($where);										// Load our plugin					$table->load((int)$id);					// Save new order and publish					$table->ordering = (int)$order;					$table->enabled = 1;					$table->store();					echo '<p>'. $name .' has been installed successfully</p>';				} else {					echo '<p>Unable to install the plugin: '. $name .'</p>';					return false;				}			}		}				return true;	}		function uninstall($parent) {				// Get instance of Database Object		$db = &JFactory::getDBO();				// Get parent xml object		$parent = $parent->getParent();				// Find any plugins to install		$plugins = $parent->manifest->plugins;				// Load our extensions table		$table =& JTable::getInstance('Extension');				// Check to see if any plugins have been set		if( is_a($plugins, "JXMLElement") && count($plugins->children())) {						// Foreach plugin			foreach($plugins->children() as $plugin){								// Get the plugin name				$name = $plugin->attributes()->plugin;								// Get the plugin group				$group = $plugin->attributes()->group;								// Try and find our plugin				$where = array('type' => 'plugin', 'element' => $name, 'folder' => $group);				$id = $table->find($where);								// If plugin id is set, uninstall it				if($id){										// Get an instance of JInstaller class					$installer = new JInstaller();										// Uninstall the plugin and recieve the result					$result[] = $installer->uninstall('plugin', $id);				}			}		}	}}