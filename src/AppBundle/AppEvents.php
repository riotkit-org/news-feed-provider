<?php declare(strict_types = 1);

namespace AppBundle;

final class AppEvents
{
    const FEED_PRE_PERSIST = 'Feed.pre-persist';
    const FEED_SOURCE_PRE_PERSIST = 'FeedSource.pre-persist';
    const FEED_LIST_POST_PROCESS  = 'Controller.FeedList.PostProcess';
}
