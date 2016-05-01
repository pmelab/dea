<?php
/**
 * @file
 * Contains \Drupal\dea_magic\EntityReferenceSolution.
 */

namespace Drupal\dea_magic;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\dea\SolutionInterface;
use Drupal\entity_test\FieldStorageDefinition;
use Drupal\user\UserInterface;

class EntityReferenceSolution implements SolutionInterface {
  /**
   * @var UserInterface $account
   */
  protected $account;

  /**
   * @var FieldDefinitionInterface $field
   */
  protected $field;

  /**
   * @var EntityInterface $target
   */
  protected $target;

  /**
   * {@inheritdoc}
   */
  public function __construct(UserInterface $account, EntityInterface $target, FieldDefinitionInterface $field) {
    $this->account = $account;
    $this->target = $target;
    $this->field = $field;
  }


  /**
   * {@inheritdoc}
   */
  public function __toString() {
    return t('Add %target to %user\'s %field.', [
      '%user' => $this->account->label(),
      '%target' => $this->target->label(),
      '%field' => $this->field->getLabel(),
    ])->render();
  }

  /**
   * {@inheritdoc}
   */
  public function apply() {
    $this->account->{$this->field->getName()}[] = $this->target;
    $this->account->save();
  }

}