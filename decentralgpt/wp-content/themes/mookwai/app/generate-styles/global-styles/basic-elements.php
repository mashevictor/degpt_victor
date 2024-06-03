<?php

function generateBasicElements($preSelector, $colorSchemeData)
{
  $stylesReg = '';
  $stylesMd = '';
  $stylesSm = '';
  $stylesDesktop = '';
  $stylesTablet = '';
  $stylesMobile = '';
  $stylesReg .= "{$preSelector}.relative{position:relative;}{$preSelector}.relative>*:where(:not(.mk-background-media)){position:relative;z-index:1;width:100%;}{$preSelector}.mk-background-media{position:absolute;inset:0;z-index:0;display:flex;}{$preSelector}.mk-background-media picture,{$preSelector}.mk-background-media img,{$preSelector}.mk-background-media video{width:100%;height:100%;object-fit:cover;}.mk-post-list .post-item > a{display:block;height:100%;}.mk-background-media .mk-bg-lottie>*{display:flex;width:100%;height:100%;}{$preSelector}.icon{display:flex;}{$preSelector}.icon svg,{$preSelector}.mk-icon svg,{$preSelector}.mk-svg svg{width:100%;}";
  $stylesReg .= "{$preSelector}.fixed{position:fixed;}";
  if ($preSelector) {
    $stylesReg .= "#mookwai-editor-loading{background-color:#fff;position:fixed;inset:0;z-index:100000;display:flex;justify-content:center;align-items:center;}";
  }

  $maskColor = isset($colorSchemeData[0]['maskBackground']) ? $colorSchemeData[0]['maskBackground'] : null;
  if ($maskColor) {
    $maskColor = "background:var(--{$maskColor});";
  } else {
    $maskColor = "background:rgba(17,17,17,.5);";
  }
  $stylesReg .= ".mk-overlay-mask{position:fixed;inset:0;display:none;$maskColor}";
  foreach ($colorSchemeData as $index => $scheme) {
    if ($index) {
      if (isset($scheme['maskBackground']) && $scheme['maskBackground']) {
        $stylesReg .= '[data-mode="' . $scheme['slug'] . '"] .mk-overlay-mask{background:var(--' . $scheme['maskBackground'] . ');}';
      }
    }
  }

  // General
  if ($preSelector) {
    $stylesReg .= "{$preSelector}.is-hide{display:none!important;}";
    $stylesReg .= "{$preSelector}.mkd-expand-edit{display:block!important;}";
    $stylesDesktop .= "{$preSelector}.is-hide-desktop{display:none!important;}";
    $stylesTablet .= "{$preSelector}.is-hide-tablet{display:none!important;}";
    $stylesMobile .= "{$preSelector}.is-hide-mobile{display:none!important;}";
  }

  // Paragraph
  $stylesReg .= "{$preSelector}.mk-paragraph{margin-bottom:16px;}";

  // List
  $stylesReg .= "{$preSelector}.mk-list{-webkit-padding-start:1em;padding-inline-start:1em;}{$preSelector}ul.mk-list li{list-style-type:disc;}{$preSelector}ol.mk-list li{list-style-type:decimal;}";

  // Code
  $stylesReg .= "{$preSelector}.mk-code{margin-top:0;margin-bottom:0;}{$preSelector}.mk-code > code{white-space:pre-wrap;word-break:break-all;}";

  // Table
  $stylesReg .= "{$preSelector}.mk-table table{width:100%;border-collapse:collapse;}{$preSelector}.mk-table table.has-fixed-layout{table-layout:fixed;}{$preSelector}.mk-table table thead,{$preSelector}.mk-table table tfoot{border:none;text-align:left;}{$preSelector}.mk-table table td,{$preSelector}.mk-table table th{border:none;padding:0;}";

  // Video
  $stylesReg .= "{$preSelector}.mk-video video{width:100%}";

  // SVG
  $stylesReg .= "{$preSelector}.mk-svg,{$preSelector}.mk-svg a{display:flex;width:100%;}";

  // Input
  $stylesReg .= $preSelector . 'input[type="text"],' . $preSelector . 'input[type="password"],' . $preSelector . 'input[type="color"],' . $preSelector . 'input[type="date"],' . $preSelector . 'input[type="datetime"],' . $preSelector . 'input[type="datetime-local"],' . $preSelector . 'input[type="email"],' . $preSelector . 'input[type="month"],' . $preSelector . 'input[type="number"],' . $preSelector . 'input[type="search"],' . $preSelector . 'input[type="tel"],' . $preSelector . 'input[type="time"],' . $preSelector . 'input[type="url"],' . $preSelector . 'input[type="week"],' . $preSelector . 'input[type="checkbox"],' . $preSelector . 'input[type="radio"],' . $preSelector . 'select,' . $preSelector . 'textarea{padding:0 8px;font-size:inherit;line-height:inherit;outline:none;box-shadow:none;border:solid 1px;border-radius:unset;}';
  $stylesReg .= $preSelector . 'input[type="text"]:focus,' . $preSelector . 'input[type="password"]:focus,' . $preSelector . 'input[type="color"]:focus,' . $preSelector . 'input[type="date"]:focus,' . $preSelector . 'input[type="datetime"]:focus,' . $preSelector . 'input[type="datetime-local"]:focus,' . $preSelector . 'input[type="email"]:focus,' . $preSelector . 'input[type="month"]:focus,' . $preSelector . 'input[type="number"]:focus,' . $preSelector . 'input[type="search"]:focus,' . $preSelector . 'input[type="tel"]:focus,' . $preSelector . 'input[type="time"]:focus,' . $preSelector . 'input[type="url"]:focus,' . $preSelector . 'input[type="week"]:focus,' . $preSelector . 'input[type="checkbox"]:focus,' . $preSelector . 'input[type="radio"]:focus,' . $preSelector . 'select:focus,' . $preSelector . 'textarea:focus{outline:none;box-shadow:none;}';
  $stylesReg .= "{$preSelector}button{font-size:inherit;line-height:inherit;}";

  // Image
  $stylesReg .= "{$preSelector}.mk-image{display:flex;}";

  // Navigation
  $stylesReg .= "{$preSelector}.mk-navigation .el-toggle .the-icon .icon-close{position:absolute;display:none;inset:0;}";

  // Carousel
  $stylesReg .= "{$preSelector}.mk-carousel .el-item.is-selected,{$preSelector}.mk-carousel .el-item.has-child-selected{z-index:2;}";

  // Horizontal
  if ($preSelector) {
    $stylesReg .= "{$preSelector}.mk-horizontal.mkd-empty,{$preSelector}{width:100%;}";
  }
  $stylesReg .= "{$preSelector}.mk-horizontal.is-disabled{display:block;}";
  $stylesDesktop .= "{$preSelector}.mk-horizontal.is-disabled-desktop{display:block;}";
  $stylesTablet .= "{$preSelector}.mk-horizontal.is-disabled-tablet{display:block;}";
  $stylesMobile .= "{$preSelector}.mk-horizontal.is-disabled-mobile{display:block;}";

  // Masonry
  $stylesReg .= "{$preSelector}.mk-masonry .inner.is-masonry{display:flex;flex-flow:column wrap;}";

  // Collapse
  $stylesReg .= "{$preSelector}.mk-collapse .ele-heading{cursor:pointer;}{$preSelector}.mk-collapse .ele-heading .text{margin-bottom:0;}{$preSelector}.mk-collapse .ele-content{overflow:hidden;}";

  // Flip card
  $stylesReg .= "{$preSelector}.mk-flip-card .el-surface{position:absolute;overflow:hidden;width:100%;height:100%;backface-visibility:hidden;-webkit-backface-visibility:hidden;-moz-backface-visibility:hidden;-ms-backface-visibility:hidden;}{$preSelector}.mk-flip-card .el-front{z-index:1;}";
  $stylesReg .= "{$preSelector}.mk-flip-card.is-disabled .el-surface{position:unset!important;height:unset;}";
  $stylesDesktop .= "{$preSelector}.mk-flip-card.is-disabled-desktop .el-surface{position:unset!important;height:unset;}";
  $stylesTablet .= "{$preSelector}.mk-flip-card.is-disabled-tablet .el-surface{position:unset!important;height:unset;}";
  $stylesMobile .= "{$preSelector}.mk-flip-card.is-disabled-mobile .el-surface{position:unset!important;height:unset;}";
  $stylesReg .= "{$preSelector}.mk-flip-card.mkd-expand-edit .el-surface{height:100%;}";
  $stylesReg .= "{$preSelector}.mk-flip-card.mkd-expand-edit .el-surface.el-front{transform:rotateY(180deg)!important;}";
  $stylesReg .= "{$preSelector}.mk-flip-card.mkd-expand-edit .el-surface.el-back{transform:rotateY(0)!important;}";

  // Marquee
  if ($preSelector) {
    $stylesReg .= "{$preSelector}.mk-marquee{overflow:hidden;}{$preSelector}.mk-marquee:not(.mkd-empty) .el-inner{display:flex;width:100%;flex-wrap:nowrap;}{$preSelector}.mk-marquee:not(.mkd-empty) .el-inner>*{flex-shrink:0;white-space:nowrap;}";
  } else {
    $stylesReg .= ".mk-marquee{overflow:hidden;}.mk-marquee .el-inner{display:flex;width:100%;flex-wrap:nowrap;}.mk-marquee .el-inner>*{flex-shrink:0;white-space:nowrap;}";
  }

  // Post List
  if (!$preSelector) {
    $stylesReg .= ".mk-entry-item .el-entry-link{position:absolute;top:0;left:0;width:100%;height:100%;}";
  }

  // // Post Cover
  // $stylesReg .= "{$preSelector}.mk-entry-cover{position:relative;}{$preSelector}.mk-entry-cover a{position:absolute;top:0;left:0;width:100%;height:100%;}{$preSelector}.mk-entry-cover .el-cover-main{display:flex;}{$preSelector}.mk-entry-cover .el-cover-addtional{position:absolute;inset:0;display:none;}{$preSelector}.mk-entry-cover .el-cover-addtional .el-cover-item{position:absolute;inset:0;}{$preSelector}.mk-entry-cover .el-cover-item{width:100%;}{$preSelector}.mk-entry-cover .el-cover-item>*{width:100%;height:100%;object-fit:cover;}";

  // Post Title
  $stylesReg .= "{$preSelector}.mk-entry-title.ellipsis{display:block;overflow:hidden;max-width:100%;white-space:nowrap;text-overflow:ellipsis;}";

  // Lightbox
  $stylesReg .= "{$preSelector}.mk-lightbox{position:fixed;inset:0;display:flex;justify-content:center;align-items:center;cursor:zoom-out;}";
  $stylesReg .= "{$preSelector}.mk-lightbox .el-container{margin:auto;width:fit-content;max-width:100%;max-height:100%;}";
  $stylesReg .= "{$preSelector}.mk-lightbox .el-container img{max-width:100vw;max-height:100vh;width:auto;height:auto;cursor:default;}";
  $stylesReg .= "{$preSelector}.mk-lightbox .el-close{position:absolute;top:12px;right:12px;display:flex;justify-content:center;align-items:center;padding:12px;width:44px;height:44px;border-radius:50%;background-color:rgba(0,0,0,0.3);fill:rgb(255,255,255);cursor:pointer;}";
  $stylesReg .= "{$preSelector}[data-mk-lightbox] {cursor:zoom-in;}";

  // Gutenberg
  $stylesReg .= "{$preSelector}.wp-block-search__inside-wrapper{display:flex;flex:auto;flex-wrap:nowrap;max-width:100%;}";
  $stylesReg .= "{$preSelector}.wp-block-search__button{display:flex;padding:6px 10px;text-align:center;border:unset;border-radius:initial;}";
  $stylesReg .= "{$preSelector}.wp-block-search__input{flex-grow:1;}";
  
  // Canvas
  if ($preSelector) {
    $stylesReg .= "{$preSelector}.mk-canvas.is-selected{background-color:rgba(255,255,255,.2);}";
  }

  if ($preSelector) {
    $stylesReg .= "{$preSelector}.mk-horizontal.mkd-expand-edit .el-container{flex-direction:column;}";
  }

  return array(
    "reg" => $stylesReg,
    "md" => $stylesMd,
    "sm" => $stylesSm,
    "desktop" => $stylesDesktop,
    "tablet" => $stylesTablet,
    "mobile" => $stylesMobile,
  );
}
