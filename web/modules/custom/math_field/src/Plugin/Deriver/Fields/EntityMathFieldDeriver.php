<?php

namespace Drupal\math_field\Plugin\Deriver\Fields;

use Drupal\graphql\Utility\StringHelper;
use Drupal\graphql_core\Plugin\Deriver\EntityFieldDeriverBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;

/**
 * Prove Math Field Deriver.
 */
class EntityMathFieldDeriver extends EntityFieldDeriverBase {

  /**
   * {@inheritdoc}
   */
  protected function getDerivativeDefinitionsFromFieldDefinition($entityTypeId, FieldStorageDefinitionInterface $fieldDefinition, array $basePluginDefinition, $bundleId = NULL) {
    if (!$propertyDefinitions = $fieldDefinition->getPropertyDefinitions()) {
      return [];
    }

    if ($fieldDefinition->getType() != 'string') {
      return [];
    }
    $fieldName = $fieldDefinition->getName();
    $parents = [StringHelper::camelCase($entityTypeId)];

    $tags = array_merge($fieldDefinition->getCacheTags(), ['entity_field_info']);
    $maxAge = $fieldDefinition->getCacheMaxAge();
    $contexts = $fieldDefinition->getCacheContexts();

    $derivative = [
      'parents' => $parents,
      'name' => StringHelper::propCase($fieldName) . 'Calculated',
      'description' => $fieldDefinition->getDescription(),
      'field' => $fieldName,
      'schema_cache_tags' => $tags,
      'schema_cache_contexts' => $contexts,
      'schema_cache_max_age' => $maxAge,
      'response_cache_tags' => $tags,
      'response_cache_contexts' => $contexts,
      'response_cache_max_age' => $maxAge,
    ] + $basePluginDefinition;

    if (count($propertyDefinitions) === 1) {
      // Flatten the structure for single-property fields.
      $derivative['type'] = reset($propertyDefinitions)->getDataType();
      $derivative['property'] = key($propertyDefinitions);
    }
    else {
      $derivative['type'] = StringHelper::camelCase('field', $entityTypeId, $fieldName);
    }

    if ($fieldDefinition->isMultiple()) {
      $derivative['type'] = StringHelper::listType($derivative['type']);
    }

    return ["$entityTypeId-$fieldName-calculated" => $derivative];
  }

  /**
   * Determines the parent types for a field.
   *
   * @param string $entityTypeId
   *   The entity type id of the field.
   * @param \Drupal\Core\Field\FieldStorageDefinitionInterface $fieldDefinition
   *   The field storage definition.
   *
   * @return array
   *   The pareants of the field.
   */
  protected function getParentsForField($entityTypeId, FieldStorageDefinitionInterface $fieldDefinition) {
    if ($fieldDefinition->isBaseField()) {
      return [StringHelper::camelCase($entityTypeId)];
    }

    if ($fieldDefinition instanceof FieldStorageConfigInterface) {
      if ($fieldDefinition->getEntityType()->hasKey('bundle')) {
        return array_values(array_map(function ($bundleId) use ($entityTypeId) {
          return StringHelper::camelCase($entityTypeId, $bundleId);
        }, $fieldDefinition->getBundles()));
      }
      else {
        return [StringHelper::camelCase($entityTypeId)];
      }
    }

    return [];
  }

}
