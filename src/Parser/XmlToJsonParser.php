<?php

namespace App\Parser;

use App\Parser\Interfaces\InputOutputInterface;
use App\Parser\XmlParser;
function xml2array ( $xmlObject, $out = array () )
{
    foreach ( (array) $xmlObject as $index => $node )
        $out[$index] = ( is_object ( $node ) ) ? xml2array ( $node ) : $node;

    return $out;
}
/**
 * Class XmlToJsonParser
 * @package App\Parser
 */
class XmlToJsonParser extends XmlParser implements InputOutputInterface
{

    /**
     * @param string $path
     * @throws ReadFileException
     */
    public function convert(string $path): void
    {
        $xml = $this->readFile($path);

        $data = json_encode($this->simpleXmlToArray($xml));

        $fileName = $this->getNewFileName($path, $this->format);

        file_put_contents($fileName, $data);
    }

}