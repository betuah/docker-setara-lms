-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 30, 2016 at 01:36 PM
-- Server version: 5.6.21
-- PHP Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `raportku`
--

-- --------------------------------------------------------

--
-- Table structure for table `datakelas`
--

CREATE TABLE IF NOT EXISTS `datakelas` (
  `kode_datakelas` char(5) NOT NULL,
  `kode_kelas` char(4) NOT NULL,
  `kode_siswa` char(5) NOT NULL,
  `jurusan` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `datakelas`
--

INSERT INTO `datakelas` (`kode_datakelas`, `kode_kelas`, `kode_siswa`, `jurusan`) VALUES
('DK001', 'K001', 'S0003', 'Rekayasa Perangkat Lunak'),
('DK002', 'K002', 'S0001', 'Multimedia'),
('DK003', 'K003', 'S0002', 'Teknik Komputer & Jaringan'),
('DK004', 'K004', 'S0004', 'Administrasi Perkantoran'),
('DK005', 'K005', 'S0005', 'Administrasi Perkantoran'),
('DK006', 'K004', 'S0006', 'Multimedia'),
('DK007', 'K005', 'S0001', 'Multimedia');

-- --------------------------------------------------------

--
-- Table structure for table `guru`
--

CREATE TABLE IF NOT EXISTS `guru` (
  `kode_guru` char(5) NOT NULL,
  `nip` varchar(18) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `nama_guru` varchar(50) NOT NULL,
  `alamat` varchar(120) NOT NULL,
  `status` enum('Aktif','Tidak') NOT NULL,
  `jenis_kelamin` varchar(10) NOT NULL,
  `foto` longtext NOT NULL,
  `telp` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `guru`
--

INSERT INTO `guru` (`kode_guru`, `nip`, `username`, `password`, `nama_guru`, `alamat`, `status`, `jenis_kelamin`, `foto`, `telp`) VALUES
('G0001', '734974164330004312', 'okto', '2ed20177a0b12db6c51a5c51ba24a3a7', 'OktovinaTandililing', 'Asrama Haji Daya', 'Aktif', 'Perempuan', 'G000191000063130723.1417164484.jpg', '081653497440'),
('G0002', '245375565530001257', 'ana', '276b6c4692e78d4799c12ada515bc3e4', 'Syapriana', 'Jl. Baruga Raya No.16', 'Aktif', 'Perempuan', 'G000291000077154884.1417165410.jpg', '085353586039'),
('G0003', '214075966020003301', 'yonas', '834b2255b131f89f3691be10e798d7a1', 'Yonas Rerung', 'Jl. Kampung Rama Lorong 5', 'Aktif', 'Laki-laki', 'G000391000081176145.1413598573.jpg', '082377690021'),
('G0004', '154173864030003334', 'adhy', 'bf763017d00eeec3799d4ac115239fb3', 'Adhy Permadi Senaen', 'Jl. Perintis Kemerdekaan, Kawasan Kima', 'Aktif', 'Laki-laki', '15417386403000333440307385188001.1417164132.jpg', '081334673124'),
('G0005', '897458974358473957', 'dikacontoh', 'ee5e70d62b9c8deffdae6d2653a14b53', 'Dika Contoh', 'Makassar', 'Aktif', 'Laki-laki', 'G0005arief2.jpg', '085235728754');

-- --------------------------------------------------------

--
-- Table structure for table `kelas`
--

CREATE TABLE IF NOT EXISTS `kelas` (
  `kode_kelas` char(4) NOT NULL,
  `tahun_ajar` varchar(9) NOT NULL,
  `kelas` char(3) NOT NULL,
  `nama_kelas` varchar(20) NOT NULL,
  `kode_guru` char(5) NOT NULL,
  `status` enum('Aktif','Tidak') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kelas`
--

INSERT INTO `kelas` (`kode_kelas`, `tahun_ajar`, `kelas`, `nama_kelas`, `kode_guru`, `status`) VALUES
('K001', '2014/2015', 'X', 'Kelas A', 'G0004', 'Aktif'),
('K002', '2014/2015', 'X', 'Kelas B', 'G0002', 'Aktif'),
('K003', '2014/2015', 'X', 'Kelas C', 'G0003', 'Aktif'),
('K004', '2015/2016', 'XI', 'Kelas A', 'G0001', 'Aktif'),
('K005', '2015/2016', 'XI', 'Kelas B', 'G0004', 'Aktif'),
('K006', '2015/2016', 'XI', 'Kelas C', 'G0002', 'Aktif');

-- --------------------------------------------------------

--
-- Table structure for table `master_kokab`
--

CREATE TABLE IF NOT EXISTS `master_kokab` (
`kota_id` int(10) NOT NULL,
  `kokab_nama` varchar(30) DEFAULT NULL,
  `provinsi_id` int(10) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=503 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `master_kokab`
--

INSERT INTO `master_kokab` (`kota_id`, `kokab_nama`, `provinsi_id`) VALUES
(1, 'Aceh Barat', 1),
(2, 'Aceh Barat Daya', 1),
(3, 'Aceh Besar', 1),
(4, 'Aceh Jaya', 1),
(5, 'Aceh Selatan', 1),
(6, 'Aceh Singkil', 1),
(7, 'Aceh Tamiang', 1),
(8, 'Aceh Tengah', 1),
(9, 'Aceh Tenggara', 1),
(10, 'Aceh Timur', 1),
(11, 'Aceh Utara', 1),
(12, 'Bener Meriah', 1),
(13, 'Bireuen', 1),
(14, 'Gayo Luwes', 1),
(15, 'Nagan Raya', 1),
(16, 'Pidie', 1),
(17, 'Pidie Jaya', 1),
(18, 'Simeulue', 1),
(19, 'Banda Aceh', 1),
(20, 'Langsa', 1),
(21, 'Lhokseumawe', 1),
(22, 'Sabang', 1),
(23, 'Subulussalam', 1),
(24, 'Asahan', 2),
(25, 'Batubara', 2),
(26, 'Dairi', 2),
(27, 'Deli Serdang', 2),
(28, 'Humbang Hasundutan', 2),
(29, 'Karo', 2),
(30, 'Labuhan Batu', 2),
(31, 'Labuhanbatu Selatan', 2),
(32, 'Labuhanbatu Utara', 2),
(33, 'Langkat', 2),
(34, 'Mandailing Natal', 2),
(35, 'Nias', 2),
(36, 'Nias Barat', 2),
(37, 'Nias Selatan', 2),
(38, 'Nias Utara', 2),
(39, 'Padang Lawas', 2),
(40, 'Padang Lawas Utara', 2),
(41, 'Pakpak Barat', 2),
(42, 'Samosir', 2),
(43, 'Serdang Bedagai', 2),
(44, 'Simalungun', 2),
(45, 'Tapanuli Selatan', 2),
(46, 'Tapanuli Tengah', 2),
(47, 'Tapanuli Utara', 2),
(48, 'Toba Samosir', 2),
(49, 'Binjai', 2),
(50, 'Gunung Sitoli', 2),
(51, 'Medan', 2),
(52, 'Padangsidempuan', 2),
(53, 'Pematang Siantar', 2),
(54, 'Sibolga', 2),
(55, 'Tanjung Balai', 2),
(56, 'Tebing Tinggi', 2),
(57, 'Agam', 3),
(58, 'Dharmas Raya', 3),
(59, 'Kepulauan Mentawai', 3),
(60, 'Lima Puluh', 3),
(61, 'Padang Pariaman', 3),
(62, 'Pasaman', 3),
(63, 'Pasaman Barat', 3),
(64, 'Pesisir Selatan', 3),
(65, 'Sijunjung', 3),
(66, 'Solok', 3),
(67, 'Solok Selatan', 3),
(68, 'Tanah Datar', 3),
(69, 'Bukittinggi', 3),
(70, 'Padang', 3),
(71, 'Padang Panjang', 3),
(72, 'Pariaman', 3),
(73, 'Payakumbuh', 3),
(74, 'Sawah Lunto', 3),
(75, 'Solok', 3),
(76, 'Bengkalis', 4),
(77, 'Indragiri Hilir', 4),
(78, 'Indragiri Hulu', 4),
(79, 'Kampar', 4),
(80, 'Kuantan Singingi', 4),
(81, 'Meranti', 4),
(82, 'Pelalawan', 4),
(83, 'Rokan Hilir', 4),
(84, 'Rokan Hulu', 4),
(85, 'Siak', 4),
(86, 'Dumai', 4),
(87, 'Pekanbaru', 4),
(88, 'Bintan', 5),
(89, 'Karimun', 5),
(90, 'Kepulauan Anambas', 5),
(91, 'Lingga', 5),
(92, 'Natuna', 5),
(93, 'Batam', 5),
(94, 'Tanjung Pinang', 5),
(95, 'Bangka', 6),
(96, 'Bangka Barat', 6),
(97, 'Bangka Selatan', 6),
(98, 'Bangka Tengah', 6),
(99, 'Belitung', 6),
(100, 'Belitung Timur', 6),
(101, 'Pangkal Pinang', 6),
(102, 'Kerinci', 7),
(103, 'Merangin', 7),
(104, 'Sarolangun', 7),
(105, 'Batang Hari', 7),
(106, 'Muaro Jambi', 7),
(107, 'Tanjung Jabung Timur', 7),
(108, 'Tanjung Jabung Barat', 7),
(109, 'Tebo', 7),
(110, 'Bungo', 7),
(111, 'Jambi', 7),
(112, 'Sungai Penuh', 7),
(113, 'Bengkulu Selatan', 8),
(114, 'Bengkulu Tengah', 8),
(115, 'Bengkulu Utara', 8),
(116, 'Kaur', 8),
(117, 'Kepahiang', 8),
(118, 'Lebong', 8),
(119, 'Mukomuko', 8),
(120, 'Rejang Lebong', 8),
(121, 'Seluma', 8),
(122, 'Bengkulu', 8),
(123, 'Banyuasin', 9),
(124, 'Empat Lawang', 9),
(125, 'Lahat', 9),
(126, 'Muara Enim', 9),
(127, 'Musi Banyu Asin', 9),
(128, 'Musi Rawas', 9),
(129, 'Ogan Ilir', 9),
(130, 'Ogan Komering Ilir', 9),
(131, 'Ogan Komering Ulu', 9),
(132, 'Ogan Komering Ulu Se', 9),
(133, 'Ogan Komering Ulu Ti', 9),
(134, 'Lubuklinggau', 9),
(135, 'Pagar Alam', 9),
(136, 'Palembang', 9),
(137, 'Prabumulih', 9),
(138, 'Lampung Barat', 10),
(139, 'Lampung Selatan', 10),
(140, 'Lampung Tengah', 10),
(141, 'Lampung Timur', 10),
(142, 'Lampung Utara', 10),
(143, 'Mesuji', 10),
(144, 'Pesawaran', 10),
(145, 'Pringsewu', 10),
(146, 'Tanggamus', 10),
(147, 'Tulang Bawang', 10),
(148, 'Tulang Bawang Barat', 10),
(149, 'Way Kanan', 10),
(150, 'Bandar Lampung', 10),
(151, 'Metro', 10),
(152, 'Lebak', 11),
(153, 'Pandeglang', 11),
(154, 'Serang', 11),
(155, 'Tangerang', 11),
(156, 'Cilegon', 11),
(157, 'Serang', 11),
(158, 'Tangerang', 11),
(159, 'Tangerang Selatan', 11),
(160, 'Adm. Kepulauan Serib', 12),
(161, 'Jakarta Barat', 12),
(162, 'Jakarta Pusat', 12),
(163, 'Jakarta Selatan', 12),
(164, 'Jakarta Timur', 12),
(165, 'Jakarta Utara', 12),
(166, 'Bandung', 13),
(167, 'Bandung Barat', 13),
(168, 'Bekasi', 13),
(169, 'Bogor', 13),
(170, 'Ciamis', 13),
(171, 'Cianjur', 13),
(172, 'Cirebon', 13),
(173, 'Garut', 13),
(174, 'Indramayu', 13),
(175, 'Karawang', 13),
(176, 'Kuningan', 13),
(177, 'Majalengka', 13),
(178, 'Purwakarta', 13),
(179, 'Subang', 13),
(180, 'Sukabumi', 13),
(181, 'Sumedang', 13),
(182, 'Tasikmalaya', 13),
(183, 'Bandung', 13),
(184, 'Banjar', 13),
(185, 'Bekasi', 13),
(186, 'Bogor', 13),
(187, 'Cimahi', 13),
(188, 'Cirebon', 13),
(189, 'Depok', 13),
(190, 'Sukabumi', 13),
(191, 'Tasikmalaya', 13),
(192, 'Banjarnegara', 14),
(193, 'Banyumas', 14),
(194, 'Batang', 14),
(195, 'Blora', 14),
(196, 'Boyolali', 14),
(197, 'Brebes', 14),
(198, 'Cilacap', 14),
(199, 'Demak', 14),
(200, 'Grobogan', 14),
(201, 'Jepara', 14),
(202, 'Karanganyar', 14),
(203, 'Kebumen', 14),
(204, 'Kendal', 14),
(205, 'Klaten', 14),
(206, 'Tegal', 14),
(207, 'Kudus', 14),
(208, 'Magelang', 14),
(209, 'Pati', 14),
(210, 'Pekalongan', 14),
(211, 'Pemalang', 14),
(212, 'Purbalingga', 14),
(213, 'Purworejo', 14),
(214, 'Rembang', 14),
(215, 'Semarang', 14),
(216, 'Sragen', 14),
(217, 'Sukoharjo', 14),
(218, 'Temanggung', 14),
(219, 'Wonogiri', 14),
(220, 'Wonosobo', 14),
(221, 'Magelang', 14),
(222, 'Pekalongan', 14),
(223, 'Salatiga', 14),
(224, 'Semarang', 14),
(225, 'Surakarta', 14),
(226, 'Tegal', 14),
(227, 'Bantul', 15),
(228, 'Gunung Kidul', 15),
(229, 'Kulon Progo', 15),
(230, 'Sleman', 15),
(231, 'Yogyakarta', 15),
(232, 'Bangkalan', 16),
(233, 'Banyuwangi', 16),
(234, 'Blitar', 16),
(235, 'Bojonegoro', 16),
(236, 'Bondowoso', 16),
(237, 'Gresik', 16),
(238, 'Jember', 16),
(239, 'Jombang', 16),
(240, 'Kediri', 16),
(241, 'Lamongan', 16),
(242, 'Lumajang', 16),
(243, 'Madiun', 16),
(244, 'Magetan', 16),
(245, 'Malang', 16),
(246, 'Mojokerto', 16),
(247, 'Nganjuk', 16),
(248, 'Ngawi', 16),
(249, 'Pacitan', 16),
(250, 'Pamekasan', 16),
(251, 'Pasuruan', 16),
(252, 'Ponorogo', 16),
(253, 'Probolinggo', 16),
(254, 'Sampang', 16),
(255, 'Sidoarjo', 16),
(256, 'Situbondo', 16),
(257, 'Sumenep', 16),
(258, 'Trenggalek', 16),
(259, 'Tuban', 16),
(260, 'Tulungagung', 16),
(261, 'Batu', 16),
(262, 'Blitar', 16),
(263, 'Kediri', 16),
(264, 'Madiun', 16),
(265, 'Malang', 16),
(266, 'Mojokerto', 16),
(267, 'Pasuruan', 16),
(268, 'Probolinggo', 16),
(269, 'Surabaya', 16),
(270, 'Badung', 17),
(271, 'Bangli', 17),
(272, 'Buleleng', 17),
(273, 'Gianyar', 17),
(274, 'Jembrana', 17),
(275, 'Karang Asem', 17),
(276, 'Klungkung', 17),
(277, 'Tabanan', 17),
(278, 'Denpasar', 17),
(279, 'Bima', 18),
(280, 'Dompu', 18),
(281, 'Lombok Barat', 18),
(282, 'Lombok Tengah', 18),
(283, 'Lombok Timur', 18),
(284, 'Lombok Utara', 18),
(285, 'Sumbawa', 18),
(286, 'Sumbawa Barat', 18),
(287, 'Bima', 18),
(288, 'Mataram', 18),
(289, 'Alor', 19),
(290, 'Belu', 19),
(291, 'Ende', 19),
(292, 'Flores Timur', 19),
(293, 'Kupang', 19),
(294, 'Lembata', 19),
(295, 'Manggarai', 19),
(296, 'Manggarai Barat', 19),
(297, 'Manggarai Timur', 19),
(298, 'Nagekeo', 19),
(299, 'Ngada', 19),
(300, 'Rote Ndao', 19),
(301, 'Sabu Raijua', 19),
(302, 'Sikka', 19),
(303, 'Sumba Barat', 19),
(304, 'Sumba Barat Daya', 19),
(305, 'Sumba Tengah', 19),
(306, 'Sumba Timur', 19),
(307, 'Timor Tengah Selatan', 19),
(308, 'Timor Tengah Utara', 19),
(309, 'Kupang', 19),
(310, 'Bengkayang', 20),
(311, 'Kapuas Hulu', 20),
(312, 'Kayong Utara', 20),
(313, 'Ketapang', 20),
(314, 'Kubu Raya', 20),
(315, 'Landak', 20),
(316, 'Melawi', 20),
(317, 'Pontianak', 20),
(318, 'Sambas', 20),
(319, 'Sanggau', 20),
(320, 'Sekadau', 20),
(321, 'Sintang', 20),
(322, 'Pontianak', 20),
(323, 'Singkawang', 20),
(324, 'Barito Selatan', 21),
(325, 'Barito Timur', 21),
(326, 'Barito Utara', 21),
(327, 'Gunung Mas', 21),
(328, 'Kapuas', 21),
(329, 'Katingan', 21),
(330, 'aringin Barat', 21),
(331, 'aringin Timur', 21),
(332, 'Lamandau', 21),
(333, 'Murung Raya', 21),
(334, 'Pulang Pisau', 21),
(335, 'Seruyan', 21),
(336, 'Sukamara', 21),
(337, 'Palangkaraya', 21),
(338, 'Balangan', 22),
(339, 'Banjar', 22),
(340, 'Barito Kuala', 22),
(341, 'Hulu Sungai Selatan', 22),
(342, 'Hulu Sungai Tengah', 22),
(343, 'Hulu Sungai Utara', 22),
(344, 'Baru', 22),
(345, 'Tabalong', 22),
(346, 'Tanah Bumbu', 22),
(347, 'Tanah Laut', 22),
(348, 'Tapin', 22),
(349, 'Banjar Baru', 22),
(350, 'Banjarmasin', 22),
(351, 'Berau', 23),
(352, 'Bulongan', 23),
(353, 'Kutai Barat', 23),
(354, 'Kutai Kartanegara', 23),
(355, 'Kutai Timur', 23),
(356, 'Malinau', 23),
(357, 'Nunukan', 23),
(358, 'Paser', 23),
(359, 'Penajam Paser Utara', 23),
(360, 'Tana Tidung', 23),
(361, 'Balikpapan', 23),
(362, 'Bontang', 23),
(363, 'Samarinda', 23),
(364, 'Tarakan', 23),
(365, 'Boalemo', 24),
(366, 'Bone Bolango', 24),
(367, 'Gorontalo', 24),
(368, 'Gorontalo Utara', 24),
(369, 'Pohuwato', 24),
(370, 'Gorontalo', 24),
(371, 'Bantaeng', 25),
(372, 'Barru', 25),
(373, 'Bone', 25),
(374, 'Bulukumba', 25),
(375, 'Enrekang', 25),
(376, 'Gowa', 25),
(377, 'Jeneponto', 25),
(378, 'Luwu', 25),
(379, 'Luwu Timur', 25),
(380, 'Luwu Utara', 25),
(381, 'Maros', 25),
(382, 'Pangkajene Kepulauan', 25),
(383, 'Pinrang', 25),
(384, 'Selayar', 25),
(385, 'Sidenreng Rappang', 25),
(386, 'Sinjai', 25),
(387, 'Soppeng', 25),
(388, 'Takalar', 25),
(389, 'Tana Toraja', 25),
(390, 'Toraja Utara', 25),
(391, 'Wajo', 25),
(392, 'Makassar', 25),
(393, 'Palopo', 25),
(394, 'Pare-pare', 25),
(395, 'Bombana', 26),
(396, 'Buton', 26),
(397, 'Buton Utara', 26),
(398, 'Kolaka', 26),
(399, 'Kolaka Utara', 26),
(400, 'Konawe', 26),
(401, 'Konawe Selatan', 26),
(402, 'Konawe Utara', 26),
(403, 'Muna', 26),
(404, 'Wakatobi', 26),
(405, 'Bau-bau', 26),
(406, 'Kendari', 26),
(407, 'Banggai', 27),
(408, 'Banggai Kepulauan', 27),
(409, 'Buol', 27),
(410, 'Donggala', 27),
(411, 'Morowali', 27),
(412, 'Parigi Moutong', 27),
(413, 'Poso', 27),
(414, 'Sigi', 27),
(415, 'Tojo Una-Una', 27),
(416, 'Toli Toli', 27),
(417, 'Palu', 27),
(418, 'Bolaang Mangondow', 28),
(419, 'Bolaang Mangondow Se', 28),
(420, 'Bolaang Mangondow Ti', 28),
(421, 'Bolaang Mangondow Ut', 28),
(422, 'Kepulauan Sangihe', 28),
(423, 'Kepulauan Siau Tagul', 28),
(424, 'Kepulauan Talaud', 28),
(425, 'Minahasa', 28),
(426, 'Minahasa Selatan', 28),
(427, 'Minahasa Tenggara', 28),
(428, 'Minahasa Utara', 28),
(429, 'Bitung', 28),
(430, 'obagu', 28),
(431, 'Manado', 28),
(432, 'Tomohon', 28),
(433, 'Majene', 29),
(434, 'Mamasa', 29),
(435, 'Mamuju', 29),
(436, 'Mamuju Utara', 29),
(437, 'Polewali Mandar', 29),
(438, 'Buru', 30),
(439, 'Buru Selatan', 30),
(440, 'Kepulauan Aru', 30),
(441, 'Maluku Barat Daya', 30),
(442, 'Maluku Tengah', 30),
(443, 'Maluku Tenggara', 30),
(444, 'Maluku Tenggara Bara', 30),
(445, 'Seram Bagian Barat', 30),
(446, 'Seram Bagian Timur', 30),
(447, 'Ambon', 30),
(448, 'Tual', 30),
(449, 'Halmahera Barat', 31),
(450, 'Halmahera Selatan', 31),
(451, 'Halmahera Tengah', 31),
(452, 'Halmahera Timur', 31),
(453, 'Halmahera Utara', 31),
(454, 'Kepulauan Sula', 31),
(455, 'Pulau Morotai', 31),
(456, 'Ternate', 31),
(457, 'Tidore Kepulauan', 31),
(458, 'Fakfak', 32),
(459, 'Kaimana', 32),
(460, 'Manokwari', 32),
(461, 'Maybrat', 32),
(462, 'Raja Ampat', 32),
(463, 'Sorong', 32),
(464, 'Sorong Selatan', 32),
(465, 'Tambrauw', 32),
(466, 'Teluk Bintuni', 32),
(467, 'Teluk Wondama', 32),
(468, 'Sorong', 32),
(469, 'Merauke', 33),
(470, 'Jayawijaya', 33),
(471, 'Nabire', 33),
(472, 'Kepulauan Yapen', 33),
(473, 'Biak Numfor', 33),
(474, 'Paniai', 33),
(475, 'Puncak Jaya', 33),
(476, 'Mimika', 33),
(477, 'Boven Digoel', 33),
(478, 'Mappi', 33),
(479, 'Asmat', 33),
(480, 'Yahukimo', 33),
(481, 'Pegunungan Bintang', 33),
(482, 'Tolikara', 33),
(483, 'Sarmi', 33),
(484, 'Keerom', 33),
(485, 'Waropen', 33),
(486, 'Jayapura', 33),
(487, 'Deiyai', 33),
(488, 'Dogiyai', 33),
(489, 'Intan Jaya', 33),
(490, 'Lanny Jaya', 33),
(491, 'Mamberamo Raya', 33),
(492, 'Mamberamo Tengah', 33),
(493, 'Nduga', 33),
(494, 'Puncak', 33),
(495, 'Supiori', 33),
(496, 'Yalimo', 33),
(497, 'Jayapura', 33),
(498, 'Bulungan', 34),
(499, 'Malinau', 34),
(500, 'Nunukan', 34),
(501, 'Tana Tidung', 34),
(502, 'Tarakan', 34);

-- --------------------------------------------------------

--
-- Table structure for table `nilai`
--

CREATE TABLE IF NOT EXISTS `nilai` (
  `kode_nilai` char(4) NOT NULL,
  `semester` int(2) NOT NULL,
  `kode_pelajaran` char(4) NOT NULL,
  `kode_guru` char(5) NOT NULL,
  `kode_kelas` char(4) NOT NULL,
  `kode_siswa` char(5) NOT NULL,
  `nilai_tugas` int(2) NOT NULL,
  `nilai_tugas2` int(2) NOT NULL,
  `nilai_tugas3` int(2) NOT NULL,
  `nilai_uts` int(2) NOT NULL,
  `nilai_uas` int(2) NOT NULL,
  `keterangan` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `nilai`
--

INSERT INTO `nilai` (`kode_nilai`, `semester`, `kode_pelajaran`, `kode_guru`, `kode_kelas`, `kode_siswa`, `nilai_tugas`, `nilai_tugas2`, `nilai_tugas3`, `nilai_uts`, `nilai_uas`, `keterangan`) VALUES
('N001', 1, 'P001', 'G0004', 'K001', 'S0003', 86, 53, 69, 76, 85, ''),
('N002', 1, 'P002', 'G0004', 'K001', 'S0003', 90, 78, 55, 95, 85, ''),
('N003', 1, 'P003', 'G0004', 'K001', 'S0003', 80, 71, 71, 80, 85, ''),
('N007', 1, 'P004', 'G0004', 'K001', 'S0003', 79, 66, 52, 57, 72, ''),
('N008', 1, 'P005', 'G0004', 'K001', 'S0003', 80, 92, 94, 57, 66, ''),
('N009', 1, 'P006', 'G0004', 'K001', 'S0003', 66, 60, 65, 93, 96, ''),
('N010', 2, 'P001', 'G0004', 'K001', 'S0003', 67, 69, 80, 86, 88, ''),
('N011', 2, 'P002', 'G0004', 'K001', 'S0003', 81, 85, 81, 78, 70, ''),
('N012', 2, 'P003', 'G0004', 'K001', 'S0003', 86, 82, 61, 57, 95, ''),
('N013', 2, 'P004', 'G0004', 'K001', 'S0003', 55, 67, 68, 91, 98, ''),
('N014', 2, 'P005', 'G0004', 'K001', 'S0003', 99, 99, 89, 73, 86, ''),
('N015', 2, 'P006', 'G0004', 'K001', 'S0003', 80, 94, 32, 31, 47, ''),
('N016', 1, 'P001', 'G0002', 'K002', 'S0001', 88, 82, 97, 82, 79, ''),
('N017', 1, 'P002', 'G0002', 'K002', 'S0001', 51, 53, 49, 56, 59, ''),
('N018', 1, 'P003', 'G0002', 'K002', 'S0001', 80, 83, 53, 69, 74, ''),
('N019', 1, 'P004', 'G0002', 'K002', 'S0001', 92, 92, 95, 87, 87, ''),
('N020', 1, 'P005', 'G0002', 'K002', 'S0001', 69, 77, 86, 89, 97, ''),
('N021', 1, 'P006', 'G0002', 'K002', 'S0001', 80, 89, 82, 88, 72, ''),
('N022', 2, 'P001', 'G0002', 'K002', 'S0001', 78, 82, 72, 72, 98, ''),
('N023', 2, 'P002', 'G0002', 'K002', 'S0001', 87, 92, 96, 90, 90, ''),
('N024', 2, 'P003', 'G0002', 'K002', 'S0001', 68, 63, 75, 83, 94, ''),
('N025', 2, 'P004', 'G0002', 'K002', 'S0001', 96, 81, 66, 82, 91, ''),
('N026', 2, 'P005', 'G0002', 'K002', 'S0001', 82, 82, 83, 94, 94, ''),
('N027', 2, 'P006', 'G0002', 'K002', 'S0001', 83, 79, 99, 59, 79, '');

-- --------------------------------------------------------

--
-- Table structure for table `pelajaran`
--

CREATE TABLE IF NOT EXISTS `pelajaran` (
  `kode_pelajaran` char(4) NOT NULL,
  `nama_pelajaran` varchar(100) NOT NULL,
  `keterangan` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pelajaran`
--

INSERT INTO `pelajaran` (`kode_pelajaran`, `nama_pelajaran`, `keterangan`) VALUES
('P001', 'Pendidikan Agama', 'Tambahan'),
('P002', 'Bahasa Indonesia', 'Wajib'),
('P003', 'Bahasa Inggris', 'Wajib'),
('P004', 'Matematika', 'Wajib'),
('P005', 'Kimia', 'Wajib'),
('P006', 'Fisika', 'Wajib');

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

CREATE TABLE IF NOT EXISTS `siswa` (
  `kode_siswa` char(5) NOT NULL,
  `nis` varchar(7) NOT NULL,
  `password` varchar(100) NOT NULL,
  `nama_siswa` varchar(50) NOT NULL,
  `alamat` varchar(120) NOT NULL,
  `tmp_lahir` varchar(50) NOT NULL,
  `tgl_lahir` date NOT NULL,
  `telp` varchar(12) NOT NULL,
  `jenis_kelamin` varchar(20) NOT NULL,
  `agama` varchar(20) NOT NULL,
  `tahun_angkatan` varchar(9) NOT NULL,
  `status` enum('Aktif','Tidak') NOT NULL,
  `foto` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `siswa`
--

INSERT INTO `siswa` (`kode_siswa`, `nis`, `password`, `nama_siswa`, `alamat`, `tmp_lahir`, `tgl_lahir`, `telp`, `jenis_kelamin`, `agama`, `tahun_angkatan`, `status`, `foto`) VALUES
('S0001', '2015001', 'bcd724d15cde8c47650fda962968f102', 'Alriosavant Taasye', 'Aspol Tello No. 54', 'Makassar', '1995-01-08', '081343634775', 'Laki-laki', 'Islam', '2014/2015', 'Aktif', 'S0001Ardikaedit.png'),
('S0002', '2015002', 'bcd724d15cde8c47650fda962968f102', 'Dendy Nurak', 'Aspol Toddopulli No.15', 'Flores Timur', '1995-01-18', '085343668030', 'Laki-laki', 'Kristen', '2014/2015', 'Aktif', '2015002Ardikaedit.png'),
('S0003', '2015003', 'bcd724d15cde8c47650fda962968f102', 'Ardika', 'Aspol Panaikang No.6 ', 'Sorong', '1994-05-10', '085634129503', 'Laki-laki', 'Islam', '2014/2015', 'Aktif', 'S0003Ardikaedit.png'),
('S0004', '2015004', 'bcd724d15cde8c47650fda962968f102', 'Andi Inggrit Juningrat', 'Perumnas Antang Raya', 'Makassar', '1996-01-03', '087744359409', 'Perempuan', 'Islam', '2015/2016', 'Aktif', 'S0004Ardikaedit.png'),
('S0005', '2015005', 'bcd724d15cde8c47650fda962968f102', 'Dwi Yanti Natalia', 'Bumi Permata Sudiang', 'Makassar', '1995-06-09', '081158339321', 'Perempuan', 'Kristen', '2014/2015', 'Aktif', 'S0005Ardikaedit.png'),
('S0006', '2015006', 'f970e2767d0cfe75876ea857f92e319b', 'dika', 'Jl. Kakatua', 'Manado', '1996-01-06', '777364366271', 'Laki-laki', 'Islam', '2015/2016', 'Aktif', 'S0006Ardikaedit.png');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `kode_user` char(4) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `nama_user` varchar(50) NOT NULL,
  `telp` varchar(12) NOT NULL,
  `email` varchar(100) NOT NULL,
  `foto` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`kode_user`, `username`, `password`, `nama_user`, `telp`, `email`, `foto`) VALUES
('U001', 'admin', '21232f297a57a5a743894a0e4a801fc3', 'Nurwisam', '081342154141', 'ardikahnc@gmail.com', 'arief2.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `datakelas`
--
ALTER TABLE `datakelas`
 ADD PRIMARY KEY (`kode_datakelas`);

--
-- Indexes for table `guru`
--
ALTER TABLE `guru`
 ADD PRIMARY KEY (`kode_guru`), ADD UNIQUE KEY `nip` (`nip`);

--
-- Indexes for table `kelas`
--
ALTER TABLE `kelas`
 ADD PRIMARY KEY (`kode_kelas`);

--
-- Indexes for table `master_kokab`
--
ALTER TABLE `master_kokab`
 ADD PRIMARY KEY (`kota_id`), ADD KEY `pro_kota` (`provinsi_id`);

--
-- Indexes for table `nilai`
--
ALTER TABLE `nilai`
 ADD PRIMARY KEY (`kode_nilai`);

--
-- Indexes for table `pelajaran`
--
ALTER TABLE `pelajaran`
 ADD PRIMARY KEY (`kode_pelajaran`);

--
-- Indexes for table `siswa`
--
ALTER TABLE `siswa`
 ADD PRIMARY KEY (`kode_siswa`), ADD UNIQUE KEY `nis` (`nis`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
 ADD PRIMARY KEY (`kode_user`), ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `master_kokab`
--
ALTER TABLE `master_kokab`
MODIFY `kota_id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=503;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
