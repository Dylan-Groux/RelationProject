-- ==============================================
-- CRÉATION DES TABLES
-- ==============================================

-- Suppression des tables existantes (si relance)
DROP TABLE IF EXISTS message;
DROP TABLE IF EXISTS relation;
DROP TABLE IF EXISTS book;
DROP TABLE IF EXISTS user;

-- Table user
CREATE TABLE user (
	id INT AUTO_INCREMENT PRIMARY KEY,
	picture VARCHAR(255),
	created_at DATETIME NOT NULL,
	updated_at DATETIME NOT NULL,
	password VARCHAR(255) NOT NULL,
	nickname VARCHAR(55) NOT NULL,
	name VARCHAR(100) NOT NULL,
	email VARCHAR(255) NOT NULL
);

-- Table book
CREATE TABLE book (
	id INT AUTO_INCREMENT PRIMARY KEY,
	title VARCHAR(55) NOT NULL,
	author VARCHAR(55) NOT NULL,
	comment MEDIUMTEXT,
	availability INT NOT NULL,
	created_at DATETIME NOT NULL,
	updated_at DATETIME NOT NULL,
	user_id INT NOT NULL,
	picture VARCHAR(255),
	FOREIGN KEY (user_id) REFERENCES user(id)
);

-- Table relation
CREATE TABLE relation (
	id INT AUTO_INCREMENT PRIMARY KEY,
	first_user INT NOT NULL,
	second_user INT NOT NULL,
	created_at DATETIME NOT NULL,
	FOREIGN KEY (first_user) REFERENCES user(id),
	FOREIGN KEY (second_user) REFERENCES user(id)
);

-- Table message
CREATE TABLE message (
	id INT AUTO_INCREMENT PRIMARY KEY,
	relation_id INT NOT NULL,
	sender_id INT NOT NULL,
	statut SMALLINT NOT NULL,
	content MEDIUMTEXT NOT NULL,
	sent_at DATETIME NOT NULL,
	FOREIGN KEY (relation_id) REFERENCES relation(id),
	FOREIGN KEY (sender_id) REFERENCES user(id)
);

-- ==============================================
-- INSERTION DES DONNÉES
-- ==============================================

-- ==============================================
-- INSERTION DES DONNÉES
-- ==============================================

-- Insertion de 5 utilisateurs passionnés de lecture
-- Mots de passe: password (haché avec bcrypt)
INSERT INTO user (picture, created_at, updated_at, password, nickname, name, email) VALUES
('/assets/utils/user-avatar.png', NOW(), NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', "Sophie_Lectrice","Sophie Martin", 'sophie.martin@gmail.com'),
('/assets/utils/user-avatar.png', NOW(), NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', "Marc_Bibliophile","Marc Dubois", 'marc.dubois@outlook.fr'),
('/assets/utils/user-avatar.png', NOW(), NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', "Emma_Books","Emma Bernard", 'emma.bernard@yahoo.fr'),
('/assets/utils/user-avatar.png', NOW(), NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', "Thomas_Reader","Thomas Petit", 'thomas.petit@hotmail.com'),
('/assets/utils/user-avatar.png', NOW(), NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', "Julie_Livre","Julie Robert", 'julie.robert@gmail.com');
-- Insertion de 25 livres variés répartis sur les 5 utilisateurs
INSERT INTO book (title, author, comment, availability, created_at, updated_at, user_id, picture) VALUES
-- Livres de Sophie
('1984', 'George Orwell', 'Un chef-d\'œuvre dystopique qui reste d\'actualité. État impeccable, lecture fascinante sur le totalitarisme.', 1, NOW(), NOW(), 1, '/assets/book/book1.png'),
('L\'Étranger', 'Albert Camus', 'Roman philosophique magnifique. Quelques annotations au crayon. Idéal pour découvrir l\'absurde.', 1, NOW(), NOW(), 1, '/assets/book/book2.png'),
('Harry Potter à l\'école des sorciers', 'J.K. Rowling', 'Premier tome de la saga culte ! Parfait état, couverture souple. Idéal pour débuter la magie.', 1, NOW(), NOW(), 1, '/assets/book/book3.png'),
('Le Petit Prince', 'Antoine de Saint-Exupéry', 'Édition illustrée magnifique. Un conte poétique intemporel qui touche petits et grands.', 1, NOW(), NOW(), 1, '/assets/book/book4.png'),
('Orgueil et Préjugés', 'Jane Austen', 'Romance classique britannique. Quelques pages cornées mais histoire captivante sur l\'amour et la société.', 1, NOW(), NOW(), 1, '/assets/book/book5.png'),

-- Livres de Marc
('Le Seigneur des Anneaux', 'J.R.R. Tolkien', 'Trilogie complète en un volume. État correct, couverture rigide. L\'épopée fantasy par excellence !', 1, NOW(), NOW(), 2, '/assets/book/book6.png'),
('Les Misérables', 'Victor Hugo', 'Chef-d\'œuvre de la littérature française. Edition Pléiade, très bon état. Un roman monumental.', 1, NOW(), NOW(), 2, '/assets/book/book7.png'),
('Le Comte de Monte-Cristo', 'Alexandre Dumas', 'Histoire de vengeance captivante. Pages jaunies mais lecture addictive garantie !', 1, NOW(), NOW(), 2, '/assets/book/book8.png'),
('Dune', 'Frank Herbert', 'SF culte, univers riche et complexe. Quelques traces d\'usage mais contenu excellent.', 1, NOW(), NOW(), 2, '/assets/book/book9.png'),
('Fondation', 'Isaac Asimov', 'Premier tome de la série légendaire. Bon état général. Science-fiction intelligente et visionnaire.', 1, NOW(), NOW(), 2, '/assets/book/book10.png'),

-- Livres d\'Emma
('Les Fleurs du Mal', 'Charles Baudelaire', 'Recueil poétique sublime. Édition annotée avec préface. Pour les amateurs de poésie française.', 1, NOW(), NOW(), 3, '/assets/book/book11.png'),
('Crime et Châtiment', 'Fiodor Dostoïevski', 'Roman psychologique intense. Traduction récente, très bon état. Lecture bouleversante.', 1, NOW(), NOW(), 3, '/assets/book/book12.png'),
('Gatsby le Magnifique', 'F. Scott Fitzgerald', 'Portrait de l\'Amérique des années 20. État neuf, couverture souple. Style élégant et mélancolique.', 1, NOW(), NOW(), 3, '/assets/book/book13.png'),
('La Peste', 'Albert Camus', 'Allégorie puissante sur la condition humaine. Quelques soulignements. Particulièrement actuel.', 1, NOW(), NOW(), 3, '/assets/book/book14.png'),
('Cent ans de solitude', 'Gabriel García Márquez', 'Réalisme magique éblouissant. Bon état, traduction française. Un voyage littéraire unique.', 1, NOW(), NOW(), 3, '/assets/book/book15.png'),

-- Livres de Thomas
('Le Parfum', 'Patrick Süskind', 'Roman sensoriel fascinant sur un parfumeur meurtrier. État impeccable. Ambiance gothique prenante.', 1, NOW(), NOW(), 4, '/assets/book/book1.png'),
('L\'Alchimiste', 'Paulo Coelho', 'Conte philosophique inspirant sur la quête de soi. Couverture un peu abîmée mais contenu intact.', 1, NOW(), NOW(), 4, '/assets/book/book2.png'),
('Le Nom de la Rose', 'Umberto Eco', 'Thriller médiéval érudit. Édition poche, bon état. Mélange réussi d\'enquête et de philosophie.', 1, NOW(), NOW(), 4, '/assets/book/book3.png'),
('L\'Insoutenable Légèreté de l\'être', 'Milan Kundera', 'Roman philosophique sur l\'amour et l\'existence. Quelques annotations. Lecture profonde et touchante.', 1, NOW(), NOW(), 4, '/assets/book/book4.png'),
('Ne tirez pas sur l\'oiseau moqueur', 'Harper Lee', 'Classique américain sur le racisme. Très bon état. Message universel et émouvant.', 1, NOW(), NOW(), 4, '/assets/book/book5.png'),

-- Livres de Julie
('L\'Écume des jours', 'Boris Vian', 'Poésie surréaliste et mélancolique. État correct avec marque-pages. Histoire d\'amour bouleversante.', 1, NOW(), NOW(), 5, '/assets/book/book6.png'),
('Bilbo le Hobbit', 'J.R.R. Tolkien', 'Prélude au Seigneur des Anneaux. Édition illustrée, parfait état. Aventure fantastique accessible.', 1, NOW(), NOW(), 5, '/assets/book/book7.png'),
('Le Rouge et le Noir', 'Stendhal', 'Roman d\'apprentissage magistral. Quelques pages jaunies. Portrait de l\'ambition au XIXe siècle.', 1, NOW(), NOW(), 5, '/assets/book/book8.png'),
('La Métamorphose', 'Franz Kafka', 'Nouvelle absurde et symbolique. Format poche, état neuf. Court mais percutant.', 1, NOW(), NOW(), 5, '/assets/book/book9.png'),
('Le Vieil Homme et la Mer', 'Ernest Hemingway', 'Récit épuré sur la persévérance. Bon état général. Style minimaliste et puissant.', 1, NOW(), NOW(), 5, '/assets/book/book10.png');

-- Insertion de 5 relations entre utilisateurs lecteurs
INSERT INTO relation (first_user, second_user, created_at) VALUES
(1, 2, NOW() - INTERVAL 15 DAY),
(2, 3, NOW() - INTERVAL 10 DAY),
(3, 4, NOW() - INTERVAL 7 DAY),
(1, 4, NOW() - INTERVAL 5 DAY),
(3, 5, NOW() - INTERVAL 3 DAY);

-- Insertion de conversations réalistes entre passionnés de lecture
INSERT INTO message (relation_id, sender_id, statut, content, sent_at) VALUES
-- Conversation Sophie & Marc sur 1984
(1, 1, 1, 'Bonjour Marc ! J\'ai vu que tu as "Le Seigneur des Anneaux" en version complète. Ça m\'intéresserait beaucoup !', NOW() - INTERVAL 5 DAY),
(1, 2, 1, 'Salut Sophie ! Oui bien sûr, c\'est une super édition. Tu as des préférences en SF/Fantasy ?', NOW() - INTERVAL 5 DAY),
(1, 1, 1, 'J\'adore la fantasy ! En échange je peux te proposer "1984" ou "Harry Potter" si ça t\'intéresse ?', NOW() - INTERVAL 4 DAY),
(1, 2, 1, 'Génial ! "1984" me tente vraiment. On se retrouve où pour l\'échange ?', NOW() - INTERVAL 4 DAY),
(1, 1, 1, 'Parfait ! Je suis disponible ce weekend au café du centre-ville vers 15h ?', NOW() - INTERVAL 3 DAY),

-- Conversation Marc & Emma sur la littérature classique
(2, 2, 1, 'Hello Emma ! J\'ai remarqué que tu as "Crime et Châtiment". Je cherche justement à découvrir Dostoïevski.', NOW() - INTERVAL 3 DAY),
(2, 3, 1, 'Salut Marc ! C\'est un chef-d\'œuvre, très intense psychologiquement. Tu l\'échangerais contre quoi ?', NOW() - INTERVAL 3 DAY),
(2, 2, 1, 'J\'ai "Les Misérables" ou "Le Comte de Monte-Cristo" si les classiques français t\'intéressent ?', NOW() - INTERVAL 2 DAY),
(2, 3, 1, 'Oh oui ! "Les Misérables" me fait de l\'œil depuis longtemps. Marché conclu ! 📚', NOW() - INTERVAL 2 DAY),

-- Conversation Emma & Thomas sur la littérature moderne
(3, 3, 1, 'Coucou Thomas ! Ton "Le Parfum" m\'intrigue beaucoup. Il paraît que c\'est très immersif ?', NOW() - INTERVAL 2 DAY),
(3, 4, 1, 'Salut Emma ! Absolument, Süskind arrive à faire sentir les odeurs à travers les mots, c\'est dingue !', NOW() - INTERVAL 2 DAY),
(3, 3, 1, 'Wow ça donne envie ! Je te propose "Gatsby le Magnifique" en échange, ça te dit ?', NOW() - INTERVAL 1 DAY),
(3, 4, 1, 'Excellente idée ! J\'aime beaucoup Fitzgerald. On organise ça cette semaine ?', NOW() - INTERVAL 1 DAY),

-- Conversation Sophie & Thomas sur la philosophie
(4, 1, 1, 'Salut Thomas ! J\'ai vu que tu avais "L\'Insoutenable Légèreté de l\'être". Je cherche du Kundera !', NOW() - INTERVAL 1 DAY),
(4, 4, 1, 'Hey Sophie ! C\'est un livre magnifique, très philosophique. Tu aimes ce genre ?', NOW() - INTERVAL 1 DAY),
(4, 1, 1, 'Oui j\'adore ! "L\'Étranger" de Camus est dans mes favoris. Je peux te le proposer ?', NOW() - INTERVAL 12 HOUR),
(4, 4, 1, 'Parfait, je n\'ai jamais lu Camus. C\'est parti pour l\'échange ! 😊', NOW() - INTERVAL 10 HOUR),

-- Conversation Emma & Julie sur la poésie
(5, 3, 1, 'Bonjour Julie ! "L\'Écume des jours" est dans ma liste depuis longtemps. Il est disponible ?', NOW() - INTERVAL 8 HOUR),
(5, 5, 1, 'Salut Emma ! Oui tout à fait. Boris Vian c\'est de la pure poésie. Tu cherches quoi d\'autre ?', NOW() - INTERVAL 7 HOUR),
(5, 3, 1, 'J\'ai "Les Fleurs du Mal" de Baudelaire si tu aimes la poésie française classique ?', NOW() - INTERVAL 6 HOUR),
(5, 5, 1, 'Excellente proposition ! J\'adore Baudelaire. On échange quand tu veux ! 📖', NOW() - INTERVAL 5 HOUR);
