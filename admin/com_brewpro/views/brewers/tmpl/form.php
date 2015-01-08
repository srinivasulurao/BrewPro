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
$brewer=$model->getBrewer(JRequest::getVar('brewer-id')); // This id is not a numeric value.(base64 encoded).
$name=explode("|",$brewer->brewer_name);
?>
<ul id="myTabTabs" class="nav nav-tabs"><li class="active"><a data-toggle="tab" href="#brewerDetails">Brewer Details</a></li><li class=""><a data-toggle="tab" href="#likes">Likes</a></li></ul>
<div id="myTabContent" class="tab-content">
<div id='brewerDetails' class='tab-pane active'>
<div class='span4'>
<label>Brewer Name1 :</label>
<input type="text" name='brewer_name[]' value='<?php echo $name[0]; ?>' required='required'><br>
<label>Brewer Name2 :</label>
<input type="text" name='brewer_name[]' value='<?php echo $name[1]; ?>' required='required'><br>
<label>Tagline :</label>
<input type="text" name='tagline' value='<?php echo $brewer->tagline; ?>'><br><br>
<label>Enable/Disable :</label>
<?php 
$status_enabled=($brewer->status)?"selected='selected'":"";
$status_disabled=(!$brewer->status)?"selected='selected'":"";
?>
<select name='status'>
    <option value='1' <?php echo $status_enabled; ?> >Enabled</option>
    <option value='0' <?php echo $status_disabled; ?> >Disabled</option>
</select><br><br>
<label>Brewer Image : </label>
    <input type='file' name='brewer_image'> <?php echo basename($brewer->brewer_image); ?><br><br>
<label>Brew Style : </label>
    <select name='fav_style[]' multiple='multiple' style="width:90%">
    <option value=''>-SELECT-</option>
<?php
foreach($model->getBrewStyles() as $style):
$brew_style=explode(",",$brewer->fav_style);
$brew_style_selected=(in_array($style->brew_style_id,$brew_style))?"selected='selected'":"";
echo"<option value='$style->brew_style_id' $brew_style_selected>$style->brew_style</option>";
endforeach;
?>
    </select><br><br>
<label>User Id:</label>
<?php if(JRequest::getVar('brewer-id')): ?>
<input type="text" disabled name='user_id' value='<?php echo $brewer->user_id; ?>'>
<?php endif; ?>
    
<?php if(JRequest::getVar('brewer-id')==""): ?>
<select name='user_id' required='required' >
<option value=''>-SELECT-</option>
<?php
foreach($model->getUniqueBrewerIdList() as $brewUser):
echo "<option value='$brewUser->id'>$brewUser->name</option>";
endforeach;
?>
</select>
<?php endif; ?>

    <br><br>


    </div>   
    
<div class="span3">
<label>Web :</label>
<input type="text" name='web' value='<?php echo $brewer->web; ?>'><br><br>
<label>City :</label>
<input type="text" name='city' value='<?php echo $brewer->city; ?>'><br><br>
<label>State :</label>
<select name='state'>
<option value="">-SELECT-</option>
<?php
foreach($model->getStatesList() as $state):
$stateSel=($state==$brewer->state)?"selected='selected'":"";
echo "<option value='$state' $stateSel>$state</option>";
endforeach;
?>
</select><br><br>
<label>Years of Brewing :</label>
<input type="text" name='yrs_brewing' value='<?php echo $brewer->yrs_brewing; ?>'><br><br>

<label>Brewing Type :</label>
<input type="text" name='brewing_type' value='<?php echo $brewer->brewing_type; ?>'><br><br>
<!--<?php //echo JHtml::_('select.options', UsersHelper::getStateOptions(), 'value', 'text', $this->state->get('filter.state')); ?>-->
<br><br>
</div>
    
    
<div class="span5" style="height:200px !important">
<label>Brewer Description :</label>
    <?php
    @$editor =& JFactory::getEditor();
    $params = array( 'smilies'=> '0' ,
		 'style'  => '1' ,  
		 'layer'  => '0' , 
		 'table'  => '0' ,
         'height'=>'300px',
		 'clear_entities'=>'0'
		 );
echo $editor->display( 'brewer_desc', $brewer->brewer_desc, '200', '200', '10', '10', false, $params );
?>
</div>
</div><!-- Tab pane brewerDetails ends here -->
    
<div  id='likes' class='tab-pane'>
    <label >Total Likes :</label><input type='text' name='likes' value='<?php echo (int)$brewer->likes; ?>'> &nbsp Likes.
    <label>Liked By :</label>
    <select name='liked_by[]' multiple='multiple'>
    <?php
    $db=JFactory::getDbo();
    $db->setQuery("SELECT * FROM #__users");
    $allUsers=$db->loadObjectList();
    foreach($allUsers as $key):
    $likedBy=explode(",",$brewer->liked_by);
    $userLiked=(in_array($key->id,$likedBy))?"selected='selected'":"";
    echo "<option value='$key->id' $userLiked>$key->name</option>";
    endforeach;
    ?>
    </select>
    </div><!-- Tab pane likes ends here -->
    
    
     
</div> <!--Tab content ends here --> 
    
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter_order" value="" />
<input type="hidden" name="filter_order_Dir" value="" />
<?php echo JHtml::_('form.token'); ?>

</form>
   
</div>

