<?php

namespace mxkh\browser\contracts;

/**
 * Interface Browser
 * @package mxkh\browser
 */
interface Browser
{
    public function setPath(string $path);

    public function getPath():string;

    public function asTree();

    public function toJson();

    public function list();
}