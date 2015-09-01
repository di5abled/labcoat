<?php

namespace Labcoat\Styleguide\Navigation;

interface TypeInterface {
  public function addPattern(\Labcoat\Patterns\PatternInterface $pattern);
  public function addSubtype(SubtypeInterface $subtype);
  public function getLowercaseName();
  public function getName();
  public function getNameWithDashes();
  public function getUppercaseName();
}