CC = gcc
CFLAGS = -g -I/usr/include/glib-2.0 -I/usr/lib/x86_64-linux-gnu/glib-2.0/include -lglib-2.0 -lpthread

all: main

clean: 
	rm -rf target/

main: src/main.c
	mkdir -p target
	$(CC) src/main.c $(CFLAGS) -o target/main 

run: main
	echo "Finishing compiling!"
	./target/main 