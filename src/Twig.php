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
   * @throws \Robo\Exception\TaskExitException
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
        file_put_contents($process['destination'], $twig->render($process['template'], $process['variables'] + $this->context));
        $this->printTaskInfo('Writting template "' . $process['template'] . '" to file "' . $process['destination'] . '"');
      }
      else {
        $this->printTaskInfo($twig->render($process['template'], $process['variables'] + $this->context));
      }
    }
  }

  /**
   * @param string $templates_dir
   * @throws \Robo\Exception\TaskException
   * @return $this
   */
  public function setTemplatesDirectory($templates_dir) {
    if (!empty($this->templatesArray)) {
      throw new TaskException($this, 'template array is already in use, unable to combine with template directory.');
    }
    $this->templatesDirectory = $templates_dir;
  }

  /**
   * @param mixed $templatesArray
   * @throws \Robo\Exception\TaskException
   * @return $this
   */
  public function setTemplatesArray($id, $content = NULL) {
    if (isset($this->templatesDirectory)) {
      throw new TaskException($this, 'template directory is already in use, unable to combine with template array.');
    }

    // reset the template array with the new variables.
    if (is_array($id)) {
      $this->templatesArray = $id;
      return $this;
    }
    $this->templatesArray[$id] = $content;

    return $this;
  }

  /**
   * @param $id
   * @param null $value
   * @return $this
   */
  public function setContext($id, $value = NULL) {
    if (is_array($id)) {
      $this->context = $id;
      return $this;
    }

    $this->context[$id] = $value;

    return $this;
  }

  /**
   * @param $template
   * @param $destination
   * @param array $variables
   * @return $this
   */
  public function applyTemplate($template, $destination, array $variables = []) {
    $this->processes[] = [
      'template' => $template,
      'destination' => $destination,
      'variables' => $variables,
    ];

    return $this;
  }

  /**
   * @param Twig_Extension $extensions
   * @return $this
   */
  public function addExtension(Twig_Extension $extension) {
    $this->extensions[] = $extension;

    return $this;
  }

}
