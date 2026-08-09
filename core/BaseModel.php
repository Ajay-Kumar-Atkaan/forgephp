<?php
declare(strict_types=1);

namespace Core;
use Core\Database;

class BaseModel extends Database
{
    public function __construct()
    {
        parent::__construct();
    }
}