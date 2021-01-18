<?php

namespace Core;

use DateTime;
use DateTimeZone;
use JsonException;

class Response
{
    protected array $headers = [];
    protected Request $request;
    protected string $content;
    protected int $status;
    protected string $version;
    protected $charset;
    protected array $statusDescription = [
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',
        103 => 'Early Hints',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status',
        208 => 'Already Reported',
        226 => 'IM Used',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        307 => 'Temporary Redirect',
        308 => 'Permanent Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Payload Too Large',
        414 => 'URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Range Not Satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot',
        421 => 'Misdirected Request',
        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',
        425 => 'Too Early',
        426 => 'Upgrade Required',
        428 => 'Precondition Required',
        429 => 'Too Many Requests',
        431 => 'Request Header Fields Too Large',
        451 => 'Unavailable For Legal Reasons',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        508 => 'Loop Detected',
        510 => 'Not Extended',
        511 => 'Network Authentication Required',
    ];

    public function __construct($content = '', $status = 200, $headers = [], $charset = 'UTF-8')
    {
        $this->setHeaders($headers);
        $this->setContent($content);
        $this->setStatus($status);
        $this->setVersion();
        $this->charset = $charset;
    }

    public function setRequest(Request $request): Response
    {
        $this->request = $request;
        return $this;
    }

    public function setContentType(string $type = 'html'): Response
    {
        if (strtolower($type) === 'html') {
            $type = 'text/html; charset=' . $this->charset;
        } else if (strtolower($type) === 'json') {
            $type = 'application/json; charset=' . $this->charset;
        }
        $this->setHeader('Content-Type', $type);
        return $this;
    }

    public function setVersion(string $version = '1.1'): Response
    {
        $this->version = $version;
        return $this;
    }

    public function sendHeaders(): Response
    {
        if (headers_sent()) {
            return $this;
        }

        if (isset($this->headers['Location'])) {
            header($this->headers['Location'], $this->status);
            exit();
        }

        if (!isset($this->headers['Date'])) {
            $this->setDate(new DateTime("NOW"));
        }

        if (!isset($this->headers['Content-Type'])) {
            $this->headers['Content-Type'] = 'application/json; charset=' . $this->charset;
        }

        foreach ($this->headers as $header => $values) {
            if (is_string($header)) {
                header($header . ': ' . $values, $header, $this->status);
            } else {
                header($values, $this->status);
            }
        }
        header(sprintf('HTTP/%s %s %s', $this->version, $this->status, $this->statusDescription[$this->status]), true, $this->status);
        return $this;
    }

    public function sendContent(): Response
    {
        echo $this->getContent();
        return $this;
    }

    public function getContent(): string
    {
        return (string)$this->content;
    }

    public function setStatus(int $status): Response
    {
        $this->status = $status;
        return $this;
    }

    public function addStatusCode(int $code, string $description = 'Unknown status'): Response
    {
        $this->statusDescription[$code] = $description;

        return $this;
    }

    public function setContent(string $content): Response
    {
        $this->content = $content;
        return $this;
    }

    public function withHeader($header, $description = null): Response
    {
        $this->setHeader($header, $description);
        return $this;
    }

    public function withHeaders($arguments): Response
    {
        $numArgs = func_num_args();
        if ($numArgs > 0) {
            $args = func_get_args();
            $this->setHeaders($args);
        }
        return $this;
    }

    public function setHeaders(array $headers): Response
    {
        if (count($headers) > 0) {
            foreach ($headers as $header => $description) {
                if (is_string($header)) {
                    $this->setHeader($header, $description);
                    continue;
                }
                $this->setHeader($description);
            }
        }
        return $this;
    }

    public function setHeader($header, $description = null): Response
    {
        if (!is_null($description)) {
            $this->headers[$header] = $description;
        } else {
            $this->headers[] = $header;
        }
        return $this;
    }

    public function setDate(DateTime $date): Response
    {
        $date->setTimezone(new DateTimeZone('UTC'));
        $this->headers['Date'] = $date->format('D, d M Y H:i:s') . ' GMT';
        return $this;
    }

    public function send(): Response
    {
        $this->sendHeaders();
        $this->sendContent();
        return $this;
    }

    /**
     * @param $data
     * @return $this
     * @throws JsonException
     */
    public function json(array $data): Response
    {
        $this->setContentType('json');
        $this->setContent(json_encode($data, JSON_THROW_ON_ERROR));
        return $this;
    }
}
