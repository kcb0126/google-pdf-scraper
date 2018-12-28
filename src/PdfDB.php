<?php
/**
 * Created by PhpStorm.
 * User: kcb01
 * Date: 12/22/2018
 * Time: 1:45 AM
 */

namespace Tiefan\PdfScraper;


use mysql_xdevapi\Exception;
use mysqli;

class PdfDB
{
    const SQL_FORMAT_GET_ALL = "SELECT `id`, `format`, `begin`, `end` FROM `tb_formats`";
    const SQL_EX_CODE_GET_ALL = "SELECT `id`, `ex_code`, `begin`, `end` FROM `tb_ex_codes`";
    const SQL_DOCUMENT_GET_ALL = "SELECT `id`, `url`, `format`, `ex_code` FROM `tb_documents`";

    const SQL_DOCUMENT_UPDATE = "UPDATE `tb_documents` SET `format`='{format}', `ex_code`='{ex_code}' WHERE `id`={id}";

    /**
     * @var mysqli $db_connection
     */
    private $db_connection;

    private $formats = [];

    private $ex_codes = [];

    private function readFormatsAndExCodes()
    {
        $result = $this->db_connection->query(self::SQL_FORMAT_GET_ALL);
        $this->formats = [];
        if($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $this->formats[] = [
                    'id' => $row['id'],
                    'format' => $row['format'],
                    'begin' => $row['begin'],
                    'end' => $row['end'],
                ];
            }
        }

        $result = $this->db_connection->query(self::SQL_EX_CODE_GET_ALL);
        $this->ex_codes = [];
        if($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $this->ex_codes[] = [
                    'id' => $row['id'],
                    'ex_code' => $row['ex_code'],
                    'begin' => $row['begin'],
                    'end' => $row['end'],
                ];
            }
        }
    }

    /**
     * PdfDB constructor.
     * @param string $host
     * @param string $username
     * @param string $password
     * @param string $database
     * @throws \Exception
     */
    public function __construct(string $host, string $username, string $password, string $database)
    {
        $this->db_connection = new mysqli($host, $username, $password, $database);

        if($this->db_connection->connect_errno) {
            throw new \Exception("MySQL Connection Error");
        }
    }

    /**
     * @return int
     */
    public function checkPdfs()
    {
        $this->readFormatsAndExCodes();

        $count = 0;

        $result = $this->db_connection->query(self::SQL_DOCUMENT_GET_ALL);
        if($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $id = $row['id'];
                $url = $row['url'];

                $fmt = '';
                $excd = '';

                try {
                    $scraper = new PdfScraper($url);
                } catch (\Google_Exception $e) {
                    continue;
                }

                foreach ($this->formats as $format) {
                    $pageNumbers = $scraper->checkKeywords($format['begin'], $format['end']);
                    foreach($pageNumbers as $pageNumber) {
                        $fmt .= "page$pageNumber: {$format['format']}; ";
                    }
                }

                if($fmt !== '') {
                    foreach ($this->ex_codes as $ex_code) {
                        $pageNumbers = $scraper->checkKeywords($ex_code['begin'], $ex_code['end']);
                        foreach($pageNumbers as $pageNumber) {
                            $excd .= "page$pageNumber: {$ex_code['ex_code']}; ";
                        }
                    }
                }

                if($fmt === '') $fmt = 'NULL';
                if($excd === '') $excd = 'NULL';

                if($fmt !== 'NULL' || $excd !== 'NULL') {
                    $query = str_replace(
                        ['{format}', '{ex_code}', '{id}'],
                        [$fmt, $excd, $id],
                        self::SQL_DOCUMENT_UPDATE
                    );

                    if($this->db_connection->query($query) === true) {
                        $count++;
                    }
                }
            }
        }
        return $count;
    }
}