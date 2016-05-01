<?php
/**
 * @file
 * Contains \Drupal\dea\SolutionInterface.
 */

namespace Drupal\dea;

interface SolutionInterface {
  /**
   * {@inheritdoc}
   */
  public function __toString();

  /**
   * Apply the solution.
   */
  public function apply();
}