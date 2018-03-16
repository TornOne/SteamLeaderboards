CREATE TABLE games (appid integer PRIMARY KEY, name varchar, rating real, votes integer, score real, platforms smallint, release date, price real);

CREATE VIEW top_games AS
SELECT *, ROW_NUMBER() OVER () FROM games ORDER BY score DESC;