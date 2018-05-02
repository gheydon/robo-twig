<?php

namespace Heydon\Robo\Task;

use Robo\Common\TaskIO;
use Robo\Exception\TaskException;
use Robo\Exception\TaskExitException;
use Robo\Task\BaseTask;
use Twig_Environment;
use Twig_Extension;
use Twig_Loader_Array;
use Twig_Loader_Filesystem;

class Twig extends BaseTask {

  use TaskIO;

  private $templatesDirectory;
  private $templatesArray = [];
  private $context = [];
  private $processes = [];
  private $extensions = [];

  /**
   * @return \Robo\Result|void
   */
  public function run() {
    if (!isset($this->templatesDirectory) && empty($this->templatesArray)) {
      throw new TaskExitException($this, 'Templates have not been defined.');
    }
    if (isset($this->templatesDirectory)) {
      $loader = new Twig_Loader_Filesystem($this->templatesDirectory);
    }
    elseif (!empty($this->templatesArray)) {
      $loader = new Twig_Loader_Array($this->templatesArray);
    }

    $twig = new Twig_Environment($loader);

    if (!empty($this->extensions)) {
      foreach ($this->extensions as $extension) {
        $twig->addExtension($extension);
      }
    }

    foreach ($this->processes as $process) {
      if (!empty($process['destination'])) {
        file_put_contents($process['destination'], $twig->render($process['template'], $this->context));
        $this->printTaskInfo('Writting template "' . $process['template'] . '" to file "' . $process['destination'] . '"');
      }
      else {
        $this->printTaskInfo($twig->render($process['template'], $this->context));
      }
    }
  }

  /**
   * @param string $templates_dir
   */
  public function setTemplatesDirectory($templates_dir) {
    if (!empty($this->templatesArray)) {
      throw new TaskException($this, 'template array is already in use, unable to combine with template directory.');
    }
    $this->templatesDirectory = $templates_dir;
  }

  /**
   * @param mixed $templatesArray
   */
  public function setTemplatesArray($id, $content = NULL) {
    if (isset($this->templatesDirectory)) {
      throw new TaskException($this, 'template directory is already in use, unable to combine with template array.');
    }

    // reset the template array with the new variables.
    if (is_array($id)) {
      $this->templatesArray = $id;
      return;
    }
    $this->templatesArray[$id] = $content;
  }

  /**
   * @param $id
   * @param null $value
   */
  public function setContext($id, $value = NULL) {
    if (is_array($id)) {
      $this->context = $id;
      return;
    }

    $this->context[$id] = $value;
  }

  /**
   * @param $template
   * @param $destination
   */
  public function applyTemplate($template, $destination) {
    $this->processes[] = [
      'template' => $template,
      'destination' => $destination,
    ];
  }

  /**
   * @param Twig_Extension $extensions
   */
  public function addExtension(Twig_Extension $extension) {
    $this->extensions[] = $extension;
  }

}
