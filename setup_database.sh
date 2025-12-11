#!/bin/bash

# This script will drop and recreate your 'thesis' database.
# It will then execute all the SQL scripts to build the schema and seed the data.
# Please make sure you have the 'mysql' command-line tool installed and in your PATH.

# Database credentials (please verify these are correct)
DB_USER="root"
DB_PASS=""
DB_HOST="127.0.0.1"
DB_PORT="3305"
DB_NAME="thesis"

# --- WARNING: This will delete all data in the 'thesis' database. ---

# Drop and create the database
echo "Attempting to drop and re-create the database: '$DB_NAME'..."
mysql -u "$DB_USER" -h "$DB_HOST" -P "$DB_PORT" -e "DROP DATABASE IF EXISTS $DB_NAME;"
mysql -u "$DB_USER" -h "$DB_HOST" -P "$DB_PORT" -e "CREATE DATABASE $DB_NAME;"
echo "Database '$DB_NAME' created successfully."
echo ""

# SQL files to execute in order
SQL_FILES=(
  "1_schema_tables/dev1_users_and_setup.sql"
  "1_schema_tables/dev2_groups_and_proposals.sql"
  "1_schema_tables/dev3_workflow_and_final.sql"
  "2_schema_relations/dev1_relations.sql"
  "2_schema_relations/dev2_relations.sql"
  "2_schema_relations/dev3_relations.sql"
  "3_seeds/01_seed_users.sql"
  "3_seeds/02_seed_courses.sql"
  "3_seeds/03_seed_groups.sql"
  "3_seeds/04_workflow_seed.sql"
)

# Execute each SQL file against the new database
for sql_file in "${SQL_FILES[@]}"; do
  if [ -f "$sql_file" ]; then
    echo "Executing $sql_file..."
    mysql -u "$DB_USER" -h "$DB_HOST" -P "$DB_PORT" "$DB_NAME" < "$sql_file"
  else
    echo "Warning: SQL file not found: $sql_file"
  fi
done

echo ""
echo "Database setup complete. The schema has been loaded from your SQL files."
