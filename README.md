[![](https://img.shields.io/packagist/v/fritzmg/contao-news-sorting.svg)](https://packagist.org/packages/fritzmg/contao-news-sorting)
[![](https://img.shields.io/packagist/dt/fritzmg/contao-news-sorting.svg)](https://packagist.org/packages/fritzmg/contao-news-sorting)

Contao News Sorting
=====================

Contao extension to allow additional sort settings in the news list. This setting is then applied via the `newsListFetchItems` hook.

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

When using the _Custom_ sort setting you can switch the sorting in the back end to _Sorting value_ and then sort the news articles via drag & drop in the back end.
