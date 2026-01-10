CREATE DATABASE IF NOT EXISTS wypozyczalnia CHARACTER SET utf8mb4 COLLATE utf8mb4_polish_ci;
USE wypozyczalnia;

CREATE TABLE klient (
    id_klient INT AUTO_INCREMENT PRIMARY KEY,
    imie VARCHAR(50) NOT NULL,
    nazwisko VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    telefon VARCHAR(20) NOT NULL
);

CREATE TABLE produkt (
    id_produkt INT AUTO_INCREMENT PRIMARY KEY,
    nazwa VARCHAR(100) NOT NULL,
    opis TEXT,
    cena_dzien DECIMAL(10,2) NOT NULL CHECK (cena_dzien >= 0),
    dostepny TINYINT(1) NOT NULL DEFAULT 1,
    image_url VARCHAR(255) NOT NULL
);

CREATE TABLE wypozyczenie (
    id_wypozyczenie INT AUTO_INCREMENT PRIMARY KEY,
    id_klient INT NOT NULL,
    id_produkt INT NOT NULL,
    data_wypoze DATE NOT NULL,
    data_zwrotu DATE DEFAULT NULL,
    koszt DECIMAL(10,2) DEFAULT NULL,
    CONSTRAINT fk_wypozyczenie_klient FOREIGN KEY (id_klient) REFERENCES klient(id_klient),
    CONSTRAINT fk_wypozyczenie_produkt FOREIGN KEY (id_produkt) REFERENCES produkt(id_produkt)
);

INSERT INTO produkt (nazwa, opis, cena_dzien, dostepny, image_url) VALUES
('Toyota Corolla', 'Ekonomiczny sedan idealny do miasta.', 180.00, 1, 'https://images.unsplash.com/photo-1472214103451-9374bd1c798e?auto=format&fit=crop&w=800&q=80'),
('BMW 3 Series', 'Komfortowy sedan klasy premium.', 420.00, 1, 'https://images.unsplash.com/photo-1555215695-3004980ad54e?auto=format&fit=crop&w=800&q=80'),
('Volkswagen Golf', 'Hatchback idealny do codziennych przejazdow.', 210.00, 1, 'https://images.unsplash.com/photo-1449965408869-eaa3f722e40d?auto=format&fit=crop&w=800&q=80'),
('Skoda Octavia', 'Przestronny sedan rodzinny.', 230.00, 1, 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=800&q=80'),
('Audi A4', 'Elegancki sedan biznesowy.', 390.00, 1, 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&w=800&q=80'),
('Hyundai Tucson', 'SUV z duzym bagaznikiem.', 360.00, 1, 'https://images.unsplash.com/photo-1502877338535-766e1452684a?auto=format&fit=crop&w=800&q=80');
