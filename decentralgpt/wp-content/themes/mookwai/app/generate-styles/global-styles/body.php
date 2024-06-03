<?php

function generateBody($datas, $isEditor, $colorSchemeData)
{
  $theSelector = 'body';
  $modeArray = [];
  if ($isEditor) {
    $theSelector = '.editor-styles-wrapper';
  }

  $generalDatas = $datas['general'];
  $cursorDatas = $datas['cursor'];

  $fontSpec = $generalDatas && isset($generalDatas['fontSpec']) ? $generalDatas['fontSpec'] : null;
  $fontFamily = $generalDatas && isset($generalDatas['fontFamily']) ? $generalDatas['fontFamily'] : null;
  $fontWeight = $generalDatas && isset($generalDatas['fontWeight']) ? $generalDatas['fontWeight'] : null;
  $cursor = null;
  if($cursorDatas && isset($cursorDatas['cursor']) && $cursorDatas['cursor'] === 'custom' && isset($cursorDatas['customCursor'])) {
    $cursor = $cursorDatas['customCursor'];
  } else if ($cursorDatas && isset($cursorDatas['cursor']) && $cursorDatas['cursor'] !== 'custom') {
    $cursor = $cursorDatas['cursor'];
  }
  $regStyles = '';
  $lgStyles = '';
  $mdStyles = '';
  if ($isEditor) {
    $regStyles .= "margin:0;padding:0;";
  } else {
    $regStyles .= "margin:0;padding:0;overflow-x:hidden!important;overflow-y:scroll!important;-webkit-overflow-scrolling:auto;overflow-scrolling:auto;width:100%;";
  }
  if ($isEditor) $regStyles .= "padding-bottom:0!important;";
  if ($fontSpec) {
    $regStyles .= globalFontSpecStyles($generalDatas, 'fontSpec');
  } else {
    $regStyles .= "font-size:16px;line-height:30px;";
    $lgStyles .= "font-size:18px;line-height:32px;";
    $mdStyles .= "font-size:15px;line-height:27px;";
  }
  if ($fontFamily) {
    $regStyles .= globalFontFamilyStyles($generalDatas, 'fontFamily');
  } else {
    $regStyles .= "font-family:sans-serif;";
  }
  if ($fontWeight) {
    $regStyles .= globalCommonStyles($generalDatas, 'fontWeight', 'font-weight', false);
  } else {
    $regStyles .= "font-weight:300;";
  }
  $regStyles .= globalCommonStyles($generalDatas, 'fontStyle', 'font-style', false);
  $regStyles .= globalCommonStyles($generalDatas, 'textAlign', 'text-align', false);
  $regStyles .= globalDecorationStyles($generalDatas, null);
  $regStyles .= globalCommonStyles($generalDatas, 'textTransform', 'text-transform', false);
  foreach ($colorSchemeData as $index => $scheme) {
    if (!$index) {
      $regStyles .= globalColorStyles($scheme, 'background', 'background', false);
      $regStyles .= globalColorStyles($scheme, 'text', 'color', false);
    } else {
      $style = '';
      if (isset($scheme['background']) && $scheme['background']) {
        $style .= 'background:var(--' . $scheme['background'] . ');';
      }
      if (isset($scheme['text']) && $scheme['text']) {
        $style .= 'color:var(--' . $scheme['text'] . ');';
      }
      $modeArray[] = [
        "slug" => $scheme['slug'],
        "style" => $style,
      ];
    }
  }
  if($cursor && !$isEditor) {
    $regStyles .= 'cursor:' . $cursor . ';';
  }
  $regStyles .= "-webkit-text-size-adjust:none;-moz-text-size-adjust:none;text-size-adjust:none;-moz-osx-font-smoothing:grayscale;-webkit-font-smoothing:antialiased;";

  $regStyles = "{$theSelector}{{$regStyles}}";
  $regStyles .= "{$theSelector}.modal-open{overflow:hidden!important;}";
  if ($lgStyles) {
    $lgStyles = "{$theSelector}{{$lgStyles}}";
  }
  if ($mdStyles) {
    $mdStyles = "{$theSelector}{{$mdStyles}}";
  }
  foreach ($modeArray as $mode) {
    $regStyles .= $theSelector . '[data-mode="' . $mode['slug'] . '"]{' . $mode['style'] . '}';
  }
  return [
    'reg' => $regStyles,
    'lg' => $lgStyles,
    'md' => $mdStyles,
  ];
}
