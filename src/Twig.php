<?php

namespace Heydon\Robo\Task;

use Robo\Common\TaskIO;
use Robo\Exception\TaskException;
use Robo\Exception\TaskExitException;
use Robo\Task\BaseTask;
use Twig_Environment;
use Twig_Loader_Array;
use Twig_Loader_Filesystem;

class Twig extends BaseTask {

  use TaskIO;

  private $templatesDirectory;
  private $templatesArray = [];
  private $template;
  private $context = [];
  private $destination;

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

    if (isset($this->destination)) {
      file_put_contents($this->destination, $twig->render($this->template, $this->context));
      $this->printTaskInfo('Writting template "' . $this->template . '" to file "' . $this->destination . '"');
    }
    else {
      $this->printTaskInfo($twig->render($this->template, $this->context));
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
    }
    $this->templatesArray[$id] = $content;
  }

  /**
   * @param mixed $template
   */
  public function setTemplate($template) {
    $this->template = $template;
  }


  public function setContext($id, $value = NULL) {
    if (is_array($id)) {
      $this->context = $id;
    }

    $this->context[$id] = $value;
  }

  /**
   * @param mixed $destination
   */
  public function setDestination($destination) {
    $this->destination = $destination;
  }

}
