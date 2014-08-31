<?
/**
 * Get Tweets helper
 * 
 * @package    	mod_gettweets
 * @subpackage 	modules
 * @link 				http://www.bradhaas.net
 * @license			GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );


/**
 *  Helper
 */
class modGetTweetsHelper

{
	/**
	 *	Method to get the tweets
	 *
	 *	@access		public
	 *
	 */
	 
	function getTweets() {

		require 'includes/tmhOAuth.php';
		require 'includes/tmhUtilities.php';
		
		$doc = JFactory::getDocument(); 
		$basepath = '/modules/mod_get_tweets/';
		
		$doc->addStyleSheet($basepath.'css/tweets.css');
		$doc->addScript('http://code.jquery.com/ui/1.10.2/jquery-ui.js');
		$doc->addScript($basepath.'includes/jquery-ui-draggable.js');
		$doc->addScript($basepath.'includes/get-tweets.js');
		
		$tmhOAuth = new tmhOAuth(array(
		 'consumer_key' => 'nBz45ixJBIFFj3HHLvVM8w',
		 'consumer_secret' => 'U8cSdLNYSr84wAjKKh15vxJjF7liMzNXMZ1ZlHPZyo',
		 'user_token' => '360144943-sleCaLNUEkzBQSjuJypkJmlhoDCS5ks0NsUbSv6p',
		 'user_secret' => '311p4XH9O1FXLZulUJGdu3vRNi5fimPAja4YOj7hfs',
		 'curl_ssl_verifypeer' => false
		));
		
		$code = $tmhOAuth->request('GET', $tmhOAuth->url('1.1/statuses/user_timeline'), array(
		 'screen_name' => 'dionousa',
		 'count' => '20'));
		
		$response = $tmhOAuth->response['response'];
		$tweets = json_decode($response, true);
		
		//print_r($tweets);
		
		$html ='';
		foreach($tweets as $key => $value){
			
			$tweet = ($value['text']);	
			
			//$tweet = preg_replace("/([\w]+\:\/\/[\w-?&;#~=\.\/\@]+[\w\/])/", "<a target=\"_blank\" href=\"$1\">$1</a>", $tweet);
			//$tweet = preg_replace("/#([A-Za-z0-9\/\.]*)/", "<a target=\"_new\" href=\"http://twitter.com/search?q=$1\">#$1</a>", $tweet);
			//$tweet = preg_replace("/@([A-Za-z0-9\/\.]*)/", "<a href=\"http://www.twitter.com/$1\">@$1</a>", $tweet);
			//echo "<br /><br />";
			
			$replacement = '';
			
			//print_r($tweet);
			$pattern = '/([\w]+\:\/\/[\w-?&;#~=\.\/\@]+[\w\/])/';
			preg_match($pattern, $tweet, $matches, PREG_OFFSET_CAPTURE, 1);
			$link = $matches[0][0];
			$tweet = preg_replace ( $pattern , $replacement , $tweet );
			
			// twitter searches
			$pattern = '/#([A-Za-z0-9\/\.]*)/';
			preg_match($pattern, $tweet, $matches, PREG_OFFSET_CAPTURE, 1);
			$search = $matches[0][0];
			$tweet = preg_replace ( $pattern , $replacement , $tweet );
			
			// link searches
			$pattern = '/@([A-Za-z0-9\/\.]*)/';
			preg_match($pattern, $tweet, $matches, PREG_OFFSET_CAPTURE, 1);
			$hash = $matches[0][0];
			$tweet = preg_replace ( $pattern , $replacement , $tweet );
			
			
			$html .= "<div class='tweetwrapper'>";
			$html .= "<div class='tweet'>";
			$html .= "<span class='tweettext'>"  . $tweet . "</span>";
			
			if($description){
				$html .= "<span class='description'>" . $description . "</span>";
			}
			
			if($link){
				$html .= "<span><a href='".$link."' target='_blank' class='link'>" . $link . "</a></span>";
			}
			
			if($hash){
				$html .= "<span><a href='http://twitter.com/search?q=".$hash."' target='_blank' class='tag'>".$hash."</a></span>";
			}
			
			$html .= "</div>";
			$html .= "</div>";
		}
		return $html;
		
	}
}
	 
?>