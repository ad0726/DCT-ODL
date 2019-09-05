
CREATE TABLE usr (
                id INT AUTO_INCREMENT NOT NULL,
                name TEXT NOT NULL,
                name_clean TEXT NOT NULL,
                password TEXT NOT NULL,
                acl VARCHAR(20) NOT NULL,
                PRIMARY KEY (id)
);


CREATE TABLE setting (
                id INT AUTO_INCREMENT NOT NULL,
                param VARCHAR(60) NOT NULL,
                value TINYINT NOT NULL,
                PRIMARY KEY (id)
);


CREATE TABLE changelog (
                id TIMESTAMP DEFAULT NOW() NOT NULL,
                author TEXT NOT NULL,
                cl_type TEXT NOT NULL,
                name_era TEXT NOT NULL,
                name_period TEXT NOT NULL,
                old_position TEXT NOT NULL,
                new_position TEXT NOT NULL,
                title TEXT NOT NULL,
                new_title TEXT NOT NULL,
                cover TEXT NOT NULL,
                content TEXT NOT NULL,
                urban TEXT NOT NULL,
                dctrad TEXT NOT NULL,
                isEvent TEXT NOT NULL,
                PRIMARY KEY (id)
);


CREATE TABLE universe (
                id_universe CHAR(13) NOT NULL,
                name VARCHAR(50) NOT NULL,
                clean_name VARCHAR(50) NOT NULL,
                PRIMARY KEY (id_universe)
);


CREATE TABLE era (
                id_era CHAR(13) NOT NULL,
                id_universe CHAR(13) NOT NULL,
                position INT NOT NULL,
                name VARCHAR(100) NOT NULL,
                clean_name VARCHAR(100) NOT NULL,
                image TEXT NOT NULL,
                PRIMARY KEY (id_era)
);


CREATE TABLE period (
                id_period CHAR(13) NOT NULL,
                id_era CHAR(13) NOT NULL,
                position INT NOT NULL,
                name VARCHAR(100) NOT NULL,
                clean_name VARCHAR(100) NOT NULL,
                PRIMARY KEY (id_period)
);


CREATE TABLE arc (
                id_arc INT AUTO_INCREMENT NOT NULL,
                id_period CHAR(13) NOT NULL,
                position INT NOT NULL,
                title TEXT NOT NULL,
                cover TEXT NOT NULL,
                content TEXT NOT NULL,
                link_a TEXT NOT NULL,
                link_b TEXT NOT NULL,
                is_event TINYINT NOT NULL,
                PRIMARY KEY (id_arc)
);


ALTER TABLE era ADD CONSTRAINT universe_era_fk
FOREIGN KEY (id_universe)
REFERENCES universe (id_universe)
ON DELETE NO ACTION
ON UPDATE NO ACTION;

ALTER TABLE period ADD CONSTRAINT era_period_fk
FOREIGN KEY (id_era)
REFERENCES era (id_era)
ON DELETE NO ACTION
ON UPDATE NO ACTION;

ALTER TABLE arc ADD CONSTRAINT period_arc_fk
FOREIGN KEY (id_period)
REFERENCES period (id_period)
ON DELETE NO ACTION
ON UPDATE NO ACTION;