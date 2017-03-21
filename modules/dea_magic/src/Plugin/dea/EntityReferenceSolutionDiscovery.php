<?php
/**
 * @file
 * Contains \Drupal\dea_magic\Plugin\dea\EntityReferencesSolutionDiscovery.
 */

namespace Drupal\dea_magic\Plugin\dea;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Plugin\PluginBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\dea\Annotation\SolutionDiscovery;
use Drupal\dea\SolutionDiscoveryInterface;
use Drupal\Core\Annotation\Translation;
use Drupal\dea_magic\EntityReferenceSolution;
use Drupal\dea_magic\OperationReferenceScanner;
use Drupal\user\Entity\User;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @SolutionDiscovery(
 *   id = "entity_reference_solution",
 *   label = @Translation("Relate entity solution")
 * )
 */
class EntityReferenceSolutionDiscovery extends PluginBase implements SolutionDiscoveryInterface, ContainerFactoryPluginInterface {
  /**
   * @var OperationReferenceScanner
   */
  protected $scanner;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static($configuration, $plugin_id, $plugin_definition, $container->get('dea.scanner'));
  }

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, OperationReferenceScanner $scanner) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->scanner = $scanner;
  }


  /**
   * {@inheritdoc}
   */
  public function solutions(EntityInterface $entity, AccountInterface $account, $operation) {
    if (!$account->isAuthenticated()) {
      return [];
    }

    $user = User::load($account->id());

    $solutions = [];
    foreach ($this->scanner->operationReferences($entity, $entity, $operation) as $reference) {
      foreach ($this->scanner->operationReferenceFields($user) as $field) {
        $target_type = $field->getFieldStorageDefinition()->getSetting('target_type');
        $target_bundles = $field->getSetting('handler_settings')['target_bundles'];
        if ($reference->getEntityTypeId() == $target_type && in_array($reference->bundle(), $target_bundles)) {
          $key = implode(':', [
            $this->getPluginId(),
            $reference->getEntityTypeId(),
            $reference->id(),
            $field->getName(),
          ]);
          $solutions[$key] = new EntityReferenceSolution($user, $reference, $field);
        }
      }
    }

    return $solutions;
  }

}
