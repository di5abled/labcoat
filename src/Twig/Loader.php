<?php

namespace Labcoat\Twig;

use Labcoat\PatternLab;
use Labcoat\PatternLab\Patterns\PatternInterface;
use Labcoat\PatternLabInterface;

/**
 * @deprecated 1.1.0 PatternLab classes moved to \Labcoat\PatternLab
 */
class Loader implements \Twig_LoaderInterface {

  protected $extension = 'twig';
  protected $index;

  public static function isPath($selector) {
    return strpbrk($selector, DIRECTORY_SEPARATOR . '/') !== false;
  }

  public function __construct(PatternLabInterface $patternlab) {
    $this->makeIndex($patternlab);
  }

  public function getSource($name) {
    return file_get_contents($this->getFile($name));
  }

  public function getCacheKey($name) {
    return md5($this->getFile($name));
  }

  public function isFresh($name, $time) {
    return filemtime($this->getFile($name)) > $time;
  }

  /**
   * @param $name
   * @return string
   * @throws \Twig_Error_Loader
   */
  protected function getFile($name) {
    if (isset($this->index[$name])) return $this->index[$name];
    throw new \Twig_Error_Loader("Unknown pattern: $name");
  }

  protected function makeIndex(PatternLabInterface $patternlab) {
    $this->index = [];
    foreach ($patternlab->getPatterns() as $pattern) {
      $file = $pattern->getFile();
      $partial = $this->makePartial($pattern);
      $this->index[$partial] = $file;
    }
  }

  protected function makePartial(PatternInterface $pattern) {
    return PatternLab\PatternLab::makePartial($pattern->getType(), $pattern->getName());
  }

  protected function stripExtension($path) {
    $ext = '.' . $this->extension;
    if (substr($path, 0 - strlen($ext)) == $ext) $path = substr($path, 0, 0 - strlen($ext));
    return $path;
  }
}