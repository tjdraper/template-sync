#!/usr/bin/env bash

###################################
# Prepare things
###################################

# Remove all virtual hosts
sudo rm -rf /etc/apache2/sites-available/*;
sudo rm -rf /etc/apache2/sites-enabled/*;

# Make apache load headers module
ln -sf /etc/apache2/mods-available/headers.load /etc/apache2/mods-enabled/;





###################################
# mysql setup
###################################

# Create site database if it does not exist
mysql -uroot -proot -e "CREATE DATABASE IF NOT EXISTS site;";

# Import the most recent DB dump if it exists
if [ -e "/vagrant/localStorage/dbBackups/site_latest.sql" ]; then
	mysql -uroot -proot site < /vagrant/localStorage/dbBackups/site_latest.sql;
fi





###################################
# Apache setup
###################################

# Place apache site config
sudo cp /vagrant/scripts/site.conf /etc/apache2/sites-available/site.conf;
sudo ln -s /etc/apache2/sites-available/site.conf /etc/apache2/sites-enabled/site.conf;





###################################
# 30 min database backup cron
###################################

# Set up DB Backups cron for every 30 minutes if it has not been setup
if [ ! -f /var/log/cronSetup ]; then
	# Make sure script is executable
	chmod +x /vagrant/scripts/vagrantDbBackups.sh;

	# Echo out the cron command
	echo "*/30 * * * * /vagrant/scripts/vagrantDbBackups.sh >/dev/null 2>&1" >> cron;

	# Set the cron file as a cron job
	crontab cron;

	# remove the cron file
	rm cron;

	# Write the file cronSetup so we know it's already been setup
	echo 'cron setup' > /var/log/cronSetup;
fi





###################################
# Restart services
###################################

# Restart apache
sudo service apache2 restart;
