# robo-twig
Use twig as a templating system system for robo

# Usage

* Use with template in memory, and displaying to the screen

```
    $this->taskTwig()
      ->setTemplatesArray('index', 'Hello {{ name }}!')
      ->setContext('name', 'Fabien')
      ->applyTemplate('index', '')
      ->run();
```

* Use with template file and write to destination

```
    $this->taskTwig()
      ->setTemplatesDirectory('./templates')
      ->setContext('name', 'Fabien')
      ->applyTemplate('index.twig', 'index.txt')
      ->applyTemplate('index.html.twig', 'index.html')
      ->run();
```
