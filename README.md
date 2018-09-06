# Magento 2 auto clean old cron jobs

Magento 2.2 has issues with giant `cron_schedule` tables. The cron job running time will increase when the table gets bigger, causing heavy CPU usage. 

### Identifing the problem
In one of our stores the `cron_schedule` table exeeded 1.000.000 rows. To identify the problem run the following SQL query:
```sql
SELECT count(*) FROM `cron_schedule`
```

### Solving the problem
Remove the old rows in `cron schedule`: 
```sql
DELETE FROM cron_schedule WHERE  scheduled_at < Date_sub(Now(), interval 24 hour);
```
[Source](https://magento.stackexchange.com/a/208597/28803)

This module will execute the cleanup query once a day.

### Installation
`composer require vendic/magento2-clean-cron-schedule`

### Related issues
- [Issue 11002 on Gitub](https://github.com/magento/magento2/issues/11002)
- [Magento stackexchange](https://magento.stackexchange.com/questions/208592/magento-2-cronjob-bug-mysql-is-always-running-at-30-usage-and-many-php-proces/208597#208597)
