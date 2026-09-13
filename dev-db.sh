#!/usr/bin/env bash
# PostgreSQL local dédié au projet LTA (sans sudo, port 5433).
# Usage : ./dev-db.sh {start|stop|status|psql}
set -euo pipefail

export PATH="/usr/lib/postgresql/18/bin:$PATH"
PGDATA="$(cd "$(dirname "$0")" && pwd)/.pgdata"
PORT=5433

case "${1:-start}" in
  start)
    pg_ctl -D "$PGDATA" -o "-p $PORT -k /tmp -c listen_addresses=127.0.0.1" \
           -l "$PGDATA/server.log" -w start
    ;;
  stop)   pg_ctl -D "$PGDATA" -m fast -w stop ;;
  status) pg_ctl -D "$PGDATA" status ;;
  psql)   PGPASSWORD=lta_dev_pwd psql -h 127.0.0.1 -p "$PORT" -U lta_user -d lta_webapp ;;
  *) echo "Usage: $0 {start|stop|status|psql}" ; exit 1 ;;
esac
