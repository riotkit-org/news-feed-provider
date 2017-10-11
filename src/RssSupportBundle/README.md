RSS Support Bundle
==================

Adds support for collecting data from RSS channels.

Example request:

```
POST /api/feed_source/newsBoard-federacja/create HTTP/1.1
Host: localhost:8000
Cache-Control: no-cache

{
    "id": "fa-wroclaw",
    "title": "FA Wrocław",
    "collectorName": "rss",
    "sourceData": {
        "url": "http://wolnywroclaw.pl/feed/"
    },
    "defaultLanguage": "pl",
    "description": "Federacja Anarchistyczna - sekcja Wrocław",
    "enabled": true,
    "scrapingSpecification": {
        "removePaths": [
            "//*[@id='main-content']/article/div[1]/div"
        ],
        
        "contentPath": "//*[@id='main-content']/article/div[1]"
    }
}
```
