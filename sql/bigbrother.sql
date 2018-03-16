CREATE TABLE bigbrother (
    id SERIAL,
    browser VARCHAR(50),
    requestTime TIME,
    platform VARCHAR(50),
    PRIMARY KEY(id)
);

CREATE VIEW bb_browsers AS
SELECT browser, count(*) FROM bigbrother GROUP BY browser ORDER BY count(*) DESC;

CREATE VIEW bb_platforms AS
SELECT platform, count(*) FROM bigbrother GROUP BY platform ORDER BY count(*) DESC;

CREATE VIEW bb_times AS
SELECT EXTRACT(HOUR FROM requestTime), count(*) FROM bigbrother GROUP BY EXTRACT(HOUR FROM requestTime) ORDER BY EXTRACT(HOUR FROM requestTime);

CREATE OR REPLACE FUNCTION insert_bb(VARCHAR(50), TIME, VARCHAR(50)) RETURNS void AS $$
	INSERT INTO bigbrother (browser, requestTime, platform) VALUES ($1, $2, $3)
$$ LANGUAGE SQL;