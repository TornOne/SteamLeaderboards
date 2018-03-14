import psycopg2, os

DATABASE_URL = os.environ['DATABASE_URL']

conn = psycopg2.connect(DATABASE_URL, sslmode='require')

with conn:
    with conn.cursor() as curs:
        curs.execute("DELETE FROM games;")
        curs.copy_from(open("games.txt", "r"), "games")

conn.close()
