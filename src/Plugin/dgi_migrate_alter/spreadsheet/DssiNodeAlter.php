<?php

namespace Drupal\pcdora\Plugin\dgi_migrate_alter\spreadsheet;

use Drupal\dgi_migrate_alter\Plugin\MigrationAlterBase;
use Drupal\dgi_migrate_alter\Plugin\MigrationAlterInterface;

/**
 * Alter for dssi_node migration.
 *
 * @MigrationAlter(
 *   id = "pcdora_dssi_node_alter",
 *   label = @Translation("DSSI Node Migration Alteration"),
 *   description = @Translation("Alters the DSSI Node migration."),
 *   migration_id = "dssi_node"
 * )
 */
class DssiNodeAlter extends MigrationAlterBase implements MigrationAlterInterface {

  /**
   * {@inheritdoc}
   */
  public function alter(array &$migration): void{
    $process =& $migration['process'];

    $process['field_origin_information'][2]['values']['field_date_note'] = [
      [
        'plugin' => 'skip_on_empty',
        'method' => 'process',
        'source' => 'parent_value/date_text',
      ],
      [
        'plugin' => 'dgi_migrate.process.explode',
        'delimiter' => '^',
      ],
    ];
  }

}
