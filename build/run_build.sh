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
        \"refresh\": \"30\",
        \"monitor\": {
            \"clients\": {
                \"connected_clients\": {
                    \"id\": \"cc\",
                    \"type\": \"\"
                },
                \"blocked_clients\": {
                    \"id\": \"bc\",
                    \"type\": \"\"
                }
            },
            \"memory\": {
                \"used_memory\": {
                    \"id\": \"um\",
                    \"type\": \"\"
                },
                \"used_memory_rss\": {
                    \"id\": \"umr\",
                    \"type\": \"\"
                },
                \"used_memory_peak\": {
                    \"id\": \"ump\",
                    \"type\": \"\"
                },
                \"used_memory_lua\": {
                    \"id\": \"uml\",
                    \"type\": \"\"
                },
                \"mem_fragmentation_ratio\": {
                    \"id\": \"mf\",
                    \"type\": \"\"
                }
            },
            \"keyspace\": {
                \"keys\": {
                    \"id\": \"k\",
                    \"type\": \"\"
                },
                \"expires\": {
                    \"id\": \"e\",
                    \"type\": \"\"
                }
            }
        }
    }
}";

echo $config > config/config.json