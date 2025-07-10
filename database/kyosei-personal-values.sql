-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 26, 2023 at 03:38 AM
-- Server version: 5.7.24
-- PHP Version: 7.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kyosei-personal-values`
--

-- --------------------------------------------------------

--
-- Table structure for table `wp_kyosei_personal_values`
--

CREATE TABLE `wp_kyosei_personal_values` (
  `id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `long_description` text COLLATE utf8mb4_unicode_520_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `modality_tag_ids` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `wp_kyosei_personal_values`
--

INSERT INTO `wp_kyosei_personal_values` (`id`, `title`, `description`, `long_description`, `image`, `modality_tag_ids`) VALUES
(21, 'Achievement', 'A thing done successfully, typically by effort, courage, or skill.', '<h3>What is Lorem Ipsum?</h3>\r\n<strong>Lorem Ipsum</strong> is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\\\\\\\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 'http://kyosei-personal-values.test/wp-content/uploads/2023/09/control_1694064518.webp', '2,3'),
(23, 'Adaptability', 'The quality of being able to adjust to new conditions.', '<h3>What is Lorem Ipsum?</h3>\r\n<strong>Lorem Ipsum</strong> is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\\\\\\\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 'http://purposedrivenwork.ca/personal-value/wp-content/uploads/2023/07/sdsd.png', '3,4,5'),
(24, 'Comfort', 'a state of physical ease and freedom from pain or constraint.', '', 'http://purposedrivenwork.ca/personal-value/wp-content/uploads/2023/06/bg.jpeg', '3,4,5'),
(25, 'Adventure', 'An unusual and exciting, typically hazardous, experience or activity.', '<h3>What is Lorem Ipsum?</h3>\r\n<strong>Lorem Ipsum</strong> is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\\\\\\\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 'http://purposedrivenwork.ca/personal-value/wp-content/uploads/2023/06/control.webp', '1,6,7'),
(26, 'Authenticity', 'The quality of being authentic.', '<h3>What is Lorem Ipsum?</h3>\r\n<strong>Lorem Ipsum</strong> is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\\\\\\\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 'http://purposedrivenwork.ca/personal-value/wp-content/uploads/2023/06/control.webp', '1'),
(27, 'Choice', 'an act of selecting or making a decision when faced with two or more possibilities.', '', 'http://purposedrivenwork.ca/personal-value/wp-content/uploads/2023/06/control.webp', '3,4'),
(28, 'Beauty', 'A combination of qualities, such as shape, color, or form, that pleases the aesthetic senses, especially the sight.', '', 'http://purposedrivenwork.ca/personal-value/wp-content/uploads/2023/07/control_1690517808.webp', '1,2,3,6'),
(29, 'Balance', 'An even distribution of weight enabling someone or something to remain upright and steady.', '<h3>What is Lorem Ipsum?</h3>\r\n<strong>Lorem Ipsum</strong> is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\\\\\\\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 'http://purposedrivenwork.ca/personal-value/wp-content/uploads/2023/07/cat.png', '1,7'),
(30, 'Community', 'a group of people living in the same place or having a particular characteristic in common.', '', 'http://purposedrivenwork.ca/personal-value/wp-content/uploads/2023/07/control_1690517902.webp', '1'),
(31, 'Chaos', 'complete disorder and confusion.', '', 'http://purposedrivenwork.ca/personal-value/wp-content/uploads/2023/07/bg.jpeg', '2,3'),
(32, 'Challenge', 'A call to take part in a contest or competition, especially a duel.', '', 'http://purposedrivenwork.ca/personal-value/wp-content/uploads/2023/07/control_1690517866.webp', '7'),
(33, 'Change', 'Make (someone or something) different; alter or modify.', '', 'http://purposedrivenwork.ca/personal-value/wp-content/uploads/2023/07/bg.jpeg', '1'),
(34, 'Commitment', 'the state or quality of being dedicated to a cause, activity, etc.', '', 'http://purposedrivenwork.ca/personal-value/wp-content/uploads/2023/07/bg_1690517888.jpeg', '5'),
(35, 'Competition', 'the activity or condition of competing.', '', 'http://purposedrivenwork.ca/personal-value/wp-content/uploads/2023/07/bg.jpeg', '1, 2'),
(36, 'Compromise', 'an agreement or a settlement of a dispute that is reached by each side making concessions.', '', 'http://purposedrivenwork.ca/personal-value/wp-content/uploads/2023/07/bg.jpeg', '1'),
(37, 'Conformity', 'compliance with standards, rules, or laws.', '', 'http://purposedrivenwork.ca/personal-value/wp-content/uploads/2023/07/bg.jpeg', '2'),
(38, 'Connection', 'a relationship in which a person, thing, or idea is linked or associated with something else.', '', 'http://purposedrivenwork.ca/personal-value/wp-content/uploads/2023/07/control_1690517955.webp', '1,2'),
(39, 'Control', 'the power to influence or direct people\\\'s behavior or the course of events.', '', 'http://purposedrivenwork.ca/personal-value/wp-content/uploads/2023/07/control_1690517964.webp', '3,4'),
(40, 'Co-operation', 'an act or instance of working or acting together for a common purpose or benefit.', '', 'http://purposedrivenwork.ca/personal-value/wp-content/uploads/2023/07/bg.jpeg', '3'),
(41, 'Courage', 'the ability to do something that frightens one.', '', 'http://purposedrivenwork.ca/personal-value/wp-content/uploads/2023/07/control_1690517973.webp', '5,6'),
(42, 'Creativity', 'the use of the imagination or original ideas, especially in the production of an artistic work.', '', 'http://purposedrivenwork.ca/personal-value/wp-content/uploads/2023/07/bg.jpeg', '4'),
(43, 'Curiosity', 'an eager desire to learn and often to learn what does not concern one : inquisitiveness.', '', 'http://purposedrivenwork.ca/personal-value/wp-content/uploads/2023/07/bg.jpeg', '5'),
(44, 'Discipline', 'the practice of training people to obey rules or a code of behavior, using punishment to correct disobedience.', '', 'http://purposedrivenwork.ca/personal-value/wp-content/uploads/2023/07/bg.jpeg', '3'),
(45, 'Dialogue', 'conversation between two or more people as a feature of a book, play, or movie.', '', 'http://purposedrivenwork.ca/personal-value/wp-content/uploads/2023/07/bg.jpeg', '5'),
(46, 'Diversity', 'the state of being diverse; variety.', '', 'http://purposedrivenwork.ca/personal-value/wp-content/uploads/2023/07/bg.jpeg', '1'),
(47, 'Diligence', 'careful and persistent work or effort.', '', 'http://purposedrivenwork.ca/personal-value/wp-content/uploads/2023/07/control_1690517995.webp', '1,3'),
(48, 'Ease', 'absence of difficulty or effort.', '', 'http://purposedrivenwork.ca/personal-value/wp-content/uploads/2023/07/bg.jpeg', '6,7'),
(49, 'Efficiency', 'the state or quality of being efficient.', '', 'http://purposedrivenwork.ca/personal-value/wp-content/uploads/2023/07/bg.jpeg', '6,7'),
(50, 'Enthusiasm', 'intense and eager enjoyment, interest, or approval.', '', 'http://purposedrivenwork.ca/personal-value/wp-content/uploads/2023/07/bg.jpeg', '6,7'),
(51, 'Ethics', 'moral principles that govern a person\\\'s behavior or the conducting of an activity.', '', 'http://purposedrivenwork.ca/personal-value/wp-content/uploads/2023/07/control_1690518014.webp', '6,7'),
(52, 'Excellence', 'the quality of being outstanding or extremely good.', '', 'http://purposedrivenwork.ca/personal-value/wp-content/uploads/2023/07/control_1690518038.webp', '1,4,6'),
(53, 'Experience', 'practical contact with and observation of facts or events.', '', 'http://purposedrivenwork.ca/personal-value/wp-content/uploads/2023/07/bg.jpeg', '1,3'),
(54, 'Fairness', 'impartial and just treatment or behavior without favoritism or discrimination.', '', 'http://purposedrivenwork.ca/personal-value/wp-content/uploads/2023/07/control_1690518046.webp', '3,4'),
(55, 'Faith', 'complete trust or confidence in someone or something.', '', 'http://purposedrivenwork.ca/personal-value/wp-content/uploads/2023/07/bg.jpeg', '1,4,6'),
(56, 'Family', 'a group of one or more parents and their children living together as a unit.', '', 'http://purposedrivenwork.ca/personal-value/wp-content/uploads/2023/07/bg.jpeg', '1,4,6'),
(57, 'Financial stability', 'a condition in which the financial system is not unstable.', '', 'http://purposedrivenwork.ca/personal-value/wp-content/uploads/2023/07/control_1690518072.webp', '1,5'),
(58, 'Financial gain', 'the amount by which the revenue of a business exceeds its cost of operating.', '', 'http://purposedrivenwork.ca/personal-value/wp-content/uploads/2023/07/bg.jpeg', '1,4,6'),
(59, 'Forgiveness', 'the action or process of forgiving or being forgiven.', '', 'http://purposedrivenwork.ca/personal-value/wp-content/uploads/2023/07/bg.jpeg', '1,4,6'),
(60, 'Freedom', 'the power or right to act, speak, or think as one wants without hindrance or restraint.', '', 'http://purposedrivenwork.ca/personal-value/wp-content/uploads/2023/07/control_1690518081.webp', '3'),
(61, 'Friendship', 'the emotions or conduct of friends; the state of being friends.', '', 'http://purposedrivenwork.ca/personal-value/wp-content/uploads/2023/07/control_1690518090.webp', '2'),
(62, 'Fun / Humour', 'an ability to perceive the ludicrous, the comical, and the absurd in human life and to express these usually without bitterness.', '', 'http://purposedrivenwork.ca/personal-value/wp-content/uploads/2023/07/bg.jpeg', '1,4,6'),
(63, 'Future generations', 'cohorts of hypothetical people not yet born.', '', 'http://purposedrivenwork.ca/personal-value/wp-content/uploads/2023/07/bg.jpeg', '1,4,6'),
(64, 'Generosity', 'the quality of being kind and generous.', '', 'http://purposedrivenwork.ca/personal-value/wp-content/uploads/2023/07/control_1690518583.webp', '5,6'),
(65, 'Growth', 'the process of increasing in physical size.', '', 'http://purposedrivenwork.ca/personal-value/wp-content/uploads/2023/07/control_1690518592.webp', '2'),
(66, 'Goal-oriented', 'focused on reaching a specific objective or accomplishing a given task', '', 'http://purposedrivenwork.ca/personal-value/wp-content/uploads/2023/07/bg.jpeg', '1,4,6'),
(67, 'Harmony', 'the combination of simultaneously sounded musical notes to produce chords and chord progressions having a pleasing effect.', '', 'http://purposedrivenwork.ca/personal-value/wp-content/uploads/2023/07/bg.jpeg', '1,4,6'),
(68, 'Health', 'the state of being free from illness or injury.', '', 'http://purposedrivenwork.ca/personal-value/wp-content/uploads/2023/07/bg.jpeg', '1,4,6'),
(69, 'Honesty', 'the quality of being honest.', '', 'http://purposedrivenwork.ca/personal-value/wp-content/uploads/2023/07/control_1690518615.webp', '7'),
(70, 'Human Rights', 'the basic rights and freedoms that belong to every person in the world, from birth until death.', '', 'http://purposedrivenwork.ca/personal-value/wp-content/uploads/2023/07/control_1690518625.webp', '1,2'),
(71, 'Humility', 'freedom from pride or arrogance : the quality or state of being humble. accepted the honor with humility.', '', 'http://purposedrivenwork.ca/personal-value/wp-content/uploads/2023/07/control_1690518635.webp', '2'),
(72, 'Image', 'a representation of the external form of a person.', '', 'http://purposedrivenwork.ca/personal-value/wp-content/uploads/2023/07/control_1690518659.webp', '2,3'),
(73, 'Imagination', 'the faculty or action of forming new ideas, or images or concepts of external objects not present to the senses.', '', 'http://purposedrivenwork.ca/personal-value/wp-content/uploads/2023/07/bg.jpeg', '1,4,6'),
(74, 'Independence', 'the fact or state of being independent.', '', 'http://purposedrivenwork.ca/personal-value/wp-content/uploads/2023/07/control_1690518669.webp', '1,4'),
(75, 'Initiative', 'the ability to assess and initiate things independently.', '', 'http://purposedrivenwork.ca/personal-value/wp-content/uploads/2023/07/bg.jpeg', '1,4'),
(76, 'Innovation', 'the action or process of innovating.', '', 'http://purposedrivenwork.ca/personal-value/wp-content/uploads/2023/07/control_1690518680.webp', '2,6'),
(77, 'Inspiration', 'the process of being mentally stimulated to do or feel something, especially to do something creative.', '', 'http://purposedrivenwork.ca/personal-value/wp-content/uploads/2023/07/cat_1690518741.png', '6'),
(78, 'Integrity', 'the quality of being honest and having strong moral principles; moral uprightness.', '', 'http://purposedrivenwork.ca/personal-value/wp-content/uploads/2023/07/sdsd_1690518758.png', '3,7'),
(79, 'Interdependence', 'the dependence of two or more people or things on each other.', '', 'http://purposedrivenwork.ca/personal-value/wp-content/uploads/2023/07/bg.jpeg', '1,4'),
(80, 'Job security', 'the state of having a job that is secure and from which one is unlikely to be dismissed.', '', 'http://purposedrivenwork.ca/personal-value/wp-content/uploads/2023/07/bg.jpeg', '1,4'),
(81, 'Kindness', 'the quality of being friendly, generous, and considerate.', '', 'http://purposedrivenwork.ca/personal-value/wp-content/uploads/2023/07/bg.jpeg', '1,4'),
(82, 'Knowledge', 'facts, information, and skills acquired by a person through experience or education; the theoretical or practical understanding of a subject.', '', 'http://purposedrivenwork.ca/personal-value/wp-content/uploads/2023/07/cat.png', '1,2'),
(83, 'Learning', 'the acquisition of knowledge or skills through experience, study, or by being taught.', '', 'http://purposedrivenwork.ca/personal-value/wp-content/uploads/2023/07/sdsd.png', '2,3'),
(86, 'Leadership', 'the action of leading a group of people or an organization.', '', 'http://purposedrivenwork.ca/personal-value/wp-content/uploads/2023/07/bg.jpeg', '4');

-- --------------------------------------------------------

--
-- Table structure for table `wp_kyosei_personal_values_user`
--

CREATE TABLE `wp_kyosei_personal_values_user` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `title` text COLLATE utf8mb4_unicode_520_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `wp_kyosei_personal_values_user`
--

INSERT INTO `wp_kyosei_personal_values_user` (`id`, `user_id`, `title`, `created_at`) VALUES
(11, 1, 'a:5:{i:0;s:7:\"Comfort\";i:1;s:10:\"Discipline\";i:2;s:8:\"Dialogue\";i:3;s:5:\"Chaos\";i:4;s:7:\"Control\";}', '2023-08-20 03:21:24'),
(12, 2, 'a:5:{i:0;s:11:\"Achievement\";i:1;s:12:\"Adaptability\";i:2;s:9:\"Adventure\";i:3;s:12:\"Authenticity\";i:4;s:6:\"Choice\";}', '2023-08-20 03:01:12'),
(13, 1, 'a:5:{i:0;s:11:\"Achievement\";i:1;s:10:\"Conformity\";i:2;s:6:\"Change\";i:3;s:6:\"Choice\";i:4;s:6:\"Beauty\";}', '2023-08-21 03:26:46'),
(14, 1, 'a:5:{i:0;s:13:\"Goal-oriented\";i:1;s:15:\"Interdependence\";i:2;s:10:\"Leadership\";i:3;s:6:\"Health\";i:4;s:12:\"Job security\";}', '2023-08-21 05:33:00'),
(15, 1, 'a:5:{i:0;s:7:\"Freedom\";i:1;s:9:\"Knowledge\";i:2;s:9:\"Integrity\";i:3;s:8:\"Learning\";i:4;s:10:\"Leadership\";}', '2023-08-21 08:00:13'),
(16, 2, 'a:5:{i:0;s:10:\"Generosity\";i:1;s:5:\"Image\";i:2;s:11:\"Achievement\";i:3;s:12:\"Fun / Humour\";i:4;s:7:\"Comfort\";}', '2023-08-29 23:45:51'),
(17, 1, 'a:5:{i:0;s:5:\"Chaos\";i:1;s:6:\"Change\";i:2;s:10:\"Commitment\";i:3;s:10:\"Discipline\";i:4;s:12:\"Co-operation\";}', '2023-09-06 21:29:40'),
(18, 1, 'a:5:{i:0;s:6:\"Choice\";i:1;s:9:\"Adventure\";i:2;s:6:\"Beauty\";i:3;s:11:\"Achievement\";i:4;s:10:\"Conformity\";}', '2023-09-07 19:50:55'),
(19, 1, 'a:5:{i:0;s:9:\"Adventure\";i:1;s:7:\"Comfort\";i:2;s:12:\"Adaptability\";i:3;s:10:\"Connection\";i:4;s:6:\"Change\";}', '2023-09-14 00:36:13'),
(20, 1, 'a:5:{i:0;s:11:\"Achievement\";i:1;s:9:\"Adventure\";i:2;s:6:\"Beauty\";i:3;s:9:\"Challenge\";i:4;s:12:\"Adaptability\";}', '2023-09-14 06:22:41'),
(21, 1, 'a:5:{i:0;s:12:\"Authenticity\";i:1;s:6:\"Choice\";i:2;s:5:\"Chaos\";i:3;s:9:\"Challenge\";i:4;s:7:\"Balance\";}', '2023-09-14 07:02:55'),
(22, 1, 'a:5:{i:0;s:12:\"Co-operation\";i:1;s:6:\"Beauty\";i:2;s:6:\"Ethics\";i:3;s:10:\"Excellence\";i:4;s:6:\"Choice\";}', '2023-09-14 22:59:34'),
(23, 1, 'a:5:{i:0;s:7:\"Comfort\";i:1;s:11:\"Achievement\";i:2;s:12:\"Adaptability\";i:3;s:12:\"Authenticity\";i:4;s:9:\"Adventure\";}', '2023-09-14 23:19:29'),
(24, 1, 'a:5:{i:0;s:11:\"Competition\";i:1;s:8:\"Humility\";i:2;s:6:\"Beauty\";i:3;s:6:\"Growth\";i:4;s:5:\"Image\";}', '2023-09-14 23:47:28'),
(26, 1, 'a:5:{i:0;s:5:\"Chaos\";i:1;s:12:\"Authenticity\";i:2;s:10:\"Discipline\";i:3;s:9:\"Curiosity\";i:4;s:10:\"Compromise\";}', '2023-09-15 05:39:27'),
(27, 1, 'a:5:{i:0;s:12:\"Co-operation\";i:1;s:11:\"Achievement\";i:2;s:9:\"Adventure\";i:3;s:10:\"Conformity\";i:4;s:10:\"Compromise\";}', '2023-09-15 05:43:10'),
(28, 1, 'a:5:{i:0;s:11:\"Achievement\";i:1;s:9:\"Community\";i:2;s:11:\"Competition\";i:3;s:10:\"Commitment\";i:4;s:5:\"Chaos\";}', '2023-09-15 05:44:48'),
(29, 1, 'a:5:{i:0;s:11:\"Achievement\";i:1;s:9:\"Challenge\";i:2;s:5:\"Chaos\";i:3;s:6:\"Beauty\";i:4;s:9:\"Community\";}', '2023-09-15 05:46:16'),
(30, 1, 'a:5:{i:0;s:11:\"Achievement\";i:1;s:11:\"Competition\";i:2;s:10:\"Conformity\";i:3;s:6:\"Beauty\";i:4;s:10:\"Compromise\";}', '2023-09-15 05:55:13');

-- --------------------------------------------------------

--
-- Table structure for table `wp_kyosei_personal_value_modality_tags`
--

CREATE TABLE `wp_kyosei_personal_value_modality_tags` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `wp_kyosei_personal_value_modality_tags`
--

INSERT INTO `wp_kyosei_personal_value_modality_tags` (`id`, `name`, `slug`) VALUES
(1, 'Career & Work', 'career-and-work'),
(2, 'Health', 'health'),
(3, 'Money', 'money'),
(4, 'Relationships (Family & Friends)', 'relationships-family-friends'),
(5, 'Purpose, Creativity, Contribution & Lifestyle', 'purpose-creativity-contribution-lifestyle'),
(6, 'Spirit', 'spirit'),
(7, 'Habits', 'habits');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `wp_kyosei_personal_values`
--
ALTER TABLE `wp_kyosei_personal_values`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wp_kyosei_personal_values_user`
--
ALTER TABLE `wp_kyosei_personal_values_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `wp_kyosei_personal_value_modality_tags`
--
ALTER TABLE `wp_kyosei_personal_value_modality_tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `wp_kyosei_personal_values`
--
ALTER TABLE `wp_kyosei_personal_values`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT for table `wp_kyosei_personal_values_user`
--
ALTER TABLE `wp_kyosei_personal_values_user`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `wp_kyosei_personal_value_modality_tags`
--
ALTER TABLE `wp_kyosei_personal_value_modality_tags`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
