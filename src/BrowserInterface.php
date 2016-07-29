<?php

namespace Mxkh\Dirinfo;

/**
 * Interface Browser
 * @package mxkh\dirinfo
 */
interface BrowserInterface
{
    public function setPath(string $path);

    public function getPath():string;

    public function asTree();

    public function toJson();

    public function list();
}