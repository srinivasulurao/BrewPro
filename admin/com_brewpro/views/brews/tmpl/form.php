<?php
defined('_JEXEC') or die;
?>
<form method="post" action="" id='adminForm' name='adminForm' enctype="multipart/form-data">

<?php

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.modal');


$model = $this->getModel();
//echo $this->sidebar = JHtmlSidebar::render();
error_reporting(~E_NOTICE);
?>
    
<div class='span12'>
<?php
//$model->debug($_REQUEST);
$brew=$model->getBrew(JRequest::getVar('brew-id')); // This id is not a numeric value.(base64 encoded).
$active1="active";
$active2="";

?>
    
<ul id="configTabs" class="nav nav-tabs"><li class="<?php echo $active1; ?>"><a data-toggle="tab" href="#brewDetails" onclick="currentTabOpen(1)">Brew/Beer Details</a></li><li class='<?php echo $active2; ?>'><a data-toggle="tab" href="#ratings_comments" onclick='currentTabOpen(2)'>Rating and Comments</a></li></ul>
<div id="myTabContent" class="tab-content">

<div id='brewDetails' class='tab-pane <?php echo $active1; ?>' >
<div class='span4'>
<label>Brew Name :</label>
<input type="text" name='brew_name' value='<?php echo $brew->brew_name; ?>' required='required'><br>
<label>Enable/Disable :</label>
<?php 
$status_enabled=($brew->status)?"selected='selected'":"";
$status_disabled=(!$brew->status)?"selected='selected'":"";
?>
<select name='status'>
    <option value='1' <?php echo $status_enabled; ?> >Enabled</option>
    <option value='0' <?php echo $status_disabled; ?> >Disabled</option>
</select><br><br>
<label>Brew Image : </label>
    <input type='file' name='brew_image'> <?php echo basename($brew->brew_image); ?><br><br>
<label>Brew Style : </label>
    <select name='style[]' multiple='multiple' style="width:90%">
    <option value=''>-SELECT-</option>
<?php
foreach($model->getBrewStyles() as $style):
$brew_style=explode(",",$brew->style);
$brew_style_selected=(in_array($style->brew_style_id,$brew_style))?"selected='selected'":"";
echo"<option value='$style->brew_style_id' $brew_style_selected>$style->brew_style</option>";
endforeach;
?>
    </select><br><br>
<label>Created By:</label>
<select name='owner_id' required='required'>
<option value="">-SELECT-</option>
<?php
$db=JFactory::getDBO();
$db->setQuery("SELECT * FROM #__users");
$owners=$db->loadObjectList();
foreach($owners as $owner):
$ownerSelected=($owner->id==$brew->owner_id)?"selected='selected'":"";
echo "<option value='$owner->id' $ownerSelected>$owner->username</option>";
endforeach;
?>
</select><br><br>
<!--<input type="user" name='created_by' value='<?php echo $brew->owner_id; ?>'><br><br>-->
<label>ABV :</label>
<input type="text" name='abv' value='<?php echo $brew->abv; ?>'><br><br>
    </div>   
    
<div class="span3">
<label>IBUs :</label>
<input type="text" name='ibu' value='<?php echo $brew->ibu; ?>'><br><br>
<label>Original Gravity :</label>
<input type="text" name='original_gravity' value='<?php echo $brew->original_gravity; ?>'><br><br>
<label>Final Gravity :</label>
<input type="text" name='final_gravity' value='<?php echo $brew->final_gravity; ?>'><br><br>
<label>Brew Date :</label>
<!--<input type="calendar" name='brew_name' value='<?php echo $brew->brew_name; ?>'><br><br>-->
<?php echo JHTML::calendar($brew->brew_date,'brew_date','brew_date','%m/%d/%Y'); ?><br><br>
<label>Bottle Date :</label>
<!--<input type="calendar" name='brew_name' value='<?php echo $brew->brew_name; ?>'><br><br>-->
<?php echo JHTML::calendar($brew->brew_date,'bottle_date','bottle_date','%m/%d/%Y'); ?>
<!--<?php //echo JHtml::_('select.options', UsersHelper::getStateOptions(), 'value', 'text', $this->state->get('filter.state')); ?>-->
<br><br>
</div>
    
    
<div class="span5" style="height:200px !important">
<label>Brew Description :</label>
    <?php
    @$editor =& JFactory::getEditor();
    $params = array( 'smilies'=> '0' ,
		 'style'  => '1' ,  
		 'layer'  => '0' , 
		 'table'  => '0' ,
         'height'=>'300px',
		 'clear_entities'=>'0'
		 );
echo $editor->display( 'brew_desc', $brew->brew_desc, '200', '200', '10', '10', false, $params );
?>
</div>
    </div><!-- Brew Details here-->
    
<div id='ratings_comments' class='tab-pane <?php echo $active2; ?>'>
    <table>
        
    </table>
    
</div>  
    
    
<input type='hidden' name='tab_open' id='tab_open' value="1" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter_order" value="" />
<input type="hidden" name="filter_order_Dir" value="" />
<?php echo JHtml::_('form.token'); ?>

</div> <!-- Tab Content end her-->
</form>
</div>

<script>
    function currentTabOpen(tab_open){
    document.getElementById('tab_open').value=tab_open;
    }
</script>
