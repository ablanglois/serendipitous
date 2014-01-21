<?php 
require_once(__DIR__.'/textprocessor/TextProcessor.php');

class Serendipitous {
  private $lang;
  private $noLineStart;
  private $noLineEnd;

  public function __construct($lang) {
    $this->lang = $lang;

    $params = include(__DIR__.'/languages/'.$lang.'.php');
    $this->noLineStart = $params['noLineStart'];
    $this->noLineEnd = $params['noLineEnd'];
    $this->pairs = $params['pairs'];
  }

  public function find($text) {
    $textProcessor = new TextProcessor($this->lang);
    $sentences = $textProcessor->sentences($text);
    foreach ($sentences as $sentence) :
      $sentenceSyllableCount = $textProcessor->syllableCount($sentence);
      if ($sentenceSyllableCount == 17 && !preg_match('/[0-9]/', $sentence)) :
        $words = $textProcessor->words($sentence);
        $lines = array(5,7,5);
        foreach ( $lines as &$line ) :
          $meter = $line;
          $line = '';
          $lastWord = '';
          while ( $textProcessor->syllableCount($line) != $meter && count($words) > 0 ) :
            $lastWord = array_shift($words);
            $line .= $lastWord.' ';
          endwhile;
          if ( in_array($lastWord, $this->noLineEnd() ) ) break;
          $line = trim($line);
        endforeach;
        if (strlen($lines[0]) > 1 && strlen($lines[1]) > 1 && strlen($lines[2]) > 1) :
          return $this->match_pairs(implode('<br>',  $lines));
        endif;
      endif;
    endforeach;
  }

  private function match_pairs($haiku) {
    foreach ($this->pairs as $pair) :
      if ( strpos($haiku, $pair[0]) && !strpos($haiku, $pair[1]) ) $haiku .= $pair[1];
      if ( strpos($haiku, $pair[1]) && !strpos($haiku, $pair[0]) ) $haiku = $pair[0].$haiku;
    endforeach;
    return $haiku;
  }
}
?>