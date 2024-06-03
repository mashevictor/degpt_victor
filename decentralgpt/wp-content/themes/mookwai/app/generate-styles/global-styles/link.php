<?php

function generateLink($datas, $preSelector, $colorSchemeData)
{
  $getSettings = $datas['link'];
  $selector = '';
  if ($preSelector) $selector = '.editor-styles-wrapper';

  $regStyles = '';
  $hoverStyles = '';
  $modeArray = [];

  $regStyles .= globalFontFamilyStyles($getSettings, 'fontFamily');
  $regStyles .= globalCommonStyles($getSettings, 'fontWeight', 'font-weight', false);
  $regStyles .= globalCommonStyles($getSettings, 'fontStyle', 'font-style', false);
  $regStyles .= globalCommonStyles($getSettings, 'textAlign', 'text-align', false);
  $regStyles .= globalDecorationStyles($getSettings, null);
  $regStyles .= globalCommonStyles($getSettings, 'textTransform', 'text-transform', false);

  $hoverStyles .= globalFontFamilyStyles($getSettings, 'hoverFontFamily');
  $hoverStyles .= globalCommonStyles($getSettings, 'hoverFontWeight', 'font-weight', false);
  $hoverStyles .= globalCommonStyles($getSettings, 'hoverFontStyle', 'font-style', false);
  $hoverStyles .= globalCommonStyles($getSettings, 'hoverTextAlign', 'text-align', false);
  $hoverStyles .= globalDecorationStyles($getSettings, 'hover');
  $hoverStyles .= globalCommonStyles($getSettings, 'hoverTextTransform', 'text-transform', false);
  $hoverStyles .= globalColorStyles($getSettings, 'hoverColor', 'color', false);

  foreach ($colorSchemeData as $index => $scheme) {
    if (!$index) {
      $regStyles .= globalColorStyles($scheme, 'highlight', 'color', false);
      $hoverStyles .= globalColorStyles($scheme, 'mouseHover', 'color', false);
    } else {
      $regStyle = '';
      $hoverStyle = '';
      if (isset($scheme['highlight']) && $scheme['highlight']) {
        $regStyle .= 'color:var(--' . $scheme['highlight'] . ');';
      }
      if (isset($scheme['mouseHover']) && $scheme['mouseHover']) {
        $hoverStyle .= 'color:var(--' . $scheme['mouseHover'] . ');';
      }
      $modeArray[] = [
        "slug" => $scheme['slug'],
        "regStyle" => $regStyle,
        "hoverStyle" => $hoverStyle,
      ];
    }
  }

  if ($regStyles) $regStyles = "{$selector} a:not(.mk-button){{$regStyles}}";
  if ($hoverStyles) $hoverStyles = "{$selector} a:not(.mk-button):hover{{$hoverStyles}}";

  foreach ($modeArray as $mode) {
    if ($mode['regStyle']) $regStyles .= $selector . '[data-mode="' . $mode['slug'] . '"] a:where(:not(.mk-button)){' . $mode['regStyle'] . '}';
    if ($mode['hoverStyle']) $hoverStyles .= $selector . '[data-mode="' . $mode['slug'] . '"] a:where(:not(.mk-button)):hover{' . $mode['hoverStyle'] . '}';
  }

  return [
    'reg' => $regStyles,
    'hover' => $hoverStyles,
  ];
}
