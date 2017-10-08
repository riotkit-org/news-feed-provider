Definitions
===========

- **NewsBoard:** A board that contains news and articles
- **FeedSource:** Pointer to some location eg. a portal from where to look for new data (through RSS for example)
- **FeedEntry:** News, article or publication fetched from `FeedSource` that appears on `NewsBoard`
- **Specification:** Internal information for the collector where to look for the information
                 may contain such things as url address, api key or other fields (in payload known as "sourceData")
- **ScrapingSpecification:** Optional information for the spider, tells how to extract additional information from page HTML source code
- **Collector:** Is a mechanism that downloads articles, news, publications from external source of one type.
             eg. A `RSS Collector` is able to collect entries from `FeedSource` sources
- **Spider:** Fetches additional information from page HTML source code, such as: full page content, article image
- **HarvestingMachine:** Controller that uses multiple `Collector` to fetch data from multiple `FeedSource`
                     eg. from `RssCollector`, `FacebookCollector`, other social media through API
