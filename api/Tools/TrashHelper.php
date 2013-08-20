<?php
/**
 * class TrashHelper
 *
 * A collection of tools to make converting various portions of
 * trash talk message insertion simpler.
 */
class TrashHelper
{
  private static $rages = array(
    'allthethings' => 'allthethings.png',
    'areyoukiddingme' => 'areyoukiddingme.png',
    'awthanks' => 'awthanks.png',
    'awyea' => 'awyea.png',
    'badpokerface' => 'badpokerface.png',
    'cereal' => 'cerealguy.png',
    'challengeaccepted' => 'challengeaccepted.png',
    'content' => 'content.png',
    'derp' => 'derp.png',
    'dumb' => 'dumbbitch.png',
    'fap' => 'fap.png',
    'foreveralone' => 'foreveralone.png',
    'freddie' => 'freddie.png',
    'fry' => 'fry.png',
    'fuckyeah' => 'fuckyeah.png',
    'gtfo' => 'gtfo.png',
    'ilied' => 'ilied.png',
    'indeed' => 'indeed.png',
    'jackie' => 'jackie.png',
    'lol' => 'lol.png',
    'megusta' => 'megusta.png',
    'notbad' => 'notbad.png',
    'nothingtodohere' => 'nothingtodohere.png',
    'ohcrap' => 'ohcrap.png',
    'ohgodwhy' => 'ohgodwhy.jpeg',
    'okay' => 'okay.png',
    'omg' => 'omg.png',
    'pokerface' => 'pokerface.png',
    'rage' => 'rageguy.png',
    'sadtroll' => 'sadtroll.png',
    'sweetjesus' => 'sweetjesus.png',
    'troll' => 'troll.png',
    'truestory' => 'truestory.png',
    'wat' => 'wat.png',
    'wtf' => 'wtf.png',
    'yey' => 'yey.png',
    'yuno' => 'yuno.png'
  );

  /**
   * Match any rage face patterns and return the image code
   *
   * @param string $message
   * @return string
   */
  public static function matchRages($message)
  {
    preg_match_all('/\([a-zA-Z]+\)/', $message, $matches);

    foreach($matches[0] as $match)
    {
      $matchSanitized = strtolower(trim($match, '()'));

      if (in_array($matchSanitized, array_keys(self::$rages)) === true)
      {
        $rageImage = '<img class="rage" src="/Assets/images/rages/' . self::$rages[$matchSanitized] . '" alt="' . $matchSanitized . '" title="' . $matchSanitized . '" />';
        $message = str_replace($match, $rageImage, $message);
      }
    }

    return $message;
  }

  /**
   * Match any links/images and return the html markup in the message
   * instead of flat text
   *
   * @param string $message
   * @return string
   */
  public static function matchLinks($message)
  {
    $message = preg_replace_callback('#(?:https?://\S+)|(?:www.\S+)#',
                                     'self::matchImages',
                                     $message );

    return $message;
  }

  /**
   * Preg callback to match images in the trash talk message
   *
   * @param array $arr
   * @return string
   */
  public static function matchImages($arr)
  {
    if ( strpos( $arr[0], 'http://' ) !== 0 )
    {
      $arr[0] = 'http://' . $arr[0];
    }

    $url = parse_url( $arr[0] );

    if ( preg_match( '#\.(png|jpg|gif)$#', $url['path'] ) )
    {
      return '<img class="trashImg" src="' . $arr[0] . '" />';
    }

    else
    {
      return '<a target="_blank" class="trashLink" href="' . $arr[0] . '">' . $arr[0] . '</a>';
    }
  }

  /**
   * Convert \n to <br />
   *
   * @param string message
   * @return string
   */
  public static function convertLineBreaks($message)
  {
    return str_replace("\n", "<br />", $message);
  }

  /**
   * Just a little helper function to do all of the above in one line.
   *
   * @param string $message
   * @return string $message
   */
  public static function processMessage($message)
  {
    $message = self::matchLinks($message);
    $message = self::matchRages($message);
    $message = self::convertLineBreaks($message);
    return $message;
  }
}