<?php

class ImgParser extends Parser
{
    public function __construct($url)
    {
        parent::__construct($url);
    }

    /*
     * var DOMDocument $doc
     */
    public function getImgsPath(DOMDocument $doc)
    {
        $nodelist = $doc->getElementsByTagName('img');
        $picturesInfo = [];
        if (count($nodelist) > 0) {
            foreach ($nodelist as $node) {
                $nodeValue = $node->attributes->getNamedItem('src')->nodeValue;
                if (strlen($nodeValue) != 0) {
                    $transformedNode = $this->transformInternalPath($nodeValue);
                    if ($transformedNode != null) {
                        $picInfo['path'] = $transformedNode;
                        $picInfo['page'] = $this->getPageSource($transformedNode);

                        $picturesInfo[] = $picInfo;
                    }
                }
            }
        }

        return $picturesInfo;
    }
}