<?php

namespace Drupal\math_field\Plugin\Field\FieldFormatter;

use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\math_field\ReversePolishNotation;

/**
 * Plugin implementation of the Math Field formatter.
 *
 * @FieldFormatter(
 *   id = "math_field",
 *   label = @Translation("Math field calculation result"),
 *   description = @Translation("Expression calculation result"),
 *   field_types = {
 *     "string"
 *   }
 * )
 */
class MathFieldFormatter extends FormatterBase implements ContainerFactoryPluginInterface {

  /**
   * The Reverse Polish Notation service.
   *
   * @var \Drupal\math_field\ReversePolishNotation
   */
  protected $reversePolishNotation;

  /**
   * MathFieldFormatter constructor.
   *
   * @param string $plugin_id
   *   The plugin_id for the formatter.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Field\FieldDefinitionInterface $field_definition
   *   The definition of the field to which the formatter is associated.
   * @param array $settings
   *   The formatter settings.
   * @param string $label
   *   The formatter label display setting.
   * @param string $view_mode
   *   The view mode.
   * @param array $third_party_settings
   *   Any third party settings settings.
   *   The plugin implementation definition.
   * @param \Drupal\math_field\ReversePolishNotation $reverse_polish_notation
   *   The route match object.
   */
  public function __construct($plugin_id, $plugin_definition, FieldDefinitionInterface $field_definition, array $settings, $label, $view_mode, array $third_party_settings, ReversePolishNotation $reverse_polish_notation) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $label, $view_mode, $third_party_settings);
    $this->reversePolishNotation = $reverse_polish_notation;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $plugin_id,
      $plugin_definition,
      $configuration['field_definition'],
      $configuration['settings'],
      $configuration['label'],
      $configuration['view_mode'],
      $configuration['third_party_settings'],
      $container->get('math_field.reverse_polish_notation')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];
    foreach ($items as $delta => $item) {
      $elements[$delta] = [
        '#type' => 'markup',
        '#markup' => $this->reversePolishNotation->calculateInfix($item->value),
      ];
    }

    return $elements;
  }

}
