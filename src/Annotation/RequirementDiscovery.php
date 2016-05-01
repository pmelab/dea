<?php
/**
 * @file
 * Contains \Drupal\dea\Annotation\RequirementDiscovery.
 */

namespace Drupal\dea\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * @Annotation
 */
class RequirementDiscovery extends Plugin {
  /**
   * The plugin id.
   * @var string $id
   */
  public $id;

  /**
   * The plugin label.
   * @var \Drupal\Core\Annotation\Translation $label
   */
  public $label;
}