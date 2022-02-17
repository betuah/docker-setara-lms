#!/bin/sh
mongorestore -u $MONGO_USER -p $MONGO_PASSWORD -d lms /data/backup/lms --authenticationDatabase admin
mongorestore -u $MONGO_USER -p $MONGO_PASSWORD -d log_lms /data/backup/log_lms --authenticationDatabase admin