use donttrip;
CREATE TABLE IF NOT EXISTS users (
  id INT NOT NULL AUTO_INCREMENT,
  username VARCHAR(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,  
  password VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL, 
  email VARCHAR(100) NOT NULL UNIQUE KEY, 
  last_login DATETIME DEFAULT CURRENT_TIMESTAMP, 
  email_verification_link VARCHAR(255) NOT NULL, 
  email_verified_at TIMESTAMP NULL, 
  tfaen INT, 
  tfa VARCHAR(255), 
  UNIQUE KEY (id), 
  PRIMARY KEY (username)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
CREATE TABLE IF NOT EXISTS all_login_attempts (
  username VARCHAR(50) NOT NULL, 
  password VARCHAR(255) NOT NULL, 
  attempt_date DATETIME, 
  ip VARCHAR(255)
);
CREATE TABLE IF NOT EXISTS failed_login_attempts (
  id INT NOT NULL AUTO_INCREMENT,
  ip VARBINARY(16) NOT NULL,
  attempt_time BIGINT NOT NULL,
  username VARCHAR(255), 
  otp VARCHAR(255), 
  PRIMARY KEY (id)
);
CREATE TABLE IF NOT EXISTS page_visits (
  browser VARCHAR(255) NOT NULL, 
  visit_date DATETIME, 
  ip VARCHAR(255)
);
CREATE TABLE IF NOT EXISTS password_reset_temp (
  email VARCHAR(250), 
  keyTO VARCHAR(255), 
  expD DATETIME,
  sent_time BIGINT
);
CREATE TABLE IF NOT EXISTS searches (
  id int(10) unsigned NOT NULL AUTO_INCREMENT, 
  username VARCHAR(50), 
  destination VARCHAR(100), 
  keyword VARCHAR(50), 
  PRIMARY KEY (id)
);