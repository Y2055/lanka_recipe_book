-- Lanka Recipe Book - Complete modern schema & sample recipes
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
  category VARCHAR(40) NOT NULL,
  description VARCHAR(255) DEFAULT '',
  prep_time VARCHAR(20) DEFAULT '15 mins',
  cook_time VARCHAR(20) DEFAULT '25 mins',
  servings VARCHAR(20) DEFAULT '4 servings',
  difficulty VARCHAR(20) DEFAULT 'Easy',
  spice_level VARCHAR(20) DEFAULT 'Medium',
  image_url VARCHAR(255) DEFAULT '',
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

INSERT INTO recipes (title, category, image_url, description, prep_time, cook_time, servings, difficulty, spice_level, ingredients, instructions) VALUES
('Kiribath (Milk Rice)', 'Rice', 'images/kiribath.jpg', 'Creamy coconut milk rice diamond cut, a beloved Sri Lankan celebration staple served with spicy lunu miris.', '10 mins', '30 mins', '6 servings', 'Easy', 'Mild',
'2 cups raw white rice (kekulu)
3 cups thick fresh coconut milk
1.5 cups water
1.5 tsp salt',
'1. Wash the rice thoroughly under cold running water.
2. Place the washed rice in a heavy-bottomed pot, add 1.5 cups of water and salt.
3. Bring to a vigorous boil, then lower the flame, cover, and cook until water is fully absorbed and rice is soft.
4. Pour in the thick coconut milk and stir gently with a wooden spoon on low heat.
5. Let it simmer gently while stirring continuously until the rice is velvety and thick.
6. Transfer onto a fresh banana leaf or flat platter, flatten evenly with a flat spoon, and slice into traditional diamond shapes once slightly cooled.
7. Serve with spicy red lunu miris or sweet seeni sambol.'),

('Pol Sambol', 'Snacks', 'images/pol-sambol.jpg', 'Freshly scraped coconut tossed with crushed red chillies, shallots, maldive fish and fresh lime juice.', '15 mins', '0 mins', '4 servings', 'Easy', 'Spicy',
'1 fresh coconut, finely scraped (approx. 2 cups)
4 whole dried red chillies (or 1.5 tbsp red chilli flakes)
1 tsp Kashmiri chilli powder for deep vibrant colour
1 small red onion or 4 shallots, thinly sliced
1.5 tsp freshly squeezed lime juice
1 tbsp Maldive fish flakes (optional)
Salt to taste',
'1. On a traditional stone grinder (miris gala) or mortar and pestle, crush the dried chillies, shallots, and salt into a coarse paste.
2. Add the Maldive fish flakes if using, and crush lightly.
3. Incorporate the freshly scraped coconut gradually, mixing by hand or pestle to blend the flavours without squeezing out all coconut oil.
4. Squeeze fresh lime juice over the sambol and give it a final mix until bright orange-red.
5. Serve fresh alongside Kiribath, string hoppers, bread, or warm rice and curry.'),

('Parippu (Dhal Curry)', 'Curry', 'images/parippu.jpg', 'Silky red lentils simmered in rich coconut milk with tempered mustard seeds, onions and fresh curry leaves.', '10 mins', '20 mins', '4 servings', 'Easy', 'Mild',
'1 cup red split lentils (masoor dhal)
1.5 cups water
1 cup thick coconut milk
1 small red onion, finely sliced
2 green chillies, slit lengthwise
2 cloves garlic, sliced
1/2 tsp ground turmeric
1 tsp unroasted curry powder
1 sprig fresh curry leaves (karapincha)
1 tsp black mustard seeds
1 dried red chilli, halved
2 tbsp coconut oil
Salt to taste',
'1. Rinse red lentils thoroughly until water runs clear; drain and transfer to a clay pot or saucepan.
2. Add water, sliced onion, garlic, green chillies, turmeric, curry powder, and half the curry leaves.
3. Simmer over medium flame until lentils are soft and tender (approx. 10-12 mins).
4. Pour in the thick coconut milk and season with salt. Simmer gently on low heat for 5 minutes without boiling vigorously.
5. In a separate small skillet, heat coconut oil. Splutter mustard seeds, then add dried red chilli and remaining curry leaves until aromatic and crispy.
6. Pour the sizzling tempered spices over the creamy dhal curry and gently stir.'),

('Kottu Roti', 'Rice', 'images/kottu-roti.jpg', 'Iconic Sri Lankan street dish made with chopped godamba flatbread, vegetables, eggs, spices and aromatic curry gravy.', '15 mins', '15 mins', '3 servings', 'Medium', 'Spicy',
'4 godamba rotis, thinly sliced into strips
2 large eggs
1 cup shredded cooked chicken (or beef/seafood)
1 cup thinly shredded cabbage, carrots, and leeks
1 medium red onion, sliced
2 green chillies, chopped
3 tbsp spicy curry gravy
1 tsp black pepper powder
1 tsp chilli flakes
2 tbsp vegetable oil
Salt to taste',
'1. Heat oil on a large flat griddle (tava) or large heavy wok over high heat.
2. Sauté onions, green chillies, and shredded mixed vegetables until crisp-tender (approx. 2 mins).
3. Push vegetables to one side, crack eggs onto the empty space, and scramble vigorously.
4. Add the shredded meat and chopped godamba roti strips.
5. Pour in spicy curry gravy, chilli flakes, black pepper, and salt.
6. Using two metal dough choppers or firm spatulas, rhythmically chop and toss all ingredients together over high heat until piping hot and thoroughly blended.
7. Serve immediately with extra curry gravy on the side and lime wedges.'),

('Kokis', 'Sweets', 'images/kokis.jpg', 'Delightfully crisp, flower-shaped deep-fried batter made of rice flour and coconut milk, a hallmark of Sinhala & Tamil New Year.', '20 mins', '25 mins', '8 servings', 'Medium', 'Mild',
'2 cups fine rice flour
1 large egg
1 cup thick fresh coconut milk
1/4 tsp ground turmeric (for golden color)
1/2 tsp salt
1 tsp sugar
Coconut oil or vegetable oil for deep frying',
'1. In a mixing bowl, combine rice flour, egg, salt, sugar, and turmeric.
2. Slowly whisk in coconut milk to form a smooth, lump-free batter that coats the back of a spoon.
3. Heat oil in a deep frying wok. Submerge the traditional brass kokis mould in hot oil for 2-3 minutes so it gets piping hot.
4. Dip the hot mould into the batter, ensuring batter does NOT overflow the top rim of the mould.
5. Lower the mould into the hot frying oil. After 15-20 seconds, gently jiggle with a skewer until the kokis releases into the oil.
6. Fry until crisp and golden on both sides, then drain on absorbent paper towels.
7. Allow to cool completely before storing in an airtight tin for lasting crunch.'),

('Wood Apple Juice (Divul)', 'Drinks', 'images/woodapple.jpg', 'A refreshing sweet-and-tangy tropical drink made with wild wood apple pulp, coconut milk and pure jaggery.', '10 mins', '0 mins', '2 glasses', 'Easy', 'Mild',
'1 ripe wood apple (divul)
3 tbsp grated kitul jaggery or palm sugar
1 cup coconut milk (or cold water)
1/4 tsp lime juice
Pinch of black salt or table salt
Crushed ice cubes',
'1. Crack open the hard wood apple shell and scoop out the fragrant brown pulp with a spoon.
2. Place pulp in a bowl, add 1/2 cup of water, and mash with fingers or a fork to loosen the fibers and seeds.
3. Pass through a stainless steel sieve, pressing the pulp firmly with the back of a ladle. Discard the seeds and coarse fibers.
4. Whisk the extracted puree with coconut milk, grated jaggery, salt, and lime juice until jaggery is dissolved.
5. Pour over crushed ice in tall glasses and serve ice cold.'),

('Spicy Sri Lankan Chicken Curry', 'Curry', 'images/chicken-curry.jpg', 'Tender chicken pieces simmered in dark roasted Ceylon curry powder, coconut milk, lemongrass, and aromatic spices in a clay pot.', '20 mins', '35 mins', '5 servings', 'Medium', 'Spicy',
'800g fresh chicken pieces, skinless
2.5 tbsp dark roasted Sri Lankan curry powder
1 tsp chilli powder
1/2 tsp ground turmeric
1 stalk fresh lemongrass, bruised
2 sprigs fresh curry leaves
1 piece pandan leaf (rampe)
1 medium red onion, sliced
4 cloves garlic, minced
1 inch fresh ginger, finely minced
1 cup thin coconut milk
1 cup thick coconut milk
2 tbsp coconut oil
1 cinnamon stick
Salt to taste',
'1. Season the chicken pieces thoroughly with roasted curry powder, chilli powder, turmeric, and 1 tsp salt. Marinate for 20 minutes.
2. In a clay pot, heat coconut oil over medium flame. Add cinnamon, curry leaves, rampe, lemongrass, onions, garlic, and ginger. Sauté until fragrant and caramelized.
3. Add the marinated chicken pieces and sear for 5 minutes until meat is lightly browned.
4. Pour in thin coconut milk, cover with lid, and simmer on gentle heat for 20 minutes until chicken is tender.
5. Stir in thick coconut milk, leave pot uncovered, and simmer on low heat for 8-10 minutes until oil separates and gravy thickens.
6. Serve hot with red rice, pol roti, or string hoppers.'),

('Authentic Sri Lankan Watalappam', 'Sweets', 'images/watalappam.jpg', 'Traditional spiced steamed custard crafted with dark kitul jaggery, thick coconut milk, nutmeg, cardamom, and toasted cashew nuts.', '25 mins', '45 mins', '6 servings', 'Medium', 'Mild',
'300g pure kitul jaggery, finely grated
5 large farm eggs
1 cup thick fresh coconut milk
1/2 tsp freshly ground cardamom
1/4 tsp freshly grated nutmeg
1 tsp pure vanilla extract
Pinch of sea salt
2 tbsp toasted cashew nut halves',
'1. Melt grated kitul jaggery with 3 tbsp water in a small saucepan over low heat until completely smooth. Strain through a fine sieve and let cool.
2. In a large mixing bowl, lightly whisk the eggs until blended (do not over-whip or create froth).
3. Gradually pour the cooled jaggery syrup into the beaten eggs while gently stirring.
4. Add thick coconut milk, cardamom, nutmeg, vanilla, and salt; stir until uniform.
5. Pour the custard mixture through a fine sieve into a heat-resistant ceramic or glass dish.
6. Cover tightly with aluminium foil and steam over simmering water for 40-45 minutes until set in the center.
7. Cool to room temperature, scatter toasted cashews on top, and chill in refrigerator for 2 hours before slicing into luxurious wedges.');
