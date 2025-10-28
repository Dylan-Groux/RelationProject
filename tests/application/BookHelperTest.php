<?php

use PHPUnit\Framework\TestCase;
use App\Application\BookManager;

use function PHPUnit\Framework\assertTrue;

class BookHelperTest extends TestCase
{
    public function mock()
    {
        $pdoMock = $this->createMock(PDO::class);
        $stmtMock = $this->createMock(PDOStatement::class);
        $pdoMock->method('prepare')->willReturn($stmtMock);
        $pdoMock->method('query')->willReturn($stmtMock);

        $bookHelper = new \App\Application\BookHelper($pdoMock);

        return [
            'helper' => $bookHelper
        ];
    }

    public function testShowBasicBookInfoReturnsString()
    {
        $bookHelper = $this->mock()['helper'];

        $book = new \App\Domain\Entity\Book();
        $book->setId(1);
        $book->setTitle('Livre de test');
        $book->setPicture('test.jpg');
        $book->setAuthor('Auteur Test');

        assertTrue(is_string($bookHelper->showBasicBookInfo($book)));
    }
}