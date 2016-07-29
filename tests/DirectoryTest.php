<?php
namespace Mxkh\Dirinfo\Tests;

use Mxkh\Dirinfo\Directory;

class DirectoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Directory
     */
    public $directory;

    /**
     * @var string
     */
    public $path;

    protected function setUp()
    {
        parent::setUp();

        $this->directory = new Directory();
        $this->path = './tests/data';
    }

    public function testDirectorySetPath()
    {
        $this->directory->setPath($this->path);

        $this->assertNotEmpty($this->directory->getPath());
        $this->assertEquals($this->path, $this->directory->getPath());
    }

    public function testDefaultPath()
    {
        $this->directory->list();

        $this->assertNotEmpty($this->directory->getPath());
        $this->assertEquals(realpath(dirname(__DIR__)), $this->directory->getPath());
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testWrongPath()
    {
        $this->directory->setPath('wrong/path/')->list();
    }

    public function testDirectoryToArray()
    {
        $output = $this->directory->setPath($this->path)->list();

        $this->assertInternalType('array', $output);
    }

    public function testDirectoryToJson()
    {
        $output = $this->directory->setPath($this->path)->toJson()->list();

        $this->assertJson($output);
    }

    public function testDirectoryAsTree()
    {
        $output = $this->directory->setPath($this->path)->asTree()->list();

        $this->assertInternalType('array', $output);
    }
}
