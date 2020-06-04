<?php

namespace App\Libraries;

use Illuminate\Contracts\Pagination\Paginator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Illuminate\Database\Eloquent\Model;

class ResponseLibrary
{
    private $errorMessages = null;

    public function successResponse()
    {
        $return = [];
        $return['meta']['code'] = 200;
        $return['meta']['message'] = trans('message.api.success');
        return $return;
    }

    public function createResponse($code, $data, $message = null)
    {
        $return = [];
        $return['meta']['code'] = $code;
        $return['meta']['message'] = $message === null ? trans('message.api.success') : $message;
        $return['data'] = $data;
        return $return;
    }

    public function errorResponse(\Exception $e)
    {
        $return = [];
        $return['meta']['code'] = JsonResponse::HTTP_INTERNAL_SERVER_ERROR;
        $return['meta']['message'] = trans('message.api.error');
        $return['meta']['error'] = $e->getMessage();
        return $return;
    }

    public function failResponse($code, $errors)
    {
        $return = [];
        $return['meta']['code'] = $code;
        $return['meta']['message'] = trans('message.api.error');
        $return['meta']['errors'] = $errors;
        $return['data'] = [];
        return $return;
    }


    public function validationFailResponse($errors = [])
    {
        $return = [];
        $return['meta']['code'] = 422;
        $return['meta']['message'] = trans('message.api.error');
        $return['meta']['errors'] = $errors;
        $return['data'] = [];
        return $return;
    }

    public function validationFailJsonResponse($errors = [])
    {
        $return = [];
        $return['meta']['code'] = 422;
        $return['meta']['message'] = trans('message.api.error');
        $return['meta']['errors'] = $errors;
        $return['data'] = [];
        return response()->json($return, 422);
    }

    public function setErrorMessage($message)
    {
        $this->errorMessages = $message;
        $return = [];
        $return['meta']['code'] = 400;
        $return['meta']['message'] = $this->errorMessages;
        $return['data'] = [];
        return $return;
    }

    /**
     * @param BaseMapper $mapper
     * @param Paginator $paged
     * @param int $countAll
     * @param string $method
     * @param int $code
     * @param array $additional
     * @return \Illuminate\Http\Response
     */
    public function list(
        Paginator $paged,
        int $countAll,
        string $method,
        int $code = JsonResponse::HTTP_OK,
        array $additional = []
    )
    {
        $version = "1.0.1";
        $message = "Request is successfully executed.";
        $errors = [];
        $item = [];
        $items = $this->createList($paged);
        $meta = $this->meta($code, $version, $method, $message);
        $pageInfo = $this->pageInfo(
            $paged->url($paged->currentPage()),
            $paged->url(1),
            $paged->url($paged->lastPage()),
            $paged->nextPageUrl(),
            $paged->previousPageUrl(),
            $paged->total(),
            $paged->perPage(),
            $paged->currentPage(),
            $countAll,
            $paged->firstItem(),
            $paged->lastItem()
        );

        $data = [
            "message" => $message,
            "item" => (object)$item,
            "items" => $items,
            "additional" => $additional
        ];

        $response = [
            "meta" => $meta,
            "page_info" => $pageInfo,
            "errors" => $errors,
            "data" => $data
        ];

        return response()->json($response, $code);
    }

    /**
     * @param BaseMapper $mapper
     * @param Model $single
     * @param string $method
     * @param int $code
     * @param array $additional
     * @return \Illuminate\Http\Response
     */
    public function single(
        Model $single,
        string $method,
        int $code = JsonResponse::HTTP_OK,
        array $additional = []
    )
    {
        $version = "1.0.1";
        $message = "Request is successfully executed.";
        $errors = [];
        $items = [];
        $item = $this->single($single);
        $meta = $this->meta($code, $version, $method, $message);
        $pageInfo = $this->pageInfo(url()->full());

        $data = [
            "message" => $message,
            "item" => (object)$item,
            "items" => $items,
            "additional" => $additional
        ];

        $response = [
            "meta" => $meta,
            "page_info" => $pageInfo,
            "errors" => $errors,
            "data" => $data
        ];

        return response()->json($response, $code);
    }

    /**
     * Loop through single() function to generate multiple mapped data.
     *
     * @param $items
     * @return array
     */
    private function createList($items)
    {
        $result = [];
        foreach ($items as $item) {
            $result[] = $this->single($item);
        }
        return $result;
    }

    /**
     * @param int $code
     * @param string $version
     * @param string $method
     * @param string $message
     * @return array
     */
    protected function meta(int $code, string $method, string $message)
    {
        return [
            "code" => $code,
            "method" => $method,
            "message" => $message
        ];
    }

    public function getErrorMessage()
    {
        return $this->errorMessages;
    }
}
