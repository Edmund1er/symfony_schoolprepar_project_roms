-- ========== INSERTION DES FILIERES (si pas déjà faites) ==========
INSERT IGNORE INTO filiere (nom, description) VALUES 
('Informatique', 'Développement web, mobile, IA, cybersécurité'),
('Mathématiques', 'Analyse, algèbre, statistiques, probabilités'),
('Physique', 'Mécanique, électricité, thermodynamique'),
('Droit', 'Droit des affaires, droit international'),
('Gestion', 'Marketing, finance, RH, comptabilité');

-- ========== INSERTION DES ETABLISSEMENTS ==========
INSERT IGNORE INTO etablissement (nom, adresse, email) VALUES 
('Université de Lomé', 'Boulevard du 13 Janvier, Lomé, Togo', 'contact@univ-lome.tg'),
('Université de Kara', 'Kara, Togo', 'rectorat@univ-kara.tg'),
('Université de Dapaong', 'Dapaong, Savanes, Togo', 'contact@univ-dapaong.tg'),
('IPNET Institute of Technology', 'Avenue Sarakawa, Lomé, Togo', 'info@ipnet.tg'),
('ESTBA', 'Lomé, Togo', 'info@estba.tg');

-- ========== INSERTION DES EVENEMENTS ==========
INSERT IGNORE INTO evenement (titre, date, lieu) VALUES 
('Portes Ouvertes IPNET 2025', '2025-05-15 09:00:00', 'IPNET Lomé'),
('Webinaire : Réussir son orientation', '2025-04-20 14:00:00', 'En ligne'),
('Hackathon SchoolPrepar', '2025-06-10 08:00:00', 'Université de Lomé'),
('Rencontre Mentor-Élève', '2025-05-05 10:00:00', 'IPNET Lomé'),
('Forum des Métiers et Formations', '2025-07-01 09:00:00', 'Palais des Congrès de Lomé');

-- ========== INSERTION DES PARTICIPATIONS (USER <-> EVENEMENT) ==========
-- Remplace les IDs par ceux que tu as dans ta base
INSERT IGNORE INTO user_evenement (user_id, evenement_id) VALUES 
(2, 1), (2, 2), (3, 1), (4, 3), (5, 4);
-- ========== INSERTION DES UTILISATEURS MANQUANTS ==========
INSERT IGNORE INTO user (nom, prenom, email, password, role, filiere_id) VALUES 
('ADJA', 'Kokou', 'kokou.adja@schoolprepar.com', 'Romaric123', '["ROLE_ADMIN"]', NULL),
('ALI', 'Romaric', 'romaric.ali@ipnet.tg', 'Romaric123', '["ROLE_ELEVE"]', 1),
('DJANGBEDJA', 'Essotina', 'essotina.djangbedja@univ-lome.tg', 'Romaric123', '["ROLE_MENTOR"]', 2),
('KOMLAN', 'Ayao', 'ayao.komlan@schoolprepar.com', 'Romaric123', '["ROLE_ELEVE"]', 3),
('SOGLO', 'Nafissatou', 'nafissatou.soglo@ipnet.tg', 'Romaric123', '["ROLE_MENTOR"]', 5);