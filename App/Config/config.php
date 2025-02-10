<?php

CONST DB_HOST = 'localhost';
CONST DB_USER = 'postgres';
CONST DB_PASS = 'toor';
CONST DB_NAME = 'eventbrite';
CONST DB_PORT = '5432';

CONST TABLES = "
-------------------------------------------------
-- ENUMs
-------------------------------------------------
CREATE TYPE user_role AS ENUM ('participant', 'organisateur', 'admin');
CREATE TYPE event_state AS ENUM ('en attente', 'validé', 'terminé');
CREATE TYPE ticket_type AS ENUM ('standard', 'VIP', 'early bird');
CREATE TYPE ticket_status AS ENUM ('actif', 'annulé');
CREATE TYPE payment_method AS ENUM ('PayPal', 'Stripe');
CREATE TYPE payment_status AS ENUM ('réussi', 'échoué', 'remboursé');
CREATE TYPE report_status AS ENUM ('en attente', 'traité');

-------------------------------------------------
-- Table des utilisateurs
-------------------------------------------------
CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    role user_role NOT NULL DEFAULT 'participant',
    avatar VARCHAR(255),
    date_inscription TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-------------------------------------------------
-- Table des catégories
-------------------------------------------------
CREATE TABLE categories (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(100) NOT NULL UNIQUE
);

-------------------------------------------------
-- Table des événements
-------------------------------------------------
CREATE TABLE events (
    id SERIAL PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    date_debut TIMESTAMP NOT NULL,
    date_fin TIMESTAMP NOT NULL,
    lieu VARCHAR(255) NOT NULL,
    prix DECIMAL(10,2) CHECK (prix >= 0),
    capacite_max INTEGER NOT NULL CHECK (capacite_max > 0),
    organisateur_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
    etat event_state NOT NULL DEFAULT 'en attente',
    image_promotionnelle VARCHAR(255),
    categorie_id INTEGER NOT NULL REFERENCES categories(id) ON DELETE CASCADE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-------------------------------------------------
-- Table des tags et relation avec events
-------------------------------------------------
CREATE TABLE tags (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE events_tags (
    event_id INTEGER REFERENCES events(id) ON DELETE CASCADE,
    tag_id INTEGER REFERENCES tags(id) ON DELETE CASCADE,
    PRIMARY KEY (event_id, tag_id)
);

-------------------------------------------------
-- Table des billets et réservations
-------------------------------------------------
CREATE TABLE tickets (
    id SERIAL PRIMARY KEY,
    event_id INTEGER NOT NULL REFERENCES events(id) ON DELETE CASCADE,
    participant_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    type_ticket ticket_type NOT NULL DEFAULT 'standard',
    prix DECIMAL(10,2) NOT NULL CHECK (prix >= 0),
    statut ticket_status NOT NULL DEFAULT 'actif',
    qr_code VARCHAR(255),
    date_reservation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-------------------------------------------------
-- Table des paiements
-------------------------------------------------
CREATE TABLE payments (
    id SERIAL PRIMARY KEY,
    ticket_id INTEGER NOT NULL REFERENCES tickets(id) ON DELETE CASCADE,
    montant DECIMAL(10,2) NOT NULL CHECK (montant >= 0), -- ✅ Vérification du montant positif
    methode_paiement payment_method NOT NULL,
    statut payment_status NOT NULL DEFAULT 'réussi',
    date_paiement TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-------------------------------------------------
-- Table des codes promotionnels et relation avec tickets
-------------------------------------------------
CREATE TABLE promo_codes (
    id SERIAL PRIMARY KEY,
    code VARCHAR(50) UNIQUE NOT NULL,
    reduction DECIMAL(10,2) NOT NULL CHECK (reduction >= 0 AND reduction <= 100),
    expiration TIMESTAMP NOT NULL,
    event_id INTEGER REFERENCES events(id) ON DELETE SET NULL
);

CREATE TABLE tickets_promo (
    ticket_id INTEGER REFERENCES tickets(id) ON DELETE CASCADE,
    promo_code_id INTEGER REFERENCES promo_codes(id) ON DELETE CASCADE,
    PRIMARY KEY (ticket_id, promo_code_id)
);

-------------------------------------------------
-- Tables des statistiques (views)
-------------------------------------------------
CREATE VIEW event_stats AS
SELECT
    e.id AS event_id,
    e.titre,
    COUNT(t.id) AS nombre_participants,
    SUM(p.montant) AS revenus_total
FROM events e
LEFT JOIN tickets t ON e.id = t.event_id
LEFT JOIN payments p ON t.id = p.ticket_id
GROUP BY e.id;

CREATE VIEW user_stats AS
SELECT
    u.id AS user_id,
    u.nom,
    COUNT(DISTINCT e.id) AS nombre_evenements_crees,
    COUNT(DISTINCT t.id) AS tickets_achetes
FROM users u
LEFT JOIN events e ON u.id = e.organisateur_id
LEFT JOIN tickets t ON u.id = t.participant_id
GROUP BY u.id;

CREATE TABLE comments (
    id SERIAL PRIMARY KEY,
    user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    event_id INTEGER NOT NULL REFERENCES events(id) ON DELETE CASCADE,
    contenu TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
-------------------------------------------------
-- Table des signalements et modération (back-office)
-------------------------------------------------
CREATE TABLE reports (
    id SERIAL PRIMARY KEY,
    user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    event_id INTEGER REFERENCES events(id) ON DELETE SET NULL,
    commentaire_id INTEGER REFERENCES comments(id) ON DELETE CASCADE,
    motif TEXT NOT NULL,
    statut report_status NOT NULL DEFAULT 'en attente',
    admin_id INTEGER REFERENCES users(id) ON DELETE SET NULL
);
";