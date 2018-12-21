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
use Smalot\PdfParser\Parser;

class PdfScraper
{
    /**
     * @param string $fileId
     * @return string
     * @throws \Google_Exception
     * @throws \Exception
     */
    public static function textFromDriveId(string $fileId)
    {
        $client = static::getClient();
        $service = new Google_Service_Drive($client);

        /**
         * @var Response $response
         */
        $response  = $service->files->get($fileId, array('alt' => 'media'));
        $content = $response->getBody()->getContents();


        $PdfParser = new Parser();

        $pdf = $PdfParser->parseContent($content);

        $text = $pdf->getText();

        return $text;
    }

    /**
     * @param string $url
     * @return string
     * @throws \Google_Exception
     */
    public static function textFromDriveUrl(string $url)
    {
        $fileId = static::fileIdFromUrl($url);

        return self::textFromDriveId($fileId);
    }

    /**
     * @param string $fileId
     * @param string $begin
     * @param string|null $end
     * @return bool
     */
    public static function checkKeywordsFromDriveId(string $fileId, string $begin, string $end = null)
    {
        try {
            $text = self::textFromDriveId($fileId);

            $beginPos = strpos($text, $begin);

            if(is_null($end)) {
                return $beginPos !== false;
            }

            $endPos = strrpos($text, $end);

            return $beginPos && $endPos && ($beginPos <= $endPos);

        } catch (\Google_Exception $e) {
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @param string $url
     * @param string $begin
     * @param string|null $end
     * @return bool
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

}