-- phpMyAdmin SQL Dump
-- version 4.6.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 22, 2019 at 04:22 PM
-- Server version: 5.5.62-0+deb8u1
-- PHP Version: 7.0.33-1~dotdeb+8.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `tickets`
--

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `ID` int(11) NOT NULL,
  `title` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `body` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `color` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `canned_responses`
--

CREATE TABLE `canned_responses` (
  `ID` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `body` mediumtext COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

CREATE TABLE `ci_sessions` (
  `id` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `ip_address` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `data` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `custom_fields`
--

CREATE TABLE `custom_fields` (
  `ID` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` int(11) NOT NULL,
  `options` varchar(2000) COLLATE utf8_unicode_ci NOT NULL,
  `required` int(11) NOT NULL,
  `profile` int(11) NOT NULL,
  `edit` int(11) NOT NULL,
  `help_text` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `register` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `custom_statuses`
--

CREATE TABLE `custom_statuses` (
  `ID` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `color` varchar(6) NOT NULL,
  `close` int(11) NOT NULL,
  `text_color` varchar(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `custom_statuses`
--

INSERT INTO `custom_statuses` (`ID`, `name`, `color`, `close`, `text_color`) VALUES
(1, 'New', '79d7ef', 0, 'FFFFFF'),
(2, 'In Progress', 'fab13e', 0, 'FFFFFF'),
(3, 'Closed', 'ec6060', 1, 'FFFFFF');

-- --------------------------------------------------------

--
-- Table structure for table `custom_views`
--

CREATE TABLE `custom_views` (
  `ID` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `categoryid` int(11) NOT NULL,
  `order_by` int(11) NOT NULL,
  `order_by_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(500) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `documentation_projects`
--

CREATE TABLE `documentation_projects` (
  `ID` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `icon` varchar(500) NOT NULL,
  `footer` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `ID` int(11) NOT NULL,
  `projectid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `document` text NOT NULL,
  `timestamp` int(11) NOT NULL,
  `last_updated` int(11) NOT NULL,
  `link_documentid` int(11) NOT NULL,
  `sort_no` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `document_files`
--

CREATE TABLE `document_files` (
  `ID` int(11) NOT NULL,
  `documentid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `extension` varchar(25) NOT NULL,
  `file_size` int(11) NOT NULL,
  `file_type` varchar(255) NOT NULL,
  `timestamp` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `email_templates`
--

CREATE TABLE `email_templates` (
  `ID` int(11) NOT NULL,
  `title` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `message` text COLLATE utf8_unicode_ci NOT NULL,
  `hook` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `language` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `email_templates`
--

INSERT INTO `email_templates` (`ID`, `title`, `message`, `hook`, `language`) VALUES
(1, 'Forgot Your Password', '<p>Dear [NAME],<br />\r\n<br />\r\nSomeone (hopefully you) requested a password reset at [SITE_URL].<br />\r\n<br />\r\nTo reset your password, please follow the following link: [EMAIL_LINK]<br />\r\n<br />\r\nIf you did not reset your password, please kindly ignore this email.<br />\r\n<br />\r\nYours,<br />\r\n[SITE_NAME]</p>\r\n', 'forgot_password', 'english'),
(2, 'Email Activation', '<p>Dear [NAME],<br />\r\n<br />\r\nSomeone (hopefully you) has registered an account on [SITE_NAME] using this email address.<br />\r\n<br />\r\nPlease activate the account by following this link: [EMAIL_LINK]<br />\r\n<br />\r\nIf you did not register an account, please kindly ignore this email.<br />\r\n<br />\r\nYours,<br />\r\n[SITE_NAME]</p>\r\n', 'email_activation', 'english'),
(3, 'Support Ticket Reply', '<p>[IMAP_TICKET_REPLY_STRING]<br />\r\n<br />\r\nDear [NAME],<br />\r\n<br />\r\nA new reply was posted on your ticket:<br />\r\n<br />\r\n[TICKET_BODY]<br />\r\n<br />\r\nTicket was generated here: [SITE_URL]<br />\r\n<br />\r\n[IMAP_TICKET_ID] [TICKET_ID]<br />\r\n<br />\r\nYours,<br />\r\n[SITE_NAME]</p>\r\n', 'ticket_reply', 'english'),
(4, 'Support Ticket Creation', '<p>[IMAP_TICKET_REPLY_STRING]<br />\r\n<br />\r\nDear [NAME],<br />\r\n<br />\r\nThanks for creating a ticket at our site: [SITE_URL]<br />\r\n<br />\r\nYour message:<br />\r\n<br />\r\n[TICKET_BODY]<br />\r\n<br />\r\nWe&#39;ll be in touch shortly.<br />\r\n<br />\r\n[IMAP_TICKET_ID] [TICKET_ID]<br />\r\n<br />\r\nYours,<br />\r\n[SITE_NAME]</p>\r\n', 'ticket_creation', 'english'),
(5, 'Support Guest Ticket Creation', '<p>[IMAP_TICKET_REPLY_STRING]<br />\r\n<br />\r\nDear [NAME],<br />\r\n<br />\r\nThanks for creating a ticket at our site: [SITE_URL]<br />\r\n<br />\r\nYour message:<br />\r\n<br />\r\n[TICKET_BODY]<br />\r\n<br />\r\nTo view your ticket, use these Guest Login details:<br />\r\nEmail: [GUEST_EMAIL]<br />\r\nPassword: [GUEST_PASS]<br />\r\n<br />\r\nGuests Login Here: [GUEST_LOGIN]<br />\r\n<br />\r\nWe&#39;ll be in touch shortly.<br />\r\n<br />\r\n[IMAP_TICKET_ID] [TICKET_ID]<br />\r\n<br />\r\nYours,<br />\r\n[SITE_NAME]</p>\r\n', 'guest_ticket_creation', 'english'),
(7, 'Ticket Reminder', '<p>Dear [NAME],<br />\r\n<br />\r\nThis is a reminder that you currently have an open ticket that needs your attention.<br />\r\n<br />\r\nPlease login to your ticket at:<br />\r\n<br />\r\n[SITE_URL]<br />\r\n<br />\r\nEmail Login: [NAME]<br />\r\nTicket Password: [GUEST_PASS]<br />\r\n<br />\r\nYours,<br />\r\n[SITE_NAME]</p>\r\n', 'ticket_reminder', 'english'),
(8, 'New Notification', 'Dear [NAME],<br /><br />\r\n\r\nYou have a new notification waiting for you at [SITE_NAME]. You can view it by logging into:<br /><br />\r\n\r\n[SITE_URL]<br /><br />\r\n\r\nYours,<br />\r\n[SITE_NAME]', 'new_notification', 'english'),
(9, 'Close Ticket', '<p>Dear [NAME],</p>\r\n\r\n<p>We have just closed your ticket at [SITE_URL]. You can view the ticket here: [TICKET_URL].</p>\r\n\r\n<p>If you need anymore assistance, please open a new ticket and we&#39;ll be happy to help.</p>\r\n\r\n<p>Thanks,</p>\r\n\r\n<p>[SITE_NAME]</p>\r\n', 'close_ticket', 'english');

-- --------------------------------------------------------

--
-- Table structure for table `faq`
--

CREATE TABLE `faq` (
  `ID` int(11) NOT NULL,
  `question` varchar(500) NOT NULL,
  `answer` varchar(2000) NOT NULL,
  `catid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `faq_categories`
--

CREATE TABLE `faq_categories` (
  `ID` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `home_stats`
--

CREATE TABLE `home_stats` (
  `ID` int(11) NOT NULL,
  `google_members` int(11) NOT NULL DEFAULT '0',
  `facebook_members` int(11) NOT NULL DEFAULT '0',
  `twitter_members` int(11) NOT NULL DEFAULT '0',
  `total_members` int(11) NOT NULL DEFAULT '0',
  `new_members` int(11) NOT NULL DEFAULT '0',
  `active_today` int(11) NOT NULL DEFAULT '0',
  `timestamp` int(11) NOT NULL DEFAULT '0',
  `total_tickets` int(11) NOT NULL,
  `total_assigned_tickets` int(11) NOT NULL,
  `tickets_today` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `home_stats`
--

INSERT INTO `home_stats` (`ID`, `google_members`, `facebook_members`, `twitter_members`, `total_members`, `new_members`, `active_today`, `timestamp`, `total_tickets`, `total_assigned_tickets`, `tickets_today`) VALUES
(1, 0, 0, 0, 0, 0, 0, 1553267937, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `ipn_log`
--

CREATE TABLE `ipn_log` (
  `ID` int(11) NOT NULL,
  `data` text COLLATE utf8_unicode_ci,
  `timestamp` int(11) NOT NULL DEFAULT '0',
  `IP` varchar(500) COLLATE utf8_unicode_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ip_block`
--

CREATE TABLE `ip_block` (
  `ID` int(11) NOT NULL,
  `IP` varchar(500) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `timestamp` int(11) NOT NULL DEFAULT '0',
  `reason` varchar(1000) COLLATE utf8_unicode_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `knowledge_articles`
--

CREATE TABLE `knowledge_articles` (
  `ID` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `body` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `userid` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `catid` int(11) NOT NULL,
  `last_updated_timestamp` int(11) NOT NULL,
  `useful_yes` int(11) NOT NULL,
  `useful_total` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `knowledge_categories`
--

CREATE TABLE `knowledge_categories` (
  `ID` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `image` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `parent_category` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `knowledge_categories`
--

INSERT INTO `knowledge_categories` (`ID`, `name`, `description`, `image`, `parent_category`) VALUES
(1, 'Default', '<p>This is a default category</p>\n', 'default_cat.png', 0);

-- --------------------------------------------------------

--
-- Table structure for table `knowledge_groups`
--

CREATE TABLE `knowledge_groups` (
  `ID` int(11) NOT NULL,
  `catid` int(11) NOT NULL,
  `groupid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `knowledge_votes`
--

CREATE TABLE `knowledge_votes` (
  `ID` int(11) NOT NULL,
  `articleid` int(11) NOT NULL,
  `IP` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

CREATE TABLE `login_attempts` (
  `ID` int(11) NOT NULL,
  `IP` varchar(500) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `username` varchar(500) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `count` int(11) NOT NULL DEFAULT '0',
  `timestamp` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset`
--

CREATE TABLE `password_reset` (
  `ID` int(11) NOT NULL,
  `userid` int(11) NOT NULL DEFAULT '0',
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `timestamp` int(11) NOT NULL DEFAULT '0',
  `IP` varchar(500) COLLATE utf8_unicode_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_logs`
--

CREATE TABLE `payment_logs` (
  `ID` int(11) NOT NULL,
  `userid` int(11) NOT NULL DEFAULT '0',
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `timestamp` int(11) NOT NULL DEFAULT '0',
  `email` varchar(500) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `processor` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_plans`
--

CREATE TABLE `payment_plans` (
  `ID` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `hexcolor` varchar(6) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `fontcolor` varchar(6) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `cost` decimal(10,2) NOT NULL DEFAULT '0.00',
  `days` int(11) NOT NULL DEFAULT '0',
  `sales` int(11) NOT NULL DEFAULT '0',
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `icon` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `payment_plans`
--

INSERT INTO `payment_plans` (`ID`, `name`, `hexcolor`, `fontcolor`, `cost`, `days`, `sales`, `description`, `icon`) VALUES
(2, 'BASIC', '65E0EB', 'FFFFFF', '3.00', 30, 11, 'This is the basic plan which gives you a introduction to our Premium Plans', 'glyphicon glyphicon-heart-empty'),
(3, 'Professional', '55CD7B', 'FFFFFF', '7.99', 90, 3, 'Get all the benefits of basic at a cheaper price and gain content for longer.', 'glyphicon glyphicon-piggy-bank'),
(4, 'LIFETIME', 'EE5782', 'FFFFFF', '300.00', 0, 2, 'Become a premium member for life and have access to all our premium content.', 'glyphicon glyphicon-gift');

-- --------------------------------------------------------

--
-- Table structure for table `reset_log`
--

CREATE TABLE `reset_log` (
  `ID` int(11) NOT NULL,
  `IP` varchar(500) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `timestamp` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `site_layouts`
--

CREATE TABLE `site_layouts` (
  `ID` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `layout_path` varchar(500) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `site_layouts`
--

INSERT INTO `site_layouts` (`ID`, `name`, `layout_path`) VALUES
(1, 'Basic', 'layout/themes/layout.php'),
(2, 'Titan', 'layout/themes/titan_layout.php'),
(3, 'Dark Fire', 'layout/themes/dark_fire_layout.php'),
(4, 'Light Blue', 'layout/themes/light_blue_layout.php');

-- --------------------------------------------------------

--
-- Table structure for table `site_settings`
--

CREATE TABLE `site_settings` (
  `ID` int(11) NOT NULL,
  `site_name` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `site_desc` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `upload_path` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `upload_path_relative` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `site_email` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `site_logo` varchar(1000) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'logo.png',
  `register` int(11) NOT NULL,
  `disable_captcha` int(11) NOT NULL,
  `date_format` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `avatar_upload` int(11) NOT NULL DEFAULT '1',
  `file_types` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `twitter_consumer_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `twitter_consumer_secret` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `disable_social_login` int(11) NOT NULL,
  `facebook_app_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `facebook_app_secret` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `google_client_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `google_client_secret` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `file_size` int(11) NOT NULL,
  `paypal_email` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `paypal_currency` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'USD',
  `payment_enabled` int(11) NOT NULL,
  `payment_symbol` varchar(5) COLLATE utf8_unicode_ci NOT NULL DEFAULT '$',
  `global_premium` int(11) NOT NULL,
  `install` int(11) NOT NULL DEFAULT '1',
  `login_protect` int(11) NOT NULL,
  `activate_account` int(11) NOT NULL,
  `default_user_role` int(11) NOT NULL,
  `secure_login` int(11) NOT NULL,
  `stripe_secret_key` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `stripe_publish_key` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `enable_ticket_uploads` int(11) NOT NULL,
  `enable_ticket_guests` int(11) NOT NULL,
  `enable_ticket_edit` int(11) NOT NULL,
  `require_login` int(11) NOT NULL,
  `protocol` int(11) NOT NULL,
  `protocol_path` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `protocol_email` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `protocol_password` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `ticket_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `protocol_ssl` int(11) NOT NULL,
  `ticket_rating` int(11) NOT NULL,
  `notes` text COLLATE utf8_unicode_ci NOT NULL,
  `google_recaptcha` int(11) NOT NULL,
  `google_recaptcha_secret` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `google_recaptcha_key` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `logo_option` int(11) NOT NULL,
  `avatar_height` int(11) NOT NULL,
  `avatar_width` int(11) NOT NULL,
  `default_category` int(11) NOT NULL,
  `checkout2_accountno` int(11) NOT NULL,
  `checkout2_secret` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `layout` varchar(500) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'layout/themes/titan_layout.php',
  `imap_ticket_string` varchar(500) COLLATE utf8_unicode_ci DEFAULT '## Ticket ID: ',
  `imap_reply_string` varchar(500) COLLATE utf8_unicode_ci NOT NULL DEFAULT '## - REPLY ABOVE THIS LINE - ##',
  `captcha_ticket` int(11) NOT NULL,
  `envato_personal_token` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `cache_time` int(11) NOT NULL DEFAULT '3600',
  `ticket_note_close` int(11) NOT NULL,
  `default_status` int(11) NOT NULL,
  `close_ticket_reply` int(11) NOT NULL,
  `disable_cert` int(11) NOT NULL,
  `staff_status` int(11) NOT NULL,
  `client_status` int(11) NOT NULL,
  `public_tickets` int(11) NOT NULL,
  `enable_knowledge` int(11) NOT NULL DEFAULT '1',
  `enable_faq` int(11) NOT NULL DEFAULT '1',
  `logo_height` int(11) NOT NULL DEFAULT '32',
  `logo_width` int(11) NOT NULL DEFAULT '123',
  `enable_documentation` int(11) NOT NULL,
  `resize_avatar` int(11) NOT NULL,
  `alert_users` varchar(1500) COLLATE utf8_unicode_ci NOT NULL,
  `price_per_ticket` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `site_settings`
--

INSERT INTO `site_settings` (`ID`, `site_name`, `site_desc`, `upload_path`, `upload_path_relative`, `site_email`, `site_logo`, `register`, `disable_captcha`, `date_format`, `avatar_upload`, `file_types`, `twitter_consumer_key`, `twitter_consumer_secret`, `disable_social_login`, `facebook_app_id`, `facebook_app_secret`, `google_client_id`, `google_client_secret`, `file_size`, `paypal_email`, `paypal_currency`, `payment_enabled`, `payment_symbol`, `global_premium`, `install`, `login_protect`, `activate_account`, `default_user_role`, `secure_login`, `stripe_secret_key`, `stripe_publish_key`, `enable_ticket_uploads`, `enable_ticket_guests`, `enable_ticket_edit`, `require_login`, `protocol`, `protocol_path`, `protocol_email`, `protocol_password`, `ticket_title`, `protocol_ssl`, `ticket_rating`, `notes`, `google_recaptcha`, `google_recaptcha_secret`, `google_recaptcha_key`, `logo_option`, `avatar_height`, `avatar_width`, `default_category`, `checkout2_accountno`, `checkout2_secret`, `layout`, `imap_ticket_string`, `imap_reply_string`, `captcha_ticket`, `envato_personal_token`, `cache_time`, `ticket_note_close`, `default_status`, `close_ticket_reply`, `disable_cert`, `staff_status`, `client_status`, `public_tickets`, `enable_knowledge`, `enable_faq`, `logo_height`, `logo_width`, `enable_documentation`, `resize_avatar`, `alert_users`, `price_per_ticket`) VALUES
(1, 'Support Center', 'Welcome to Support Centre', '/var/www/uploads', 'uploads', 'test@test.com', 'logo.png', 0, 1, 'd/m/Y h:i', 1, 'gif|png|jpg|jpeg', '', '', 0, '', '', '', '', 1028, '', 'USD', 1, '$', 0, 1, 1, 0, 10, 0, '', '', 1, 1, 1, 0, 1, 'imap.gmail.com:993', '', '', 'Support Ticket', 1, 1, '', 0, '', '', 0, 100, 100, 1, 0, '', 'layout/themes/titan_layout.php', '## Ticket ID:', '## - REPLY ABOVE THIS LINE - ##', 0, '', 3600, 0, 1, 1, 0, 2, 1, 1, 1, 1, 32, 123, 1, 1, '', '0.00');

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `ID` int(11) NOT NULL,
  `title` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `body` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `userid` int(11) NOT NULL,
  `assignedid` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `categoryid` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `priority` int(11) NOT NULL,
  `last_reply_timestamp` int(11) NOT NULL,
  `last_reply_userid` int(11) NOT NULL,
  `notes` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `message_id_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `guest_email` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `guest_password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_reply_string` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `rating` int(11) NOT NULL,
  `ticket_date` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `close_ticket_date` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `archived` int(11) NOT NULL,
  `public` int(11) NOT NULL,
  `close_timestamp` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ticket_categories`
--

CREATE TABLE `ticket_categories` (
  `ID` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `cat_parent` int(11) NOT NULL,
  `image` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `no_tickets` int(11) NOT NULL,
  `auto_assign` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `ticket_categories`
--

INSERT INTO `ticket_categories` (`ID`, `name`, `description`, `cat_parent`, `image`, `no_tickets`, `auto_assign`) VALUES
(1, 'Default', '<p>The default group.</p>\r\n', 0, '8ee11fb7b8cfe92cdf20749847761694.png', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `ticket_category_groups`
--

CREATE TABLE `ticket_category_groups` (
  `ID` int(11) NOT NULL,
  `groupid` int(11) NOT NULL,
  `catid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ticket_custom_fields`
--

CREATE TABLE `ticket_custom_fields` (
  `ID` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` int(11) NOT NULL,
  `options` varchar(2000) COLLATE utf8_unicode_ci NOT NULL,
  `help_text` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `required` int(11) NOT NULL,
  `all_cats` int(11) NOT NULL,
  `hide_clientside` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ticket_custom_field_cats`
--

CREATE TABLE `ticket_custom_field_cats` (
  `ID` int(11) NOT NULL,
  `fieldid` int(11) NOT NULL,
  `catid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ticket_files`
--

CREATE TABLE `ticket_files` (
  `ID` int(11) NOT NULL,
  `ticketid` int(11) NOT NULL,
  `upload_file_name` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `file_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `extension` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
  `file_size` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` int(11) NOT NULL,
  `replyid` int(11) NOT NULL,
  `userid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ticket_history`
--

CREATE TABLE `ticket_history` (
  `ID` int(11) NOT NULL,
  `ticketid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `message` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ticket_replies`
--

CREATE TABLE `ticket_replies` (
  `ID` int(11) NOT NULL,
  `ticketid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `body` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` int(11) NOT NULL,
  `replyid` int(11) NOT NULL,
  `files` int(11) NOT NULL,
  `hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ticket_user_custom_fields`
--

CREATE TABLE `ticket_user_custom_fields` (
  `ID` int(11) NOT NULL,
  `fieldid` int(11) NOT NULL,
  `ticketid` int(11) NOT NULL,
  `value` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `itemname` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `support` int(11) NOT NULL,
  `error` varchar(1000) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `ID` int(11) NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `password` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `IP` varchar(500) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `username` varchar(25) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `first_name` varchar(25) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `last_name` varchar(25) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `avatar` varchar(1000) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'default.png',
  `joined` int(11) NOT NULL DEFAULT '0',
  `joined_date` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `online_timestamp` int(11) NOT NULL DEFAULT '0',
  `oauth_provider` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `oauth_id` varchar(1000) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `oauth_token` varchar(1500) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `oauth_secret` varchar(500) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `email_notification` int(11) NOT NULL DEFAULT '1',
  `aboutme` varchar(1000) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `points` decimal(10,2) NOT NULL DEFAULT '0.00',
  `premium_time` int(11) NOT NULL DEFAULT '0',
  `user_role` int(11) NOT NULL DEFAULT '0',
  `premium_planid` int(11) NOT NULL DEFAULT '0',
  `active` int(11) NOT NULL DEFAULT '1',
  `activate_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `noti_count` int(11) NOT NULL,
  `custom_view` int(11) NOT NULL,
  `active_project` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_custom_fields`
--

CREATE TABLE `user_custom_fields` (
  `ID` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `fieldid` int(11) NOT NULL,
  `value` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_events`
--

CREATE TABLE `user_events` (
  `ID` int(11) NOT NULL,
  `IP` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `event` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `timestamp` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_groups`
--

CREATE TABLE `user_groups` (
  `ID` int(11) NOT NULL,
  `name` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `default` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user_groups`
--

INSERT INTO `user_groups` (`ID`, `name`, `default`) VALUES
(1, 'Default Group', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_group_users`
--

CREATE TABLE `user_group_users` (
  `ID` int(11) NOT NULL,
  `groupid` int(11) NOT NULL DEFAULT '0',
  `userid` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_notifications`
--

CREATE TABLE `user_notifications` (
  `ID` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `url` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `message` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `fromid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

CREATE TABLE `user_roles` (
  `ID` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `admin` int(11) NOT NULL DEFAULT '0',
  `admin_settings` int(11) NOT NULL DEFAULT '0',
  `admin_members` int(11) NOT NULL DEFAULT '0',
  `admin_payment` int(11) NOT NULL DEFAULT '0',
  `admin_announcements` int(11) NOT NULL,
  `banned` int(11) NOT NULL,
  `ticket_manager` int(11) NOT NULL,
  `ticket_worker` int(11) NOT NULL,
  `knowledge_manager` int(11) NOT NULL,
  `client` int(11) NOT NULL,
  `faq_manager` int(11) NOT NULL,
  `documentation_manager` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user_roles`
--

INSERT INTO `user_roles` (`ID`, `name`, `admin`, `admin_settings`, `admin_members`, `admin_payment`, `admin_announcements`, `banned`, `ticket_manager`, `ticket_worker`, `knowledge_manager`, `client`, `faq_manager`, `documentation_manager`) VALUES
(1, 'Admin', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(2, 'Member Manager', 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(3, 'Admin Settings', 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(4, 'Admin Payments', 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0),
(5, 'Member', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(6, 'Banned', 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0),
(7, 'Ticket Manager', 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0),
(8, 'Ticket Worker', 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0),
(9, 'Knowledge Manager', 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0),
(10, 'Client', 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0),
(11, 'FAQ Manager', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_role_permissions`
--

CREATE TABLE `user_role_permissions` (
  `ID` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `classname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `hook` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user_role_permissions`
--

INSERT INTO `user_role_permissions` (`ID`, `name`, `description`, `classname`, `hook`) VALUES
(1, 'ctn_308', 'ctn_308', 'admin', 'admin'),
(2, 'ctn_309', 'ctn_309', 'admin', 'admin_settings'),
(3, 'ctn_310', 'ctn_310', 'admin', 'admin_members'),
(4, 'ctn_311', 'ctn_311', 'admin', 'admin_payment'),
(5, 'ctn_33', 'ctn_33', 'banned', 'banned'),
(6, 'ctn_397', 'ctn_398', 'ticket', 'ticket_manager'),
(7, 'ctn_399', 'ctn_400', 'ticket', 'ticket_worker'),
(8, 'ctn_401', 'ctn_402', 'knowledge', 'knowledge_manager'),
(9, 'ctn_403', 'ctn_404', 'client', 'client'),
(11, 'ctn_744', 'ctn_745', 'knowledge', 'faq_manager'),
(12, 'ctn_806', 'ctn_807', 'knowledge', 'documentation_manager');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `canned_responses`
--
ALTER TABLE `canned_responses`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `ci_sessions`
--
ALTER TABLE `ci_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ci_sessions_timestamp` (`timestamp`);

--
-- Indexes for table `custom_fields`
--
ALTER TABLE `custom_fields`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `custom_statuses`
--
ALTER TABLE `custom_statuses`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `custom_views`
--
ALTER TABLE `custom_views`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `documentation_projects`
--
ALTER TABLE `documentation_projects`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `document_files`
--
ALTER TABLE `document_files`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `email_templates`
--
ALTER TABLE `email_templates`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `faq`
--
ALTER TABLE `faq`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `faq_categories`
--
ALTER TABLE `faq_categories`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `home_stats`
--
ALTER TABLE `home_stats`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `ipn_log`
--
ALTER TABLE `ipn_log`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `ip_block`
--
ALTER TABLE `ip_block`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `knowledge_articles`
--
ALTER TABLE `knowledge_articles`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `knowledge_categories`
--
ALTER TABLE `knowledge_categories`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `knowledge_groups`
--
ALTER TABLE `knowledge_groups`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `knowledge_votes`
--
ALTER TABLE `knowledge_votes`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `password_reset`
--
ALTER TABLE `password_reset`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `payment_logs`
--
ALTER TABLE `payment_logs`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `payment_plans`
--
ALTER TABLE `payment_plans`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `reset_log`
--
ALTER TABLE `reset_log`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `site_layouts`
--
ALTER TABLE `site_layouts`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `site_settings`
--
ALTER TABLE `site_settings`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `ticket_categories`
--
ALTER TABLE `ticket_categories`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `ticket_category_groups`
--
ALTER TABLE `ticket_category_groups`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `ticket_custom_fields`
--
ALTER TABLE `ticket_custom_fields`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `ticket_custom_field_cats`
--
ALTER TABLE `ticket_custom_field_cats`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `ticket_files`
--
ALTER TABLE `ticket_files`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `ticket_history`
--
ALTER TABLE `ticket_history`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `ticket_replies`
--
ALTER TABLE `ticket_replies`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `ticket_user_custom_fields`
--
ALTER TABLE `ticket_user_custom_fields`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `user_custom_fields`
--
ALTER TABLE `user_custom_fields`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `user_events`
--
ALTER TABLE `user_events`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `user_groups`
--
ALTER TABLE `user_groups`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `user_group_users`
--
ALTER TABLE `user_group_users`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `user_notifications`
--
ALTER TABLE `user_notifications`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `user_role_permissions`
--
ALTER TABLE `user_role_permissions`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `canned_responses`
--
ALTER TABLE `canned_responses`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `custom_fields`
--
ALTER TABLE `custom_fields`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `custom_statuses`
--
ALTER TABLE `custom_statuses`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `custom_views`
--
ALTER TABLE `custom_views`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `documentation_projects`
--
ALTER TABLE `documentation_projects`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `document_files`
--
ALTER TABLE `document_files`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `email_templates`
--
ALTER TABLE `email_templates`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `faq`
--
ALTER TABLE `faq`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `faq_categories`
--
ALTER TABLE `faq_categories`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `home_stats`
--
ALTER TABLE `home_stats`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `ipn_log`
--
ALTER TABLE `ipn_log`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ip_block`
--
ALTER TABLE `ip_block`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `knowledge_articles`
--
ALTER TABLE `knowledge_articles`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `knowledge_categories`
--
ALTER TABLE `knowledge_categories`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `knowledge_groups`
--
ALTER TABLE `knowledge_groups`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `knowledge_votes`
--
ALTER TABLE `knowledge_votes`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `password_reset`
--
ALTER TABLE `password_reset`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `payment_logs`
--
ALTER TABLE `payment_logs`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `payment_plans`
--
ALTER TABLE `payment_plans`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `reset_log`
--
ALTER TABLE `reset_log`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `site_layouts`
--
ALTER TABLE `site_layouts`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `site_settings`
--
ALTER TABLE `site_settings`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ticket_categories`
--
ALTER TABLE `ticket_categories`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `ticket_category_groups`
--
ALTER TABLE `ticket_category_groups`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ticket_custom_fields`
--
ALTER TABLE `ticket_custom_fields`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ticket_custom_field_cats`
--
ALTER TABLE `ticket_custom_field_cats`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ticket_files`
--
ALTER TABLE `ticket_files`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ticket_history`
--
ALTER TABLE `ticket_history`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ticket_replies`
--
ALTER TABLE `ticket_replies`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ticket_user_custom_fields`
--
ALTER TABLE `ticket_user_custom_fields`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_custom_fields`
--
ALTER TABLE `user_custom_fields`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_events`
--
ALTER TABLE `user_events`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_groups`
--
ALTER TABLE `user_groups`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `user_group_users`
--
ALTER TABLE `user_group_users`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_notifications`
--
ALTER TABLE `user_notifications`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_roles`
--
ALTER TABLE `user_roles`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `user_role_permissions`
--
ALTER TABLE `user_role_permissions`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
