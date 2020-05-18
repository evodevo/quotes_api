
**Task:** Implement quotes caching layer  
**Assigned to:** Developers  
**Estimate:** 3 - 4h  
**Description:** In order to avoid hitting expensive quotes data source and have a faster response time,
implement caching layer for the shouted quotes. Caching layer architecture is presented in the 
[quotes_api.png](https://github.com/evodevo/quotes_api/tree/master/doc/quotes_api.png) diagram.  

**Requirements:**
- Data source should not be hit twice for the same author give a time interval of 60 seconds.
- Caching of items should be done through RabbitMQ. Instead of directly writing to Redis, 
a message should be sent to RabbitMQ, and there must be at least 2 workers reading from the 
queue and storing the items in redis.  

**Constraints:**
- Redis should be used as a cache storage. 
- RabbitMQ should be used as a messaging system.

**Points to note:**
- When someone requests quotes from non existent author, cache an empty response,
so that we also avoid repeatedly hitting the expensive data source for non existent data.
- Cache the maximum allowed limit of quotes per author (10), so that we avoid unnecessarily
hitting the data source when the limit changes.
- When frequently accessed cache entry expires, cache stampede can be caused which consequently crashes the
system. To avoid that, the first cache update request should lock this cache entry so that
all subsequent requests do not try to update the same cache entry at the same time. They should wait
for a certain time interval until this entry is computed and use the computed result.

**Possible optimization:**
- Keep serving stale cache items for a certain period of time after they are expired so that
they can be updated in the background without affecting client response waiting times.
