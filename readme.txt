=== Payment Gateway for QPayPro on WooCommerce ===
Contributors: XicoOfficial, digitallabs
Donate link: https://digitallabs.agency
Tags: qpaypro, qpaypro guatemala, visanet, visanet guatemala, credomatic, credomatic guatemala, qpaypro, custom gateway, woocommerce payment gateway, qpaypro card, woocommerce, págalo card, pagalo, payment gateway
Requires at least: 4.6
Requires PHP: 5.2.4
Tested up to: 6.5
Stable tag: 1.0.0
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

This plugin allows your store to make payments via QPayProCard service.


== Description ==

= QPayPro - WooCommerce Payment Gateway =

This is a basic pluging to connect your your woocommerce store with [QPayPro](https://www.qpaypro.com/) payment Gateway. If the transaction is successful the order status will be changed to “processing”. If the payment charge failed the order status will be changed to “cancelled”. If something is wrong with the connection between your server and the QPayProCard server the order status will be changed to “on-hold”. After successful transaction the customer is redirected to the default WP thank you page.


You are using the QPayPro for WooCommerce plugin developed by [Digital Labs](https://digitallabs.agency/). If you need assistance configuring the plugin, help with your eCommerce site or just want to say hi, feel free to contact us [here](https://digitallabs.agency/contacto). We will be happy to work with you.

= Support =

Use the wordpress support forum for any questions regarding the plugin, or if you want to improve it.

= Get Involved =

Looking to contribute code to this plugin? Go ahead and [fork the repository over at GitHub](https://github.com/DigitalLabsAgcy/wp-p-qpaypro-woocommerce-test).
(submit pull requests to the latest "release-" tag).

== Usage ==

To start using the "QPayPro - WooCommerce Payment Gateway", first create an account at [QPayPro.com](https://www.qpaypro.com/). They will provide you with your account api keys.

Make sure you take a look at their [Terms and Conditions](https://www.qpaypro.com/politicas-de-privacidad/) to see if their service is a good match for your eCommerce website.

After you have your QPayPro account active:

1. Head to Woocommerce Settings and click on the Checkout tab.
2. On checkout options you should see the option "QPayPro", click on it.
3. Enable the payment gateway byt checking the checkbox that reads "Enable this payment gateway".
4. Fill the form with your account information.
5. Click on save changes and you should be ready to start accepting credit cards with QPayPro Card.

You can use this data to make the testing:
CC Number: 4111-1111-1111-1111 
CVV: 123
Ex date: 08/30

If you are interested in a more custom solution reach out to us at [DigitalLabs.agency](https://digitallabs.agency) and we will be happy to work with you.


== Installation ==

Installing "QPayPro - WooCommerce Payment Gateway" can be done either by searching for "QPayPro - WooCommerce Payment Gateway" via the "Plugins > Add New" screen in your WordPress dashboard, or by using the following steps:
	
1. Download the plugin via WordPress.org.
1. Upload the ZIP file through the "Plugins > Add New > Upload" screen in your WordPress dashboard.
1. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= How do I contribute? =

We encourage everyone to contribute their ideas, thoughts and code snippets. This can be done by forking the [repository over at GitHub](https://github.com/DigitalLabsAgcy/wp-p-qpaypro-woocommerce).

= What key features are missing in this plugin that are already available on the paid version of the plugin? =

The main features that are not included in this free plugin are:
- Support for both modes (Cybersource and ePay).
- Support for Visa Cuotas and Master Cuotas.

== Screenshots ==

1. The QPayPro payment gateway settings page showing the texts and description that can be customized.

2. The QPayPro payment gateway settings page showing the fields that need to be filled with each individual account information.

3. The checkout page with the QPayPro payment credit card form.

== Changelog ==
= 1.0.0 =
* Update api field labels to match api docs
* Add Cleave.js for a more friendly way to fill CC number

= 0.0.1 =
* Integration with QPayProCard to acept credit card payments. 


== Upgrade Notice ==

= 0.0.1 =
* Initial release. Yeah!

