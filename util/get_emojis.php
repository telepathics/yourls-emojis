<?php
/*
 * you can update the emoji list by changing the unicode link below
 * then, visit your site's /user/plugins/yourls-emojis/util/get_emojis.php page to run the script
 *
 * most recent 13.1 https://www.unicode.org/emoji/charts/full-emoji-list.html
 */

if( !defined( 'YOURLS_ABSPATH' ) ) die();
require_once __DIR__ . '/../vendor/autoload.php';
/*
 * Last retrieved: April 11, 2021
 */
function get_emojis() {
  $detect_emoji = Emoji\detect_emoji(file_get_contents('https://unicode.org/Public/emoji/13.1/emoji-test.txt'));
  $file = fopen(__DIR__ . '/emojis.txt', 'w+');

  if ( sizeof($detect_emoji) > 0 ) {
    foreach ( $detect_emoji as $emoji ) {
      fwrite($file, $emoji['emoji']);
    }
  }
}
get_emojis();
