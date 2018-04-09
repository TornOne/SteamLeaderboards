import psycopg2, os

def query(q):
    curs.execute(q)
    return curs.fetchall()

def print_query(q):
    q = query(q)
    for line in q:
        for result in line:
            print result,
        print

def reset():
    conn.rollback()

def get_user_tables():
    return query("SELECT * FROM pg_tables WHERE schemaname NOT IN ('pg_catalog', 'information_schema');")

def print_user_tables():
    print "Schema name\tTable name\tOwner name"
    for row in get_user_tables():
        print row[0] + "\t" + row[1] + "\t" + row[2]

def get_system_views():
    return query("SELECT viewname, definition FROM pg_views WHERE schemaname = 'pg_catalog';")

def print_system_views():
    for row in get_system_views():
        print row[0]

def get_user_views():
    return query("SELECT * FROM pg_views WHERE schemaname NOT IN ('pg_catalog', 'information_schema');")

def print_user_views():
    for row in get_user_views():
        print row[0], "\t", row[1], "\t", row[2]
        print row[3]
        print

def get_user_functions():
    return query("SELECT * FROM pg_proc WHERE proowner != 10;")

def print_user_functions():
    for row in get_user_functions():
        print row[0], "\t", row[1], "\t", row[2]
        print row[-4]
        print

#EXPLAIN ANALYZE ...

conn = psycopg2.connect(os.environ["DATABASE_URL"], sslmode="require")
curs = conn.cursor()
