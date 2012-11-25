CREATE TABLE `user_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `slug` varchar(30) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT `user_groups` (`name`, `slug`, `description`) VALUES ("Banned", "banned", "Banned users");
INSERT `user_groups` (`name`, `slug`, `description`) VALUES ("Member", "member", "Registered users");
INSERT `user_groups` (`name`, `slug`, `description`) VALUES ("Manager", "manager", "Content managers");
INSERT `user_groups` (`name`, `slug`, `description`) VALUES ("Admin", "admin", "Site administrators");
INSERT `user_groups` (`name`, `slug`, `description`) VALUES ("Root", "root", "Site owner");

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(10) NOT NULL,
  `password` varchar(105) NOT NULL,
  `email` varchar(105) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `user_group_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`,`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `about_users` (
  `user_id` int(11) NOT NULL,
  `fullname` varchar(105) DEFAULT NULL,
  `homepage` varchar(105) DEFAULT NULL,
  `about` text,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `password_resets` (
  `user_id` int(11) NOT NULL,
  `expires` datetime NOT NULL,
  `token` varchar(100) NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `token` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `user_activations` (
  `user_id` int(11) NOT NULL,
  `token` varchar(100) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

