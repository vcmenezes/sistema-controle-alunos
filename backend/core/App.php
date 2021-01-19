<?php

namespace Core;

use Exception;

class App
{
    protected Request $request;
    protected Router $router;
    protected Response $response;

    public function __construct()
    {
        $this->router = router();
        $this->request = request();
        $this->response = response();
    }

    /**
     * @return Response|Router|false|mixed|null
     * @throws Exception
     */
    public function find()
    {
        if ($this->request) {
            $route = $this->router->find($this->request->method(), $this->request);
            if ($route) {
                $result = $route->dispatch();
                if ($result instanceof Response) {
                    return $result->send();
                }
                return $result;
            }
        }
        return $this->response->setError('Rota não encontrada')->setStatus(404)->send();
    }
}
