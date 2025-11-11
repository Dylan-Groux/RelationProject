<?php

namespace App\Services;

use App\Models\Repository\BookRepository;

class BooksPaginator
{

    public function __construct(private BookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    public function paginate(array $books, int $page, int $limit, string $search): array
    {
        $limit = 16;
        $offset = ($page - 1) * $limit;

        if ($search !== '') {
            // Recherche stricte (exacte)
            $exactBooks = array_filter($this->bookRepository->getAllBooks(), function($book) use ($search) {
                return mb_strtolower($book->getTitle()) === mb_strtolower($search);
            });
            if (!empty($exactBooks)) {
                $books = array_slice(array_values($exactBooks), $offset, $limit);
                $totalPages = max(1, ceil(count($exactBooks) / $limit));
            } else {
                // Recherche fuzzy
                $results = $this->bookRepository->searchBookByTitleFuzzy($search, 3);
                $books = array_slice($results, $offset, $limit);
                $totalPages = max(1, ceil(count($results) / $limit));
            }
        } else {
            // Pagination classique
            $allBooks = $this->bookRepository->getAllBooks();
            $totalPages = max(1, ceil(count($allBooks) / $limit));
            $books = $this->bookRepository->getBooksPaginated($limit, $offset);
        }
        return ['books' => $books, 'totalPages' => $totalPages];
    }
}
