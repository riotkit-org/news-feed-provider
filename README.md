Wolnościowiec News Feed Provider
================================

Microservice.

Provides news from various sources - blogs, portals, facebook pages and exposes via API.
Created for Wolnościowiec Initiative, see https://wolnosciowiec.net

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

To enable the integration simply only all paramters in `parameters.yml` needs to be filled up properly,
the rest is handled by the `ImageRepositoryBundle`.
