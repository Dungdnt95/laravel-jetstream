<?php

namespace App\Components;

use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\CurlMultiHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Psr\Http\Message\ResponseInterface;

class CommonComponent
{
    public static function uploadFile($folder, $file, $fileName)
    {
        try {
            $azure = Storage::disk('azure');

            return $azure->put($folder, $file, $fileName);
        } catch (Exception $exception) {
            return false;
        }

        return true;
    }

    public static function uploadFileName($extension = '')
    {
        return sha1(time().rand(0, 9999999)).'.'.$extension;
    }

    public static function deleteFile($folder, $nameFile)
    {
        try {
            return Storage::disk('public')->delete($folder.'/'.$nameFile);
        } catch (Exception $exception) {
            return false;
        }

        return true;
    }

    public static function getFullUrl($blob)
    {
        $_signature = self::getSASForBlob(env('AZURE_STORAGE_NAME'), env('AZURE_STORAGE_CONTAINER'), $blob, 'b', 'r', env('AZURE_STORAGE_KEY'));
        $_blobUrl = self::getBlobUrl(env('AZURE_STORAGE_NAME'), env('AZURE_STORAGE_CONTAINER'), $blob, 'b', 'r', $_signature);

        return $_blobUrl;
    }

    public static function getSASForBlob($accountName, $container, $blob, $resourceType, $permissions, $key)
    {
        $_arraysign = [];
        $_arraysign[] = $permissions;
        $_arraysign[] = '';
        $_arraysign[] = gmdate("Y-m-d\TH:i:s\Z", strtotime('+ 24 hour'));
        $_arraysign[] = '/'.$accountName.'/'.$container.'/'.$blob;
        $_arraysign[] = '';
        $_arraysign[] = '2014-02-14'; //the API version is now required
        $_arraysign[] = '';
        $_arraysign[] = '';
        $_arraysign[] = '';
        $_arraysign[] = '';
        $_arraysign[] = '';

        $_str2sign = implode("\n", $_arraysign);

        return base64_encode(
            hash_hmac('sha256', urldecode(utf8_encode($_str2sign)), base64_decode($key), true)
        );
    }

    public static function getBlobUrl($accountName, $container, $blob, $resourceType, $permissions, $_signature)
    {
        $_parts = [];
        $expiry = gmdate("Y-m-d\TH:i:s\Z", strtotime('+ 24 hour'));
        $_parts[] = (! empty($expiry)) ? 'se='.urlencode($expiry) : '';
        $_parts[] = 'sr='.$resourceType;
        $_parts[] = (! empty($permissions)) ? 'sp='.$permissions : '';
        $_parts[] = 'sig='.urlencode($_signature);
        $_parts[] = 'sv=2014-02-14';

        /* Create the signed blob URL */
        $accountName = 'https://'.$accountName.'.blob.core.windows.net/';
        $finalUrlPath = (env('ASSET_CDN_URL') !== null) ? env('ASSET_CDN_URL') : $accountName;

        $_url = $finalUrlPath
            .$container.'/'
            .$blob.'?'
            .implode('&', $_parts);

        return $_url;
    }

    public static function urlencode($string)
    {
        $encoded = rawurlencode($string);
        $encoded = str_replace('%7E', '~', $encoded);

        return $encoded;
    }

    public static function newListLimit($query)
    {
        $newSizeLimit = PAGE_SIZE_DEFAULT;
        $arrPageSize = PAGE_SIZE_LIMIT;
        if (isset($query['limit_page'])) {
            $newSizeLimit = (($query['limit_page'] === '') || ! in_array($query['limit_page'], $arrPageSize)) ? $newSizeLimit : $query['limit_page'];
        }
        if (((isset($query['limit_page']))) && (! empty($query->query('limit_page')))) {
            $newSizeLimit = (! in_array($query->query('limit_page'), $arrPageSize)) ? $newSizeLimit : $query->query('limit_page');
        }

        return $newSizeLimit;
    }

    public static function checkPaginatorList($query)
    {
        if ($query->currentPage() > $query->lastPage()) {
            return true;
        }

        return false;
    }
}
