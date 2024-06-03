<?php

function generateSelection($datas, $preSelector)
{
  $theStyles = '';
  $modeArray = [];

  foreach ($datas as $index => $scheme) {
    if (!$index) {
      $theStyles .= globalColorStyles($scheme, 'selectionBackground', 'background-color', false);
      $theStyles .= globalColorStyles($scheme, 'selectionText', 'color', false);
    } else {
      $style = '';
      if (isset($scheme['selectionBackground']) && $scheme['selectionBackground']) {
        $style .= 'background-color:var(--' . $scheme['selectionBackground'] . ');';
      }
      if (isset($scheme['selectionText']) && $scheme['selectionText']) {
        $style .= 'color:var(--' . $scheme['selectionText'] . ');';
      }
      $modeArray[] = [
        "slug" => $scheme['slug'],
        "style" => $style,
      ];
    }
  }
  if ($theStyles) $theStyles = "{$preSelector}::selection{{$theStyles}}";
  foreach ($modeArray as $mode) {
    $theStyles .= $preSelector . '[data-mode="' . $mode['slug'] . '"] ::selection{' . $mode['style'] . '}';
  }
  return $theStyles;
}
