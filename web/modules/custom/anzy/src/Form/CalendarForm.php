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
    $session = \Drupal::request()->getSession();
    $quartalsVal = [];
    if (!empty($inputes) && $session->get('Rebuild') == 1) {
      $this->rowsCount = $inputes["hidden"];
      $this->rowsCount++;
      $quartalsVal = $session->get('monthsVal');
    }
    elseif (!empty($inputes)) {
      $this->rowsCount = $inputes["hidden"];
    }
    if ($session->get('falseValid') != 1) {
      $quartalsVal = $session->get('monthsVal');
    }
    else {
      $quartalsVal = [];
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
      ];
      $form['wrapper']["Feb$i"] = [
        '#type' => 'number',
        '#default_value' => "",
      ];
      $form['wrapper']["Mar$i"] = [
        '#type' => 'number',
        '#default_value' => "",
      ];
      $form['wrapper']["Qfirst$i"] = [
        '#type' => 'textfield',
        '#value' => $quartalsVal["Qfirst$i"] ?? '',
        '#disabled' => TRUE,
      ];
      $form['wrapper']["Apr$i"] = [
        '#type' => 'number',
        '#default_value' => "",
      ];
      $form['wrapper']["May$i"] = [
        '#type' => 'number',
        '#default_value' => "",
      ];
      $form['wrapper']["Jun$i"] = [
        '#type' => 'number',
        '#default_value' => "",
      ];
      $form['wrapper']["Qsecond$i"] = [
        '#type' => 'textfield',
        '#value' => $quartalsVal["Qsecond$i"] ?? '',
        '#disabled' => TRUE,
      ];
      $form['wrapper']["Jul$i"] = [
        '#type' => 'number',
        '#default_value' => "",
      ];
      $form['wrapper']["Aug$i"] = [
        '#type' => 'number',
        '#default_value' => "",
      ];
      $form['wrapper']["Sep$i"] = [
        '#type' => 'number',
        '#default_value' => "",
      ];
      $form['wrapper']["Qthird$i"] = [
        '#type' => 'textfield',
        '#value' => $quartalsVal["Qthird$i"] ?? '',
        '#disabled' => TRUE,
      ];
      $form['wrapper']["Oct$i"] = [
        '#type' => 'number',
        '#default_value' => "",
      ];
      $form['wrapper']["Nov$i"] = [
        '#type' => 'number',
        '#default_value' => "",
      ];
      $form['wrapper']["Dec$i"] = [
        '#type' => 'number',
        '#default_value' => "",
      ];
      $form['wrapper']["Qfourth$i"] = [
        '#type' => 'textfield',
        '#value' => $quartalsVal["Qfourth$i"] ?? '',
        '#disabled' => TRUE,
      ];
      $form['wrapper']["YTD$i"] = [
        '#type' => 'textfield',
        '#value' => $quartalsVal["YTD$i"] ?? '',
        '#disabled' => TRUE,
      ];
      $form['wrapper']['hidden'] = [
        '#type' => 'hidden',
        '#value' => $this->rowsCount,
      ];
    }

    $form['wrapper']['add'] = [
      '#type' => 'submit',
      '#value' => t('Add year'),
      '#ajax' => [
        'callback' => '::addYear',
        'wrapper'    => 'data-wrapper',
      ],
    ];
    $form['wrapper']['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => t('Submit'),
      '#description' => t('Submit, #type = submit'),
      '#ajax' => [
        'callback' => '::submitCall',
        'wrapper'    => 'data-wrapper',
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
        $form_state->setErrorByName($name, t('There is a space in report, you need to fix'));
      }
      \Drupal::request()->getSession()->set('falseValid', 1);
    }
    return $form;
  }

  /**
   * Adding new year table to the report.
   */
  public function addYear(array &$form, FormStateInterface $form_state) {
    $form_state->setRebuild(TRUE);
    \Drupal::request()->getSession()->set('Rebuild', 1);
    return $form['wrapper'];
  }

  /**
   * {@inheritDoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $session = \Drupal::request()->getSession();
    $monthsVal = [];
    for ($i = $this->rowsCount; $i > -1; $i--) {
      $monthsVal["Qfirst$i"] = [
        $form_state->getValue("Jan$i") == NULL ? 0 : round($form_state->getValue("Jan$i"), 2),
        $form_state->getValue("Feb$i") == NULL ? 0 : round($form_state->getValue("Feb$i"), 2),
        $form_state->getValue("Mar$i") == NULL ? 0 : round($form_state->getValue("Mar$i"), 2),
      ];
      $monthsVal["Qfirst$i"] = $monthsVal["Qfirst$i"][0] == 0 && $monthsVal["Qfirst$i"][1] == 0 && $monthsVal["Qfirst$i"][2] == 0 ? "" : round((($monthsVal["Qfirst$i"][0] + $monthsVal["Qfirst$i"][1] + $monthsVal["Qfirst$i"][2]) + 1) / 3, 2);
      $monthsVal["Qsecond$i"] = [
        $form_state->getValue("Apr$i") == NULL ? 0 : round($form_state->getValue("Apr$i"), 2),
        $form_state->getValue("May$i") == NULL ? 0 : round($form_state->getValue("May$i"), 2),
        $form_state->getValue("Jun$i") == NULL ? 0 : round($form_state->getValue("Jun$i"), 2),
      ];
      $monthsVal["Qsecond$i"] = $monthsVal["Qsecond$i"][0] == 0 && $monthsVal["Qsecond$i"][1] == 0 && $monthsVal["Qsecond$i"][2] == 0 ? "" : round((($monthsVal["Qsecond$i"][0] + $monthsVal["Qsecond$i"][1] + $monthsVal["Qsecond$i"][2]) + 1) / 3, 2);
      $monthsVal["Qthird$i"] = [
        $form_state->getValue("Jul$i") == NULL ? 0 : round($form_state->getValue("Jul$i"), 2),
        $form_state->getValue("Aug$i") == NULL ? 0 : round($form_state->getValue("Aug$i"), 2),
        $form_state->getValue("Sep$i") == NULL ? 0 : round($form_state->getValue("Sep$i"), 2),
      ];
      $monthsVal["Qthird$i"] = $monthsVal["Qthird$i"][0] == 0 && $monthsVal["Qthird$i"][1] == 0 && $monthsVal["Qthird$i"][2] == 0 ? "" : round((($monthsVal["Qthird$i"][0] + $monthsVal["Qthird$i"][1] + $monthsVal["Qthird$i"][2]) + 1) / 3, 2);
      $monthsVal["Qfourth$i"] = [
        $form_state->getValue("Oct$i") == NULL ? 0 : round($form_state->getValue("Oct$i"), 2),
        $form_state->getValue("Nov$i") == NULL ? 0 : round($form_state->getValue("Nov$i"), 2),
        $form_state->getValue("Dec$i") == NULL ? 0 : round($form_state->getValue("Dec$i"), 2),
      ];
      $monthsVal["Qfourth$i"] = $monthsVal["Qfourth$i"][0] == 0 && $monthsVal["Qfourth$i"][1] == 0 && $monthsVal["Qfourth$i"][2] == 0 ? "" : round((($monthsVal["Qfourth$i"][0] + $monthsVal["Qfourth$i"][1] + $monthsVal["Qfourth$i"][2]) + 1) / 3, 2);
      $monthsVal["YTD$i"] = [
        $monthsVal["Qfirst$i"] == "" ? 0 : round($monthsVal["Qfirst$i"], 2),
        $monthsVal["Qsecond$i"] == "" ? 0 : round($monthsVal["Qsecond$i"], 2),
        $monthsVal["Qthird$i"] == "" ? 0 : round($monthsVal["Qthird$i"], 2),
        $monthsVal["Qfourth$i"] == "" ? 0 : round($monthsVal["Qfourth$i"], 2),
      ];
      $monthsVal["YTD$i"] = $monthsVal["Qfirst$i"] == 0 && $monthsVal["Qsecond$i"] == 0 && $monthsVal["Qthird$i"] == 0 && $monthsVal["Qfourth$i"] == 0 ? "" : round((($monthsVal["YTD$i"][0] + $monthsVal["YTD$i"][1] + $monthsVal["YTD$i"][2] + $monthsVal["YTD$i"][3]) + 1) / 4, 2);
    }
    $session->set('monthsVal', $monthsVal);
    \Drupal::messenger()->addMessage($this->t('Report updated successfully'), 'status', TRUE);
  }

  /**
   * Adding new year table to the report.
   */
  public function submitCall(array &$form, FormStateInterface $form_state) {
    $form_state->setRebuild(TRUE);
    $session = \Drupal::request()->getSession();
    $session->set('Rebuild', 0);
    $session->set('falseValid', 0);
    return $form['wrapper'];
  }

}
