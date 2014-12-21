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

use Exception;
use Symfony\Component\Yaml\Parser;

/**
 * a YAML file parser, with the ability
 * to replace __DIR__ placeholders with the directory name, which
 * the processed yaml config file is stored in
 *
 * @category PHP
 * @package  ZendYamlConfig\Service
 * @author   rasta <mfris@icloud.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     https://github.com/Rastusik/ZendYamlConfig
 */
class YamlFileParser
{

    /**
     * instance of the Symfony yaml parser
     *
     * @var Parser
     */
    private $parser;

    /**
     * Symfony parser injection
     *
     * disabled ability to
     */
    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
    }

    /**
     * parses the input yaml string into a PHP config array
     *
     * @param string $yaml
     *
     * @return array
     */
    public function parse($yaml)
    {
        return $this->parser->parse($yaml);
    }

    /**
     * parses the input yaml file into a PHP config array
     * - also replaces the __DIR__ placeholders with the directory name
     *   of the parent directory of the input yaml file
     *
     * @param string $fileName
     *
     * @return array
     * @throws Exception
     */
    public function parseFile($fileName)
    {
        if (!is_file($fileName) || !is_readable($fileName)) {
            throw new Exception("Invalid file - {$fileName}");
        }

        $dir = realpath(dirname($fileName));
        $yaml = file_get_contents($fileName);
        $yaml = strtr($yaml, array(
            '___DIR___' => '__DIR__',
            '__DIR__' => $dir,
        ));

        return $this->parse($yaml);
    }
}
