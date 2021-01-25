<?php

namespace App\Model\MenuItems;

use Nette;

class MenuItems
{
    private $database;

    public function __construct(Nette\Database\Connection $database)
    {
        $this->database = $database;
    }

    public function getAll()
    {
        return $this->database->query('SELECT * FROM menuItems')->fetchAll();
    }

    public function instertMenuItem( $values)
    {
        $this->database->query('INSERT INTO menuItems', $values);
    }

}