-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- ホスト: 127.0.0.1
-- 生成日時: 2023-09-15 14:09:56
-- サーバのバージョン： 10.4.27-MariaDB
-- PHP のバージョン: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `blog_db02`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `admin`
--

CREATE TABLE `admin` (
  `id` int(100) NOT NULL,
  `name` varchar(20) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `admin`
--

INSERT INTO `admin` (`id`, `name`, `password`) VALUES
(1, 'admin', '6216f8a75fd5bb3d5f22b6f9958cdede3fc086c2'),
(4, 'admin02', '6216f8a75fd5bb3d5f22b6f9958cdede3fc086c2'),
(5, 'admin03', '6216f8a75fd5bb3d5f22b6f9958cdede3fc086c2');

-- --------------------------------------------------------

--
-- テーブルの構造 `comments`
--

CREATE TABLE `comments` (
  `id` int(100) NOT NULL,
  `post_id` int(100) NOT NULL,
  `admin_id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `user_name` varchar(50) NOT NULL,
  `comment` varchar(1000) NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `comments`
--

INSERT INTO `comments` (`id`, `post_id`, `admin_id`, `user_id`, `user_name`, `comment`, `date`) VALUES
(23, 14, 1, 1, 'user01', 'this is test comment', '2023-09-15'),
(24, 17, 4, 1, 'user01', 'this is user01 comment', '2023-09-15'),
(26, 15, 1, 2, 'user02', 'this is user.2 test comment ', '2023-09-15'),
(27, 14, 1, 2, 'user02', 'this is user02 test comment edikt', '2023-09-15'),
(28, 14, 1, 2, 'user02', 'this is another user02 comment', '2023-09-15'),
(29, 14, 1, 2, 'user02', 'this remove user02 commnet', '2023-09-15');

-- --------------------------------------------------------

--
-- テーブルの構造 `likes`
--

CREATE TABLE `likes` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `admin_id` int(100) NOT NULL,
  `post_id` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `likes`
--

INSERT INTO `likes` (`id`, `user_id`, `admin_id`, `post_id`) VALUES
(7, 2, 4, 17),
(16, 1, 1, 15),
(22, 1, 4, 17),
(26, 1, 5, 23),
(28, 1, 4, 19),
(34, 1, 1, 14),
(35, 1, 5, 21),
(37, 2, 1, 14),
(39, 2, 1, 16),
(42, 2, 1, 15);

-- --------------------------------------------------------

--
-- テーブルの構造 `posts`
--

CREATE TABLE `posts` (
  `id` int(100) NOT NULL,
  `admin_id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `title` varchar(100) NOT NULL,
  `content` varchar(10000) NOT NULL,
  `category` varchar(50) NOT NULL,
  `image` varchar(100) NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp(),
  `status` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `posts`
--

INSERT INTO `posts` (`id`, `admin_id`, `name`, `title`, `content`, `category`, `image`, `date`, `status`) VALUES
(2, 1, 'admin', 'testing post title', 'this content is tensting', 'nature', 'priscilla-du-preez-CoqJGsFVJtM-unsplash.jpg', '2023-08-31', 'deactive'),
(14, 1, 'admin', 'new posts title', 'Lorem ipsum, dolor sit amet consectetur adipisicing elit.\r\nVeniam doloribus, voluptatem adipisci minima dicta molestiae\r\n\r\nducimus sint nulla aut enim ullam fugiat eos facilis facere veritatis \r\nrem modi! Suscipit minus necessitatibus fuga assumenda explicabo nam reprehenderit.\r\n\r\nTotam, qui ullam! Dolorum dolores harum sint minus cum. Ex quos quaerat\r\ntotam blanditiis sunt eligendi sapiente repellat error facilis aperiam\r\n\r\nfacere iure incidunt, inventore distinctio accusantium et rem fugiat aspernatur\r\neius alias! Necessitatibus, odit animi deleniti dignissimos eaque esse ratione \r\n\r\neligendi impedit voluptate at adipisci velit, quis fugiat molestias dolore maiores\r\nporro vitae ex ullam! Ex quasi nostrum rem quia et magnam fugiat!', 'fashion', 'alex-haigh-fEt6Wd4t4j0-unsplash.jpg', '2023-09-05', 'active'),
(15, 1, 'admin', 'new post iamge', 'Lorem ipsum, dolor sit amet consectetur adipisicing elit.\r\nVeniam doloribus, voluptatem adipisci minima dicta molestiae\r\n\r\nducimus sint nulla aut enim ullam fugiat eos facilis facere veritatis \r\nrem modi! Suscipit minus necessitatibus fuga assumenda explicabo nam reprehenderit.\r\n\r\nTotam, qui ullam! Dolorum dolores harum sint minus cum. Ex quos quaerat\r\ntotam blanditiis sunt eligendi sapiente repellat error facilis aperiam\r\n\r\nfacere iure incidunt, inventore distinctio accusantium et rem fugiat aspernatur\r\neius alias! Necessitatibus, odit animi deleniti dignissimos eaque esse ratione ', 'gaming', 'post_2.webp', '2023-09-05', 'active'),
(16, 1, 'admin', 'test title', 'Lorem ipsum, dolor sit amet consectetur adipisicing elit.\r\nVeniam doloribus, voluptatem adipisci minima dicta molestiae\r\n\r\nducimus sint nulla aut enim ullam fugiat eos facilis facere veritatis \r\nrem modi! Suscipit minus necessitatibus fuga assumenda explicabo nam reprehenderit.\r\n\r\nTotam, qui ullam! Dolorum dolores harum sint minus cum. Ex quos quaerat\r\ntotam blanditiis sunt eligendi sapiente repellat error facilis aperiam\r\n\r\nfacere iure incidunt, inventore distinctio accusantium et rem fugiat aspernatur\r\neius alias! Necessitatibus, odit animi deleniti dignissimos eaque esse ratione ', 'news', 'post_3.webp', '2023-09-08', 'active'),
(17, 4, 'admin02', 'test post content', 'Lorem ipsum, dolor sit amet consectetur adipisicing elit.\r\nVeniam doloribus, voluptatem adipisci minima dicta molestiae\r\n\r\nducimus sint nulla aut enim ullam fugiat eos facilis facere veritatis \r\nrem modi! Suscipit minus necessitatibus fuga assumenda explicabo nam reprehenderit.\r\n\r\nTotam, qui ullam! Dolorum dolores harum sint minus cum. Ex quos quaerat\r\ntotam blanditiis sunt eligendi sapiente repellat error facilis aperiam\r\n\r\nfacere iure incidunt, inventore distinctio accusantium et rem fugiat aspernatur\r\neius alias! Necessitatibus, odit animi deleniti dignissimos eaque esse ratione ', 'food and drinks', 'takuya-nagaoka-fENvSZUzbzU-unsplash (1).jpg', '2023-09-08', 'active'),
(19, 4, 'admin02', 'post test contens', 'Lorem ipsum, dolor sit amet consectetur adipisicing elit.\r\nVeniam doloribus, voluptatem adipisci minima dicta molestiae\r\n\r\nducimus sint nulla aut enim ullam fugiat eos facilis facere veritatis \r\nrem modi! Suscipit minus necessitatibus fuga assumenda explicabo nam reprehenderit.\r\n\r\nTotam, qui ullam! Dolorum dolores harum sint minus cum. Ex quos quaerat\r\ntotam blanditiis sunt eligendi sapiente repellat error facilis aperiam\r\n\r\nfacere iure incidunt, inventore distinctio accusantium et rem fugiat aspernatur\r\neius alias! Necessitatibus, odit animi deleniti dignissimos eaque esse ratione ', 'travel', 'erik-mclean-ZRns2R5azu0-unsplash.jpg', '2023-09-08', 'active'),
(20, 4, 'admin02', 'admin2 test', 'Lorem ipsum, dolor sit amet consectetur adipisicing elit.\r\nVeniam doloribus, voluptatem adipisci minima dicta molestiae\r\n\r\nducimus sint nulla aut enim ullam fugiat eos facilis facere veritatis \r\nrem modi! Suscipit minus necessitatibus fuga assumenda explicabo nam reprehenderit.\r\n\r\nTotam, qui ullam! Dolorum dolores harum sint minus cum. Ex quos quaerat\r\ntotam blanditiis sunt eligendi sapiente repellat error facilis aperiam\r\n\r\nfacere iure incidunt, inventore distinctio accusantium et rem fugiat aspernatur\r\neius alias! Necessitatibus, odit animi deleniti dignissimos eaque esse ratione ', 'nature', 'pexels-chevanon-photography-1335971 (1).jpg', '2023-09-08', 'active'),
(21, 5, 'admin03', 'admin03 post title', 'Lorem ipsum, dolor sit amet consectetur adipisicing elit.\r\nVeniam doloribus, voluptatem adipisci minima dicta molestiae\r\n\r\nducimus sint nulla aut enim ullam fugiat eos facilis facere veritatis \r\nrem modi! Suscipit minus necessitatibus fuga assumenda explicabo nam reprehenderit.\r\n\r\nTotam, qui ullam! Dolorum dolores harum sint minus cum. Ex quos quaerat\r\ntotam blanditiis sunt eligendi sapiente repellat error facilis aperiam\r\n\r\nfacere iure incidunt, inventore distinctio accusantium et rem fugiat aspernatur\r\neius alias! Necessitatibus, odit animi deleniti dignissimos eaque esse ratione ', 'pets and animals', 'daniel-tuttle-deTto-_UXCk-unsplash.jpg', '2023-09-08', 'active'),
(22, 5, 'admin03', 'admin03 testin post', 'Lorem ipsum, dolor sit amet consectetur adipisicing elit.\r\nVeniam doloribus, voluptatem adipisci minima dicta molestiae\r\n\r\nducimus sint nulla aut enim ullam fugiat eos facilis facere veritatis \r\nrem modi! Suscipit minus necessitatibus fuga assumenda explicabo nam reprehenderit.\r\n\r\nTotam, qui ullam! Dolorum dolores harum sint minus cum. Ex quos quaerat\r\ntotam blanditiis sunt eligendi sapiente repellat error facilis aperiam\r\n\r\nfacere iure incidunt, inventore distinctio accusantium et rem fugiat aspernatur\r\neius alias! Necessitatibus, odit animi deleniti dignissimos eaque esse ratione ', 'movies and animations', 'post_6.webp', '2023-09-08', 'active'),
(23, 5, 'admin03', 'testing post admin3', 'Lorem ipsum, dolor sit amet consectetur adipisicing elit.\r\nVeniam doloribus, voluptatem adipisci minima dicta molestiae\r\n\r\nducimus sint nulla aut enim ullam fugiat eos facilis facere veritatis \r\nrem modi! Suscipit minus necessitatibus fuga assumenda explicabo nam reprehenderit.\r\n\r\nTotam, qui ullam! Dolorum dolores harum sint minus cum. Ex quos quaerat\r\ntotam blanditiis sunt eligendi sapiente repellat error facilis aperiam\r\n\r\nfacere iure incidunt, inventore distinctio accusantium et rem fugiat aspernatur\r\neius alias! Necessitatibus, odit animi deleniti dignissimos eaque esse ratione ', 'nature', '', '2023-09-08', 'active');

-- --------------------------------------------------------

--
-- テーブルの構造 `users`
--

CREATE TABLE `users` (
  `id` int(100) NOT NULL,
  `name` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`) VALUES
(1, 'user01', 'user01@gmail.com', '6216f8a75fd5bb3d5f22b6f9958cdede3fc086c2'),
(2, 'user02', 'user02@gmail.com', '6216f8a75fd5bb3d5f22b6f9958cdede3fc086c2'),
(4, 'userA', 'userA@gmail.com', '6216f8a75fd5bb3d5f22b6f9958cdede3fc086c2'),
(5, 'userB', 'userB@gmail.com', '6216f8a75fd5bb3d5f22b6f9958cdede3fc086c2');

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- テーブルのインデックス `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- ダンプしたテーブルの AUTO_INCREMENT
--

--
-- テーブルの AUTO_INCREMENT `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- テーブルの AUTO_INCREMENT `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- テーブルの AUTO_INCREMENT `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- テーブルの AUTO_INCREMENT `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- テーブルの AUTO_INCREMENT `users`
--
ALTER TABLE `users`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
