<?php

CONST DB_HOST = 'localhost';
CONST DB_USER = 'postgres';
CONST DB_PASS = 'root';
CONST DB_NAME = 'eventbrite';
CONST DB_PORT = '5432';

CONST TABLES = "
-------------------------------------------------
-- ENUMs
-------------------------------------------------
CREATE TYPE user_role AS ENUM ('participant', 'organizer', 'admin');
CREATE TYPE event_state AS ENUM ('pending', 'approved', 'completed');
CREATE TYPE ticket_type AS ENUM ('standard', 'VIP', 'bird');
CREATE TYPE ticket_status AS ENUM ('active', 'canceled');
CREATE TYPE payment_method AS ENUM ('PayPal', 'Stripe');
CREATE TYPE payment_status AS ENUM ('successful', 'failed', 'refunded');
CREATE TYPE report_status AS ENUM ('pending', 'processed');
CREATE TYPE user_status AS ENUM ('active', 'banned');
-------------------------------------------------
-- Users Table
-------------------------------------------------
CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    status user_status NOT NULL DEFAULT 'active';
    role user_role NOT NULL DEFAULT 'participant',
    avatar VARCHAR(255),
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-------------------------------------------------
-- Categories Table
-------------------------------------------------
CREATE TABLE categories (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
);

-------------------------------------------------
-- Events Table
-------------------------------------------------
CREATE TABLE events (
    id SERIAL PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    start_date TIMESTAMP NOT NULL,
    end_date TIMESTAMP NOT NULL,
    location VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) CHECK (price >= 0),
    max_capacity INTEGER NOT NULL CHECK (max_capacity > 0),
    organizer_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
    state event_state NOT NULL DEFAULT 'pending',
    promotional_image VARCHAR(255),
    category_id INTEGER NOT NULL REFERENCES categories(id) ON DELETE CASCADE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-------------------------------------------------
-- Tags Table and Relationship with Events
-------------------------------------------------
CREATE TABLE tags (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE events_tags (
    event_id INTEGER REFERENCES events(id) ON DELETE CASCADE,
    tag_id INTEGER REFERENCES tags(id) ON DELETE CASCADE,
    PRIMARY KEY (event_id, tag_id)
);

-------------------------------------------------
-- Tickets and Reservations Table
-------------------------------------------------
CREATE TABLE tickets (
    id SERIAL PRIMARY KEY,
    event_id INTEGER NOT NULL REFERENCES events(id) ON DELETE CASCADE,
    participant_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    ticket_type ticket_type NOT NULL DEFAULT 'standard',
    price DECIMAL(10,2) NOT NULL CHECK (price >= 0),
    status ticket_status NOT NULL DEFAULT 'active',
    qr_code VARCHAR(255),
    reservation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-------------------------------------------------
-- Payments Table
-------------------------------------------------
CREATE TABLE payments (
    id SERIAL PRIMARY KEY,
    ticket_id INTEGER NOT NULL REFERENCES tickets(id) ON DELETE CASCADE,
    amount DECIMAL(10,2) NOT NULL CHECK (amount >= 0),
    payment_method payment_method NOT NULL,
    status payment_status NOT NULL DEFAULT 'successful',
    payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-------------------------------------------------
-- Promo Codes Table and Relationship with Tickets
-------------------------------------------------
    CREATE TABLE promo_codes (
        id SERIAL PRIMARY KEY,
        code VARCHAR(50) UNIQUE NOT NULL,
        discount DECIMAL(10,2) NOT NULL CHECK (discount >= 0 AND discount <= 100),
        expiration TIMESTAMP NOT NULL,
        event_id INTEGER REFERENCES events(id) ON DELETE SET NULL
    );

    CREATE TABLE tickets_promo (
        ticket_id INTEGER REFERENCES tickets(id) ON DELETE CASCADE,
        promo_code_id INTEGER REFERENCES promo_codes(id) ON DELETE CASCADE,
        PRIMARY KEY (ticket_id, promo_code_id)
    );

-------------------------------------------------
-- Statistics Views
-------------------------------------------------
CREATE VIEW event_stats AS
SELECT
    e.id AS event_id,
    e.title,
    COUNT(t.id) AS participant_count,
    SUM(p.amount) AS total_revenue
FROM events e
LEFT JOIN tickets t ON e.id = t.event_id
LEFT JOIN payments p ON t.id = p.ticket_id
GROUP BY e.id;

CREATE VIEW user_stats AS
SELECT
    u.id AS user_id,
    u.name,
    COUNT(DISTINCT e.id) AS events_created,
    COUNT(DISTINCT t.id) AS tickets_purchased
FROM users u
LEFT JOIN events e ON u.id = e.organizer_id
LEFT JOIN tickets t ON u.id = t.participant_id
GROUP BY u.id;

-------------------------------------------------
-- Comments Table
-------------------------------------------------
CREATE TABLE comments (
    id SERIAL PRIMARY KEY,
    user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    event_id INTEGER NOT NULL REFERENCES events(id) ON DELETE CASCADE,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-------------------------------------------------
-- Reports and Moderation Table (Back-office)
-------------------------------------------------
CREATE TABLE reports (
    id SERIAL PRIMARY KEY,
    user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    event_id INTEGER REFERENCES events(id) ON DELETE SET NULL,
    comment_id INTEGER REFERENCES comments(id) ON DELETE CASCADE,
    reason TEXT NOT NULL,
    status report_status NOT NULL DEFAULT 'pending',
    admin_id INTEGER REFERENCES users(id) ON DELETE SET NULL
);
";