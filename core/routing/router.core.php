<?php

class Router {
    protected array $routers = [];
    protected array $controllers = [];

    public function addRouter(string $baseUrl, Router $router): void {
        $parsedBaseUrl = UrlParser::parseRoute($baseUrl)[0];
        $this->routers[$parsedBaseUrl] = $router;
    }

    public function addController(string $postfixUrl, Controller $controller): void {
        $parsedPostfixUrl = UrlParser::parseRoute($postfixUrl)[0];
        $this->controllers[$parsedPostfixUrl] = $controller;
    }

    public function processRequest(Request $request): string {
        $basePath = $request->getPath()[0];

        if (count($request->getPath()) === 1)
            foreach ($this->controllers as $key => $value)
                if ($key === $basePath)
                    return $value->processRequest($request);

        foreach ($this->routers as $key => $value) {
            if ($key === $basePath) {
                $request->shift();
                return $value->processRequest($request);
            }
        }
        return MessageView::render('Страница не найдена', "<p>Данной страницы не существует</p>");
    }
}