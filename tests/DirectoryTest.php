<?php
namespace mxkh\dirinfo\tests;

use mxkh\dirinfo\Directory;

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

    public function test_directory_set_path()
    {
        $this->directory->setPath($this->path);

        static::assertNotEmpty($this->directory->getPath());
        static::assertEquals($this->path, $this->directory->getPath());
    }

    public function test_default_path(){
        $this->directory->list();

        static::assertNotEmpty($this->directory->getPath());
        static::assertEquals(realpath(dirname(__DIR__)), $this->directory->getPath());
    }

    public function test_directory_to_array()
    {
        $output = $this->directory->setPath($this->path)->list();

        static::assertInternalType('array', $output);
    }

    public function test_directory_to_json(){
        $output = $this->directory->setPath($this->path)->toJson()->list();

        static::assertJson($output);
    }

    public function test_directory_as_tree(){
        $output = $this->directory->setPath($this->path)->asTree()->list();

        static::assertInternalType('array', $output);
    }
}
