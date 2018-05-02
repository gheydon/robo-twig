<?php

class RoboFile extends \Robo\Tasks
{

  use \Heydon\Robo\Task\Twig\loadTasks;

  public function templateArray() {
    $this->taskTwig()
      ->setTemplatesArray('index', 'Hello {{ name }}!')
      ->setContext('name', 'Fabien')
      ->applyTemplate('index', '')
      ->run();
  }

  public function templateDirectory() {
    $this->taskTwig()
      ->setTemplatesDirectory('./templates')
      ->setContext('name', 'Fabien')
      ->applyTemplate('index.twig', 'index.txt', ['name' => 'Gordon'])
      ->run();
  }
}