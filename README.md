# Content Cart
A fork from joomline/contentcart https://github.com/Joomline/contentcart

A simple shopping cart where you can sell your article as a product in Joomla! using custom fields to set price and sizes

## History
In search for a very simple shopping cart, I kept bumping into more complicated shopping systems and ecommerce extensions than I needed for the purpose. Of course the aim for these eCommerce systems is professional stores that want to offer a 24/7 available service with accurate stocks and online payment, ordered today, delivered tomorrow. 
There hardly is anything that suits the small organisations that do not have a pro staff that can do all this.

Then I stumbled upon joomline/contentcart. Simple, clean and no hassle. Of course I needed some specific features that where not in this plugin. I needed the possibility to add sizes. I needed the possibility to have it multilingual. It needed to be GDPR compliant in the most simple and effective way.
It would make too many changes to the original. Therefore I decided to fork.

## New features implemented
- add the feature to allow sizes in the article.
- make sure the price set, cannot be tempered with in the form fields.
- GDPR compliancy of the system. The proces does not require one to be logged in. Never store the order in DB, do not store customer in DB.
- add Dutch Language.
- multilingual routing (needs improvement)
- change decimal separator based on languages set in Joomla!
- add reference numbers to emails sent. Hostname and unix date time stamp.

## New features planned
- 

## Changes
- removed the "add to menu" from the parameters. There is no need and added 'trouble' to the multilingual requirements of sites.
- made some changes to untranslated messages and texts.
- I am so sorry, I do not speak Russian, I removed the russian translation.
- name and email address are always required. Practically, there is no way to do without. It is needed at the minimum to contact the buyer. Removed from the parameters.

# Setup
Install as any other extension

Before publishing do the following if required.
- Create a field group "Shop".
- In Content - Custom Fields, create a text field "Price" to your desired content category/categories. 
- In Content - Custom Fields, create a text field "Sizes" to your desired content category/categories.
Remember the ID's

In the setup of the plugin.
- If you enable "Using Price?" add the ID of Custom Field "Price".
- If you enable "Using Size?" add the ID of Custom Field "Size".
- Currency: add your desired currency. Currency Sign or Code will both do. (ie. € or EUR)
- Filtering: Select Inclusive to Include the Selected Categories, Exclusive to Exclude the Selected Categories.
- Categories: Select the desired category/categories.
- Application Area: choose where to show the button. Article, Category Blog and/or Featured Articles.
- Enable CSS: use the stylesheet provided (Yes) or your own (No).
- Shopkeeper: add the email address of the Shopkeeper (required).

Publish the plugin
Publish the module in a position you want it to see. Note; if enabled, it is using the same CSS as provided in the plugin.

Create an article in the category as you are used to do with any other article in Joomla!. In Tab "Shop", enter a price value in the Price field. Also enter a comma separated list of sizes in the Size field. (ie. "XS,S,M,L,XL")
Publish and save.

Your article is ready for sale.

# Multilingual Setup
In order to set this up for a multilingual site, use following plugin: n3t Language Filter (https://extensions.joomla.org/extension/n3t-language-filter/) or alike.

For the articles in your shop; 
- create your categories as "all languages" category.
- create your articles as "all languages" article.
- use the "n3t Language Filter" plugin features to add the texts.
- create a menu item for the desired category/categories in an "all languages" menu. Set the menu item to your article(s) to "all languages".
- create a module on the desired module position for this menu, set to "all languages".

The reason for this is that the name of your article is leading. That would mean that when you have two languages in your Joomla! site, you will usually create an article in language 1, another in language 2. That usually is no problem but the shopping cart sees these as two different products. This is avoided by creating "all language" articles. In the code, it strips any language code from the urls to add the language code of the current view of the site.

# Your Imagination
It is not limited to the above. Of course you may use your imagination to set this up in any other way you like.. ;) 
