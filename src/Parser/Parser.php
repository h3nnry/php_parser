<?php

namespace App\Parser;

/**
 * Class Parser
 * @package App\Parser
 */
class Parser
{

    /**
     * @param string $path
     * @param string $extension
     * @return string
     */
    protected function getNewFileName(string $path, string $extension): string
    {
        $fileInfo = pathinfo($path);
        return $fileInfo["dirname"] . DIRECTORY_SEPARATOR . $fileInfo["filename"] . "." . $extension;
    }

}