<?php

namespace App\Model\Facade;

trait FacadeTrait {

    public function getDatabase() {
        return $this->database;
    }

    public function getData(array $where = [], $order = null, $limit = null, $offset = null) {

        $result = $this->database
                ->table(self::TABLE_NAME)
                ->where($where);

        if ($order != null) {
            $result->order($order);
        }
        if ($limit != null) {
            $result->limit($limit, $offset);
        }

        return $result;
    }

    public function getAll(array $where = [], $order = null, $limit = null, $offset = null) {
        return $this->getData($where, $order, $limit, $offset)->fetchAll();
    }

    public function getOne(array $where = [], $order = null, $limit = null, $offset = null): \Nette\Database\Table\ActiveRow|null {

        return $this->getData($where, $order, $limit, $offset)->fetch();
    }

    public function insert(array $data): int {
        $row = $this->database->table(self::TABLE_NAME)->insert($data);

        return $row->id;
    }
}
