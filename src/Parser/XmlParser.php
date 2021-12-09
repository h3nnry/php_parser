<?php

namespace App\Parser;

use App\Parser\Exceptions\ReadFileException;
use App\Parser\Parser;

/**
 * Class XmlParser
 * @package App\Parser
 */
class XmlParser extends Parser
{
    /**
     * @param string $format
     */
    public function __construct(
        protected string $format
    ){}

    /**
     * @param $path
     * @return \SimpleXMLElement
     * @throws ReadFileException
     */
    protected function readFile($path): \SimpleXMLElement
    {
        if (function_exists("simplexml_load_file")) {
            // gets XML content from file
            $xml = file_get_contents($path);
            // removes newlines, returns and tabs
            $xml = str_replace(array("\n", "\r", "\t"), '', $xml);

            // replace double quotes with single quotes, to ensure the simple XML function can parse the XML
            $xml = trim(str_replace('"', "'", $xml));
            $xml = simplexml_load_string($xml);

            if ($xml === false) {
                throw new ReadFileException("Could not load xml file");
            }

            return $xml;
        } else {
            throw new ReadFileException("Please, install php-xml, simplexml_load_file function is missing.");
        }
    }

    /**
     * @param \SimpleXMLElement $xmlObject
     * @return array
     */
    protected function simpleXmlToArray(\SimpleXMLElement $xmlObject): array
    {
        $array = [];

        foreach ($xmlObject->children() as $node) {
            $attributes = [];

            if ($node->attributes()) {
                foreach ($node->attributes() as $name => $value) {
                    $attributes[$name] = (string)$value;
                }
            }

            if ($node->children()->count() > 0) {
                $data = array_merge($attributes, $this->simpleXmlToArray($node));

                if (isset($array[$node->getName()])) {
                    if (!isset($array[$node->getName()][0])) {
                        $entry = $array[$node->getName()];
                        $array[$node->getName()] = [];
                        $array[$node->getName()][] = $entry;
                    }

                    $array[$node->getName()][] = $data;
                } else {
                    $array[] = $data;
                }
            } else {
                if ($node->getName() != 'product')
                $array[$node->getName()] = (string)$node;
            }
        }

        return $array;
    }

    /**
     * @param array $data
     * @return array
     */
    protected function primaryProcessedData(array $data): array
    {
        $header = true;
        $result = [];
        foreach ($data as $value) {
            if ($header) {
                $result[] = array_keys($value);
                $header = false;
            }
            $result[] = array_values($value);
        }
        return $result;
    }

}