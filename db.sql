DROP TABLE IF EXISTS users ;
CREATE TABLE users (u_id INT(255)  AUTO_INCREMENT NOT NULL, u_name CHAR(255), u_password CHAR(255), u_mail CHAR(255), u_timestamp INT(255), u_size_total BIGINT(255), u_size_used BIGINT(255), PRIMARY KEY (u_id) ) ENGINE=InnoDB;
DROP TABLE IF EXISTS files ;
CREATE TABLE files (f_id INT(255)  AUTO_INCREMENT NOT NULL, f_name CHAR(255), f_extension CHAR(255), f_mime CHAR(255), f_size BIGINT(255), f_md5 CHAR(255), f_filename CHAR(255), f_timestamp INT(255), f_picture INT(255), f_music INT(255), f_movie INT(255), f_book INT(255), f_trash INT(255), u_id INT(255) NOT NULL, PRIMARY KEY (f_id) ) ENGINE=InnoDB;
DROP TABLE IF EXISTS users_groups ;
CREATE TABLE users_groups (g_id INT(255)  AUTO_INCREMENT NOT NULL, g_name CHAR(255), g_nb INT(255), g_editable INT(255), u_id INT(255), PRIMARY KEY (g_id) ) ENGINE=InnoDB;
DROP TABLE IF EXISTS tags ;
CREATE TABLE tags (t_id INT(255)  AUTO_INCREMENT NOT NULL, t_name CHAR(255), t_usage INT(255), t_search BIGINT(255), PRIMARY KEY (t_id) ) ENGINE=InnoDB;
DROP TABLE IF EXISTS users_connections ;
CREATE TABLE users_connections (uc_id INT(255)  AUTO_INCREMENT NOT NULL, uc_ip CHAR(255), uc_agent CHAR(255), uc_timestamp INT(255), u_id INT(255) NOT NULL, PRIMARY KEY (uc_id) ) ENGINE=InnoDB;
DROP TABLE IF EXISTS storages ;
CREATE TABLE storages (s_id INT(255)  AUTO_INCREMENT NOT NULL, s_size_total BIGINT(255), s_size_used BIGINT(255), s_path CHAR(255), PRIMARY KEY (s_id) ) ENGINE=InnoDB;
DROP TABLE IF EXISTS album ;
CREATE TABLE album (a_id INT(255)  AUTO_INCREMENT NOT NULL, a_name VARCHAR(255), a_timestamp INT(255), u_id INT(255) NOT NULL, g_id INT(255) NOT NULL, PRIMARY KEY (a_id) ) ENGINE=InnoDB;
DROP TABLE IF EXISTS files_access ;
CREATE TABLE files_access (f_id INT(255)  AUTO_INCREMENT NOT NULL, g_id INT(255) NOT NULL, i_read INT(255), i_write INT(255), i_delete INT(255), PRIMARY KEY (f_id,  g_id) ) ENGINE=InnoDB;
DROP TABLE IF EXISTS users_members ;
CREATE TABLE users_members (u_id INT(255)  AUTO_INCREMENT NOT NULL, g_id INT(255) NOT NULL, PRIMARY KEY (u_id,  g_id) ) ENGINE=InnoDB;
DROP TABLE IF EXISTS files_tags ;
CREATE TABLE files_tags (f_id INT(255)  AUTO_INCREMENT NOT NULL, t_id INT(255) NOT NULL, ft_timestamp INT(255), PRIMARY KEY (f_id,  t_id) ) ENGINE=InnoDB;
DROP TABLE IF EXISTS files_path ;
CREATE TABLE files_path (f_id INT(255)  AUTO_INCREMENT NOT NULL, s_id INT(255) NOT NULL, fp_timestamp INT(255), PRIMARY KEY (f_id,  s_id) ) ENGINE=InnoDB;
DROP TABLE IF EXISTS album_files ;
CREATE TABLE album_files (a_id INT(255)  AUTO_INCREMENT NOT NULL, f_id INT(255) NOT NULL, af_order INT(255), PRIMARY KEY (a_id,  f_id) ) ENGINE=InnoDB;

ALTER TABLE files ADD CONSTRAINT FK_files_u_id FOREIGN KEY (u_id) REFERENCES users (u_id);
ALTER TABLE users_groups ADD CONSTRAINT FK_users_groups_u_id FOREIGN KEY (u_id) REFERENCES users (u_id);
ALTER TABLE users_connections ADD CONSTRAINT FK_users_connections_u_id FOREIGN KEY (u_id) REFERENCES users (u_id);
ALTER TABLE album ADD CONSTRAINT FK_album_u_id FOREIGN KEY (u_id) REFERENCES users (u_id);
ALTER TABLE album ADD CONSTRAINT FK_album_g_id FOREIGN KEY (g_id) REFERENCES users_groups (g_id);
ALTER TABLE files_access ADD CONSTRAINT FK_files_access_f_id FOREIGN KEY (f_id) REFERENCES files (f_id);
ALTER TABLE files_access ADD CONSTRAINT FK_files_access_g_id FOREIGN KEY (g_id) REFERENCES users_groups (g_id);
ALTER TABLE users_members ADD CONSTRAINT FK_users_members_u_id FOREIGN KEY (u_id) REFERENCES users (u_id);
ALTER TABLE users_members ADD CONSTRAINT FK_users_members_g_id FOREIGN KEY (g_id) REFERENCES users_groups (g_id);
ALTER TABLE files_tags ADD CONSTRAINT FK_files_tags_f_id FOREIGN KEY (f_id) REFERENCES files (f_id);
ALTER TABLE files_tags ADD CONSTRAINT FK_files_tags_t_id FOREIGN KEY (t_id) REFERENCES tags (t_id);
ALTER TABLE files_path ADD CONSTRAINT FK_files_path_f_id FOREIGN KEY (f_id) REFERENCES files (f_id);
ALTER TABLE files_path ADD CONSTRAINT FK_files_path_s_id FOREIGN KEY (s_id) REFERENCES storages (s_id);
ALTER TABLE album_files ADD CONSTRAINT FK_album_files_a_id FOREIGN KEY (a_id) REFERENCES album (a_id);
ALTER TABLE album_files ADD CONSTRAINT FK_album_files_f_id FOREIGN KEY (f_id) REFERENCES files (f_id);