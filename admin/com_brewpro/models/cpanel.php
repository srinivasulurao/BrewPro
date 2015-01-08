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

jimport( 'joomla.application.component.model' );

class BrewProModelCpanel extends JModelLegacy
{

	var $_data = null;
	var $_id = null;	
	var $_content = null;
	
	function __construct()
	{
		parent::__construct();

		global $option;
		
		$id = JRequest::getInt('feed_id',  0, '', 'int');
		
	}
	
	function getCpanelLinks(){
            $links=array();
            
            $links[0]['name']="C-Panel";
            $links[0]['link']="cpanel";
            $links[0]['icon']="cpanel.png";
            
            $links[1]['name']="Brews";
            $links[1]['link']="brews";
            $links[1]['icon']="drink.png";
            
            $links[2]['name']="Brewers";
            $links[2]['link']="brewers";
            $links[2]['icon']="brewers.png";
            
            $links[3]['name']="Brew Fans";
            $links[3]['link']="fans";
            $links[3]['icon']="brew-fans.png";
            
            $links[4]['name']="Messages";
            $links[4]['link']="messages";
            $links[4]['icon']="attribute.png";
            
            $links[5]['name']="BrewPro Settings";
            $links[5]['link']="settings";
            $links[5]['icon']="settings.png";
                    
            return $links;
        }
	
        
      function getBrewProLicense(){
          $details=array();
          
          $details['Component']="BrewPro (Brew Management Software)";
          $details['Author']="N.Srinivasulu Rao";
          $details['Version']="1.0.0";
          $details['License']="GNU/GPL (Distributed under Omkarsoft)";
          $details['Copyright']="&copy Omkarsoft Pvt Ltd";
          return $details;
          
      }
	
}
?>
