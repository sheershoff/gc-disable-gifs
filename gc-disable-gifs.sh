#!/bin/bash
cd downloaded 
wget -nv -O- https://github.com/composer/composer/commit/ac676f47f7bbc619678a29deae097b6b0710b799 | \
	 egrep -o "http://[^[:space:]]*.gif" | \
	 xargs -P 10 -n 1 wget -nv
