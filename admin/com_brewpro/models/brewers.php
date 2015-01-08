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

class BrewProModelBrewers extends JModelLegacy
{

	var $_data = null;
	var $_id = null;	
	var $_content = null;
    var $brewerQuery="";
    var $user_group_id=14;
	
	function __construct()
	{
		parent::__construct();

		global $option;
		
		$id = JRequest::getInt('feed_id',  0, '', 'int');
		
	}
	
	
        function getItems(){
            $session = JFactory::getSession();
            if(JRequest::getVar('filter_order')):       
                $session->set('brewer_filter_order',JRequest::getVar('filter_order'));
                $session->set('brewer_filter_order_Dir',JRequest::getVar('filter_order_Dir'));
            endif;
            $listOrder=$session->get('brewer_filter_order');
            $listDir=$session->get('brewer_filter_order_Dir');
            $orderBy=($listOrder and $listDir)?"ORDER BY $listOrder $listDir":"";
            $db=  JFactory::getDbo();
            $conditionsApply="";
          
            if(isset($_REQUEST['filter_published']) and $_REQUEST['filter_published']!="*" and $_REQUEST['filter_published']!=""):
            $conditionsApply.=" AND b.status='{$_REQUEST['filter_published']}'";
            endif;
            
            if($_REQUEST['filter_search']):
                $conditionsApply.=" AND (brewer_name LIKE '%{$_REQUEST['filter_search']}%' or brewer_desc LIKE '%{$_REQUEST['filter_search']}%' or tagline LIKE '%{$_REQUEST['filter_search']}%')";
            endif;
            
            if(isset($_REQUEST['filter_brew_style']) and $_REQUEST['filter_brew_style']!="*"  and $_REQUEST['filter_brew_style']!="" ):
            $conditionsApply.=" AND FIND_IN_SET({$_REQUEST['filter_brew_style']},fav_style)";
            endif;
            
            $s=(int)$_REQUEST['limitstart'];
            $l=($_REQUEST['limit'])?$_REQUEST['limit']:20;
            $this->brewerQuery="SELECT * FROM #__brewpro_brewers as b INNER JOIN #__users as u on b.user_id=u.id INNER JOIN #__user_usergroup_map as user_map on user_map.user_id=u.id WHERE user_map.group_id='$this->user_group_id' $conditionsApply $orderBy ";
            $query=$this->brewerQuery." LIMIT $s,$l";
            //echo $query;
            $db->setQuery($query);
            $results=$db->loadObjectList();
            //$this->debug($results);
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
            $db->setQuery($this->brewerQuery);
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
        
        function unpublishBrewers(){
            $cid=$_REQUEST['cid'];
            $db=  JFactory::getDbo();
            foreach($cid as $index=>$brewer_id):
                $query="UPDATE #__brewpro_brewers SET status='0' where brewer_id='$brewer_id'";
                $db->setQuery($query);
                $db->query();
            endforeach;
            return "Selected brewers unpublished successfully !";
        }
        
        function publishBrewers(){
          $cid=$_REQUEST['cid'];  
            $db=  JFactory::getDbo();
            foreach($cid as $index=>$brewer_id):
                $query="UPDATE #__brewpro_brewers SET status='1' where brewer_id='$brewer_id'";
                $db->setQuery($query);
                $db->query();
            endforeach;
            return "Selected brewers published successfully !";
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
        
        
        function deleteBrewers(){
           $cid=$_REQUEST['cid'];  
            $db=  JFactory::getDbo();
            foreach($cid as $index=>$brewer_id):
                $query="DELETE FROM #__brewpro_brewers where brewer_id='$brewer_id'";
                $db->setQuery($query);
                $db->query();
            endforeach;
            return "Selected brewers deleted successfully !"; 
        }
    
    function getBrewer($brewer_id){
        $db=JFactory::getDBO();
        $brewer_id=base64_decode($brewer_id);
        $query="Select * FROM #__brewpro_brewers WHERE brewer_id='$brewer_id'";
        $db->setQuery($query);
        $result=$db->loadObject();
//      $this->debug($result);
        return $result;    
    }
    
    function getBrewStyles(){
        
        $db=JFactory::getDBO();
        $query="SELECT * FROM #__brewpro_styles";
        $db->setQuery($query);
        return $db->loadObjectList();
    }
    
    function cronNewBrewer(){
    
       
    }
    
    function saveBrewerDetails(){
        $db=JFactory::getDBO();
         //First of all get the brewer id, then do whatever you want to.
        $brewerId=(JRequest::getVar('brewer-id'))?base64_decode(JRequest::getVar('brewer-id')):"";       
        $queryType=(!$brewerId)?"INSERT INTO ":"UPDATE ";
        $brewer_image=""; // Initially assume that there is no image.
        if($_FILES['brewer_image']['name']):
        $brewer_image="images/brewer/".$_FILES['brewer_image']['name'];
        @move_uploaded_file($_FILES['brewer_image']['tmp_name'],JPATH_SITE."/".$brewer_image);
        endif;
        
        $fav_style=implode(",",$_POST['fav_style']);
        $query=$queryType. " #__brewpro_brewers SET ";
        $query.="brewer_name='".implode("|",$_POST['brewer_name'])."', ";
        $query.="brewer_desc='{$_POST['brewer_desc']}', ";
        if($brewer_image):
        $query.="brewer_image='$brewer_image', ";
        endif;
        $query.="fav_style='{$fav_style}', ";
        $query.="tagline='{$_POST['tagline']}', ";
        $query.="web='{$_POST['web']}', ";
        $query.="city='{$_POST['city']}', ";
        $query.="state='{$_POST['state']}', ";
        $query.="yrs_brewing='{$_POST['yrs_brewing']}', ";
        $query.="brewing_type='{$_POST['brewing_type']}', ";
        $query.="liked_by='".implode(",",$_POST['liked_by'])."', ";
        $query.="likes='{$_POST['likes']}', ";
        if(!$brewerId)
        $query.="user_id='{$_POST['user_id']}', ";
        $query.="status='{$_POST['status']}' ";
        
        if($brewerId)
        $query.=" WHERE brewer_id='$brewerId'";
        
        $db->setQuery($query);
        $db->query();
        
        if($brewerId)
        return $brewerId;
        else
        return $db->insertid();
    }
       
    
     function getStatesList(){
  $states=array("Alabama","Alaska","Alberta","American","Samoa","Arizona" 
,"Arkansas","Armed Forces Americas","Armed Forces Europe","Armed Forces Pacific","British Columbia"  
,"California","Colorado","Connecticut","Delaware","District of Columbia"
,"District of Columbia","Federated States of Micronesia","Florida Georgia Guam"  
,"Hawaii","Idaho","Illinois","Indiana","Iowa"  
,"Kansas","Kentucky","Louisiana","Maine","Manitoba"  
,"Marshall","Islands","Maryland","Massachusetts","Michigan","Minnesota" 
,"Mississippi","Missouri","Montana","Nebraska","Nevada"  
,"New Brunswick","New Hampshire","New Jersey","New Mexico","New York"  
,"Newfoundland and Labrador","North Carolina","North Dakota","Northern Mariana Islands","Northwest Territories" 
,"Nova","Scotia","Nunavut","Ohio","Oklahoma","Ontario" 
,"Oregon","Palau","Pennsylvania","Prince Edward Island","Puerto Rico" 
,"Quebec","Rhode Island","Saskatchewan","South Carolina","South Dakota"  
,"Tennessee","Texas","U.S. Virgin Islands","Utah","Vermont" 
,"Virginia","Washington","West Virginia","Wisconsin","Wyoming" 
,"Yukon");

  return $states;
 }
    
    
    function getUniqueBrewerIdList(){
        //Best for each only one brewer one brewere detail is required/
        $db=JFactory::getDbo();
        $mysqlQuery="SELECT user_id FROM #__brewpro_brewers";
        $db->setQuery($mysqlQuery);
        $alreadyCreatedBrewerIds=array();
        foreach($db->loadObjectList() as $key):
        $alreadyCreatedBrewerIds[]=$key->user_id;
        endforeach;
        //$alreadyCreatedBrewerIds[]=1; //kept purposely so that the array should not be empty
        $alreadyCretedBrewers=implode(",",$alreadyCreatedBrewerIds);
        $query="SELECT * FROM #__users as u INNER JOIN #__user_usergroup_map as up ON u.id=up.user_id WHERE up.group_id='$this->user_group_id' AND FIND_IN_SET(u.id,'$alreadyCretedBrewers')=0"; // this is the correct query.
        $db->setQuery($query);
        $result=$db->loadObjectList(); // This are the unique ids which are linked to any of the brewer details.
//        $this->debug($result);
        return $result;
    }
    
 function getModul(){
 }
        
}//Model class ends here.

?>
