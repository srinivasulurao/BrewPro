<?php
defined('_JEXEC') or die;
?>
<div class='span2'>
<?php
$model = $this->getModel();
echo $this->sidebar = JHtmlSidebar::render();
?>
</div>


<div class='span6' id='cpanelMenus'>
    <div id='cpanel'>
<!--Show the Menus here.-->
<?php
$cpanelLinks=$model->getCpanelLinks();
foreach($cpanelLinks as $key):
    echo "<div class='icon'>";
    $icon="<img src='".JURI::root()."administrator/components/com_brewpro/assets/images/"."{$key['icon']}'>";
    $url=JURI::current()."?option=com_brewpro&view=".$key['link'];
    echo"<a href='$url' style='text-align:center' href='$url'>$icon<br>{$key['name']}</a>";
    echo "</div>";
endforeach;
?>
    </div>
</div>

<div class='span4'>
 <div class='logoDiv'>
<?php
$brewProLogo=JURI::root()."administrator/components/com_brewpro/assets/images/brewpro.jpg";
echo "<img src='$brewProLogo'><br><br>";

foreach($model->getBrewProLicense() as $key=>$value):
echo "<div style='text-align:left;padding:10px;background:#123456;color:white;border-bottom:1px solid grey'><b style='display:inline-block;width:100px;'>$key</b>: $value</div>";
endforeach;
?>
     
 </div>
</div>