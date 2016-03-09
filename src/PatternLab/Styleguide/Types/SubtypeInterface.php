<?php

namespace Labcoat\PatternLab\Styleguide\Types;

/*

Subtype properties:

  Lowercase name
    The name of the subtype with ordering removed and dashes converted to spaces
    Used in the class name of the subtype's navigation item

  Uppercase name
    The lowercase name, with all words capitalized
    Used as the label for the subtype's navigation item

 */

use Labcoat\PatternLab\Styleguide\Types\TypeInterface;

interface SubtypeInterface extends TypeInterface {

  /**
   * @return TypeInterface
   */
  public function getType();

  /**
   * @return string
   */
  public function getPartial();
}