<?php

namespace mxkh\browser;

use FilesystemIterator;
use mxkh\browser\contracts\Browser;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

/**
 * Class Directory
 *
 * @package mxkh\browser
 */
class Directory implements Browser
{
    /**
     * @var string
     */
    protected $path = null;

    /**
     * @var bool
     */
    private $asTree = false;

    /**
     * @var bool
     */
    private $toJson = false;

    /**
     * @var array
     */
    private $directories = [];

    /**
     * @var array
     */
    private $files = [];

    /**
     * Sets the directory path
     * If path is empty set the current working directory
     *
     * @param string $path
     * @return $this
     */
    public function setPath(string $path = '')
    {
        $this->path = $path;

        if ('' === $this->path) {
            $this->path = dirname(__DIR__);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getPath():string
    {
        return $this->path;
    }

    /**
     * Generates directories info
     *
     * @return array|string
     */
    public function list()
    {
        if (is_null($this->path)) {
            $this->setPath();
        }

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($this->path, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );

        return $this->directoryIterator($iterator);
    }

    /**
     * @param RecursiveIteratorIterator $recursiveIteratorIterator
     * @return array|string
     */
    protected function directoryIterator(RecursiveIteratorIterator $recursiveIteratorIterator)
    {
        foreach ($recursiveIteratorIterator as $name => $object) {
            if ($object->isDir()) {
                $filesystemIterator = new FilesystemIterator($object->getRealPath(), FilesystemIterator::SKIP_DOTS);
                $this->directories = $this->directoryBuilder($recursiveIteratorIterator, $object, $filesystemIterator);
            }
        }

        $output = $this->toJson ? json_encode($this->directories) : $this->directories;

        return $output;
    }

    /**
     * Finds files in the directory which have the same content
     *
     * @param FilesystemIterator $filesystemIterator
     * @return int
     */
    protected function findDuplicateFiles(FilesystemIterator $filesystemIterator)
    {
        $this->files = [];
        foreach ($filesystemIterator as $file) {
            if ($file->isFile()) {
                $this->files[] = md5_file($file->getRealPath());
            }
        }

        return $this->countDuplicateFiles($this->files);
    }

    /**
     * Counts files which duplicated
     *
     * @param array $files
     * @return int
     */
    protected function countDuplicateFiles(array $files)
    {
        $counter = 0;
        foreach (array_count_values($files) as $hash => $count) {
            if ($count > 1) {
                $counter = $counter + $count;
            }
        }

        return $counter;
    }

    /**
     * @param RecursiveIteratorIterator $recursiveIteratorIterator
     * @param SplFileInfo $recursiveDirectoryIterator
     * @param FilesystemIterator $filesystemIterator
     * @return array
     */
    protected function directoryBuilder(
        RecursiveIteratorIterator $recursiveIteratorIterator,
        SplFileInfo $recursiveDirectoryIterator,
        FilesystemIterator $filesystemIterator
    ) {

        $sameFiles = $this->findDuplicateFiles($filesystemIterator);

        if ($this->asTree) {
            return $this->buildTree(
                $recursiveIteratorIterator,
                $recursiveDirectoryIterator,
                $filesystemIterator,
                $sameFiles
            );
        } else {
            return $this->buildList($recursiveDirectoryIterator, $filesystemIterator, $sameFiles);
        }
    }

    /**
     * @param SplFileInfo $recursiveDirectoryIterator
     * @param FilesystemIterator $filesystemIterator
     * @param int $sameFiles
     * @return array
     */
    protected function buildList(
        SplFileInfo $recursiveDirectoryIterator,
        FilesystemIterator $filesystemIterator,
        int $sameFiles
    ) {
        $this->directories[$recursiveDirectoryIterator->getBasename()] = [
            'size' => $recursiveDirectoryIterator->getSize(),
            'files' => iterator_count($filesystemIterator),
            'sameFiles' => $sameFiles,
        ];

        return $this->directories;
    }

    /**
     * @param RecursiveIteratorIterator $recursiveIteratorIterator
     * @param SplFileInfo $recursiveDirectoryIterator
     * @param FilesystemIterator $filesystemIterator
     * @param int $sameFiles
     * @return array
     */
    protected function buildTree(
        RecursiveIteratorIterator $recursiveIteratorIterator,
        SplFileInfo $recursiveDirectoryIterator,
        FilesystemIterator $filesystemIterator,
        int $sameFiles
    ) {
        $path = [
            $recursiveDirectoryIterator->getFilename() => [
                'size' => $recursiveDirectoryIterator->getSize(),
                'files' => iterator_count($filesystemIterator),
                'sameFiles' => $sameFiles,
            ]
        ];

        for ($depth = $recursiveIteratorIterator->getDepth() - 1; $depth >= 0; $depth--) {
            $path = [$recursiveIteratorIterator->getSubIterator($depth)->current()->getFilename() => $path];
        }
        $this->directories = array_merge_recursive($this->directories, $path);

        return $this->directories;
    }

    /**
     * @return $this
     */
    public function asTree()
    {
        $this->asTree = true;

        return $this;
    }

    /**
     * @return $this
     */
    public function toJson()
    {
        $this->toJson = true;

        return $this;
    }
}