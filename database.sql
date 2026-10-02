-- Lanka Recipe Book - import this in phpMyAdmin
CREATE DATABASE IF NOT EXISTS recipe_book CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE recipe_book;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  email VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS recipes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(120) NOT NULL,
  category VARCHAR(30) NOT NULL,
  ingredients TEXT NOT NULL,
  instructions TEXT NOT NULL,
  user_id INT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS messages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(80) NOT NULL,
  email VARCHAR(100) NOT NULL,
  message TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO recipes (title, category, ingredients, instructions) VALUES
('Kiribath (Milk Rice)', 'Rice', '2 cups raw rice\n3 cups thick coconut milk\n1 cup water\n1 tsp salt', 'Boil rice with water and salt until soft.\nAdd coconut milk and stir on low heat until thick.\nSpread on a tray, press flat and cut into diamonds.\nServe with lunu miris.'),
('Pol Sambol', 'Snacks', '1 cup grated coconut\n4 dried chillies, ground\n1 small red onion\n1 tsp lime juice\nSalt, Maldive fish (optional)', 'Grind chillies, onion and salt into a rough paste.\nMix in grated coconut by hand.\nAdd lime juice and mix well.'),
('Parippu (Dhal Curry)', 'Curry', '1 cup red lentils\n1 cup coconut milk\n1 onion, sliced\n2 green chillies\nCurry leaves, turmeric, salt', 'Boil lentils with turmeric and water until soft.\nAdd onion, chillies, curry leaves and salt.\nPour in coconut milk and simmer 5 minutes.'),
('Kottu Roti', 'Rice', '4 godamba rotis, chopped\n2 eggs\n1 cup mixed vegetables\n1 onion\n2 tbsp curry sauce\nChilli powder, salt', 'Fry onion and vegetables on a hot pan.\nScramble the eggs in.\nAdd roti and curry sauce, then chop with two blades until mixed.'),
('Kokis', 'Sweets', '2 cups rice flour\n1 cup thick coconut milk\n1 egg\nPinch of salt\nOil for frying', 'Mix flour, egg, salt and coconut milk into a smooth batter.\nHeat the kokis mould in hot oil, dip in batter.\nFry until golden and crisp.'),
('Wood Apple Juice (Divul)', 'Drinks', '1 ripe wood apple\n2 tbsp jaggery\n1 cup water', 'Scoop out the pulp and mash with water.\nStrain, add jaggery and stir.\nServe cold.');
