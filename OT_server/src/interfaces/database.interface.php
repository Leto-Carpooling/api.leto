<?php

    interface DatabaseInterface{
        /**
         * Connects to the database using the information in the .env folder.
         */
        public function connect();

        public function selectFromTable($table, $columns, $condition_columns, $condition_values, $operator = 'and');
        public function insertIntoTable($table, $columns, $values);
        public function deleteFromTable($table, $condition_columns, $operator = 'and');
        public function rawSql($sql);
        public function close();
    }

?>