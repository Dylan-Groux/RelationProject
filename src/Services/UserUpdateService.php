<?php

namespace App\Services;

use App\Models\Repository\UserRepository;
use PDO;

class UserUpdateService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

   /**
     * Nettoie et valide les entrées pour un objet Contact.
     * @param string $name Le nom du contact
     * @param string $nickname Le pseudo du contact
     * @param string $email L'email du contact
     * @param string $phone_number Le numéro de téléphone du contact
     * @param int|null $id L'ID du contact (optionnel)
     * @return array Un tableau associatif avec les champs nettoyés et validés
     */
    public static function sanitizeUserObjectInput(?string $nickname, ?string $mail, ?string $password = null, ?int $id = null): array {
      $data = [];
      if ($id !== null && (!is_int($id) || $id <= 0)) {
        return [];
      }
      if ($id !== null) {
        $data['id'] = $id;
      }
      if ($nickname !== null && $nickname !== '') {
        $nickname = trim(strip_tags($nickname));
        $nickname = preg_replace('/[^a-zA-Z0-9_]/', '', $nickname);
        $data['nickname'] = $nickname;
      }
      if ($mail !== null && $mail !== '') {
        $mail = filter_var($mail, FILTER_SANITIZE_EMAIL);
        if (filter_var($mail, FILTER_VALIDATE_EMAIL) === false) {
          return [];
        }
        $data['mail'] = $mail;
      }
      if ($password !== null && $password !== '') {
        if (strlen($password) < 3) {
          return [];
        }
        $data['password'] = password_hash($password, PASSWORD_BCRYPT);
      }
      return $data;
    }
}