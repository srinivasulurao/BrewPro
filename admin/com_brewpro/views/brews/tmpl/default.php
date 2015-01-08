<?php
defined('_JEXEC') or die;
?>
<form method="post" action="" id='adminForm' name='adminForm'>
<div class='span2'>
<?php

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');


$model = $this->getModel();
echo $this->sidebar = JHtmlSidebar::render();
error_reporting(~E_NOTICE);
//$model->debug($_REQUEST);
$session = JFactory::getSession();
$listOrder=$session->get('brew_filter_order');
$listDirn=$session->get('brew_filter_order_Dir');
$saveOrder	= $listOrder == 'b.ordering';
if ($saveOrder or 1)
{
	$saveOrderingUrl = 'index.php?option=com_newsfeeds&task=newsfeeds.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'articleList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}
?>
    <script type="text/javascript">
	Joomla.orderTable = function()
	{
		table = document.getElementById("sortTable");
		direction = document.getElementById("directionTable");
		order = table.options[table.selectedIndex].value;
		if (order != '<?php echo $listOrder; ?>')
		{
			dirn = 'DESC';
		}
		else
		{
			dirn = direction.options[direction.selectedIndex].value;
		}
		Joomla.tableOrdering(order, dirn, '');
	}
</script>

</div>


<div class='span10' >
<!--Show the list of brewers here.-->
<div class="filter-search btn-group pull-left">
				<label for="filter_search" class="element-invisible"><?php echo JText::_('Search');?></label>
				<input type="text" name="filter_search" id="filter_search" placeholder="<?php echo JText::_('Search'); ?>" value="<?php echo $_REQUEST['filter_search']; ?>" class="hasTooltip" title="<?php echo JHtml::tooltipText('Search Brew'); ?>" />
			</div>
<div class="btn-group pull-left hidden-phone">
				<button type="submit" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
				<button type="button" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.id('filter_search').value='';this.form.submit();"><i class="icon-remove"></i></button>
			</div>
			
<div class="btn-group pull-right hidden-phone">
				<?php echo $this->pagination->getLimitBox(); ?>
</div>
<br><br>
    <table width='100%' class="table table-striped">
        <tr></tr>
 <tr>
					
					<th width="1%" class="hidden-phone">
						<?php echo JHtml::_('grid.checkall'); ?>
					</th>
					<th width="5%" class="nowrap center">
						<?php echo JHtml::_('grid.sort', 'Status', 'b.status', $listDirn, $listOrder); ?>
					</th>
					<th class="title" width='10%'>
						<?php echo JHtml::_('grid.sort', 'Brew Name', 'b.brew_name', $listDirn, $listOrder); ?>
					</th>
					<th width="5%" class="nowrap hidden-phone">
						<?php echo JHtml::_('grid.sort', 'Description', 'b.brew_desc', $listDirn, $listOrder); ?>
					</th>
                                        <th width="5%" class="nowrap hidden-phone">
						<?php echo JHtml::_('grid.sort', 'Brew Owner', 'u.username', $listDirn, $listOrder); ?>
					</th>
                                        
					<th width="5%" class="nowrap hidden-phone">
						<?php echo JHtml::_('grid.sort', 'Style', 'b.style', $listDirn, $listOrder); ?>
					</th>
					<th width="5%" class="nowrap hidden-phone">
						<?php echo JHtml::_('grid.sort', 'Orginal Gravity', 'b.original_gravity', $listDirn, $listOrder); ?>
					</th>
					
					<th width="5%" class="nowrap hidden-phone">
						<?php echo JHtml::_('grid.sort', 'Final Gravity', 'b.final_gravity', $listDirn, $listOrder); ?>
					</th>
					
					<th width="5%" class="nowrap hidden-phone">
						<?php echo JHtml::_('grid.sort', 'Brew Date', 'b.brew_date', $listDirn, $listOrder); ?>
					</th>
					<th width="1%" class="nowrap center hidden-phone">
						<?php echo JHtml::_('grid.sort', 'Bottle Date', 'b.bottle_date', $listDirn, $listOrder); ?>
					</th>
                    <th width="1%" class="nowrap center hidden-phone">
						<?php echo JHtml::_('grid.sort', 'ID', 'b.brew_id', $listDirn, $listOrder, null, 'desc', 'JGRID_HEADING_ORDERING'); ?>
					</th>
				</tr>
                                
    <?php
    ///$model->debug($this->items);
    $canChange=1;
    foreach($this->items as $item):
    echo "<tr class='row0'>";
    //echo "<td class='nowrap center hidden-phone'>".JHtml::_('grid.sort', '<i class="icon-menu-2"></i>', 'b.brew_id', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING')."</td>";
        echo "<td>".JHtml::_('grid.id', $item->brew_id, $item->brew_id)."</td>";
    //    // Create dropdown items
//    $action = $archived ? 'unarchive' : 'archive';
//    JHtml::_('actionsdropdown.' . $action, 'cb' . $i, 'newsfeeds');
//
//    $action = $trashed ? 'untrash' : 'trash';
    JHtml::_('actionsdropdown.duplicate', 'cb' . $item->brew_id, 'brews');
//
//    // Render dropdown list
//    echo JHtml::_('actionsdropdown.render', $this->escape($item->brew_name));
//  
    echo "<td><div class=\"btn-group\">".JHtml::_('jgrid.published', $item->status, $item->brew_id, 'brews.', $canChange, 'cb', $item->publish_up, $item->publish_down).JHtml::_('actionsdropdown.render', $this->escape($item->brew_name))."</div></td>";
    $brewConfigLink=JRoute::_('index.php?option=com_brewpro&view=brews&layout=form&brew-id='.str_replace("=",'',base64_encode($item->brew_id)), false);
    echo "<td><a href='$brewConfigLink'>$item->brew_name</a></td>";
    echo "<td>$item->brew_desc</td>";
    echo "<td>$item->username</td>"; 
    echo "<td>".$model->getBrewStyle($item->style)."</td>";
    echo "<td>$item->original_gravity</td>";
    echo "<td>$item->final_gravity</td>"; 
    echo "<td>".date("m/d/Y",strtotime($item->brew_date))."</td>";
    echo "<td>".date("m/d/Y ",strtotime($item->bottle_date))."</td>"; 
    echo "<td style='text-align:center'>$item->brew_id</td>";
    echo "</tr>";
    
    
    endforeach;
    ?>
                               </table>                        
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
<?php echo JHtml::_('form.token'); ?>

<div style='text-align:center'>
<?php 
$pageNav=$model->getPagination();
echo $pageNav->getListFooter(); 
?>
</div>
</form>
</div>

