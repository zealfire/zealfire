<?php

/**
 * @file
 * Contains \Drupal\zealfire\zealfireEntityManagerInterface
 */

namespace Drupal\zealfire;

use Drupal\Core\Entity\EntityInterface;

/**
 * Entity manager interface for the zealfire module.
 */
interface zealfireEntityManagerInterface {

  /**
   * Get the entities that zealfire is available for.
   *
   * @return array
   *  An array of entity definitions keyed by the entity type.
   */
  public function getzealfireEntities();

  /**
   * Check if an entity has a zealfire version available for it.
   *
   * @param EntityInterface $entity
   *  The entity to check a zealfire version is available for.
   *
   * @return bool
   *  TRUE if the entity has a zealfire version available, FALSE if not.
   */
  public function iszealfireEntity(EntityInterface $entity);

  /**
   * Get the entities that zealfire can generate hardcopies for.
   *
   * @return array
   *  An array of entity definitions keyed by the entity type.
   */
  public function getCompatibleEntities();

}
