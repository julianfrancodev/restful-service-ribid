
CREATE TABLE public.role(
                            id serial not null,
                            name VARCHAR(100) NOT NULL,
                            created_at      TIMESTAMP DEFAULT NULL,
                            updated_at      TIMESTAMP DEFAULT NULL,
                            CONSTRAINT id_rol PRIMARY KEY (id)
);

CREATE TABLE public.sede(
                            id serial not null,
                            name VARCHAR(100) NOT NULL,
                            created_at      TIMESTAMP DEFAULT NULL,
                            updated_at      TIMESTAMP DEFAULT NULL,
                            CONSTRAINT id_sede PRIMARY KEY (id)

);

CREATE TABLE public.document_type(

                                     id serial not null,
                                     name VARCHAR(100) NOT NULL,
                                     created_at      TIMESTAMP DEFAULT NULL,
                                     updated_at      TIMESTAMP DEFAULT NULL,
                                     CONSTRAINT id_tipo_documento PRIMARY KEY (id)

);

CREATE TABLE public.categories (
                                   id serial NOT NULL,
                                   name VARCHAR(100) NOT NULL,
                                   created_at      TIMESTAMP DEFAULT NULL,
                                   updated_at      TIMESTAMP DEFAULT NULL,
                                   CONSTRAINT id_category PRIMARY KEY (id)
);


CREATE TABLE public.users (
                              id serial NOT NULL,
                              name VARCHAR(100) NOT NULL,
                              surname VARCHAR(100),
                              image VARCHAR(200),
                              role int DEFAULT '1' NOT NULL,
                              sede int DEFAULT '1' NOT NULL,
                              email VARCHAR(200) NOT NULL,
                              password VARCHAR(200) NOT NULL,
                              phone VARCHAR(50),
                              birth_date TIMESTAMP DEFAULT NULL,
                              created_at      TIMESTAMP DEFAULT NULL,
                              updated_at      TIMESTAMP DEFAULT NULL,
                              email_verified_at TIMESTAMP DEFAULT NULL,
                              CONSTRAINT id_user PRIMARY KEY (id)
);


CREATE TABLE public.posts (
                              id serial NOT NULL,
                              title VARCHAR(200) NOT NULL,
                              status VARCHAR(30) DEFAULT 'PENDIENTE' NOT NULL,
                              user_id int NOT NULL,
                              category_id int NOT NULL,
                              document_type_id int NOT NULL,
                              section VARCHAR(200),
                              pages VARCHAR(200),
                              created_at      TIMESTAMP DEFAULT NULL,
                              updated_at      TIMESTAMP DEFAULT NULL,
                              CONSTRAINT id_post PRIMARY KEY (id)
);


CREATE TABLE public.res_post (
                                 id serial NOT NULL,
                                 file_res VARCHAR,
                                 user_id_res int NOT NULL,
                                 post_id_res int NOT NULL,
                                 lib_document_id int,
                                 created_at      TIMESTAMP DEFAULT NULL,
                                 updated_at      TIMESTAMP DEFAULT NULL,
                                 CONSTRAINT id_post_re PRIMARY KEY (id)
);


CREATE TABLE public.lib_document (

                                     id serial NOT NULL,
                                     user_id int NOT NULL,
                                     file_lib VARCHAR,
                                     title VARCHAR (200) NOT NULL,
                                     author VARCHAR (200) NOT NULL,
                                     section VARCHAR(200),
                                     pages VARCHAR(200),
                                     document_type_id int NOT NULL,
                                     category_id int NOT NULL,
                                     created_at TIMESTAMP DEFAULT NULL,
                                     updated_at TIMESTAMP DEFAULT NULL,
                                     CONSTRAINT id_lib_document PRIMARY KEY(id)

);


ALTER TABLE public.lib_document ADD CONSTRAINT lib_document_users_fk
    FOREIGN KEY (user_id)
        REFERENCES public.users(id)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION
        NOT DEFERRABLE;

ALTER TABLE public.lib_document ADD CONSTRAINT lib_document_category_fk
    FOREIGN KEY (category_id)
        REFERENCES public.categories(id)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION
        NOT DEFERRABLE;

ALTER TABLE public.lib_document ADD CONSTRAINT lib_document_type_document_fk
    FOREIGN KEY (document_type_id)
        REFERENCES public.document_type(id)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION
        NOT DEFERRABLE;


ALTER TABLE public.users ADD CONSTRAINT role_users_fk
    FOREIGN KEY (role)
        REFERENCES public.role (id)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION
        NOT DEFERRABLE;

ALTER TABLE public.users ADD CONSTRAINT sede_users_fk
    FOREIGN KEY (sede)
        REFERENCES public.sede (id)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION
        NOT DEFERRABLE;

ALTER TABLE public.posts ADD CONSTRAINT document_type_post_fk
    FOREIGN KEY (document_type_id)
        REFERENCES public.document_type(id)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION
        NOT DEFERRABLE;

ALTER TABLE public.posts ADD CONSTRAINT categories_post_fk
    FOREIGN KEY (category_id)
        REFERENCES public.categories (id)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION
        NOT DEFERRABLE;

ALTER TABLE public.res_post ADD CONSTRAINT users_res_post_fk
    FOREIGN KEY (user_id_res)
        REFERENCES public.users (id)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION
        NOT DEFERRABLE;

ALTER TABLE public.posts ADD CONSTRAINT users_post_fk
    FOREIGN KEY (user_id)
        REFERENCES public.users (id)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION
        NOT DEFERRABLE;

ALTER TABLE public.res_post ADD CONSTRAINT post_res_post_fk
    FOREIGN KEY (post_id_res)
        REFERENCES public.posts (id)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION
        NOT DEFERRABLE;

ALTER TABLE public.res_post ADD CONSTRAINT lib_document_res_post_fk
    FOREIGN KEY (lib_document_id)
        REFERENCES public.lib_document(id)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION
        NOT DEFERRABLE;


insert into categories(name) values ('Biología');
insert into categories(name) values ('Física');
insert into categories(name) values ('Historia');
insert into categories(name) values ('Literatura');
insert into categories(name) values ('Química');
insert into categories(name) values ('Cálculo');
insert into categories(name) values ('Algebra');
insert into categories(name) values ('Contaduria');
insert into categories(name) values ('Ingeniería');
insert into categories(name) values ('Ciencia');
insert into categories(name) values ('Economía');
insert into categories(name) values ('Jurídico');

insert into role(name) values ('Investigador');
insert into role(name) values ('Estudiante');
insert into role(name) values ('Bibliotecario');
insert into role(name) values ('Docente');

insert into sede(name) values ('Universidad Santo Tomas - Tunja');
insert into sede(name) values ('Universidad Santo Tomas - Bogota');
insert into sede(name) values ('Universidad Santo Tomas - Bucaramanga');
insert into sede(name) values ('Universidad Santo Tomas - Medellin');
insert into sede(name) values ('Universidad Santo Tomas - Villavicencio');


insert into document_type(name) values ('Articulo');
insert into document_type(name) values ('Capitulos de libro');

