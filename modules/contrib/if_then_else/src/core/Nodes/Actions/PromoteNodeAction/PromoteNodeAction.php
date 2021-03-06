<?php

namespace Drupal\if_then_else\core\Nodes\Actions\PromoteNodeAction;

use Drupal\node\NodeInterface;
use Drupal\if_then_else\core\Nodes\Actions\Action;
use Drupal\if_then_else\Event\NodeSubscriptionEvent;
use Drupal\if_then_else\Event\GraphValidationEvent;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Promote a node to front action class.
 */
class PromoteNodeAction extends Action {
  use StringTranslationTrait;

  /**
   * The entity manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a new RouteSubscriber object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   The entity type manager.
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager) {
    $this->entityTypeManager = $entityTypeManager;
  }

  /**
   * {@inheritdoc}
   */
  public static function getName() {
    return 'promote_node_action';
  }

  /**
   * {@inheritdoc}
   */
  public function registerNode(NodeSubscriptionEvent $event) {
    $event->nodes[static::getName()] = [
      'label' => $this->t('Promote Node'),
      'description' => $this->t('Promote Node'),
      'type' => 'action',
      'class' => 'Drupal\\if_then_else\\core\\Nodes\\Actions\\PromoteNodeAction\\PromoteNodeAction',
      'classArg' => ['entity_type.manager'],
      'inputs' => [
        'node' => [
          'label' => $this->t('Nid / Node'),
          'description' => $this->t('Nid or Node object. Can be of any bundle.'),
          'sockets' => ['number', 'object.entity.node'],
          'required' => TRUE,
        ],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function validateGraph(GraphValidationEvent $event) {
    $nodes = $event->data->nodes;

    foreach ($nodes as $node) {

      if ($node->data->type == 'event' && $node->data->name == 'entity_load_event') {
        if ((property_exists($node->data, 'selected_entity') && $node->data->selected_entity->value == 'node') ||
          (property_exists($node->data, 'selection') && $node->data->selection == 'all')) {
          $event->errors[] = $this->t('Event trigger is an entity load. This may call the If Then Else flow to go into an infinity loop.');
        }
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function process() {

    $node = $this->inputs['node'];

    if (is_numeric($node)) {
      $node = $this->entityTypeManager->getStorage('node')->load($node);
      if (empty($node)) {
        $this->setSuccess(FALSE);
        return;
      }
    }
    elseif (!$node instanceof NodeInterface) {
      $this->setSuccess(FALSE);
      return;
    }
    try {
      $node->setPromoted(TRUE);
      $node->save();
    }
    catch (\Exception $e) {
      watchdog_exception('if_then_else', $e);
    }
  }

}
