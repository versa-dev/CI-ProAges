-- get generation of agent Vida
SELECT
  DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(connection_date)), '%Y')+0
  AS generation FROM agents where user_id = some_id;

-- get generation of agent GMM
SELECT (TIMESTAMPDIFF(MONTH, '2018-04-02', NOW()) - 4) div 12

-- update generation of agent vida in agent table
UPDATE `agents`
SET    generation_vida = (case when (DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(connection_date)), '%Y')+0) = 0 then 'Generación 1'
						  when (DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(connection_date)), '%Y')+0) = 1 then 'Generación 2'
						  when (DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(connection_date)), '%Y')+0) = 2 then 'Generación 3'
						  when (DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(connection_date)), '%Y')+0) = 3 then 'Generación 4'
						  when (DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(connection_date)), '%Y')+0) >= 4 then 'Consolidado'
						  end);

-- update generation of agent GMM in agent table
UPDATE `agents`
SET    generation_gmm = (case when (TIMESTAMPDIFF(MONTH, connection_date, NOW()) - 4) div 12 = 0 then 'Generación 1'
						  when (TIMESTAMPDIFF(MONTH, connection_date, NOW()) - 4) div 12 = 1 then 'Generación 2'
						  when (TIMESTAMPDIFF(MONTH, connection_date, NOW()) - 4) div 12 = 2 then 'Generación 3'
						  when (TIMESTAMPDIFF(MONTH, connection_date, NOW()) - 4) div 12 = 3 then 'Generación 4'
						  when (TIMESTAMPDIFF(MONTH, connection_date, NOW()) - 4) div 12 >= 4 then 'Consolidado'
						  end);

-- update generation of agent vida in payments table
UPDATE agents, payments
SET    payments.agent_generation = (case when (DATE_FORMAT(FROM_DAYS(TO_DAYS(DATE_FORMAT(payments.payment_date, '%Y-%m-%d %H:%i'))-TO_DAYS(agents.connection_date)), '%Y')+0) = 0 then 'Generación 1'
                          when (DATE_FORMAT(FROM_DAYS(TO_DAYS(DATE_FORMAT(payments.payment_date, '%Y-%m-%d %H:%i'))-TO_DAYS(agents.connection_date)), '%Y')+0) = 1 then 'Generación 2'
                          when (DATE_FORMAT(FROM_DAYS(TO_DAYS(DATE_FORMAT(payments.payment_date, '%Y-%m-%d %H:%i'))-TO_DAYS(agents.connection_date)), '%Y')+0) = 2 then 'Generación 3'
                          when (DATE_FORMAT(FROM_DAYS(TO_DAYS(DATE_FORMAT(payments.payment_date, '%Y-%m-%d %H:%i'))-TO_DAYS(agents.connection_date)), '%Y')+0) = 3 then 'Generación 4'
                          when (DATE_FORMAT(FROM_DAYS(TO_DAYS(DATE_FORMAT(payments.payment_date, '%Y-%m-%d %H:%i'))-TO_DAYS(agents.connection_date)), '%Y')+0) >= 4 then 'Consolidado'
                          end)
where agents.id = payments.agent_id and payments.product_group = 1;

-- update generation of agent GMM in payments table
UPDATE agents, payments
SET    payments.agent_generation = (case when (TIMESTAMPDIFF(MONTH, connection_date, payments.payment_date) - 4) div 12 = 0 then 'Generación 1'
                          when (TIMESTAMPDIFF(MONTH, agents.connection_date, payments.payment_date) - 4) div 12 = 1 then 'Generación 2'
                          when (TIMESTAMPDIFF(MONTH, agents.connection_date, payments.payment_date) - 4) div 12 = 2 then 'Generación 3'
                          when (TIMESTAMPDIFF(MONTH, agents.connection_date, payments.payment_date) - 4) div 12 = 3 then 'Generación 4'
                          when (TIMESTAMPDIFF(MONTH, agents.connection_date, payments.payment_date) - 4) div 12 >= 4 then 'Consolidado'
                          end)
where agents.id = payments.agent_id and payments.product_group = 2;

-- update generation of agent vida in work_order table
UPDATE agents, work_order
SET    work_order.agent_generation = (case when (DATE_FORMAT(FROM_DAYS(TO_DAYS(DATE_FORMAT(work_order.creation_date, '%Y-%m-%d %H:%i'))-TO_DAYS(agents.connection_date)), '%Y')+0) = 0 then 'Generación 1'
                          when (DATE_FORMAT(FROM_DAYS(TO_DAYS(DATE_FORMAT(work_order.creation_date, '%Y-%m-%d %H:%i'))-TO_DAYS(agents.connection_date)), '%Y')+0) = 1 then 'Generación 2'
                          when (DATE_FORMAT(FROM_DAYS(TO_DAYS(DATE_FORMAT(work_order.creation_date, '%Y-%m-%d %H:%i'))-TO_DAYS(agents.connection_date)), '%Y')+0) = 2 then 'Generación 3'
                          when (DATE_FORMAT(FROM_DAYS(TO_DAYS(DATE_FORMAT(work_order.creation_date, '%Y-%m-%d %H:%i'))-TO_DAYS(agents.connection_date)), '%Y')+0) = 3 then 'Generación 4'
                          when (DATE_FORMAT(FROM_DAYS(TO_DAYS(DATE_FORMAT(work_order.creation_date, '%Y-%m-%d %H:%i'))-TO_DAYS(agents.connection_date)), '%Y')+0) >= 4 then 'Consolidado'
                          end)
where agents.user_id = work_order.user;

-- update generation of agent GMM in work_order table
UPDATE agents, work_order
SET    work_order.agent_generation = (case when (TIMESTAMPDIFF(MONTH, connection_date, work_order.creation_date) - 4) div 12 = 0 then 'Generación 1'
                          when (TIMESTAMPDIFF(MONTH, agents.connection_date, work_order.creation_date) - 4) div 12 = 1 then 'Generación 2'
                          when (TIMESTAMPDIFF(MONTH, agents.connection_date, work_order.creation_date) - 4) div 12 = 2 then 'Generación 3'
                          when (TIMESTAMPDIFF(MONTH, agents.connection_date, work_order.creation_date) - 4) div 12 = 3 then 'Generación 4'
                          when (TIMESTAMPDIFF(MONTH, agents.connection_date, work_order.creation_date) - 4) div 12 >= 4 then 'Consolidado'
                          end)
where agents.user_id = work_order.user;

-- event Schendule Vida
DELIMITER $$

CREATE EVENT updateGenerationsAgentsVida
ON SCHEDULE
    EVERY 1 MINUTE
    STARTS '2018-04-05 12:00:00'
    ON COMPLETION PRESERVE
DO
BEGIN
    DECLARE rightnow DATETIME;
    DECLARE rightMonth,hh,mm TINYINT;

    SET rightnow = NOW();
    SET hh = HOUR(rightnow);
    SET rightMonth = month(NOW());
    SET mm = MINUTE(rightnow);

    IF (DATE(rightnow) = LAST_DAY(DATE(rightnow)) AND rightMonth = 3 )
    	OR (DATE(rightnow) = LAST_DAY(DATE(rightnow)) AND rightMonth = 6 )
    	OR (DATE(rightnow) = LAST_DAY(DATE(rightnow)) AND rightMonth = 9 )
    	OR (DATE(rightnow) = LAST_DAY(DATE(rightnow)) AND rightMonth = 12 ) THEN
        IF hh = 23 THEN
            IF mm = 50 THEN
                	UPDATE `agents`
					SET    generation_vida = (case when (DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(connection_date)), '%Y')+0) <= 1 then 1
							  				       when (DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(connection_date)), '%Y')+0) = 2 then 2
							  				       when (DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(connection_date)), '%Y')+0) = 3 then 3
							  			           when (DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(connection_date)), '%Y')+0) = 4 then 4
							  			           when (DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(connection_date)), '%Y')+0) >= 5 then 5
							  			           end);
            END IF;
        END IF;
    END IF;

END $$

DELIMITER ;

-- event Schendule GMM
DELIMITER $$

CREATE EVENT updateGenerationsAgentsGMM
ON SCHEDULE
    EVERY 1 MINUTE
    STARTS '2018-04-05 12:00:00'
    ON COMPLETION PRESERVE
DO
BEGIN
    DECLARE rightnow DATETIME;
    DECLARE rightMonth,hh,mm TINYINT;

    SET rightnow = NOW();
    SET hh = HOUR(rightnow);
    SET rightMonth = month(NOW());
    SET mm = MINUTE(rightnow);

    IF (DATE(rightnow) = LAST_DAY(DATE(rightnow)) AND rightMonth = 4 )
    	OR (DATE(rightnow) = LAST_DAY(DATE(rightnow)) AND rightMonth = 8 )
    	OR (DATE(rightnow) = LAST_DAY(DATE(rightnow)) AND rightMonth = 12 ) THEN
        IF hh = 23 THEN
            IF mm = 50 THEN
                	UPDATE `agents`
					SET    generation_gmm = (case when (DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(connection_date)), '%Y')+0) <= 1 then 1
							  				       when (DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(connection_date)), '%Y')+0) = 2 then 2
							  				       when (DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(connection_date)), '%Y')+0) = 3 then 3
							  			           when (DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(connection_date)), '%Y')+0) = 4 then 4
							  			           when (DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(connection_date)), '%Y')+0) >= 5 then 5
							  			           end);
            END IF;
        END IF;
    END IF;

END $$

DELIMITER ;

-- Down events
DROP EVENT updateGenerationsAgentsGMM;
DROP EVENT updateGenerationsAgentsVida;

-- Alter statement
ALTER TABLE `agents`
ADD COLUMN `generation_gmm` INT(11) NULL,
ADD COLUMN `generation_vida` INT(11) NULL;

--New changes for tables 
ALTER TABLE `payments` 
DROP COLUMN `agent_generation_gmm`,
CHANGE COLUMN `agent_generation_vida` `agent_generation` VARCHAR(45) NULL DEFAULT NULL ;

ALTER TABLE `policies_vs_users` 
DROP COLUMN `agent_generation_gmm`,
CHANGE COLUMN `agent_generation_vida` `agent_generation` VARCHAR(45) NULL DEFAULT NULL ;
