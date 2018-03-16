import psycopg2, os

conn = psycopg2.connect(os.environ['DATABASE_URL'], sslmode='require')

with conn:
    with conn.cursor() as curs:
        curs.execute("DELETE FROM games;")
        curs.copy_from(open("games.txt", "r"), "games")

conn.close()
