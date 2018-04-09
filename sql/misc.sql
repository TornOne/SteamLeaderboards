CREATE TABLE config_dates (key varchar PRIMARY KEY, value timestamp);

INSERT INTO config_dates VALUES ('attempted_refresh_time', '2018-01-01 00:00:00');
INSERT INTO config_dates VALUES ('completed_refresh_time', '2018-01-01 00:00:00');
INSERT INTO config_dates VALUES ('failed_refresh_time', '2018-01-01 00:00:00');

CREATE OR REPLACE FUNCTION update_attempted_refresh_time() RETURNS void AS $$
UPDATE config_dates SET value = 'now' WHERE key = 'attempted_refresh_time';
$$ LANGUAGE SQL;

CREATE OR REPLACE FUNCTION update_completed_refresh_time() RETURNS void AS $$
UPDATE config_dates SET value = 'now' WHERE key = 'completed_refresh_time';
$$ LANGUAGE SQL;

CREATE OR REPLACE FUNCTION update_failed_refresh_time() RETURNS void AS $$
UPDATE config_dates SET value = 'now' WHERE key = 'failed_refresh_time';
$$ LANGUAGE SQL;