<?php

namespace Neuro;

use PDO;
use PDOException;

class WeightHelper {
  public $dataTableName = "neuron";
  public $trainFileName = "train_data";

  public $cachedTrainingSet = [];

  public function loadWeight($layerN, $neuroN) {
    $data = '';
    try {
      $sql = "SELECT weights FROM $this->dataTableName WHERE layerN=:layerN AND neuroN=:neuroN";
      $stmt = Database::getInstance()->getConnection()->prepare($sql);
      $stmt->bindValue(':layerN', $layerN);
      $stmt->bindValue(':neuroN', $neuroN);
      $stmt->execute();
      $data = $stmt->fetchColumn();

    }
    catch (PDOException $e) {
      echo $e->getMessage();
    }

    $weight = json_decode($data, TRUE);
    return $weight;
  }

  public function saveWeight($layerN, $neuroN, $weightData) {
    $jsondata = json_encode($weightData, JSON_PRETTY_PRINT);

    if ($this->loadWeight($layerN, $neuroN)) {
      $this->updateWeights($layerN, $neuroN, $jsondata);
      return;
    }

    try {
      $sql = "INSERT INTO $this->dataTableName (layerN, neuroN, weights) VALUES(:layerN,:neuroN, :weights)";
      $stmt = Database::getInstance()->getConnection()->prepare($sql);
      $stmt->bindValue(':layerN', $layerN);
      $stmt->bindValue(':neuroN', $neuroN);
      $stmt->bindValue(':weights', $jsondata);
      $stmt->execute();
    }
    catch (PDOException $e) {
      echo $e->getMessage();
    }
  }

  public function updateWeights($layerN, $neuroN, $weightData) {
    try {
      $sql = "UPDATE $this->dataTableName SET layerN=:layerN,neuroN=:neuroN, weights=:weights WHERE layerN=:layerN AND neuroN=:neuroN";
      $stmt = Database::getInstance()->getConnection()->prepare($sql);
      $stmt->bindValue(':layerN', $layerN);
      $stmt->bindValue(':neuroN', $neuroN);
      $stmt->bindValue(':weights', $weightData);
      $stmt->execute();

    }
    catch (PDOException $e) {
      echo $e->getMessage();
    }
  }

  public function addTraindata($data, $right_result) {
    try {
      $sql = "INSERT INTO $this->trainFileName (data, right_result) VALUES(:data,:right_result)";
      $stmt = Database::getInstance()->getConnection()->prepare($sql);
      $stmt->bindValue(':data', json_encode($data, JSON_PRETTY_PRINT));
      $stmt->bindValue(':right_result', $right_result);
      $stmt->execute();

    }
    catch (PDOException $e) {
      echo $e->getMessage();
    }
  }

  public function loadTrainDataSet() {
    $data = '';
    if (!empty($this->cachedTrainingSet)) {
      shuffle($this->cachedTrainingSet);
      return $this->cachedTrainingSet;
    }

    try {

      $sql = "SELECT * FROM $this->trainFileName ORDER BY RAND()";
      $stmt = Database::getInstance()->getConnection()->prepare($sql);
      $stmt->execute();
      $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    catch (PDOException $e) {
      echo $e->getMessage();
    }

    foreach ($data as $key => $item) {
      $data[$key]['data'] = str_replace('"', "", $item['data']);
    }

    $this->cachedTrainingSet = $data;

    return $data;
  }
}
