CREATE DATABASE DarDark;
use DarDark;
show DATABASES;
show TABLEs;
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY, 

    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,

    role ENUM('TRAVELER', 'HOST', 'ADMIN') NOT NULL DEFAULT 'TRAVELER',
    status ENUM('ACTIVE', 'BANNED') NOT NULL DEFAULT 'ACTIVE',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE rentals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    host_id INT NOT NULL,
    title VARCHAR(150) NOT NULL,
    city VARCHAR(100) NOT NULL,
    address VARCHAR(255) NOT NULL,
    price_per_night DECIMAL(10,2) NOT NULL,
    max_guests INT NOT NULL,    
    description TEXT,
    status ENUM('ACTIVE','INACTIVE') DEFAULT 'ACTIVE',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_rentals_host
        FOREIGN KEY (host_id) REFERENCES users(id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


ALTER Table rentals ADD COLUMN cover_path VARCHAR(255) ;


select * from rentals;

CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,

    rental_id INT NOT NULL,
    user_id INT NOT NULL,

    start_date DATE NOT NULL,
    end_date DATE NOT NULL,

    total_price DECIMAL(10,2) NOT NULL,

    status ENUM('PENDING','CONFIRMED','CANCELLED') 
           NOT NULL DEFAULT 'PENDING',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_booking_rental
        FOREIGN KEY (rental_id) REFERENCES rentals(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_booking_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE
);
CREATE TABLE favorites (
    id INT AUTO_INCREMENT PRIMARY KEY,

    user_id INT NOT NULL,
    rental_id INT NOT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    UNIQUE (user_id, rental_id),

    CONSTRAINT fk_fav_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_fav_rental
        FOREIGN KEY (rental_id) REFERENCES rentals(id)
        ON DELETE CASCADE
);
CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,

    rental_id INT NOT NULL,
    user_id INT NOT NULL,

    rating TINYINT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    comment TEXT,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_review_rental
        FOREIGN KEY (rental_id) REFERENCES rentals(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_review_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE
);


SELECT * FROM rentals WHERE status = 'ACTIVE' and id = 6 ;

SELECT * from rentals INNER JOIN users on rentals.host_id = users.id WHERE rentals.id = 5;
ALTER TABLE users
ADD COLUMN is_active TINYINT(1) NOT NULL DEFAULT 1;
ALTER TABLE rentals
ADD COLUMN is_active TINYINT(1) NOT NULL DEFAULT 1;
