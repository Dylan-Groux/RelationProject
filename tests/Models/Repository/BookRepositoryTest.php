<?php
require_once __DIR__ . '/../../../vendor/autoload.php';

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
        $statementMock->method('fetch')
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
        $this->assertEquals('disponible', $book->getAvailability());
        $this->assertEquals('Ceci est un commentaire de test', $book->getComment());
        $this->assertEquals(new \DateTimeImmutable('2023-01-01 10:00:00'), $book->getCreatedAt());
        $this->assertEquals(new \DateTimeImmutable('2023-01-01 10:00:00'), $book->getUpdatedAt());
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

    public function testDeleteBookWhitNoIdReturnFalse()
    {
        $statementMock = $this->createMock(PDOStatement::class);
        $statementMock->method('execute')->willReturn(false);

        $pdoMock = $this->createMock(PDO::class);
        $pdoMock->method('prepare')->willReturn($statementMock);

        $bookRepository = new BookRepository($pdoMock);
        $bookRedirect = $bookRepository->deleteBook(9874);
        $this->assertFalse($bookRedirect);
    }

    public function testSearchBookByTitleReturnBook()
    {
        $statementMock = $this->createMock(PDOStatement::class);
        $statementMock->method('fetch')
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
        $pdoMock->method('prepare')->willReturn($statementMock);

        $bookRepository = new BookRepository($pdoMock);
        $books = $bookRepository->searchBookByTitle('test');
        $this->assertIsArray($books);
        $this->assertInstanceOf(\App\Models\Entity\Book::class, $books[0]);
    }

    public function testSearchBookByTitleWithNoResultReturnsEmptyArray()
    {
        $statementMock = $this->createMock(PDOStatement::class);
        $statementMock->method('fetchAll')->willReturn([]);

        $pdoMock = $this->createMock(PDO::class);
        $pdoMock->method('prepare')->willReturn($statementMock);

        $bookRepository = new BookRepository($pdoMock);
        $books = $bookRepository->searchBookByTitle('nonexistenttitle');
        $this->assertIsArray($books);
        $this->assertEmpty($books);
    }

    // ========== TESTS POUR createBook() ==========
    
    public function testCreateBookWithValidDataReturnsId()
    {
        $bookData = [
            'title' => 'Nouveau Livre',
            'author' => 'Auteur Test',
            'picture' => 'picture.jpg',
            'availability' => 1,
            'comment' => 'Un commentaire',
            'user_id' => 1
        ];
        
        $book = new \App\Models\Entity\Book($bookData);
        
        $statementMock = $this->createMock(\PDOStatement::class);
        $statementMock->expects($this->once())
            ->method('execute')
            ->willReturn(true);
        
        $pdoMock = $this->createMock(\PDO::class);
        $pdoMock->expects($this->once())
            ->method('prepare')
            ->willReturn($statementMock);
        $pdoMock->expects($this->once())
            ->method('lastInsertId')
            ->willReturn('42');
        
        $bookRepository = new BookRepository($pdoMock);
        $result = $bookRepository->createBook($book);
        
        $this->assertSame(42, $result);
    }

    public function testCreateBookWithEmptyTitleReturnsFalse()
    {
        $bookData = [
            'title' => '   ',
            'author' => 'Auteur Test',
            'picture' => 'picture.jpg',
            'availability' => 1,
            'comment' => 'Commentaire',
            'user_id' => 1
        ];
        
        $book = new \App\Models\Entity\Book($bookData);
        
        $pdoMock = $this->createMock(\PDO::class);
        $pdoMock->expects($this->never())->method('prepare');
        
        $bookRepository = new BookRepository($pdoMock);
        $result = $bookRepository->createBook($book);
        
        $this->assertFalse($result);
    }

    public function testCreateBookWithEmptyAuthorReturnsFalse()
    {
        $bookData = [
            'title' => 'Titre',
            'author' => '',
            'picture' => 'picture.jpg',
            'availability' => 1,
            'comment' => 'Commentaire',
            'user_id' => 1
        ];
        
        $book = new \App\Models\Entity\Book($bookData);
        
        $pdoMock = $this->createMock(\PDO::class);
        $pdoMock->expects($this->never())->method('prepare');
        
        $bookRepository = new BookRepository($pdoMock);
        $result = $bookRepository->createBook($book);
        
        $this->assertFalse($result);
    }

    public function testCreateBookWithInvalidUserIdReturnsFalse()
    {
        $bookData = [
            'title' => 'Titre',
            'author' => 'Auteur',
            'picture' => 'picture.jpg',
            'availability' => 1,
            'comment' => 'Commentaire',
            'user_id' => 0
        ];
        
        $book = new \App\Models\Entity\Book($bookData);
        
        $pdoMock = $this->createMock(\PDO::class);
        $pdoMock->expects($this->never())->method('prepare');
        
        $bookRepository = new BookRepository($pdoMock);
        $result = $bookRepository->createBook($book);
        
        $this->assertFalse($result);
    }

    public function testCreateBookWithTitleTooLongReturnsFalse()
    {
        $longTitle = str_repeat('a', 256);
        $bookData = [
            'title' => $longTitle,
            'author' => 'Auteur',
            'picture' => 'picture.jpg',
            'availability' => 1,
            'comment' => 'Commentaire',
            'user_id' => 1
        ];
        
        $book = new \App\Models\Entity\Book($bookData);
        
        $pdoMock = $this->createMock(\PDO::class);
        $pdoMock->expects($this->never())->method('prepare');
        
        $bookRepository = new BookRepository($pdoMock);
        $result = $bookRepository->createBook($book);
        
        $this->assertFalse($result);
    }

    public function testCreateBookWhenExecuteFailsReturnsFalse()
    {
        $bookData = [
            'title' => 'Titre',
            'author' => 'Auteur',
            'picture' => 'picture.jpg',
            'availability' => 1,
            'comment' => 'Commentaire',
            'user_id' => 1
        ];
        
        $book = new \App\Models\Entity\Book($bookData);
        
        $statementMock = $this->createMock(\PDOStatement::class);
        $statementMock->expects($this->once())
            ->method('execute')
            ->willReturn(false);
        
        $pdoMock = $this->createMock(\PDO::class);
        $pdoMock->method('prepare')->willReturn($statementMock);
        
        $bookRepository = new BookRepository($pdoMock);
        $result = $bookRepository->createBook($book);
        
        $this->assertFalse($result);
    }

    public function testCreateBookWithPDOExceptionReturnsFalse()
    {
        $bookData = [
            'title' => 'Titre',
            'author' => 'Auteur',
            'picture' => 'picture.jpg',
            'availability' => 1,
            'comment' => 'Commentaire',
            'user_id' => 1
        ];
        
        $book = new \App\Models\Entity\Book($bookData);
        
        $pdoMock = $this->createMock(\PDO::class);
        $pdoMock->method('prepare')
            ->willThrowException(new \PDOException('Database error'));
        
        $bookRepository = new BookRepository($pdoMock);
        $result = $bookRepository->createBook($book);
        
        $this->assertFalse($result);
    }

    // ========== TESTS POUR updateBook() ==========

    public function testUpdateBookWithValidDataReturnsTrue()
    {
        $data = [
            'id' => 1,
            'title' => 'Titre mis à jour',
            'author' => 'Auteur mis à jour'
        ];
        
        $statementMock = $this->createMock(\PDOStatement::class);
        $statementMock->expects($this->once())
            ->method('execute')
            ->willReturn(true);
        
        $pdoMock = $this->createMock(\PDO::class);
        $pdoMock->expects($this->once())
            ->method('prepare')
            ->with($this->stringContains('UPDATE book'))
            ->willReturn($statementMock);
        
        $bookRepository = new BookRepository($pdoMock);
        $result = $bookRepository->updateBook($data);
        
        $this->assertTrue($result);
    }

    public function testUpdateBookWithoutIdThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Impossible d'update : l'entité n'a pas d'ID.");
        
        $data = [
            'title' => 'Titre',
            'author' => 'Auteur'
        ];
        
        $pdoMock = $this->createMock(\PDO::class);
        $bookRepository = new BookRepository($pdoMock);
        $bookRepository->updateBook($data);
    }

    public function testUpdateBookWithOnlyNullFieldsReturnsFalse()
    {
        $data = [
            'id' => 1,
            'title' => null,
            'author' => null,
            'comment' => null
        ];
        
        $pdoMock = $this->createMock(\PDO::class);
        $pdoMock->expects($this->never())->method('prepare');
        
        $bookRepository = new BookRepository($pdoMock);
        $result = $bookRepository->updateBook($data);
        
        $this->assertFalse($result);
    }

    public function testUpdateBookIgnoresNullFields()
    {
        $data = [
            'id' => 1,
            'title' => 'Nouveau titre',
            'author' => null,
            'comment' => null
        ];
        
        $statementMock = $this->createMock(\PDOStatement::class);
        $statementMock->method('execute')->willReturn(true);
        
        $pdoMock = $this->createMock(\PDO::class);
        $pdoMock->expects($this->once())
            ->method('prepare')
            ->with($this->logicalAnd(
                $this->stringContains('title = :title'),
                $this->logicalNot($this->stringContains('author = :author'))
            ))
            ->willReturn($statementMock);
        
        $bookRepository = new BookRepository($pdoMock);
        $result = $bookRepository->updateBook($data);
        
        $this->assertTrue($result);
    }

    public function testUpdateBookWithPDOExceptionReturnsFalse()
    {
        $data = [
            'id' => 1,
            'title' => 'Titre'
        ];
        
        $pdoMock = $this->createMock(\PDO::class);
        $pdoMock->method('prepare')
            ->willThrowException(new \PDOException('Database error'));
        
        $bookRepository = new BookRepository($pdoMock);
        $result = $bookRepository->updateBook($data);
        
        $this->assertFalse($result);
    }

    // ========== TESTS POUR AUTRES MÉTHODES ==========

    public function testGetUserWithBookIdReturnsUserId()
    {
        $statementMock = $this->createMock(\PDOStatement::class);
        $statementMock->method('fetch')
            ->willReturn(['user_id' => 42]);
        
        $pdoMock = $this->createMock(\PDO::class);
        $pdoMock->method('prepare')->willReturn($statementMock);
        
        $bookRepository = new BookRepository($pdoMock);
        $result = $bookRepository->getUserWithBookId(1);
        
        $this->assertSame(42, $result);
    }

    public function testGetUserWithBookIdReturnsNullWhenNotFound()
    {
        $statementMock = $this->createMock(\PDOStatement::class);
        $statementMock->method('fetch')->willReturn(false);
        
        $pdoMock = $this->createMock(\PDO::class);
        $pdoMock->method('prepare')->willReturn($statementMock);
        
        $bookRepository = new BookRepository($pdoMock);
        $result = $bookRepository->getUserWithBookId(999);
        
        $this->assertNull($result);
    }

    public function testGetLastBooksReturnsLimitedArray()
    {
        $statementMock = $this->createMock(\PDOStatement::class);
        $statementMock->method('fetch')
            ->willReturnOnConsecutiveCalls(
                [
                    'id' => 1,
                    'user_id' => 1,
                    'title' => 'Livre 1',
                    'picture' => 'pic1.jpg',
                    'author' => 'Auteur 1',
                    'availability' => 1,
                    'comment' => 'Comment 1',
                    'created_at' => '2023-01-01 10:00:00',
                    'updated_at' => '2023-01-01 10:00:00'
                ],
                [
                    'id' => 2,
                    'user_id' => 1,
                    'title' => 'Livre 2',
                    'picture' => 'pic2.jpg',
                    'author' => 'Auteur 2',
                    'availability' => 1,
                    'comment' => 'Comment 2',
                    'created_at' => '2023-01-02 10:00:00',
                    'updated_at' => '2023-01-02 10:00:00'
                ],
                false
            );
        
        $pdoMock = $this->createMock(\PDO::class);
        $pdoMock->method('prepare')->willReturn($statementMock);
        
        $bookRepository = new BookRepository($pdoMock);
        $books = $bookRepository->getLastBooks(2);
        
        $this->assertIsArray($books);
        $this->assertCount(2, $books);
        $this->assertInstanceOf(\App\Models\Entity\Book::class, $books[0]);
    }
}