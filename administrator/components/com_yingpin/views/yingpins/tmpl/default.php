<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');

$user		= JFactory::getUser();
$userId		= $user->get('id');
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$canOrder	= $user->authoriseInCustom('core.edit.state', 'com_newsfeeds.category');
$saveOrder	= $listOrder == 'a.ordering';

?>
<form action='<?php echo JRoute::_('index.php?option=com_yingpin'); ?>' method='post' name='adminForm' >
	<fieldset id='filter-bar'>
		<div class='filter-search fltlft'>
			<label class='filter-search-lbl' for='filter_search'><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
			<input type='text' name='filter_search' id='filter_search' value='<?php echo $this->escape($this->state->get('filter.search')); ?>' title='<?php echo JText::_('COM_CONTACT_SEARCH_IN_NAME'); ?>' />
			<button type='submit'><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button type='button' onclick='document.id("filter_search").value="";this.form.submit();'><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
		</div>
		<div class='filter-select fltrt'>

			<select name='filter_published' class='inputbox' onchange='this.form.submit()'>
				<option value=''><?php echo JText::_('JOPTION_SELECT_PUBLISHED');?></option>
				<?php echo JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.published'), true);?>
			</select>

			<select name='filter_category_id' class='inputbox' onchange='this.form.submit()'>
				<option value=''><?php echo JText::_('JOPTION_SELECT_CATEGORY');?></option>
				<?php echo JHtml::_('select.options', JHtml::_('category.options', 'com_yingpin'), 'value', 'text', $this->state->get('filter.category_id'));?>
			</select>

           
		</div>
	</fieldset>
	<div class='clr'> </div>
	<table class='adminlist'>
		<thead>
			<tr>
				<th width='1%'>
					<input type='checkbox' name='checkall-toggle' value='' title='<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>' onclick='Joomla.checkAll(this)' />
				</th>
				<th>
					<?php echo JHtml::_('grid.sort', 'COM_YINGPIN_YINGPIN_HEADING_JOB', 'a.job', $listDirn, $listOrder); ?>
				</th>
				<th>
					<?php echo JHtml::_('grid.sort', 'COM_YINGPIN_YINGPIN_HEADING_NAME', 'a.name', $listDirn, $listOrder); ?>
				</th>
				<th>
					<?php echo JHtml::_('grid.sort', 'COM_YINGPIN_YINGPIN_HEADING_SEX', 'a.sex', $listDirn, $listOrder); ?>
				</th>
				<th>
					<?php echo JHtml::_('grid.sort', 'COM_YINGPIN_YINGPIN_HEADING_TEL', 'a.tel', $listDirn, $listOrder); ?>
				</th>
				<th>
					<?php echo JHtml::_('grid.sort', 'COM_YINGPIN_YINGPIN_HEADING_TIME', 'a.time', $listDirn, $listOrder); ?>
				</th>
				<th>
					<?php echo JHtml::_('grid.sort', 'COM_YINGPIN_YINGPIN_HEADING_PUBLISHED', 'a.published', $listDirn, $listOrder); ?>
				</th>
				<th width='10%'>
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ORDERING', 'a.ordering', $listDirn, $listOrder); ?>
					<?php if ($canOrder && $saveOrder) :?>
						<?php echo JHtml::_('grid.order',  $this->items, 'filesave.png', 'yingpins.saveorder'); ?>
					<?php endif; ?>
				</th>
				<th width='1%'>
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
				</th>
			</tr>
		</thead>

		<tfoot>
			<tr>
				<td colspan='7'>
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>

		<tbody>
		<?php
		$n = count($this->items);
		foreach ($this->items as $i => $item) :
			$ordering	= $listOrder == 'a.ordering';
			$canCreate	= $user->authoriseInCustom('core.create',		'com_yingpin.category.'.$item->catid);
			$canEdit	= $user->authoriseInCustom('core.edit',			'com_yingpin.category.'.$item->catid);
			$canCheckin	= $user->authoriseInCustom('core.manage',		'com_checkin') || $item->checked_out == $userId || $item->checked_out == 0;
			$canEditOwn	= $user->authoriseInCustom('core.edit.own',		'com_yingpin.category.'.$item->catid) && $item->created_by == $userId;
			$canChange	= $user->authoriseInCustom('core.edit.state',	'com_yingpin.category.'.$item->catid) && $canCheckin;

			$item->cat_link = JRoute::_('index.php?option=com_categories&extension=com_yingpin&task=edit&type=other&id='.$item->catid);
			$item->link=JRoute::_('index.php?option=com_yingpin&task=yingpin.edit&id='.(int) $item->id);
			?>
			<tr class='row<?php echo $i % 2; ?>'>
				<td class='center'>
					<?php echo JHtml::_('grid.id', $i, $item->id); ?>
				</td>
				<td align='center'>
					<a href="<?php echo $item->link; ?>"><?php echo $item->job; ?></a>
				<a class="top-this" 
						href="index.php?option=<?php echo JRequest::getVar("option") ?>&task=<?php echo substr(JRequest::getVar("option"),4)?>s.topthis&cid=<?php echo $item->id?>">置顶</a>
				</td>
				<td align='center'>
					<?php echo $item->name; ?>
				</td>
				<td align='center'>
					<?php echo $item->sex == 1 ? '先生' : '小姐'; ?>
				</td>
				<td align='center'>
					<?php echo $item->tel; ?>
				</td>
				<td align='center'>
					<?php echo $item->time; ?>
				</td>
				<td class='center'>
					<?php echo JHtml::_('grid.boolean', $i, $item->published, 'yingpin.published', 'yingpin.unpublished'); ?>
				</td>
				<td class='order'>
					<?php if ($canChange) : ?>
						<?php if ($saveOrder) :?>
							<?php if ($listDirn == 'asc') : ?>
								<span><?php echo $this->pagination->orderUpIcon($i, ($item->catid == @$this->items[$i-1]->catid),'yingpins.orderup', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
								<span><?php echo $this->pagination->orderDownIcon($i, $n, ($item->catid == @$this->items[$i+1]->catid), 'yingpins.orderdown', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
							<?php elseif ($listDirn == 'desc') : ?>
								<span><?php echo $this->pagination->orderUpIcon($i, ($item->catid == @$this->items[$i-1]->catid),'yingpins.orderdown', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
								<span><?php echo $this->pagination->orderDownIcon($i, $n, ($item->catid == @$this->items[$i+1]->catid), 'yingpins.orderup', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
							<?php endif; ?>
						<?php endif; ?>
						<?php $disabled = $saveOrder ?  '' : 'disabled="disabled"'; ?>
						<input type='text' name='order[]' size='5' value='<?php echo $item->ordering;?>' <?php echo $disabled ?> class='text-area-order' />
					<?php else : ?>
						<?php echo $item->ordering; ?>
					<?php endif; ?>
				</td>
				<td align='center'>
					<?php echo $item->id; ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<div>
		<input type='hidden' name='task' value='' />
		<input type='hidden' name='boxchecked' value='0' />
		<input type='hidden' name='filter_order' value='<?php echo $listOrder; ?>' />
		<input type='hidden' name='filter_order_Dir' value='<?php echo $listDirn; ?>' />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
