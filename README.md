# SinriLogKeeper

View Log From Machine

Version 1.1

----

## Install

1. Just clone or download this project to your server with PHP support.
2. Edit the `slk.config` file, set the security settings and log file paths
3. Make this project visitable, and visit the `index.php`.

## Features

* Shell wildcard pattern for log file path supported
* Line range support
* Three search methods including Regular Expression support
* Result Limitation to protect the server
* Optional security settings as user auth support

## Reason

I was trapped with Filebeat and Logstash, as filebeat always lost connection to logstash server on Debian.

Why not visit the log files directly on server.