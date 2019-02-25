<?php

namespace Neuro;

class Train {

  private $jsonWeightsHelper;
  const step = 0.1;
  private $layers;
  private $weightsDelta = NULL;

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
          $neuron->weightsDelta = $neuron->error * $neuron->sigmoidPrime($neuron->getOutput());

          // Correct weight;
          for ($k = 0; $k < count($neuron->weights); $k++) {
            $neuron->weights[$k] = $neuron->weights[$k] - $neuron->getOutput() * $neuron->weightsDelta * self::step;
          }
        }
      }
    }
  }

  public function getNeuronError($neuron, $right_result, $net_result, $lN) {
    $error = 0;
    if ($lN === 0) {
      $error = $net_result - $right_result;
    }
    else {
      $prev_layer = $this->layers[$lN - 1];
      foreach ($prev_layer as $neuron) {
        foreach ($neuron->weights as $weight) {
          $error += $weight * $neuron->weightsDelta;
        }
      }
    }

    return $error;
  }

  public function normalizeRightResult($right_res) {
    $wrong_digit = 0;  // maybe 0.
    $right_digit = 1;
    $normalized = array_fill(0, 10, $wrong_digit);
    $normalized[$right_res] = $right_digit;
    return $normalized;
  }

  public function saveAllWeights() {
    foreach ($this->layers as $layer) {
      foreach ($layer as $neuron) {
        $neuron->saveWeights();
      }
    }
  }
}
