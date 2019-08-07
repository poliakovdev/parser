<?php

class LinkParser extends Parser
{
    public function __construct($url)
    {
        parent::__construct($url);
    }

    /*
     * var DOMDocument $doc
     */
    public function getDomainLinks(DOMDocument $doc)
    {
        $nodelist = $doc->getElementsByTagName('a');
        $linksInfo = [];
        if (count($nodelist) > 0) {
            foreach ($nodelist as $node) {
                if (is_object($node->attributes->getNamedItem('href'))){
                    $nodeValue = $node->attributes->getNamedItem('href')->nodeValue;
                    if (strstr($nodeValue, parent::getUrl())) {
                        $checkedNode = self::checkProtocol($nodeValue);
                        $linksInfo[] = $checkedNode;
                    }
                }
            }
        }

        return $linksInfo;
    }
}