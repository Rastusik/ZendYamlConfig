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

namespace ZendYamlConfig\Service;

use Symfony\Component\Yaml\Parser;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Factory for the creation of the Symfony YAML file parser class instance,
 * - the created instance of the yaml parser is shared
 *
 * @category PHP
 * @package  ZendYamlConfig\Service
 * @author   rasta <mfris@icloud.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     https://github.com/Rastusik/ZendYamlConfig
 */
class YamlFileParserFactory implements FactoryInterface
{

    /**
     * single instance of the yaml file parser class
     *
     * @var YamlFileParser
     */
    private static $fileParserInstance;

    /**
     * Creates the yaml file parser service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return YamlFileParser
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return self::getYamlFileParserInstance();
    }

    /**
     * returns the yaml file parser from the static context
     * - useful for parsing the configuration files before
     *   the zf2 application is instantialized
     *
     * @return YamlFileParser
     */
    public static function getYamlFileParser()
    {
        return self::getYamlFileParserInstance();
    }

    /**
     * singleton method for the creation of the yaml file parser
     *
     * @return YamlFileParser
     */
    protected static function getYamlFileParserInstance()
    {
        if (self::$fileParserInstance === null) {
            self::$fileParserInstance = new YamlFileParser(new Parser());
        }

        return self::$fileParserInstance;
    }
}
