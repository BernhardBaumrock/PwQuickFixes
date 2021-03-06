<?php namespace ProcessWire;
class ChildParentTemplateRemoval extends PWFix {
  public $label = "childTemplates and parentTemplates removal";
  public $description = "Removes a template from all template's `childTemplates` and `parentTemplates` settings when template itself is deleted.";
  public $issue = 'https://github.com/processwire/processwire-issues/issues/802';
  public $author = 'adrianbj';
  public function init() {
    $this->addHookAfter('Templates::delete', function($event) {
      $templates = $event->object;
      foreach($templates as $t) {
          // child templates
          $childTemplates = $t->childTemplates;
          if($childTemplates) {
              foreach($childTemplates as $i => $tid) {
                  if(!$templates->get($tid)) {
                      unset($childTemplates[$i]);
                      $t->childTemplates = $childTemplates;
                      $t->save();
                  }
              }
          }

          // parent templates
          $parentTemplates = $t->parentTemplates;
          if($parentTemplates) {
              foreach($parentTemplates as $i => $tid) {
                  if(!$templates->get($tid)) {
                      unset($parentTemplates[$i]);
                      $t->parentTemplates = $parentTemplates;
                      $t->save();
                  }
              }
          }
      }
    });
  }
}
