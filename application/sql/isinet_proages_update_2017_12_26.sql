#PROAGES-77
START TRANSACTION;
INSERT INTO modules (name, label, last_updated, date) VALUES ('Reporte de ventas', '', DATE(NOW()), UNIX_TIMESTAMP());
SET @module_id = LAST_INSERT_ID();
INSERT INTO user_roles_vs_access (user_role_id, module_id, action_id) VALUES (6, @module_id, 1), (6, @module_id, 2), (6, @module_id, 3), (6, @module_id, 12), (6, @module_id, 10);
INSERT INTO user_roles_vs_access (user_role_id, module_id, action_id) VALUES (5, @module_id, 1), (5, @module_id, 2), (5, @module_id, 3), (5, @module_id, 12), (5, @module_id, 10);
COMMIT;