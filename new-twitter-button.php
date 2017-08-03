<?php
/**
 * Plugin Name: New Twitter Button
 * Plugin URI: http://www.tailoredinternetmarketing.com/blog/new-twitter-button/
 * Description: The Twitter.com button.
 * Version: 2.1
 * Author: Dan Taylor
 * Author URI: http://www.tailoredinternetmarketing.com/
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
 if (!class_exists("drt_ntb_plugin")) {
    class drt_ntb_plugin {
		
		public function __construct() {
			add_action( 'admin_init', array($this, 'register_drt_ntb_plugin_settings'));
			add_action('admin_menu', array($this, 'add_twitter_button_plugin_submenu'));
			add_action('the_content', array($this, 'twitter_button_output'), 5);
			add_action('the_excerpt', array($this, 'twitter_button_excerpt_output'), 5);
		}
		
		public function register_drt_ntb_plugin_settings() {
			//register our settings
			register_setting( 'twitter-group', 'twitter_button_type1' );
			register_setting( 'twitter-group', 'twitter_button_type2' );
			register_setting( 'twitter-group', 'twitter_button_account' );
			register_setting( 'twitter-group', 'twitter_button_topstyle' );
			register_setting( 'twitter-group', 'twitter_button_bottomstyle' );
			register_setting( 'twitter-group', 'twitter_button_rt' );
			register_setting( 'twitter-group', 'twitter_button_rec' );
		}
		
		function twitter_button_output($out ='') {
	
			global $post;
			
			if(get_option('twitter_button_type1') == 1) { 
				$tweettype = 'none'; 
			} elseif(get_option('twitter_button_type1') == 2) {
				$tweettype = 'horizontal';
			} elseif(get_option('twitter_button_type1')==3) {
				$tweettype = 'vertical';
			} else {
				$tweettype = 'vertical';
			}
			
			if(get_option('twitter_button_type2') == 1) { 
				$tweettype2 = 'none'; 
			} elseif(get_option('twitter_button_type2') == 2) {
				$tweettype2 = 'horizontal';
			} elseif(get_option('twitter_button_type2') ==3) {
				$tweettype2 = 'vertical';
			} else {
				$tweettype2 = 'horizontal';
			}
			if(get_option('twitter_button_account') != '')
			{
				$twittervia	= ' data-via="'.get_option('twitter_button_account').'" ';
			} else {
				$twittervia	= '';
			}
			if(get_option('twitter_button_rt') == 1) {
				$twitter_pt = 'RT @'.get_option('twitter_button_account').' '.the_title('','',false);
				$twittervia	= '';
			} else {
				$twitter_pt = the_title('','',false);
			}
			if(get_option('twitter_button_rec') == 1) {
				$twitter_rec = 'data-related="SEOMoz"';
			} else {
				$twitter_rec = '';
			}
			
			if(get_option('twitter_button_type1') != 4) {
				$headerbut = '
					<div style="'.get_option('twitter_button_topstyle').'">
						<a href="http://twitter.com/share" class="twitter-share-button" data-url="'.get_permalink($post->ID).'" data-text="'.$twitter_pt.'" data-count="'.$tweettype.'" '.$twittervia.' '.$twitter_rec.'>Tweet</a>
						<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
					</div>';
			} else { 
				$headerbut =''; 
			}
			
			if(get_option('twitter_button_type2') != 4) {
				$footerbut = '<div style="'.get_option('twitter_button_bottomstyle').'">
								<a href="http://twitter.com/share" class="twitter-share-button" data-url="'.get_permalink($post->ID).'" data-text="'.$twitter_pt.'" data-count="'.$tweettype2.'" '.$twittervia.' '.$twitter_rec.'>Tweet</a>
								<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
							</div>';
			} else { 
				$footerbut = ''; 
			}
			if(!is_home() && ! is_feed() && is_single()) {	
				return $headerbut.$out.$footerbut;
			} elseif(is_home() || is_single() && !is_feed()) {
				return $headerbut.$out;
			} else {
				return $out;
			}
		}
		
		public function twitter_button_excerpt_output($out ='') {
			global $post;
			$out = substr($out, 6);
			return $out;
		}

		function twitter_button_options() {
			echo '
				<div class="wrap">
					<h2>New Twitter Button Plugin Settings</h2>';
			
			echo'
					<form method="post" action="options.php">';
			settings_fields( 'twitter-group' ); 
			
			$tweetvia1 = get_option('twitter_button_account');
			$twittertops = get_option('twitter_button_topstyle');
			$twitterbots =	get_option('twitter_button_bottomstyle');
			
			if(!isset($twittertops)) { $twittertops = 'float:left; margin-right: 10px; margin-bottom: 10px;'; }
			if(!isset($twitterbots)) { $twitterbots = 'float: right; margin-top: 5px; margin-left: 5px;'; }
			echo'
				<table class="form-table">';
			echo '
			  <tr>
				<th align="left" scope="row"><label for="twitter_button_type1">Top Button Count Display Position</label></th>
				<td>
					<select name="twitter_button_type1" id="twitter_button_type1">
					  <option value="1" '. (get_option('twitter_button_type1') == 1 ? 'selected="selected"' : '') . '>No Count</option>
					  <option value="2" '. (get_option('twitter_button_type1') == 2 ? 'selected="selected"' : '') . '>Horizontal Count</option>
					  <option value="3"'. (get_option('twitter_button_type1') == 3 ? 'selected="selected"' : '') . '>Vertical Count</option>
					  <option value="4"'. (get_option('twitter_button_type1') == 4 ? 'selected="selected"' : '') . '>Don\'t Display</option>
					</select>
				</td>
				
			  </tr>
			  <tr>
				<th align="left" scope="row">Top Button Container Style</th>
				<td><input type="text" name="twitter_button_topstyle" value="'. $twittertops  .'" /><br/><strong>Reccomended:</strong> float:left; margin-right: 10px; margin-bottom: 10px;</td>
			  </tr>
			  <tr>
				<th align="left" scope="row"><label for="twitter_button_type">Bottom Button Count Display Position</label></th>
				<td>
					<select name="twitter_button_type2" id="twitter_button_type2">
					  <option value="1" '. (get_option('twitter_button_type2') == 1 ? 'selected="selected"' : '') . '>No Count</option>
					  <option value="2" '. (get_option('twitter_button_type2') == 2 ? 'selected="selected"' : '') . '>Horizontal Count</option>
					  <option value="3"'. (get_option('twitter_button_type2') == 3 ? 'selected="selected"' : '') . '>Vertical Count</option>
					  <option value="4"'. (get_option('twitter_button_type2') == 4 ? 'selected="selected"' : '') . '>Don\'t Display</option>
					</select>
			  </td>
			  </tr>
			  <tr>
				<th align="left" scope="row">Top Button Container Style</th>
				<td><input type="text" name="twitter_button_bottomstyle" value="'. $twitterbots .'" /><br/><strong>Reccomended:</strong> float: right; margin-top: 5px; margin-left: 5px;</td>
			  </tr>
			  <tr>
				<th align="left" scope="row"><label for="twitter_button_type">Your Twitter Account</label></th>
				<td><input type="text" name="twitter_button_account" value="'.$tweetvia1.'" /><br/>Just account name needed, no \'@\' sign or the twitter address</td>
			  </tr>
			  <tr>
				<th align="left" scope="row"><label for="twitter_button_rt">Use RT instead of /via?</label></th>
				<td><input name="twitter_button_rt" type="checkbox" id="twitter_button_rt" value="1" '.(get_option('twitter_button_rt') == 1 ? 'checked="checked""' : ''). '/></td>
			  </tr>
			<tr>
				<th align="left" scope="row"><label for="twitter_button_rec">Support @DanTaylorSEO by recommending as a follow?</label></th>
				<td><input name="twitter_button_rec" type="checkbox" id="twitter_button_rec" value="1" '.(get_option('twitter_button_rec') == 1 ? 'checked="checked""' : ''). '/></td>
			  </tr>
			</table>';
			submit_button();
			
		}
			
		function add_twitter_button_plugin_submenu() {
			add_submenu_page('options-general.php', 'Twitter Button', 'Twitter Button', 10, __FILE__, array($this, 'twitter_button_options')); 
		}
	}
 }
 
 if (class_exists("drt_ntb_plugin")) {
    $drt_ntb_plugin = new drt_ntb_plugin();
}