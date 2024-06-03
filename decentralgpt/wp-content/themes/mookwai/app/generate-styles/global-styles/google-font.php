<?php

function GlobalStylesGoogleFont($preSelector, $datas)
{
  $stylesReg = '';
  if ($preSelector) return $stylesReg;
  $googleFont = $datas['googleFont'];
  if (!$googleFont) return $stylesReg;
  foreach ($googleFont as $item) {
    $the_code = $item['embedCode'];
    $stylesReg .= "@import url('$the_code');";
  }
  return $stylesReg;
}
