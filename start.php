<?php

namespace Neuro;

require_once 'Database.php';
require_once 'Main.php';
require_once 'Neuron.php';
require_once 'JsonWeightsHelper.php';
require_once 'WeightHelper.php';

  $main = new Main();
  $main->init($_POST["data"]);
  $result = $main->getResult();

  print json_encode($result);


