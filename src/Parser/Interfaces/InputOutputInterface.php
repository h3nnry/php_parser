<?php

declare(strict_types=1);

namespace App\Parser\Interfaces;

/**
 * The interface provides the contract for different parsers
 * E.g. it can be xml to json, xml to csv, xml to csv
 */
interface InputOutputInterface
{
    /**
     * Convert to selected format
     */
    public function convert(string $path): void;
}