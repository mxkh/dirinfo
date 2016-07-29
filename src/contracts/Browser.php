<?php

namespace mxkh\dirinfo\contracts;

/**
 * Interface Browser
 * @package mxkh\dirinfo
 */
interface Browser
{
    public function setPath(string $path);

    public function getPath():string;

    public function asTree();

    public function toJson();

    public function list();
}