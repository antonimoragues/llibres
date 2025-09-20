-- Sample MariaDB script generated from books.json data
-- Normalized to use a dedicated genres table

DROP TABLE IF EXISTS `books`;
DROP TABLE IF EXISTS `genres`;

CREATE TABLE `genres` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_genres_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `books` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `author` VARCHAR(255) NOT NULL,
  `publication_year` SMALLINT NOT NULL,
  `genre_id` INT NOT NULL,
  `image` VARCHAR(255) NOT NULL,
  `pages` SMALLINT NOT NULL,
  `description` TEXT NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_books_genre_id` (`genre_id`),
  CONSTRAINT `fk_books_genre` FOREIGN KEY (`genre_id`) REFERENCES `genres` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `genres` (`id`, `name`) VALUES
  (1, 'Adventure'),
  (2, 'Classic'),
  (3, 'Dystopian'),
  (4, 'Historical'),
  (5, 'Romance');

INSERT INTO `books` (
  `id`, `title`, `author`, `publication_year`, `genre_id`, `image`, `pages`, `description`
) VALUES
  (1, '1984', 'George Orwell', 1949, 3, 'images/1984.jpg', 328, 'A dystopian novel set in a totalitarian society ruled by Big Brother, exploring themes of surveillance and control.'),
  (2, 'The Great Gatsby', 'F. Scott Fitzgerald', 1925, 2, 'images/gatsby.jpg', 180, 'A classic novel about the mysterious Jay Gatsby and his unrelenting passion for Daisy Buchanan, set in the Roaring Twenties.'),
  (3, 'To Kill a Mockingbird', 'Harper Lee', 1960, 2, 'images/mockingbird.jpg', 281, 'A powerful story of racial injustice and childhood innocence in the Deep South, seen through the eyes of young Scout Finch.'),
  (4, 'Pride and Prejudice', 'Jane Austen', 1813, 5, 'images/pride.jpg', 279, 'A romantic novel that explores the themes of love, reputation, and class in 19th-century England.'),
  (5, 'Moby-Dick', 'Herman Melville', 1851, 1, 'images/mobydick.jpg', 635, 'The epic tale of Captain Ahab''s obsessive quest to hunt the white whale, Moby-Dick.'),
  (6, 'War and Peace', 'Leo Tolstoy', 1869, 4, 'images/warandpeace.jpg', 1225, 'A sweeping historical novel that intertwines the lives of several families during the Napoleonic Wars.'),
  (7, 'The Catcher in the Rye', 'J.D. Salinger', 1951, 2, 'images/catcher.jpg', 214, 'A coming-of-age story about teenage alienation and rebellion, narrated by Holden Caulfield.'),
  (8, 'Brave New World', 'Aldous Huxley', 1932, 3, 'images/bravenewworld.jpg', 268, 'A dystopian vision of a future society driven by technology, conditioning, and the loss of individuality.');
