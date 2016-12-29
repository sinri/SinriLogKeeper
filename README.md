# SinriLogKeeper

View Log From Machine

Version 1.4 
[![Code Climate](https://codeclimate.com/github/sinri/SinriLogKeeper/badges/gpa.svg)](https://codeclimate.com/github/sinri/SinriLogKeeper) 
[![Issue Count](https://codeclimate.com/github/sinri/SinriLogKeeper/badges/issue_count.svg)](https://codeclimate.com/github/sinri/SinriLogKeeper)

----

## Install

1. Just clone or download this project to your server with PHP support.
2. Edit the `slk.config` file, set the security settings and log file paths
3. Make this project visitable, and visit the `index.php`.

### Advance

* About awk

SinriLogKeeper use `awk` to filter the log lines. Sometimes when the log line has too many spaces, you might meet a `bug` that the search engine of GREP reponses empty, but PHP method would not. Actually, the reason might be that awk threw an error like

> awk: program limit exceeded: maximum number of fields size=32767

where you cannot see it.

[StackOverflow](http://stackoverflow.com/questions/24292787/awk-program-limit-exceeded-maximum-number-of-fields-size-32767) tells us. 

> On ubuntu awk is a soft link to some variant of awk, nowadays by default it is mawk. Try to install gawk. gawk does not have a limitation on the number of fields in a record.

So if you found you are using mawk, install gawk instead.

	# check which awk you use
	ls -alh `ls -alh \`which awk\` | awk '{print $11}'`
	# if you were using mawk
	app-get install gawk

## Features Footprints

### Up to Ver 1.1

* Shell wildcard pattern for log file path supported
* Line range support
* Three search methods including Regular Expression support
* Result Limitation to protect the server
* Optional security settings as user auth support

### Since Ver 1.2

* Add Grep Support (Only UNIX-Like(Linux) Supported), which seems to be stronger to deal with huge size files

### Since Ver 1.3

* Add view-around-lines function
* Download log file (Experimental)

### Since Ver 1.4

* Add file filter on page in order to face too many files. Regex supported.

## Reason

I was trapped with Filebeat and Logstash, as filebeat always lost connection to logstash server on Debian.

Why not visit the log files directly on server.

## Users

Now SinriLogKeeper is powering Develop Team of [Leqee Group](http://www.leqee.com/).