<?php

namespace Drupal\nomad\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\RedirectCommand;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\file\Entity\File;
use Drupal\Core\Url;

/**
 * Contains form created in order to create list of gests, that leave comments.
 */
class Nomadform extends FormBase {

  /**
   * Contains form created in order to create list of gests.
   */
  public function getFormId() {
    return 'nomad_name_form';
  }

  /**
   * Using build form function to create.
   */
  protected $dbinsert;

  /**
   * Using build form function to create.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $year_date = date('Y', time());
    $year_date = (int) $year_date;
    $content = [];
    $value = [];
    $db = \Drupal::service('database');
    $select = $db->select('nomad', 'r');
    $select->fields('r', ['Year', 'Jan', 'Feb', 'Mar',
      'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep',
      'Oct', 'Nov', 'Dec',
      'id',
    ]);
    $select->orderBy('id', 'DESC');
    $output = $select->execute()->fetchall();
    if ($output == NULL) {
      array_push($value, 0);
      $values[0] = [
        0 => NULL,
        1 => NULL,
        2 => NULL,
        3 => NULL,
        4 => NULL,
        5 => NULL,
        6 => NULL,
        7 => NULL,
        8 => NULL,
        9 => NULL,
        10 => NULL,
        11 => NULL,
      ];
      $qfirst = NULL;
      $qsecond = NULL;
      $qthird = NULL;
      $qfourth = NULL;
      $ytd = NULL;
    }
    else {
      $changed = json_decode(json_encode($output), TRUE);
      foreach ($changed as $key => $id) {
        array_push($value, $id['id']);
        $values[$key] = [
          0 => ($changed[$key]['Jan']) != NULL ? (int) ($changed[$key]['Jan']) : ($changed[$key]['Jan']),
          1 => ($changed[$key]['Feb']) != NULL ? (int) ($changed[$key]['Feb']) : ($changed[$key]['Feb']),
          2 => ($changed[$key]['Mar']) != NULL ? (int) ($changed[$key]['Mar']) : ($changed[$key]['Mar']),
          3 => ($changed[$key]['Apr']) != NULL ? (int) ($changed[$key]['Apr']) : ($changed[$key]['Apr']),
          4 => ($changed[$key]['May']) != NULL ? (int) ($changed[$key]['May']) : ($changed[$key]['May']),
          5 => ($changed[$key]['Jun']) != NULL ? (int) ($changed[$key]['Jun']) : ($changed[$key]['Jun']),
          6 => ($changed[$key]['Jul']) != NULL ? (int) ($changed[$key]['Jul']) : ($changed[$key]['Jul']),
          7 => ($changed[$key]['Aug']) != NULL ? (int) ($changed[$key]['Aug']) : ($changed[$key]['Aug']),
          8 => ($changed[$key]['Sep']) != NULL ? (int) ($changed[$key]['Sep']) : ($changed[$key]['Sep']),
          9 => ($changed[$key]['Oct']) != NULL ? (int) ($changed[$key]['Oct']) : ($changed[$key]['Oct']),
          10 => ($changed[$key]['Nov']) != NULL ? (int) ($changed[$key]['Nov']) : ($changed[$key]['Nov']),
          11 => ($changed[$key]['Dec']) != NULL ? (int) ($changed[$key]['Dec']) : ($changed[$key]['Dec']),
        ];
      }
      $this->dbinsert = $value;
    }
    $num_of_rows = $form_state->get('num_of_rows');
    if (empty($num_of_rows)) {
      $num_of_rows = 0;
      $form_state->set('num_of_rows', $num_of_rows);
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

    for ($i = 0; $i < count($value); $i++) {
      $marker = 0;
      if ($this->dbinsert != NULL) {
        $marker = ($this->dbinsert)[$i];
      }
      $jan = ($values[$i][0]) != '' ? $values[$i][0] : 0;
      $feb = ($values[$i][1]) != '' ? $values[$i][1] : 0;
      $mar = ($values[$i][2]) != '' ? $values[$i][2] : 0;
      $qfirst = (($jan + $feb + $mar) + 1) / 3;
      $qfirst = round($qfirst, 2);
      $qfirst = ($jan + $feb + $mar) != 0 ? $qfirst : NULL;
      $apr = ($values[$i][3]) != '' ? $values[$i][3] : 0;
      $may = ($values[$i][4]) != '' ? $values[$i][4] : 0;
      $jun = ($values[$i][5]) != '' ? $values[$i][5] : 0;
      $qsecond = (($apr + $may + $jun) + 1) / 3;
      $qsecond = round($qsecond, 2);
      $qsecond = ($apr + $may + $jun) != 0 ? $qsecond : NULL;
      $jul = ($values[$i][6]) != '' ? $values[$i][6] : 0;
      $aug = ($values[$i][7]) != '' ? $values[$i][7] : 0;
      $sep = ($values[$i][8]) != '' ? $values[$i][8] : 0;
      $qthird = (($jul + $aug + $sep) + 1) / 3;
      $qthird = round($qthird, 2);
      $qthird = ($jul + $aug + $sep) != 0 ? $qthird : NULL;
      $oct = ($values[$i][9]) != '' ? $values[$i][9] : 0;
      $nov = ($values[$i][10]) != '' ? $values[$i][10] : 0;
      $dec = ($values[$i][11]) != '' ? $values[$i][11] : 0;
      $qfourth = (($oct + $nov + $dec) + 1) / 3;
      $qfourth = round($qfourth, 2);
      $qfourth = ($oct + $nov + $dec) != 0 ? $qfourth : NULL;
      $ytd = (($qfirst + $qsecond + $qthird + $qfourth) + 1) / 4;
      $ytd = round($ytd, 2);
      $ytd = ($qfirst + $qsecond + $qthird + $qfourth) != 0 ? $ytd : NULL;

      $form['table'] = [
        '#type' => 'table',
        '#header' => $headers,
      ];
      $form["Year$marker"] = [
        '#title' => '',
        '#type' => 'number',
        '#default_value' => $year_date,
        '#disabled' => TRUE,
      ];
      $form["Jan$marker"] = [
        '#title' => '',
        '#type' => 'number',
        '#default_value' => !isset($values[$i][0]) ? '' : $values[$i][0],
      ];
      $form["Feb$marker"] = [
        '#title' => '',
        '#type' => 'number',
        '#default_value' => !isset($values[$i][1]) ? '' : $values[$i][1],
      ];
      $form["Mar$marker"] = [
        '#title' => '',
        '#type' => 'number',
        '#default_value' => !isset($values[$i][2]) ? '' : $values[$i][2],
      ];
      $form["Q1$marker"] = [
        '#title' => '',
        '#type' => 'number',
        '#step' => '.01',
        '#default_value' => !isset($qfirst) ? '' : $qfirst,
        '#disabled' => TRUE,
        '#required' => FALSE,
      ];
      $form["Apr$marker"] = [
        '#title' => '',
        '#type' => 'number',
        '#default_value' => !isset($values[$i][3]) ? '' : $values[$i][3],
      ];
      $form["May$marker"] = [
        '#title' => '',
        '#type' => 'number',
        '#default_value' => !isset($values[$i][4]) ? '' : $values[$i][4],
      ];
      $form["Jun$marker"] = [
        '#title' => '',
        '#type' => 'number',
        '#default_value' => !isset($values[$i][5]) ? '' : $values[$i][5],
      ];
      $form["Q2$marker"] = [
        '#title' => '',
        '#type' => 'number',
        '#step' => '.01',
        '#default_value' => !isset($qsecond) ? '' : $qsecond,
        '#disabled' => TRUE,
        '#required' => FALSE,
      ];
      $form["Jul$marker"] = [
        '#title' => '',
        '#type' => 'number',
        '#default_value' => !isset($values[$i][6]) ? '' : $values[$i][6],
      ];
      $form["Aug$marker"] = [
        '#title' => '',
        '#type' => 'number',
        '#default_value' => !isset($values[$i][7]) ? '' : $values[$i][7],
      ];
      $form["Sep$marker"] = [
        '#title' => '',
        '#type' => 'number',
        '#default_value' => !isset($values[$i][8]) ? '' : $values[$i][8],
      ];
      $form["Q3$marker"] = [
        '#title' => '',
        '#type' => 'number',
        '#step' => '.01',
        '#default_value' => !isset($qthird) ? '' : $qthird,
        '#disabled' => TRUE,
        '#required' => FALSE,
      ];
      $form["Oct$marker"] = [
        '#title' => '',
        '#type' => 'number',
        '#default_value' => !isset($values[$i][9]) ? '' : $values[$i][9],
      ];
      $form["Nov$marker"] = [
        '#title' => '',
        '#type' => 'number',
        '#default_value' => !isset($values[$i][10]) ? '' : $values[$i][10],
      ];
      $form["Dec$marker"] = [
        '#title' => '',
        '#type' => 'number',
        '#default_value' => !isset($values[$i][11]) ? '' : $values[$i][11],
      ];
      $form["Q4$marker"] = [
        '#title' => '',
        '#type' => 'number',
        '#step' => '.01',
        '#default_value' => !isset($qfourth) ? '' : $qfourth,
        '#disabled' => TRUE,
        '#required' => FALSE,
      ];
      $form["YTD$marker"] = [
        '#title' => '',
        '#type' => 'number',
        '#step' => '.01',
        '#default_value' => $ytd,
        '#disabled' => TRUE,
        '#required' => FALSE,
      ];
    }
    $content['message'] = [
      '#markup' => $this->t('You can use this table below, in order to manage your accounting operations.'),
    ];
    $form['system_messages'] = [
      '#markup' => '<div id="form-system-messages"></div>',
      '#weight' => -100,
    ];
    $form['actions']['add_row'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add Row'),
      '#submit' => [
        '::addRowCallback',
      ],
    ];
    $form['actions']['remove_row'] = [
      '#type' => 'submit',
      '#value' => $this->t('Remove Row'),
      '#submit' => [
        '::removeRowCallback',
      ],
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => t('Submit'),
    ];
    $form['#attached']['library'][] = 'nomad/quarter-style';
    $form['#attributes']['class'][] = 'accounting_table';
    return $form;
  }

  /**
   * Using standart structure of build form to create validation.
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

  }

  /**
   * Adding form submit according to build_form structure.
   */
  public function addRowCallback(array &$form, FormStateInterface $form_state) {
    $response = new AjaxResponse();
    // Increase by 1 the number of rows.
    $num_of_rows = $form_state->get('num_of_rows');
    if ($num_of_rows == 0) {
      $data = \Drupal::service('database')->insert('nomad')
        ->fields([
          "Year" => ($form_state->getValue('Year')) - 1,
          "Jan" => ($form_state->getValue("Jan$num_of_rows")) != '' ? $form_state->getValue("Jan$num_of_rows") : NULL,
          "Feb" => ($form_state->getValue("Feb$num_of_rows")) != '' ? $form_state->getValue("Feb$num_of_rows") : NULL,
          "Mar" => ($form_state->getValue("Mar$num_of_rows")) != '' ? $form_state->getValue("Mar$num_of_rows") : NULL,
          "Apr" => ($form_state->getValue("Apr$num_of_rows")) != '' ? $form_state->getValue("Apr$num_of_rows") : NULL,
          "May" => ($form_state->getValue("May$num_of_rows")) != '' ? $form_state->getValue("May$num_of_rows") : NULL,
          "Jun" => ($form_state->getValue("Jun$num_of_rows")) != '' ? $form_state->getValue("Jun$num_of_rows") : NULL,
          "Jul" => ($form_state->getValue("Jul$num_of_rows")) != '' ? $form_state->getValue("Jul$num_of_rows") : NULL,
          "Aug" => ($form_state->getValue("Aug$num_of_rows")) != '' ? $form_state->getValue("Aug$num_of_rows") : NULL,
          "Sep" => ($form_state->getValue("Sep$num_of_rows")) != '' ? $form_state->getValue("Sep$num_of_rows") : NULL,
          "Oct" => ($form_state->getValue("Oct$num_of_rows")) != '' ? $form_state->getValue("Oct$num_of_rows") : NULL,
          "Nov" => ($form_state->getValue("Nov$num_of_rows")) != '' ? $form_state->getValue("Nov$num_of_rows") : NULL,
          "Dec" => ($form_state->getValue("Dec$num_of_rows")) != '' ? $form_state->getValue("Dec$num_of_rows") : NULL,
        ])
        ->execute();
    }
    $num_of_rows++;
    $form_state->set('num_of_rows', $num_of_rows);
    $data = \Drupal::service('database')->insert('nomad')
      ->fields([
        'Year' => ($form_state->getValue('Year')) - 1,
        'Jan' => NULL,
        'Feb' => NULL,
        'Mar' => NULL,
        'Apr' => NULL,
        'May' => NULL,
        'Jun' => NULL,
        'Jul' => NULL,
        'Aug' => NULL,
        'Sep' => NULL,
        'Oct' => NULL,
        'Nov' => NULL,
        'Dec' => NULL,
      ])
      ->execute();
    // Rebuild form with 1 extra row.
    $form_state->setRebuild();
  }

  /**
   * Adding form submit according to build_form structure.
   */
  public function removeRowCallback(array &$form, FormStateInterface $form_state) {
    // Increase by 1 the number of rows.
    $query = \Drupal::database()->delete('nomad');
    $query->condition('id', ($this->dbinsert)[0]);
    $query->execute();
    $num_of_rows = $form_state->get('num_of_rows');
    $num_of_rows--;
    $form_state->set('num_of_rows', $num_of_rows);
    // Rebuild form with 1 extra row.
    $form_state->setRebuild();
  }

  /**
   * Adding form submit according to build_form structure.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    if (($this->dbinsert) != NULL) {
      foreach ($this->dbinsert as $key => $value) {
        $query = \Drupal::database()->update('nomad')
          ->fields([
            'Year' => $form_state->getValue("Year$value"),
            'Jan' => ($form_state->getValue("Jan$value")) != '' ? $form_state->getValue("Jan$value") : NULL,
            'Feb' => ($form_state->getValue("Feb$value")) != '' ? $form_state->getValue("Feb$value") : NULL,
            'Mar' => ($form_state->getValue("Mar$value")) != '' ? $form_state->getValue("Mar$value") : NULL,
            'Apr' => ($form_state->getValue("Apr$value")) != '' ? $form_state->getValue("Apr$value") : NULL,
            'May' => ($form_state->getValue("May$value")) != '' ? $form_state->getValue("May$value") : NULL,
            'Jun' => ($form_state->getValue("Jun$value")) != '' ? $form_state->getValue("Jun$value") : NULL,
            'Jul' => ($form_state->getValue("Jul$value")) != '' ? $form_state->getValue("Jul$value") : NULL,
            'Aug' => ($form_state->getValue("Aug$value")) != '' ? $form_state->getValue("Aug$value") : NULL,
            'Sep' => ($form_state->getValue("Sep$value")) != '' ? $form_state->getValue("Sep$value") : NULL,
            'Oct' => ($form_state->getValue("Oct$value")) != '' ? $form_state->getValue("Oct$value") : NULL,
            'Nov' => ($form_state->getValue("Nov$value")) != '' ? $form_state->getValue("Nov$value") : NULL,
            'Dec' => ($form_state->getValue("Dec$value")) != '' ? $form_state->getValue("Dec$value") : NULL,
          ]);
        $query->condition('id', $value);
        $query->execute();
      }

    }
    else {
      $this->dbinsert = [
        0 => 0,
      ];
      foreach ($this->dbinsert as $key => $value) {
        $data = \Drupal::service('database')->insert('nomad')
          ->fields([
            'Year' => $form_state->getValue("Year$value"),
            'Jan' => ($form_state->getValue("Jan$value")) != '' ? $form_state->getValue("Jan$value") : NULL,
            'Feb' => ($form_state->getValue("Feb$value")) != '' ? $form_state->getValue("Feb$value") : NULL,
            'Mar' => ($form_state->getValue("Mar$value")) != '' ? $form_state->getValue("Mar$value") : NULL,
            'Apr' => ($form_state->getValue("Apr$value")) != '' ? $form_state->getValue("Apr$value") : NULL,
            'May' => ($form_state->getValue("May$value")) != '' ? $form_state->getValue("May$value") : NULL,
            'Jun' => ($form_state->getValue("Jun$value")) != '' ? $form_state->getValue("Jun$value") : NULL,
            'Jul' => ($form_state->getValue("Jul$value")) != '' ? $form_state->getValue("Jul$value") : NULL,
            'Aug' => ($form_state->getValue("Aug$value")) != '' ? $form_state->getValue("Aug$value") : NULL,
            'Sep' => ($form_state->getValue("Sep$value")) != '' ? $form_state->getValue("Sep$value") : NULL,
            'Oct' => ($form_state->getValue("Oct$value")) != '' ? $form_state->getValue("Oct$value") : NULL,
            'Nov' => ($form_state->getValue("Nov$value")) != '' ? $form_state->getValue("Nov$value") : NULL,
            'Dec' => ($form_state->getValue("Dec$value")) != '' ? $form_state->getValue("Dec$value") : NULL,
          ])
          ->execute();
      }
    }

    \Drupal::messenger()->addMessage($this->t('Valid'), 'status', TRUE);
  }

}
