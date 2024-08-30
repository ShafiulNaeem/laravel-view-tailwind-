<?php


use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;
use Pusher\Pusher;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Shafiulnaeem\MultiAuthRolePermission\Models\UserRole;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Shafiulnaeem\MultiAuthRolePermission\Models\AuthGuard;
use Illuminate\Support\Arr;
use Intervention\Image\ImageManager;



if ( ! function_exists('sendResponse') ){
    /**
     * @param $message
     * @param $result
     * @param int $code
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    function sendResponse($message, $result, int $code = 200) {
        $response = [
            'code'    => $code,
            'success' => true,
            'message' => $message,
            'data'    => $result,
            'errors'  => []
        ];

        return response($response, $code);
    }
}


if( ! function_exists('sendError') ){
    /**
     * @param $error
     * @param array $errorMessages
     * @param int $code
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    function sendError($message, array $error = [], int $code = 404) {
        $response = [
            'code'    => $code,
            'success' => false,
            'message' => $message,
            'data'    => [],
            'errors'  => $error,
        ];

        return response($response, $code);
    }
}

if ( ! function_exists('sendInternalServerError') ){

    /**
     * @param Exception $exception
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    function sendInternalServerError(\Exception $exception)
    {
        $response['errors'] = ['error' => 'Internal Server Error'];
        if ( env('APP_ENV') != 'local' ){
            $response['errors'] = [
                'message' => $exception->getMessage(),
                'line'    => $exception->getLine(),
                'file'    => $exception->getFile(),
                'trace'   => $exception->getTrace(),
            ];
        }

        $response = [
            'code'    => Response::HTTP_INTERNAL_SERVER_ERROR,
            'success' => false,
            'message' => 'Something went wrong.',
            'data'    => []
        ];

        return response($response, Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
