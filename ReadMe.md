## Google PDF Scraper with Keywords



This is a php library to filter pdf documents in google driver for Daniel Fischl.

To import this into your project, use composer.

```shell
composer require tiefan/google-pdf-scraper
```



-----------------------------------

### Extract text from PDF document

```php
$text = PdfScraper::textFromDriveId(string $fileId);
```

```php
$text = PdfScraper::textFromDriveUrl(string $url);
```



### Check Document with "Begin" and "End" Keyword

```php
$isThatDocument = PdfScraper::checkKeywordsFromDriveId(string $fileId, string $begin, string $end = null);
```

```php
$isThatDocument = PdfScraper::checkKeywordsFromDriveUrl(string $url, string $begin, string $end = null);
```

```php
$scraper = new PdfScraper($doc, $isURL = true); // $isURL: true for url, false for id
$isThatDocument = $scraper->checkKeywords(string $begin, string $end = null);
```



### Using MySQL or MariaDB to process data at once

Following code is using db schema in `Sample\db_pdf_scraper.sql`

```
$pdfDB = new PdfDB($host, $username, $password, $database);
$processed_count = $pdfDB->checkPdfs();
```

