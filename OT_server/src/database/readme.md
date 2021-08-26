# database

This folder contains the schema of the SQL and NoSQL databases.

## Leto NoSQL Naming conventions

The naming of ids are as follows,  

 - group id: `gid-xxx`
 - route id: `rid-xxx`
 - user id: `uid-xxx`

 The user id in indices are just `xxx`: true.

 ### Leto NoSQL groups database

 The `pickUpRoute` property has been changed to `pickUpPointId`, which is the placeId from which the driver is to pick everyone up.

