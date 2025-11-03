<?php

use App\Application\BookManager;
use PHPUnit\Framework\TestCase;
use App\Models\Repository\BookRepository;

use Tests\Fixtures\FakeBook;

class BookRepositoryTest extends TestCase
{
    public function mockBase()
    {
        $statementMock = $this->createMock(PDOStatement::class);
        $statementMock->method('fetch')->willReturn($bookData ?? [
            'id' => 1,
            'user_id' => 1,
            'title' => 'Livre de test',
            'picture' => 'test.jpg',
            'author' => 'Auteur Test',
            'availability' => 1,
            'comment' => 'Ceci est un commentaire de test',
            'created_at' => '2023-01-01 10:00:00',
            'updated_at' => '2023-01-01 10:00:00'
        ]);
        $statementMock->method('fetchAll')->willReturn([$bookData ?? [
            'id' => 1,
            'user_id' => 1,
            'title' => 'Livre de test',
            'picture' => 'test.jpg',
            'author' => 'Auteur Test',
            'availability' => 1,
            'comment' => 'Ceci est un commentaire de test',
            'created_at' => '2023-01-01 10:00:00',
            'updated_at' => '2023-01-01 10:00:00'
        ]]);
        $statementMock->method('execute')->willReturn(true);

        $pdoMock = $this->createMock(PDO::class);
        $pdoMock->method('query')->willReturn($statementMock);
        $pdoMock->method('prepare')->willReturn($statementMock);

        $bookRepository = new BookRepository($pdoMock);
    }

    public function testGetAllBooksReturnsArray()
    {
        $statementMock = $this->createMock(PDOStatement::class);
        $statementMock->method('fetchAll')
            ->willReturn(
                [
                    [
                        'id' => 1,
                        'user_id' => 1,
                        'title' => 'Livre de test',
                        'picture' => 'test.jpg',
                        'author' => 'Auteur Test',
                        'availability' => 1,
                        'comment' => 'Ceci est un commentaire de test',
                        'created_at' => '2023-01-01 10:00:00',
                        'updated_at' => '2023-01-01 10:00:00'
                    ]
                ],
                []
            );
        
        $pdoMock = $this->createMock(PDO::class);
        $pdoMock->method('query')->willReturn($statementMock);

        $bookRepository = new BookRepository($pdoMock);
        $books = $bookRepository->getAllBooks();
        $this->assertIsArray($books);
        $this->assertInstanceOf(\App\Models\Entity\Book::class, $books[0]);
    }

    public function testGetOneBookReturnsBook()
    {
        $statementMock = $this->createMock(PDOStatement::class);
        $statementMock->method('fetch')
            ->willReturn([
                'id' => 1,
                'user_id' => 1,
                'title' => 'Livre de test',
                'picture' => 'test.jpg',
                'author' => 'Auteur Test',
                'availability' => 1,
                'comment' => 'Ceci est un commentaire de test',
                'created_at' => new \DateTimeImmutable('2023-01-01 10:00:00'),
                'updated_at' => new \DateTimeImmutable('2023-01-01 10:00:00')
            ]);
        
        $pdoMock = $this->createMock(PDO::class);
        $pdoMock->method('prepare')->willReturn($statementMock);

        $bookRepository = new BookRepository($pdoMock);
        $book = $bookRepository->getBookById(1);
        $this->assertInstanceOf(\App\Models\Entity\Book::class, $book);
        $this->assertEquals(1, $book->getId());
        $this->assertEquals('Livre de test', $book->getTitle());
        $this->assertEquals('test.jpg', $book->getPicture());
        $this->assertEquals('Auteur Test', $book->getAuthor());
        $this->assertEquals(1, $book->getAvailability());
        $this->assertEquals('Ceci est un commentaire de test', $book->getComment());
        $this->assertEquals('2023-01-01 10:00:00', $book->getCreatedAt());
        $this->assertEquals('2023-01-01 10:00:00', $book->getUpdatedAt());
    }

    public function testGetOneBookReturnNullForNonExistentId()
    {
        $statementMock = $this->createMock(PDOStatement::class);
        $statementMock->method('fetch')->willReturn(false);

        $pdoMock = $this->createMock(PDO::class);
        $pdoMock->method('prepare')->willReturn($statementMock);

        $bookManager = new BookRepository($pdoMock);
        $book = $bookManager->getBookById(99999); // ID supposé non existant
        $this->assertNull($book);
    }

    public function testGetAllBooksReturnsByUserId()
    {
        $statementMock = $this->createMock(PDOStatement::class);
        $statementMock->method('fetchAll')
            ->willReturn (
                [
                    'id' => 1,
                    'user_id' => 1,
                    'title' => 'Livre de test',
                    'picture' => 'test.jpg',
                    'author' => 'Auteur Test',
                    'availability' => 1,
                    'comment' => 'Ceci est un commentaire de test',
                    'created_at' => '2023-01-01 10:00:00',
                    'updated_at' => '2023-01-01 10:00:00'
                ],
                []
            );
        
        $pdoMock = $this->createMock(PDO::class);
        $pdoMock->method('prepare')->willReturn($statementMock);

        $bookRepository = new BookRepository($pdoMock);
        $books = $bookRepository->getAllBooksByUserId(1);

        $this->assertIsArray($books);
        foreach ($books as $book){
            $this->assertInstanceOf(\App\Models\Entity\Book::class, $book);
        }
    }

    public function testDeleteBookReturnTrue()
    {
        $statementMock = $this->createMock(PDOStatement::class);
        $statementMock->method('execute')->willReturn(true);

        $pdoMock = $this->createMock(PDO::class);
        $pdoMock->method('prepare')->willReturn($statementMock);

        $bookRepository = new BookRepository($pdoMock);
        $result = $bookRepository->deleteBook(1);
        $this->assertTrue($result);
    }

    public function testDeleteBookIfFalseRedirects()
    {
        $statementMock = $this->createMock(PDOStatement::class);
        $statementMock->method('execute')->willReturn(false);

        $pdoMock = $this->createMock(PDO::class);
        $pdoMock->method('prepare')->willReturn($statementMock);

        $bookRepository = new BookRepository($pdoMock);
        $bookRedirect = $bookRepository->deleteBook(9874);
        $this->assertMatchesRegularExpression('/Location: \/Books/', $bookRedirect);
    }
}
