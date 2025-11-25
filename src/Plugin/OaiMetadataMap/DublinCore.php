<?php

namespace Drupal\pcdora\Plugin\OaiMetadataMap;

use Drupal\dgi_standard_oai\Plugin\OaiMetadataMap\DgiStandard;

/**
 * OAI Dublin Core mapping for PC.
 *
 * @OaiMetadataMap(
 *  id = "pc_dublin_core",
 *  label = @Translation("OAI Dublin Core (PC)"),
 *  metadata_format = "oai_dc",
 *  template = {
 *    "type" = "module",
 *    "name" = "rest_oai_pmh",
 *    "directory" = "templates",
 *    "file" = "oai-default"
 *  }
 * )
 */
class DublinCore extends DgiStandard {


  /**
   * Mapping of paragraph fields to maps of subfields to element names.
   *
   * @var string[][]
   */
  protected const PARAGRAPH_MAPPING = [
    'field_faceted_subject' => [
      'field_topic_general_subdivision_' => 'dcterms:subject',
      'field_temporal_chronological_sub' => 'dcterms:temporal',
      'field_geographic_geographic_subd' => 'dcterms:spatial',
    ],
    'field_hierarchical_geographic_su' => [
      'field_continent' => 'dcterms:spatial',
      'field_country' => 'dcterms:spatial',
      'field_region' => 'dcterms:spatial',
      'field_state' => 'dcterms:spatial',
      'field_territory' => 'dcterms:spatial',
      'field_county' => 'dcterms:spatial',
      'field_city' => 'dcterms:spatial',
      'field_city_section' => 'dcterms:spatial',
      'field_island' => 'dcterms:spatial',
      'field_area' => 'dcterms:spatial',
      'field_extraterrestrial_area' => 'dcterms:spatial',
    ],
    'field_origin_information' => [
      'field_date_created' => 'dcterms:created',
      'field_date_issued' => 'dcterms:issued',
      'field_date_note' => 'dcterms:date',
      'field_date_captured' => 'dcterms:date',
      'field_date_valid' => 'dcterms:date',
      'field_date_modified' => 'dcterms:date',
      'field_copyright_date' => 'dcterms:date',
      'field_publisher' => 'dcterms:publisher',
      'field_other_date' => 'dcterms:date',
    ],
    'field_related_item' => [
      'field_title' => 'dcterms:relation',
      'field_url' => 'dcterms:relation',
    ],
  ];

}
