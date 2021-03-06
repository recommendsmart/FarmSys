<?php

namespace Drupal\collection\Entity;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Provides an interface for defining Collection item type entities.
 */
interface CollectionItemTypeInterface extends ConfigEntityInterface {

  /**
   * Get the allowed collection item types property.
   *
   * @return array
   *   An array of allowed collection item types.
   */
  public function getAllowedBundles();
}
