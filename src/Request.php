<?php

namespace App;

class Request{
    private string $method;
    private string $url;
    private array $data = [];
    private bool $isLogin = false;


    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->url =  explode('?', $_SERVER['REQUEST_URI'])[0];
        function prepareData():array
        {
            $params = [];
            if ($_SERVER['QUERY_STRING'] !== null) {
                parse_str($_SERVER['QUERY_STRING'],$params);
                return $params;
            }
            return $params;
        }
        $this->data = match ($this->method){
            'GET' => prepareData(),
            'POST' => $_REQUEST
        };
        if ( !empty($_SESSION['user_login']) ){
            $this->isLogin = true;
        }
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @param string $method
     * @return Request
     */
    public function setMethod(string $method): Request
    {
        $this->method = $method;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return Request
     */
    public function setUrl(string $url): Request
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array $data
     * @return Request
     */
    public function setData(array $data): Request
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return bool
     */
    public function isLogin(): bool
    {
        return $this->isLogin;
    }


}
