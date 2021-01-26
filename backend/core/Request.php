<?php

namespace Core;

use JsonException;

final class Request
{
    private static ?Request $requestClass = null;
    protected $base;
    protected $uri;
    protected string $method;
    protected string $protocol;
    protected array $request = [];
    protected array $query = [];

    /**
     * Request constructor.
     * @throws JsonException
     */
    private function __construct()
    {
        $this->base = $_SERVER['REQUEST_URI'];
        $this->uri = $_REQUEST['URI'] ?? '/';
        $this->method = strtolower($_SERVER['REQUEST_METHOD']);
        $this->protocol = isset($_SERVER["HTTPS"]) ? 'https' : 'http';
        $this->query = $_GET;
        $this->setRequest();
    }

    public static function getInstance(): Request
    {
        if (self::$requestClass === null) {
            self::$requestClass = new Request();
        }
        return self::$requestClass;
    }

    /**
     * @throws JsonException
     */
    protected function setRequest(): void
    {
        $content = file_get_contents('php://input');
        switch ($this->method) {
            case 'post':
            case 'head':
            case 'put':
            case 'delete':
            case 'options':
                $this->request = !empty($content) ? (array)json_decode($content, TRUE, 512, JSON_THROW_ON_ERROR) : [];
                break;
            case 'get':
                $this->request = $_GET;
                break;
            default:
                $this->request = [];
        }
    }

    public function query(string $parameter)
    {
        return $this->query[$parameter] ?? null;
    }

    public function all(): array
    {
        return $this->request;
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

    public function set($name, $value)
    {
        $this->request[$name] = $value;
    }

    public function __set($name, $value)
    {
        $this->set($name, $value);
    }

    public function __isset($name): bool
    {
        return isset($this->request[$name]);
    }
}
