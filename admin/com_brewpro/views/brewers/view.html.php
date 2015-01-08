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
class BrewProViewBrewers extends JViewLegacy
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
    
        protected $items;

	protected $pagination;

	protected $state;
        
	public function display($tpl = null)
	{
        $this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');
                
		$app    = JFactory::getApplication();
		//$params = $app->getParams();
        BrewProHelper::addSubmenu('brewers');
        $model = $this->getModel();
		$model->cronNewBrewer();
        $this->addToolbar();
		parent::display($tpl);
	}
        
    
        
        public function addToolbar(){ //this is the dynamic toolbar.
            
            JToolbarHelper::title(JText::_('BrewPro-Brewers Configuration'), 'pushpin banners');
            
            if(JRequest::getVar('view')=="brewers" and JRequest::getVar('layout')==""):
            JToolbarHelper::addNew('brewers.add');
            JToolbarHelper::deleteList('Are you sure you want to delete this Brewer(s)?','brewers.delete',"Delete");
            JToolbarHelper::publish('brewers.publish', 'JTOOLBAR_PUBLISH', true);
            JToolbarHelper::unpublish('brewers.unpublish', 'JTOOLBAR_UNPUBLISH', true);
            
            $filter_published=(isset($_REQUEST['filter_published']))?JRequest::getVar('filter_published'):"";
            
            $options        = array();       
            $options[]      = JHtml::_('select.option', '*', 'All');
            $options[]      = JHtml::_('select.option', '1', 'published');
            $options[]      = JHtml::_('select.option', '0', 'Unpublished');
            
            JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_PUBLISHED'),
			'filter_published',
			JHtml::_('select.options',$options, 'value', 'text',$filter_published, true)
		);
            

            $filter_brew_style=(isset($_REQUEST['filter_brew_style']))?JRequest::getVar('filter_brew_style'):"";      
            $options        = array();       
            $options[]      = JHtml::_('select.option', '*', 'All');
           $db=  JFactory::getDbo();
           $query="SELECT * FROM #__brewpro_styles";
           $db->setQuery($query);
           $results=$db->loadObjectList();
           foreach($results as $key):
               $options[]=JHtml::_('select.option',$key->brew_style_id,$key->brew_style);
           endforeach;
            
		JHtmlSidebar::addFilter(
			JText::_('- Select Brew Style -'),
			'filter_brew_style',
			JHtml::_('select.options',$options, 'value', 'text',$filter_brew_style, true)
		);
                
            endif;
            
            if(JRequest::getVar('layout')=="form"):
            JToolbarHelper::apply('brewers.apply');
            JToolbarHelper::save('brewers.save');
            JToolbarHelper::cancel('brewers.cancel');
            //JToolbarHelper::back('Back');
            endif;
            
            
            
        }
    
    
    
    
}//Class ends here.
