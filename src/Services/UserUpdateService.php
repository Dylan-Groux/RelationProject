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
    public static function sanitizeUserObjectInput(?string $nickname, ?string $email, ?string $password = null, ?int $id = null): array {
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
      if ($email !== null && $email !== '') {
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
          return [];
        }
        $data['email'] = $email;
      }
      if ($password !== null && $password !== '') {
        if (strlen($password) < 3) {
          return [];
        }
        $data['password'] = password_hash($password, PASSWORD_BCRYPT);
      }
      return $data;
    }

    public static function sanitizeInput(?string $name, ?string $mail, ?string $password): array
    {
      if (empty($name) || empty($mail) || empty($password)) {
            return [];
        }
        if (strlen($name) < 3 || strlen($mail) < 3 || strlen($password) < 3) {
            return [];
        }
        $name = trim(strip_tags($name));
        $mail = filter_var($mail, FILTER_SANITIZE_EMAIL);
        if (filter_var($mail, FILTER_VALIDATE_EMAIL) === false) {
            return [];
        }
        $password = password_hash($password, PASSWORD_BCRYPT);
        return [
            'nickname' => $name,
            'email' => $mail,
            'password' => $password
        ];
    }

      public static function sanitizeAuthInput(?string $mail, ?string $password, ?string $nickname = null, bool $requireNickname = false, bool $hashPassword = true): array
      {
        if (empty($mail) || empty($password)) {
          return [];
        }
        if ($requireNickname && empty($nickname)) {
          return [];
        }
        if (strlen($mail) < 3 || strlen($password) < 3) {
          return [];
        }
        if ($requireNickname && strlen((string)$nickname) < 3) {
          return [];
        }
        $mail = filter_var($mail, FILTER_SANITIZE_EMAIL);
        if (filter_var($mail, FILTER_VALIDATE_EMAIL) === false) {
          return [];
        }
        $data = [
          'email' => $mail,
          'password' => $hashPassword ? password_hash($password, PASSWORD_BCRYPT) : $password
        ];
        if ($requireNickname) {
          $cleanNickname = trim(strip_tags((string)$nickname));
          $cleanNickname = preg_replace('/[^a-zA-Z0-9_]/', '', $cleanNickname);
          $data['nickname'] = $cleanNickname;
        }
        return $data;
      }
}