<?php

namespace App\Services;

class LazadaService
{
    private $api;

    private $httpHeader;


    public function __construct()
    {
        $this->api = config("lazada.api");
        $this->httpHeader = config("lazada.http_header");
    }

    public function crawlProducts($page = 1): bool|object
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL            => "{$this->api}/{$this->store}/?ajax=true&from=wangpu&isFirstRequest=true&langFlag=vi&page={$page}&pageTypeId=2&q=All-Products",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => 'GET',
            CURLOPT_HTTPHEADER     => config("lazada.http_header"),
        ));
        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        if ($httpCode === 200) {
            return json_decode($response);
        }
        return false;
    }

    public function getHttpHeader(): mixed
    {
        return $this->httpHeader;
    }

    public function setHttpHeader(mixed $httpHeader): void
    {
        $this->httpHeader = $httpHeader;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function setPage(int $page): void
    {
        $this->page = $page;
    }

    /**
     * @return mixed
     */
    public function getStore()
    {
        return $this->store;
    }

    /**
     * @param mixed $store
     */
    public function setStore($store): void
    {
        $this->store = $store;
    }

    private $store;

    public function getApi(): mixed
    {
        return $this->api;
    }

    public function setApi(mixed $api): void
    {
        $this->api = $api;
    }



}
