CREATE TABLE users (
  username varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL, 
  password varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL, 
  email VARCHAR(100) NOT NULL UNIQUE KEY, 
  id INT NOT NULL AUTO_INCREMENT, 
  Unique KEY (id), 
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP, 
  count INT DEFAULT 0, 
  email_verification_link VARCHAR(255) NOT NULL, 
  email_verified_at TIMESTAMP NULL, 
  ans VARCHAR(255), 
  ques INT, 
  tfaen INT, 
  tfa VARCHAR(255), 
  PRIMARY KEY (username)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
CREATE TABLE IF NOT EXISTS all_login_attempts (
  username VARCHAR(50) NOT NULL, 
  password VARCHAR(255) NOT NULL, 
  attempt_date DATETIME, 
  ip VARCHAR(255)
);
CREATE TABLE IF NOT EXISTS login_attempts LIKE all_login_attempts;
CREATE TABLE IF NOT EXISTS password_reset_temp (
  email VARCHAR(250), 
  keyTO VARCHAR(255), 
  expD DATETIME
);
CREATE TABLE IF NOT EXISTS searches (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT, 
  username VARCHAR(50), 
  destination VARCHAR(100), 
  keyword VARCHAR(50), 
  PRIMARY KEY (`id`)
);