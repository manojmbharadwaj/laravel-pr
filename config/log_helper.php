<?php

use Illuminate\Support\Facades\Log;


/**
 * Log Application Exceptions.
 *
 * @param Exception $ex
 * @param string $logMsg
 * @return void
 */
function le($logMsg, $ex, $data = [])
{
    $logDetails = __getLogDetails($ex);
    Log::channel('exceptions')->error($logMsg . json_encode($logDetails) . ' :: ' . json_encode($data));
}

/**
 * Logging only secutiry related data.
 *
 * @param string $logMsg
 * @param exception|empty array $ex
 * @param array $data
 * @return void
 */
function ls($logMsg, $ex, $data = [])
{
    $logDetails = __getLogDetails($ex);

    $userId = ' :: UserId - ' . auth()->user()->id;

    Log::channel('security')->error($logMsg . json_encode($logDetails) . ' :: ' . json_encode($data) . $userId);
}

/**
 * Logging all payment transactions related data.
 *
 * @param string $logMsg
 * @param exception|empty array $ex
 * @param array $data
 * @return void
 */
function lp($logMsg, $ex = [], $data = [])
{
    $logDetails = __getLogDetails($ex);

    $userId = ' :: UserId - ' . auth()->user()->id;

    Log::channel('payment')
        ->info($logMsg . json_encode($logDetails) . ' :: ' . json_encode($data) . $userId);
}

function __getLogDetails($ex)
{
    $logDetails = [];

    if (empty($ex)) {
        return $logDetails;
    }

    if (!empty($ex->getMessage())) $logDetails['message'] = $ex->getMessage();
    if (!empty($ex->getCode())) $logDetails['code'] = $ex->getCode();
    if (!empty($ex->getFile())) $logDetails['file'] = $ex->getFile();
    if (!empty($ex->getLine())) $logDetails['line'] = $ex->getLine();

    return $logDetails;
}
