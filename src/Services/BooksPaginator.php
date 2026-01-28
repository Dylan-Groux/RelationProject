<?php

namespace App\Services;

use App\Models\Repository\BookRepository;
use App\Models\Entity\DTO\BookWithUserDTO;

class BooksPaginator
{

    public function __construct(private BookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    /**
     * Méthode qui pagine les livres avec les infos utilisateur.
     * @param int $page
     * @param int $limit
     * @param string $search
     * @return array tableau avec les livres paginés (avec infos user) et le nombre total de pages
     */
    public function paginateWithUser(int $page, int $limit, string $search): array
    {
        $limit = 16;
        $offset = ($page - 1) * $limit;

        if ($search !== '') {
            // Recherche stricte (exacte)
            $allBooksWithUser = $this->bookRepository->searchBookByTitleWithUser($search);
            
            // Vérifier si recherche exacte
            $exactMatch = array_filter($allBooksWithUser, function($dto) use ($search) {
                return mb_strtolower($dto->book->getTitle()) === mb_strtolower($search);
            });

            if (!empty($exactMatch)) {
                $results = array_values($exactMatch);
                $booksWithUser = array_slice($results, $offset, $limit);
                $totalPages = max(1, ceil(count($results) / $limit));
            } else {
                // Recherche fuzzy
                $fuzzyBooks = $this->bookRepository->searchBookByTitleFuzzy($search, 4);
                
                // Enrichir les résultats fuzzy avec les infos utilisateur
                $booksWithUser = [];
                foreach (array_slice($fuzzyBooks, $offset, $limit) as $book) {
                    $userId = $book->getUserId();
                    $userInfo = $this->getUserInfo($userId);
                    $booksWithUser[] = new BookWithUserDTO(
                        book: $book,
                        userNickname: $userInfo['nickname'],
                        userPicture: $userInfo['picture']
                    );
                }
                $totalPages = max(1, ceil(count($fuzzyBooks) / $limit));
            }
        } else {
            // Pagination classique avec JOIN
            $allBooks = $this->bookRepository->getAllBooks();
            $totalPages = max(1, ceil(count($allBooks) / $limit));
            $booksWithUser = $this->bookRepository->getBooksPaginatedWithUser($limit, $offset);
        }
        
        return ['booksWithUser' => $booksWithUser, 'totalPages' => $totalPages];
    }

    /**
     * Récupère les infos utilisateur (helper pour la recherche fuzzy)
     * @param int $userId
     * @return array
     */
    private function getUserInfo(int $userId): array
    {
        // Tu pourrais injecter le UserRepository dans le constructeur
        $userRepo = new \App\Models\Repository\UserRepository();
        $user = $userRepo->getUserById($userId);
        return [
            'nickname' => $user ? $user->getNickname() : 'Inconnu',
            'picture' => $user ? $user->getPicture() : ''
        ];
    }
}
