<?php

function dd($var)
{
    \Tracy\Debugger::dump($var);
    exit;
}

function d($var)
{
    \Tracy\Debugger::dump($var);
}

function bd($var)
{
    \Tracy\Debugger::barDump($var);
}