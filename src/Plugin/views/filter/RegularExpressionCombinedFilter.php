<?php

namespace Drupal\views_custom_regex\Plugin\views\filter;

use Drupal\views\Plugin\views\filter\Combine;

/**
 * Filter handler which allows to search on multiple fields.
 *
 * @ingroup views_field_handlers
 *
 * @ViewsFilter("combine")
 */
class RegularExpressionCombinedFilter extends Combine {

  /**
   * Overrides defineOptions function.
   *
   * Drupal\views\Plugin\views\filter\Combine.
   *
   * Information about new options added for Regular expression
   *
   * based on combined fields.
   */
  protected function defineOptions() {
    $options = parent::defineOptions();
    $options['expose']['contains']['position'] = ['default' => 'prefix'];
    $options['expose']['contains']['regex'] = ['default' => ''];

    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function defaultExposeOptions() {
    parent::defaultExposeOptions();
    $this->options['expose']['position'] = 'prefix';
    $this->options['expose']['regex'] = '';
  }

  /**
   * Overrides buildExposeForm function.
   *
   * Drupal\views\Plugin\views\filter\Combine.
   *
   * New fields are added to expose form.
   */
  public function buildExposeForm(&$form, FormStateInterface $form_state) {

    parent::buildExposeForm($form, $form_state);

    $form['expose']['position'] = [
      '#type' => 'radios',
      '#default_value' => $this->options['expose']['position'],
      '#title' => $this->t('Regex position'),
      '#description' => $this->t('Select postion of regular expression'),
      '#options' => [
        'prefix' => $this->t('Regex Prefix'),
        'suffix' => $this->t('Regex Suffix'),
      ],
      '#states' => [
        'visible' => [
          'select[name="options[operator]"]' => ['value' => 'regular_expression'],
        ],
      ],
    ];

    $form['expose']['regex'] = [
      '#type' => 'textfield',
      '#default_value' => $this->options['expose']['regex'],
      '#title' => $this->t('Regular Expression'),
      '#size' => 20,
      '#states' => [
        'visible' => [
          'select[name="options[operator]"]' => ['value' => 'regular_expression'],
        ],
      ],
    ];
  }

  /**
   * Filters by a regular expression.
   *
   * @param string $field
   *   The expression pointing to the queries field, for example "foo.bar".
   */
  protected function opRegex($field) {
    $regex_field = $this->options['expose']['regex'];

    // Checks if Regular Expression Field is empty
    // if empty then in that case default drupal query of Filter will execute.
    if (!empty($regex_field)) {
      // Depending on postion selected Regular expression
      // will be appended in the Query.
      $value = ($this->options['expose']['position'] == 'prefix') ? $this->options['expose']['regex'] . $this->value : $this->value . $this->options['expose']['regex'];
      $this->query->addWhereExpression($this->options['group'], "$field REGEXP '$value'");
    }
    else {
      // Regular expression field is empty in that case default
      // drupal query will execute.
      $this->query->addWhere($this->options['group'], $field, $this->value, 'REGEXP');
    }
  }

}
