<?php

namespace Core;

use JsonException;

class Request
{
    protected $base;
    protected $uri;
    protected string $method;
    protected string $protocol;
    protected array $request = [];

    /**
     * Request constructor.
     * @throws JsonException
     */
    public function __construct()
    {
        $this->base = $_SERVER['REQUEST_URI'];
        $this->uri = $_REQUEST['URI'] ?? '/';
        $this->method = strtolower($_SERVER['REQUEST_METHOD']);
        $this->protocol = isset($_SERVER["HTTPS"]) ? 'https' : 'http';
        $this->setRequest();
    }

    /**
     * @throws JsonException
     */
    protected function setRequest(): void
    {
        switch ($this->method) {
            case 'post':
                $this->request = (array)json_decode(file_get_contents('php://input'), TRUE, 512, JSON_THROW_ON_ERROR);
                break;
            case 'get':
                $this->request = $_GET;
                break;
            case 'head':
            case 'put':
            case 'delete':
            case 'options':
            $this->request = (array)json_decode(file_get_contents('php://input'), TRUE, 512, JSON_THROW_ON_ERROR);
        }
    }

    public function uri(): string
    {
        return $this->uri;
    }

    public function method(): string
    {
        return $this->method;
    }

    public function get($key)
    {
        return $this->request[$key] ?? false;
    }

    public function __get($key)
    {
        return $this->get($key);
    }

    public function __set($name, $value)
    {
    }

    public function __isset($name)
    {
    }
}
