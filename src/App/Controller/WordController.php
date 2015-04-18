<?php

namespace App\Controller;


use App\Entity\Word;
use Symfony\Component\HttpFoundation\Response;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;

class WordController {
    public function create() {
        return 'Oh hai!';
    }

    public function show($theword) {
        $fractal = new Manager();

        // Stub out a quick words collection
        $word = new Word();
        $words = [
            [
            'definition' => $word->setDefinition("This would usually hold the definition of $theword"),
            'word' => $word->setWord($theword),
            'wordtype' => $word->setWordtype("n.")
            ]
        ];

        $resource = new Collection($words, function(array $word){
           return [
            'word'  => "Foo",
            'definition'  => "Bar",
            'wordtype'  => "Baz",
            'links' => [
                [
                    'rel' => 'self',
                    'uri' => 'words/' . $word['word']
                ]
              ]
           ];
        });

        $json = $fractal->createData($resource)->toJson();

        return $json;
    }

    public function update($id) {
        return 'Update it!';
    }

    public function delete($id) {
        return 'Gone with the wind.';
    }

    public function search() {
        return new Response("This is not an allowed operation.", 400);
    }
}