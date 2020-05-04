##PROAGES-46
#Renovacion
INSERT INTO work_order_types (patent_id, name, description, duration, last_updated, date) values(2, 'RENOVACIÓN','', 0, NOW(), NOW());
SET @last_id = LAST_INSERT_ID();
INSERT INTO work_order_types (patent_id, name, description, duration, last_updated, date) values(@last_id, 'GENERAL','', 1, NOW(), NOW());

#Conversión a individual
INSERT INTO work_order_types (patent_id, name, description, duration, last_updated, date) values(2, 'CONVERSIÓN A INDIVIDUAL','', 0, NOW(), NOW());
SET @last_id = LAST_INSERT_ID();
INSERT INTO work_order_types (patent_id, name, description, duration, last_updated, date) values(@last_id, 'GENERAL','', 1, NOW(), NOW());

#Contratación Conexión
INSERT INTO work_order_types (patent_id, name, description, duration, last_updated, date) values(2, 'CONTRATACIÓN DE CONEXIÓN','', 0, NOW(), NOW());
SET @last_id = LAST_INSERT_ID();
INSERT INTO work_order_types (patent_id, name, description, duration, last_updated, date) values(@last_id, 'GENERAL','', 1, NOW(), NOW());

#Cambio de Contratante
INSERT INTO work_order_types (patent_id, name, description, duration, last_updated, date) values(2, 'CAMBIO DE CONTRATANTE','', 0, NOW(), NOW());
SET @last_id = LAST_INSERT_ID();
INSERT INTO work_order_types (patent_id, name, description, duration, last_updated, date) values(@last_id, 'GENERAL','', 1, NOW(), NOW());

#Cambios datos del contratante
INSERT INTO work_order_types (patent_id, name, description, duration, last_updated, date) values(2, 'CAMBIO DATOS DEL CONTRATANTE','', 0, NOW(), NOW());
SET @last_id = LAST_INSERT_ID();
INSERT INTO work_order_types (patent_id, name, description, duration, last_updated, date) values(@last_id, 'GENERAL','', 1, NOW(), NOW());

#Cambio individual a Conexión
INSERT INTO work_order_types (patent_id, name, description, duration, last_updated, date) values(2, 'CAMBIO INDIVIDUAL A CONEXIÓN','', 0, NOW(), NOW());
SET @last_id = LAST_INSERT_ID();
INSERT INTO work_order_types (patent_id, name, description, duration, last_updated, date) values(@last_id, 'GENERAL','', 1, NOW(), NOW());

##PROAGES-47
UPDATE policies SET name=UPPER(name)