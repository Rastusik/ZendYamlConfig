<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 */

namespace ZendYamlConfig;

use Zend\Config\Factory as ConfigFactory;
use Zend\Config\Reader\Yaml;
use Zend\Http\Response;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\InitProviderInterface;
use Zend\ModuleManager\ModuleManager;
use Zend\ModuleManager\ModuleManagerInterface;
use Zend\ServiceManager\ServiceManager;
use ZendYamlConfig\Service\YamlFileParser;

/**
 * ZendYamlConfig module
 *
 * @category PHP
 * @package  ZendYamlConfig
 * @author   rasta <mfris@icloud.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     https://github.com/Rastusik/ZendYamlConfig
 */
class Module implements InitProviderInterface, ConfigProviderInterface
{

    /**
     * @var YamlFileParser
     */
    protected $yamlParser;

    /**
     * module initialization:
     *  - YamlFileReader service registration
     *  - zend config factory configuration
     *
     * @param $manager
     */
    public function init(ModuleManagerInterface $manager)
    {
        if (!$manager instanceof ModuleManager) {
            return;
        }

        $event = $manager->getEvent();
        /* @var $serviceManager ServiceManager */
        $serviceManager = $event->getParam('ServiceManager');
        $serviceManager->setFactory(
            'ZendYamlConfig\Service\YamlFileParser',
            'ZendYamlConfig\Service\YamlFileParserFactory',
            true
        );
        $serviceManager->setAlias('yamlParser', 'ZendYamlConfig\Service\YamlFileParser');

        // Adding the parser to the reader, this makes it possible to merge yaml configs
        // from the config/autoload folder
        $this->yamlParser = $serviceManager->get('ZendYamlConfig\Service\YamlFileParser');
        /* @var $reader Yaml */
        $reader = ConfigFactory::getReaderPluginManager()->get('yaml');
        $reader->setYamlDecoder([$this->yamlParser, 'parse']);
    }

    /**
     * returns the module configuration
     *
     * @return array
     */
    public function getConfig()
    {
        return $this->yamlParser->parseFile(__DIR__ . '/config/module.config.yaml');
    }
}
