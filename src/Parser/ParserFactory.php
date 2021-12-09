<?php

declare(strict_types=1);

namespace App\Parser;

use App\Parser\Formats;
use App\Parser\Interfaces\InputOutputInterface;
use App\Parser\XmlToJsonParser;
use App\Parser\XmlToCsvParser;
use App\Parser\XmlToXlxsParser;
use App\Parser\Exceptions\MissingParserException;

/**
 * The interface provides the contract for different parsers
 * E.g. it can be xml to json, xml to csv, xml to csv
 */
class ParserFactory
{
    /**
     * Factory for creation Parsers
     */
    public static function convert(string $input, string $output): InputOutputInterface
    {
        switch ($input) {
            case Formats::FORMAT_XML:
                switch ($output) {
                    case Formats::FORMAT_JSON:
                        return new XmlToJsonParser(Formats::FORMAT_JSON);
                    case Formats::FORMAT_CSV:
                        return new XmlToCsvParser(Formats::FORMAT_CSV);
                    case Formats::FORMAT_XLSX:
                        return new XmlToXlxsParser(Formats::FORMAT_XLSX);
                }
            default:
                throw new MissingParserException(message: "Parser from {$input} to {$output} doesn't exists.");
        }

    }
}