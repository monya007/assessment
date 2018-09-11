<?php

namespace Drupal\math_field\Plugin\GraphQL\Fields;

use Drupal\graphql\Plugin\GraphQL\Fields\FieldPluginBase;
use GraphQL\Type\Definition\ResolveInfo;
use Drupal\graphql\GraphQL\Execution\ResolveContext;
use Drupal\math_field\ReversePolishNotation;
use Drupal\Core\Entity\FieldableEntityInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Math FIeld.
 *
 * @GraphQLField(
 *   id = "math_field",
 *   secure = true,
 *   type = "any",
 *   deriver = "Drupal\math_field\Plugin\Deriver\Fields\EntityMathFieldDeriver",
 * )
 */
class MathField extends FieldPluginBase implements ContainerFactoryPluginInterface {

  /**
   * Reverse polish notation service.
   *
   * @var \Drupal\math_field\ReversePolishNotation
   */
  protected $polishNotation;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $pluginId, $pluginDefinition) {
    return new static(
      $configuration,
      $pluginId,
      $pluginDefinition,
      $container->get('math_field.reverse_polish_notation')
    );
  }

  /**
   * MathField constructor.
   *
   * @param array $configuration
   *   The plugin configuration array.
   * @param string $pluginId
   *   The plugin id.
   * @param mixed $pluginDefinition
   *   The plugin definition.
   * @param \Drupal\math_field\ReversePolishNotation $polish_notation
   *   Reverse polish notation service.
   */
  public function __construct(array $configuration, $pluginId, $pluginDefinition, ReversePolishNotation $polish_notation) {
    parent::__construct($configuration, $pluginId, $pluginDefinition);
    $this->polishNotation = $polish_notation;
  }

  /**
   * {@inheritdoc}
   */
  protected function resolveValues($value, array $args, ResolveContext $context, ResolveInfo $info) {
    if ($value instanceof FieldableEntityInterface) {
      $definition = $this->getPluginDefinition();
      $name = $definition['field'];

      if ($value->hasField($name)) {
        $items = $value->get($name);
        $access = $items->access('view', NULL, TRUE);

        if ($access->isAllowed()) {
          foreach ($items as $item) {
            yield $this->polishNotation->calculateInfix($item->value);
          }
        }
      }
    }
  }

}
