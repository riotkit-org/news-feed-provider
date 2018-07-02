Wolnościowiec News Feed Provider
================================

Microservice.

Provides news from various sources - blogs, portals, facebook pages and exposes via API.

Free software
-------------

Created for an anarchist portal, with aim to propagate the freedom and grass-root social movements,
to automate things for building a better world. Viva la revolución.

- https://wolnosciowiec.net/#/flash
- http://iwa-ait.org
- http://zsp.net.pl
- http://federacja-anarchistyczna.pl/

### Setup

Clone the repository, set up a MySQL database, execute `make deploy`

### Getting started

Application is fully manageable by the HTTP API, so you can run eg. a `postman` to add news boards and attach feed sources.
There is an example postman collection exported to file `postman.json`

##### 1. Creating a news board

A news board is a board where your articles will appear, it's like a time line on facebook or twitter.

`{{DOMAIN}}/api/news_board/create`

```
{
    "id": "federacja",
    "name": "Federacja Anarchistyczna",
    "description": "Działająca na terenie Polski federacja grup anarchistycznych. Istniejąca od 1988 roku (początkowo jako Międzymiastówka Anarchistyczna). Dąży do „stworzenia samorządnego społeczeństwa, tworzonego na zasadzie dobrowolności”"
} 
```

##### 2. Add sources that will import articles to your news board

`{{DOMAIN}}/api/feed_source/newsBoard-federacja/create`

```
{
    "id": "fa-wroclaw",
    "title": "FA Wrocław",
    "collectorName": "rss",
    "icon": "http://wolnywroclaw.pl/wp-content/uploads/2015/05/icon-555f99cdv1_site_icon-256x256.png",
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

Explanation:
- collectorName: Must point to a valid name of supported collector, actually only "rss" is bundled by default
  by the plans are to add also a "facebook_page"
  
- id: You will use it to filter by a source for example
- defaultLanguage: It's a fallback for articles that do not expose a language via eg. RSS
- sourceData: Describes parameters that are passed to the collector, in this example to "rss" collector which
  requires only URL
- scrapingSpecification: Scrapper is a tool that is able to attempt to extract full article content from linked article page, and remove ads
  everything is operated by xPaths (OPTIONAL)
  
##### 3. Browsing feed sources 

You may want to create an option that will allow to select from which sources to show the content.
There is an endpoint that shows this list and allows filtering it.

`{{DOMAIN}}/api/feed_source/newsBoard-federacja/search`

```
{
    "title": "FA"
}
```

##### 4. Displaying the list of articles

`{{DOMAIN}}/public/api/feed/federacja/browse/1/5`

```
{
    "lanaguage": ["pl"],
    "exceptFeedSource": ["fa-krakow"]
}
```

Explanation:
- /browse/1/5 - the first number is a page number, the second is a count of elements to return per page
- language: Allows to filter by content language (OPTIONAL)
- exceptFeedSource: Excludes feed sources by id (OPTIONAL)
- See `{{DOMAIN}}/public/api/feed/search/help` endpoint for more options

### Todo:
- [ ] Complete test coverage
- [ ] Implement OAuth2 tokens as currently the service is for internal usage only
- [ ] Implement Facebook pages posts crawler
- [ ] Implement a distributed mode (a crawler that collects data from other instance)

#### File Repository integration

Crawled content often contains attached images, and the article itself very often has a main image.
To avoid hotlinking entries there is a way to fetch all of those images to a own server
and replace all usages.

`File Repository` is a [Wolnościowiec's microservice](https://github.com/Wolnosciowiec/file-repository) that exposes an API interface for file storage,
the service itself handles de-duplication, caching and of course storing the files in right places.

To enable the integration simply only all environment variables needs to be filled up properly,
the rest is handled by the `ImageRepositoryBundle`.

#### Web-Proxy integration

When links to the articles does not have SSL it is possible to use a `Wolnościowiec Web-Proxy` to generate urls secured with SSL.
The `web-proxy` service is available here: https://github.com/Wolnosciowiec/web-proxy

Example case:
1. We have a source url to the article: `http://wolnywroclaw.pl/relacje/protest-lokatorow-z-ul-slicznej-i-zaulka-rogozinskiego-wideo/`
2. But... our site has SSL, to render the article in iframe we call the `web-proxy`
3. The webproxy returns: `https://some-proxy.services.someservice.org/?__wp_one_time_token=YT323DEe21FAFAFAZSP1312161F234ewfADSy65Efre4RE23EDW312gtrhtr6`
4. Embedding the URL from the `web-proxy` allows to render correctly the page through our `web-proxy` server

Enabling:

```
# web-proxy
NFP_WEB_PROXY_URL="https://some-proxy.services.someservice.org"
NFP_WEB_PROXY_PASSPHRASE="my-super-secret-passphrase-from-webproxy-service"
NFP_PROCESS_INTERNAL_LINKS=1 # process HTML and CSS content, so all static content will be rendered by the proxy
NFP_EXPIRATION_TIME_MINUTES=1
NFP_ENABLED_SSL=true
```

#### Health checking

There is an endpoint that shows the application health, it's placed under `{{DOMAIN}}/{{NFP_MONITORING_KEY}}/monitor/health/run`
NFP_MONITORING_KEY is a environment variable you can pass to nginx/apache/docker

#### Setting up using Docker

There exists an image `wolnosciowiec/news-feed-provider`, it uses environment variables to configure the application.
You may want to take a look at the list of variables with example values in the [Dockerfile](./Dockerfile.x86_64).

```
sudo docker run -e NFP_MAILER_HOST=somehost -e NFP_MAILER_USER=someuser -p 80:80 wolnosciowiec/news-feed-provider
```

There are a lot of configuration variables so you might consider using a `docker-compose` for simple usage,
or `docker-swarm` or `Kubernetes` if scaling in a cluser.
