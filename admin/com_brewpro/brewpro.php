<?php

defined('_JEXEC') or die('Restricted access');
//require_once (JPATH_COMPONENT.DS.'controllers'.DS.'cpanel.php');
JFactory::getDocument()->addStyleSheet(JURI::root()."administrator/components/com_brewpro/assets/css/backend.css");
if($controller = JRequest::getWord('controller')) {
	require_once (JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php');
}
$controller = JControllerLegacy::getInstance('BrewPro');
$controller->execute( JRequest::getWord('task'));
$controller->redirect();
?>