<?php

namespace Neuro;

class Neuron {

  public $weights = [];
  public $data = [];
  public $bias = 0;
  private $jsonHelper;
  public $output;
  public $error;
  public $layerN;
  public $neuronN;

  public function __construct(array $data, $layerN, $neuronN) {
    $this->jsonHelper = new WeightHelper();
    $this->data = $data;
    $this->layerN = $layerN;
    $this->neuronN = $neuronN;
    $this->initWeights($layerN, $neuronN);
    $this->initBias($layerN, $neuronN);
  }

  public function initWeights($layerN, $neuroN) {
    $this->weights = $this->jsonHelper->loadWeight($layerN, $neuroN);
    if (empty($this->weights)) {
      $this->weights = $this->getRandomWeights(count($this->data));
      $this->saveWeights();
    }

    return $this->weights;
  }

  public function initBias($layerN, $neuroN) {
    $this->bias = $this->jsonHelper->loadBias($layerN, $neuroN);
    if (empty($this->bias)) {
      $this->bias = $this->getRandomBias();
      $this->saveBias();
    }

    return $this->bias;
  }

  public function getRandomWeights($count) {
    $weights = [];

    for ($i = 0; $i < $count; $i++) {
      $weights[] = (rand(0, 100) - 50) / 100;
    }

    return $weights;
  }

  public function getRandomBias() {
    return rand(0, 100) / 100;
  }

  public function saveWeights() {
    $this->jsonHelper->saveWeight($this->layerN, $this->neuronN, $this->weights);
  }

  public function saveBias() {
    $this->jsonHelper->saveBias($this->layerN, $this->neuronN, $this->bias);
  }

  public function getSum() {
    $summ = 0;

    for ($i = 0; $i < count($this->data); $i++) {
      $summ += $this->data[$i] * $this->weights[$i];
    }

    return $summ;
  }

  public function activation() {
    $summ = $this->getSum();
    $activation_sum = $this->relu($summ);
    $bias_sum = max(0, ($activation_sum - $this->bias)); // Minus bias.

    return $bias_sum;
  }

  public function getOutput() {
    $this->output = $this->activation();
    return $this->output;
  }

  public function sigmoid($x) {
    $steepness = 0.00069315;
//    return 1 / (1 + exp(-$x * $steepness));
    return 1 / (1 + exp(-$x));
//    return $x;
  }

  public function relu($x) {
    return max(0, $x);
  }
}
