#!/bin/sh

# создаём все необходимые папки


INSTALL_DIR=`pwd`
ENGINE_DIR=`dirname ${INSTALL_DIR}`
ROOT_DIR=`dirname ${ENGINE_DIR}`



if [ ! -d ${ROOT_DIR}/configs ]; then
	echo "Creating config dir"
	mkdir ${ROOT_DIR}/configs
fi
chmod 777 ${ROOT_DIR}/configs

cp ${INSTALL_DIR}/configs/.htaccess ${ROOT_DIR}/configs/

if [ ! -d ${ROOT_DIR}/users-data ]; then
	echo "Creating users dir"
	mkdir ${ROOT_DIR}/users-data
	echo 'RewriteEngine Off' > ${ROOT_DIR}/users-data/.htaccess
fi

if [ ! -d ${ENGINE_DIR}/var ]; then
	echo "Creating temporarty dir"
	mkdir ${ENGINE_DIR}/var
	mkdir ${ENGINE_DIR}/var/templates_c
	mkdir ${ENGINE_DIR}/var/logs
	chmod -R 0777 ${ENGINE_DIR}/var
fi



chmod 777 ${ROOT_DIR}/users-data

# делаем все временные каталоги писабельными
chmod -R 777 ${ENGINE_DIR}/var

cp ${INSTALL_DIR}/index/* ${ROOT_DIR}/
cp ${INSTALL_DIR}/index/.htaccess ${ROOT_DIR}/

chmod +x ${ENGINE_DIR}/cron/*.php


if [[ ! `crontab -l | grep "${ENGINE_DIR}"` ]]; then
	read -p "Add scripts to crontab? [y/n]" NEED2CRON

	if [ "${NEED2CRON}" == "" ] || [ ${NEED2CRON} == 'y' ] || [ ${NEED2CRON} == 'Y' ]; then

		crontab -l > ${ENGINE_DIR}/var/tmp/cron-file; echo "* * * * * ${ENGINE_DIR}/cron/post2blog.php >/dev/null 2>&1" >> ${ENGINE_DIR}/var/tmp/cron-file; crontab ${ENGINE_DIR}/var/tmp/cron-file; rm -f ${ENGINE_DIR}/var/tmp/cron-file
		crontab -l > ${ENGINE_DIR}/var/tmp/cron-file; echo "*/5 * * * * ${ENGINE_DIR}/cron/mail-monitor.php >/dev/null 2>&1" >> ${ENGINE_DIR}/var/tmp/cron-file; crontab ${ENGINE_DIR}/var/tmp/cron-file; rm -f ${ENGINE_DIR}/var/tmp/cron-file
		crontab -l > ${ENGINE_DIR}/var/tmp/cron-file; echo "*/10 * * * * ${ENGINE_DIR}/cron/importFromTwitter.php >/dev/null 2>&1" >> ${ENGINE_DIR}/var/tmp/cron-file; crontab ${ENGINE_DIR}/var/tmp/cron-file; rm -f ${ENGINE_DIR}/var/tmp/cron-file
		crontab -l > ${ENGINE_DIR}/var/tmp/cron-file; echo "*/10 * * * * ${ENGINE_DIR}/cron/importFromLJ.php >/dev/null 2>&1" >> ${ENGINE_DIR}/var/tmp/cron-file; crontab ${ENGINE_DIR}/var/tmp/cron-file; rm -f ${ENGINE_DIR}/var/tmp/cron-file

	fi
fi



read -p "Create default site structure? [y/n]" NEED2CREATE

if [ "${NEED2CREATE}" == "" ] || [ ${NEED2CREATE} == 'y' ] || [ ${NEED2CREATE} == 'Y' ]; then
	echo 'CREATING...'
	cp -R ${INSTALL_DIR}/site ${ROOT_DIR}/
	rm -Rf ${ROOT_DIR}/site/.svn
	rm -Rf ${ROOT_DIR}/site/*/.svn
fi




echo "Done."
