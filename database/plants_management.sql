-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 25 déc. 2025 à 14:10
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `plants_management`
--

-- --------------------------------------------------------

--
-- Structure de la table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `created_at`) VALUES
(1, 'Self-Watering Plants', 'self_watering', 'Plants that can survive with minimal watering or have self-watering systems', '2025-12-25 12:12:44'),
(2, 'Pet-Friendly Plants', 'pet_friendly', 'Non-toxic plants safe for pets', '2025-12-25 12:12:44'),
(3, 'Air Purifying Plants', 'air_purifying', 'Plants that help improve indoor air quality', '2025-12-25 12:12:44'),
(4, 'Cold Weather Plants', 'cold_weather', 'Plants that can tolerate lower temperatures', '2025-12-25 12:12:44'),
(5, 'Giant Plants', 'giant_plants', 'Large plants perfect for statement pieces', '2025-12-25 12:12:44'),
(6, 'Low-Maintenance Plants', 'low_maintenance', 'Easy-care plants perfect for beginners', '2025-12-25 12:12:44');

-- --------------------------------------------------------

--
-- Structure de la table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content_text` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `follows`
--

CREATE TABLE `follows` (
  `id` int(11) NOT NULL,
  `follower_id` int(11) NOT NULL,
  `following_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ;

-- --------------------------------------------------------

--
-- Structure de la table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `message` text NOT NULL,
  `link_url` varchar(500) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','processing','shipped','delivered','cancelled') DEFAULT 'pending',
  `shipping_address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_amount`, `status`, `shipping_address`, `created_at`, `updated_at`) VALUES
(1, 1, 89.97, 'pending', 'amal5 n292 rabat\r\n', '2025-12-25 10:26:35', '2025-12-25 10:26:35');

-- --------------------------------------------------------

--
-- Structure de la table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `unit_price`) VALUES
(1, 1, 1, 3, 29.99);

-- --------------------------------------------------------

--
-- Structure de la table `plant_care_events`
--

CREATE TABLE `plant_care_events` (
  `id` int(11) NOT NULL,
  `user_plant_id` int(11) NOT NULL,
  `event_type` enum('watering','fertilizing','repotting','other') NOT NULL,
  `event_date` date NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `plant_catalog`
--

CREATE TABLE `plant_catalog` (
  `id` int(11) NOT NULL,
  `common_name` varchar(255) NOT NULL,
  `scientific_name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `difficulty_level` enum('beginner','intermediate','advanced') DEFAULT 'beginner',
  `light_requirement` enum('low','medium','high') DEFAULT 'medium',
  `water_requirement` enum('low','medium','high') DEFAULT 'medium',
  `humidity_preference` enum('low','medium','high') DEFAULT 'medium',
  `temperature_min` int(11) DEFAULT NULL,
  `temperature_max` int(11) DEFAULT NULL,
  `image_url` varchar(500) DEFAULT NULL,
  `recommended_for_beginners` tinyint(1) DEFAULT 0,
  `default_watering_interval_days` int(11) DEFAULT 7,
  `default_fertilizing_interval_days` int(11) DEFAULT 30,
  `seed_guide` text DEFAULT NULL,
  `mature_plant_guide` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `plant_catalog`
--

INSERT INTO `plant_catalog` (`id`, `common_name`, `scientific_name`, `description`, `difficulty_level`, `light_requirement`, `water_requirement`, `humidity_preference`, `temperature_min`, `temperature_max`, `image_url`, `recommended_for_beginners`, `default_watering_interval_days`, `default_fertilizing_interval_days`, `seed_guide`, `mature_plant_guide`, `created_at`, `updated_at`) VALUES
(1, 'Monstera Deliciosa', 'Monstera deliciosa', 'A popular tropical plant with large, glossy leaves that develop characteristic splits and holes as they mature. Perfect for adding a jungle vibe to any room.', 'beginner', 'medium', 'medium', 'medium', 18, 27, 'https://bloomscape.com/wp-content/uploads/2020/08/bloomscape_monstera_alt_slate.jpg?ver=279416', 1, 7, 30, 'Plant seeds in well-draining soil, keep moist but not waterlogged. Maintain temperature 20-25°C. Germination takes 2-4 weeks. Provide indirect bright light.', 'Water when top 2-3cm of soil is dry. Prefers bright, indirect light. Mist leaves occasionally. Fertilize monthly during growing season. Repot every 2-3 years.', '2025-12-13 21:22:26', '2025-12-13 22:21:55'),
(2, 'Snake Plant', 'Sansevieria trifasciata', 'An extremely hardy plant that can survive in low light and with minimal water. Perfect for beginners and busy plant parents.', 'beginner', 'low', 'low', 'low', 15, 30, 'https://images.unsplash.com/photo-1593691501777-1c0e0a1a3a1a?w=400', 1, 14, 60, 'Plant seeds in sandy, well-draining soil. Keep slightly moist. Germination is slow, 4-8 weeks. Low light is acceptable.', 'Water sparingly, only when soil is completely dry. Can tolerate low light but prefers bright indirect light. Very drought tolerant. Fertilize 2-3 times per year.', '2025-12-13 21:22:26', '2025-12-13 21:22:26'),
(3, 'Pothos', 'Epipremnum aureum', 'A fast-growing trailing plant that is almost impossible to kill. Great for hanging baskets or training up a trellis.', 'beginner', 'medium', 'medium', 'medium', 18, 26, 'https://bloomscape.com/wp-content/uploads/2022/10/bloomscape_xs-golden-pothos-opp_xs_charcoal.jpg?ver=955410', 1, 7, 30, 'Rarely grown from seed. Propagate from cuttings instead. Place cutting in water or soil, roots will develop in 2-3 weeks.', 'Water when top inch of soil is dry. Prefers bright indirect light but tolerates low light. Pinch back to encourage bushiness. Fertilize monthly in growing season.', '2025-12-13 21:22:26', '2025-12-13 22:24:20'),
(4, 'Fiddle Leaf Fig', 'Ficus lyrata', 'A stunning plant with large, violin-shaped leaves. Requires more attention but rewards with dramatic beauty.', 'intermediate', 'high', 'medium', 'high', 18, 24, 'https://images.unsplash.com/photo-1463320898486-8c50c4e2a0a3?w=400', 0, 7, 30, 'Not typically grown from seed. Purchase as young plant and provide consistent care.', 'Water when top 2-3cm of soil is dry. Needs bright, indirect light. Keep away from drafts. Mist regularly or use humidifier. Rotate weekly for even growth. Fertilize monthly.', '2025-12-13 21:22:26', '2025-12-13 21:22:26'),
(5, 'ZZ Plant', 'Zamioculcas zamiifolia', 'An incredibly low-maintenance plant with glossy, dark green leaves. Thrives on neglect.', 'beginner', 'low', 'low', 'low', 15, 26, 'https://images.unsplash.com/photo-1593691501777-1c0e0a1a3a1a?w=400', 1, 21, 90, 'Propagate by division of rhizomes. Plant in well-draining soil. Keep slightly moist initially.', 'Water only when soil is completely dry (every 2-3 weeks). Tolerates low light but grows faster in bright indirect light. Very drought tolerant. Fertilize 2-3 times per year.', '2025-12-13 21:22:26', '2025-12-13 21:22:26'),
(6, 'Peace Lily', 'Spathiphyllum', 'Elegant plant with white flowers and dark green leaves. Known for air-purifying qualities.', 'beginner', 'low', 'high', 'high', 18, 24, 'https://images.unsplash.com/photo-1593691501777-1c0e0a1a3a1a?w=400', 1, 5, 30, 'Rarely grown from seed. Divide mature plants or purchase as young plant.', 'Keep soil consistently moist but not waterlogged. Prefers low to medium light. Drooping leaves indicate need for water. Mist regularly. Fertilize monthly during growing season.', '2025-12-13 21:22:26', '2025-12-13 21:22:26'),
(7, 'Spider Plant', 'Chlorophytum comosum', 'Easy-to-grow plant that produces baby plantlets. Perfect for hanging baskets.', 'beginner', 'medium', 'medium', 'medium', 15, 26, 'https://images.unsplash.com/photo-1593691501777-1c0e0a1a3a1a?w=400', 1, 7, 30, 'Plant seeds in well-draining soil. Keep moist. Germination in 2-3 weeks. Or propagate from plantlets.', 'Water when top inch of soil is dry. Prefers bright indirect light. Produces plantlets that can be propagated. Fertilize monthly during growing season.', '2025-12-13 21:22:26', '2025-12-13 21:22:26'),
(8, 'Rubber Plant', 'Ficus elastica', 'A bold plant with large, glossy leaves. Adds a statement to any room.', 'intermediate', 'medium', 'medium', 'medium', 18, 24, 'https://images.unsplash.com/photo-1593691501777-1c0e0a1a3a1a?w=400', 0, 7, 30, 'Not typically grown from seed. Purchase as young plant.', 'Water when top 2-3cm of soil is dry. Prefers bright indirect light. Wipe leaves occasionally to keep them shiny. Fertilize monthly during growing season.', '2025-12-13 21:22:26', '2025-12-13 21:22:26'),
(9, 'Alocasia', 'Alocasia amazonica', 'Dramatic plant with arrow-shaped leaves and striking veins. Requires high humidity.', 'advanced', 'medium', 'high', 'high', 20, 25, 'https://images.unsplash.com/photo-1593691501777-1c0e0a1a3a1a?w=400', 0, 5, 30, 'Plant tubers in well-draining soil. Keep warm and humid. Germination can be slow.', 'Keep soil consistently moist. Needs high humidity (60%+). Prefers bright indirect light. Mist regularly or use pebble tray. Fertilize every 2 weeks during growing season.', '2025-12-13 21:22:26', '2025-12-13 21:22:26'),
(11, 'Philodendron Birken', 'Philodendron birkin', 'Le Philodendron Birken est une variété rare et élégante avec des feuilles vert foncé marquées de rayures blanches distinctives. Cette plante est un cultivar qui produit des feuilles uniques avec des motifs en rayures qui deviennent plus prononcés avec l\'âge. C\'est une plante d\'intérieur très recherchée pour son apparence distinctive.', 'beginner', 'medium', 'medium', 'medium', 18, 26, '', 1, 7, 30, '', 'Arrosage: Arrosez lorsque le premier centimètre du sol est sec. Évitez l\'eau stagnante.\r\n\r\nLumière: Lumière indirecte brillante. Évitez la lumière directe du soleil.\r\n\r\nHumidité: Apprécie une humidité modérée. Brumisez les feuilles occasionnellement.\r\n\r\nEngrais: Fertilisez mensuellement pendant la saison de croissance (printemps-été).\r\n\r\nTaille: Taillez les feuilles mortes ou jaunies pour encourager une nouvelle croissance.', '2025-12-14 18:41:47', '2025-12-25 12:17:46'),
(12, 'Philodendron Sun Red', 'Philodendron erubescens', 'Le Philodendron Sun Red est une variété magnifique avec des feuilles qui présentent des teintes rouges et vertes vibrantes. Les nouvelles feuilles émergent souvent dans des tons de rouge ou de rose, créant un contraste saisissant avec les feuilles matures vertes. Cette plante est appréciée pour sa couleur unique et sa facilité d\'entretien.', 'beginner', 'medium', 'medium', 'medium', 18, 26, NULL, 1, 7, 30, NULL, 'Arrosage: Maintenez le sol légèrement humide mais pas détrempé. Arrosez lorsque le sol est sec au toucher.\n\nLumière: Lumière indirecte brillante à moyenne. Peut tolérer une lumière plus faible.\n\nHumidité: Préfère une humidité modérée à élevée. Brumisez régulièrement pour maintenir l\'humidité.\n\nEngrais: Appliquez un engrais équilibré toutes les 4-6 semaines pendant la saison de croissance.\n\nSupport: Cette plante peut bénéficier d\'un tuteur ou d\'un support pour grimper.', '2025-12-14 18:41:47', '2025-12-14 18:41:47'),
(13, 'Philodendron Hope Selloum', 'Philodendron bipinnatifidum', 'Le Philodendron Hope Selloum est une plante imposante avec de grandes feuilles profondément lobées qui créent un effet tropical spectaculaire. Cette variété peut atteindre une taille considérable et est parfaite comme plante d\'accent dans de grands espaces. Les feuilles vert foncé brillantes et profondément découpées donnent à cette plante un aspect exotique et luxuriant.', 'intermediate', 'medium', 'medium', 'high', 18, 27, NULL, 0, 7, 30, NULL, 'Arrosage: Arrosez abondamment lorsque le sol est sec au toucher, mais laissez le sol sécher entre les arrosages.\n\nLumière: Lumière indirecte brillante. Peut tolérer une lumière moyenne mais poussera plus lentement.\n\nHumidité: Nécessite une humidité élevée. Utilisez un humidificateur ou placez sur un plateau de galets avec de l\'eau.\n\nEspace: Cette plante peut devenir très grande. Assurez-vous d\'avoir suffisamment d\'espace pour sa croissance.\n\nEngrais: Fertilisez toutes les 4-6 semaines pendant la saison de croissance avec un engrais équilibré.\n\nNettoyage: Essuyez régulièrement les grandes feuilles pour maintenir leur éclat.', '2025-12-14 18:41:47', '2025-12-14 18:41:47'),
(14, 'Bamboo Plam', 'Chamaedorea seifrizii', 'The Bamboo Palm is a popular indoor plant known for its feathery, elegant fronds. It thrives in low to medium light and is easy to care for, making it a perfect choice for home or office spaces. In addition to its beauty, the Bamboo Palm is also known for its air-purifying qualities, helping to filter out toxins and improve indoor air quality. Its lush green appearance adds a touch of tranquility and nature to any room.', 'beginner', 'low', 'low', 'low', NULL, NULL, 'https://bloomscape.com/wp-content/uploads/2025/04/self-watering-charcoal-xxl-bamboo-palm-1.png?ver=1096527', 0, 7, 30, '', '', '2025-12-25 12:47:21', '2025-12-25 12:47:21');

-- --------------------------------------------------------

--
-- Structure de la table `plant_catalog_images`
--

CREATE TABLE `plant_catalog_images` (
  `id` int(11) NOT NULL,
  `plant_catalog_id` int(11) NOT NULL,
  `image_url` varchar(500) NOT NULL,
  `display_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `plant_catalog_images`
--

INSERT INTO `plant_catalog_images` (`id`, `plant_catalog_id`, `image_url`, `display_order`, `created_at`) VALUES
(1, 1, 'https://bloomscape.com/wp-content/uploads/2020/08/bloomscape_monstera_alt_slate.jpg?ver=279416', 0, '2025-12-13 22:49:08'),
(3, 3, 'https://bloomscape.com/wp-content/uploads/2022/10/bloomscape_xs-golden-pothos-opp_xs_charcoal.jpg?ver=955410', 0, '2025-12-13 22:49:08'),
(5, 5, 'https://images.unsplash.com/photo-1593691501777-1c0e0a1a3a1a?w=400', 0, '2025-12-13 22:49:08'),
(7, 7, 'https://images.unsplash.com/photo-1593691501777-1c0e0a1a3a1a?w=400', 0, '2025-12-13 22:49:08'),
(8, 8, 'https://images.unsplash.com/photo-1593691501777-1c0e0a1a3a1a?w=400', 0, '2025-12-13 22:49:08'),
(16, 9, 'https://bloomscape.com/wp-content/uploads/2020/08/bloomscape_alocasia-polly_charcoal.jpg?ver=279173', 0, '2025-12-13 22:49:36'),
(17, 9, 'https://bloomscape.com/wp-content/uploads/2020/08/bloomscape_alocasia-polly_detail.jpg?ver=279175', 0, '2025-12-13 22:51:32'),
(18, 4, 'https://bloomscape.com/wp-content/uploads/2025/04/VENICE_neon_praper_plant_XXL_charcoal_0567_V1-scaled-e1762793817365.jpg?ver=1096209', 0, '2025-12-13 22:59:03'),
(19, 4, 'https://bloomscape.com/wp-content/uploads/2020/08/bloomscape_fiddle-leaf-fig_detail.jpg?ver=279581', 0, '2025-12-13 22:59:22'),
(20, 4, 'https://bloomscape.com/wp-content/uploads/2019/03/bloomscape_microfiber-glove_fiddle-leaf-fig-scaled-e1629210231401.jpeg?ver=575135', 0, '2025-12-13 22:59:49'),
(21, 4, 'https://bloomscape.com/wp-content/uploads/2021/10/bloomscape_woven-basket_large_cactus_fiddle-leaf-fig_xl-scaled.jpg?ver=610405', 0, '2025-12-13 23:00:13'),
(22, 2, 'https://bloomscape.com/wp-content/uploads/2022/08/bloomscape_sansevieria_xs_charcoal.jpg?ver=927227', 0, '2025-12-14 01:36:17'),
(23, 2, 'https://bloomscape.com/wp-content/uploads/2022/08/bloomscape_sansevieria_xs_care-product.jpg?ver=927226', 0, '2025-12-14 01:36:39'),
(24, 2, 'https://bloomscape.com/wp-content/uploads/2022/08/bloomscape_sansevieria_xs_detail.jpg?ver=927228', 0, '2025-12-14 01:37:04'),
(25, 2, 'https://bloomscape.com/wp-content/uploads/2022/08/bloomscape_sansevieria_xs_angle2-scaled.jpg?ver=927225', 0, '2025-12-14 01:37:25'),
(26, 6, 'https://bloomscape.com/wp-content/uploads/2025/04/2-2.png?ver=1095716', 0, '2025-12-14 17:30:54'),
(27, 6, 'https://bloomscape.com/wp-content/uploads/2022/10/bloomscape_peace-lily6_md_detail-scaled.jpg?ver=955367', 0, '2025-12-14 17:31:15'),
(28, 6, 'https://bloomscape.com/wp-content/uploads/2020/09/bloomscape_neon-prayer-plant_detail.jpg?ver=292321', 0, '2025-12-14 17:31:33'),
(29, 13, 'https://bloomscape.com/wp-content/uploads/2020/11/bloomscape_philodendron-hope-selloum-alt_indigo-scaled.jpg?ver=333349', 0, '2025-12-14 18:42:54'),
(30, 13, 'https://bloomscape.com/wp-content/uploads/2020/08/bloomscape_philodendron-hope-selloum_detail.jpg?ver=279426', 0, '2025-12-14 18:43:10'),
(31, 12, 'https://bloomscape.com/wp-content/uploads/2024/07/philo_sun_red_charcoal-e1721335677714.jpg?ver=1080174', 0, '2025-12-14 18:43:40'),
(32, 12, 'https://bloomscape.com/wp-content/uploads/2024/07/philo_sun_red_0037-e1721400734189.jpg?ver=1080175', 0, '2025-12-14 18:43:59'),
(33, 12, 'https://bloomscape.com/wp-content/uploads/2024/07/philo_sun_red_0041-e1721400168417.jpg?ver=1080176', 0, '2025-12-14 18:44:25'),
(34, 11, 'https://bloomscape.com/wp-content/uploads/2023/08/230805_BB_philodendron_birkin_0799-e1692211717990-scaled.jpg?ver=1054903', 0, '2025-12-25 11:57:00'),
(35, 11, 'https://bloomscape.com/wp-content/uploads/2023/08/230805_BB_philodendron_birkin_0804-e1692212345129.jpg?ver=1054905', 0, '2025-12-25 11:58:01'),
(37, 11, 'https://bloomscape.com/wp-content/uploads/2023/08/230805_BB_philodendron_birkin_0927-scaled-e1692212629715.jpg?ver=1054921', 0, '2025-12-25 11:58:29'),
(39, 11, 'https://bloomscape.com/wp-content/uploads/2021/03/bloomscape_philodendron_birkin_small_detail-scaled-e1692212133923.jpg?ver=473525', 0, '2025-12-25 11:59:07'),
(40, 14, 'https://bloomscape.com/wp-content/uploads/2025/04/self-watering-charcoal-xxl-bamboo-palm-1.png?ver=1096527', 0, '2025-12-25 12:47:21'),
(41, 14, 'https://bloomscape.com/wp-content/uploads/2020/08/bloomscape_bamboo-palm_detail.jpg?ver=279481', 0, '2025-12-25 12:48:22'),
(42, 14, 'https://bloomscape.com/wp-content/uploads/2020/04/bloomscape_bamboo-palm_xl_detail_V2-scaled.jpeg?ver=586787', 0, '2025-12-25 12:48:46'),
(43, 14, 'https://bloomscape.com/wp-content/uploads/2022/04/Bloomscape_bamboo_palm_x2_Location5485.jpg?ver=768676', 0, '2025-12-25 12:49:04');

-- --------------------------------------------------------

--
-- Structure de la table `plant_category_assignments`
--

CREATE TABLE `plant_category_assignments` (
  `id` int(11) NOT NULL,
  `plant_catalog_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `plant_category_assignments`
--

INSERT INTO `plant_category_assignments` (`id`, `plant_catalog_id`, `category_id`, `created_at`) VALUES
(1, 11, 3, '2025-12-25 12:17:46'),
(2, 14, 3, '2025-12-25 12:47:21'),
(3, 14, 2, '2025-12-25 12:47:21'),
(4, 14, 1, '2025-12-25 12:47:21');

-- --------------------------------------------------------

--
-- Structure de la table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content_text` text NOT NULL,
  `image_url` varchar(500) DEFAULT NULL,
  `related_user_plant_id` int(11) DEFAULT NULL,
  `post_type` enum('normal','help','article') DEFAULT 'normal',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `content_text`, `image_url`, `related_user_plant_id`, `post_type`, `created_at`, `updated_at`) VALUES
(1, 1, 'welcome to my community', 'https://media.istockphoto.com/id/816807384/vector/welcome-inscription-hand-drawn-lettering-greeting-card-with-calligraphy-handwritten-design.jpg?s=612x612&w=0&k=20&c=aaq_MBiakiX_lzv83f78v5RMU4S9frQeovD_FtQFXNU=', NULL, 'normal', '2025-12-25 11:32:33', '2025-12-25 11:32:33');

-- --------------------------------------------------------

--
-- Structure de la table `post_likes`
--

CREATE TABLE `post_likes` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `post_likes`
--

INSERT INTO `post_likes` (`id`, `post_id`, `user_id`, `created_at`) VALUES
(1, 1, 1, '2025-12-25 11:32:53');

-- --------------------------------------------------------

--
-- Structure de la table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `category` enum('seed','plant','pot','soil','fertilizer','accessory') NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image_url` varchar(500) DEFAULT NULL,
  `stock` int(11) DEFAULT 0,
  `related_plant_catalog_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `category`, `price`, `image_url`, `stock`, `related_plant_catalog_id`, `created_at`, `updated_at`) VALUES
(1, 'Monstera Deliciosa - Mature Plant', 'Beautiful mature Monstera plant, 30-40cm tall', 'plant', 29.99, 'https://images.unsplash.com/photo-1614594975525-e45190c55d0b?w=400', 12, 1, '2025-12-13 21:22:26', '2025-12-25 10:26:35'),
(2, 'Monstera Deliciosa Seeds', 'Premium Monstera seeds, pack of 5', 'seed', 9.99, 'https://images.unsplash.com/photo-1614594975525-e45190c55d0b?w=400', 50, 1, '2025-12-13 21:22:26', '2025-12-13 21:22:26'),
(3, 'Snake Plant - Mature', 'Hardy Snake Plant, 25-30cm', 'plant', 19.99, 'https://images.unsplash.com/photo-1593691501777-1c0e0a1a3a1a?w=400', 20, 2, '2025-12-13 21:22:26', '2025-12-13 21:22:26'),
(4, 'Pothos Golden - Cuttings', 'Rooted Pothos cuttings, ready to plant', 'plant', 12.99, 'https://images.unsplash.com/photo-1519336056116-9e799c0a78b9?w=400', 30, 3, '2025-12-13 21:22:26', '2025-12-13 21:22:26'),
(5, 'Ceramic Pot - White', 'Beautiful white ceramic pot, 15cm diameter', 'pot', 14.99, 'https://images.unsplash.com/photo-1593691501777-1c0e0a1a3a1a?w=400', 40, NULL, '2025-12-13 21:22:26', '2025-12-13 21:22:26'),
(6, 'Premium Potting Soil', 'Well-draining potting mix for indoor plants, 5L', 'soil', 8.99, 'https://images.unsplash.com/photo-1593691501777-1c0e0a1a3a1a?w=400', 100, NULL, '2025-12-13 21:22:26', '2025-12-13 21:22:26'),
(7, 'Liquid Fertilizer', 'Balanced liquid fertilizer for houseplants, 500ml', 'fertilizer', 12.99, 'https://images.unsplash.com/photo-1593691501777-1c0e0a1a3a1a?w=400', 60, NULL, '2025-12-13 21:22:26', '2025-12-13 21:22:26'),
(8, 'Plant Mister', 'Fine mist sprayer for increasing humidity', 'accessory', 6.99, 'https://images.unsplash.com/photo-1593691501777-1c0e0a1a3a1a?w=400', 50, NULL, '2025-12-13 21:22:26', '2025-12-13 21:22:26');

-- --------------------------------------------------------

--
-- Structure de la table `product_images`
--

CREATE TABLE `product_images` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `image_url` varchar(500) NOT NULL,
  `display_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `username` varchar(100) NOT NULL,
  `bio` text DEFAULT NULL,
  `avatar_url` varchar(500) DEFAULT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `city` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `username`, `bio`, `avatar_url`, `role`, `city`, `created_at`) VALUES
(1, 'admin@plants.com', '$2y$10$mH4aCE6pothThWOHge.O4.Rg6jZVMGT/FmKvHAE8FO2X2LqKi8Egq', 'Admin', NULL, NULL, 'admin', NULL, '2025-12-13 21:22:26'),
(2, 'user@example.com', '$2y$10$KkWWTVyoiaZcM7v8C4aMUeMmwZfowMjfn5WIrjpp3wudlqlC5W63y', 'PlantLover', 'I love indoor plants!', NULL, 'user', 'Paris', '2025-12-13 21:22:26'),
(3, 'test@gmail.com', '$2y$10$OlIS4o6vXgGDNUqKsrthCelrrevN39k0cICmPPqmOM5n1/AAKsiEO', 'test', '', NULL, 'user', NULL, '2025-12-13 21:32:40');

-- --------------------------------------------------------

--
-- Structure de la table `user_plants`
--

CREATE TABLE `user_plants` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `plant_catalog_id` int(11) NOT NULL,
  `nickname_for_plant` varchar(255) DEFAULT NULL,
  `is_from_marketplace` tinyint(1) DEFAULT 0,
  `purchase_date` date DEFAULT NULL,
  `acquisition_type` enum('seed','plant','unknown') DEFAULT 'unknown',
  `last_watering_date` date DEFAULT NULL,
  `last_fertilizing_date` date DEFAULT NULL,
  `custom_watering_interval_days` int(11) DEFAULT NULL,
  `custom_fertilizing_interval_days` int(11) DEFAULT NULL,
  `room_location` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user_plants`
--

INSERT INTO `user_plants` (`id`, `user_id`, `plant_catalog_id`, `nickname_for_plant`, `is_from_marketplace`, `purchase_date`, `acquisition_type`, `last_watering_date`, `last_fertilizing_date`, `custom_watering_interval_days`, `custom_fertilizing_interval_days`, `room_location`, `notes`, `created_at`) VALUES
(1, 1, 1, NULL, 1, '2025-12-25', 'plant', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-25 10:26:35');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_product` (`user_id`,`product_id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_product_id` (`product_id`);

--
-- Index pour la table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_slug` (`slug`);

--
-- Index pour la table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_post_id` (`post_id`),
  ADD KEY `idx_user_id` (`user_id`);

--
-- Index pour la table `follows`
--
ALTER TABLE `follows`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_follow` (`follower_id`,`following_id`),
  ADD KEY `idx_follower_id` (`follower_id`),
  ADD KEY `idx_following_id` (`following_id`);

--
-- Index pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_is_read` (`is_read`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Index pour la table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_status` (`status`);

--
-- Index pour la table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `idx_order_id` (`order_id`);

--
-- Index pour la table `plant_care_events`
--
ALTER TABLE `plant_care_events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_plant_id` (`user_plant_id`),
  ADD KEY `idx_event_date` (`event_date`);

--
-- Index pour la table `plant_catalog`
--
ALTER TABLE `plant_catalog`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_difficulty` (`difficulty_level`),
  ADD KEY `idx_light` (`light_requirement`),
  ADD KEY `idx_common_name` (`common_name`);

--
-- Index pour la table `plant_catalog_images`
--
ALTER TABLE `plant_catalog_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_plant_catalog_id` (`plant_catalog_id`),
  ADD KEY `idx_display_order` (`display_order`);

--
-- Index pour la table `plant_category_assignments`
--
ALTER TABLE `plant_category_assignments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_assignment` (`plant_catalog_id`,`category_id`),
  ADD KEY `idx_plant_catalog_id` (`plant_catalog_id`),
  ADD KEY `idx_category_id` (`category_id`);

--
-- Index pour la table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `related_user_plant_id` (`related_user_plant_id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_post_type` (`post_type`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Index pour la table `post_likes`
--
ALTER TABLE `post_likes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_like` (`post_id`,`user_id`),
  ADD KEY `idx_post_id` (`post_id`),
  ADD KEY `idx_user_id` (`user_id`);

--
-- Index pour la table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_category` (`category`),
  ADD KEY `idx_related_plant` (`related_plant_catalog_id`);

--
-- Index pour la table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_product_id` (`product_id`),
  ADD KEY `idx_display_order` (`display_order`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_username` (`username`);

--
-- Index pour la table `user_plants`
--
ALTER TABLE `user_plants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_plant_catalog_id` (`plant_catalog_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `follows`
--
ALTER TABLE `follows`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `plant_care_events`
--
ALTER TABLE `plant_care_events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `plant_catalog`
--
ALTER TABLE `plant_catalog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `plant_catalog_images`
--
ALTER TABLE `plant_catalog_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT pour la table `plant_category_assignments`
--
ALTER TABLE `plant_category_assignments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `post_likes`
--
ALTER TABLE `post_likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `user_plants`
--
ALTER TABLE `user_plants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `follows`
--
ALTER TABLE `follows`
  ADD CONSTRAINT `follows_ibfk_1` FOREIGN KEY (`follower_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `follows_ibfk_2` FOREIGN KEY (`following_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `plant_care_events`
--
ALTER TABLE `plant_care_events`
  ADD CONSTRAINT `plant_care_events_ibfk_1` FOREIGN KEY (`user_plant_id`) REFERENCES `user_plants` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `plant_catalog_images`
--
ALTER TABLE `plant_catalog_images`
  ADD CONSTRAINT `plant_catalog_images_ibfk_1` FOREIGN KEY (`plant_catalog_id`) REFERENCES `plant_catalog` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `plant_category_assignments`
--
ALTER TABLE `plant_category_assignments`
  ADD CONSTRAINT `plant_category_assignments_ibfk_1` FOREIGN KEY (`plant_catalog_id`) REFERENCES `plant_catalog` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `plant_category_assignments_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `posts_ibfk_2` FOREIGN KEY (`related_user_plant_id`) REFERENCES `user_plants` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `post_likes`
--
ALTER TABLE `post_likes`
  ADD CONSTRAINT `post_likes_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `post_likes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`related_plant_catalog_id`) REFERENCES `plant_catalog` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `user_plants`
--
ALTER TABLE `user_plants`
  ADD CONSTRAINT `user_plants_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_plants_ibfk_2` FOREIGN KEY (`plant_catalog_id`) REFERENCES `plant_catalog` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
