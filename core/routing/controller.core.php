<?php

interface Controller {
    function processRequest(Request $request): string;
}

abstract class RestController implements Controller {
    public abstract function GET(Request $request): string;
    public abstract function POST(Request $request): string;

    public function processRequest(Request $request): string {
        switch ($request->getMethod()) {
            case "GET": return $this->GET($request);
            case "POST": return $this->POST($request);
        }
        return "Endpoint was not found";
    }
}