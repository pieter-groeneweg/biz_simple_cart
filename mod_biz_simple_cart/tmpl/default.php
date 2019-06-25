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
$doc = JFactory::getDocument();

if ($params->get('enable_css', 1)) {
	$doc->addStyleSheet(JUri::root().'plugins/content/biz_simple_cart/assets/css/biz_simple_cart.css', array('version' => 'auto'));
}
$plugin = JPluginHelper::getPlugin('content','biz_simple_cart');
$pluginParams = new JRegistry($plugin->params);
$session = JFactory::getSession();
if($pluginParams->get('mymenuitem')){
	$cart_url = JRoute::_("index.php?Itemid=".$pluginParams->get('mymenuitem'));
} else {
	$order = $session->get('content_order');
	$cart_url = $order[0]['link'].'?cart=1';

	$lang = JFactory::getLanguage();
	$languages = JLanguageHelper::getLanguages('lang_code');
	$languageCode = $languages[ $lang->getTag() ]->sef.'/';

	if($languageCode) {

		$pattern = "/^.?[a-z]{0,2}\//i";
		$cart_url = $languageCode . preg_replace($pattern, "", $cart_url, 1);
	
	}
}
if ($session->get('content_order')) {
?>
<div class="content_cart biz_simple_cart"> 
	<div class="content_cart_info" style="display:none;"> 
	<?php 
        $i = 0; 
        $total=0; 
        foreach($session->get('content_order') as $order_item){ 
        	$order_item['title'];
        	$order_item['link'];

        	$order_item['price'];
        	$order_item['count'];
        	$i++; 
        	$total=$total+($order_item['price']*$order_item['count']);} 
	?>
	</div>
	<p class="count"><span><?php echo JText::_('BIZ_SIMPLE_CART_PRODUCTS_COUNT')?>: </span><span><?php echo ' '.count($session->get('content_order')); ?> </span></p>
	<p class="total"><span><?php echo JText::_('BIZ_SIMPLE_CART_PRODUCT_TOTAL')?>: </span><span><?php echo ' '.$pluginParams->get('currency') . ' ' . preg_replace( '/[.,]0{2,}/', ',-', strval(number_format($total, 2, JText::_('DECIMALS_SEPARATOR'), '') )) ; ?> </span></p>
	<a class="bsc-button bsc-success" title="" href="<?php echo $cart_url ?>"><?php echo JText::_('BIZ_SIMPLE_CART_GO_TO_CART')  ?></a>
</div>
<?php } else { ?>
<div class="content_cart biz_simple_cart" > 
	<p class="count"><span><?php echo JText::_('BIZ_SIMPLE_CART_PRODUCTS_COUNT')?>: </span><span><?php echo ' 0 ' ?> </span></p>
	<p class="total"><span><?php echo JText::_('BIZ_SIMPLE_CART_PRODUCT_TOTAL')?>: </span><span><?php echo $pluginParams->get('currency') . ' ' . preg_replace( '/[.,]0{2,}/', ',-', strval(number_format(0, 2, JText::_('DECIMALS_SEPARATOR'), '') )) ; ?> </span></p>
	<a onclick="return false" class="bsc-button bsc-primary" title="" href="<?php echo $cart_url ?>"><?php echo JText::_('BIZ_SIMPLE_CART_EMPTY_CART')?></a>
</div>
<?php } ?>