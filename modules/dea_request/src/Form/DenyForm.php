<?php
/**
 * @file
 * Contains \Drupal\dea_request\Form\DenyForm.
 */

namespace Drupal\dea_request\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\dea_request\Entity\AccessRequest;

class DenyForm extends ContentEntityForm {
  /**
   * @inheritDoc
   */
  protected function copyFormValuesToEntity(EntityInterface $entity, array $form, FormStateInterface $form_state) {
    parent::copyFormValuesToEntity($entity, $form, $form_state);
    $entity->status = AccessRequest::DENIED;
  }

  /**
   * @inheritDoc
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);
    $form_state->setRedirect('dea_request.list');
    drupal_set_message($this->t('%user\'s request to %operation %label has been rejected.', [
      '%user' => $this->entity->uid->entity->label(),
      '%operation' => $this->entity->operation->value,
      '%label' => $this->entity->getTarget()->label(),
    ]));
  }


}
