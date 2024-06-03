<?php

function counterStyles($setting)
{
  // Initialize
  $theStyles = '';
  $foundSet = null;
  $foundReset = null;
  $foundIncrement = null;

  // Get Datas
  foreach ($setting as $item) {
    switch ($item['property']) {
      case 'set':
        $foundSet = $item;
        break;
      case 'reset':
        $foundReset = $item;
        break;
      case 'increment':
        $foundIncrement = $item;
        break;
    }
  }

  // Generate
  if ($foundSet) {
    $the_value = $foundSet['custom'];
    $important = $foundSet['important'] ? '!important' : '';
    $theStyles .= 'counter-set:' . $the_value . $important . ';';
  }
  if ($foundReset) {
    $the_value = $foundReset['custom'];
    $important = $foundSet['important'] ? '!important' : '';
    $theStyles .= 'counter-reset:' . $the_value . $important. ';';
  }
  if ($foundIncrement) {
    $the_value = $foundIncrement['custom'];
    $important = $foundSet['important'] ? '!important' : '';
    $theStyles .= 'counter-increment:' . $the_value . $important . ';';
  }

  return $theStyles;
}
