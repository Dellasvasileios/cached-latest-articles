# CachedLatestArticles

CachedLatestArticles – WordPress Plugin

CachedLatestArticles is a lightweight and flexible WordPress plugin that fetches and displays the latest articles either in a card layout or a simple list, using a custom JSON-based caching system for enhanced performance.

Designed to minimize load times and reduce unnecessary database calls, this plugin caches article data locally in JSON format. You can easily embed the output anywhere on your site using a simple shortcode.

🔧 Features:

🔁 Custom JSON Cache: Avoids repeated database queries by caching latest articles in a JSON file.

📰 Display Options: Choose between card or list view for article layout.

⚙️ Shortcode Integration: Easily insert the latest articles.

🕒 Configurable Cache Duration
Set how frequently the cached articles should refresh (e.g., every 15 minutes, hourly, daily) with  WP-Cron events. This helps balance performance with content freshness.

🔄 Automatic Cache Invalidation configuration

The cache is automatically cleared when:

A new article is created (published or scheduled)

An existing article is updated from the latest articles 

🧹 Manual Cache Clear
