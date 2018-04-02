CREATE TABLE games (appid integer PRIMARY KEY, name varchar, rating real, votes integer, score real, platforms smallint, release date, price real);

CREATE VIEW top_games AS
SELECT * FROM games ORDER BY score DESC;

CREATE INDEX game_order_index ON games (score DESC);