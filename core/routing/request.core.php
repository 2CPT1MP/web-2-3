<?php require_once('util/url-parser.core.php');

class Request {
    private string $method;
    private array $path, $params, $body;

    public function getMethod(): string { return $this->method; }
    public function getParams(): array { return $this->params; }
    public function getPath(): array { return $this->path; }
    public function getBody(): array { return $this->body; }

    public function __construct() {
        $this->method = $_SERVER["REQUEST_METHOD"];
        $this->setUrl($_SERVER["REQUEST_URI"]);
        $this->body = array_merge($_POST, $_FILES);
    }

    public function shift(): void {
        array_shift($this->path);
        if (!$this->path)
            $this->path[] = "";
    }

    public function setUrl($url): void {
        $result = UrlParser::parseRequestUrl($url);
        $this->path = $result[0]?: [];
        $this->params = $result[1]?: [];
    }
}