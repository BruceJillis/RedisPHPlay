Win32 port of Redis server, client and utils
--------------------------------------------

It is made to be as close as possible to original unix version.
You can download prebuilt binaries here: 

   http://github.com/dmajkic/redis/archives/master


Building Redis on Windows
-------------------------

Building Redis on Windows requires MinGW. If you are using mSysGit,
you allready have all tools needed for the job. 

Start Git bash, and clone this repository:

   $ git clone http://github.com/dmajkic/redis.git

Compile it:

   $ make 

Test it: 

   $ make test 

Compiled programs are in source dir, and have no external dependencies.

You can use your own MinGW installation, RubyInstaller DevKit, or TDM. 
Note that you will need Tcl installed for testing. 

  
What is done and what is missing
--------------------------------

Commands that use fork() to perform backgroud operations are implemented 
as foreground operations. These are BGSAVE and BGREWRITEAOF. 
Both still work - only in foreground. All original tests pass.

Since I work on 32bit, 64bit compilation is not something I tried,
but compilation shoud be straight forward, please - try. Fork and pull
requests if something should be changed to support 64bits. 

Everything else is ported: redis-cli with linenoise, rdb dumps, 
virtual memory with threads and pipes, replication, all commands, etc.

32bit Redis server can be used from 64bit apps. 
You can install and use all ruby gems that use Redis on windows.
You can develop on windows with local, native Redis server.
You can use redis-cli.exe to connect to unix servers.
...


Future plans
------------ 

Run tests, fix bugs, try to follow what Salvatore and Pieter are coding.

This port is bare. Redis-server.exe is console application, that can
be started from console or your app. It is not true Windows Service 
app, so there is space to make it SCM aware. 

Please, see redis README for more info.

That's it. Enjoy. 

Regads,
Dusan Majkic

