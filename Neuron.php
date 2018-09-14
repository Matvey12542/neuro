<?php

namespace Neuro;

class Neuron {

  public $weights = [];
  public $data = [];
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
  }

  public function initWeights($layerN, $neuroN) {
    $this->weights = $this->jsonHelper->loadWeight($layerN, $neuroN);
    if (empty($this->weights)) {
      $this->weights = $this->getRandomWeights(count($this->data));
      $this->saveWeights();
    }

    return $this->weights;
  }

  public function getRandomWeights($count) {
    $weights = [];

    for ($i = 0; $i < $count; $i++) {
      $weights[] = (rand(0, 100) - 50) / 100;
    }

    return $weights;
  }

  public function saveWeights() {
    $this->jsonHelper->saveWeight($this->layerN, $this->neuronN, $this->weights);
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
    $activation = $this->sigmoid($summ);

    return $activation;
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
}
