#!/usr/bin/make

es::
	docker run \
          --name elasticsearch \
          -p 9200:9200 \
          -e discovery.type=single-node \
          -e ES_JAVA_OPTS="-Xms1g -Xmx1g"\
          -e xpack.security.enabled=false \
          -it \
          --rm \
          docker.elastic.co/elasticsearch/elasticsearch:8.9.0