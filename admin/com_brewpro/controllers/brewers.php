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

class BrewProControllerBrewers extends JControllerAdmin
{

	function __construct()
	{
        $model = $this->getModel();
		parent::__construct();	
		
	}	
    
    public function add(){
        $this->setRedirect('index.php?option=com_brewpro&view=brewers&layout=form');
    }
        
    function publish(){
        $model=$this->getModel('brewers');
        if(JRequest::getVar('task')=="unpublish"):
        $unpublishMessage=$model->unpublishBrewers();
        $this->setRedirect('index.php?option=com_brewpro&view=brewers',$unpublishMessage);
        endif;

        if(JRequest::getVar('task')=="publish"):
        $publishMessage=$model->publishBrewers();
        $this->setRedirect('index.php?option=com_brewpro&view=brewers',$publishMessage);
        endif;

    }
        
        
    function delete() {
        $model=$this->getModel('brewers');
        $msg=$model->deleteBrewers();
        $this->setRedirect('index.php?option=com_brewpro&view=brewers',$msg);
    }
        
        
    function duplicate(){
        $model=$this->getModel('brewers');
        $msg=$model->duplicateBrew();
        $this->setRedirect('index.php?option=com_brewpro&view=brewers',$msg);
    }

    function cancel(){
        $this->setRedirect('index.php?option=com_brewpro&view=brewers');
    }
        
    function apply() //this is the simple save of the brew details.
    {    
        $model=$this->getModel('brewers');
        $brewerId=$model->saveBrewerDetails();
        $this->setRedirect('index.php?option=com_brewpro&view=brewers&layout=form&brewer-id='.trim(base64_encode($brewerId),"="),"Brewer Details updated successfully!");
        
    }
    
    function save() // this is the save and close .
    {
        $model=$this->getModel('brewers');
        $brewId=$model->saveBrewerDetails();     
        $this->setRedirect('index.php?option=com_brewpro&view=brewers',"Brewer Details updated successfully!");
    }
    
    
    
       
}//Controller class ends here.
?>
