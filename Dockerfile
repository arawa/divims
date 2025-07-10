#docker build --tag php:parallel --build-arg PUID=$(id -u) --build-arg PGID=$(id -g) --build-arg USER=$(id -un) .
FROM php:8.4-zts

RUN apt-get update \
  && apt-get -y install openssh-client git

RUN pecl install parallel-1.2.7 \
  && docker-php-ext-enable parallel

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
