<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));

?>

<form 
	action="<?php echo 'index.php?option=com_redshop&view=states'; ?>" 
	class="admin" 
	method="post" 
	name="adminForm" 
	id="adminForm"
>
	<div class="filterTool">
		<?php
		echo JLayoutHelper::render(
			'joomla.searchtools.default',
			array(
				'view' => $this,
				'options' => array(
					'searchField' => 'search',
					'filtersHidden' => false,
					'searchFieldSelector' => '#filter_search',
					'countryIdFieldSelector' => '#filter_country_id',
					'limitFieldSelector' => '#list_users_limit',
					'activeOrder' => $listOrder,
					'activeDirection' => $listDirn,
				)
			)
		);
		?>
	</div>

	<?php if (empty($this->items)) : ?>
		<div class="alert alert-no-items">
			<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
		</div>
	<?php else : ?>
	<table class="adminlist table table-striped">
		<thead>
		<tr>
			<th width="5"><?php echo JText::_('COM_REDSHOP_NUM'); ?></th>
			<th width="10">
				<?php echo JHtml::_('redshopgrid.checkall'); ?>
			</th>
			<th><?php echo JHTML::_('grid.sort', JText::_('COM_REDSHOP_STATE_NAME'), 's.state_name', $listDirn, $listOrder);?></th>
			<th><?php echo JHTML::_('grid.sort', JText::_('COM_REDSHOP_COUNTRY_NAME'), 'c.country_name', $listDirn, $listOrder);?></th>
			<th><?php echo JHTML::_('grid.sort', JText::_('COM_REDSHOP_STATE_3_CODE'), 's.state_3_code', $listDirn, $listOrder);?></th>
			<th><?php echo JHTML::_('grid.sort', JText::_('COM_REDSHOP_STATE_2_CODE'), 's.state_2_code', $listDirn, $listOrder);?></th>
			<th><?php echo JHTML::_('grid.sort', JText::_('COM_REDSHOP_ID'), 's.state_id', $listDirn, $listOrder); ?></th>
		</tr>
		</thead>
		<?php

		$k = 0;
		for ($i = 0, $n = count($this->items); $i < $n; $i++)
		{
			$row = $this->items[$i];
			$row->id = $row->id;

			$link = JRoute::_('index.php?option=com_redshop&view=state&task=edit&id=' . $row->id);

			?>
			<tr class="<?php echo "row$k"; ?>">
				<td><?php echo $this->pagination->getRowOffset($i); ?></td>
				<td><?php echo JHTML::_('grid.id', $i, $row->id); ?></td>
				<td>
					<a href="<?php echo $link; ?>"
					   title="<?php echo JText::_('COM_REDSHOP_EDIT_state'); ?>"><?php echo $row->state_name ?></a></td>

				<td align="center" width="10%"><?php echo $row->country_name; ?></td>
				<td align="center" width="10%"><?php echo $row->state_3_code; ?></td>
				<td align="center" width="10%"><?php echo $row->state_2_code; ?></td>

				<td align="center" width="10%"><?php echo $row->id;?></td>

			</tr>
			<?php
			$k = 1 - $k;

		}
		?>
		<tfoot>
			<td colspan="14">
				<?php if (version_compare(JVERSION, '3.0', '>=')): ?>
					<div class="redShopLimitBox">
						<?php echo $this->pagination->getLimitBox(); ?>
					</div>
				<?php endif; ?>
				<?php echo $this->pagination->getListFooter(); ?>
			</td>
		</tfoot>
	</table>
	<?php endif; ?>

	<input type="hidden" name="view" value="states">
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<?php echo JHtml::_('form.token'); ?>
</form>
