<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_wrapper
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * @package     Joomla.Site
 * @subpackage  com_wrapper
 * @since       1.5
 */
class BrewProViewCpanel extends JViewLegacy
{
	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise a Error object.
	 *
	 * @since   1.5
	 */
	public function display($tpl = null)
	{
		$app    = JFactory::getApplication();
		//$params = $app->getParams();
                BrewProHelper::addSubmenu('cpanel');
                $model = $this->getModel();
		JToolbarHelper::title(JText::_('BrewPro-Cpanel'), 'pushpin banners');
		parent::display($tpl);
	}
}
