<?php
/**
 *  TinyPNG API v1
 *
 *  Michael Wright - @michaelw90
 */


class TinyPNG
{

    private $url = 'http://api.tinypng.org/api/shrink';
    private $key = '';
    private $curl = null;
    private $lastResult = null;

    /**
     * Constructor
     * @param strong $key API key for all requests
     */
    public function __construct($key)
    {
        $this->key = $key;
    }

    /**
     * Instantiate curl request if not already created
     * @return resource
     */
    private function getCurl()
    {
        if (is_null($this->curl)) {
            $this->curl = curl_init();
            curl_setopt_array($this->curl, array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => $this->url,
                CURLOPT_USERAGENT => 'TinyPNG PHP API v1',
                CURLOPT_POST => 1,
                CURLOPT_USERPWD => 'api:' . $this->key,
                CURLOPT_BINARYTRANSFER => 1
            ));
        }
        return $this->curl;
    }

    /**
     * Send image shrink request
     * @param  string $file path to file to shrink
     * @return boolean       Is HTTP response 200
     */
    public function shrink($file)
    {
        curl_setopt($this->getCurl(), CURLOPT_POSTFIELDS, file_get_contents($file));
        $this->lastResult = curl_exec($this->getCurl());
        return curl_getinfo($this->getCurl(), CURLINFO_HTTP_CODE) === 200;
    }

    /**
     * Return API response
     * @param  boolean $object Return as object or JSON string
     * @return object|string
     */
    public function getResult($object = FALSE)
    {
        return ($object ? json_decode($this->lastResult) : $this->lastResult);
    }

}