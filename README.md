# robo-twig
Use twig as a templating system system for robo

# Example
```
    $this->taskTwig()
      ->setTemplatesArray('index', 'Hello {{ name }}!')
      ->setContext('name', 'Fabien')
      ->setTemplate('index')
      ->setDestination('readme.txt')
      ->run();
```
