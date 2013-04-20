#!/bin/bash

config="
{
    \"storage\": {
        \"host\": \"localhost\",
        \"port\": \"6379\"
    },

    \"servers\": {
        \"Server01\": {
            \"host\": \"localhost\",
            \"port\": \"6379\",
            \"name\": \"Server Test\"
        }
    },

    \"monitoring\": {
        \"refresh\": \"30\"
    }
}";

echo $config > config/config.json