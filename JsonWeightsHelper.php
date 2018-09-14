<?php

namespace Neuro;

class JsonWeightsHelper {

  public $fileName = "data.json";
  public $trainFileName = "train_data.json";

  public function loadWeight($layerN, $neuroN) {
    $jsondata = file_get_contents($this->fileName);
    $arr_data = json_decode($jsondata, true);

    return $arr_data[$layerN][$neuroN];
  }

  public function saveWeight($layerN, $neuroN, $weightData) {
    $jsondata = file_get_contents($this->fileName);
    $arr_data = json_decode($jsondata, true);
    $arr_data[$layerN][$neuroN] = $weightData;

    $jsondata = json_encode($arr_data, JSON_PRETTY_PRINT);

    if(file_put_contents($this->fileName, $jsondata)) {
      $success = '';
    }
    else {
      echo 'Failed save data';
    }
  }

  public function addTraindata($data, $right_result) {
    $jsondata = file_get_contents($this->trainFileName);
    $arr_data = json_decode($jsondata, true);
    $arr_data[] = [
      'data' => $data,
      'right_result' => $right_result,
    ];

    $jsondata = json_encode($arr_data, JSON_PRETTY_PRINT);

    if(file_put_contents($this->trainFileName, $jsondata)) {
      $success = '';
    }
    else {
      echo 'Failed save data';
    }
  }

  public function loadTrainDataSet() {
    $jsondata = file_get_contents($this->trainFileName);
    $arr_data = json_decode($jsondata, true);

    return $arr_data;
  }

  public function shuffleDataSet() {
    $jsondata = file_get_contents($this->trainFileName);
    $arr_data = json_decode($jsondata, true);
    shuffle($arr_data);

    $jsondata = json_encode($arr_data, JSON_PRETTY_PRINT);

    if(file_put_contents($this->trainFileName, $jsondata)) {
      $success = '';
    }
    else {
      echo 'Failed save data';
    }
  }

}
