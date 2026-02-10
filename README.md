[![](https://img.shields.io/packagist/v/fritzmg/contao-news-sorting.svg)](https://packagist.org/packages/fritzmg/contao-news-sorting)
[![](https://img.shields.io/packagist/dt/fritzmg/contao-news-sorting.svg)](https://packagist.org/packages/fritzmg/contao-news-sorting)

Contao News Sorting
=====================

This Contao extension adds additional sort settings for the news list:

* Random (Date descending)
* Custom (Date ascending)
* Custom (Date descending)

When using the _Custom_ sort setting you can switch the sorting in the back end to _Sorting value_ (do not forget to apply) and then sort the news articles via drag & drop in the back end.

This setting is applied via the [`NewsFilterEvent`](https://github.com/inspiredminds/contao-news-filter-event).

> [!NOTE]
> Install [`inspiredminds/contao-categories-news-filter`](https://github.com/inspiredminds/contao-categories-news-filter) fot compatibility with [`codefog/contao-news_categories`](https://github.com/codefog/contao-news_categories).
