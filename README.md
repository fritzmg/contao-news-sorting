[![](https://img.shields.io/packagist/v/fritzmg/contao-news-sorting.svg)](https://packagist.org/packages/fritzmg/contao-news-sorting)
[![](https://img.shields.io/packagist/dt/fritzmg/contao-news-sorting.svg)](https://packagist.org/packages/fritzmg/contao-news-sorting)

Contao News Sorting
=====================

Contao extension to allow additional sort settings in the news list. This setting is then applied via the `NewsFilterEvent`.

The following settings are available:

* Date (descending)
* Date (ascending)
* Headline (ascending)
* Headline (descending)
* Random
* Random (Date descending)
* Featured
* Custom (Date ascending)
* Custom (Date descending)

Some of these settings are already available in some Contao 4 versions. The extension is fully compatible with that and only uses the hook when necessary.

When using the _Custom_ sort setting you can switch the sorting in the back end to _Sorting value_ (do not forget to apply) and then sort the news articles via drag & drop in the back end.

_Note:_ install [`inspiredminds/contao-categories-news-filter`](https://github.com/inspiredminds/contao-categories-news-filter) to restore compatibility with [`codefog/contao-news_categories`](https://github.com/codefog/contao-news_categories).
