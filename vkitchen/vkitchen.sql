

-- --------------------------------------------------------

--
-- Table structure for table `recipes`
--

CREATE TABLE `recipes` (
  `rid` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(300) DEFAULT NULL,
  `type` enum('French','Italian','Chinese','Indian','Mexican','Others') NOT NULL,
  `Cookingtime` int(4) ,
  `ingredients` varchar(1000) ,
  `instructions` varchar(1000) ,
  `image` varchar(200),
  `uid` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `uid` int(11) UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `recipes`
--
ALTER TABLE `recipes`
  ADD PRIMARY KEY (`rid`),
  ADD KEY `uid` (`uid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`uid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `recipes`
--
ALTER TABLE `recipes`
  MODIFY `rid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `uid` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `recipes`
--
ALTER TABLE `recipes`
  ADD CONSTRAINT `recipes_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

INSERT INTO users (username, password, email) VALUES
('chefjulia', 'icanCOOK123', 'julia@example.com'),
('cookingking', 'IMTHEBESTcook25', 'king@example.com'),
('tastytom', 'Wonder84mince', 'tom@example.com');

INSERT INTO recipes (name, description, type, Cookingtime, ingredients, instructions, image, uid) VALUES
('Spaghetti Carbonara', 'Classic Italian pasta dish', 'Italian', 25,
 'Spaghetti, eggs, pancetta, parmesan, pepper',
 'Boil pasta. Cook pancetta. Mix eggs and cheese. Combine all and serve.',
 'carbonara.jpg', 1),

('Chicken Tikka Masala', 'Creamy spiced chicken curry', 'Indian', 45,
 'Chicken, yogurt, cream, tomato, spices',
 'Marinate chicken. Grill chicken. Make sauce. Combine and simmer.',
 'tikka.jpg', 2),

('Beef Tacos', 'Quick Mexican tacos', 'Mexican', 30,
 'Taco shells, ground beef, lettuce, tomato, cheese',
 'Cook beef. Fill tacos. Add toppings and serve.',
 'tacos.jpg', 2),

('Spring Rolls', 'Crispy Chinese appetizer', 'Chinese', 20,
 'Rice paper, veggies, soy sauce, noodles',
 'Prep veggies. Wrap and roll. Fry until golden.',
 'springrolls.jpg', 3);


