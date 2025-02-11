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

        if (isset($_FILES['image'])){
            $this->data['files'] = $_FILES['image'];
        }

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

    public function getUri(): string
    {
        return $_SERVER['REQUEST_URI'];
    }

}