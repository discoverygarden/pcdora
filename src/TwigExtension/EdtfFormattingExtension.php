<?php

namespace Drupal\pcdora\TwigExtension;

use EDTF\EdtfFactory;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Twig extension class.
 */
class EdtfFormattingExtension extends AbstractExtension {

  /**
   * {@inheritDoc}
   */
  public function getFunctions() {
    $functions = parent::getFunctions();

    $functions['pcdora_format_edtf'] = new TwigFunction('pcdora_format_edtf', $this->formatEdtf(...));

    return $functions;
  }

  /**
   * Function callback; format an EDTF value to be human-readable.
   *
   * @param string $value
   *   The EDTF value to be formatted.
   * @param string $langcode
   *   The language with which to format.
   * @param string $fallback_langcode
   *   The fallback language code.
   *
   * @return string
   *   The human-readable representation.
   */
  public function formatEdtf(string $value, string $langcode = 'en', string $fallback_langcode = 'en') : string {
    if (empty($value)) {
      return '';
    }
    $parser = EdtfFactory::newParser();
    $parse_result = $parser->parse($value);
    if (!$parse_result->isValid()) {
      return "Failed to parse EDTF ({$value}): {$parse_result->getErrorMessage()}";
    }

    $edtf = $parse_result->getEdtfValue();
    $humanizer = EdtfFactory::newHumanizerForLanguage($langcode, $fallback_langcode);
    $humanized = $humanizer->humanize($edtf);

    if (!$humanized) {
      $structured_humanizer = EdtfFactory::newStructuredHumanizerForLanguage($langcode, $fallback_langcode);
      $humanized_structure = $structured_humanizer->humanize($edtf);
      if (!$humanized_structure->wasHumanized()) {
        return "Failed to humanize EDTF ({$value}): {$humanized_structure->getContextMessage()}";
      }
      $humanized = $humanized_structure->getSimpleHumanization();
    }

    return $humanized;
  }

}
