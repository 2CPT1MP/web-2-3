<?php require_once('interests-category.model.php');

class Interests {
    private array $interests = [];
    public function getInterests(): array { return $this->interests; }

    public function createCategory(string $categoryName): void {
        $this->interests[$categoryName] = new InterestsCategory($categoryName);
    }

    public function addItemToCategory(string $categoryName, string $item): void {
        $this->interests[$categoryName]->addListItem($item);
    }
}