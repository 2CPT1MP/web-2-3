<?php

interface IEntity {
    function save(): bool;
    function delete(): bool;

    static function find(Filter $filter, bool $fetchAll = true): IEntity | array ;
    static function findAll(): array;
    static function sync();
    static function setRows($row): IEntity;
}