<?php

namespace Drupal\views_custom_regex\Plugin\views\filter;

/**
 * Provides convenience methods for Regular Expression query.
 */
trait RegularExpressionTrait {

  /**
   * Creates regular expression query.
   *
   * @param bool $field_selected
   *   Contains the operator selected.
   * @param array $options
   *   Contains all form options.
   */
  protected function regexquery($field_selected, array $options) {
    $group = $options['group'];
    $regex = $options['expose']['regex'];

    if (!empty($position = $options['expose']['position'])) {
      // Checks if Regular Expression Field is empty
      // if empty then in that case default drupal query of Filter will execute.
      if (!empty($regex)) {
        // Depending on position selected Regular expression,
        // will be appended in the Query.
        $value = ($position == 'prefix') ? $regex . $this->value : $this->value . $regex;
        $this->query->addWhereExpression($group, "$field_selected REGEXP '$value'");
      }
      else {
        // If Regular expression field is empty then use default query.
        $this->query->addWhere($group, $field_selected, $this->value, 'REGEXP');
      }
    }
    else {
      if (!empty($regex)) {
        // Depending on position selected Regular expression,
        // will be appended in the Query.debug($this->value, 'value');.
        $value = $this->value['value'];
        $this->query->addWhereExpression($group, "$field_selected REGEXP '$regex$value'");
      }
      else {
        // If Regular expression field is empty then use default query.
        $this->query->addWhere($group, $field_selected, $this->value, 'REGEXP');
      }
    }
  }

}
