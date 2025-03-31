# Wishlist-application
# Overview
This is a web application that allows you to buy gifts for people who have wishes, or to make some yourself.
# How to run this application
In order to run this app using docker, go to the docker folder and run the command docker compose up.
You should have four containers startes on docker. You then run docker exec -it www_docker_symfony bash.
you then go back to root and run symfony server:start --allow-all-ip.
You're now ready to go.
# Initialising the database
This app comes with a database for testing.
In order to use it, execute the command line below:
mysql -u username -p wishlist < init.sql
