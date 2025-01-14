﻿<?php
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
header('Content-Type: text/html; charset=utf-8');
$session = JFactory::getSession();
$plugin = JPluginHelper::getPlugin('content','biz_simple_cart');
$pluginParams = new JRegistry($plugin->params);
$content_order = $session->get('content_order');

	$mailer = JFactory::getMailer();
	$app = JFactory::getApplication();
	$sender = array('no-reply@'.$_SERVER['HTTP_HOST'], $fromname = $app->get('fromname'));
	$mailer->setSender($sender);

	$recipient = array($pluginParams->get('shop_email'));
	$mailer->addRecipient($recipient);

	$mailer->addCc($_REQUEST['client_email']);

	// Added a variable colspan for the mailbody on using prices or sizes yes or no

	$colspan = 2;
	$total = null;

	$mailer->setSubject(JText::_('BIZ_SIMPLE_CART_ORDER_INFO'));
	$body  = '<h2>'.JText::_('BIZ_SIMPLE_CART_ORDER_INFO').'</h2>';
	$body  .=  '<table style="width:100%;">';
	if ($_REQUEST['client_name']) {
	$body  .=  '<tr><td>'.JText::_('BIZ_SIMPLE_CART_CLIENT_NAME').'</td><td>'.$_REQUEST['client_name'].'</td></tr>';
	}
	if ($_REQUEST['client_email']) {
	$body  .=  '<tr><td>'.JText::_('BIZ_SIMPLE_CART_CLIENT_EMAIL').'</td><td>'.$_REQUEST['client_email'].'</td></tr>';
	}
	if ($_REQUEST['client_phone']) {
	$body  .=  '<tr><td>'.JText::_('BIZ_SIMPLE_CART_CLIENT_PHONE').'</td><td>'.$_REQUEST['client_phone'].'</td></tr>';
	}
	$title_note = $pluginParams->get('title_note') ? $pluginParams->get('title_note') : JText::_('BIZ_SIMPLE_CART_CLIENT_NOTE');
	if ($_REQUEST['client_note']) {
	$body  .=  '<tr><td>'.$title_note.'</td><td>'.$_REQUEST['client_note'].'</td></tr>';
	}
	$body  .=  '</table> ';
	$body  .=  '<table style="width:100%;">';
	$body  .=  '<thead><tr>';
	$body  .=  '<td>#</td>';
	$body  .=  '<td>'.JText::_('BIZ_SIMPLE_CART_PRODUCT_TITLE').'</td>';
	if ($pluginParams->get('using_size')=='1'){
		$body  .=  '<td>'.JText::_('BIZ_SIMPLE_CART_PRODUCT_SIZE').'</td>';
	}
	$body  .=  '<td>'.JText::_('BIZ_SIMPLE_CART_PRODUCT_COUNT').'</td>';
	if ($pluginParams->get('using_price')=='1'){
		$body  .=  '<td>'.JText::_('BIZ_SIMPLE_CART_PRODUCT_PRICE').'</td>';
		$body  .=  '<td>'.JText::_('BIZ_SIMPLE_CART_PRODUCT_SUMM').'</td>';
	}
	$body  .=  '</tr></thead> <tbody>';
	$i=0;
	foreach($content_order as $order_item){ 
		$body  .=  '<tr>';
		$body  .=  '<td>'.($i+1).'</td>';
        $body  .=  '<td><a class="order_item_name" href="'.$_SERVER['HTTP_HOST'].$order_item['link'].'">'.$order_item['title'].'</a></td>';
		if ($pluginParams->get('using_size')=='1') {
			$body  .=  '<td>'.$order_item['size'].'</td>';
        	$colspan += 1;
		}    
        $body  .=  '<td>'.$_REQUEST['count'.$i].'</td>';  
		if ($pluginParams->get('using_price')=='1') {
			$body  .=  '<td>'.$order_item['price'].' '.$pluginParams->get('currency').'</td>';
			$body  .=  '<td>'.$order_item['price']*$order_item['count'].' '.$pluginParams->get('currency').'</td>';
        	$colspan += 2;
		}
        $body  .=  '</tr> ';
		$i++;
    	$total=$total+($order_item['price']*$order_item['count']);	
	}
	$body  .=  '<tr><td colspan="'. $colspan. '" style="text-align:right;"><b>'.JText::_('BIZ_SIMPLE_CART_PRODUCT_TOTAL').':&nbsp;</b></td><td>'.$total.' '.$pluginParams->get('currency').'</td></tr>';
	$body  .=  '</tbody></table>';
	$mailer->isHTML(true);
	$mailer->setBody($body);

	$send = $mailer->Send();
	if ($send !== true) {
    	$msg = 'Message was not sent';
		$controller = JControllerLegacy::getInstance('Content');
		$controller->setRedirect($redirect_url,$msg,'message');
		$controller->redirect();
	} else {
		if(!empty($pluginParams->get('cat_for_orders'))) {		
			$article = JTable::getInstance('content');
			$article->title            = JText::_('BIZ_SIMPLE_CART_ORDER').' '.date( 'd-m-Y H:i:s' );
			$article->introtext        = $body;
			$article->catid            = $pluginParams->get('cat_for_orders');
			$article->created          = JFactory::getDate()->toSQL();
			$article->created_by	   = $userid;
			$article->state            = 0;
			$article->access           = 1;
			$article->metadata         = '{"page_title":"","author":"","robots":""}';
			$article->language         = '*';

			// Check to make sure our data is valid, raise notice if it's not.
			if (!$article->check()) {
				JFactory::getApplication()->enqueueMessage();
				return FALSE;
			}

			// Now store the article, raise notice if it doesn't get stored.
			if (!$article->store(TRUE)) {
				JFactory::getApplication()->enqueueMessage();
			return FALSE;
			}
		}
		$redirect_url =  $_SERVER['REQUEST_URI'];
    	$msg = JText::_('BIZ_SIMPLE_CART_ORDER_ACCEPTED');
		$session->clear('content_order');
		$controller = JControllerLegacy::getInstance('Content');
		$controller->setRedirect($redirect_url,$msg,'message');
		$controller->redirect();
	}
