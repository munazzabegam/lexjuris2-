-- create database
CREATE DATABASE IF NOT EXISTS lex_juris;
USE lex_juris;


-- admin_users table
CREATE TABLE admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash TEXT NOT NULL,
    profile_image VARCHAR(500),
    is_active BOOLEAN NOT NULL DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL
);

-- cases table
CREATE TABLE cases (
    id INT AUTO_INCREMENT PRIMARY KEY,
    case_number VARCHAR(50) NOT NULL UNIQUE,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    link TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    status ENUM('Open', 'Closed', 'In Progress') NOT NULL DEFAULT 'Open',
    category ENUM('criminal', 'family', 'cheque', 'consumer','labour','high court', 'supreme court','other'),
    author_id INT,
    author_name VARCHAR(100),
    tags TEXT,
    order_index INT DEFAULT 0,
    
    FOREIGN KEY (author_id) REFERENCES admin_users(id),
    FOREIGN KEY (author_name) REFERENCES admin_users(username)
);


-- articles table
CREATE TABLE articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    content LONGTEXT NOT NULL,
    summary TEXT,
    author_id INT NOT NULL,
    cover_image VARCHAR(2083),
    tags TEXT,
    category ENUM('criminal', 'family', 'cheque', 'consumer','labour','high court', 'supreme court','other'),
    published_at TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    status ENUM('draft', 'published') NOT NULL DEFAULT 'draft',
    external_link VARCHAR(2083) DEFAULT NULL,
    order_index INT DEFAULT 0,

    FOREIGN KEY (author_id) REFERENCES admin_users(id)
);

CREATE TABLE article_social_links (
    id INT AUTO_INCREMENT PRIMARY KEY,
    article_id INT NOT NULL,
    platform ENUM('linkedin', 'twitter', 'facebook', 'instagram', 'youtube', 'pinterest', 'reddit', 'other') NOT NULL,
    url VARCHAR(2083) NOT NULL,
    FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE
);



-- faq table
CREATE TABLE faq (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question TEXT NOT NULL,
    answer TEXT NOT NULL,
    order_index INT DEFAULT 0,
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    author_id INT,
    author_name VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (author_id) REFERENCES admin_users(id),
    FOREIGN KEY (author_name) REFERENCES admin_users(username)
);

-- testimonials table
CREATE TABLE testimonials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT DEFAULT NULL,
    name VARCHAR(255) NOT NULL,
    position VARCHAR(255),
    company VARCHAR(255),
    photo VARCHAR(500),
    testimonial TEXT NOT NULL,
    order_index INT DEFAULT 0,
    date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    FOREIGN KEY (user_id) REFERENCES admin_users(id)
);

-- social_links table
CREATE TABLE social_links (
    id INT AUTO_INCREMENT PRIMARY KEY,
    platform ENUM('Facebook', 'Twitter', 'Instagram', 'LinkedIn', 'YouTube', 'GitHub', 'Other') NOT NULL,
    url VARCHAR(255) NOT NULL,
    order_index INT DEFAULT 0,
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- team_members table
CREATE TABLE team_members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(255) NOT NULL,
    position VARCHAR(255) NOT NULL,
    bio TEXT,
    photo VARCHAR(255) NOT NULL,
    portfolio VARCHAR(255),
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    order_index INT DEFAULT 0
);

CREATE TABLE team_social_links (
    id INT AUTO_INCREMENT PRIMARY KEY,
    team_id INT NOT NULL,
    platform ENUM('LinkedIn', 'Twitter', 'Email', 'Facebook', 'Instagram', 'GitHub', 'Other') NOT NULL,
    url VARCHAR(255) NOT NULL,
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_team
      FOREIGN KEY (team_id)
      REFERENCES team_members(id)
      ON DELETE CASCADE
);

CREATE TABLE sub_junior_team_members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(255) NOT NULL,
    position VARCHAR(255) NOT NULL,
    bio TEXT,
    photo VARCHAR(255) NOT NULL,
    portfolio VARCHAR(255),
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    order_index INT DEFAULT 0
);

CREATE TABLE sub_junior_social_links (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sub_junior_id INT NOT NULL,
    platform ENUM('LinkedIn', 'Twitter', 'Email', 'Facebook', 'Instagram', 'GitHub', 'Other') NOT NULL,
    url VARCHAR(255) NOT NULL,
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_sub_junior
      FOREIGN KEY (sub_junior_id)
      REFERENCES sub_junior_team_members(id)
      ON DELETE CASCADE
);

-- gallery table
CREATE TABLE gallery (
    id INT AUTO_INCREMENT PRIMARY KEY,
    image VARCHAR(255) NOT NULL,
    uploaded_by VARCHAR(255),
    order_index INT DEFAULT 0,
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- contact table
CREATE TABLE contact (
    id INT AUTO_INCREMENT PRIMARY KEY,
    phone VARCHAR(20),
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- disclaimer_agreements table
CREATE TABLE disclaimer_agreements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ip_address VARCHAR(45) NOT NULL,
    session_id VARCHAR(255) NOT NULL,
    user_agent TEXT,
    location VARCHAR(255),
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE `achievements` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `number_value` INT NOT NULL,
    `label` VARCHAR(255) NOT NULL,
    `order_index` INT DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
