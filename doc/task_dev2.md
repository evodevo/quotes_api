
**Task:** Design system architecture  
**Assigned to:** Developers  
**Estimate:** 1h  
**Description:** Create initial system architecture for the Quotes API. The resulting diagram will 
be used by the developers, QA and DevOps as a reference for other tasks.  

The architecture should follow the following principles:
- It should be easily extendable with new features in the future
- It should follow Domain Driven Design principles, therefore It should be composed of the 
following layers: Application, Domain, Infrastructure. A clear separation between these layers 
should be maintained. 
- It should support Command and Query separation from the beginning so that it can be easily scaled in the future
- It should include components that implement requirements given in User Story 1
- It should take into account that multiple quote data sources might be used

**Deliverable:** system architecture diagram (quotes_api.png)