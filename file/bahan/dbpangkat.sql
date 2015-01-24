-- phpMyAdmin SQL Dump
-- version 4.1.6
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jul 08, 2014 at 11:55 PM
-- Server version: 5.6.16
-- PHP Version: 5.5.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `dbpangkat`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE IF NOT EXISTS `admin` (
  `idadmin` int(11) NOT NULL AUTO_INCREMENT,
  `iduser` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `idfak` int(11) NOT NULL,
  PRIMARY KEY (`idadmin`),
  KEY `idfak` (`idfak`),
  KEY `iduser` (`iduser`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`idadmin`, `iduser`, `nama`, `idfak`) VALUES
(1, 1, 'Teknik', 1),
(2, 46, 'Ekonomi', 3);

-- --------------------------------------------------------

--
-- Table structure for table `batut`
--

CREATE TABLE IF NOT EXISTS `batut` (
  `id_batut` int(11) NOT NULL AUTO_INCREMENT,
  `batut` varchar(100) NOT NULL,
  PRIMARY KEY (`id_batut`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=40 ;

--
-- Dumping data for table `batut`
--

INSERT INTO `batut` (`id_batut`, `batut`) VALUES
(1, 'satu sertifikat per periode penilaian'),
(2, 'satu sertifikat per tahun'),
(3, 'satu sertifikat per semester'),
(4, 'maksimal 12 sks'),
(5, 'tidak dibatasi jumlah mahasiswa dihitung persemester'),
(6, 'delapan lulusan per semester'),
(7, 'sepuluh lulusan per semester'),
(8, 'enam lulusan per semester'),
(9, 'empat lulusan per semester'),
(10, 'satu mata kuliah per semester'),
(11, 'satu buku per tahun'),
(12, 'satu karya per semester'),
(13, 'dua perguruan tinggi per semester'),
(14, 'dosen yang menduduki lebih dari satu jabatan maka angka kredit paling tinggi yang dihitung'),
(15, 'dihitung per-semester'),
(16, 'satu kegiatan pencangkokan persemester'),
(17, 'satu kegiatan detasering persemester'),
(18, 'satu buku pertahun'),
(19, 'reprint artikel yang dicetak oleh penerbit (legalisir)'),
(20, 'satu artikel persemester'),
(21, 'dua artikel persemester'),
(22, 'satu makalah per-semester'),
(23, 'dua makalah per-semester'),
(24, 'satu poster per-semester'),
(25, 'dua poster per-semester'),
(26, 'maksimal 10% dari angka kredit minimal yang diperlukan untuk melaksanakan penelitian'),
(27, 'satu buku persemster'),
(28, 'satu karya per tahun'),
(29, 'dihitung tiap program'),
(30, 'dihitung tiap karya'),
(31, 'dihitung per-tahun'),
(32, 'dihitung perkepanitiaan'),
(33, 'dihitung tiap periode jabatan'),
(34, 'dihitung tiap kepanitiaan'),
(35, 'dihitung tiap kegiatan'),
(37, 'dihitung tiap penghargaan'),
(38, 'dihitung tiap buku'),
(39, 'dihitung tiap medali');

-- --------------------------------------------------------

--
-- Table structure for table `bukeg`
--

CREATE TABLE IF NOT EXISTS `bukeg` (
  `idbukeg` int(11) NOT NULL AUTO_INCREMENT,
  `iddtk` int(11) NOT NULL,
  `file` text NOT NULL,
  `status` enum('new','valid','invalid') NOT NULL DEFAULT 'new',
  `keterangan` text NOT NULL,
  PRIMARY KEY (`idbukeg`),
  KEY `iddtk` (`iddtk`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=157 ;

--
-- Dumping data for table `bukeg`
--

INSERT INTO `bukeg` (`idbukeg`, `iddtk`, `file`, `status`, `keterangan`) VALUES
(48, 49, '130_4858b5dce3.jpeg', 'new', ''),
(49, 49, '130_dc080cb0bc.png', 'new', ''),
(50, 50, '5030_d0dc21cb59.jpeg', 'new', ''),
(51, 51, '5130_76db2149a1.gif', 'new', ''),
(52, 51, '5130_efabd48676.jpeg', 'new', ''),
(53, 52, '5230_bf4ed67744.jpeg', 'new', ''),
(54, 53, '5330_853db51009.jpeg', 'new', ''),
(55, 54, '5430_d1f773f11f.jpeg', 'valid', ''),
(56, 55, '5552_3daa1a6397.png', 'valid', ''),
(57, 56, '5652_e4a31a18bf.jpeg', 'valid', ''),
(58, 57, '5752_0fa552caba.jpeg', 'valid', ''),
(59, 58, '5852_1e14df7ce2.jpeg', 'invalid', 'gambar tidak valid'),
(60, 59, '5952_d6aa6f31ab.jpeg', 'valid', ''),
(61, 60, '6052_2deb9feec7.jpeg', 'valid', ''),
(62, 61, '6152_28445c7260.png', 'valid', ''),
(63, 62, '6252_573bd82e1a.png', 'valid', ''),
(64, 63, '6352_aa93951ef4.png', 'valid', ''),
(65, 64, '6452_4ad6e998e5.png', 'invalid', 'gambar tidak valid'),
(66, 65, '6552_ff3f5956e2.png', 'invalid', 'gambar tidak valid'),
(67, 54, '5430_a2451f45b9.png', 'valid', ''),
(68, 66, '6641_e170929b88.png', 'valid', ''),
(69, 67, '6741_e75f041b25.png', 'valid', ''),
(70, 68, '6841_da0bc6cb59.png', 'valid', ''),
(71, 69, '6941_ac11986a58.png', 'valid', ''),
(72, 70, '7041_f6364f2da5.png', 'valid', ''),
(73, 71, '7141_bb4035c179.png', 'valid', ''),
(74, 72, '7241_242d15910b.png', 'valid', ''),
(75, 73, '7341_e1a9b78b36.png', 'valid', ''),
(76, 74, '7441_1f0aa06512.png', 'valid', ''),
(77, 75, '7541_061faa52db.png', 'valid', ''),
(78, 76, '7641_e443bc3e55.png', 'valid', ''),
(79, 77, '7741_0a03427053.png', 'valid', ''),
(80, 78, '7841_5bab1c3057.png', 'valid', ''),
(81, 79, '7941_736116f121.png', 'valid', ''),
(82, 80, '8041_c447752ad8.png', 'valid', ''),
(83, 81, '8141_ae8e987486.png', 'valid', ''),
(84, 82, '8241_fda80fa18d.png', 'valid', ''),
(85, 83, '8341_bc98b15140.png', 'valid', ''),
(86, 84, '8453_20b1ce0d6f.png', 'new', ''),
(87, 85, '8553_2656850d21.png', 'new', ''),
(88, 86, '8653_c2f03d5b67.png', 'valid', ''),
(89, 87, '8753_321c5c2f80.png', 'new', ''),
(90, 88, '8853_dfa8eff9e9.png', 'new', ''),
(91, 89, '8953_574f8be9ea.png', 'new', ''),
(92, 90, '9053_a6002e4414.png', 'new', ''),
(93, 91, '9153_c4478e768c.png', 'new', ''),
(94, 92, '9253_3eb6fb7d7d.png', 'new', ''),
(95, 93, '9353_3e969bb19f.png', 'new', ''),
(96, 94, '9453_895b02e8d5.png', 'new', ''),
(97, 95, '9553_9b8b9b9f83.png', 'new', ''),
(98, 96, '9653_2226fe94f7.png', 'new', ''),
(99, 97, '9753_fd6366e902.png', 'new', ''),
(100, 98, '9853_0f35a10727.png', 'new', ''),
(101, 99, '9953_69674e5b6c.png', 'new', ''),
(102, 100, '10053_4baba3bea7.png', 'new', ''),
(103, 101, '10153_ab9462a9ec.png', 'new', ''),
(104, 102, '10253_3ff8152ddc.png', 'new', ''),
(105, 103, '10353_1d7808ecbc.png', 'new', ''),
(106, 104, '10453_dcaa22aa0c.png', 'new', ''),
(107, 105, '10553_b8e851dd7d.png', 'new', ''),
(108, 106, '10653_1ab188a254.png', 'new', ''),
(109, 107, '10753_575672b110.png', 'new', ''),
(110, 108, '10853_df53f34cab.png', 'new', ''),
(111, 109, '10953_bdf279d71d.png', 'new', ''),
(112, 110, '11053_7bc044515f.png', 'new', ''),
(113, 111, '11153_312eac828a.png', 'new', ''),
(114, 112, '11253_a35bae2bf9.png', 'new', ''),
(115, 113, '11353_5c0277545e.png', 'new', ''),
(116, 114, '11453_484df19888.png', 'new', ''),
(117, 115, '11553_f2198d65bf.png', 'invalid', 'gambar tidak valid'),
(118, 116, '11653_bb03d39eb1.png', 'valid', ''),
(119, 117, '11753_39a89ef3ea.png', 'valid', ''),
(120, 118, '11853_2f6e2abe69.png', 'valid', ''),
(121, 119, '11953_e97853fff3.png', 'valid', ''),
(122, 120, '12053_f3789e6061.png', 'valid', ''),
(123, 121, '12153_de4796126c.png', 'new', ''),
(124, 122, '12253_cc08b1345f.png', 'new', ''),
(125, 123, '12353_c4bd7ce28b.png', 'new', ''),
(126, 124, '12453_0b0968ce54.png', 'new', ''),
(127, 125, '12553_a68be35fb4.png', 'new', ''),
(128, 126, '12653_894ffab9bf.png', 'new', ''),
(129, 127, '12753_976f679212.png', 'new', ''),
(130, 128, '12853_2733cab2b8.png', 'new', ''),
(131, 129, '12953_0e68aa108a.png', 'valid', ''),
(132, 130, '13053_fef6d0a568.png', 'new', ''),
(133, 131, '13153_5d7ac7bb94.png', 'valid', ''),
(134, 132, '13253_e98e101d58.png', 'valid', ''),
(135, 133, '13353_f096e5f84a.png', 'new', ''),
(136, 134, '13453_985955e949.png', 'new', ''),
(137, 135, '13553_516c04a113.png', 'new', ''),
(138, 136, '13653_ffa5a3bec9.png', 'new', ''),
(139, 137, '13753_61be86a023.png', 'new', ''),
(140, 138, '13853_d94122457c.png', 'new', ''),
(141, 139, '13953_3e55e4c203.png', 'new', ''),
(142, 140, '14053_0e6aa1bd6e.png', 'new', ''),
(143, 141, '14153_94f0c32f18.png', 'new', ''),
(144, 142, '14253_c05d37063d.png', 'new', ''),
(145, 143, '14353_3bd20b41d9.png', 'new', ''),
(146, 144, '14453_b14b28d653.png', 'new', ''),
(147, 145, '14553_3a9301f33e.png', 'new', ''),
(148, 146, '14653_aab7f621df.png', 'new', ''),
(149, 147, '14753_c5aa21fe9f.png', 'new', ''),
(150, 148, '14852_fb7010cbee.png', 'valid', ''),
(152, 150, '14941_5a2cdd323e.jpeg', 'valid', ''),
(153, 151, '15141_0ea8fb8cdb.jpeg', 'valid', ''),
(154, 152, '15241_7f14f90d1e.jpeg', 'valid', ''),
(155, 153, '15341_547d06418d.jpeg', 'invalid', 'gambar tidak valid'),
(156, 154, '15441_a82766b880.jpeg', 'valid', '');

-- --------------------------------------------------------

--
-- Table structure for table `dsn`
--

CREATE TABLE IF NOT EXISTS `dsn` (
  `iduser` int(5) NOT NULL,
  `iddsn` int(11) NOT NULL AUTO_INCREMENT,
  `idprodi` int(11) NOT NULL,
  `nip` varchar(19) NOT NULL,
  `karpeg` varchar(19) NOT NULL,
  `gelard` varchar(255) NOT NULL,
  `namad` varchar(50) NOT NULL,
  `namab` varchar(100) NOT NULL,
  `gelarb` varchar(255) NOT NULL,
  `jk` enum('L','P') NOT NULL,
  `agama` enum('islam','kristen','katholik','hindu','budha') NOT NULL,
  `tl` varchar(50) NOT NULL,
  `tgll` date NOT NULL,
  `baru` int(11) NOT NULL,
  `sisaStatus` enum('no','invalid','valid') NOT NULL,
  PRIMARY KEY (`iddsn`),
  KEY `iduser` (`iduser`),
  KEY `idjur` (`idprodi`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=38 ;

--
-- Dumping data for table `dsn`
--

INSERT INTO `dsn` (`iduser`, `iddsn`, `idprodi`, `nip`, `karpeg`, `gelard`, `namad`, `namab`, `gelarb`, `jk`, `agama`, `tl`, `tgll`, `baru`, `sisaStatus`) VALUES
(30, 21, 1, '197508202008121003', 'P.193125', '', 'subuh', 'isnur haryudo', 'ST. MT', 'L', 'islam', 'Lumajang', '1975-08-20', 0, 'invalid'),
(35, 24, 1, '197909022003122001', 'L.175036', '', 'lilik', 'anifah', 'ST. MT', 'P', 'islam', 'Gresik', '1979-09-02', 0, 'invalid'),
(36, 25, 1, '197706252006041003', 'M.243524', '', 'm. syarifuddien', 'zuhrie', 'S.Pd, MT', 'L', 'islam', 'Denpasar', '1977-06-25', 0, 'no'),
(40, 29, 1, '197908062006041001', 'M.243941', '', 'agus', 'prihanto', 'ST M.Kom', 'L', 'islam', 'Lamongan', '1979-08-06', 0, 'invalid'),
(41, 30, 1, '197912062008011011', 'P.266185', '', 'dedy', 'rahman prehanto', 'S.Kom', 'L', 'islam', 'Jombang', '1979-12-06', 0, 'invalid'),
(42, 31, 1, '197701102008121003', 'P.270050', '', 'asmunin', '', 'S.Kom', 'L', 'islam', 'Jombang', '1977-01-10', 0, 'no'),
(43, 32, 1, '197810272008121002', 'P.381058', '', 'andi', 'iwan nurhidayat', 'S.Kom, M.Kom', 'L', 'islam', 'Negara', '1978-10-27', 0, 'no'),
(44, 33, 1, '198003262008121001', 'P.266188', '', 'IGL', 'eka prasmana', 'S.Kom, MM', 'L', 'hindu', 'Surabaya', '1980-03-26', 0, 'no'),
(45, 34, 1, '198211022008121001', 'P.266184', '', 'salamun', 'rohman nudin', 'S.Kom, M.Kom', 'L', 'islam', 'Jombang', '1982-11-02', 0, 'invalid'),
(52, 35, 2, '197908132006042001', 'M.243523', 'Dr', 'erina', 'rahmadyanti', 'S.T., M.T.', 'P', 'islam', 'surabaya', '1979-08-13', 0, 'valid'),
(53, 36, 1, '197508172008012017', 'N.510954', '', 'raden roro', 'hapsari peni agustin tjahyaningtijas', 'S.Si., MT.', 'P', 'islam', 'sidoarjo', '1975-08-17', 0, 'no'),
(54, 37, 2, '197006221997032002', '', '', 'Puput', 'Wanarti', 'ST., MT.', 'P', 'islam', 'nganjuk', '1970-06-22', 0, 'no');

-- --------------------------------------------------------

--
-- Table structure for table `dtk`
--

CREATE TABLE IF NOT EXISTS `dtk` (
  `iddtk` int(5) NOT NULL AUTO_INCREMENT,
  `idhistjab` int(11) NOT NULL,
  `idkeg` int(5) NOT NULL,
  `status` enum('new','checked','valid','done') NOT NULL DEFAULT 'new',
  `tglinput` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `isGroup` enum('n','y') DEFAULT NULL,
  `isLeader` enum('n','y') DEFAULT NULL,
  `jumAnggota` int(2) NOT NULL,
  `sks` int(2) NOT NULL,
  `tglKeg` int(2) NOT NULL,
  `blnKeg` int(2) NOT NULL,
  `thnKeg` int(4) NOT NULL,
  `waktu` varchar(50) NOT NULL,
  `tempat` varchar(50) NOT NULL,
  `jabatan` varchar(50) NOT NULL,
  `ket` text NOT NULL,
  `sisa` float NOT NULL,
  PRIMARY KEY (`iddtk`),
  KEY `idkeg` (`idkeg`),
  KEY `idhistjab` (`idhistjab`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=155 ;

--
-- Dumping data for table `dtk`
--

INSERT INTO `dtk` (`iddtk`, `idhistjab`, `idkeg`, `status`, `tglinput`, `isGroup`, `isLeader`, `jumAnggota`, `sks`, `tglKeg`, `blnKeg`, `thnKeg`, `waktu`, `tempat`, `jabatan`, `ket`, `sisa`) VALUES
(49, 5, 44, 'valid', '2014-06-02 04:37:15', 'y', 'n', 2, 0, 0, 0, 0, '', '', '', 'membimbing thesis 2 mahasiswa PTE 2014', 0),
(50, 5, 82, 'valid', '2014-06-02 04:37:16', NULL, NULL, 0, 0, 0, 0, 0, 'op', 'u', 'po', 'jj', 0),
(51, 5, 63, 'valid', '2014-06-02 04:37:18', NULL, NULL, 0, 0, 0, 0, 0, 'semester ganjil', 'tempat baru', '', 'nama detail kegiatan', 0),
(52, 5, 82, 'valid', '2014-06-02 04:37:19', NULL, NULL, 0, 0, 0, 0, 0, 'waktu x', 'tempat x', 'tingkat x', '[enujag  x', 0),
(53, 5, 13, 'valid', '2014-06-02 04:37:26', NULL, NULL, 3, 2, 0, 0, 0, '3', '3', '', '3', 0),
(54, 5, 13, 'valid', '2014-07-07 16:10:51', NULL, NULL, 2, 11, 0, 0, 0, 'semester pendek', 'unesa', '', 'ngajar macul mahasiswa pertanian', 0),
(55, 18, 13, 'valid', '2014-07-07 16:10:16', NULL, NULL, 2, 2, 0, 0, 0, 'sem. gsl. 2010/2011', 'S1/PTB/2007 A', '', 'Instalasi Bahan Bangunan', 0),
(56, 18, 13, 'valid', '2014-06-25 12:00:06', NULL, NULL, 2, 2, 0, 0, 0, 'sem. gsl. 2010/2011', 'S1/PTB/2007 B', '', 'Instalasi Bahan Bangunan', 0),
(57, 18, 13, 'valid', '2014-06-24 04:39:05', NULL, NULL, 2, 2, 0, 0, 0, 'sem. gsl. 2010/2011', 'S1/PTB/2007 A', '', 'Bahasa Inggris', 0),
(58, 18, 45, 'checked', '2014-07-04 09:49:43', 'y', 'y', 0, 0, 0, 0, 0, '', '', '', 'Molecular Exploration of Biomarkers as Early Warning System of Aquatic Pollution . (Karya Ilmiah yang dimuat dalaam Journal of Environment and Earth Science, Vol. 2 No. 8, 2012 , Hal, 80 s.d  89, ISSN : 2224-3216). Sebagai Penulis.', 0),
(59, 18, 61, 'valid', '2014-06-25 13:19:33', NULL, NULL, 0, 0, 0, 0, 0, '2012', 'Ponorogo', '', 'IbM Industri Jepang Teguh Raharjo di Kabupaten Ponorogo Dalam Upaya Meminimalisasi Limbah Melalui Pemberdayaan Masyarakat Di sekitar Lokasi Produksi', 0),
(60, 18, 75, 'valid', '2014-06-25 13:19:27', NULL, NULL, 0, 0, 0, 0, 0, '18-05-2011', 'FT UNESA', 'Sie Pengawas', 'Panitia Ujian Sumatif Semester Genap 2010/2011', 0),
(61, 18, 78, 'valid', '2014-06-24 18:51:37', NULL, NULL, 0, 0, 0, 0, 0, '04-02-2010', 'Pasuruan', 'Ketua Pelaksana', 'Panitia Pelaksana Peningkatan INdonesia-Managing Higher Education For Relevance And Efficiency (IMHERE) Project Batch III UNESA  tahun 2010-2011', 0),
(62, 18, 73, 'valid', '2014-07-04 13:53:45', NULL, NULL, 0, 0, 0, 0, 0, '2012', 'Ponorogo', '', 'model pengemabangan AgroIndustri UMKM Makanan Minuman Melalui Strategi Ekoefisiensi Produksi Bersih Untuk Percepatan Pembangunan Ekonomi Kabupaten Pasuuan Sebagai Kawasan Agropolitan KOridor Ekonomi Jawa', 0),
(63, 18, 29, 'valid', '2014-06-25 12:08:00', 'n', 'n', 0, 0, 0, 0, 0, 'Sem. Gsl. 2010/2011', 'D3/TS/2010', '', 'Dosen Penasehat 11 Mahasiswa', 0),
(64, 18, 45, 'checked', '2014-07-04 09:50:02', 'y', 'n', 1, 0, 0, 0, 0, '', '', '', 'operating conditions optimization on indonesia batik dyes wastwater treatment by fenton oxidation and separation using ultrafiltration membrane', 0),
(65, 18, 48, 'checked', '2014-07-04 09:49:56', 'n', 'n', 0, 0, 0, 0, 0, '', '', '', 'Domestic wastewater treatment using constructed wetland as a development strategy of sustainable residental', 0),
(66, 13, 13, 'valid', '2014-07-08 06:38:07', NULL, NULL, 2, 3, 0, 0, 0, 'sem.gsl.2010/2011', 'S1/PTE/2010 Kelas B', '', 'fisika', 0),
(67, 13, 18, 'valid', '2014-07-08 06:36:59', 'n', 'n', 0, 0, 0, 0, 0, 'sem.gnp. 2010/2011', 'D3/2007', '', 'pembimbing praktek industri 1 mahasiswa', 0),
(68, 13, 29, 'valid', '2014-07-08 06:37:04', 'n', 'n', 0, 0, 0, 0, 0, 'Sem.Gnp. 2010/2011', 'S1/2008,2009,2010', '', 'Dosen Penasehat 34 mahasiswa', 0),
(69, 13, 29, 'valid', '2014-07-08 06:37:09', 'n', 'n', 0, 0, 0, 0, 0, 'Sem.Gsl. 2010/2011', 'S1/2008,2009,2010', '', 'Dosen penasehat 34 mahasiswa', 0),
(70, 13, 29, 'valid', '2014-07-08 06:38:03', 'n', 'n', 0, 0, 0, 0, 0, 'Sem.Gsl. 2011/2012', 'S1/2008,2009,2010,2011', '', 'Dosen penasehat 44 mahasiswa', 0),
(71, 13, 29, 'valid', '2014-07-08 06:37:21', 'n', 'n', 0, 0, 0, 0, 0, 'Sem.Gnp. 2011/2012', 'S1/2008,2009,2010,2011', '', 'Dosen penasehat 44 mahasiswa', 0),
(72, 13, 29, 'valid', '2014-07-08 06:37:34', 'n', 'n', 0, 0, 0, 0, 0, 'Sem.Gsl. 2012/2013', 'S1/2008,2009,2010,2011', '', 'Dosen penasehat 44 mahasiswa', 0),
(73, 13, 18, 'valid', '2014-07-08 06:37:46', 'n', 'n', 0, 0, 0, 0, 0, 'Sem.Gnp. 2011/2012', 'S1/2008', '', 'membimbing praktek industri 2 mahasiswa', 0),
(74, 13, 13, 'valid', '2014-07-08 06:37:58', NULL, NULL, 1, 2, 0, 0, 0, 'Sem.Gsl.2010/2011', 'S1/Elkom 3/2008', '', 'saluran transmisi', 0),
(75, 13, 13, 'valid', '2014-07-08 06:37:27', NULL, NULL, 2, 2, 0, 0, 0, 'Sem.Gsl. 2011/2012', 'S1/Elkom 2/2009', '', 'praktikum 3 telekomunikasi 1', 0),
(76, 13, 13, 'valid', '2014-07-08 06:37:53', NULL, NULL, 2, 2, 0, 0, 0, 'Sem.Gsl. 2011/2012', 'S1/Elkom 1/2009', '', 'Praktikum 3 telekomunikasi 1', 0),
(77, 13, 13, 'valid', '2014-07-08 06:37:41', NULL, NULL, 1, 2, 0, 0, 0, 'Sem.Gsl. 2011/2012', 'S1/Elkom 3/2009', '', 'Praktikum 3 telekomunikasi 1', 0),
(78, 13, 49, 'valid', '2014-07-08 06:25:13', 'y', 'n', 1, 0, 0, 0, 0, '', '', '', 'pengaruh teknik pembelajaran quantumm teaching terhadap hasil belajar siswa yang memiliki motivasi berprestasi berbeda pada standart kompetensi menerapkan sistem mikroprosessor', 0),
(79, 13, 47, 'valid', '2014-07-07 17:47:03', 'n', 'n', 0, 0, 0, 0, 0, '', '', '', 'kinerja synchronous optical CDMA menggunakan kode prima', 0),
(80, 13, 68, 'valid', '2014-07-08 06:25:26', NULL, NULL, 0, 0, 0, 0, 0, '02-12-2012', 'mojokerto', '', 'instruktur pelatihan pembuatan web blog bagi guru-guru di SMA negeri 1 trawas', 0),
(81, 13, 68, 'valid', '2014-07-08 06:25:21', NULL, NULL, 0, 0, 0, 0, 0, '2012', 'surabaya', '', 'IbM penerapan alat pendeteksi kebocoran gas LPG untuk kelompok usaha bakso di surabaya', 0),
(82, 13, 86, 'valid', '2014-07-08 06:25:56', NULL, NULL, 0, 0, 0, 0, 0, '02-01-2012', 'FT unesa', 'sie jadwal', 'panitia ujian sumatif semester gasal 2011/2012 kelas A', 0),
(83, 13, 86, 'valid', '2014-07-08 06:25:34', NULL, NULL, 0, 0, 0, 0, 0, '02-01-2012', 'FT unesa', 'sie jadwal', 'panitia ujian sumatif semester gasal 2011/2012 kelas B', 0),
(84, 19, 12, 'new', '2014-06-09 04:49:54', NULL, NULL, 0, 0, 0, 0, 0, '2 nov s.d 29 des 2011', 'unesa', '', 'kegiatan pelatihan PEKERTI dan AA', 0),
(85, 19, 13, 'new', '2014-06-09 04:51:47', NULL, NULL, 1, 3, 0, 0, 0, 'Sem.gsl. 2010/2011', 'S1/PTE/2010 Kelas B', '', 'Fisika 1', 0),
(86, 19, 13, 'valid', '2014-07-04 07:24:20', NULL, NULL, 2, 3, 0, 0, 0, 'Sem.Gsl. 2010/2011', 'S1/PTE/2010 Kelas C', '', 'Fisika 1', 0),
(87, 19, 13, 'new', '2014-06-09 04:54:27', NULL, NULL, 1, 2, 0, 0, 0, 'Sem.Gsl. 2010/2011', 'S1/Elkom 3/2008', '', 'saluran transmisi', 0),
(88, 19, 13, 'new', '2014-06-09 04:56:56', NULL, NULL, 1, 2, 0, 0, 0, 'Sem.Gsl 2010/2011', 'D3/TL/2010', '', 'Fisika 1', 0),
(89, 19, 13, 'new', '2014-06-09 05:24:06', NULL, NULL, 2, 2, 0, 0, 0, 'Sem.Gsl. 2010/2011', 'D3/TL/2010', '', 'Fisika 1', 0),
(90, 19, 13, 'new', '2014-06-09 05:25:43', NULL, NULL, 2, 3, 0, 0, 0, 'Sem.Gnp.2010/2011', 'S1/PTE/2010 Kelas A', '', 'Fisika 2', 0),
(91, 19, 13, 'new', '2014-06-09 05:29:31', NULL, NULL, 2, 3, 0, 0, 0, 'Sem.Gnp. 2010/2011', 'S1/PTE/2010 Kelas B', '', 'fisika 2', 0),
(92, 19, 13, 'new', '2014-06-09 05:37:23', NULL, NULL, 1, 2, 0, 0, 0, 'Sem. Gsl. 2011/2012', 'S1/Elkom 3/2009', '', 'saluran transmisi', 0),
(93, 19, 13, 'new', '2014-06-09 05:39:01', NULL, NULL, 1, 3, 0, 0, 0, 'Sem.Gsl. 2011/2012', 'S1/PTE/2011 Kelas B', '', 'fisika 1', 0),
(94, 19, 13, 'new', '2014-06-09 05:42:39', NULL, NULL, 1, 2, 0, 0, 0, 'Sem.Gsl. 2011/2012', 'D3/MI Reg 1/2009', '', 'bahasa inggris 2', 0),
(95, 19, 13, 'new', '2014-06-09 05:43:48', NULL, NULL, 1, 2, 0, 0, 0, 'Sem.Gsl. 2011/2012', 'D3/MI Reg 2/2009', '', 'bahasa inggris 2', 0),
(96, 19, 13, 'new', '2014-06-09 05:45:02', NULL, NULL, 1, 2, 0, 0, 0, 'Sem.Gsl. 2011/2012', 'D3/MI non Reg 1/2009', '', 'bahasa inggris 2', 0),
(97, 19, 13, 'new', '2014-06-09 05:45:59', NULL, NULL, 1, 2, 0, 0, 0, 'Sem.Gsl. 2011/2012', 'D3/MI non Reg 2/2009', '', 'bahasa inggris 2', 0),
(98, 19, 13, 'new', '2014-06-09 05:47:17', NULL, NULL, 2, 2, 0, 0, 0, 'Sem.Gsl. 2011/2012', 'D3/MI kelas A/2011', '', 'bahasa inggris 1', 0),
(99, 19, 13, 'new', '2014-06-09 05:48:35', NULL, NULL, 2, 2, 0, 0, 0, 'Sem.Gsl. 2011/2012', 'D3/MI kelas B/2011', '', 'bahasa inggris 1', 0),
(100, 19, 13, 'new', '2014-06-09 05:52:44', NULL, NULL, 2, 2, 0, 0, 0, 'Sem.Gsl. 2011/2012', 'S1/Elkom 1/2009', '', 'praktikum 3 telekomunikasi 1', 0),
(101, 19, 13, 'new', '2014-06-09 05:53:39', NULL, NULL, 2, 2, 0, 0, 0, 'Sem.Gsl. 2011/2012', 'S1/Elkom 2/2009', '', 'praktikum 3 telekomunikasi 1', 0),
(102, 19, 13, 'new', '2014-06-09 05:54:48', NULL, NULL, 2, 2, 0, 0, 0, 'Sem.Gsl. 2011/2012', 'S1/Elkom 3/2009', '', 'praktikum 3 telekomunikasi 1', 0),
(103, 19, 13, 'new', '2014-06-09 05:57:16', NULL, NULL, 1, 3, 0, 0, 0, 'Sem.Gnp. 2011/2012', 'S1/PTE Kelas C/2011', '', 'fisika 2', 0),
(104, 19, 13, 'new', '2014-06-09 05:59:02', NULL, NULL, 1, 2, 0, 0, 0, 'Sem.Gnp. 2011/2012', 'D3/MI Kelas A/2011', '', 'bahasa inggris 2', 0),
(105, 19, 13, 'new', '2014-06-09 06:01:57', NULL, NULL, 1, 2, 0, 0, 0, 'Sem.Gnp. 2011/2012', 'D3/MI Reg 1/2009', '', 'bahasa inggris 3', 0),
(106, 19, 13, 'new', '2014-06-09 06:03:17', NULL, NULL, 1, 2, 0, 0, 0, 'Sem.Gnp. 2011/2012', 'D3/MI Ulang A/2010', '', 'bahasa inggris 2', 0),
(107, 19, 13, 'new', '2014-06-09 06:05:47', NULL, NULL, 2, 3, 0, 0, 0, 'Sem.Gsl. 2012/2013', 'S1/Elkom 1/2010', '', 'teknik laser dan fiber optik', 0),
(108, 19, 13, 'new', '2014-07-08 09:22:32', NULL, NULL, 2, 3, 0, 0, 0, 'Sem.Gsl. 2012/2013', 'S1/Elkom 2/2010', '', 'teknik laser dan fiber optik', 0),
(109, 19, 13, 'new', '2014-06-09 11:47:08', NULL, NULL, 2, 3, 0, 0, 0, 'Sem.Gsl. 2012/2013', 'S1/Elkom 3/2010', '', 'teknik laser dan fiber optik', 0),
(110, 19, 13, 'new', '2014-06-09 11:48:37', NULL, NULL, 1, 3, 0, 0, 0, 'Sem.Gnp. 2012/2013', 'S1/PTE Kelas B/2011', '', 'fisika 1', 0),
(111, 19, 13, 'new', '2014-06-09 11:50:00', NULL, NULL, 1, 2, 0, 0, 0, 'Sem.Gnp. 2012/2013', 'D3/TL/2012', '', 'fisika teknik 1', 0),
(112, 19, 13, 'new', '2014-06-09 11:50:55', NULL, NULL, 1, 2, 0, 0, 0, 'Sem.Gnp. 2012/2013', 'D3/MI Kelas C/2012', '', 'bahasa inggris 2', 0),
(113, 19, 18, 'new', '2014-06-09 12:52:01', 'n', 'n', 0, 0, 0, 0, 0, 'Sem.Gsl.2010/2011', 'D3/2007', '', 'membimbing praktek industri 1 mahasiswa', 0),
(114, 19, 18, 'new', '2014-06-09 12:51:21', 'n', 'n', 0, 0, 0, 0, 0, 'Sem.Gnp.2010/2011', 'D3/2007', '', 'membimbing praktek industri 1 mahasiswa', 0),
(115, 19, 18, 'checked', '2014-07-08 10:40:58', 'n', 'n', 0, 0, 0, 0, 0, 'Sem.Gnp.2011/2012', 'S1/2008', '', 'pembimbing praktek industri 2 mahasiswa', 0),
(116, 19, 29, 'new', '2014-07-08 09:22:39', 'n', 'n', 0, 0, 0, 0, 0, 'Sem.Gnp. 2010/2011', 'S1/2008,2009,2010', '', 'dosen penasehat 34 mahasiswa', 0),
(117, 19, 29, 'valid', '2014-06-10 01:11:23', 'n', 'n', 0, 0, 0, 0, 0, 'Sem.Gsl. 2010/2011', 'S1/2008,2009,2010', '', 'dosen penasehat 34 mahasiswa', 0),
(118, 19, 29, 'valid', '2014-06-10 01:08:02', 'n', 'n', 0, 0, 0, 0, 0, 'Sem.Gsl. 2011/2012', 'S1/2008,2009,2010,2011', '', 'dosen penasehat 44 mahasiswa', 0),
(119, 19, 29, 'valid', '2014-07-04 07:24:48', 'n', 'n', 0, 0, 0, 0, 0, 'Sem.Gnp. 2011/2012', 'S1/2008,2009,2010,2011', '', 'dosen penasehat 44 mahasiswa', 0),
(120, 19, 29, 'valid', '2014-06-10 01:08:10', 'n', 'n', 0, 0, 0, 0, 0, 'Sem.Gsl. 2012/2013', 'S1/2008,2009,2010,2011', '', 'dosen penasehat 44 mahasiswa', 0),
(121, 19, 47, 'new', '2014-06-09 13:04:30', 'n', 'n', 0, 0, 0, 0, 0, '', '', '', 'kinerja synchronous optical CDMA menggunakan kode prima', 0),
(122, 19, 47, 'new', '2014-06-09 13:05:37', 'y', 'n', 1, 0, 0, 0, 0, '', '', '', 'pemanfaatan subband LL sebagai watermark untuk deteksi kerusakan dan pemuliha citra digital', 0),
(123, 19, 49, 'new', '2014-06-09 13:07:18', 'y', 'n', 1, 0, 0, 0, 0, '', '', '', 'pengaruh teknik pembelajaran quantum teaching terhadap hasil belajar siswa yang memiliki motivasi berprestasi berbeda  pada standart kompetensi menerapkan siste mikroprosesor', 0),
(124, 19, 53, 'new', '2014-06-09 13:15:00', 'y', 'n', 1, 0, 0, 0, 0, '', '', '', 'penerapan metode umpan balik adaptif pada MAC 802.16 untuk meningkatkan kinerja sistim komunikasi wimax', 0),
(125, 19, 53, 'new', '2014-06-09 13:16:43', 'y', 'n', 1, 0, 0, 0, 0, '', '', '', 'penggunaan teknik watermarking untuk deteksi kerusakan dan pemulihan citra digital', 0),
(126, 19, 53, 'new', '2014-06-09 13:18:19', 'y', 'n', 1, 0, 0, 0, 0, '', '', '', 'penggunaan teknik watermarking untuk deteksi kerusakan dan pemulihan citra digital 2', 0),
(127, 19, 69, 'new', '2014-07-08 09:23:00', NULL, NULL, 0, 0, 0, 0, 0, '02-12-2012', 'mojokerto', '', 'instruktur pelatihan pembuatan web blog bagi guru-guru di SMA negeri 1 trawas', 0),
(128, 19, 68, 'new', '2014-06-09 13:23:07', NULL, NULL, 0, 0, 0, 0, 0, '2012', 'surabaya', '', 'IbM penerapan alat pendeteksi kebocoran gas LPG untuk kelompok usaha bakso', 0),
(129, 19, 86, 'valid', '2014-06-10 01:12:20', NULL, NULL, 0, 0, 0, 0, 0, '02-01-2012', 'FT Unesa', 'sie jadwal', 'panitia ujian sumatif semester gasal 2011/2012 kelas A', 0),
(130, 19, 86, 'new', '2014-07-08 09:23:34', NULL, NULL, 0, 0, 0, 0, 0, '02-01-2012', 'FT Unesa', 'sie jadwal', 'panitia ujian sumatif semester gasal 2011/2012 kelas B', 0),
(131, 19, 86, 'valid', '2014-06-10 01:12:30', NULL, NULL, 0, 0, 0, 0, 0, '28-05-2012', 'FT Unesa', 'sie jadwal', 'panitia ujian sumatif semester genap 2011/2012 kelas A', 0),
(132, 19, 86, 'new', '2014-07-08 09:23:27', NULL, NULL, 0, 0, 0, 0, 0, '28-05-2012', 'FT Unesa', 'sie jadwal', 'panitia ujian sumatif semester genap 2011/2012 kelas B', 0),
(133, 19, 86, 'new', '2014-07-08 09:23:15', NULL, NULL, 0, 0, 0, 0, 0, '30-05-2013', 'FT Unesa', 'sie jadwal', 'panitia ujian sumatif semester genap 2012/2013', 0),
(134, 19, 86, 'new', '2014-07-08 09:23:09', NULL, NULL, 0, 0, 0, 0, 0, '01-06-2012', 'surabaya', 'penanggungjawab', 'panitia lokal surabaya SNMPTN tahun 2012', 0),
(135, 19, 75, 'new', '2014-06-09 13:36:12', NULL, NULL, 0, 0, 0, 0, 0, '13-10-2011', 'FT Unesa', 'anggota', 'tim evaluasi diri akreditasi program studi S1 PTE', 0),
(136, 19, 75, 'new', '2014-06-09 13:38:43', NULL, NULL, 0, 0, 0, 0, 0, '30-31 okt 2012', 'FT Unesa', 'anggota', 'tim workshop III &quot;review dan finalisasi pengembangan kurikulum berbasis KBK mengacu KKNI D3TL&quot;', 0),
(137, 19, 75, 'new', '2014-06-09 13:39:52', NULL, NULL, 0, 0, 0, 0, 0, 'juli 2012', 'FT Unesa', 'anggota', 'pembuatan hand out teaching grant jurusan TE', 0),
(138, 19, 75, 'new', '2014-06-09 13:41:27', NULL, NULL, 0, 0, 0, 0, 0, '2012', 'FT Unesa', 'anggota', 'penyusun evaluasi diri prodi D3TL jurusan listrik', 0),
(139, 19, 75, 'new', '2014-06-09 13:43:19', NULL, NULL, 0, 0, 0, 0, 0, '130-06-2011', 'FT Unesa', 'anggota', 'tim penyusun kurikulum program studi S1 PTE konsentrasi elektronik', 0),
(140, 19, 75, 'new', '2014-06-09 13:44:30', NULL, NULL, 0, 0, 0, 0, 0, '13-06-2011', 'FT Unesa', 'anggota', 'tim penyusun borang akreditasi program studi S1 PTE', 0),
(141, 19, 75, 'new', '2014-06-09 13:46:09', NULL, NULL, 0, 0, 0, 0, 0, '12-08-2011', 'FT Unesa', 'anggota', 'tim penyusun diskripsi mata kuliah program studi S1 PTE, D3 TL dan D3 MI', 0),
(142, 19, 90, 'new', '2014-06-09 13:49:47', NULL, NULL, 0, 0, 0, 0, 0, '15 des s.d. 9 mar 2012', 'surabaya', 'peserta', 'kursus bahasa inggris', 0),
(143, 19, 92, 'new', '2014-06-09 13:58:48', NULL, NULL, 0, 0, 0, 0, 0, '19-29 April 2010', 'ITS', 'peserta', 'pelatihan linux OS, Apache, PHP dan postgreSQL', 0),
(144, 19, 92, 'new', '2014-06-09 14:00:15', NULL, NULL, 0, 0, 0, 0, 0, '15-19 okt 2012', 'FT Unesa', 'peserta', 'pelatihan asesor kompetensi', 0),
(145, 19, 92, 'new', '2014-07-08 09:23:47', NULL, NULL, 0, 0, 0, 0, 0, '20-22 jan 2011', 'trawas mojokerto', 'peserta', 'workshop pemanfaatan teknologi informasi dan komunikasi untuk peningkatan kualitas PBM', 0),
(146, 19, 92, 'new', '2014-06-09 14:04:03', NULL, NULL, 0, 0, 0, 0, 0, '19-12-2011', 'FT Unesa', 'peserta', 'seminar nasional &quot;engineering and technology education&quot;', 0),
(147, 19, 92, 'new', '2014-07-08 09:23:40', NULL, NULL, 0, 0, 0, 0, 0, '31 okt s.d. 1 nov 2011', 'unesa', 'peserta', 'workshop E-learning pemanfaatan internet sebagai sumber belajar mahasiswa', 0),
(148, 18, 60, 'valid', '2014-07-07 16:10:23', 'n', 'n', 0, 0, 0, 0, 0, '', '', '', 'tes doang', 0),
(150, 13, 42, 'valid', '2014-07-08 06:52:38', 'y', 'y', 0, 0, 0, 0, 0, '2012', 'unesa', '', 'teknik pembelajaran menggunakan kelompok', 0),
(151, 13, 42, 'valid', '2014-07-08 06:52:19', 'n', 'n', 0, 0, 0, 0, 0, '2013', 'unesa', '', 'teknik pembelajaran', 0),
(152, 13, 41, 'valid', '2014-07-08 06:52:13', 'n', 'n', 0, 0, 0, 0, 0, '2013', 'unesa', '', 'pencangkokan', 0),
(153, 13, 18, 'checked', '2014-07-08 21:46:10', 'n', 'n', 0, 0, 0, 0, 0, '2012', 'unesa', '', 'kkn', 0),
(154, 13, 32, 'valid', '2014-07-08 06:57:57', 'n', 'n', 0, 0, 0, 0, 0, '2014', 'unesa', '', 'mengembangkan alat bantu mengajar', 0);

-- --------------------------------------------------------

--
-- Table structure for table `dtksisa`
--

CREATE TABLE IF NOT EXISTS `dtksisa` (
  `iddtksisa` int(11) NOT NULL AUTO_INCREMENT,
  `idhistjab` int(11) NOT NULL,
  `idkatkeg` int(11) NOT NULL,
  `poin` float NOT NULL,
  `remain` float NOT NULL,
  PRIMARY KEY (`iddtksisa`),
  KEY `idkatkeg` (`idkatkeg`),
  KEY `idhistjab` (`idhistjab`),
  KEY `idhistjab_2` (`idhistjab`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=54 ;

--
-- Dumping data for table `dtksisa`
--

INSERT INTO `dtksisa` (`iddtksisa`, `idhistjab`, `idkatkeg`, `poin`, `remain`) VALUES
(46, 5, 1, 11, 11),
(47, 5, 3, 11, 11),
(48, 5, 2, 8, 8),
(49, 18, 1, 64.8, 64.8),
(50, 18, 2, 12.1, 12.1),
(51, 18, 3, 0, 0),
(52, 18, 4, 0, 0),
(53, 5, 4, 3, 3);

-- --------------------------------------------------------

--
-- Table structure for table `fak`
--

CREATE TABLE IF NOT EXISTS `fak` (
  `idfak` int(11) NOT NULL AUTO_INCREMENT,
  `fak` varchar(50) NOT NULL,
  PRIMARY KEY (`idfak`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `fak`
--

INSERT INTO `fak` (`idfak`, `fak`) VALUES
(1, 'FT'),
(2, 'FMIPA'),
(3, 'FE'),
(4, 'FIS'),
(5, 'FIP'),
(6, 'FIK'),
(7, 'FBS');

-- --------------------------------------------------------

--
-- Table structure for table `gol`
--

CREATE TABLE IF NOT EXISTS `gol` (
  `id_gol` int(11) NOT NULL AUTO_INCREMENT,
  `id_jab` int(11) NOT NULL,
  `gol` varchar(255) NOT NULL,
  `pangkat` varchar(255) NOT NULL,
  `urutan` int(11) NOT NULL,
  PRIMARY KEY (`id_gol`),
  KEY `id_jab` (`id_jab`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `gol`
--

INSERT INTO `gol` (`id_gol`, `id_jab`, `gol`, `pangkat`, `urutan`) VALUES
(1, 1, 'III A', 'Penata Muda', 1),
(2, 1, 'III B', 'Penata Muda Tingkat I', 2),
(3, 2, 'III C', 'Penata', 3),
(4, 2, 'III D', 'Penata Tingkat I', 4);

-- --------------------------------------------------------

--
-- Table structure for table `histjab`
--

CREATE TABLE IF NOT EXISTS `histjab` (
  `idhistjab` int(11) NOT NULL AUTO_INCREMENT,
  `id_gol` int(11) NOT NULL,
  `id_jab` int(11) NOT NULL,
  `tglgols` date DEFAULT NULL,
  `tgljabs` date DEFAULT NULL,
  `tgltmp` date NOT NULL,
  `id_pt` int(11) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `iddsn` int(11) NOT NULL,
  `sk` varchar(30) NOT NULL,
  `id_periode` int(11) DEFAULT NULL,
  PRIMARY KEY (`idhistjab`),
  KEY `id_gol` (`id_gol`),
  KEY `id_jab` (`id_jab`),
  KEY `id_pt` (`id_pt`),
  KEY `id_pt_2` (`id_pt`),
  KEY `iddsn` (`iddsn`),
  KEY `iddsn_2` (`iddsn`),
  KEY `id_periode` (`id_periode`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

--
-- Dumping data for table `histjab`
--

INSERT INTO `histjab` (`idhistjab`, `id_gol`, `id_jab`, `tglgols`, `tgljabs`, `tgltmp`, `id_pt`, `status`, `iddsn`, `sk`, `id_periode`) VALUES
(5, 3, 2, '2011-04-01', '2010-08-01', '0000-00-00', 2, 1, 21, '', 1),
(7, 3, 2, '2012-04-01', '2009-12-01', '0000-00-00', 2, 1, 24, '', 1),
(8, 3, 2, '2012-10-01', '2010-09-01', '0000-00-00', 2, 1, 25, '', 1),
(12, 3, 1, '2014-04-01', '2013-08-01', '0000-00-00', 2, 1, 29, '', 1),
(13, 1, 1, '2010-01-01', '2007-07-01', '0000-00-00', 2, 1, 30, '', 1),
(14, 1, 1, '2010-09-01', '2007-07-01', '0000-00-00', 1, 1, 31, '', 1),
(15, 1, 1, '2010-09-01', '2007-07-01', '0000-00-00', 2, 1, 32, '', 1),
(16, 1, 1, '2010-09-01', '2007-07-01', '0000-00-00', 2, 1, 33, '', 1),
(17, 1, 1, '2010-09-01', '2007-07-01', '0000-00-00', 2, 1, 34, '', NULL),
(18, 3, 1, '2006-04-01', '2007-08-01', '0000-00-00', 3, 1, 35, '', NULL),
(19, 2, 1, '2008-01-01', '2010-06-01', '0000-00-00', 2, 1, 36, '', NULL),
(20, 4, 2, '2010-10-01', '2008-05-01', '0000-00-00', 2, 1, 37, '', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `jab`
--

CREATE TABLE IF NOT EXISTS `jab` (
  `id_jab` int(11) NOT NULL,
  `jab` varchar(255) NOT NULL,
  `urutan` int(11) NOT NULL,
  PRIMARY KEY (`id_jab`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jab`
--

INSERT INTO `jab` (`id_jab`, `jab`, `urutan`) VALUES
(1, 'asisten ahli', 1),
(2, 'lektor', 2);

-- --------------------------------------------------------

--
-- Table structure for table `jur`
--

CREATE TABLE IF NOT EXISTS `jur` (
  `idjur` int(11) NOT NULL AUTO_INCREMENT,
  `jur` varchar(100) NOT NULL,
  `idfak` int(11) NOT NULL,
  `iddsn` int(11) NOT NULL,
  PRIMARY KEY (`idjur`),
  KEY `idfak` (`idfak`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

--
-- Dumping data for table `jur`
--

INSERT INTO `jur` (`idjur`, `jur`, `idfak`, `iddsn`) VALUES
(1, 'TEKNIK ELEKTRO', 1, 37),
(2, 'TEKNIK MESIN', 1, 37),
(3, 'TEKNIK SIPIL', 1, 37),
(4, 'PKK', 1, 37),
(5, 'ILMU KEOLAHRAGAAN', 6, 37),
(6, 'PENDIDIKAN JASMANI', 6, 37),
(7, 'PENDIDIKAN KEPARIWISATAAN', 6, 37),
(8, 'PMP-KN', 4, 37),
(9, 'PENDIDIKAN GEOGRAFI', 4, 37),
(10, 'PENDIDIKAN SEJARAH', 4, 37),
(11, 'AKUNTANSI', 3, 37),
(12, 'MANAJEMEN', 3, 37),
(13, 'PEND. EKONOMI', 3, 37),
(14, 'BAHASA', 7, 37),
(15, 'SENDRATASIK', 7, 37);

-- --------------------------------------------------------

--
-- Table structure for table `katkeg`
--

CREATE TABLE IF NOT EXISTS `katkeg` (
  `idkatkeg` int(11) NOT NULL AUTO_INCREMENT,
  `cum` varchar(1) NOT NULL,
  `katkeg` varchar(50) NOT NULL,
  `subTotTgt` float NOT NULL,
  `tipe` enum('mx','mn') NOT NULL,
  PRIMARY KEY (`idkatkeg`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `katkeg`
--

INSERT INTO `katkeg` (`idkatkeg`, `cum`, `katkeg`, `subTotTgt`, `tipe`) VALUES
(1, 'A', 'Pendidikan dan Pegajaran', 40, 'mn'),
(2, 'B', 'Penelitian', 25, 'mn'),
(3, 'C', 'Pengabdian', 15, 'mx'),
(4, 'D', 'Penunjang', 20, 'mx');

-- --------------------------------------------------------

--
-- Table structure for table `kegiatan`
--

CREATE TABLE IF NOT EXISTS `kegiatan` (
  `idkeg` int(5) NOT NULL AUTO_INCREMENT,
  `id_subkatkeg` int(11) NOT NULL,
  `nakeg` varchar(200) NOT NULL,
  `poin` float NOT NULL,
  `idbatut` int(11) NOT NULL,
  `batut` varchar(100) NOT NULL,
  `bukeg` varchar(200) NOT NULL,
  PRIMARY KEY (`idkeg`),
  KEY `katkeg` (`id_subkatkeg`),
  KEY `katkeg_2` (`id_subkatkeg`),
  KEY `katkeg_3` (`id_subkatkeg`),
  KEY `katkeg_4` (`id_subkatkeg`),
  KEY `id_subkatkeg` (`id_subkatkeg`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=102 ;

--
-- Dumping data for table `kegiatan`
--

INSERT INTO `kegiatan` (`idkeg`, `id_subkatkeg`, `nakeg`, `poin`, `idbatut`, `batut`, `bukeg`) VALUES
(7, 3, 'Mengikuti pendidikan dan pelatihan lebih dari 960 jam', 15, 0, 'satu sertifikat per periode penilaian', 'STTP/Sertifikat'),
(8, 3, 'Mengikuti pendidikan dan pelatihan antara 641-960 jam', 9, 0, 'satu sertifikat per tahun', 'STTP/Sertifikat'),
(9, 3, 'Mengikuti pendidikan dan pelatihan antara 481-640 jam', 6, 0, 'satu sertifikat per tahun', 'STTP/Sertifikat'),
(10, 3, 'Mengikuti pendidikan dan pelatihan antara 161-480 jam', 3, 0, 'satu sertifikat per semester', 'STTP/Sertifikat'),
(11, 3, 'Mengikuti pendidikan dan pelatihan antara 81-160 jam', 2, 0, 'satu sertifikat per semester', 'STTP/Sertifikat'),
(12, 3, 'Mengikuti pendidikan dan pelatihan antara 30-80 jam', 1, 0, 'satu sertifikat per semester', 'STTP/Sertifikat'),
(13, 2, 'Melaksanakan perkuliahan untuk jabatan asisten ahli', 0.5, 0, 'tidak dibatasi sks', 'SK penugasan'),
(14, 2, 'Melaksanakan perkuliahan untuk jabatan di atas asisten ahli', 1, 0, 'tidak dibatasi sks', 'SK penugasan'),
(17, 1, 'Membimbing seminar mahasiswa', 1, 0, 'tidak dibatasi jumlah mahasiswa dihitung persemester', 'SK penugasan'),
(18, 1, 'Membimbing KKN', 1, 0, 'tidak dibatasi jumlah mahasiswa dihitung persemester', 'SK penugasan'),
(19, 1, 'Pembimbing disertasi', 14, 0, 'empat lulusan per semester', 'fotokopi lembar pengesahan disertasi'),
(20, 1, 'Pembimbing tesis', 3, 0, 'enam lulusan per semester', 'fotokopi lembar pengesahan tesis'),
(21, 1, 'Pembimbing skripsi', 1, 0, 'delapan lulusan per semester', 'fotokopi lembar pengesahan skripsi'),
(22, 1, 'Pembimbing laporan akhir studi', 1, 0, 'sepuluh lulusan per semester', 'fotokopi lembar pengesahan laporan akhir studi'),
(27, 1, 'Penguji ujian akhir ', 1, 0, 'empat lulusan per semester', 'surat penugasan atau surat undangan'),
(29, 1, 'Pembina kegiatan mahasiswa', 2, 0, 'tidak dibatasi jumlah mahasiswa dihitung persemester', 'SK penugasan'),
(30, 1, 'Mengembangkan program kuliah', 2, 0, 'satu mata kuliah per semester', 'makalah atau tulisan asli'),
(31, 1, 'Mengembangkan buku ajar untuk bahan pengajaran', 20, 0, 'satu buku per tahun', 'buku ajar atau buku teks asli'),
(32, 1, 'mengembangkan diktat, modul, alat bantu dsb untuk bahan pengajaran', 5, 0, 'satu karya per semester', 'dikta atau modul dsb asli'),
(33, 1, 'Menyampaikan orasi ilmiah', 5, 0, 'dua perguruan tinggi per semester', 'makalah atau buku bahan orasi ilmiah'),
(34, 3, 'Menduduki jabatan sebagai rektor', 6, 0, 'dosen yang menduduki lebih dari satu jabatan maka angka kredit paling tinggi yang dihitung', 'SK jabatan'),
(35, 3, 'Menduduki jabatan sebagai pembantu rektor, ketua lembaga, dekan fakultas atau direktur pascasarjana', 5, 0, 'dosen yang menduduki lebih dari satu jabatan maka angka kredit paling tinggi yang dihitung', 'SK jabatan'),
(36, 3, 'Menduduki jabatan sebagai pembantu dekan, ketua sekolah tinggi, asdir PPs, direktur politeknik, kapus penelitian universitas', 4, 0, 'dosen yang menduduki lebih dari satu jabatan maka angka kredit paling tinggi yang dihitung', 'SK jabatan'),
(37, 3, 'Menduduki jabatan sebagai direktur akademi, pembantu ketua sekolah tinggi,kapus penelitian dan pengabdian kepada masyarakat sekolah tinggi, pembantu direktur politeknik', 4, 0, 'dosen yang menduduki lebih dari satu jabatan maka angka kredit paling tinggi yang dihitung', 'SK Jabatan'),
(38, 3, 'Menduduki jabatan sebagai pembantu direktur akademi, kajur, kabag, sekretaris prodi,kepala laboratorium, ketua unit penelitian dan pengabdian pada masyarakat politeknik', 3, 0, 'dosen yang menduduki lebih dari satu jabatan maka angka kredit paling tinggi yang dihitung', 'SK Jabatan'),
(39, 1, 'Membimbing (pencangkokan) dosen yang lebih rendah jabatannya', 2, 0, 'dihitung per-semester', 'SK Penugasan'),
(40, 1, 'Membimbing (Reguler) dosen yang lebih rendah jabatannya', 1, 0, 'dihitung per-semester', 'SK Penugasan'),
(41, 1, 'Melaksanakan kegiatan pencangkokan', 5, 0, 'satu kegiatan pencangkokan persemester', 'SK Penugasan'),
(42, 1, 'Melaksanakan kegiatan detasering', 4, 0, 'satu kegiatan detasering persemester', 'SK Penugasan'),
(43, 4, 'Menghasilkan karya ilmiah (dipublikasikan) dalam bentuk monograf', 20, 0, 'satu buku pertahun', 'buku monograf asli'),
(44, 4, 'Menghasilkan karya ilmiah (dipublikasikan) dalam bentuk buku referensi', 40, 0, 'satu buku pertahun', 'buku referensi asli'),
(45, 4, 'Menghasilkan karya ilmiah (dipublikasikan) dalam majalah ilmiah internasional', 40, 0, 'reprint artikel yang dicetak oleh penerbit (legalisir)', 'buku monograf asli'),
(46, 4, 'Menghasilkan karya ilmiah (dipublikasikan) dalam majalah ilmiah nasional terakreditasi', 25, 0, 'satu artikel persemester', 'majalah ilmiah asli'),
(47, 4, 'Menghasilkan karya ilmiah (dipublikasikan) dalam majalah ilmiah nasional tidak terakreditasi', 10, 0, 'dua artikel persemester', 'majalah ilmiah asli'),
(48, 5, 'Menghasilkan karya ilmiah (dipublikasikan) melalui seminar internasional', 15, 0, 'satu makalah per-semester', 'prosiding asli'),
(49, 5, 'Menghasilkan karya ilmiah (dipublikasikan) melalui seminar nasional', 10, 0, 'dua makalah per-semester', 'prosiding asli'),
(50, 5, 'Menghasilkan karya ilmiah (dipublikasikan) melalui poster internasional', 10, 0, 'satu poster per-semester', 'prosiding asli'),
(51, 5, 'Menghasilkan karya ilmiah (dipublikasikan) melalui poster nasional', 5, 0, 'dua poster per-semester', 'prosiding asli'),
(52, 6, 'Menghasilkan karya ilmiah (dipublikasikan) koran', 1, 0, 'maksimal 10% dari angka kredit minimal yang diperlukan untuk melaksanakan penelitian', 'koran atau majalah umum yang memuat artikel'),
(53, 6, 'Menghasilkan karya ilmiah (tidak dipublikasikan) yang tersimpan diperpustakaan perguruan tinggi', 3, 0, 'maksimal 10% dari angka kredit minimal yang diperlukan untuk melaksanakan penelitian', 'buku atau makalah yang telah dilampiri bukti pendokumentasian dari perpustakaan perguruan tinggi'),
(54, 7, 'menerjemahkan/menyadur buku ilmiah', 15, 0, 'satu buku persemster', 'buku asli terjemahan/saduran'),
(55, 7, 'mengedit/menyunting karya ilmiah', 10, 0, 'satu buku persemster', 'buku asli hasil editing/suntingan'),
(56, 6, 'membuat rancangan dan karya teknologi (internasional) yang dipatenkan', 40, 0, 'satu karya per tahun', 'sertifikat keterangan paten yang dilegalisir'),
(57, 6, 'membuat rancangan dan karya teknologi (nasional) yang dipatenkan', 40, 0, 'satu karya per tahun', 'sertifikat keterangan paten yang dilegalisir'),
(58, 6, 'membuat rancangan dan karya teknologi (internasional) yang tidak dipatenkan', 20, 0, 'satu karya per tahun', 'surat keterangan keberadaan rancangan dan karya teknologi yang dilegalisir'),
(59, 6, 'membuat rancangan dan karya teknologi (nasional) yang tidak dipatenkan', 15, 0, 'satu karya per tahun', 'surat keterangan keberadaan rancangan dan karya teknologi yang dilegalisir'),
(60, 6, 'membuat rancangan dan karya teknologi (lokal) yang tidak dipatenkan', 10, 0, 'satu karya per tahun', 'surat keterangan keberadaan rancangan dan karya teknologi yang dilegalisir'),
(61, 8, 'Menduduki jabatan pimpinan pada lembaga pemerintahan/pejabat Negara yang harus dibebaskan dari jabatan organiknya', 5.5, 0, 'dihitung per-semester', 'SK jabatan'),
(62, 8, 'Melaksanakan pengembangan hasil pendidikan, dan peneliitan yang dapat dimanfaatkan oleh masyarakat', 3, 0, 'dihitung tiap program', 'SK penugasan'),
(63, 8, 'Memberi latihan/ penyuluhan/penataran/ceramah pada masyarakat terprogram dalam satu semester atau lebih (internasional)', 4, 0, 'dihitung tiap program', 'SK penugasan'),
(64, 8, 'Memberi latihan/ penyuluhan/penataran/ceramah pada masyarakat terprogram dalam satu semester atau lebih (nasional)', 3, 0, 'dihitung tiap program', 'SK penugasan'),
(65, 8, 'Memberi latihan/ penyuluhan/penataran/ceramah pada masyarakat terprogram dalam satu semester atau lebih (lokal)', 2, 0, 'dihitung tiap program', 'SK penugasan'),
(66, 8, 'Memberi latihan/ penyuluhan/penataran/ceramah pada masyarakat terprogram kurang dari satu semester, minimal satu bulan (internasional)', 3, 0, 'dihitung tiap program', 'SK penugasan'),
(67, 8, 'Memberi latihan/ penyuluhan/penataran/ceramah pada masyarakat terprogram kurang dari satu semester, minimal satu bulan (nasional)', 2, 0, 'dihitung tiap program', 'SK penugasan'),
(68, 8, 'Memberi latihan/ penyuluhan/penataran/ceramah pada masyarakat terprogram kurang dari satu semester, minimal satu bulan (lokal)', 1, 0, 'dihitung tiap program', 'SK penugasan'),
(69, 8, 'Memberi latihan/ penyuluhan/penataran/ceramah pada masyarakat secara insidental', 1, 0, 'dihitung tiap program', 'SK penugasan'),
(70, 8, 'Memberi pelayanan kepada Masyarakat atau kegiatan lain yang menunjang pelaksanaan tugas umum pemerintahan dan pembangunan berdasarkan bidang keahlian', 1.5, 0, 'dihitung tiap program', 'SK penugasan'),
(71, 8, 'Memberi pelayanan kepada Masyarakat atau kegiatan lain yang menunjang pelaksanaan tugas umum pemerintahan dan pembangunan berdasarkan penugasan', 1, 0, 'dihitung tiap program', 'SK penugasan'),
(72, 8, 'Memberi pelayanan kepada Masyarakat atau kegiatan lain yang menunjang pelaksanaan tugas umum pemerintahan dan pembangunan berdasarkan fungsi/jabatan', 0.5, 0, 'dihitung tiap program', 'SK penugasan'),
(73, 8, 'Membuat/menulis karya pengabdian pada masyarakat yang tidak dipublikasikan', 3, 0, 'dihitung tiap karya', 'SK penugasan'),
(74, 9, 'Menjadi anggota dalam suatu Panitia/Badan pada Perguruan Tinggi (ketua/wakil)', 2, 0, 'dihitung per-tahun', 'SK jabatan'),
(75, 9, 'Menjadi anggota dalam suatu Panitia/Badan pada Perguruan Tinggi (anggota)', 1, 0, 'dihitung per-tahun', 'SK jabatan'),
(76, 9, 'Menjadi ketua/wakil panitia/badan pada lembaga pemerintah pusat', 3, 0, 'dihitung perkepanitiaan', 'SK penugasan'),
(77, 9, 'Menjadi anggota panitia/badan pada lembaga pemerintah pusat', 2, 0, 'dihitung perkepanitiaan', 'SK penugasan'),
(78, 9, 'Menjadi ketua/wakil panitia/badan pada lembaga pemerintah daerah', 2, 0, 'dihitung perkepanitiaan', 'SK penugasan'),
(79, 9, 'Menjadi anggota panitia/badan pada lembaga pemerintah pusat', 1, 0, 'dihitung perkepanitiaan', 'SK penugasan'),
(80, 9, 'Menjadi pengurus organisasi profesi (internasional)', 2, 0, 'dihitung tiap periode jabatan', 'SK penugasan'),
(81, 9, 'Menjadi anggota (atas permintaan) organisasi profesi (internasional)', 1, 0, 'dihitung tiap periode jabatan', 'SK penugasan'),
(82, 9, 'Menjadi anggota organisasi profesi (internasional)', 0.5, 0, 'dihitung tiap periode jabatan', 'SK penugasan'),
(83, 9, 'Menjadi pengurus organisasi profesi (nasional)', 1.5, 0, 'dihitung tiap periode jabatan', 'SK penugasan'),
(84, 9, 'Menjadi anggota (atas permintaan) organisasi profesi (nasional)', 1, 0, 'dihitung tiap periode jabatan', 'SK penugasan'),
(85, 9, 'Menjadi anggota organisasi profesi (nasional)', 0.5, 0, 'dihitung tiap periode jabatan', 'SK penugasan'),
(86, 9, 'Mewakili Perguruan tinggi/Lembaga Pemerintah duduk dalam Panitia Antar Lembaga', 1, 0, 'dihitung tiap kepanitiaan', 'SK penugasan'),
(87, 9, 'Menjadi ketua delegasi Nasional ke pertemuan Internasional', 3, 0, 'dihitung tiap kegiatan', 'SK penugasan'),
(88, 9, 'Menjadi anggota delegasi Nasional ke pertemuan Internasional', 2, 0, 'dihitung tiap kegiatan', 'SK penugasan'),
(89, 9, 'Berperan serta aktif dalam pertemuan ilmiah sebagai ketua (internasional, nasional, regional)', 3, 0, 'dihitung tiap kegiatan', 'SK penugasan'),
(90, 9, 'Berperan serta aktif dalam pertemuan ilmiah sebagai anggota (internasional, nasional, regional)', 2, 0, 'dihitung tiap kegiatan', 'SK penugasan'),
(91, 9, 'Berperan serta aktif dalam pertemuan ilmiah sebagai ketua di lingkungan perguruan tinggi', 2, 0, 'dihitung tiap kegiatan', 'SK penugasan'),
(92, 9, 'Berperan serta aktif dalam pertemuan ilmiah sebagai anggota di lingkungan perguruan tinggi', 1, 0, 'dihitung tiap kegiatan', 'SK penugasan'),
(93, 9, 'Mendapat tanda jasa/penghargaan tingkat internasional', 5, 0, 'dihitung tiap penghargaan', 'piagam/sertifikat'),
(94, 9, 'Mendapat tanda jasa/penghargaan tingkat nasional', 3, 0, 'dihitung tiap penghargaan', 'piagam/sertifikat'),
(95, 9, 'Mendapat tanda jasa/penghargaan tingkat regional/lokal', 1, 0, 'dihitung tiap penghargaan', 'piagam/sertifikat'),
(96, 9, 'Menulis buku pelajaran SLTA ke bawah yang diterbitkan dan diedarkan secara nasional', 5, 0, 'dihitung tiap buku', 'buku asli'),
(97, 9, 'Mempunyai prestasi di bidang olahraga/Humaniora (internasional)', 3, 0, 'dihitung tiap medali', 'piagam/sertifikat'),
(98, 9, 'Mempunyai prestasi di bidang olahraga/Humaniora (nasional)', 2, 0, 'dihitung tiap medali', 'piagam/sertifikat'),
(99, 9, 'Mempunyai prestasi di bidang olahraga/Humaniora (regional/lokal)', 1, 0, 'dihitung tiap medali', 'piagam/sertifikat'),
(101, 3, 'mengikuti pendidikan sekolah dan memperoleh gelar Magister', 9, 0, '1(satu) ijazah per periode penilaian', 'fotokopi ijazah yang dilegalisir oleh pejabat yang berkompeten');

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE IF NOT EXISTS `news` (
  `idnews` int(100) NOT NULL AUTO_INCREMENT,
  `file` varchar(100) DEFAULT NULL,
  `counter` int(11) NOT NULL,
  `tittle` varchar(100) NOT NULL,
  `deskripsi` text NOT NULL,
  `tglupdate` datetime NOT NULL,
  `kategori` enum('berita','download') NOT NULL,
  PRIMARY KEY (`idnews`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`idnews`, `file`, `counter`, `tittle`, `deskripsi`, `tglupdate`, `kategori`) VALUES
(3, '32_220379d240.docx', 0, 'formulir pendaftaran kenaikan pangkat dosen', 'formulir untuk pengisian data pengajuan kenaikkan pangkat dosen', '2014-03-17 03:46:09', 'download'),
(5, '41_5516ed6d7b.png', 0, 'Surat Edaran Tentang Usulan Penetapan Angka Kredit Dan Kenaikan Jabatan', 'Dalam rangka meningkatkan pelayanan kepegawaian dan aspek ketenagaan secara terintegrasi dan selaras dengan kebijakan pangkalan data perguruan tinggi (PDPT), Biro Kepegawaian Sekretariat Jenderal dan Direktorat Pendidik dan Tenaga Kependidikan, Direktorat jenderal Pendidikan Tinggi Kementerian Pendidikan Nasional telah melakukan penilaian terhadap usulan penetapan angka kredit dan kenaikan jabatan Lektor Kepala dan Guru Besar dengan menggunakan Sistem Informasi Penetapan Angka Kredit (SIMPAK) mulai pengusulan 1 Juli 2011, sebagaimana surat Direktur Pendidik dan Tenaga. Kependidikan Nomor 1037/E4.3/2011 tanggal 5 Mei 2011. Untuk memperlancar pelaksanaan SIMPAK secara Online melalui laman : pak.dikti.go.id, dengan ini kami sampaikan mekanisme pengusulan penilaian penetapan angka kredit, kenaikan jabatan, dan kenaikan panakat PNS dosen Lektor Kepala dan Guru Besar pada Perauruan Tinggi Negeri dan Koordinasi Perguruan Tinggi Swasta (Kopertis) melalui surat edaran yang dapat diunduh di halaman download laman ini.', '2014-04-26 12:26:20', 'berita'),
(6, '61_1c2a27e765.pdf', 0, '[Edaran Dirjen DIKTI] 117 Jabfung dan AK ', 'jabatan fungsional beserta angka kreditnya', '2014-03-26 14:21:03', 'download');

-- --------------------------------------------------------

--
-- Table structure for table `periode`
--

CREATE TABLE IF NOT EXISTS `periode` (
  `id_periode` int(11) NOT NULL AUTO_INCREMENT,
  `bulan` int(2) NOT NULL,
  `tahun` int(4) NOT NULL,
  PRIMARY KEY (`id_periode`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `periode`
--

INSERT INTO `periode` (`id_periode`, `bulan`, `tahun`) VALUES
(1, 4, 2013),
(2, 10, 2014);

-- --------------------------------------------------------

--
-- Table structure for table `prodi`
--

CREATE TABLE IF NOT EXISTS `prodi` (
  `idprodi` int(10) NOT NULL AUTO_INCREMENT,
  `prodi` varchar(255) NOT NULL,
  `idjur` int(10) NOT NULL,
  PRIMARY KEY (`idprodi`),
  KEY `idjur` (`idjur`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `prodi`
--

INSERT INTO `prodi` (`idprodi`, `prodi`, `idjur`) VALUES
(1, 'D3 MANAJEMEN INFORMARTIKA', 1),
(2, 'S1 PEND TEKNIK ELEKTRO', 1),
(3, 'S1 PEND TEKNOLOGI IFORMASI', 1),
(4, 'S1 PEND TEKNIK MESIN', 2),
(6, 'D3 TEKNIK MESIN', 2);

-- --------------------------------------------------------

--
-- Table structure for table `pt`
--

CREATE TABLE IF NOT EXISTS `pt` (
  `id_pt` int(11) NOT NULL,
  `pt` varchar(255) NOT NULL,
  PRIMARY KEY (`id_pt`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pt`
--

INSERT INTO `pt` (`id_pt`, `pt`) VALUES
(1, 'S1'),
(2, 'S2'),
(3, 'S3');

-- --------------------------------------------------------

--
-- Table structure for table `rule`
--

CREATE TABLE IF NOT EXISTS `rule` (
  `id_rule` int(11) NOT NULL AUTO_INCREMENT,
  `id_gol` int(11) NOT NULL,
  `point` int(11) NOT NULL,
  `masa` int(11) NOT NULL,
  `masaJab` int(11) NOT NULL,
  `id_pt` int(11) NOT NULL,
  PRIMARY KEY (`id_rule`),
  KEY `id_gol` (`id_gol`),
  KEY `id_pt` (`id_pt`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `rule`
--

INSERT INTO `rule` (`id_rule`, `id_gol`, `point`, `masa`, `masaJab`, `id_pt`) VALUES
(1, 1, 100, 2, 1, 1),
(2, 2, 50, 2, 1, 2),
(3, 3, 50, 2, 1, 2),
(4, 4, 100, 2, 1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `subkatkeg`
--

CREATE TABLE IF NOT EXISTS `subkatkeg` (
  `id_subkatkeg` int(11) NOT NULL AUTO_INCREMENT,
  `id_katkeg` int(11) NOT NULL,
  `subkatkeg` enum('grup','kuliah') DEFAULT NULL,
  `dsubkatkeg` enum('jurnal','prosiding','penelitian') DEFAULT NULL,
  PRIMARY KEY (`id_subkatkeg`),
  KEY `id_katkeg` (`id_katkeg`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `subkatkeg`
--

INSERT INTO `subkatkeg` (`id_subkatkeg`, `id_katkeg`, `subkatkeg`, `dsubkatkeg`) VALUES
(1, 1, 'grup', NULL),
(2, 1, 'kuliah', NULL),
(3, 1, NULL, NULL),
(4, 2, 'grup', 'jurnal'),
(5, 2, 'grup', 'prosiding'),
(6, 2, 'grup', 'penelitian'),
(7, 2, 'grup', NULL),
(8, 3, NULL, NULL),
(9, 4, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `iduser` int(5) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `password` text NOT NULL,
  `level` varchar(10) NOT NULL,
  PRIMARY KEY (`iduser`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=56 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`iduser`, `username`, `password`, `level`) VALUES
(1, 'ft', '49af3b640275c9b552a5f3f3d96a6062', 'adminf'),
(2, 'univ', 'ecdf60aa6c20eca4ea902ec98909c783', 'adminu'),
(30, 'subuh', '7d9523fc12e5691c051c8885625fd806', 'user'),
(35, 'anifah', '31746d1b6b347241e4b9f8637527de7a', 'user'),
(36, 'syarif', '8daa2f003d41f1ea865c1503b3d99d3d', 'user'),
(40, 'agusp', 'd85d6f339711902e0cb1d2fd05fd98ec', 'user'),
(41, 'dedy', 'd5fdbe5b16111739a53f6bedc2c29e5c', 'user'),
(42, 'asmunin', '0f8d8f03501d14b5ab896e1e9065bed3', 'user'),
(43, 'andi', 'ce0e5bf55e4f71749eade7a8b95c4e46', 'user'),
(44, 'eka', '79ee82b17dfb837b1be94a6827fa395a', 'user'),
(45, 'salamun', '53d0ec31b7b91ca612c3920d2ccb6697', 'user'),
(46, 'fe', '2d917f5d1275e96fd75e6352e26b1387', 'adminf'),
(47, 'ft', '7347416ebef5b0ac3c3e6e6778534c42', 'adminf'),
(48, 'fbs', '0c676dd1e9dd719c36bef5fbc7562df0', 'adminf'),
(49, 'fmipa', '640a10b73e5d19e72f5589be18d642f2', 'adminf'),
(50, 'fik', 'a29530332290da89653790cac7b5f9c8', 'adminf'),
(51, 'fis', '37ab815c056b5c5f600f6ac93e486a78', 'adminf'),
(52, 'erina', '0a7011496eb85eb074f46c0ac8fe2acf', 'user'),
(53, 'peni', '3c5ce124a833c9f6db7beef9072a0be7', 'user'),
(54, 'puput', 'f95c24c42b0f2ea683727cc47cde3ad2', 'user'),
(55, 'fak', 'd130795249ec1ade352b18b325e328b9', 'adminf');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`idfak`) REFERENCES `fak` (`idfak`),
  ADD CONSTRAINT `admin_ibfk_2` FOREIGN KEY (`iduser`) REFERENCES `user` (`iduser`);

--
-- Constraints for table `bukeg`
--
ALTER TABLE `bukeg`
  ADD CONSTRAINT `bukeg_ibfk_1` FOREIGN KEY (`iddtk`) REFERENCES `dtk` (`iddtk`) ON DELETE CASCADE;

--
-- Constraints for table `dsn`
--
ALTER TABLE `dsn`
  ADD CONSTRAINT `dsn_ibfk_1` FOREIGN KEY (`iduser`) REFERENCES `user` (`iduser`) ON DELETE CASCADE,
  ADD CONSTRAINT `dsn_ibfk_2` FOREIGN KEY (`idprodi`) REFERENCES `prodi` (`idprodi`);

--
-- Constraints for table `dtk`
--
ALTER TABLE `dtk`
  ADD CONSTRAINT `dtk_ibfk_2` FOREIGN KEY (`idkeg`) REFERENCES `kegiatan` (`idkeg`),
  ADD CONSTRAINT `dtk_ibfk_3` FOREIGN KEY (`idhistjab`) REFERENCES `histjab` (`idhistjab`) ON DELETE CASCADE;

--
-- Constraints for table `dtksisa`
--
ALTER TABLE `dtksisa`
  ADD CONSTRAINT `dtksisa_ibfk_2` FOREIGN KEY (`idkatkeg`) REFERENCES `katkeg` (`idkatkeg`),
  ADD CONSTRAINT `dtksisa_ibfk_3` FOREIGN KEY (`idhistjab`) REFERENCES `histjab` (`idhistjab`);

--
-- Constraints for table `histjab`
--
ALTER TABLE `histjab`
  ADD CONSTRAINT `histjab_ibfk_1` FOREIGN KEY (`id_gol`) REFERENCES `gol` (`id_gol`),
  ADD CONSTRAINT `histjab_ibfk_2` FOREIGN KEY (`id_pt`) REFERENCES `pt` (`id_pt`),
  ADD CONSTRAINT `histjab_ibfk_3` FOREIGN KEY (`iddsn`) REFERENCES `dsn` (`iddsn`) ON DELETE CASCADE,
  ADD CONSTRAINT `histjab_ibfk_4` FOREIGN KEY (`id_periode`) REFERENCES `periode` (`id_periode`);

--
-- Constraints for table `jab`
--
ALTER TABLE `jab`
  ADD CONSTRAINT `jab_ibfk_1` FOREIGN KEY (`id_jab`) REFERENCES `gol` (`id_jab`);

--
-- Constraints for table `jur`
--
ALTER TABLE `jur`
  ADD CONSTRAINT `jur_ibfk_1` FOREIGN KEY (`idfak`) REFERENCES `fak` (`idfak`);

--
-- Constraints for table `kegiatan`
--
ALTER TABLE `kegiatan`
  ADD CONSTRAINT `kegiatan_ibfk_1` FOREIGN KEY (`id_subkatkeg`) REFERENCES `subkatkeg` (`id_subkatkeg`);

--
-- Constraints for table `prodi`
--
ALTER TABLE `prodi`
  ADD CONSTRAINT `prodi_ibfk_1` FOREIGN KEY (`idjur`) REFERENCES `jur` (`idjur`);

--
-- Constraints for table `rule`
--
ALTER TABLE `rule`
  ADD CONSTRAINT `rule_ibfk_1` FOREIGN KEY (`id_gol`) REFERENCES `gol` (`id_gol`),
  ADD CONSTRAINT `rule_ibfk_2` FOREIGN KEY (`id_pt`) REFERENCES `pt` (`id_pt`);

--
-- Constraints for table `subkatkeg`
--
ALTER TABLE `subkatkeg`
  ADD CONSTRAINT `subkatkeg_ibfk_1` FOREIGN KEY (`id_katkeg`) REFERENCES `katkeg` (`idkatkeg`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
