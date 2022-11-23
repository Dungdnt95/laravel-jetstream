<?php

namespace App\Http\Controllers;

use App\Enums\OperationType;
use App\Models\OperationLog;
use Auth;
use Carbon\Carbon;
use Illuminate\Routing\Controller;
use Illuminate\Routing\Route;
use Log;

class BaseController extends Controller
{
    public function setFlash($message, $mode = 'success', $urlRedirect = '')
    {
        session()->forget('Message.flash');
        session()->push('Message.flash', [
            'message' => $message,
            'mode' => $mode,
            'urlRedirect' => $urlRedirect,
        ]);
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

    public static function newListLimitForUser($query)
    {
        return $query['per_page'];
        // $newSizeLimit = PAGE_SIZE_DEFAULT;
        // $arrPageSize = PAGE_SIZE_LIMIT;
        // if (isset($query['per_page'])) {
        //     $newSizeLimit = (($query['per_page'] === '') || !in_array($query['per_page'], $arrPageSize)) ? $newSizeLimit : $query['per_page'];
        // }
        // if (((isset($query['per_page']))) && (!empty($query->query('per_page')))) {
        //     $newSizeLimit = (!in_array($query->query('per_page'), $arrPageSize)) ? $newSizeLimit : $query->query('per_page');
        // }
        // return $newSizeLimit;
    }

    /**
     * [escapeLikeSentence description]
     * @param  [type]  $str    :search conditions
     * @param  bool $before : add % before
     * @param  bool $after  : add % after
     * @return [type]          [description]
     */
    public function escapeLikeSentence($column, $str, $before = true, $after = true)
    {
        $result = str_replace('\\', '[\]', $this->mbTrim($str)); // \ -> \\
        $result = str_replace('%', '\%', $result); // % -> \%
        $result = str_replace('_', '\_', $result); // _ -> \_

        return [[$column, 'LIKE', (($before) ? '%' : '').$result.(($after) ? '%' : '')]];
    }

    public function handleSearchQuery($str, $before = true, $after = true)
    {
        $result = str_replace('\\', '[\]', $this->mbTrim($str)); // \ -> \\
        $result = str_replace('%', '\%', $result); // % -> \%
        $result = str_replace('_', '\_', $result); // _ -> \_

        return (($before) ? '%' : '').$result.(($after) ? '%' : '');
    }

    public function mbTrim($string)
    {
        $whitespace = '[\s\0\x0b\p{Zs}\p{Zl}\p{Zp}]';
        $ret = preg_replace(sprintf('/(^%s+|%s+$)/u', $whitespace, $whitespace), '', $string);

        return $ret;
    }

    /**
     * マルチバイト対応のtrim
     *
     * 文字列先頭および最後の空白文字を取り除く
     *
     * @param  string  空白文字を取り除く文字列
     * @return  string
     */
    public function checkRfidCode($rfidCode)
    {
        return preg_match('/^[a-zA-Z0-9][a-zA-Z0-9]*$/i', $rfidCode);
    }

    public function checkReturnCsvContent($column)
    {
        $ret = 0;
        if ($column == '') {
            $ret = 1; // Blank OR NULL
        } elseif (! $this->checkRfidCode($column)) {
            $ret = 2; // Error formart
        }

        return $ret;
    }

    public function logInfo($request, $message = '')
    {
        Log::channel('access_log')->info([
            'url' => url()->full(),
            'method' => $request->getMethod(),
            'data' => $request->all(),
            'message' => $message,
        ]);
    }

    public function logError($request, $message = '')
    {
        Log::channel('access_log')->error([
            'url' => url()->full(),
            'method' => $request->getMethod(),
            'data' => $request->all(),
            'message' => $message,
        ]);
    }

    public function logWarning($request, $message = '')
    {
        Log::channel('access_log')->warning([
            'url' => url()->full(),
            'method' => $request->getMethod(),
            'data' => $request->all(),
            'message' => $message,
        ]);
    }

    public function convertShijis($text)
    {
        return mb_convert_encoding($text, 'SJIS', 'UTF-8');
    }

    public function saveOperationLog($request, $operationType = OperationType::INSERT)
    {
        $requestUri = $request->getRequestUri();
        $operationLog = new OperationLog();
        $operationLog->operation_log_datetime = Carbon::now();
        $operationLog->screen_name = $requestUri;
        $operationLog->user_id = Auth::guard('admin')->user() === null ? null : Auth::guard('admin')->user()->id;
        $operationLog->operation_name = $request->route()->getActionMethod();
        $operationLog->operation_type = $operationType;
        $operationValue = $request->all();
        unset($operationValue['_token']);
        unset($operationValue['_method']);
        unset($operationValue['password']);
        unset($operationValue['password_confirmation']);
        $operationValue['ip'] = $request->ip();
        $operationValue['user_agent'] = $request->server('HTTP_USER_AGENT');
        $operationLog->operation_value = $operationValue;
        $operationLog->save();
    }

    public function getData($line, $column)
    {
        return $this->removeBomUtf8($this->multibyteTrim(($line[$column] != 'None' && $line[$column] != '') ? $line[$column] : ''));
    }

    public function removeBomUtf8($s)
    {
        if (substr($s, 0, 3) == chr(hexdec('EF')).chr(hexdec('BB')).chr(hexdec('BF'))) {
            return substr($s, 3);
        }

        return $s;
    }

    public function multibyteTrim($str)
    {
        if (! function_exists('mb_trim') || ! extension_loaded('mbstring')) {
            return preg_replace("/(^\s+)|(\s+$)/u", '', $str);
        }

        return mb_trim($str);
    }

    public function mergeSession($data)
    {
        $dataSession = '';
        if (session()->get('Message.flash')) {
            $dataSession = session()->get('Message.flash')[0];
            session()->forget('Message.flash');
        }

        return array_merge($data, [
            'sessionAlert' => $dataSession,
        ]);
    }

    public function sortLinks($routeName, $data, $request)
    {
        $link = [];
        $dataParam = $request->all();
        unset($dataParam['sort']);
        unset($dataParam['direction']);
        foreach ($data as $key => $value) {
            $dataParam['sort'] = $value['key'];
            $dataParam['direction'] = $request->sort != $value['key'] ? 'asc' : ($request->direction == 'asc' ? 'desc' : 'asc');
            $link[] = [
                'link' => route($routeName, $dataParam),
                'name' => $value['name'],
                'iconDirection' => $request->sort != $value['key'] ? 'fa-sort' : ($request->direction == 'asc' ? 'fa-sort-asc' : 'fa-sort-desc'),
            ];
        }

        return $link;
    }

    public function paginator($data)
    {
        $url = [0 => $data->url(1)];
        foreach (range(1, $data->lastPage()) as $key => $i) {
            $url[$key] = $data->url($i);
        }

        return [
            'firstItem' => $data->firstItem(),
            'end' => $data->perPage() * ($data->currentPage() - 1) + $data->count(),
            'total' => $data->total(),
            'onFirstPage' => $data->onFirstPage(),
            'previousPageUrl' => $data->previousPageUrl(),
            'lastPage' => $data->lastPage(),
            'hasMorePages' => $data->hasMorePages(),
            'currentPage' => $data->currentPage(),
            'nextPageUrl' => $data->nextPageUrl(),
            'url' => $url,
        ];
    }
}
