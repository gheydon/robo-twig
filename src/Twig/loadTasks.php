<?php

namespace Heydon\Robo\Task\Twig;

use Heydon\Robo\Task\Twig;

trait loadTasks {

  /**
   * Load Twig
   */
  protected function taskTwig() {
    return $this->task(Twig::class);
  }
}