CREATE DATABASE IF NOT EXISTS student_accommodation
CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE student_accommodation;

DROP TABLE IF EXISTS interested_users;
DROP TABLE IF EXISTS property_amenities;
DROP TABLE IF EXISTS amenities;
DROP TABLE IF EXISTS properties;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE properties (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    city VARCHAR(100) NOT NULL,
    address VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    gender ENUM('Male','Female','Any') NOT NULL DEFAULT 'Any',
    rating DECIMAL(2,1) NOT NULL DEFAULT 4.0,
    description TEXT NOT NULL,
    image VARCHAR(500) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_city(city),
    INDEX idx_price(price),
    INDEX idx_gender(gender)
) ENGINE=InnoDB;

CREATE TABLE amenities (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE property_amenities (
    property_id INT UNSIGNED NOT NULL,
    amenity_id INT UNSIGNED NOT NULL,
    PRIMARY KEY(property_id, amenity_id),
    CONSTRAINT fk_pa_property FOREIGN KEY(property_id) REFERENCES properties(id) ON DELETE CASCADE,
    CONSTRAINT fk_pa_amenity FOREIGN KEY(amenity_id) REFERENCES amenities(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE interested_users (
    user_id INT UNSIGNED NOT NULL,
    property_id INT UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY(user_id, property_id),
    CONSTRAINT fk_iu_user FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_iu_property FOREIGN KEY(property_id) REFERENCES properties(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Demo password is: password
INSERT INTO users(name,email,password,phone) VALUES
('Demo Student','demo@example.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa7aQxQxXj6z4a2x7l4Qx3m1s5K','9876543210');

INSERT INTO amenities(name) VALUES
('Wi-Fi'),('AC'),('Food'),('Laundry'),('Power Backup'),('CCTV'),('Study Table'),('Parking');

INSERT INTO properties(name,city,address,price,gender,rating,description,image) VALUES
('Green View Student PG','Kolkata','Salt Lake, Sector V',8500,'Any',4.7,'Comfortable student PG close to colleges, metro connectivity and daily conveniences. Includes furnished rooms, Wi-Fi and food options.','https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=1000&q=80'),
('Campus Comfort PG','Bengaluru','Koramangala, Bengaluru',10500,'Female',4.5,'Secure and well-connected accommodation designed for students and young professionals.','https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=1000&q=80'),
('City Scholars Residence','Pune','Viman Nagar, Pune',9000,'Male',4.4,'Modern rooms with study spaces, reliable internet and convenient access to educational hubs.','https://images.unsplash.com/photo-1560185008-b033106af5c3?auto=format&fit=crop&w=1000&q=80'),
('Lakeview Student Homes','Hyderabad','Madhapur, Hyderabad',7800,'Any',4.6,'Budget-friendly PG with essential amenities and easy access to transport and restaurants.','https://images.unsplash.com/photo-1564013799919-ab600027ffc6?auto=format&fit=crop&w=1000&q=80'),
('Scholars Nest','Delhi','Laxmi Nagar, Delhi',7200,'Male',4.2,'Affordable student stay with furnished rooms, CCTV and study-friendly common spaces.','https://images.unsplash.com/photo-1493809842364-78817add7ffb?auto=format&fit=crop&w=1000&q=80'),
('Heritage Girls PG','Kolkata','Garia, Kolkata',6800,'Female',4.8,'Peaceful girls PG with strong security, food and excellent local connectivity.','https://images.unsplash.com/photo-1560185127-6ed189bf02f4?auto=format&fit=crop&w=1000&q=80');

INSERT INTO property_amenities(property_id,amenity_id) VALUES
(1,1),(1,2),(1,3),(1,5),(1,6),(1,7),
(2,1),(2,2),(2,3),(2,4),(2,6),(2,7),
(3,1),(3,3),(3,5),(3,6),(3,7),(3,8),
(4,1),(4,3),(4,4),(4,5),(4,6),
(5,1),(5,5),(5,6),(5,7),
(6,1),(6,3),(6,4),(6,5),(6,6),(6,7);
