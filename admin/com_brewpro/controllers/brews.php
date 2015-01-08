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

jimport('joomla.application.component.controller');

class BrewProControllerBrews extends JControllerAdmin
{

	function __construct()
	{
                $model = $this->getModel();
		parent::__construct();	
		
	}	
    
    public function add(){
        $this->setRedirect('index.php?option=com_brewpro&view=brews&layout=form');
    }
        
    function publish(){
        $model=$this->getModel('brews');
        if(JRequest::getVar('task')=="unpublish"):
        $unpublishMessage=$model->unpublishBrews();
        $this->setRedirect('index.php?option=com_brewpro&view=brews',$unpublishMessage);
        endif;

        if(JRequest::getVar('task')=="publish"):
        $publishMessage=$model->publishBrews();
        $this->setRedirect('index.php?option=com_brewpro&view=brews',$publishMessage);
        endif;

    }
        
        
    function delete() {
        $model=$this->getModel('brews');
        $msg=$model->deleteBrews();
        $this->setRedirect('index.php?option=com_brewpro&view=brews',$msg);
    }
        
        
    function duplicate(){
        $model=$this->getModel('brews');
        $msg=$model->duplicateBrew();
        $this->setRedirect('index.php?option=com_brewpro&view=brews',$msg);
    }

    function cancel(){
        $this->setRedirect('index.php?option=com_brewpro&view=brews');
    }
        
    function apply() //this is the simple save of the brew details.
    {    
        $model=$this->getModel('brews');
        $brewId=$model->saveBrewDetails();
        $this->setRedirect('index.php?option=com_brewpro&view=brews&layout=form&brew-id='.trim(base64_encode($brewId),"="),"Brew Details updated successfully!");
        
    }
    
    function save() // this is the save and close .
    {
        $model=$this->getModel('brews');
        $brewId=$model->saveBrewDetails();     
        $this->setRedirect('index.php?option=com_brewpro&view=brews',"Brew Details updated successfully!");
    }
    
    
       
}//Controller class ends here.
?>
