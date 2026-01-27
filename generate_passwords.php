<?php
// Script pour générer des hashs de mots de passe

$passwords = [
    'sophie123',
    'marc123',
    'emma123',
    'thomas123',
    'julie123'
];

echo "Hashs de mots de passe pour la fixture :\n\n";

foreach ($passwords as $index => $password) {
    $hash = password_hash($password, PASSWORD_BCRYPT);
    $user = $index + 1;
    echo "User $user (mot de passe: $password) :\n";
    echo "'$hash',\n\n";
}

echo "\n\nPour tester, utilisez ces identifiants :\n";
echo "Email: sophie.martin@gmail.com / Mot de passe: sophie123\n";
echo "Email: marc.dubois@outlook.fr / Mot de passe: marc123\n";
echo "Email: emma.bernard@yahoo.fr / Mot de passe: emma123\n";
echo "Email: thomas.petit@hotmail.com / Mot de passe: thomas123\n";
echo "Email: julie.robert@gmail.com / Mot de passe: julie123\n";
