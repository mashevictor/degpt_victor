<?php

function generateHeading($datas, $preSelector)
{
  $getSettings = isset($datas['heading']) ? $datas['heading'] : null;

  $theStyles = '';
  $theStyles .= "margin-top:0;margin-bottom:1em;";
  $theStyles .= globalFontFamilyStyles($getSettings, 'fontFamily');
  $theStyles .= globalCommonStyles($getSettings, 'fontWeight', 'font-weight', false);
  $theStyles .= globalCommonStyles($getSettings, 'fontStyle', 'font-style', false);
  $theStyles .= globalCommonStyles($getSettings, 'textAlign', 'text-align', false);
  $theStyles .= globalDecorationStyles($getSettings, null);
  $theStyles .= globalCommonStyles($getSettings, 'textTransform', 'text-transform', false);
  $theStyles .= globalColorStyles($getSettings, 'color', 'color', false);

  $theStyles = "{$preSelector}h1,{$preSelector}h2,{$preSelector}h3,{$preSelector}h4,{$preSelector}h5,{$preSelector}h6{{$theStyles}}";
  $theStyles .= generateHeadingSingle($getSettings, 'h1', $preSelector, '3.5em', '1.25em');
  $theStyles .= generateHeadingSingle($getSettings, 'h2', $preSelector, '1.875em', '1.4em');
  $theStyles .= generateHeadingSingle($getSettings, 'h3', $preSelector, '1.375em', '1.6em');
  $theStyles .= generateHeadingSingle($getSettings, 'h4', $preSelector, '1.2em', '1.789em');
  $theStyles .= generateHeadingSingle($getSettings, 'h5', $preSelector, '1em', '1.875em');
  $theStyles .= generateHeadingSingle($getSettings, 'h6', $preSelector, '0.875em', '2em');
  return $theStyles;
}

function generateHeadingSingle($setting, $tag, $preSelector, $size, $height)
{
  $theStyles = '';
  $fontSpecName = $tag . 'Spec';
  $marginTopName = $tag . 'MarginTop';
  $marginBottomName = $tag . 'MarginBottom';
  $getFontSpce = isset($setting[$fontSpecName]) ? $setting[$fontSpecName] : null;
  if ($getFontSpce) {
    $theStyles .= globalFontSpecStyles($setting, $fontSpecName);
  } else {
    $theStyles .= "font-size:{$size};line-height:{$height};";
  }
  $theStyles .= globalSpacingStyles($setting, $marginTopName, 'margin-top', false);
  $theStyles .= globalSpacingStyles($setting, $marginBottomName, 'margin-bottom', false);
  if ($theStyles) $theStyles = "{$preSelector}{$tag}{{$theStyles}}";
  return $theStyles;
}
