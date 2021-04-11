<?php
/*
Plugin Name: Emojis
Description: Create an emoji-only short link, like http://sho.rt/✨ or http://sho.rt/😎🆒🔗
Version: 1.0
Author: telepathics
Author URI: https://telepathics.xyz
*/

if( !defined( 'YOURLS_ABSPATH' ) ) die();
require_once(__DIR__ . '/vendor/autoload.php');
use SteppingHat\EmojiDetector;

/*
 * Accept detected emojis
 */
yourls_add_filter( 'get_shorturl_charset', 'path_emojis_in_charset');
function path_emojis_in_charset($in) {
  return $in . file_get_contents(__DIR__ . '/util/emojis.txt');
}

/*
 * Accepts URLs that are ONLY emojis
 */
yourls_add_filter( 'sanitize_url', 'path_emojis_sanitize_url' );
function path_emojis_sanitize_url($unsafe_url) {
  $clean_url = '';
  $detector = new SteppingHat\EmojiDetector\EmojiDetector();
  $detect_emoji = $detector->detect(urldecode($unsafe_url));

  if( sizeof($detect_emoji) > 0 ) {
    foreach ($detect_emoji as $emoji) {
      $clean_url .= $emoji->getEmoji();
    }
    return $clean_url;
  }
  return $unsafe_url;
}

/*
 * filter wrong spacing whoopsies
 * see @link https://github.com/YOURLS/YOURLS/issues/1303
 */
yourls_add_filter( 'sanitize_url', 'fix_long_url' );
function fix_long_url( $url, $unsafe_url ) {
  $search = array ( '%2520', '%2521', '%2522', '%2523', '%2524', '%2525', '%2526', '%2527', '%2528', '%2529', '%252A', '%252B', '%252C', '%252D', '%252E', '%252F', '%253D', '%253F', '%255C', '%255F' );
  $replace = array ( '%20', '%21', '%22', '%23', '%24', '%25', '%26', '%27', '%28', '%29', '%2A', '%2B', '%2C', '%2D', '%2E', '%2F', '%3D', '%3F', '%5C', '%5F' );
  $url = str_ireplace ( $search, $replace ,$url );
  return yourls_apply_filter( 'after_fix_long_url', $url, $unsafe_url );
}
