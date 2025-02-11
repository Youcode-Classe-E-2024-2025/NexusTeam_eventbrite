<?php

namespace App\Core;

class Request
{

    private array $data = [];

    public function __construct()
    {
        foreach ($_GET as $key => $value) {
            $this->data[$key] = $value;
        }

        foreach ($_POST as $key => $value) {
            $this->data[$key] = $value;
        }

        $this->data['files'] = $_FILES;

    }

    public function merge(array $data): void
    {
        $this->data = array_merge($this->data, $data);
    }

    public function get($key){
        return $this->data[$key];
    }

    public function all(): array{
        return $this->data;
    }

    public function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    public function isGet(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }



}