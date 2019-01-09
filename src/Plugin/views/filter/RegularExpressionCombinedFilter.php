<?php

namespace Drupal\views_custom_regex\Plugin\views\filter;

use Drupal\views\Plugin\views\filter\Combine;
use Drupal\Core\Form\FormStateInterface;

/**
 * Filter handler which allows to search on multiple fields.
 *
 * @ingroup views_field_handlers
 *
 * @ViewsFilter("combine")
 */
class RegularExpressionCombinedFilter extends Combine {
  use RegularExpressionTrait;

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

    $form['expose']['regex'] = [
      '#type' => 'textfield',
      '#default_value' => $this->options['expose']['regex'],
      '#title' => $this->t('Regular Expression'),
      '#description' => $this->t('Enter a regular expression. Example: [^abc] The expression is used to find any character NOT between the brackets'),
      '#size' => 20,
      '#states' => [
        'visible' => [
          'select[name="options[operator]"]' => ['value' => 'regular_expression'],
        ],
      ],
    ];

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
  }

  /**
   * Filters by a regular expression.
   *
   * @param string $field
   *   The expression pointing to the queries field, for example "foo.bar".
   */
  protected function opRegex($field) {
    // Call to regular expression query method from ReqularExpressionTrait.
    $this->regexquery($field, $this->options);
  }

}
