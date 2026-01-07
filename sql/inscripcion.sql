
CREATE DATABASE IF NOT EXISTS cendi_db;
USE cendi_db;



CREATE TABLE cendi (
    id_cendi INT PRIMARY KEY AUTO_INCREMENT,
    cendi VARCHAR(100) NOT NULL
);

CREATE TABLE grupo (
    id_grupo INT PRIMARY KEY AUTO_INCREMENT,
    grupo VARCHAR(50) NOT NULL
);

CREATE TABLE grupo_sang (
    id_grupo_sang INT PRIMARY KEY AUTO_INCREMENT,
    grupo_sang VARCHAR(10) NOT NULL
);

CREATE TABLE rh (
    id_rh INT PRIMARY KEY AUTO_INCREMENT,
    rh VARCHAR(10) NOT NULL
);

CREATE TABLE entidad_federativa (
    id_entidad INT PRIMARY KEY AUTO_INCREMENT,
    entidad VARCHAR(100) NOT NULL
);

CREATE TABLE ocupacion (
    id_ocupacion INT PRIMARY KEY AUTO_INCREMENT,
    ocupacion VARCHAR(100) NOT NULL
);

CREATE TABLE escolaridad (
    id_escolaridad INT PRIMARY KEY AUTO_INCREMENT,
    escolaridad VARCHAR(100) NOT NULL
);

CREATE TABLE edo_civil (
    id_edo_civil INT PRIMARY KEY AUTO_INCREMENT,
    edo_civil VARCHAR(50) NOT NULL
);

CREATE TABLE tipo_adscripcion (
    id_tipo_adscripcion INT PRIMARY KEY AUTO_INCREMENT,
    tipo_adscripcion VARCHAR(100) NOT NULL
);


CREATE TABLE municipio (
    id_municipio INT PRIMARY KEY AUTO_INCREMENT,
    municipio VARCHAR(100) NOT NULL,
    id_entidad INT,
    UNIQUE KEY uniq_municipio_entidad (municipio, id_entidad),
    FOREIGN KEY (id_entidad) REFERENCES entidad_federativa(id_entidad)
);

CREATE TABLE adscripcion (
    id_adscripcion INT PRIMARY KEY AUTO_INCREMENT,
    adscripcion VARCHAR(100) NOT NULL,
    id_tipo_adscripcion INT,
    FOREIGN KEY (id_tipo_adscripcion) REFERENCES tipo_adscripcion(id_tipo_adscripcion)
);

CREATE TABLE domicilio (
    id_domicilio INT PRIMARY KEY AUTO_INCREMENT,
    calle VARCHAR(255),
    num VARCHAR(20),
    cp VARCHAR(10),
    id_entidad INT,
    id_municipio INT,
    FOREIGN KEY (id_entidad) REFERENCES entidad_federativa(id_entidad),
    FOREIGN KEY (id_municipio) REFERENCES municipio(id_municipio)
);



CREATE TABLE nino (
    id_nino INT PRIMARY KEY AUTO_INCREMENT,
    curp VARCHAR(18) UNIQUE NOT NULL,
    apPat VARCHAR(100),
    apMat VARCHAR(100),
    nombres VARCHAR(100) NOT NULL,
    fecha_nac DATE,
    id_lugar_nac INT, 
    tel VARCHAR(20),
    id_grupo_sang INT,
    id_rh INT,
    id_entidad_nac INT,
    id_cendi INT,
    id_grupo INT,
    id_trabajador INT,
    id_domicilio INT,
    FOREIGN KEY (id_grupo_sang) REFERENCES grupo_sang(id_grupo_sang),
    FOREIGN KEY (id_rh) REFERENCES rh(id_rh),
    FOREIGN KEY (id_entidad_nac) REFERENCES entidad_federativa(id_entidad),
    FOREIGN KEY (id_cendi) REFERENCES cendi(id_cendi),
    FOREIGN KEY (id_grupo) REFERENCES grupo(id_grupo),
    FOREIGN KEY (id_trabajador) REFERENCES trabajador(id_trabajador),
    FOREIGN KEY (id_domicilio) REFERENCES domicilio(id_domicilio)
);

CREATE TABLE trabajador (
    id_trabajador INT PRIMARY KEY AUTO_INCREMENT,
    curp VARCHAR(18) UNIQUE NOT NULL,
    apPat VARCHAR(100),
    apMat VARCHAR(100),
    nombres VARCHAR(100) NOT NULL,
    fecha_nac DATE,
    correo_institucional VARCHAR(150),
    correo_personal VARCHAR(150),
    num_empleado VARCHAR(50),
    password_hash VARCHAR(255) NOT NULL,
    horario_laboral VARCHAR(100),
    id_entidad_nac INT,
    id_ocupacion INT,
    id_escolaridad INT,
    id_tipo_adscripcion INT,
    id_edo_civil INT,
    FOREIGN KEY (id_entidad_nac) REFERENCES entidad_federativa(id_entidad),
    FOREIGN KEY (id_ocupacion) REFERENCES ocupacion(id_ocupacion),
    FOREIGN KEY (id_escolaridad) REFERENCES escolaridad(id_escolaridad),
    FOREIGN KEY (id_tipo_adscripcion) REFERENCES tipo_adscripcion(id_tipo_adscripcion),
    FOREIGN KEY (id_edo_civil) REFERENCES edo_civil(id_edo_civil)
);