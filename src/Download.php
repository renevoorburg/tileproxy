<?php declare(strict_types=1);

class Download
{
    private string $data;
    private $size;
    private int $HTTPStatusCode;
    private array $headers;

    public function __construct(string $url, string $referer='', int $timeout=10)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HEADERFUNCTION, array($this,'readHeader'));
        if(!empty($referer)) curl_setopt($ch, CURLOPT_REFERER, $referer);

        $this->data = curl_exec($ch);
        $this->size = curl_getinfo($ch, CURLINFO_SIZE_DOWNLOAD);
        $this->HTTPStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);
    }

    public function getData(): string
    {
        return $this->data;
    }

    public function getSize(): int
    {
        return (int)$this->size;
    }

    public function getHTTPStatusCode(): int
    {
        return (int)$this->HTTPStatusCode;
    }

    public function getHeaders() : array
    {
        return $this->headers;
    }

    private function readHeader($url, $header) : int
    {
        $this->headers[] =  $header ;
        return strlen($header);
    }
}