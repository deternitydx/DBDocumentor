Postgres Database Documentor
============

This script reads the schema information for a Postgres database and exports it in Markdown format.  The format is as follows:

```
Table Name
===========

Columns
----------
* Column name
    * *Type: column type*
    * Description: posgres-stored description of the column
...
```

The script can be run, using php: *php documentdb.php hostname dbname user passwd [title] [author]*

The title and author are optional parameters.
