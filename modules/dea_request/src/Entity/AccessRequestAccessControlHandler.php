<?php
/**
 * @file
 * Contains \Drupal\dea_request\Entity\AccessRequestAccessControlHandler.
 */

namespace Drupal\dea_request\Entity;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;

class AccessRequestAccessControlHandler extends EntityAccessControlHandler {
  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    // TODO: more sophisticated access control
    return parent::checkAccess($entity, $operation, $account);
  }

}