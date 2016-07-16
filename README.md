# SinriLogKeeper

View Log From Machine

Version 1.0

----

## Install

1. Just clone or download this project to your server with PHP support.
2. Edit the `slk.config` file, set the log file paths. Shell wildcard pattern supported.
3. Make this project visitable, and visit the `index.php`.

## Features

* Line Range Support
* Three search methods including Regular Expression support
* Result Limitation to protect the server

## Reason

I was trapped with Filebeat and Logstash, as filebeat always lost connection to logstash server on Debian.

Why not visit the log files directly on server.