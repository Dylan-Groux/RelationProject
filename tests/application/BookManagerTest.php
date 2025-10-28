<?php

use PHPUnit\Framework\TestCase;
use App\Application\BookManager;

class BookManagerTest extends TestCase
{

    public function mockBase()
    {
        $pdoMock = $this->createMock(PDO::class);
        $stmtMock = $this->createMock(PDOStatement::class);
        $pdoMock->method('prepare')->willReturn($stmtMock);
        $pdoMock->method('query')->willReturn($stmtMock);

        $book = new \App\Domain\Entity\Book();
        $book->setId(1);
        $book->setTitle('Livre de test');
        $book->setPicture('test.jpg');
        $book->setAuthor('Auteur Test');
        $book->setAvailability(1);
        $book->setComment('Ceci est un commentaire de test');
        $book->setCreatedAt(new \DateTime('2023-01-01 10:00:00'));
        $book->setUpdatedAt(new \DateTime('2023-01-01 10:00:00'));
        $book->setUserId(1);

        $bookManager = new BookManager($pdoMock);

        return [
            'manager' => $bookManager,
            'book' => $book
        ];
    }

    public function testGetAllBooksReturnsArray()
    {
        $bookManager = $this->mockBase()['manager'];
        $books = $bookManager->getAllBooks();
        $this->assertIsArray($books);
    }

    public function testGetOneBookReturnsBook()
    {
        $bookManager = $this->mockBase()['manager'];
        $book = $bookManager->getBookById(1);
        $this->assertInstanceOf(\App\Domain\Entity\Book::class, $book);
    }

    public function testGetOneBookReturnNullForNonExistentId()
    {
        $bookManager = $this->mockBase()['manager'];
        $book = $bookManager->getBookById(99999); // ID supposé non existant
        $this->assertNull($book);
    }

    public function testGetAllBooksReturnsByUserId()
    {
        $bookManager = $this->mockBase()['manager'];
        $books = $bookManager->getAllBooksByUserId(1);
        $this->assertIsArray($books);
        foreach ($books as $book){
            $this->assertInstanceOf(\App\Domain\Entity\Book::class, $book);
        }
    }

    public function testDeleteBookReturnTrue()
    {
        $mock = $this->mockBase();
        $bookManager = $mock['manager'];
        $result = $bookManager->deleteBook(1);
        $this->assertTrue($result);
    }

    public function testUpdateBookReturnTrue()
    {
        $bookManager = $this->mockBase()['manager'];
        $book = $this->mockBase()['book'];
        $book->setId(1);
        $book->setTitle("Updated Title");
        $result = $bookManager->updateBook($book);
        $this->assertTrue($result);
    }

    public function testUpdateBookChangeOnlyFieldHere()
    {
        $mock = $this->mockBase();
        $bookManager = $mock['manager'];
        $book = $mock['book'];

        $oldAuthor = $book->getAuthor();

        $newTitle = "Updated Title";
        $book->setTitle($newTitle);
        $result = $bookManager->updateBook($book);

        $this->assertTrue($result);
        
        $updateBook = $bookManager->getBookById(1);

        $this->assertEquals($newTitle, $updateBook->getTitle());
        $this->assertEquals($oldAuthor, $updateBook->getAuthor());
    }


}
