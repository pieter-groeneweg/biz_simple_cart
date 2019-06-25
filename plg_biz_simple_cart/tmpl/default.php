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

ini_set('session.cache_limiter','public');
session_cache_limiter(false);

JHtml::_('jquery.framework');
$doc = JFactory::getDocument();

if($this->params->get('mymenuitem')){
	$cart_url = JRoute::_("index.php?Itemid=".$this->params->get('mymenuitem'));
} else {
	$content_order = $session->get('content_order');
	$cart_url = $content_order[0]['link'].'?cart=1';
}
if($this->params->get('enable_css')=='1') {
	$doc->addStyleSheet(JUri::root().'plugins/content/biz_simple_cart/assets/css/biz_simple_cart.css', array('version' => 'auto'));
}

// Added conditions to allow update count of articles and allow sizes used on a single article.
// Added a preg replace pattern to strip the language code from the URL -> needs improvement.

if($link != $cart_url) {

?>
	<div class="biz_simple_cart">
		<form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post">
			<input type="hidden" name="add" value="1" />
			<input type="hidden" name="article_id" value="<?php echo $row->id ?>" /> 
			<input type="hidden" name="title" value="<?php echo $row->title ?>" />
			<input type="hidden" name="link" value="<?php echo preg_replace('/^.?[a-z]{0,2}\//i', '', $link, 1) ?>" />

			<?php 
				// check for the use of sizes and if there are sizes available.
				if ($this->params->get('using_size') == '1' && $row->jcfields[$this->params->get('size_id')]->value) { ?>
				
				<span><?php echo JText::_('BIZ_SIMPLE_CART_PRODUCT_SIZE')?>:</span>
				<?php 
				// Create selection list
				$sizes = explode(',',$row->jcfields[$this->params->get('size_id')]->value);
				echo "<select class='bsc-input bsc-count' name='size'>";
					foreach($sizes as $size):
					echo '<option value="'.$size.'">'.$size.'</option>'; 
					endforeach;
				echo "</select>";
				?>
				
			<?php } ?>
			<span><?php echo JText::_('BIZ_SIMPLE_CART_NUMBER_OF_ITEMS')?>:</span>
			<input type="number" name="count" max="999" min="1" value="1" class="bsc-input bsc-count">



       		<?php 
				// Added a preg_replace for the use of the proper decimal delimiter on the front end
				if ($this->params->get('using_price') == '1' && $row->jcfields[$this->params->get('price_id')]->value) { ?>
        		<span><?php echo JText::_('BIZ_SIMPLE_CART_PRODUCT_PRICE')?>:</span>
        		<span class"price"><?php echo ($this->params->get('currency').' '.preg_replace( '/[.,]0{2,}/', ',-', strval(number_format($row->jcfields[$this->params->get('price_id')]->value, 2, JText::_('DECIMALS_SEPARATOR'),'')))); ?></span>
        		<span><?php echo JText::_('BIZ_SIMPLE_CART_EACH')?></span>
        	<?php } ?>


       
			<input type="submit" class="bsc-button bsc-primary" value="<?php echo JText::_('BIZ_SIMPLE_CART_ADD_TO_CART')?>" />
		</form>
	</div>
<?php } 
elseif (!JFactory::getApplication()->input->getInt('cart')  && $link != $cart_url) { ?>
	<div class="to-cart"><a class="bsc-button bsc-success" href="<?php echo $cart_url;?>"><?php echo JText::_('BIZ_SIMPLE_CART_GO_TO_CART')?></a></div>
<?php } ?>

