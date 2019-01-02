<?php

namespace Neuro;


class Relu {

  public function process($x) {
    return max(0, $x);
  }

  public function derivative($x) {
    return $x > 0;
  }

}
