## 2026-03-11 - [Eager Loading for Service Statistics]

**Learning:** Calculating annual service hours by iterating through `Service` entities and their associations (`VolunteerService` -> `Fichaje`) causes massive N+1 query overhead (O(N) queries where N is the number of services).

**Action:** Always use eager loading (`->select('s', 'vs', 'f')`) when querying entities whose collections will be traversed in a loop for statistical calculations.

## 2026-03-11 - [Direct Scalar Counts for Dashboard KPIs]

**Learning:** Fetching full entity collections just to perform a `count()` in PHP is extremely memory-inefficient and slows down the dashboard as the database grows.

**Action:** Implement dedicated `countX()` methods in repositories that return a single scalar result using DQL `count(m.id)`.
