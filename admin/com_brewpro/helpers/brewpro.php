<?php
defined('_JEXEC') or die;

class BrewProHelper extends JHelperContent
{
	/**
	 * Configure the Linkbar.
	 *
	 * @param   string  $vName  The name of the active view.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	public static function addSubmenu($vName)
	{
		JHtmlSidebar::addEntry(
			JText::_('BrewPro-Cpanel'),
			'index.php?option=com_brewpro&view=cpanel',
			$vName == 'cpanel'
		);

                JHtmlSidebar::addEntry(
			JText::_('Brews'),
			'index.php?option=com_brewpro&view=brews',
			$vName == 'brews'
		);
                
		JHtmlSidebar::addEntry(
			JText::_('Brewers'),
			'index.php?option=com_brewpro&view=brewers',
			$vName == 'brewers'
		);
                
                
                
                JHtmlSidebar::addEntry(
			JText::_('Brew Fans'),
			'index.php?option=com_brewpro&view=brewers',
			$vName == 'brew-fans'
		);
                
                JHtmlSidebar::addEntry(
			JText::_('Messages'),
			'index.php?option=com_brewpro&view=messages',
			$vName == 'attribute'
		);
                
                JHtmlSidebar::addEntry(
			JText::_('Settings'),
			'index.php?option=com_brewpro&view=brewers',
			$vName == 'settings'
		);
                
              
	}

}



?>