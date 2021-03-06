<?php

namespace Labcoat\Html;

class Document implements DocumentInterface {

  protected $baseHref;
  protected $body;
  protected $charset = 'UTF-8';
  protected $meta = [];
  protected $scripts = [];
  protected $scriptBlocks = [];
  protected $styleBlocks = [];
  protected $stylesheets = [];
  protected $themeColor;
  protected $title;

  public function __construct($body = null, $title = null) {
    $this->body = $body;
    $this->title = $title;
  }

  public function __toString() {
    return $this->makeDoctype() . $this->makeDocument();
  }

  public function addMeta($name, $content) {
    $this->meta[] = [$name, $content];
  }

  public function includeScript($url) {
    $this->scripts[] = $url;
  }

  public function addScript($script) {
    $this->scriptBlocks[] = $script;
  }

  public function addStyles($styles) {
    $this->styleBlocks[] = $styles;
  }

  public function includeStylesheet($url) {
    $this->stylesheets[] = $url;
  }

  public function getBody() {
    return $this->body;
  }

  public function getThemeColor() {
    return $this->themeColor;
  }

  public function getTitle() {
    return $this->title;
  }

  public function setBaseHref($href) {
    $this->baseHref = $href;
  }

  public function setBody($body) {
    $this->body = $body;
  }

  public function setThemeColor($themeColor) {
    $this->themeColor = $themeColor;
  }

  public function setTitle($title) {
    $this->title = $title;
  }

  protected function hasScripts() {
    return !empty($this->scripts);
  }

  protected function hasStyles() {
    return !empty($this->styles);
  }

  protected function hasStylesheets() {
    return !empty($this->stylesheets);
  }

  protected function isSelfClosingTag($name) {
    return in_array($name, explode(' ', 'area base br col command embed hr img input keygen link meta param source track wbr'));
  }

  protected function makeAttributeString($key, $value) {
    return sprintf('%s="%s"', $key, htmlentities($value));
  }

  protected function makeAttributesString(array $attributes) {
    $keys = array_keys($attributes);
    $values = array_values($attributes);
    return implode(' ', array_map([$this, 'makeAttributeString'], $keys, $values));
  }

  protected function makeBody() {
    $body  = $this->getBody();
    if ($this->hasStyles() && $this->hasStylesheets()) $body .= $this->makeStylesheetLinks();
    if ($this->hasScripts()) $body .= $this->makeScriptIncludes();
    foreach ($this->scriptBlocks as $script) {
      $body .= $this->makeMeta('script', $script);
    }
    return $this->makeElement('body', $body);
  }

  protected function makeCharsetMeta() {
    return $this->makeElement('meta', null, ['charset' => $this->charset]);
  }

  protected function makeClosingTag($name) {
    return '</' . $name . '>';
  }

  protected function makeDoctype() {
    return '<!DOCTYPE html>';
  }

  protected function makeDocument() {
    return $this->makeElement('html', $this->makeHead() . $this->makeBody(), ['lang' => 'en']);
  }

  protected function makeHead() {
    $head  = $this->makeTitle();
    $head .= $this->makeCharsetMeta();
    $head .= $this->makeMeta('viewport', 'width=device-width');
    if (!empty($this->baseHref)) {
      $head .= $this->makeOpeningTag('base', ['href' => $this->baseHref]);
    }
    if (!empty($this->themeColor)) {
      $head .= $this->makeMeta('theme-color', $this->themeColor);
    }
    foreach ($this->meta as $meta) {
      list($name, $content) = $meta;
      $head .= $this->makeMeta($name, $content);
    }
    if ($this->hasStyles()) $head .= $this->makeStyleBlocks();
    if (!$this->hasStyles() && $this->hasStylesheets()) $head .= $this->makeStylesheetLinks();
    return $this->makeElement('head', $head);
  }

  protected function makeElement($name, $content = null, array $attributes = []) {
    $element = $this->makeOpeningTag($name, $attributes);
    if (!$this->isSelfClosingTag($name)) $element .= $content . $this->makeClosingTag($name);
    return $element;
  }

  protected function makeLink(array $attributes) {
    return $this->makeElement('link', null, $attributes);
  }

  protected function makeMeta($name, $content) {
    return $this->makeElement('meta', null, ['name' => $name, 'content' => $content]);
  }

  protected function makeOpeningTag($name, array $attributes = null) {
    $attributes = $attributes ? ' ' . $this->makeAttributesString($attributes) : '';
    return '<' . $name . $attributes  . '>';
  }

  protected function makeStyleBlocks() {
    foreach ($this->styleBlocks as $styles) {
      $blocks[] = $this->makeElement('style', $styles);
    }
    return !empty($blocks) ? implode($blocks) : '';
  }

  protected function makeStylesheetLink($url, $media = 'all') {
    return $this->makeLink(['rel' => 'stylesheet', 'href' => $url, 'media' => $media]);
  }

  protected function makeStylesheetLinks() {
    foreach ($this->stylesheets as $stylesheet) {
      $links[] = $this->makeStylesheetLink($stylesheet);
    }
    return !empty($links) ? implode($links) : '';
  }

  protected function makeScriptInclude($url) {
    return $this->makeElement('script', null, ['src' => $url]);
  }

  protected function makeScriptIncludes() {
    foreach ($this->scripts as $script) {
      $includes[] = $this->makeScriptInclude($script);
    }
    return !empty($includes) ? implode($includes) : '';
  }

  protected function makeTitle() {
    return $this->makeElement('title', $this->getTitle());
  }
}