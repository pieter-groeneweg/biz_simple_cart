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

header('Content-Type: text/html; charset=utf-8');
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');
$session = JFactory::getSession();
$plugin = JPluginHelper::getPlugin('content','biz_simple_cart');
$pluginParams = new JRegistry($plugin->params);
$content_order = $session->get('content_order');

$lang = JFactory::getLanguage();
$languages = JLanguageHelper::getLanguages('lang_code');
$languageCode = $languages[ $lang->getTag() ]->sef.'/';

// Added a variable colspan for the mailbody on using prices or sizes yes or no
$colspan=2;

if (!empty($_REQUEST['mail']) && empty($_REQUEST['nosend'])){
	include JPluginHelper::getLayoutPath('content', 'biz_simple_cart', 'mail');
}
if(!empty($_REQUEST['nosend'])) {	
	for($i=0;$i<count($content_order);$i++){
		$content_order[$i]['count'] = $_REQUEST['count'.$i];
	}
	$session->set('content_order',  $content_order);
}
?>
<div class="biz_simple_cart">
	<h2 class="title contentheading"><?php echo JText::_('BIZ_SIMPLE_CART_SHOPPING_CART')?></h2>
	<form name="cart" class="order form-inline" method="post" action="<?php echo $_SERVER['REQUEST_URI'] ?>">
		<table style="width:100%;">
			<thead>
				<th>#</th>
				<th><?php echo JText::_('BIZ_SIMPLE_CART_PRODUCT_TITLE')?></th>
				<?php if ($pluginParams->get('using_size')=='1') { ?>
					<th><?php echo JText::_('BIZ_SIMPLE_CART_PRODUCT_SIZE')?></th>
					<?php $colspan += 1 ?>
				<?php } ?>					
				<th><?php echo JText::_('BIZ_SIMPLE_CART_PRODUCT_COUNT')?></th>

				<?php if ($pluginParams->get('using_price')=='1') { ?>
					<th><?php echo JText::_('BIZ_SIMPLE_CART_PRODUCT_PRICE')?></th>
					<th><?php echo JText::_('BIZ_SIMPLE_CART_PRODUCT_SUMM')?></th>
					<?php $colspan += 2 ?>
				<?php } ?>
				<th></th>
			</thead>
			<tbody>
				<?php $i = 0; $total=0; foreach($content_order as $order_item){ ?>
					<tr class="order_item">
						<td><?php echo $i+1 ?></td>
						<td><a class="order_item_name" href="<?php echo $languageCode . $order_item['link'] ?>"><?php echo $order_item['title'] ?></a></td>
						
						<?php if ($pluginParams->get('using_size')=='1') { ?>
						
							<td><?php echo $order_item['size'] ?></td>
							
						<?php } ?>	
						
						<td><input class="bsc-input bsc-count" type="number" name="count<?php echo $i ?>" max="999" min="1"  value="<?php echo $order_item['count'] ?>" onchange="update()" /></td>
						<?php if ($pluginParams->get('using_price')=='1') { ?>
							<td class="money" name="price"><?php echo $pluginParams->get('currency').' '.preg_replace( '/[.,]0{2,}/', ',-', strval(number_format($order_item['price'], 2, JText::_('DECIMALS_SEPARATOR'), '') )) ?></td>
							<td class="money" name="pric2"><?php echo $pluginParams->get('currency').' '.preg_replace( '/[.,]0{2,}/', ',-', strval(number_format(($order_item['price']*$order_item['count']), 2, JText::_('DECIMALS_SEPARATOR'), '') )) ?></td>
							
						<?php } ?>
						<td><a class="bsc-button bsc-primary" href="<?php echo JURI::current().'?delete='.$i ?>"><?php echo JText::_('BIZ_SIMPLE_CART_PRODUCT_DELETE')?></a></td>
					</tr>	
				<?php $i++; $total=$total+($order_item['price']*$order_item['count']);} ?>
				<?php if ($pluginParams->get('using_price')=='1') { ?>
					<tr class="order_total">
						<td colspan="<?php echo $colspan ?>" style="text-align:right;"><b><?php echo JText::_('BIZ_SIMPLE_CART_PRODUCT_TOTAL')?>:&nbsp;</b></td>
						<td class="money"><?php echo $pluginParams->get('currency').' '.preg_replace( '/[.,]0{2,}/', ',-', strval(number_format($total, 2, JText::_('DECIMALS_SEPARATOR'), '') )) ?></td>
						<td></td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
		<?php
		if(!JFactory::getUser()->guest) {
			$userid = JFactory::getUser()->id;
			$useremail = JFactory::getUser()->email;
			$username = JFactory::getUser()->name;
		} else {
			$userid = 1;
			$useremail = '';
			$username = '';
		}
		?>
		<h3 class="bsc-title-data"><?php echo JText::_('BIZ_SIMPLE_CART_CLIENT_DATA')?></h3>
		<div class="bsc-block-data">
			<input class="bsc-input" type="hidden" name="mail" value="1" />
			<?php if ($pluginParams->get('client_name')!='0') { ?>
				<div>
				<input class="bsc-input" type="text" name="client_name" value="<?php echo $username ?>"  size="25" 
				<?php if ($pluginParams->get('client_name')=='2') { ?>
					required="required" aria-required="true" 
				<?php } ?>
				autofocus="" aria-invalid="false" placeholder="<?php echo JText::_('BIZ_SIMPLE_CART_ENTER_NAME')?>" />
				</div>
			<?php } ?>

			<?php if ($pluginParams->get('client_email')!='0') { ?>
				<div>
					<input class="bsc-input" type="email" name="client_email" value="<?php echo $useremail ?>"  size="25" 
					<?php if ($pluginParams->get('client_email')=='2') { ?>
						required="required" aria-required="true" 
					<?php } ?>
					autofocus="" aria-invalid="false" placeholder="<?php echo JText::_('BIZ_SIMPLE_CART_ENTER_EMAIL')?>" validate="email" />
				</div>
			<?php } ?>

			<?php if ($pluginParams->get('client_phone')!='0') { ?>
				<div>
					<input class="bsc-input" type="tel" name="client_phone" value=""  size="25" 
					<?php if ($pluginParams->get('client_phone')=='2') { ?>
						required="required" aria-required="true" 
					<?php } ?>
					autofocus="" aria-invalid="false" placeholder="<?php echo JText::_('BIZ_SIMPLE_CART_ENTER_PHONE')?>"  />
				</div>
			<?php } ?>

			<?php if ($pluginParams->get('client_note')!='0') { ?>
				<div>
					<textarea class="bsc-textarea" name="client_note" value=""
					<?php if ($pluginParams->get('client_note')=='2') { ?>
						required="required" aria-required="true" 
					<?php } ?>
					autofocus="" aria-invalid="false" placeholder="<?php if ($pluginParams->get('title_note')) {echo $pluginParams->get('title_note');} else {echo JText::_('BIZ_SIMPLE_CART_CLIENT_NOTE');} ?>"></textarea>
				</div>
			<?php } ?>
			<div>
				<input type="submit" class="validate bsc-button bsc-primary" value="<?php echo JText::_('BIZ_SIMPLE_CART_TO_ORDER')?>" />
			</div>
		</div> 
	</form>
	<script>
	function update() {
		var x = document.createElement("INPUT");
		x.setAttribute("type", "hidden");
		x.setAttribute("name", "nosend");
		x.setAttribute("value", "yes");
		document.cart.appendChild(x);
		document.cart.submit();
	}
	</script>
</div>