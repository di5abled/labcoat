<?php

namespace Labcoat\Patterns\Configuration;

interface ConfigurationInterface {
  public function getDescription();
  public function getName();
  public function getState();
  public function getSubtype();
  public function getType();
  public function hasDescription();
  public function hasName();
  public function hasState();
  public function hasSubtype();
  public function hasType();
}