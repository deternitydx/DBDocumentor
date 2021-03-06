<?php

if ($argc < 5)
        die ("Usage: php documentdb.php hostname dbname user passwd [title] [author]\n");

list ($junk, $host, $db, $user, $passwd, $title, $author) = $argv;
$db = pg_connect("host=$host dbname=$db user=$user password=$passwd");

$result = pg_query($db, "SELECT table_name, table_type FROM information_schema.tables WHERE table_schema = 'public' AND table_type = 'BASE TABLE' ORDER BY table_name ASC;");
if (!$result) {
      echo "An error occured.\n";
      die();
}

echo "% $title\n% $author\n% \n\n";
$tables = pg_fetch_all($result);

foreach ($tables as $table) {
    $tablename = $table["table_name"];

    echo "$tablename\n=========================\n\n";

    $result = pg_query($db, "
            SELECT c.column_name,c.data_type,pgd.description, c.ordinal_position
            FROM information_schema.columns c left outer join
            pg_catalog.pg_statio_all_tables st on (c.table_schema=st.schemaname and c.table_name=st.relname)
              left outer join pg_catalog.pg_description pgd on (pgd.objoid=st.relid and pgd.objsubid=c.ordinal_position)
                   WHERE c.table_name = '$tablename' ORDER BY c.ordinal_position ASC;");

    $columns = pg_fetch_all($result);
    echo "Columns\n---------------\n\n";
    foreach($columns as $column) {
        if (!isset($column['description'])) $column['description'] = "";
        echo "* **{$column['column_name']}**\n     * *Type: {$column['data_type']}*\n     * Description: {$column['description']}\n";
    }

    echo "\n\n";
}

?>
