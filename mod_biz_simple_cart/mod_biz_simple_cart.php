<?php
/**
 * Biz Simple Cart
 *
 * @version 	0.0.1
 * @author		Bizgo.nl 
 * @copyright	(C) 2019, bizgo.nl. All rights reserved.
 * @license 	GNU General Public License version 2 or later; see	LICENSE.txt
 * Forked from Joomline ContentCart
 */

defined('_JEXEC') or die;

if ($params->def('prepare_content', 1))
{
	JPluginHelper::importPlugin('content');
	$module->content = JHtml::_('content.prepare', $module->content, '', 'mod_biz_simple_cart.content');
}

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'), ENT_COMPAT, 'UTF-8');

require JModuleHelper::getLayoutPath('mod_biz_simple_cart', $params->get('layout', 'default'));
