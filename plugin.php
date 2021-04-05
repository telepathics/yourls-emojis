<?php
/*
Plugin Name: YOURLS Emojis
Description: Allows emojis in the custom short URLs
Version: 1.0
Author: telepathics test
Author URI: https://telepathics.xyz
*/

if( !defined( 'YOURLS_ABSPATH' ) ) die();
require_once __DIR__ . '/vendor/autoload.php';

/*
 * Accept detected emojis
 */
yourls_add_filter( 'get_shorturl_charset', 'path_emojis_in_charset');
function path_emojis_in_charset($in) {
  $available = '';
  $detect_emoji = Emoji\detect_emoji(file_get_contents('https://unicode.org/Public/emoji/13.1/emoji-test.txt'));

  if ( sizeof($detect_emoji) > 0 ) {
    foreach ( $detect_emoji as $emoji ) {
      $available .= $emoji['emoji'];
    }
  }

  return $in.$available;
}

/*
 * Accepts URLs that are ONLY emojis
 */
yourls_add_filter( 'sanitize_url', 'path_emojis_sanitize_url' );
function path_emojis_sanitize_url($unsafe_url) {
  $clean_url = '';
  $detect_emoji = Emoji\detect_emoji(urldecode($unsafe_url));

  if( sizeof($detect_emoji) > 0 ) {
    foreach ($detect_emoji as $emoji) {
      $clean_url .= $emoji['emoji'];
    }
    return $clean_url;
  }
  return $unsafe_url;
}
