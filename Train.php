<?php

namespace Neuro;

class Train {

  private $jsonWeightsHelper;
  const step = 0.003;
  private $layers;

  public function __construct() {
//    $this->jsonWeightsHelper = new JsonWeightsHelper();
    $this->jsonWeightsHelper = new WeightHelper();
  }

  public function addTraindata($data, $right_result) {
    $this->jsonWeightsHelper->addTraindata($data, $right_result);
  }

  public function train() {
    $trainSet = $this->jsonWeightsHelper->loadTrainDataSet();

    foreach ($trainSet as $trainItem) {

      $main = new Main();
      $main->init($trainItem['data']);
      $net_res = $main->getResult();
      $right_res = $trainItem['right_result'];
      $normalize_right_result = $this->normalizeRightResult($right_res);

      $layers = $main->getLayers();
      $this->layers = array_reverse($layers);
      for ($lN = 0; $lN < count($this->layers); $lN++) {
        $neurons = $this->layers[$lN];

        for ($j = 0; $j < count($neurons); $j++) {
          $neuron = $neurons[$j];
          $neuron->error = $this->getNeuronError($neuron, $normalize_right_result[$j], $net_res[$j], $lN);

          // Correct weight;
          for ($k = 0; $k < count($neuron->weights); $k++) {
            $delta_weight = self::step * $neuron->error /** $neuron->data[$k]*/;
            $neuron->weights[$k] += $delta_weight;
          }
        }
      }
    }
  }

  public function normalizeRightResult($right_res) {
    $wrong_digit = 0.1;  // maybe 0.
    $right_digit = 1;
    $normalized = array_fill(0, 10, $wrong_digit);
    $normalized[$right_res] = $right_digit;
    return $normalized;
  }

  public function getNeuronError($neuron, $right_result, $net_result, $lN) {

    if ($lN == 0) {
      // Derivative from sigmoid.
      $error = ($right_result - $net_result) * $neuron->output * (1 - $neuron->output);
    }
    else {
      $errors = 0;
      for ($i = 0; $i < count($this->layers[$lN - 1]); $i++) {
        $parent_neuron = $this->layers[$lN - 1][$i];
        $errors += $parent_neuron->error * $parent_neuron->weights[$i];
      }

      // Derivative from sigmoid.
      $error = $errors * $neuron->output * (1 - $neuron->output);
    }

    return $error;
  }

  public function saveAllWeights() {
    foreach ($this->layers as $layer) {
      foreach ($layer as $neuron) {
        $neuron->saveWeights();
      }
    }
  }
}
