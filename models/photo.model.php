<?php

class Photo {
    private string $title, $path;

    public function getTitle(): string { return $this->title; }
    public function getPath(): string { return $this->path; }

    public function __construct(string $title, string $path) {
        $this->title = $title;
        $this->path = $path;
    }
}