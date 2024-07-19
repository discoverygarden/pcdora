<?php

namespace Drupal\pcdora\TwigExtension;

use Drupal\Core\DependencyInjection\DependencySerializationTrait;
use EDTF\EdtfFactory;
use Psr\Log\LoggerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Twig extension class.
 */
class EdtfFormattingExtension extends AbstractExtension {

  use DependencySerializationTrait;

  /**
   * Constructor.
   */
  public function __construct(
    protected LoggerInterface $logger,
  ) {
    // No-op.
  }

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
   *   The language with which to try to format.
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
      $this->logger->info('Failed to parse EDTF value {value}: {message}', [
        'value' => $value,
        'message' => $parse_result->getErrorMessage(),
      ]);
      // Return the original, unmodified value.
      return $value;
    }

    $edtf = $parse_result->getEdtfValue();
    $humanizer = EdtfFactory::newHumanizerForLanguage($langcode, $fallback_langcode);
    $humanized = $humanizer->humanize($edtf);

    if (!$humanized) {
      $structured_humanizer = EdtfFactory::newStructuredHumanizerForLanguage($langcode, $fallback_langcode);
      $humanized_structure = $structured_humanizer->humanize($edtf);
      if (!$humanized_structure->wasHumanized()) {
        $this->logger->info('Failed to humanize EDTF value {value} with {language} (fallback: {fallback}): {message}', [
          'value' => $value,
          'language' => $langcode,
          'fallback' => $fallback_langcode,
          'message' => $humanized_structure->getContextMessage(),
        ]);
        // Return the original, unmodified value.
        return $value;
      }
      $humanized = $humanized_structure->getSimpleHumanization();
    }

    return $humanized;
  }

}
