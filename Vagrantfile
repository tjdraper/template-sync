# Require yaml
require 'yaml'

# Specify Vagrant API version
VAGRANTFILE_API_VERSION ||= "2"

# Specify script paths
vagrantConfig = "scripts/vagrantConfig.yaml"
aliasesPath = "scripts/vagrantAliases"

# Configure Loop
Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
	# Specify the vagrant box to use
	config.vm.box = "buzzingpixel/phpbrew"

	# Load settings from YAML file
	yamlConfig = YAML::load(File.read(vagrantConfig))

	# Check if aliases file exists
	if File.exist? aliasesPath then
		config.vm.provision "file", source: aliasesPath, destination: "~/.bash_aliases"
	end

	# Create a private network
	config.vm.network "private_network", ip: yamlConfig["ipAddress"]

	# Example sync directories
	config.vm.synced_folder __dir__ + "/libraries/ExpressionEngine", "/var/www/html", type: "nfs"
	config.vm.synced_folder __dir__ + "/template_sync", "/var/www/html/system/user/addons/template_sync", type: "nfs"

	# Add ssh keys
	config.vm.provision "file", source: "#{Dir.home}/.ssh/id_rsa.pub", destination: "/home/vagrant/.ssh/id_rsa.pub"
	config.vm.provision "file", source: "#{Dir.home}/.ssh/id_rsa", destination: "/home/vagrant/.ssh/id_rsa"

	# Add public key for SSH access
	config.vm.provision "shell" do |s|
		ssh_pub_key = File.readlines("#{Dir.home}/.ssh/id_rsa.pub").first.strip
		s.inline = <<-SHELL
			sed -i -e "s,. ~/.custom_message,,g" /home/vagrant/.bashrc
			echo ". ~/.custom_message" >> /home/vagrant/.bashrc
			cp /vagrant/scripts/.custom_message /home/vagrant/.custom_message

			echo #{ssh_pub_key} >> /home/vagrant/.ssh/authorized_keys
			echo #{ssh_pub_key} >> /root/.ssh/authorized_keys

			chown vagrant:vagrant /home/vagrant/.ssh/id_rsa.pub
			chmod 0644 /home/vagrant/.ssh/id_rsa.pub

			chmod 0600 /home/vagrant/.ssh/id_rsa
			chown vagrant:vagrant /home/vagrant/.ssh/id_rsa

			cp /home/vagrant/.ssh/id_rsa.pub /root/.ssh/id_rsa.pub
			chown root:root /root/.ssh/id_rsa.pub
			chmod 0644 /root/.ssh/id_rsa.pub

			cp /home/vagrant/.ssh/id_rsa /root/.ssh/id_rsa
			chown root:root /root/.ssh/id_rsa
			chmod 0600 /root/.ssh/id_rsa
		SHELL
	end

	# Set the timesync threshold to 5 seconds, instead of the default 20 minutes, and set timesync to run automatically upon wake.
	config.vm.provider :virtualbox do |v|
		v.customize ["guestproperty", "set", :id, "/VirtualBox/GuestAdd/VBoxService/--timesync-set-threshold", "5000"]
		v.customize ["guestproperty", "set", :id, "/VirtualBox/GuestAdd/VBoxService/--timesync-set-start"]
		v.customize ["guestproperty", "set", :id, "/VirtualBox/GuestAdd/VBoxService/--timesync-set-on-restore", "1"]
	end

	# Run shell script provisioning on first box boot
	config.vm.provision :shell, path: "scripts/vagrantProvision.sh"

	# Run a script at every boot
	config.vm.provision :shell, path: "scripts/vagrantStart.sh", run: "always", privileged: true
end # /Configure Loop
