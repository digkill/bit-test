FROM mariadb:10.2
RUN apt-get update && apt-get install -y locales && rm -rf /var/lib/apt/lists/* $ && localedef -i ru_RU -c -f UTF-8 -A /usr/share/locale/locale.alias ru_RU.UTF-8
ENV LANG ru_RU.utf8
