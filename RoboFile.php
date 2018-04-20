<?php

class RoboFile extends \Robo\Tasks
{

  use \Heydon\Robo\Task\Twig\loadTasks;

  public function templateArray() {
    $this->taskTwig()
      ->setTemplatesArray('index', 'Hello {{ name }}!')
      ->setContext('name', 'Fabien')
      ->setTemplate('index')
      ->run();
  }

  public function templateDirectory() {
    $this->taskTwig()
      ->setTemplatesDirectory('./templates')
      ->setContext('name', 'Fabien')
      ->setTemplate('index.twig')
      ->setDestination('index.txt')
      ->run();
  }
}