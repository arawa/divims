#!/usr/bin/env bash

# Retrieve new data
##BEGIN-INSERT
OLD_DOMAIN=
OLD_INTERNAL_IPV4=
OLD_EXTERNAL_IPV4=
OLD_EXTERNAL_IPV6=
NEW_DOMAIN=
NEW_INTERNAL_IPV4=
NEW_EXTERNAL_IPV4=
NEW_EXTERNAL_IPV6=
LETSENCRYPT=
##END-INSERT

if [ "$LETSENCRYPT" = "true" ]; then
  # Require Letsencrypt certificate
  echo "Require new certificate for $NEW_DOMAIN"
  unlink /etc/nginx/sites-enabled/bigbluebutton
  service nginx restart
  certbot certonly --non-interactive -a webroot --webroot-path /var/www/html --domain $NEW_DOMAIN --email tech@arawa.fr --agree-tos --rsa-key-size=4096 --renew-hook="systemctl restart nginx"
  #Reconfigure nginx
  sed -i -e "s/${OLD_DOMAIN}/${NEW_DOMAIN}/" /etc/nginx/sites-available/bigbluebutton
  (cd /etc/nginx/sites-enabled && ln -s ../sites-available/bigbluebutton)
  service nginx reload
  echo "Delete old certificate"
  certbot delete --cert-name $OLD_DOMAIN
  sed -i -e "s#/var/www/html#/var/www/bigbluebutton-default#" /etc/letsencrypt/renewal/${NEW_DOMAIN}.conf
fi


# Reconfigure BBB
echo "Reconfigure BBB domain"
bbb-conf --setip $NEW_DOMAIN

# Modify configuration files
echo "Alter configuration files"
echo "Updating domain"
FILES="/etc/nginx/sites-available/bigbluebutton /root/bbb-exporter/secrets.env"
for FILE in $FILES; do
  echo "- $FILE"
  if [ -f "$FILE" ]; then
    #sed -i -r -e "s/${OLD_EXTERNAL_IPV6}/${NEW_EXTERNAL_IPV6}/g" -e "s/${OLD_INTERNAL_IPV4}/${NEW_INTERNAL_IPV4}/g" "$FILE"
    sed -i -r -e "s/${OLD_DOMAIN}/${NEW_DOMAIN}/g" "$FILE"
    #grep $NEW_INTERNAL_IPV4 "$FILE"
    #grep $NEW_EXTERNAL_IPV6 "$FILE"
    grep $NEW_DOMAIN $FILE
  fi
done

echo "Updating external IP"
FILES="/lib/systemd/system/dummy-nic.service"
for FILE in $FILES; do
  echo "- $FILE"
  if [ -f "$FILE" ]; then
    sed -i -r -e "s/${OLD_EXTERNAL_IPV4}/${NEW_EXTERNAL_IPV4}/g" "$FILE"
    grep $NEW_EXTERNAL_IPV4 "$FILE"
  fi
done

# Add dummy NIC fort external IP
ip addr del ${OLD_EXTERNAL_IPV4}/32 dev lo
ip addr add ${NEW_EXTERNAL_IPV4}/32 dev lo

echo "Restart bbb-exporter"
service bbb-exporter restart

echo "Restart BBB"
bbb-conf --clean
bbb-conf --secret
