<?php

namespace Drupal\pcdora\TwigExtension;

use Drupal\Component\Render\MarkupInterface;
use Drupal\Core\Render\Markup;
use EDTF\EdtfFactory;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class EdtfFormattingExtension extends AbstractExtension {

  public function getFunctions() {
    $functions = parent::getFunctions();

    $functions['pcdora_format_edtf'] = new TwigFunction('pcdora_format_edtf', $this->formatEdtf(...));

    return $functions;
  }

  public function formatEdtf(string $value, string $langcode = 'en', string $fallback_langcode = 'en') : \Stringable|string {
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
