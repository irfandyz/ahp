## Migration Order

The migrations are designed to run in the correct order:

1. **Master Data** - Creates reference tables
2. **Operational** - Creates operational tables (depends on master data)
3. **Expedition** - Creates expedition tables (depends on master and operational data)
4. **System** - Creates system tables (independent)
5. **Triggers** - Creates database triggers (depends on expedition tables)
6. **Sessions** - Creates session management table (independent)

System Tables
├── users
├── cache
├── cache_locks
├── jobs
└── failed_jobs

Sessions Table
└── sessions
