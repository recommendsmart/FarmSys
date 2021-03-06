<?php

namespace Drupal\content_as_config\Form;

use Drupal\Core\Form\FormStateInterface;

/**
 * Exports taxonomy term content to configuration.
 */
class TaxonomiesExportForm extends ExportBase {

  use TaxonomiesImportExportTrait;

  /**
   * {@inheritdoc}
   */
  protected function getListElements(): array {
    $export_list = [];
    $entities = $this->entityTypeManager->getStorage('taxonomy_vocabulary')
      ->loadMultiple();
    foreach ($entities as $entity) {
      $export_list[$entity->id()] = $entity->label();
    }
    return $export_list;
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);
    $form['export_list']['#title'] = $this->t('Export terms from these vocabularies:');
    return $form;
  }

}
