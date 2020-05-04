#PROAGES-49
START TRANSACTION;
INSERT INTO modules (name, label, last_updated, date) VALUES ('Grupos', '', DATE(NOW()), UNIX_TIMESTAMP());
SET @module_id = LAST_INSERT_ID();
INSERT INTO user_roles_vs_access (user_role_id, module_id, action_id) VALUES (6, @module_id, 1), (6, @module_id, 2), (6, @module_id, 3), (6, @module_id, 12);
COMMIT;

CREATE TABLE user_groups(
	id INT(11) auto_increment PRIMARY KEY,
	description VARCHAR(100),
	group_owner INT(11) NOT NULL,
	filter_type TINYINT(1) COMMENT '1 => Vida, 2 => GMM, 3 => Both',
	CONSTRAINT fk_ug_users FOREIGN KEY (group_owner) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE user_groups_vs_agents(
	id INT(11) auto_increment PRIMARY KEY,
	user_group_id INT(11) NOT NULL,
	agent_id INT(11) NOT NULL,
	CONSTRAINT fk_uga_users FOREIGN KEY (user_group_id) REFERENCES user_groups(id) ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT fk_uga_agents FOREIGN KEY (agent_id) REFERENCES agents(id) ON UPDATE CASCADE ON DELETE CASCADE
);