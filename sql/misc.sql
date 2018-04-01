CREATE TABLE last_refresh (time timestamp PRIMARY KEY);

CREATE OR REPLACE FUNCTION reset_refresh_time() RETURNS void AS $$
	DELETE FROM last_refresh;
	INSERT INTO last_refresh VALUES ("now");
$$ LANGUAGE SQL;

CREATE OR REPLACE FUNCTION error_refresh_time() RETURNS void AS $$
	DELETE FROM last_refresh;
	INSERT INTO last_refresh VALUES ("2030-01-01 00:00:00");
$$ LANGUAGE SQL;