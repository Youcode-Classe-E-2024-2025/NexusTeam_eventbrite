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

        if (isset($_FILES['media'])){
            $this->data['files'] = $_FILES['media'];
        }

        if ($this->isJson()){
            $json = json_decode(file_get_contents('php://input'), true, 512, JSON_THROW_ON_ERROR);
            if ($json){
                $this->data = array_merge($this->data, $json);
            }
        }
    }

    public function merge(array $data): void
    {
        $this->data = array_merge($this->data, $data);
    }

    public function get($key){
        return $this->data[$key] ?? null;
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

    private function isJson(): bool {
        return isset($_SERVER['CONTENT_TYPE']) &&
            str_contains($_SERVER['CONTENT_TYPE'], 'application/json');
    }

}