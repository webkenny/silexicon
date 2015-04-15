<?php

namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;

class WordController {
    public function create() {
        return 'Oh hai!';
    }

    public function show($id) {
        return "This would show a specific user... $id";
    }

    public function update($id) {
        return 'Update it!';
    }

    public function delete($id) {
        return 'Gone with the wind.';
    }

    public function search() {
        // Every day I'm hustlin'
    }
}