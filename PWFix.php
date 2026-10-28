<?php namespace ProcessWire;
abstract class PWFix extends Wire {
  public $name;
  public $label;
  public $description;

  /**
   * Class constructor
   */
  public function __construct() {
    $obj = new \ReflectionClass($this);
    $info = (object)pathinfo($obj->getFileName());
    if(!$this->label) $this->label = $info->filename;
  }

  /**
   * Load assets having the same name as the fix
   */
  public function loadAssets() {
    $file = __DIR__ . "/fixes/{$this->name}.css";
    if(is_file($file)) {
      $m = "?m=".filemtime($file);
      $this->config->styles->add($this->url($file).$m);
    }
    $file = __DIR__ . "/fixes/{$this->name}.js";
    if(is_file($file)) {
      $m = "?m=".filemtime($file);
      $this->config->scripts->add($this->url($file).$m);
    }
  }

  /**
   * Init method
   */
  public function init() {
  }

  /**
   * Return relative url
   */
  public function url($path) {
    $path = $this->config->urls->normalizeSeparators($path);
    return str_replace($this->config->paths->root, $this->config->urls->root, $path);
  }

  /**
   * debugInfo PHP 5.6+ magic method
   * @return array
   */
  public function __debugInfo() {
    $info['name'] = $this->name;
    $info['label'] = $this->label;
    $info['description'] = $this->description;
    return $info;
  }
}
