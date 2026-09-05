-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Sep 05, 2026 at 07:09 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `online_book_store_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `full_name`, `email`, `password`) VALUES
(2, 'admin', 'admin@example.com', '$2y$10$IXvGcDNZTu8Tq9DONBK8NeFecl2fdqu.M5D3LcBdzDT1nCkyGYvkK');

-- --------------------------------------------------------

--
-- Table structure for table `authors`
--

CREATE TABLE `authors` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `authors`
--

INSERT INTO `authors` (`id`, `name`) VALUES
(1, 'jack London'),
(8, 'charles dickens'),
(10, 'walt whitman'),
(11, 'Lord Byron'),
(12, 'Robert Cecil Martin'),
(13, 'Andrew Hunt'),
(14, 'Thomas H. Cormen'),
(15, 'Francis Scott Fitzgerald'),
(16, 'Josh Lockhart'),
(17, 'Jane Austen'),
(18, 'Arthur Conan Doyle'),
(19, 'Oscar Wilde'),
(20, 'मुंशी प्रेमचंद (Munshi Premchand)'),
(21, 'देवकीनंदन खत्री(Devkinandan Khatri)'),
(22, 'Ved Vyas'),
(23, 'Valmiki'),
(25, 'Leo Tolstoy'),
(26, 'Herman Melville'),
(27, 'F. Scott Fitzgerald'),
(28, 'Franz Kafka'),
(29, 'Alexandre Dumas'),
(30, 'Victor Hugo'),
(31, 'Homer'),
(32, 'Agatha Christie'),
(33, 'Edgar Allan Poe'),
(34, 'Mary Shelley'),
(35, 'Bram Stoker'),
(36, 'H.P. Lovecraft'),
(37, 'Robert Louis Stevenson'),
(38, 'H.G. Wells'),
(39, 'Jules Verne'),
(40, 'Lewis Carroll'),
(41, 'Miguel de Cervantes'),
(42, 'Ernest Hemingway'),
(43, 'Joseph Conrad'),
(44, 'Gaston Leroux'),
(45, 'Louisa May Alcott'),
(46, 'Rabindranath Tagore'),
(47, 'William Shakespeare'),
(48, 'Emily Dickinson'),
(49, 'Kalidasa'),
(50, 'Chanakya'),
(51, 'Sun Tzu'),
(52, 'Marcus Aurelius'),
(53, 'Plato'),
(54, 'Friedrich Nietzsche');

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `category_id` int(11) NOT NULL,
  `cover` varchar(255) NOT NULL,
  `file` varchar(255) NOT NULL,
  `likes` int(11) NOT NULL DEFAULT 0,
  `dislikes` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `title`, `author_id`, `description`, `category_id`, `cover`, `file`, `likes`, `dislikes`) VALUES
(1, 'Crime and Punishment', 1, 'Crime and Punishment by Fyodor Dostoevsky is a psychological novel that explores the moral dilemmas of Raskolnikov, a young man who commits murder believing he\'s above the law. The story delves into guilt, redemption, and the struggle between good and evil within the human soul.', 1, '6867b146635c16.72345793.jpg', '6867b10397f899.69454118.pdf', 2, 1),
(2, 'Great Expectations', 8, 'Great Expectations by Charles Dickens is a coming-of-age novel that follows Pip, an orphan who dreams of rising above his humble beginnings. Through unexpected fortune and painful lessons, he discovers the true meaning of gentility, loyalty, and love.', 1, '6866d0c00ebda7.98132941.jpg', '6866d0c00fc879.95848990.pdf', 0, 0),
(7, 'Pride and Prejudice', 17, 'A classic novel about love and social expectations in 19th-century England, featuring the witty Elizabeth Bennet and proud Mr. Darcy.', 13, '6867f1c2bb2597.65605507.jpg', '6867f1c2bb6b02.25725302.pdf', 0, 0),
(8, 'The Adventures of Sherlock Holmes', 18, 'Brilliant detective Sherlock Holmes solves mysteries in Victorian London using logic and observation.', 14, '6867f2679e0e48.56247330.jpg', '6867f2679e63b0.35301571.pdf', 0, 0),
(9, 'The Picture of Dorian Gray', 19, 'A young man remains eternally youthful while a hidden portrait of him ages and reflects his moral decay. It’s a chilling tale of vanity, sin, and supernatural consequences.', 15, '6867f347645c59.42987298.jpg', '6867f347652c93.83394651.pdf', 0, 0),
(10, 'गोदान (Godaan)', 20, 'भारतीय किसान जीवन की पीड़ा और सामाजिक अन्याय की गहरी कथा।(A deep tale of the suffering and social injustice of Indian farmer\'s life)', 16, '6867f47066fa58.69641596.jpg', '6867f47076bb84.82902042.pdf', 0, 0),
(11, 'चंद्रकांता(Chandrakanta)', 21, 'राजाओं, जादूगरों और रोमांचक रहस्यों से भरी भारत की पहली फैंटेसी शैली की किताब।(India\'s first fantasy genre book filled with kings, wizards and thrilling mysteries.)', 17, '6867f5b7727257.86137234.jpg', '6867f5b77323f7.43254319.pdf', 0, 0),
(12, 'Mahabharat(English)', 22, 'The *Mahabharat* is one of the greatest Indian epics, narrating the war between the Pandavas and the Kauravas—two branches of the same royal family. It explores themes of dharma (duty), karma, loyalty, and justice. At its core is the legendary Kurukshetra war, with Lord Krishna guiding Arjuna in the *Bhagavad Gita*. The epic also includes rich stories of devotion, betrayal, sacrifice, and divine intervention. It remains a timeless guide to life, ethics, and spiritual wisdom.', 18, '6867f70137e543.66294593.jpg', '6867f70147a9a4.03065371.pdf', 1, 0),
(14, 'Ramayana By Valmiki (Hindi)', 23, 'रामायण एक प्राचीन भारतीय महाकाव्य है, जिसकी रचना महर्षि वाल्मीकि ने की थी। यह भगवान श्रीराम के जीवन, उनके वनवास, माता सीता की रावण द्वारा अपहरण, और अंत में रावण के वध की कथा है। रामायण में भक्ति, धर्म, कर्तव्य, आदर्श परिवार और मर्यादा पुरुषोत्तम राम के गुणों का वर्णन है। इसमें भाईचारे, त्याग, सत्य और नारी सम्मान जैसे मूल्यों को प्रमुखता दी गई है। यह ग्रंथ आज भी भारतीय संस्कृति और आचरण का आधार है।(The *Ramayana* is an ancient Indian epic written by Sage Valmiki. It narrates the life of Lord Rama, his exile to the forest, the abduction of his wife Sita by the demon king Ravana, and Rama\'s eventual victory over evil. The story highlights ideals of devotion, duty, honor, sacrifice, and righteousness. Lord Rama is portrayed as the perfect man (*Maryada Purushottam*), and the epic serves as a moral and spiritual guide. Even today, the *Ramayana* deeply influences Indian culture and values.)', 18, '6867fb63714084.51756895.jpg', '6867fb6381b243.71382794.pdf', 1, 0),
(16, 'War and Peace', 25, 'Epic masterpiece chronicling the French invasion of Russia and the impact of the Napoleonic era on Tsarist society through the stories of five aristocratic families.', 16, '6a9c4478898537.jpg', '6a9c4478898537.pdf', 43, 1),
(17, 'Moby-Dick', 26, 'The epic voyage of the whaling ship Pequod and its Captain Ahab, whose relentless obsessive quest to hunt down the fierce white whale Moby Dick drives the crew into danger and destiny.', 16, '6a9c447f344998.jpg', '6a9c447f344998.pdf', 20, 4),
(18, 'The Great Gatsby', 27, 'A dazzling portrait of the Jazz Age, romantic obsession, and the tragedy of the American Dream, set in the lavish estates of Long Island in the summer of 1922.', 16, '6a9c4485673438.jpg', '6a9c4485673438.pdf', 16, 3),
(19, 'The Metamorphosis', 28, 'The haunting psychological tale of Gregor Samsa, a traveling salesman who wakes up one morning to find himself transformed into a monstrous verminous insect.', 16, '6a9c4489778854.jpg', '6a9c4489778854.pdf', 58, 0),
(20, 'A Tale of Two Cities', 8, 'Set in London and Paris during the tumultuous events of the French Revolution, depicting themes of resurrection, sacrifice, and the enduring power of love amid political turmoil.', 16, '6a9c448c120686.jpg', '6a9c448c120686.pdf', 76, 0),
(21, 'The Count of Monte Cristo', 29, 'An unforgettable tale of betrayal, wrongful imprisonment in the Château d\'If, miraculous escape, hidden treasure, and calculated vengeance by Edmond Dantès.', 16, '6a9c4495484099.jpg', '6a9c4495484099.pdf', 26, 3),
(22, 'Les Misérables', 30, 'A sweeping epic of social injustice, redemption, compassion, and revolution following the life of paroled convict Jean Valjean and the relentless Inspector Javert.', 16, '6a9c4496389189.jpg', '6a9c4496389189.pdf', 18, 2),
(23, 'The Odyssey', 31, 'The foundational epic poem following Odysseus, King of Ithaca, on his perilous ten-year voyage home after the fall of Troy, battling monsters, sorceresses, and divine wrath.', 16, '6a9c4499475107.jpg', '6a9c4499475107.pdf', 14, 0),
(24, 'The Hound of the Baskervilles', 18, 'Sherlock Holmes and Dr. Watson investigate the legendary spectral hound that haunts the cursed Baskerville family upon the eerie, fog-drenched moors of Devonshire.', 14, '6a9c449a451713.jpg', '6a9c449a451713.pdf', 41, 1),
(25, 'The Mysterious Affair at Styles', 32, 'The debut novel featuring the brilliant Belgian detective Hercule Poirot, called to investigate the poison murder of a wealthy matriarch at an English country manor.', 14, '6a9c449d795387.jpg', '6a9c449d795387.pdf', 70, 1),
(26, 'The Murders in the Rue Morgue', 33, 'The pioneering detective story introducing C. Auguste Dupin, who unravels an impossible, gruesome double murder inside a locked Parisian apartment through razor-sharp analytical deduction.', 14, '6a9c44a3540237.jpg', '6a9c44a3540237.pdf', 42, 1),
(27, 'A Study in Scarlet', 18, 'The thrilling novel that first introduced the world to detective Sherlock Holmes and Dr. John H. Watson, beginning with a mysterious corpse and a blood-written message in a London townhouse.', 14, '6a9c44a6893779.jpg', '6a9c44a6893779.pdf', 44, 2),
(28, 'The Sign of Four', 18, 'Sherlock Holmes and Dr. Watson are drawn into a complex mystery involving stolen Agra treasure, a secret pact between four convicts, and a poisonous blowpipe in Victorian London.', 14, '6a9c44a9185145.jpg', '6a9c44a9185145.pdf', 17, 0),
(29, 'The Secret Adversary', 32, 'Tommy and Tuppence, two adventurous young friends, launch \'The Young Adventurers Ltd.\' and plunge headfirst into a dangerous conspiracy involving missing secret state documents.', 14, '6a9c44ad647617.jpg', '6a9c44ad647617.pdf', 69, 0),
(30, 'Frankenstein', 34, 'The definitive Gothic masterpiece exploring the terrifying consequences of scientific hubris when Victor Frankenstein breathes life into a creature made from assembled human remains.', 15, '6a9c44b0892374.jpg', '6a9c44b0892374.pdf', 79, 4),
(31, 'Dracula', 35, 'The chilling epistolary novel of Count Dracula\'s sinister attempt to relocate from Transylvania to England to spread the undead curse, and the desperate battle waged by Professor Van Helsing.', 15, '6a9c44b7642103.jpg', '6a9c44b7642103.pdf', 16, 1),
(32, 'The Call of Cthulhu', 36, 'The foundational tale of cosmic dread revealing ancient, slumbering extraterrestrial deities in the sunken city of R\'lyeh, waiting for the stars to align to reclaim Earth.', 15, '6a9c44b8770036.jpg', '6a9c44b8770036.pdf', 45, 4),
(33, 'The Fall of the House of Usher', 33, 'A harrowing Gothic masterpiece of dread, hereditary decay, live burial, and supernatural terror inside the crumbling ancestral mansion of Roderick Usher.', 15, '6a9c44ba163748.jpg', '6a9c44ba163748.pdf', 82, 1),
(34, 'The Strange Case of Dr Jekyll and Mr Hyde', 37, 'A gripping exploration of the dual nature of humanity, following a respected London physician who invents a potion that unleashes his depraved alter-ego, Edward Hyde.', 15, '6a9c44bd677143.jpg', '6a9c44bd677143.pdf', 34, 3),
(35, 'The Dunwich Horror', 36, 'In the secluded village of Dunwich, Massachusetts, the monstrous offspring of an otherworldly entity grows uncontrollably inside the decaying farmhouse of Old Whateley.', 15, '6a9c44c5777159.jpg', '6a9c44c5777159.pdf', 93, 2),
(36, 'The Time Machine', 38, 'The pioneering science fiction classic in which a Victorian inventor journeys into the year 802,701 AD to discover humanity divided into the ethereal Eloi and subterranean Morlocks.', 17, '6a9c44c8177206.jpg', '6a9c44c8177206.pdf', 75, 3),
(37, 'The War of the Worlds', 38, 'The gripping narrative of an extraterrestrial invasion of Victorian England by technologically superior Martians in colossal three-legged fighting machines equipped with Heat-Rays.', 17, '6a9c44cb819004.jpg', '6a9c44cb819004.pdf', 93, 1),
(38, 'Twenty Thousand Leagues Under the Sea', 39, 'Professor Aronnax, Conseil, and harpooner Ned Land embark on an astonishing underwater voyage aboard Captain Nemo\'s revolutionary submarine, the Nautilus, exploring the ocean depths.', 17, '6a9c44ce165572.jpg', '6a9c44ce165572.pdf', 27, 2),
(39, 'Journey to the Center of the Earth', 39, 'Professor Lidenbrock and his nephew Axel decipher an ancient Icelandic runic parchment and descend into a volcanic crater to uncover a subterranean prehistoric world.', 17, '6a9c44d2343445.jpg', '6a9c44d2343445.pdf', 40, 3),
(40, 'Around the World in Eighty Days', 39, 'The eccentric, meticulously punctual gentleman Phileas Fogg wagers his entire fortune that he can circumnavigate the globe in eighty days alongside his resourceful French valet Passepartout.', 17, '6a9c44d9562453.jpg', '6a9c44d9562453.pdf', 57, 3),
(41, 'Alice\'s Adventures in Wonderland', 40, 'A whimsical, surreal journey of a young girl named Alice who falls down a rabbit hole into a fantastical world populated by peculiar anthropomorphic creatures and riddles.', 17, '6a9c44db369450.jpg', '6a9c44db369450.pdf', 21, 2),
(42, 'The Invisible Man', 38, 'A brilliant scientist named Griffin discovers the formula for invisibility but descends into madness and terror when he realizes the tragic isolation and power of his condition.', 17, '6a9c44dd338181.jpg', '6a9c44dd338181.pdf', 83, 0),
(43, 'Treasure Island', 37, 'Young Jim Hawkins finds a map leading to buried pirate gold and sets sail aboard the Hispaniola, facing betrayal and adventure led by the cunning Long John Silver.', 17, '6a9c44de829999.jpg', '6a9c44de829999.pdf', 45, 1),
(44, 'Don Quixote', 41, 'The celebrated comedic and poignant adventure of the idealistic nobleman Alonso Quixano who imagines himself a heroic knight-errant and sets out to right the world\'s wrongs.', 1, '6a9c44e3809204.jpg', '6a9c44e3809204.pdf', 14, 4),
(45, 'The Call of the Wild', 1, 'The thrilling survival story of Buck, a domesticated St. Bernard-Scotch Collie stolen from his California home and forced into the brutal life of an Alaskan sled dog during the Klondike Gold Rush.', 1, '6a9c44eb716805.jpg', '6a9c44eb716805.pdf', 23, 2),
(46, 'White Fang', 1, 'The companion story to Call of the Wild, following a wild wolfdog\'s journey from the harsh Canadian wilderness to eventual domestication and loyalty in the human world.', 1, '6a9c44f2462239.jpg', '6a9c44f2462239.pdf', 73, 3),
(47, 'The Old Man and the Sea', 42, 'Hemingway\'s Pulitzer Prize-winning novella about Santiago, an aging Cuban fisherman who engages in an epic, agonizing battle with a giant marlin far out in the Gulf Stream.', 1, '6a9c44f6524086.jpg', '6a9c44f6524086.pdf', 22, 1),
(48, 'David Copperfield', 8, 'Dickens\' most autobiographical novel following the heartwarming and tragic life journey of David Copperfield from an impoverished childhood to personal growth and literary triumph.', 1, '6a9c44fa388612.jpg', '6a9c44fa388612.pdf', 20, 2),
(49, 'Anna Karenina', 25, 'A profound, tragic masterpiece exploring love, societal hypocrisy, passion, and faith through the doomed romance of Countess Anna Karenina and Count Vronsky in 19th-century Russia.', 1, '6a9c44fd536258.jpg', '6a9c44fd536258.pdf', 22, 2),
(50, 'Heart of Darkness', 43, 'A dark psychological journey along the Congo River in search of the enigmatic ivory trader Kurtz, uncovering the deep moral corruption and colonial brutality in Africa.', 1, '6a9c44fe622881.jpg', '6a9c44fe622881.pdf', 13, 1),
(51, 'Sense and Sensibility', 17, 'The enchanting story of the Dashwood sisters--prudent Elinor and emotional Marianne--as they navigate heartbreak, social expectations, and true love in Regency England.', 13, '6a9c44ff994525.jpg', '6a9c44ff994525.pdf', 57, 4),
(52, 'Emma', 17, 'The delightful romantic comedy of Emma Woodhouse, handsome, clever, and rich, who fancies herself a matchmaker for others while blind to her own feelings for Mr. Knightley.', 13, '6a9c4506618681.jpg', '6a9c4506618681.pdf', 28, 4),
(53, 'Persuasion', 17, 'Austen\'s mature and tender romance of second chances, following Anne Elliot who is reunited with Captain Wentworth years after being persuaded to break their engagement.', 13, '6a9c450a492754.jpg', '6a9c450a492754.pdf', 75, 0),
(54, 'The Phantom of the Opera', 44, 'The dramatic Gothic romance of the disfigured musical genius who haunts the Paris Opera House and his passionate, tragic obsession with the beautiful soprano Christine Daaé.', 13, '6a9c450d226627.jpg', '6a9c450d226627.pdf', 45, 4),
(55, 'Little Women', 45, 'The timeless, beloved chronicle of the four March sisters--Meg, Jo, Beth, and Amy--growing up in New England during the Civil War, experiencing artistic dreams, friendship, and love.', 13, '6a9c4510599452.jpg', '6a9c4510599452.pdf', 93, 2),
(56, 'Leaves of Grass', 10, 'A landmark collection of transcendental free verse celebrating the human body, the spirit of American democracy, nature, individuality, and universal brotherhood.', 5, '6a9c4514978654.jpg', '6a9c4514978654.pdf', 76, 3),
(57, 'Gitanjali (Song Offerings)', 46, 'Tagore\'s Nobel Prize-winning collection of mystical, lyrical poems expressing deep devotion to the divine, union with nature, and the spiritual yearning of the soul.', 5, '6a9c4518991693.jpg', '6a9c4518991693.pdf', 52, 0),
(58, 'The Raven and Selected Poems', 33, 'Atmospheric, musical poems exploring mournful beauty, lost love, nocturnal melancholy, and haunting supernatural visitations, featuring \'The Raven\' and \'Annabel Lee\'.', 5, '6a9c451b498584.jpg', '6a9c451b498584.pdf', 54, 4),
(59, 'Sonnets of William Shakespeare', 47, 'The quintessential collection of 154 sonnets meditating on the passage of time, the beauty of the Fair Youth, the passionate allure of the Dark Lady, and immortality through verse.', 5, '6a9c451e526803.jpg', '6a9c451e526803.pdf', 15, 1),
(60, 'The Complete Poems of Emily Dickinson', 48, 'Unique, visionary poems characterized by bold slant rhyme, idiosyncratic punctuation, and startling meditations on nature, death, eternity, and the infinite scope of consciousness.', 5, '6a9c4521239388.jpg', '6a9c4521239388.pdf', 53, 4),
(61, 'Meghaduta (The Cloud Messenger)', 49, 'Kalidasa\'s sublime Sanskrit lyrical masterpiece in which an exiled Yaksha implores a passing monsoon cloud to deliver a message of eternal love and longing to his lonely wife in Alaka.', 5, '6a9c4525923027.jpg', '6a9c4525923027.pdf', 13, 3),
(62, 'The Bhagavad Gita', 22, 'The sacred 700-verse dialogue between Prince Arjuna and Lord Krishna on the battlefield of Kurukshetra, illuminating Karma Yoga, devotion, cosmic duty, and self-realization.', 18, '6a9c452d174042.jpg', '6a9c452d174042.pdf', 35, 1),
(63, 'Chanakya Neeti & Arthashastra', 50, 'Ancient strategic treatise by the master statesman Chanakya (Kautilya), encompassing statecraft, political warfare, economics, ethics, diplomacy, and righteous leadership.', 18, '6a9c4532276991.jpg', '6a9c4532276991.pdf', 86, 0),
(64, 'Upanishads - Principles of Cosmic Wisdom', 22, 'Foundational philosophical revelations of Vedic India exploring the identity of Atman (the inner Self) with Brahman (the supreme cosmic reality) and the path to liberation.', 18, '6a9c4535227320.jpg', '6a9c4535227320.pdf', 65, 1),
(65, 'The Art of War', 51, 'The definitive ancient Chinese treatise on military strategy, tactics, deception, intelligence, and the supreme art of subduing the enemy without fighting.', 18, '6a9c4536438946.jpg', '6a9c4536438946.pdf', 45, 2),
(66, 'Meditations', 52, 'The personal private journal of Roman Emperor Marcus Aurelius, offering profound Stoic wisdom on resilience, mindfulness, self-discipline, mortality, and inner peace.', 18, '6a9c4539462385.jpg', '6a9c4539462385.pdf', 83, 1),
(67, 'The Republic', 53, 'Socrates\' foundational philosophical dialogue examining justice, the ideal city-state, the Philosopher King, and the famous Allegory of the Cave.', 18, '6a9c453d329059.jpg', '6a9c453d329059.pdf', 51, 4),
(68, 'Beyond Good and Evil', 54, 'A radical critique of traditional morality, religion, dogmatism, and philosophical systems, advocating for intellectual honesty, the Will to Power, and the free spirit.', 18, '6a9c4540521380.jpg', '6a9c4540521380.pdf', 91, 1);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Novel'),
(5, 'Poetry'),
(13, 'Romance'),
(14, 'Mystery'),
(15, 'Horror'),
(16, 'Classic'),
(17, 'Fiction'),
(18, 'Hindu Itihasa (History)');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `rating` tinyint(4) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `name`, `email`, `rating`, `message`, `created_at`) VALUES
(1, 'harsh', 'gohilharsh759@gmail.com', 4, 'this is so beautiful web site', '2025-07-05 10:26:12'),
(2, 'Antigravity Tester', 'test@bookverse.com', 5, 'BookVerse is an absolutely phenomenal 3D digital reading platform! Loving the Mahabharata and epics collection.', '2026-09-05 16:48:02'),
(3, 'Gohil Harsh', 'gohilharshg1234@gmail.com', 4, 'good', '2026-09-05 16:48:35');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `authors`
--
ALTER TABLE `authors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `authors`
--
ALTER TABLE `authors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
