<?php

class InterestsCategory {
    private string $title;
    private array $listItems;

    public function getListItems(): array { return $this->listItems; }
    public function getTitle(): string { return $this->title; }

    public function __construct(string $title) {
        $this->title = $title;
    }

    public function addListItem(string $listItem): void {
        $this->listItems[] = $listItem;
    }
}