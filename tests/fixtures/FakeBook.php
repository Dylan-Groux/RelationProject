<?php

namespace Tests\Fixtures;

use App\Domain\Entity\Book;

class FakeBook
{
    public static function create(): Book
    {
        $book = new Book();
        $book->setId(1);
        $book->setTitle('Livre de test');
        $book->setPicture('test.jpg');
        $book->setAuthor('Auteur Test');
        $book->setAvailability(1);
        $book->setComment('Ceci est un commentaire de test');
        $book->setCreatedAt(new \DateTime('2023-01-01 10:00:00'));
        $book->setUpdatedAt(new \DateTime('2023-01-01 10:00:00'));
        $book->setUserId(1);
        return $book;
    }
}
