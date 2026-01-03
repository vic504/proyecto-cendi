CREATE TABLE grupos (
    id_grupo INT AUTO_INCREMENT PRIMARY KEY,
    nombre_grupo VARCHAR(100) NOT NULL, -- Ej: 'Lactantes I - II'
    edad_min_meses INT NOT NULL,        -- Edad mínima en meses
    edad_max_meses INT NOT NULL,        -- Edad máxima en meses
    cupo_maximo INT DEFAULT 10          -- Restricción del punto 31 del PDF 
);
CREATE TABLE cendis (
    id_cendi INT AUTO_INCREMENT PRIMARY KEY,
    nombre_cendi VARCHAR(100) NOT NULL,
    direccion TEXT NOT NULL
);
CREATE TABLE ninos (
    id_nino INT AUTO_INCREMENT PRIMARY KEY,
    nombre1 VARCHAR(50) NOT NULL,
    nombre2 VARCHAR(50), -- Se deja opcional (NULL) por si solo tienen un nombre
    apellido_paterno VARCHAR(50) NOT NULL,
    apellido_materno VARCHAR(50) NOT NULL,
    
    -- Campo indispensable para el punto 8 (Cálculo automático de edad)
    fecha_nacimiento DATE NOT NULL, 
    
    -- Llave Foránea para vincular al hijo con el trabajador del IPN (punto 6)
    id_usuario INT NOT NULL, 
    
    -- Campos que el sistema llenará automáticamente (puntos 8 y 15)
    id_cendi INT, 
    id_grupo INT,
    
    -- Relaciones (Llaves Foráneas)
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
    FOREIGN KEY (id_cendi) REFERENCES cendis(id_cendi),
    FOREIGN KEY (id_grupo) REFERENCES grupos(id_grupo)
);


--inseciones
INSERT INTO grupos (nombre_grupo, edad_min_meses, edad_max_meses) VALUES 
('Lactantes I - II', 0, 12),
('Maternal I', 13, 24),
('Maternal II', 25, 36),
('Preescolar I', 37, 48),
('Preescolar II', 49, 60),
('Preescolar III', 61, 72);

INSERT INTO cendis (nombre_cendi, direccion) VALUES 
('CENDI "Amalia Solórzano de Cárdenas"', 'Av. Wilfrido Massieu s/n, Zacatenco, CDMX'),
('CENDI "Clementina Batalla de Bassols"', 'Unidad Profesional Adolfo López Mateos, Zacatenco, CDMX'),
('CENDI "Eva Sámano de López Mateos"', 'Casco de Santo Tomás, Miguel Hidalgo, CDMX'),
('CENDI "Laura Pérez de Bátiz"', 'Av. Juan de Dios Bátiz, Zacatenco, CDMX'),
('CENDI "Margarita Salazar de Erro"', 'Calle de Carpio, Col. Plutarco Elías Calles, CDMX');