<?php

namespace App\Helpers;

use Kapi\Constants;

/**
 * Response class
 */
final class Response
{
    /**
     * Setting the default headers
     *
     * @param integer $httpStatus
     * @return void
     */
    private static function setDefaultHeaders(int $httpStatus)
    {
        http_response_code($httpStatus);

        $headers = [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS',
            'X-Application-Name' => 'Paythru Test',
            'X-Application-Version' => '1.0.0',
            'Content-Type' => 'application/json; charset=utf-8'
        ];

        // if the headers are not sent, please set the headers
        if (!headers_sent()) {
            foreach ($headers as $header => $value) {
                header("$header: $value");
            }
        }
        return self::class;
    }

    /**
     * Sending json response
     *
     * @param array $data
     * @param int $httpStatus
     * @param array $errors
     * @return string
     */
    public static function sendResponse(mixed $data, int $httpStatus = Constants::HTTP_OK, array $errors = []): string
    {
        self::setDefaultHeaders($httpStatus);

        $response = [
            'status' => ($httpStatus == 200 || $httpStatus == 201 ? 1 : 0),
            'code' => $httpStatus,
            'errors' => $errors,
        ];

        if ($data) {
            $response['data'] = $data;
        }

        return json_encode($response, JSON_PRETTY_PRINT);
    }

    /**
     * Sending error Response
     *
     * @param int $httpStatus
     * @param array $errors
     * @return string
     */
    public static function sendErrorResponse(int $httpStatus = Constants::HTTP_BAD_REQUEST, array $errors = []): string
    {
        return self::sendResponse([], $httpStatus, $errors);
    }

    /**
     * Downloading the file
     *
     * @param string $filePath
     * @return void
     */
    public static function download(string $filePath, string $fileName): void
    {
        $fp = fopen($filePath, 'rb');
        $fileSize = (int) filesize($filePath);
        if (!$fp || $fileSize == 0) {
            die("Error opening file $fileName");
        }

        ob_clean();
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header("Content-Disposition: attachment; filename=$fileName");
        header("Content-Length: " . $fileSize);
        header("Content-Transfer-Encoding: binary");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
        header("Pragma: public");

        fpassthru($fp);
        fclose($fp);
    }
}
