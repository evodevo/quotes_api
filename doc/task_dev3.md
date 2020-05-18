
**Task:** Implement quotes shouting API endpoint  
**Assigned to:** Developers  
**Estimate:** 2h  
**Description:** The goal is to develop a REST API endpoint that returns quotes shouted from famous people.  

**Requirements:**
- Given the author name, return quotes shouted from this author. 
  Shouting a quote consists of transforming it to uppercase and adding an exclamation mark at the end. 
- Quotes request must have an optional `limit` parameter. 
  If `limit` parameter is not provided or is equal to 0, a default limit of 10 must be used.
  If `limit` parameter is less than 0 or greater than 10, API should return an error.
- Author slug should consist of lowercase letters, numbers and dashes only
- API should have an auto-generated documentation from Swagger annotations
  
API should have the following endpoint:
```
GET http://awesomequotesapi.com/shout/{authorSlug}?limit={quotesLimit}
```
`authorSlug` - A slug made from author's name that identifies this author uniquely.

`quotesLimit` - a maximum number of quotes to return from the given author.

## Examples

Given the following quotes from Steve Jobs:
- "The only way to do great work is to love what you do.",
- "Your time is limited, so don’t waste it living someone else’s life!"

When you make the following request:
```
GET http://awesomequotesapi.com/shout/steve-jobs?limit=2
```
Then you should get the following response:
```
[
    "THE ONLY WAY TO DO GREAT WORK IS TO LOVE WHAT YOU DO!",
    "YOUR TIME IS LIMITED, SO DON’T WASTE IT LIVING SOMEONE ELSE’S LIFE!"
]
```

**Constraints:**
- MySQL must be used as the main data storage
  