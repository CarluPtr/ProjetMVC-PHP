<?php

class Manager
{
    protected function dbConnect()
    {
        $db = new PDO('mysql:host=localhost;dbname=blogp5;charset=utf8', 'root', '');

        return $db;
    }
}
