#docker build --tag php:parallel --build-arg PUID=$(id -u) --build-arg PGID=$(id -g) --build-arg USER=$(id -un) .
FROM php:8.2-zts

RUN apt-get update \
  && apt-get -y install openssh-client git

#RUN git clone https://github.com/krakjoe/pthreads -b master /tmp/pthreads \
#  && docker-php-ext-configure /tmp/pthreads --enable-pthreads \
#  && docker-php-ext-install /tmp/pthreads

#RUN git clone https://github.com/krakjoe/parallel -b release /tmp/parallel \
#  && docker-php-ext-configure /tmp/parallel --enable-parallel \
#  && docker-php-ext-install /tmp/parallel

#RUN pecl install pthreads-3.1.6 \
#  && docker-php-ext-enable pthreads

RUN pecl install parallel-1.1.4 \
  && docker-php-ext-enable parallel

RUN pecl install stats-2.0.3 \
  && docker-php-ext-enable stats

#RUN pecl install psr-1.2.0 \
#  && docker-php-ext-enable psr

#RUN apt-get -y install libssh2-1-dev \
#  && pecl install ssh2-1.2 \
#  && docker-php-ext-enable ssh2

#RUN  docker-php-ext-install pcntl

# Mail configuration
# install
RUN apt-get update && apt-get install -y msmtp mailutils
# config
COPY msmtprc /etc/msmtprc
RUN chmod 644 /etc/msmtprc
# Set up php sendmail config
RUN echo "sendmail_path=/usr/bin/msmtp -t" >> /usr/local/etc/php/conf.d/sendmail.ini

# accept the arguments from build-args
ARG PUID 
ARG PGID
ARG USER

# Add the group (if not existing) 
# then add the user to the numbered group 
RUN groupadd -g ${PGID} ${USER} || true && \
    useradd --create-home --uid ${PUID} --gid `getent group ${PGID} | cut -d: -f1` ${USER} || true
