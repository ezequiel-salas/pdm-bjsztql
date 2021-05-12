#!/bin/sh
# !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
# Please don't fucking execute this script on your local machine
# !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
psql -f ./ddl/up.sql
psql -f ./ddl/raw_data.sql
psql -f ./dml/data_transfer.sql
psql -f ./dml/song_relations.sql
psql -f ./dml/album_relations.sql
psql -f ./dml/song_play_generator.sql

cat ./scripts/*_functions.sql > ./scripts/functions.sql

psql -f ./scripts/functions.sql

psql -f ./ddl/drop_raw_data.sql
