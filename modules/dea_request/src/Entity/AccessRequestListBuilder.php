<?php
/**
 * @file
 * Contains \Drupal\dea_access\Entity\AccessRequestListBuilder.
 */

namespace Drupal\dea_request\Entity;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Url;

class AccessRequestListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  protected function getEntityIds() {
    $query = $this->getStorage()->getQuery()
      ->sort($this->entityType->getKey('id'), 'DESC');

    // Only add the pager if a limit is specified.
    if ($this->limit) {
      $query->pager($this->limit);
    }
    return $query->execute();
  }

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    return [
      $this->t('Request #'),
      $this->t('User'),
      $this->t('Entity'),
      $this->t('Operation'),
      $this->t('path'),
      $this->t('Status'),
    ] + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    return [
      $entity->link(),
      $entity->uid->entity->link(),
      $entity->getTarget()->link(),
      $entity->operation->value,
      $entity->path ? \Drupal::l($entity->path->value, Url::fromUri('base:' . $entity->path->value)) : '',
      $entity->getReadableStatus(),
    ] + parent::buildRow($entity);
  }

  /**
   * {@inheritdoc}
   */
  public function buildOperations(EntityInterface $entity) {
    if ($entity->getStatus() == AccessRequest::OPEN) {
      return parent::buildOperations($entity);
    }
    else {
      return [];
    }
  }

}