<?php

namespace Labcoat\PatternLab\Styleguide\Twig;

use Labcoat\PatternLabInterface;

class PageTemplateLoader extends \Twig_Loader_Array {

  public function __construct(PatternLabInterface $patternlab) {
    parent::__construct([
      'header' => file_get_contents($patternlab->getStyleguideHeader()),
      'footer' => file_get_contents($patternlab->getStyleguideFooter()),
    ]);
  }
}