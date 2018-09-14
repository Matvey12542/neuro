<?php

namespace Neuro;

require_once 'Database.php';
require_once 'Train.php';
require_once 'Neuron.php';
require_once 'JsonWeightsHelper.php';
require_once 'WeightHelper.php';

$train = new Train();
$train->addTraindata($_POST["data"], $_POST["right_result"]);
