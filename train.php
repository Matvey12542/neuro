<?php

namespace Neuro;

require_once 'Database.php';
require_once 'Train.php';
require_once 'Main.php';
require_once 'Neuron.php';
require_once 'JsonWeightsHelper.php';
require_once 'WeightHelper.php';

set_time_limit(0);
$train = new Train();
for ($i = 0; $i < 1; $i++) {
  $train->train();
}
$train->saveAllWeights();
