<?php

namespace App\Repository;

use Silex\Provider\DoctrineServiceProvider;

class WordRepository implements RepositoryInterface {
    protected $db;

    public function __construct() {

    }

    public function read($fields) {

    }
}