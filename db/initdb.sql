-- Create DB
CREATE DATABASE IF NOT EXISTS smart_factory;
USE smart_factory;

-- User Table
CREATE TABLE users (
    UID VARCHAR(10) PRIMARY KEY,                
    username VARCHAR(50) NOT NULL UNIQUE,       
    password VARCHAR(255) NOT NULL,             
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    role ENUM('admin', 'operator', 'factory_manager', 'auditor') DEFAULT 'admin'
);

-- Machine Table
CREATE TABLE machines (
    machine_id INT AUTO_INCREMENT PRIMARY KEY,
    machine_name VARCHAR(100) NOT NULL UNIQUE
);

-- Log Table
CREATE TABLE logs (
    sl_no INT AUTO_INCREMENT PRIMARY KEY,
    machine_id INT,
    log_date DATE,                              
    log_time TIME,                              
    temperature DECIMAL(5, 2),
    pressure DECIMAL(5, 2),
    vibration DECIMAL(5, 2),
    humidity DECIMAL(5, 2),
    power_consumption DECIMAL(5, 2),
    operational_status VARCHAR(50),
    error_code VARCHAR(10),
    production_count INT,
    maintenance_log TEXT,
    speed DECIMAL(5, 2),
    FOREIGN KEY (machine_id) REFERENCES machines(machine_id) ON DELETE CASCADE
);

-- Job Table
CREATE TABLE jobs (
    job_id INT AUTO_INCREMENT PRIMARY KEY,
    machine_id INT,
    UID VARCHAR(10),
    status VARCHAR(50) NOT NULL,
    start_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    description TEXT,
    FOREIGN KEY (machine_id) REFERENCES machines(machine_id) ON DELETE CASCADE,
    FOREIGN KEY (UID) REFERENCES users(UID) ON DELETE CASCADE
);

-- Task Note Table
CREATE TABLE task_notes (
    note_id INT AUTO_INCREMENT PRIMARY KEY,
    job_id INT,
    manager_id VARCHAR(10),  
    user_id VARCHAR(10),     
    task_sub VARCHAR(100),
    task_body TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (job_id) REFERENCES jobs(job_id) ON DELETE CASCADE,
    FOREIGN KEY (manager_id) REFERENCES users(UID) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(UID) ON DELETE CASCADE
);


-- Notifications Table
CREATE TABLE activity_notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    UID VARCHAR(10) DEFAULT NULL,
    machine_id INT DEFAULT NULL,
    job_id INT DEFAULT NULL,
    type ENUM('activity', 'notification') NOT NULL, 
    status ENUM('aborted', 'started', 'completed', 'updated', 'online', 'offline', 'error') NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (UID) REFERENCES users(UID) ON DELETE SET NULL,
    FOREIGN KEY (machine_id) REFERENCES machines(machine_id) ON DELETE SET NULL,
    FOREIGN KEY (job_id) REFERENCES jobs(job_id) ON DELETE SET NULL
);


INSERT INTO users (UID, username, password, first_name, last_name, role)
VALUES 
    ('A001', 'admin', '$2y$10$xk2iACvABsJruVEeMaQfu..bBGpEO0CbriCh1f93KDjmrjEnvdD3C', 'Admin', 'Admin', 'admin'),
    ('AU01', 'anna.bell', '$2y$10$.NYansbUHfs0ywVG8RPkjeF55.AdxzCJ0BN4p72gNXjNb8U40zvx.', 'Anna', 'Bell', 'auditor'),
    ('F001', 'john.doe', '$2y$10$a.6mBNwsbGhlOgh78dM11u8qRcZM5hWV3PGWDrwHIz0aK5RlyTu9G', 'John', 'Doe', 'factory_manager'),
    ('F002', 'barry.allen', '$2y$10$TW0dn0W5qICgGJ1fdLmVh.GS7.cjp.78L457Bqt2f76eeCj4/wnUW', 'Barry', 'Allen', 'factory_manager'),
    ('U001', 'paul.augustine', '$2b$12$J1WyPKLL8dcZOZmOFQ.znOUV2/ioISVq8CjMqSpH2dNFOgsoqdx9q', 'Paul', 'Augustine', 'operator');
