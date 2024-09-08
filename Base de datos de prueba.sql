Create database Mateando_Juntos;
use Mateando_Juntos;

CREATE TABLE Usuario (
    ID_usuario INT PRIMARY KEY AUTO_INCREMENT,
    Nombre VARCHAR(100) NOT NULL,
    Nombre_usuario VARCHAR(50) NOT NULL unique,
    Contrasena VARCHAR(255) NOT NULL,
    Email VARCHAR(100) NOT NULL,
    Fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
select * from usuario;

CREATE TABLE Administrador (
    ID_admin INT PRIMARY KEY AUTO_INCREMENT,
    Contrasena VARCHAR(255) not null unique
);

CREATE TABLE Perfil_usuario (
    ID_perfil INT PRIMARY KEY AUTO_INCREMENT,
    Tema boolean DEFAULT TRUE,
    Foto_perfil VARCHAR(255),
    Biografia TEXT,
    Privado BOOLEAN DEFAULT FALSE,
    ID_usuario INT,
    FOREIGN KEY (ID_usuario) REFERENCES Usuario(ID_usuario)
    ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE Comunidad (
    ID_comunidad INT PRIMARY KEY AUTO_INCREMENT,
    Nombre_comunidad VARCHAR(100)  NOT NULL,
    Descripcion TEXT,
    Fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ID_perfil INT NOT NULL,
    FOREIGN KEY (ID_perfil) REFERENCES Perfil_usuario(ID_perfil)
);

CREATE TABLE Idioma (
    ID_idioma INT PRIMARY KEY AUTO_INCREMENT,
    Nombre_idioma varchar(70) NOT NULL
);

CREATE TABLE Mensaje (
    ID_mensaje INT PRIMARY KEY AUTO_INCREMENT,
    Contenido TEXT,
    Fecha_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ID_perfil INT,
    FOREIGN KEY (ID_perfil) REFERENCES Perfil_usuario(ID_perfil)
); -- no creada

CREATE TABLE Pertenece (
    ID_perfil INT,
    ID_comunidad INT,
    PRIMARY KEY (ID_perfil, ID_comunidad),
    FOREIGN KEY (ID_perfil) REFERENCES Perfil_usuario(ID_perfil),
    FOREIGN KEY (ID_comunidad) REFERENCES Comunidad(ID_comunidad)
);

CREATE TABLE Evento (
    ID_evento INT PRIMARY KEY AUTO_INCREMENT,
    Fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Fechayhora_encuentro DATETIME,
    Latitud DECIMAL(10, 8),
    Longitud DECIMAL(11, 8),
    ID_perfil INT,
    ID_comunidad INT,
    FOREIGN KEY (ID_perfil) REFERENCES Pertenece(ID_perfil),
    FOREIGN KEY (ID_comunidad) REFERENCES Pertenece(ID_comunidad)
);-- NO creada

CREATE TABLE Post (
    ID_post INT PRIMARY KEY AUTO_INCREMENT,
    Titulo VARCHAR(255),
    Descripcion TEXT,
    Fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ID_usuario INT NOT NULL,  
    FOREIGN KEY (ID_usuario) REFERENCES Usuario(ID_usuario)
    ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE Post_multimedia (
    Numero_mul INT  AUTO_INCREMENT,
    Src_mul VARCHAR(255),
    ID_post INT ,
    primary key (Numero_mul,ID_post),
    FOREIGN KEY (ID_post) REFERENCES Post(ID_post)
);

CREATE TABLE Comentarios (
    ID_comentario INT PRIMARY KEY AUTO_INCREMENT,
    ID_perfil INT,
    FOREIGN KEY (ID_perfil) REFERENCES Perfil_usuario(ID_perfil)
);-- No creada

CREATE TABLE Modifica (
    ID_perfil INT,
    ID_idioma INT,
    PRIMARY KEY (ID_perfil, ID_idioma),
    FOREIGN KEY (ID_perfil) REFERENCES Perfil_usuario(ID_perfil),
    FOREIGN KEY (ID_idioma) REFERENCES Idioma(ID_idioma)
); -- No creada

CREATE TABLE Seguir (
    Perfil_usuario_Seguido INT,
    Perfil_usuario_Seguidor INT,
    PRIMARY KEY (Perfil_usuario_Seguido, Perfil_usuario_Seguidor),
    FOREIGN KEY (Perfil_usuario_Seguido) REFERENCES Perfil_usuario(ID_perfil),
    FOREIGN KEY (Perfil_usuario_Seguidor) REFERENCES Perfil_usuario(ID_perfil)
); -- NO creada

CREATE TABLE Dar_megusta (
    ID_perfil INT,
    ID_post INT,
    PRIMARY KEY (ID_perfil, ID_post),
    FOREIGN KEY (ID_perfil) REFERENCES Perfil_usuario(ID_perfil),
    FOREIGN KEY (ID_post) REFERENCES Post(ID_post)
); -- no creada

CREATE TABLE Ev_Contiene (
    ID_evento INT,
    ID_post INT,
    PRIMARY KEY (ID_evento, ID_post),
    FOREIGN KEY (ID_evento) REFERENCES Evento(ID_evento) on update cascade on delete cascade,
    FOREIGN KEY (ID_post) REFERENCES Post(ID_post)  on update cascade on delete cascade
);-- No creada

CREATE TABLE Admin_elimina_post (
    ID_post INT,
    ID_admin INT,
    Fecha_eliminacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (ID_post, ID_admin),
    FOREIGN KEY (ID_post) REFERENCES Post(ID_post),
    FOREIGN KEY (ID_admin) REFERENCES Administrador(ID_admin)
);

CREATE TABLE Crea_Admin (
    Administrador_admin_creador INT,
    Administrador_admin_creado INT,
    PRIMARY KEY (Administrador_admin_creador, Administrador_admin_creado),
    FOREIGN KEY (Administrador_admin_creador) REFERENCES Administrador(ID_admin),
    FOREIGN KEY (Administrador_admin_creado) REFERENCES Administrador(ID_admin)
);

CREATE TABLE Elimina_usuario (
    ID_usuario INT,
    ID_admin INT,
    Fecha_Eliminacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (ID_usuario, ID_admin),
    FOREIGN KEY (ID_usuario) REFERENCES Usuario(ID_usuario),
    FOREIGN KEY (ID_admin) REFERENCES Administrador(ID_admin)
);

CREATE TABLE TieneComentarios (
    ID_comentario INT,
    ID_post INT,
    PRIMARY KEY (ID_comentario, ID_post),
    FOREIGN KEY (ID_comentario) REFERENCES Comentarios(ID_comentario),
    FOREIGN KEY (ID_post) REFERENCES Post(ID_post)
);-- no creada