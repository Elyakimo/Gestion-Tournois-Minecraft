-- ============================================
-- PostgreSQL Database Schema — Tournoi Minecraft
-- ============================================

CREATE EXTENSION IF NOT EXISTS "pgcrypto";

-- --------------------------------------------
-- EQUIPE (créée en premier, référencée par JOUEUR)
-- --------------------------------------------
CREATE TABLE equipe (
  id_equipe   UUID PRIMARY KEY DEFAULT gen_random_uuid(),
  nom_equipe  VARCHAR(255) NOT NULL,
  tag         VARCHAR(10)
);

-- --------------------------------------------
-- JOUEUR
-- --------------------------------------------
CREATE TABLE joueur (
  id_joueur       UUID PRIMARY KEY DEFAULT gen_random_uuid(),
  pseudo          VARCHAR(255) NOT NULL,
  email           VARCHAR(255) UNIQUE,
  date_naissance  DATE,
  id_equipe       UUID,
  password_hash   VARCHAR(255) NOT NULL,
  CONSTRAINT fk_joueur_equipe FOREIGN KEY (id_equipe)
    REFERENCES equipe(id_equipe) ON DELETE SET NULL
);

-- --------------------------------------------
-- TOURNOI
-- --------------------------------------------
CREATE TABLE tournoi (
  id_tournoi    UUID PRIMARY KEY DEFAULT gen_random_uuid(),
  nom_tournoi   VARCHAR(255) NOT NULL,
  date_debut    DATE,
  date_fin      DATE,
  format_jeu    VARCHAR(100)
);

-- --------------------------------------------
-- CARTE
-- --------------------------------------------
CREATE TABLE carte (
  id_carte      UUID PRIMARY KEY DEFAULT gen_random_uuid(),
  nom_carte     VARCHAR(255) NOT NULL,
  type_terrain  VARCHAR(100)
);

-- --------------------------------------------
-- ARBITRE
-- --------------------------------------------
CREATE TABLE arbitre (
  id_arbitre      UUID PRIMARY KEY DEFAULT gen_random_uuid(),
  nom             VARCHAR(255) NOT NULL,
  pseudo_arbitre  VARCHAR(255)
);

-- --------------------------------------------
-- MATCH (dépend de TOURNOI, CARTE, ARBITRE)
-- --------------------------------------------
CREATE TABLE match (
  id_match      UUID PRIMARY KEY DEFAULT gen_random_uuid(),
  date_match    DATE,
  heure_match   TIME,
  statut        VARCHAR(50),
  id_tournoi    UUID NOT NULL,
  id_carte      UUID,
  id_arbitre    UUID,
  CONSTRAINT fk_match_tournoi FOREIGN KEY (id_tournoi)
    REFERENCES tournoi(id_tournoi) ON DELETE CASCADE,
  CONSTRAINT fk_match_carte FOREIGN KEY (id_carte)
    REFERENCES carte(id_carte) ON DELETE SET NULL,
  CONSTRAINT fk_match_arbitre FOREIGN KEY (id_arbitre)
    REFERENCES arbitre(id_arbitre) ON DELETE SET NULL
);

-- --------------------------------------------
-- INSCRIPTION (table de jonction EQUIPE <-> TOURNOI)
-- --------------------------------------------
CREATE TABLE inscription (
  id_equipe         UUID NOT NULL,
  id_tournoi        UUID NOT NULL,
  date_inscription  DATE DEFAULT CURRENT_DATE,
  classement_final  INT,
  points            INT DEFAULT 0,
  PRIMARY KEY (id_equipe, id_tournoi),
  CONSTRAINT fk_inscription_equipe FOREIGN KEY (id_equipe)
    REFERENCES equipe(id_equipe) ON DELETE CASCADE,
  CONSTRAINT fk_inscription_tournoi FOREIGN KEY (id_tournoi)
    REFERENCES tournoi(id_tournoi) ON DELETE CASCADE
);

-- --------------------------------------------
-- PARTICIPATION (table de jonction MATCH <-> EQUIPE)
-- --------------------------------------------
CREATE TABLE participation (
  id_match   UUID NOT NULL,
  id_equipe  UUID NOT NULL,
  score      INT DEFAULT 0,
  resultat   VARCHAR(20),
  PRIMARY KEY (id_match, id_equipe),
  CONSTRAINT fk_participation_match FOREIGN KEY (id_match)
    REFERENCES match(id_match) ON DELETE CASCADE,
  CONSTRAINT fk_participation_equipe FOREIGN KEY (id_equipe)
    REFERENCES equipe(id_equipe) ON DELETE CASCADE
);

CREATE TABLE RESULTAT_MATCH (
  id_match UUID PRIMARY KEY,
  score_equipe1 INT DEFAULT 0,
  score_equipe2 INT DEFAULT 0,
  resultat VARCHAR(20),
  CONSTRAINT fk_resultat_match FOREIGN KEY (id_match)
    REFERENCES match(id_match) ON DELETE CASCADE
);

-- ============================================
-- Index suggérés pour les clés étrangères
-- ============================================
CREATE INDEX IF NOT EXISTS idx_joueur_id_equipe ON joueur(id_equipe);
CREATE INDEX IF NOT EXISTS idx_match_id_tournoi ON match(id_tournoi);
CREATE INDEX IF NOT EXISTS idx_match_id_carte ON match(id_carte);
CREATE INDEX IF NOT EXISTS idx_match_id_arbitre ON match(id_arbitre);
CREATE INDEX IF NOT EXISTS idx_inscription_id_tournoi ON inscription(id_tournoi);
CREATE INDEX IF NOT EXISTS idx_participation_id_equipe ON participation(id_equipe);
