<?php

namespace App\Models\Entity\DTO;

use App\Models\Entity\Book;

class BookWithUserDTO
{
    public function __construct(
        public readonly Book $book,
        public readonly string $userNickname,
        public readonly ?string $userPicture
    ) {
    }
}
