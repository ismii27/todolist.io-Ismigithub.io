CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255),
    password VARCHAR(255)
);

CREATE TABLE todos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    judul TEXT,
    deadline DATETIME,
    status ENUM('pending','done') DEFAULT 'pending',
    total VARCHAR,
    title VARCHAR
);