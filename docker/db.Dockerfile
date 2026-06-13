FROM mariadb:10.11

COPY banhang.sql /docker-entrypoint-initdb.d/01-banhang.sql
COPY order_timeout_settings.sql /docker-entrypoint-initdb.d/02-order-timeout-settings.sql
