<?

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

require_once (dirname(__FILE__).DS.'helper.php');
$tweets = modGetTweetsHelper::getTweets();

?>

<div class="tweet-container">
<div class="twitter-logo"><img src="images/socials/twitter-logo.png" /></div>
<div id="scroll-pane">

<?php
	echo $tweets;	
?>
</div>
</div>

<script type="text/javascript">
	jQuery.noConflict(setSlider(jQuery('#scroll-pane')));
</script>




