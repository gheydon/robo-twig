<?php

class RoboFile extends \Robo\Tasks
{

  use \Heydon\Robo\Task\Twig\loadTasks;

  public function template() {
    $this->taskTwig()
      ->setTemplatesArray('index', 'Hello {{ name }}!')
      ->setContext('name', 'Fabien')
      ->setTemplate('index')
      ->setDestination('readme.txt')
      ->run();
  }
}