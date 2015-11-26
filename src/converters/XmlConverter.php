<?php
namespace jones\novaposhta\converters;

use SimpleXMLElement;
use yii\helpers\Json;

/**
 * Class XmlConverter
 * @package jones\novaposhta\converters
 */
class XmlConverter implements ConverterInterface
{
    const CONTENT_TYPE = 'application/xml';

    /**
     * @inheritdoc
     */
    public function encode(array $params)
    {
        $document = $this->createDocument();
        $this->appendChildren($document, $params);
        return $document->asXML();
    }

    /**
     * @inheritdoc
     */
    public function decode($data)
    {
        $document = simplexml_load_string($data);
        return Json::decode(Json::encode((array) $document), true);
    }

    /**
     * @inheritdoc
     */
    public function getContentType()
    {
        return self::getContentType();
    }

    /**
     * Create xml document for request
     * @return SimpleXMLElement
     */
    private function createDocument()
    {
        $document = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><file/>');
        return $document;
    }

    /**
     * Recursively append array values to received xml node
     * @param SimpleXMLElement $node
     * @param array $data
     */
    private function appendChildren(SimpleXMLElement $node, array $data)
    {
        foreach ($data as $key => $item) {
            if (is_array($item)) {
                $child = $node->addChild($key);
                $this->appendChildren($child, $item);
            } else {
                $node->addChild($key, $item);
            }
        }
    }
}
