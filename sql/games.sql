CREATE TABLE games (appid integer PRIMARY KEY, name text, rating real, votes integer, score real, platforms smallint, release date, price real, tags text[]);

CREATE VIEW top_games AS
SELECT * FROM games ORDER BY score DESC;

CREATE INDEX game_order_index ON games (score DESC);

CREATE INDEX game_release_index ON games (release);

CREATE INDEX game_price_index ON games (price);

CREATE INDEX game_tags_index ON games USING GIN (tags);
