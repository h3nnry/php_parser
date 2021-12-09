<?php

namespace App\Parser;

use App\Parser\Interfaces\InputOutputInterface;
use App\Parser\XmlParser;

/**
 * Class XmlToXlxsParser
 * @package App\Parser
 */
class XmlToXlxsParser extends XmlParser implements InputOutputInterface
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
        \SimpleXLSXGen::fromArray($this->primaryProcessedData($data))
            ->saveAs($fileName);
    }

}