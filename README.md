ZendYamlConfig
==============

Module providing the ability to use YAML configuration files in ZF2. 

Many devs don't like ZF2 for many reasons, one of them is the necessity to use php arrays for configuration.
With this module, it is possible to leave behind all the php arrays and to use a nicer and more
compact format, in other words, you are able to change files like this:

```php
<?php

return [
    'modules' => [
        'ZendDeveloperTools',
        'ZendYamlConfig',
        'Application',
    ],
    'module_listener_options' => [
        'config_glob_paths' => [
            'config/autoload/{,*.}{global,local}.php',
        ],
        'module_paths' => [
            './module',
            './vendor',
        ],
    ],
];
```

into this:

```yaml
modules:
  - ZendDeveloperTools
  - ZendYamlConfig
  - Application

module_listener_options:
  config_glob_paths:
    - config/autoload/{,*.}{global,local}.yaml

  module_paths:
    - ./module
    - ./vendor
```

Note the difference between the number of lines. YAML also prohibits you to write executable code
into your configuration files, so the configuration does only what it is supposed to do (without any
anonymous callbacks).

## Requirements

 -  [ZendFramework 2](https://github.com/zendframework/zf2).
 -  Any application similar to the
    [ZendSkeletonApplication](https://github.com/zendframework/ZendSkeletonApplication).
    
## Installation

 1.  Add `"rastusik/zend-yaml-config": "dev-master"` to your `composer.json`
 2.  Run `php composer.phar install`
 3.  Enable the module in your `config/application.config.php` by adding `ZendYamlConfig` to `modules`
     (note: the module has to be loaded before all the modules that use YAML config files)
 
## Usage
 
If you want to read the configuration files on the Module level (the config files of your modules),
your module has to look like this:
 
```php
<?php

class Module implements DependencyIndicatorInterface, InitProviderInterface
{
    
    /**
     * @var ZendYamlConfig\Service\YamlFileParser
     */
    protected $yamlParser;
    
    /**
     * Expected to return an array of modules on which the current one depends on
     * - this module dependes on ZendYamlConfig
     *
     * @return array
     */
    public function getModuleDependencies()
    {
        return ['ZendYamlConfig'];
    }
    
    /**
     * Initialization of the module - retrieval of the YAML file parser
     */
    public function init(ModuleManagerInterface $manager)
    {
        if (!$manager instanceof ModuleManager) {
            return;
        }
        
        $event = $manager->getEvent();
        /* @var $serviceManager ServiceManager */
        $serviceManager = $event->getParam('ServiceManager');
        $this->yamlParser = $serviceManager->get('yamlParser');
    }
    
    /**
     * Config array retrieval from the YAML file
     */
    public function getConfig()
    {
        $config = $this->yamlParser->parseFile(__DIR__ . '/config/module.config.yaml');
        
        return $config;
    }
}
```
 
It is possible to use YAML files also as the main config files of the project. 
The main config file should similar to this one:

```yaml
modules:
  - ZendDeveloperTools
  - ZendYamlConfig
  - Application

module_listener_options:
  config_glob_paths:
    - config/autoload/{,*.}{global,local}.yaml

  module_paths:
    - ./module
    - ./vendor
```

Then, the app has to be initialized the following way:

```php
<?php

$yamlParser = ZendYamlConfig\Service\YamlFileParserFactory::getYamlFileParser();
$config = $yamlParser->parseFile(__DIR__ . '/../config/application.config.yaml');

Zend\Mvc\Application::init($config)->run();
```

## Features

The file parser replaces every occurence of the ___DIR___ constant in the YAML files with the path
to the parent directory of the YAML file (basically it is the same function as the __DIR__ constant
in a usual PHP file. 

## TODO

Parsing of the YAML files is not as fast as using plain PHP files, so a caching layer will have to be
imlemented. Soon.