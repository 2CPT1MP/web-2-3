<?php

class Limit {
    private int $pageNumber, $recordsPerPage;

    public function getPageNumber(): int { return $this->pageNumber; }
    public function getRecordsPerPage(): int { return $this->recordsPerPage; }

    public function __construct(int $pageNumber, int $recordPerPage) {
        $this->pageNumber = $pageNumber;
        $this->recordsPerPage = $recordPerPage;
    }

    public function toSql(): string {
        $limit = $this->recordsPerPage;
        $offset = $this->pageNumber * $this->recordsPerPage - $this->recordsPerPage;
        return "LIMIT $limit OFFSET $offset";
    }
}


class Filter {
    private array $conditions = [];
    private Limit | null $limit = null;

    public function getLimit(): Limit | null { return $this->limit; }
    public function getConditions(): array { return $this->conditions; }

    public function addCondition(string $field, mixed $value): void {
        $this->conditions[$field] = $value;
    }

    public function setLimit(Limit $limit): void {
        $this->limit = $limit;
    }

    public function getSqlWhereCondition(): string {
        if (count($this->conditions) < 1) return "";
        $condition = "WHERE";
        $i = 1;

        foreach ($this->conditions as $field => $value) {
            $condition .= ($value === null)? " $field IS :$field" : " $field = :$field";

            if ($i < count($this->conditions)) {
                $condition .= " AND";
                $i++;
            }
        }
        return $condition;
    }
}