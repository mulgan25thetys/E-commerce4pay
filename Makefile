.PHONY: dump
dump: var/dump ## Génère un dump SQL
	$(de) db sh -c 'mysqldump -u admintherese -p"root" "thereseDATABASES" --dump-date --no-autocommit --single-transaction --hex-blob --triggers -R -E  > /var/www/var/dump/dump.sql'

.PHONY: dumpimport
dumpimport: ## Import un dump SQL
	$(de) db sh -c 'mysql -u admintherese -p"root" thereseDATABASES < /var/www/var/dump/dump.sql'
