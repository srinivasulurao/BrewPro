<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_brewpro
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Content Component Controller
 *
 * @package     Joomla.Site
 * @subpackage  com_wrapper
 * @since       1.5
 */
class BrewProController extends JControllerLegacy
{
	/**
	 * Method to display a view.
	 *
	 * @param   boolean  $cachable   If true, the view output will be cached
	 * @param   array    $urlparams  An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return  JControllerLegacy  This object to support chaining.
	 *
	 * @since   1.5
	 */
	public function display($cachable = false, $urlparams = array())
	{
                require_once JPATH_COMPONENT.'/helpers/brewpro.php';
		$cachable = true;
		// Set the default view name and format from the Request.
		$vName = $this->input->get('view', 'cpanel');
		$this->input->set('view', $vName);
		return parent::display($cachable, array('Itemid' => 'INT'));
	}
}
