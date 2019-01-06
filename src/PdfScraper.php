<?php
/**
 * Created by PhpStorm.
 * User: kcb01
 * Date: 12/21/2018
 * Time: 6:19 PM
 */

namespace Tiefan\PdfScraper;

use Google_Client;
use Google_Service_Drive;
use GuzzleHttp\Psr7\Response;
use Smalot\PdfParser\Document;
use Smalot\PdfParser\Parser;

class PdfScraper
{
    private static $client = null;
    private static $service = null;

    /**
     * @param string $fileId
     * @return Document
     * @throws \Google_Exception
     * @throws \Exception
     */
    public static function documentFromDriveId(string $fileId)
    {
        self::$client = self::$client ?? static::getClient();
        self::$service = self::$service ?? new Google_Service_Drive(self::$client);

        /**
         * @var Response $response
         */
        $response  = self::$service->files->get($fileId, array('alt' => 'media'));
        $content = $response->getBody()->getContents();


        $PdfParser = new Parser();

        $document = $PdfParser->parseContent($content);

        return $document;
    }

    /**
     * @param string $url
     * @return Document
     * @throws \Google_Exception
     */
    public static function documentFromDriveUrl(string $url)
    {
        $fileId = static::fileIdFromUrl($url);

        return self::documentFromDriveId($fileId);
    }

    private static function checkKeywordsFromText(string $text, string $begin, string $end = null)
    {
        $text = preg_replace('/[^a-zA-Z0-9]/', '', $text);
        $begin = preg_replace('/[^a-zA-Z0-9]/', '', $begin);

        $beginPos = strpos($text, $begin);

        if(is_null($end)) {
            return $beginPos !== false;
        }

        $end = preg_replace('/[^a-zA-Z0-9]/', '', $end);

        $endPos = strrpos($text, $end);

        return $beginPos && $endPos && ($beginPos <= $endPos);
    }

    /**
     * @param string $fileId
     * @param string $begin
     * @param string|null $end
     * @return int[]
     */
    public static function checkKeywordsFromDriveId(string $fileId, string $begin, string $end = null)
    {
        try {
            $document = self::documentFromDriveId($fileId);

            $pages = $document->getPages();

            $pageNumbers = [];
            $pageNo = 1;

            foreach ($pages as $page) {
                $text = $page->getText();
                if(self::checkKeywordsFromText($text, $begin, $end)) {
                    $pageNumbers[] = $pageNo;
                }
				$pageNo++;
            }

            return $pageNumbers;

        } catch (\Google_Exception $e) {
            return [];
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * @param string $url
     * @param string $begin
     * @param string|null $end
     * @return int[]
     */
    public static function checkKeywordsFromDriveUrl(string $url, string $begin, string $end = null)
    {
        $fileId = static::fileIdFromUrl($url);
        return self::checkKeywordsFromDriveId($fileId, $begin, $end);
    }

    /**
     * Returns an authorized API client.
     * @return Google_Client the authorized client object
     * @throws \Google_Exception
     */
    private static function getClient()
    {
        $client = new Google_Client();
        $client->setApplicationName(Constants::APP_NAME);
        $client->setScopes([Google_Service_Drive::DRIVE]);

        $authConfigPath = 'authconfig.json';
        $config = Constants::AUTH_CONFIG;
        file_put_contents($authConfigPath, json_encode($config));
        $client->setAuthConfig($authConfigPath);
        unlink($authConfigPath);

        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');

        $client->fetchAccessTokenWithAssertion();

        return $client;
    }


    private static function fileIdFromUrl($url) {

        $pos = strpos($url, '/d/');
        $id = substr($url, $pos + 3);
        $pos = strpos($id, '/');
        if($pos) {
            $id = substr($id, 0, $pos);
        }

        return $id;
    }

    /**
     * @var Document $text
     */
    private $document;

    /**
     * PdfScraper constructor.
     * @param string $doc
     * @param bool $isURL
     * @throws \Google_Exception
     */
    public function __construct(string $doc, $isURL = true)
    {
        if($isURL) {
            $this->document = self::documentFromDriveUrl($doc);
        } else {
            $this->document = self::documentFromDriveId($doc);
        }
    }

    /**
     * @param string $begin
     * @param string|null $end
     * @return int[]
     */
    public function checkKeywords(string $begin, string $end = null) {
        try {
            $pages = $this->document->getPages();
        } catch (\Exception $e) {
            return [];
        }

        $pageNumbers = [];
        $pageNo = 1;

        foreach ($pages as $page) {
            $text = $page->getText();
            if(self::checkKeywordsFromText($text, $begin, $end)) {
                $pageNumbers[] = $pageNo;
            }
        }

        return $pageNumbers;
    }
}