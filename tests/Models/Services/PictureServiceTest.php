<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\AllowMockObjectsWithoutExpectations;

use App\Models\Entity\Book;
use App\Models\Repository\BookRepository;
use App\Models\Repository\UserRepository;

class PictureServiceTest extends TestCase
{
    public function mock()
    {
        $pdoMock = $this->createMock(PDO::class);

        $fileBookExistsMock = function($path) {
            return $path === dirname(__DIR__, 3) . '/public/uploads/books/test.jpg';
        };

        $pictureService = $this->createPictureServiceWithMocks();

        $book = $this->makeBook('/public/uploads/books/test.jpg');

        return [
            'service' => $pictureService,
            'book' => $book
        ];
    }

    public function createPictureServiceWithMocks(
        ?PDO $pdoMock = null,
        ?callable $fileExistsMock = null,
        ?BookRepository $bookRepoMock = null,
        ?UserRepository $userRepoMock = null
    ) {
        $pdoMock = $pdoMock ?? $this->createMock(PDO::class);
        $bookRepoMock = $bookRepoMock ?? $this->createMock(BookRepository::class);
        $userRepoMock = $userRepoMock ?? $this->createMock(UserRepository::class);

        return new \App\Models\Services\PictureService(
            $pdoMock,
            null,
            null,
            null,
            null,
            null,
            null,
            $fileExistsMock,
            $bookRepoMock,
            $userRepoMock
        );
    }

    private function makeBook(string $picture): \App\Models\Entity\Book
    {
        return new Book([
            'id' => 5,
            'user_id' => 1,
            'title' => 'Test',
            'picture' => $picture,
            'author' => 'X',
            'availability' => 1, 
            'comment' => '',
            'created_at' => '2023-01-01 00:00:00',
            'updated_at' => '2023-01-01 00:00:00',
        ]);
    }

    #[AllowMockObjectsWithoutExpectations]
    public function testDeleteTheLastPictureThrowsExceptionWhenNoPicture()
    {
        $pictureService = $this->mock()['service'];

        $this->expectException(\Exception::class);
        $pictureService->deleteTheLastPicture(null, null);
    }

    #[AllowMockObjectsWithoutExpectations]
    public function testDeleteTheLastPictureHaveExactlyPath()
    {
        $pdoMock = $this->createMock(PDO::class);

        $fakeBook = $this->mock()['book'];

        $bookRepoMock = $this->createMock(BookRepository::class);
        $bookRepoMock->method('getBookById')->willReturn($fakeBook);

        $capturedPath = null;
        $fileBookExistsMock = function($path) use (&$capturedPath) {
            $capturedPath = $path;
            return false;
        };

        $pictureService = $this->createPictureServiceWithMocks(
            $pdoMock,
            $fileBookExistsMock,
            $bookRepoMock
        );

        $pictureService->deleteTheLastPicture(1, null);

        $this->assertStringEndsWith('/public/uploads/books/test.jpg', $capturedPath);
    }
}