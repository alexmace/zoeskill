# -*- mode: ruby -*-
# vi: set ft=ruby :
# vagrant plugin install dotenv
#begin
#    require 'dotenv'
#    if File.exists?(File.join(Dir.home, '.env'))
#      Dotenv.load(File.join(Dir.home, '.env'))
#    end
#
#    Dotenv.load
#end

# All Vagrant configuration is done below. The "2" in Vagrant.configure
# configures the configuration version (we support older styles for
# backwards compatibility). Please don't change it unless you know what
# you're doing.
Vagrant.configure("2") do |config|

  # Every Vagrant development environment requires a box. You can search for
  # boxes at https://atlas.hashicorp.com/search.
  config.vm.box = "ubuntu/xenial64"

  config.vm.network :forwarded_port, guest: 80, host: 8080, auto_correct: true
  config.vm.network :forwarded_port, guest: 15672, host: 25672, auto_correct: true

  # Enable provisioning with a shell script. Additional provisioners such as
  # Puppet, Chef, Ansible, Salt, and Docker are also available. Please see the
  # documentation for more information about their specific syntax and use.
  config.vm.provision "ansible" do |ansible|
      ansible.playbook = "playbook.yml"
#      ansible.extra_vars = {
#          db_password: ENV.fetch('DB_PASSWORD', false)
#      }
  end

end
