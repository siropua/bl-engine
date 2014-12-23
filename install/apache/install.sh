THIS_DIR=`pwd`
INSTALL_DIR=`dirname ${THIS_DIR}`
ENGINE_DIR=`dirname ${INSTALL_DIR}`
SITE_DIR=`dirname ${ENGINE_DIR}`
SITE_NAME=`basename ${SITE_DIR}`

echo $SITE_NAME

cp apache-host.template apache-host.template2

sed "s,_SITE_DIR_,${SITE_DIR},g" apache-host.template > apache-host.template2
sed "s/_SITE_NAME_/${SITE_NAME}/g" apache-host.template2 > /etc/apache2/other/vhost-$SITE_NAME.conf
rm apache-host.template2

apachectl restart

# echo 127.0.0.1 $SITE_NAME.local >> /etc/hosts

