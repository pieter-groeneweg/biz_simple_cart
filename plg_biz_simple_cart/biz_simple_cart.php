<?php
// No direct access
defined( '_JEXEC' ) or die;

/**
 * Biz Simple Cart
 *
 * @version 	0.0.1
 * @author		Bizgo.nl 
 * @copyright	(C) 2019, bizgo.nl. All rights reserved.
 * @license 	GNU General Public License version 2 or later; see	LICENSE.txt
 * Forked from Joomline ContentCart
 */
 
class plgContentbiz_simple_cart extends JPlugin
{
	/**
	 * Class Constructor
	 * @param object $subject
	 * @param array $config
	 */
	public function __construct( & $subject, $config )
	{
		parent::__construct( $subject, $config );
		$this->loadLanguage();
	}
	
	public function onContentAfterDisplay($context, &$row, &$params, $page = 0){
	$session = JFactory::getSession();
	if (JFactory::getApplication()->input->getInt('delete') !== NULL) {
			$content_order = $session->get('content_order');
			unset($content_order[JFactory::getApplication()->input->getInt('delete')]);
			sort($content_order);
			$session->set('content_order',  $content_order);
			JFactory::getApplication()->redirect($_SERVER['HTTP_REFERER'], 301);
		}
	if (!$row->text) {return;}
	if (($this->params->get('category_filtering_type')=='0' && in_array($row->catid,$this->params->get('catid'))) or ($this->params->get('category_filtering_type')=='1' && !in_array($row->catid,$this->params->get('catid')))) {return;}
		if (!in_array($context,$this->params->get('application_area'))) {return;}
		$link = JRoute::_(ContentHelperRoute::getArticleRoute($row->slug, $row->catid, $row->language));
		
		if (!empty($_REQUEST['add'])) {
			$msg = '';
			if($session->get('content_order')) {
				$content_order=$session->get('content_order');
            }
        	else {
            	$content_order=null;
            }
			// Check for already existing articles, uid is unique id needed when using sizes.
			if(count($content_order)==0) {
				$unique_line=0;
			}
			elseif(array_search($row->id.'-'.$_REQUEST['size'], array_column($session->get('content_order', array()), 'uid'))===false) {
				$unique_line=count($content_order); // Unique_line is always plus one since count starts at zero.
			}
			else {
				$unique_line = array_search($row->id.'-'.$_REQUEST['size'], array_column($session->get('content_order', array()), 'uid'));
			}
			//
			if (!empty($content_order[$unique_line]['count'])) {
				$count=$_REQUEST['count'] + $content_order[$unique_line]['count'];
			}
			else {
				$count=$_REQUEST['count'];
			}
			// Since visitors can change the value by using console (F12) in browsers, moved calculated pricing here to avoid hijacking of the value
			if ($this->params->get('using_price') == '1') {
				$price = $row->jcfields[$this->params->get('price_id')]->value;
			}
			else {
				$price = '';
			}
			// Added new values to the Array.
	
			$content_order[$unique_line]=array('uid'=>$_REQUEST['article_id'].'-'.$_REQUEST['size'], 'article_id'=>$_REQUEST['article_id'], 'title'=>$_REQUEST['title'],'size'=>$_REQUEST['size'], 'link'=>$_REQUEST['link'], 'count'=>$count, 'price'=>$price);
			$msg = JText::_('BIZ_SIMPLE_CART_ADDED');
			$session->set('content_order',  $content_order);
			$application = JFactory::getApplication();
			$application->enqueueMessage($msg, 'message');
		}
			ob_start();
			include JPluginHelper::getLayoutPath('content', 'biz_simple_cart', 'default');
			$html = ob_get_clean(); 
			return $html;
	}
    public function onContentPrepare($context, $article, $params, $page = 0)
    {
		if ($context != 'com_content.article') {
            return;
        }
        $app  = JFactory::getApplication();
        $session = JFactory::getSession();
        $cart_url = JRoute::_("index.php?Itemid=" . $this->params->get('mymenuitem'));
        $link = JRoute::_(ContentHelperRoute::getArticleRoute($article->slug, $article->catid, $article->language));
		
		if ($app->input->getInt('cart', 0) == 0 && $link != $cart_url) {
            return;
        }

        if ($session->get('content_order'))
        {
            $template = $app->getTemplate();
            $view = JControllerLegacy::getInstance('Content')->getView('article', JFactory::getDocument()->getType());

            $basePath = JPATH_ROOT . '/plugins/content/biz_simple_cart/tmpl/';
            if(is_file(JPATH_ROOT.'/templates/'.$template.'/html/plg_content_biz_simple_cart/cart.php')){
                $basePath = JPATH_ROOT.'/templates/'.$template.'/html/plg_content_biz_simple_cart/';
            }

            $view->addTemplatePath($basePath);
            $view->setLayout('cart');
            if (!$this->params->get('mymenuitem')) {
                $doc = JFactory::getDocument();
                $doc->setTitle(JText::_('BIZ_SIMPLE_CART_SHOPPING_CART'));
            }
        }
        elseif ($link != $cart_url)
        {
            JFactory::getApplication()->redirect($link, 301);
        }
    }
}
