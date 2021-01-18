<?php

namespace Core;

use Exception;

class App
{
    protected Request $request;
    protected Router $router;
    protected Response $response;

    public function __construct(Request $request)
    {
        $this->router = new Router();
        $this->request = $request;
        $this->response = new Response();
        $this->response->setRequest($this->request);
    }

    /**
     * @return $this
     * @throws Exception
     */
    public function find(): App
    {
        if ($this->request) {
            $route = $this->router->find($this->request->method(), $this->request);
            if ($route) {
//                $this->response->setContent($route->dispatch());
                $this->response->json($route->dispatch());
            }
        }
        return $this;
    }

    public function send(): ?Response
    {
        if ($this->response) {
            return $this->response->send();
        }
        return null;
    }
}
