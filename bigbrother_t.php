<body>
<?php
$conn = pg_connect(getenv("DATABASE_URL"));

pg_prepare($conn, "get_browsers", 'SELECT * FROM bb_browsers;');
$result_browsers = pg_execute($conn, "get_browsers", array());

pg_prepare($conn, "get_platforms", 'SELECT * FROM bb_platforms');
$result_platforms = pg_execute($conn, "get_platforms", array());

pg_prepare($conn, "get_times", 'SELECT * FROM bb_times');
$result_times = pg_execute($conn, "get_times", array());

while ($row = pg_fetch_row($result_browsers)) {
    echo nl2br("$row[0]:  $row[1]\n");
}
echo nl2br("\n");
while ($row = pg_fetch_row($result_platforms)) {
    echo nl2br("$row[0]:  $row[1]\n");
}
echo nl2br("\n");
while ($row = pg_fetch_row($result_times)) {
    echo nl2br("$row[0]:  $row[1]\n");
}
echo nl2br("\n\n");
var_dump($_SERVER["SSL_CLIENT_CERT"]);
print_r($_SERVER["SSL_CLIENT_CERT"]);

pg_free_result($result_browsers);
pg_free_result($result_platforms);
pg_free_result($result_times);
pg_close($conn);
?>
</body>