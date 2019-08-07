<?php

class Parser
{
    public $url;

    public function __construct($url)
    {
        $this->url = self::checkProtocol($url);
    }

    public function getUrl()
    {
        return $this->url;
    }

    /*
     * var string $url
     */
    public function getDocument()
    {
        $html = file_get_contents($this->url);
        $doc = new DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML($html);

        return $doc;
    }

    /*
     * var string $url
     */
    public static function checkProtocol($url)
    {
        if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
            $fullUrl = "https://" . $url;
            return $fullUrl;
        }

        return $url;
    }

    /*
     * var string $url
     */
    protected function transformInternalPath($path)
    {
        $fullUrl = '';
        if (!strstr($path, '://')) {
            if (preg_match("/^\/[a-z]/", $path)) {
                $fullUrl = $this->url . $path;

                return $fullUrl;
            } elseif (preg_match("/^[a-z]/", $path)) {
                $fullUrl = $this->url . '/' . $path;

                return $fullUrl;
            } else {
                return null;
            }
        }

        return $path;
    }

    /*
     * var string $url
     */
    protected function getPageSource($url)
    {
        $parcedUrl = parse_url($url);
        if ($parcedUrl) {
            if (key_exists("scheme", $parcedUrl)) {
                $pageSource = $parcedUrl["scheme"] . '://' . $parcedUrl["host"];

                return $pageSource;
            }
        }

        return 'empty';
    }
}