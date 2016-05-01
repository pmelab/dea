<?php
/**
 * @file
 * Contains \Drupal\dea\EntityAccessManager.
 */

namespace Drupal\dea;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Implementation of TermAccessManagerInterface.
 */
class EntityAccessManager {

  /**
   * @var \Drupal\dea\GrantDiscovery
   */
  protected $grantManager;

  /**
   * @var \Drupal\dea\RequirementDiscovery
   */
  protected $requirementManager;

  /**
   * @param \Drupal\dea\RequirementDiscovery $requirement_manager
   * @param \Drupal\dea\GrantDiscovery $grant_manager
   */
  public function __construct(
    RequirementDiscovery $requirement_manager,
    GrantDiscovery $grant_manager
  ) {
    $this->requirementManager = $requirement_manager;
    $this->grantManager = $grant_manager;
  }


  /**
   * {@inheritdoc}
   */
  public function access(EntityInterface $entity, $operation, AccountInterface $account) {
    // Build a list of requirement strings from entity requirements.
    $requirements = array_map(function (EntityInterface $entity) {
      return $entity->getEntityTypeId() . ':' . $entity->id();
    }, $this->requirementManager->requirements($entity, $entity, $operation));

    // If there are no requirements, ignore the access check.
    if (count($requirements) == 0) {
      return AccessResult::neutral();
    }

    // Build a list of grant strings from the current accounts grants.
    $grants = array_map(function (EntityInterface $entity) {
      return $entity->getEntityTypeId() . ':' . $entity->id();
    }, $this->grantManager->grants($account, $entity, $operation));

    // If grants and requirements overlap allow, else deny access.
    if (count(array_intersect($requirements, $grants)) > 0) {
      return AccessResult::allowed();
    }
    else {
      return AccessResult::forbidden();
    }

  }

}
