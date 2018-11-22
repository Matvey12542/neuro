<?php

namespace Neuro;

class Main {

  private $data;
  private $layers;

  public function init($data) {
    $this->data = json_decode($data);
  }

  public function getLayers() {
    return $this->layers;
  }

  public function getResult() {

    // L1 - 8 neurons.
    $l1_neurons_out = [];
    $l = 0;

    for ($i = 0; $i < 10; $i++) {
      $neuron = new Neuron($this->data, $l, $i);
      $l1_neurons_out[] = $neuron->getOutput();
      $this->layers[$l][] = $neuron;
    }

    // L2 - 5 neurons.
    $l2_neurons_out = [];
    $l = 1;

    for ($i = 0; $i < 10; $i++) {
      $neuron = new Neuron($l1_neurons_out, $l, $i);
      $l2_neurons_out[] = $neuron->getOutput();
      $this->layers[$l][] = $neuron;
    }

    // L3 - output 10 neurons.
    $l3_neurons_out = [];
    $l = 2;

    for ($i = 0; $i < 10; $i++) {
      $neuron = new Neuron($l2_neurons_out, $l, $i);
      $l3_neurons_out[] = $neuron->getOutput();
      $this->layers[$l][] = $neuron;
    }

    return $l3_neurons_out;
  }
}
