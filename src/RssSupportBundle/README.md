RSS Support Bundle
==================

Adds support for collecting data from RSS channels.

Example request:

```
POST /api/feed_source/create HTTP/1.1
Host: localhost:8000
Cache-Control: no-cache

{
    "collectorName": "rss",
    "newsBoard": "981AF0A9-8ED5-4A9F-8C7E-5C79BA53FFD9",
    "defaultLanguage": "pl",
    "sourceData": {
        "url": "http://wolnywroclaw.pl/feed/"
    }
}
```
