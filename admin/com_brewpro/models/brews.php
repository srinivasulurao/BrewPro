<?php

/**
* @version		2.0
* @package		com_ninjarsssydicator
* @author 		NinjaForge
* @author email	support@ninjaforge.com
* @link			http://ninjaforge.com
* @license      http://www.gnu.org/copyleft/gpl.html GNU GPL
* @copyright	Copyright (C) 2012 NinjaForge - All rights reserved.
*/

defined('_JEXEC') or die( 'Restricted access' );
 error_reporting(~E_NOTICE);

jimport( 'joomla.application.component.model' );

class BrewProModelBrews extends JModelLegacy
{

	var $_data = null;
	var $_id = null;	
	var $_content = null;
    
    var $brewQuery="";
	
	function __construct()
	{
		parent::__construct();

		global $option;
		
		$id = JRequest::getInt('feed_id',  0, '', 'int');
		
	}
	
	
        function getItems(){
            $session = JFactory::getSession();
            if(JRequest::getVar('filter_order')):       
                $session->set('brew_filter_order',JRequest::getVar('filter_order'));
                $session->set('brew_filter_order_Dir',JRequest::getVar('filter_order_Dir'));
            endif;
            $listOrder=$session->get('brew_filter_order');
            $listDir=$session->get('brew_filter_order_Dir');
            $orderBy=($listOrder and $listDir)?"ORDER BY $listOrder $listDir":"";
            $db=  JFactory::getDbo();
            $conditionsApply="";
          
            if(isset($_REQUEST['filter_published']) and $_REQUEST['filter_published']!="*" and $_REQUEST['filter_published']!=""):
            $conditionsApply.=" AND b.status='{$_REQUEST['filter_published']}'";
            endif;
            
            if($_REQUEST['filter_search']):
                $conditionsApply.=" AND (brew_name LIKE '%{$_REQUEST['filter_search']}%' or brew_desc LIKE '%{$_REQUEST['filter_search']}%')";
            endif;
            
            if(isset($_REQUEST['filter_brew_style']) and $_REQUEST['filter_brew_style']!="*"  and $_REQUEST['filter_brew_style']!="" ):
            $conditionsApply.=" AND FIND_IN_SET({$_REQUEST['filter_brew_style']},style)";
            endif;
            
            $s=(int)$_REQUEST['limitstart'];
            $l=($_REQUEST['limit'])?$_REQUEST['limit']:20;
            $this->brewQuery="SELECT * FROM #__brewpro_brews as b INNER JOIN #__users as u on b.owner_id=u.id WHERE 1  $conditionsApply $orderBy ";
            $query=$this->brewQuery." LIMIT $s,$l";
            //echo $query;
            $db->setQuery($query);
            $results=$db->loadObjectList();
            
            return $results;
        }
        
        function getBrewStyle($style){
            $styleExplode=explode(",",$style);
            $sendStyle="";
            $db=JFactory::getDbo();
            if(sizeof($styleExplode)){
            foreach($styleExplode as $key=>$value):
                $query="SELECT * FROM #__brewpro_styles WHERE brew_style_id='$value'";
                $db->setQuery($query);
                $result=$db->loadObject();
                $sendStyle.=$result->brew_style.",";
            endforeach;
           
           return rtrim($sendStyle,",");
            }
            else
                return "-";
        }
         function getPagination(){
            
            jimport('joomla.html.pagination');
            $db=  JFactory::getDbo();
            $db->setQuery($this->brewQuery);
            $results=sizeof($db->loadObjectList());
            $s=(int)$_REQUEST['limitstart'];
            $l=($_REQUEST['limit'])?$_REQUEST['limit']:20;
            $pageNav = new JPagination($results, $s, $l);
           return $pageNav;
            
        }
	
        function debug($arrayOrObject){
            echo"<pre>";
            print_r($arrayOrObject);
            echo"</pre>";
        }
        
        function unpublishBrews(){
            $cid=$_REQUEST['cid'];
            $db=  JFactory::getDbo();
            foreach($cid as $index=>$brew_id):
                $query="UPDATE #__brewpro_brews SET status='0' where brew_id='$brew_id'";
                $db->setQuery($query);
                $db->query();
            endforeach;
            return "Selected brews unpublished successfully !";
        }
        
        function publishBrews(){
          $cid=$_REQUEST['cid'];  
            $db=  JFactory::getDbo();
            foreach($cid as $index=>$brew_id):
                $query="UPDATE #__brewpro_brews SET status='1' where brew_id='$brew_id'";
                $db->setQuery($query);
                $db->query();
            endforeach;
            return "Selected brews published successfully !";
        }
	
        function duplicateBrew(){
            $cid=$_REQUEST['cid'];
            $db=  JFactory::getDbo();
            foreach($cid as $index=>$brew_id):
                $query1="SELECT * FROM #__brewpro_brews WHERE brew_id='$brew_id'";
                $db->setQuery($query1);
                $replicate=$db->loadObject();
                $dumper=$this->getDuplicateDumper($replicate);
                $query2="INSERT INTO #__brewpro_brews SET $dumper";
                $db->setQuery($query2);
                $db->query();
            endforeach;
        return "Brew duplication done successfully!";
        }
        
        function getDuplicateDumper($dumpingArray){
            $array=(array)$dumpingArray;
            $dump="";
            foreach($array as $key=>$value):
                if($key!="brew_id")
                $dump.=$key."='".$value."'".",";
            endforeach;
        
        return rtrim($dump,",");
        }
        
        
        function deleteBrews(){
           $cid=$_REQUEST['cid'];  
            $db=  JFactory::getDbo();
            foreach($cid as $index=>$brew_id):
                $query="DELETE FROM #__brewpro_brews where brew_id='$brew_id'";
                $db->setQuery($query);
                $db->query();
            endforeach;
            return "Selected brews deleted successfully !"; 
        }
    
    function getBrew($brew_id){
        $db=JFactory::getDBO();
        $brew_id=base64_decode($brew_id);
        $query="Select * FROM #__brewpro_brews WHERE brew_id='$brew_id'";
        $db->setQuery($query);
        $result=$db->loadObject();
//        $this->debug($result);
        return $result;    
    }
    
    function getBrewStyles(){
        
        $db=JFactory::getDBO();
        $query="SELECT * FROM #__brewpro_styles";
        $db->setQuery($query);
        return $db->loadObjectList();
    }
    
    
    function saveBrewDetails(){
        $db=JFactory::getDBO();
         //First of all get the brewer id, then do whatever you want to.
        $brewId=(JRequest::getVar('brew-id'))?base64_decode(JRequest::getVar('brew-id')):"";       
        $queryType=(!$brewId)?"INSERT INTO ":"UPDATE ";
        $brew_image=""; // Initially assume that there is no image.
        if($_FILES['brew_image']['name']):
        $brew_image="images/brew/".$_FILES['brew_image']['name'];
        move_uploaded_file($_FILES['brew_image']['tmp_name'],JPATH_SITE."/".$brew_image);
        endif;
        
        $brew_date=date("Y-m-d H:i:s",strtotime($_POST['brew_date']));
        $bottle_date=date("Y-m-d H:i:s",strtotime($_POST['bottle_date']));
        $style=implode(",",$_POST['style']);
        $query=$queryType. " #__brewpro_brews SET ";
        $query.="brew_name='{$_POST['brew_name']}', ";
        $query.="brew_desc='{$_POST['brew_desc']}', ";
        if($brew_image):
        $query.="brew_image='$brew_image', ";
        endif;
        $query.="style='{$style}', ";
        $query.="abv='{$_POST['abv']}', ";
        $query.="ibu='{$_POST['ibu']}', ";
        $query.="original_gravity='{$_POST['original_gravity']}', ";
        $query.="final_gravity='{$_POST['final_gravity']}', ";
        $query.="brew_date='$brew_date', ";
        $query.="bottle_date='$bottle_date', ";
        $query.="status='{$_POST['status']}', ";
        $query.="owner_id='{$_POST['owner_id']}' ";
        if($brewId)
        $query.=" WHERE brew_id='$brewId'";
        
        $db->setQuery($query);
        $db->query();
        
        if($brewId)
        return $brewId;
        else
        return $db->insertid();
    }
       
        
}//Model class ends here.

?>
