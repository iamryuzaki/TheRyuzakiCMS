CREATE TABLE `accounts` (
  `id` int(11) NOT NULL,
  `login` varchar(32) NOT NULL DEFAULT '',
  `password` varchar(32) NOT NULL DEFAULT '',
  `email` varchar(64) NOT NULL DEFAULT '',
  `phone` varchar(32) NOT NULL DEFAULT '',
  `session` varchar(32) NOT NULL DEFAULT '',
  `language` int(11) NOT NULL DEFAULT '1',
  `group` int(11) NOT NULL DEFAULT '1',
  `balance` int(11) NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `accounts_information_login` (
  `id` int(11) NOT NULL,
  `id_account` int(11) NOT NULL,
  `useragent` text NOT NULL,
  `time` datetime NOT NULL DEFAULT '2000-01-01 00:00:00',
  `ip` varchar(15) NOT NULL DEFAULT '0.0.0.0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `accounts_information_registration` (
  `id_account` int(11) NOT NULL,
  `id_parent` int(11) NOT NULL DEFAULT '0',
  `login` varchar(32) NOT NULL,
  `email` varchar(64) NOT NULL,
  `email_active` tinyint(1) NOT NULL DEFAULT '0',
  `useragent` text NOT NULL,
  `time` datetime NOT NULL DEFAULT '2000-01-01 00:00:00',
  `ip` varchar(15) NOT NULL DEFAULT '0.0.0.0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `groups` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `active` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO `groups` (`id`, `name`, `active`) VALUES
(1, 'User', 0);

CREATE TABLE `groups_access` (
  `id_group` int(11) NOT NULL DEFAULT '0',
  `key` varchar(264) NOT NULL,
  `value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `languages` (
  `id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `image` text NOT NULL,
  `default` tinyint(1) NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `languages_translate` (
  `id_language` int(11) NOT NULL,
  `key` varchar(264) NOT NULL,
  `value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `sessions` (
  `session` varchar(32) NOT NULL,
  `useragent` text NOT NULL,
  `time` datetime NOT NULL DEFAULT '2000-01-01 00:00:00',
  `ip` varchar(15) NOT NULL DEFAULT '0.0.0.0',
  `location` varchar(264) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `accounts_information_login`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `accounts_information_registration`
  ADD PRIMARY KEY (`id_account`);

ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `groups_access`
  ADD PRIMARY KEY (`id_group`,`key`);

ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `languages_translate`
  ADD PRIMARY KEY (`id_language`,`key`);

ALTER TABLE `sessions`
  ADD PRIMARY KEY (`session`);

ALTER TABLE `accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `accounts_information_login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `languages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;