<?php
//no direct access
defined('_JEXEC') or die('Direct Access to this location is not allowed.');

// include the helper file
require_once(dirname(__FILE__).DS.'helper.php');

$user = JFactory::getUser();
   if ($user->guest) { ?>     
      
<div id="notloggediv">
<p>The Partner Center provides Diono dealers instant access to product information and marketing materials when you need them.  You can:</p>
<ul class="diono-ul">
  <li>View and download the latest product information and high resolution images</li>
  <li>View product videos</li>
  <li>Place an order online and immediately get an email copy</li>
  <li>View your order history</li>
  <li>Manage your billing and shipping information</li>
</ul>

<p>Click on the Partner Center New Account Request below to request an account. As soon as your request is approved, you can immediately start using all the great features of the Partner Center.</p>
<p><a href="http://us.dionopartnercenter.com/index.php?option=com_virtuemart&page=shop.registration&Itemid=1&vmcchk=1&Itemid=1">New Account Request</a></p>
</div>

<?php }
   else{ ?>

<div id="loggedindiv">
      <p>You are now logged in.  Check back here for promotions or other Diono news.</p>
      <p>On the left are all the great Diono product categories that you're used to seeing.  As you begin using the site, if there is something that you are unable to find, give us a call and we will get it added.  Car seat instruction manuals and product specification sheets are toward the bottom of each product page.</p>
      <p>We want to make this a handy site for you so feel free to send us suggestions on what else you might need.</p>
      <p>Please note the lead times on new orders for Radian seats. We are working very hard to fill all backorders as quickly as possible. As we move through the year we expect lead times to decrease dramatically. In the meantime, please keep sending in your orders as early as possible so we can continue to fill your stock. We sincerely apologize for the inconvenience!</p>
      <p>Thank you for using Partner Centre.</p>   
      
<?php readcsv("http://ca.dionopartnercenter.com/docs/ca-availability.csv",false); ?>
</div>
<?php } ?>


