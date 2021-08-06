<?php

namespace Drupal\anzy\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;

/**
 * Contains \Drupal\anzy\Form\CalendarForm for building form on report.
 *
 * @file
 */

/**
 * Inherit FormBase for build our form.
 */
class CalendarForm extends FormBase {

  /**
   * Contain number of tables to display.
   *
   * @var tableCount
   */
  protected $tableCount = 0;

  /**
   * Contain number of rows to display.
   *
   * @var rowsCount
   */
  protected $rowsCount = 0;

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'Calendar_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $inputes = $form_state->getUserInput();
    if (!empty($inputes)) {
      $this->rowsCount = $inputes["hidden"];
      $this->rowsCount++;
    }
    $headers = [
      t('Year'),
      t('Jan'),
      t('Feb'),
      t('Mar'),
      t('Q1'),
      t('Apr'),
      t('May'),
      t('Jun'),
      t('Q2'),
      t('Jul'),
      t('Aug'),
      t('Sep'),
      t('Q3'),
      t('Oct'),
      t('Nov'),
      t('Dec'),
      t('Q4'),
      t('YTD'),
    ];
    $form['wrapper'] = [
      '#type' => 'container',
      '#attributes' => ['id' => 'data-wrapper'],
    ];
    $form['system_messages'] = [
      '#markup' => '<div id="form-system-messages"></div>',
      '#weight' => -100,
    ];
    $form['wrapper']['table'] = [
      '#type' => 'table',
      '#header' => $headers,
    ];
    for ($i = $this->rowsCount; $i != -1; $i--) {
      $form['wrapper']["Year$i"] = [
        '#type' => 'number',
        '#value' => $i == 0 ? date('Y', time()) : date('Y', time()) - $i,
        '#disabled' => TRUE,
        '#attributes' => ['class' => ['firstField']],
      ];
      $form['wrapper']["Jan$i"] = [
        '#type' => 'number',
        '#default_value' => "",
        '#ajax' => [
          'callback' => '::validateTable',
          'event' => 'change',
        ],
      ];
      $form['wrapper']["Feb$i"] = [
        '#type' => 'number',
        '#default_value' => "",
        '#ajax' => [
          'callback' => '::validateTable',
          'event' => 'change',
        ],
      ];
      $form['wrapper']["Mar$i"] = [
        '#type' => 'number',
        '#default_value' => "",
        '#ajax' => [
          'callback' => '::validateTable',
          'event' => 'change',
        ],
      ];
      $form['wrapper']["Qfirst$i"] = [
        '#type' => 'textfield',
        '#value' => "",
        '#disabled' => TRUE,
      ];
      $form['wrapper']["Apr$i"] = [
        '#type' => 'number',
        '#default_value' => "",
        '#ajax' => [
          'callback' => '::validateTable',
          'event' => 'change',
        ],
      ];
      $form['wrapper']["May$i"] = [
        '#type' => 'number',
        '#default_value' => "",
        '#ajax' => [
          'callback' => '::validateTable',
          'event' => 'change',
        ],
      ];
      $form['wrapper']["Jun$i"] = [
        '#type' => 'number',
        '#default_value' => "",
        '#ajax' => [
          'callback' => '::validateTable',
          'event' => 'change',
        ],
      ];
      $form['wrapper']["Qsecond$i"] = [
        '#type' => 'textfield',
        '#value' => "",
        '#disabled' => TRUE,
      ];
      $form['wrapper']["Jul$i"] = [
        '#type' => 'number',
        '#default_value' => "",
        '#ajax' => [
          'callback' => '::validateTable',
          'event' => 'change',
        ],
      ];
      $form['wrapper']["Aug$i"] = [
        '#type' => 'number',
        '#default_value' => "",
        '#ajax' => [
          'callback' => '::validateTable',
          'event' => 'change',
        ],
      ];
      $form['wrapper']["Sep$i"] = [
        '#type' => 'number',
        '#default_value' => "",
        '#ajax' => [
          'callback' => '::validateTable',
          'event' => 'change',
        ],
      ];
      $form['wrapper']["Qthird$i"] = [
        '#type' => 'textfield',
        '#value' => "",
        '#disabled' => TRUE,
      ];
      $form['wrapper']["Oct$i"] = [
        '#type' => 'number',
        '#default_value' => "",
        '#ajax' => [
          'callback' => '::validateTable',
          'event' => 'change',
        ],
      ];
      $form['wrapper']["Nov$i"] = [
        '#type' => 'number',
        '#default_value' => "",
        '#ajax' => [
          'callback' => '::validateTable',
          'event' => 'change',
        ],
      ];
      $form['wrapper']["Dec$i"] = [
        '#type' => 'number',
        '#default_value' => "",
        '#ajax' => [
          'callback' => '::validateTable',
          'event' => 'change',
        ],
      ];
      $form['wrapper']["Qfourth$i"] = [
        '#type' => 'textfield',
        '#value' => "",
        '#disabled' => TRUE,
      ];
      $form['wrapper']["YTD$i"] = [
        '#type' => 'textfield',
        '#value' => "",
        '#disabled' => TRUE,
      ];
      $form['wrapper']['hidden'] = [
        '#type' => 'hidden',
        '#value' => $this->rowsCount,
      ];
    }

    $form['wrapper']['add'] = [
      '#type' => 'submit',
      '#value' => 'Add year',
      '#ajax' => [
        'callback' => '::addYear',
        'wrapper'    => 'data-wrapper',
      ],
    ];
    $form['wrapper']['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
      '#description' => $this->t('Submit, #type = submit'),
      '#ajax' => [
        'callback' => '::ajaxForm',
        'event' => 'click',
        'progress' => [
          'type' => 'throbber',
        ],
      ],
    ];
    $form['#attached']['library'][] = 'anzy/my-lib';
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $monthsVal = [];
    for ($i = $this->rowsCount; $i > -1; $i--) {
      $monthsVal["Jan$i"] = $form_state->getValue("Jan$i");
      $monthsVal["Feb$i"] = $form_state->getValue("Feb$i");
      $monthsVal["Mar$i"] = $form_state->getValue("Mar$i");
      $monthsVal["Apr$i"] = $form_state->getValue("Apr$i");
      $monthsVal["May$i"] = $form_state->getValue("May$i");
      $monthsVal["Jun$i"] = $form_state->getValue("Jun$i");
      $monthsVal["Jul$i"] = $form_state->getValue("Jul$i");
      $monthsVal["Aug$i"] = $form_state->getValue("Aug$i");
      $monthsVal["Sep$i"] = $form_state->getValue("Sep$i");
      $monthsVal["Oct$i"] = $form_state->getValue("Oct$i");
      $monthsVal["Nov$i"] = $form_state->getValue("Nov$i");
      $monthsVal["Dec$i"] = $form_state->getValue("Dec$i");
    }
    $count = 0;
    $values = 0;
    $theLastOne = 0;
    $offset = 0;
    $fieldsName = [];
    foreach ($monthsVal as $month => $number) {
      if ($number != "") {
        $count++;
        $values++;
        if ($theLastOne != 0) {
          $count--;
          $theLastOne = 0;
        }
      }
      elseif ($count != 0) {
        $fieldsName[$offset] = $month;
        $theLastOne++;
        $offset++;
      }
    }
    $fieldsName = array_slice($fieldsName, 0, $offset - $theLastOne, TRUE);
    if ($count != $values) {
      foreach ($fieldsName as $name) {
        $form_state->setErrorByName("wrapper", t('There is a space in report, you need to fix it.'));
      }
    }
    return $form;
  }

  /**
   * Adding new year table to the report.
   */
  public function addYear(array &$form, FormStateInterface $form_state) {
    $form_state->setRebuild(TRUE);
    return $form['wrapper'];
  }

  /**
   * Adding new year table to the report.
   */
  public function validateTable(array &$form, FormStateInterface $form_state) {
    $response = new AjaxResponse();
    $monthsVal = [];
    for ($i = $this->rowsCount; $i > -1; $i--) {
      $monthsVal["Jan$i"] = $form_state->getValue("Jan$i");
      $monthsVal["Feb$i"] = $form_state->getValue("Feb$i");
      $monthsVal["Mar$i"] = $form_state->getValue("Mar$i");
      $monthsVal["Apr$i"] = $form_state->getValue("Apr$i");
      $monthsVal["May$i"] = $form_state->getValue("May$i");
      $monthsVal["Jun$i"] = $form_state->getValue("Jun$i");
      $monthsVal["Jul$i"] = $form_state->getValue("Jul$i");
      $monthsVal["Aug$i"] = $form_state->getValue("Aug$i");
      $monthsVal["Sep$i"] = $form_state->getValue("Sep$i");
      $monthsVal["Oct$i"] = $form_state->getValue("Oct$i");
      $monthsVal["Nov$i"] = $form_state->getValue("Nov$i");
      $monthsVal["Dec$i"] = $form_state->getValue("Dec$i");
    }
    $count = 0;
    $values = 0;
    $theLastOne = 0;
    $offset = 0;
    $fieldsName = [];
    foreach ($monthsVal as $month => $number) {
      if ($number != "") {
        $count++;
        $values++;
        if ($theLastOne != 0) {
          $count--;
          $theLastOne = 0;
        }
      }
      elseif ($count != 0) {
        $fieldsName[$offset] = $month;
        $theLastOne++;
        $offset++;
      }
    }
    $fieldsName = array_slice($fieldsName, 0, $offset - $theLastOne, TRUE);
    if ($count != $values) {
      foreach ($fieldsName as $name) {
        $response->addCommand(new HtmlCommand('#form-system-messages', '<div class="alert alert-dismissible fade show col-12 alert-danger">' . t('You have a space in your report you need to fix it.') . '</div>'));
      }
    }
    return $response;
  }

  /**
   * {@inheritDoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    \Drupal::messenger()->addMessage($this->t('Report updated successfully'), 'status', TRUE);
  }

  /**
   * Ajax callback for submit.
   */
  public function ajaxForm(array &$form, FormStateInterface $form_state) {
    $ajax_response = new AjaxResponse();
    $message = [
      '#theme' => 'status_messages',
      '#message_list' => $this->messenger()->all(),
      '#status_headings' => [
        'status' => t('Status message'),
        'error' => t('Error message'),
        'warning' => t('Warning message'),
      ],
    ];
    $messages = \Drupal::service('renderer')->render($message);
    $ajax_response->addCommand(new HtmlCommand('#form-system-messages', $messages));
    $form_state->setRebuild(TRUE);
    return $ajax_response;
  }

}
