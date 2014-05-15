Kimix
=====

### Requirements
- PHP 5.3.7 or later
- PDO extension
- Web server with support for URL rewrites

### Recommended
- GD Library
- MySQL server

### Updating
Simply pulling the repo again should be safe for updates. If something breaks after updating, clearing the APC cache or the tmp/ and tmp/cache/ directories of everything except .gitignore will usually solve the problem.

### Internal details
Kimix uses the [Fat Free Framework](http://fatfreeframework.com/home) as it's base, allowing it to have a simple but powerful feature set without compromising performance. Every template file is compiled at run time, and only needs to be recompiled when the code is changed. Kimix includes internal caching that prevents duplicate or bulk database queries from being used, greatly improving performance on large pages with lots of data. This caching will only work if the tmp/cache/ directory is writeable or APC is installed and configured on the server.
