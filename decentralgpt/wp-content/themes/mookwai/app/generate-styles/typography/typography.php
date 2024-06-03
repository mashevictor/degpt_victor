<?php

function typographyStyles($setting)
{
  $theStyles = '';
  $foundIndent = null;
  $foundAlign = null;
  $foundBreak = null;
  $foundWhite = null;

  // Get Datas
  foreach ($setting as $item) {
    switch ($item['property']) {
      case 'textIndent':
        $foundIndent = $item;
        break;
      case 'textAlign':
        $foundAlign = $item;
        break;
      case 'wordBreak':
        $foundBreak = $item;
        break;
      case 'whiteSpace':
        $foundWhite = $item;
        break;
    }
  }

  $theStyles .= generateSizingStyles($foundIndent, 'text-indent', null, null, null, null, false);
  $theStyles .= generateCommonStyles($foundAlign, 'text-align', false);
  $theStyles .= generateCommonStyles($foundBreak, 'word-break', false);
  $theStyles .= generateCommonStyles($foundWhite, 'white-space', false);

  return $theStyles;
}
