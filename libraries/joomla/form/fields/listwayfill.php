<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');

/**
 * 2012-03-02 wengebin Add
 * 
 * Form Field class for the Joomla Platform.
 * Supports a generic list of options.
 *
 * @package     Joomla.Platform
 * @subpackage  Form
 * @since       11.1
 */
class JFormFieldListwayfill extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $type = 'Listwayfill';

	/**
	 * Method to get the field input markup for a generic list.
	 * Use the multiple attribue to enable multiselect.
	 *
	 * @return  string  The field input markup.
	 * @since   11.1
	 */
	protected function getInput()
	{
		$html = array();
		
		$html[] = '<input type="hidden" id="listwayfill_setid" name="'.$this->name.'" value="'.($this->value ? $this->value : $this->element['default']).'" />';
		$html[] = '<div id="listwayfill_container">';
		
		$options = $this->getOptions();
		foreach ($options as $op) {
			$html[] = '<a href="javascript:;" title="'.$op->title.'" class="listwayfill_item'.($op->class ? ' '.$op->class : '').($op->value == ($this->value ? $this->value : $this->element['default']) ? ' active_item' : '').'" id="'.$op->id.'" val="'.$op->value.'" params="'.$op->params.'"><span style="background-image:url('.$op->src.');"></span></a>';
		}
		
		$html[] = '<div class="clr"></div>';
		$html[] = '</div>';
		$html[] = '<script type="text/javascript">'.
					'$("#listwayfill_container .listwayfill_item").click(function(){' .
						'$("#listwayfill_container .listwayfill_item").removeClass("active_item");'.
						'$(this).addClass("active_item");'.
						'$("#listwayfill_setid").val($(this).attr("val"));'.
						'var get_params = eval("("+$(this).attr("params")+")");'.
						'for (var param in get_params) {'.
							'if($("#jform_params_"+param)[0] != undefined) $("#jform_params_"+param).val(get_params[param]);'.
						'}'.
					'});'.
					'</script>';
		
		return implode($html);
	}
	
	/**
	 * Method to get the field options for radio buttons.
	 *
	 * @return  array  The field option objects.
	 * 
	 * @since   11.1
	 */
	protected function getOptions()
	{
		// Initialize variables.
		$options = array();

		foreach ($this->element->children() as $option) {

			// Only add <option /> elements.
			if ($option->getName() != 'option') {
				continue;
			}
			
			$tmp = new stdClass;
			$tmp->id = (string) $option['id'];
			$tmp->title = (string) $option['title'];
			$tmp->value = (string) $option['value'];
			$tmp->params = (string) $option['params'];
			$tmp->src = (string) $option['src'];
			$tmp->class = (string) $option['class'];

			// Add the option object to the result set.
			$options[] = $tmp;
		}

		reset($options);

		return $options;
	}
}
