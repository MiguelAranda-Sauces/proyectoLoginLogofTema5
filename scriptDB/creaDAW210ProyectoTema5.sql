/**
 * Author:  daw2
 * Created: 25 nov. 2020
 */

-- Crear base de datos 
   -- CREATE DATABASE if NOT EXISTS (Nombre base de datos);

-- Uso de la base de datos. 
   -- USE (Nombre base de datos);
    
-- Crear tablas. 
 --   CREATE TABLE IF NOT EXISTS (Nombre tabla);(
     --   (Nombre campo) varchar(3) PRIMARY KEY,
      --  (Nombre campo) varchar(255) NOT null
  --  )ENGINE=INNODB;

-- Crear del usuario
--CREATE USER IF NOT EXISTS '(Nombre Usuario)'@'%' identified BY '(password)'; 

-- Dar permisos al usuario 
   -- GRANT ALL PRIVILEGES ON (Nombre base de datos).* TO '(Nombre Usuario)'@'%'; 

-- Hacer el flush privileges, por si acaso da error de credenciales
   -- FLUSH PRIVILEGES;

-- Creacion de tablas de la base de datos
CREATE TABLE if NOT EXISTS db771560945.T02_Departamento (
    T02_CodDepartamento VARCHAR(3) PRIMARY KEY,
    T02_DescDepartamento VARCHAR(255) NOT NULL,
    T02_FechaBajaDepartamento DATE NULL,
    T02_FechaCreacionDepartamento INT NULL,
    T02_VolumenNegocio FLOAT NULL
)ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS db771560945.T01_Usuario(
        T01_CodUsuario VARCHAR(15) PRIMARY KEY,
        T01_DescUsuario VARCHAR(255) NOT NULL,
        T01_Password VARCHAR(64) NOT NULL,
        T01_Perfil enum('administrador', 'usuario') DEFAULT 'usuario',
        T01_FechaHoraUltimaConexion INT,
        T01_NumConexiones INT DEFAULT 0,
        T01_ImagenUsuario MEDIUMBLOB
)ENGINE=INNODB;
