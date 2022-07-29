CREATE TABLE `comments` (
  `name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `homepage` varchar(512) DEFAULT NULL,
  `text` text NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip` varchar(50) NOT NULL,
  `browser` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci
