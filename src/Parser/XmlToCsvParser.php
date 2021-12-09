<?php

namespace App\Parser;

use App\Parser\Interfaces\InputOutputInterface;
use App\Parser\XmlParser;

/**
 * Class XmlToCsvParser
 * @package App\Parser
 */
class XmlToCsvParser extends XmlParser implements InputOutputInterface
{
    /**
     * @param string $path
     * @throws ReadFileException
     */
    public function convert(string $path): void
    {
        $xml = $this->readFile($path);
        $fileName = $this->getNewFileName($path, $this->format);
        $data = $this->simpleXmlToArray($xml);
        $data = $this->primaryProcessedData($data);
        $f = fopen($fileName, 'w');
        foreach ($data as $value) {
            fputcsv($f, $value, ',', '"');
        }
        fclose($f);
    }
}