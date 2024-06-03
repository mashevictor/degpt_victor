<?php

function generateParagraph($datas, $preSelector)
{
  $getSettings = isset($datas['paragraph']) && $datas['paragraph'];

  $regStyles = '';

  $marginBottom = isset($getSettings['marginBottom']) && $getSettings['marginBottom'];

  $regStyles .= "margin:0;";
  if ($marginBottom) {
    $regStyles .= globalSpacingStyles($getSettings, 'marginBottom', 'margin-bottom', false);
  }
  $regStyles .= globalFontSpecStyles($getSettings, 'fontSpec');
  $regStyles .= globalFontFamilyStyles($getSettings, 'fontFamily');
  $regStyles .= globalCommonStyles($getSettings, 'fontWeight', 'font-weight', false);
  $regStyles .= globalCommonStyles($getSettings, 'fontStyle', 'font-style', false);
  $regStyles .= globalCommonStyles($getSettings, 'textAlign', 'text-align', false);
  $regStyles .= globalDecorationStyles($getSettings, null);
  $regStyles .= globalCommonStyles($getSettings, 'textTransform', 'text-transform', false);
  $regStyles .= globalColorStyles($getSettings, 'color', 'color', false);

  $regStyles = "{$preSelector}p{{$regStyles}}";

  return $regStyles;
}
