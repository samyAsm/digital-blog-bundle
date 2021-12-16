<?php
/**
 * Date: 15/03/21
 * Time: 17:38
 */

namespace Dhi\BlogBundle\Core\Data;


use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\JsonResponse;

class APIResponse extends JsonResponse implements SerializableInterface
{

    protected $message;

    protected $data;

    protected $serialized;

    protected $status;

    /**
     * CustomResponse constructor.
     * @param bool $data
     * @param int $status
     * @param array $headers
     * @param bool $json
     */
    public function __construct($data = true, $status = 200, array $headers = [], $json = false)
    {
        parent::__construct($data, $status, $headers, $json);

        if ($data) $this->data = $data;

        $this->status = $status;

        $this->serialized = null;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message): void
    {
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    public function getViewData()
    {
        return is_array($this->data)?$this->data:[];
    }

    public function normalizeDataValues()
    {
        if (is_array($this->data)) {
            $this->data = $this->normalizeValues();
        }
    }

    protected function normalizeValues(?array $data = [])
    {
        if (!$data) $data = $this->data;

        foreach ($data as $index => $datum) {
            if (is_null($datum) || $datum == "0")
                $data[$index] = 0;
            elseif (is_numeric($datum)) {
                $data[$index] = floatval($datum);
            } elseif (is_array($datum)) {
                $data[$index] = $this->normalizeValues($datum);
            }
        }

        return $data;
    }

    public function resetData()
    {
        $this->data = true;
    }

    /**
     * @param $key
     * @param $data
     */
    public function addData($data, $key = null)
    {
        if ($key) {
            $this->data = [];
            if (!isset($this->data[$key]))
                $this->data[$key] = [];

            if ($data instanceof SerializableInterface)
                $this->data[$key][] = $data;
            else
                $this->data[$key][] = $data;
        } else {

            if ($data instanceof SerializableInterface)
                $this->data = $data;
            else
                $this->data = $data;
        }
    }

    protected function dryUnNecessary($data)
    {
        return $data;
    }

    /**
     * @param null $data
     * @param null $key
     */
    public function data($data, $key = null): void
    {
        if ($data instanceof Collection)
            $data = $data->toArray();

        if ($key && !is_array($this->data))
            $this->data = [];

        if ($key)
            $this->data[$key] = $data;
        else
            $this->data = $data;
    }

    public function success()
    {
        $this->status = self::HTTP_OK;

        return $this->restResponse();
    }

    public function restResponse()
    {
        $this->serialize();

        return new JsonResponse(json_encode([
            'data' => $this->data,
            'message' => $this->message,
            'status' => $this->getStatusCode(),
            'header' => $this->headers,
        ]), $this->status, $this->headers->all(), true);
    }

    public function serialize()
    {
        $this->serialized = [];

        if (is_array($this->data)) {
            $this->serialized = $this->serializeArray($this->data);
        } else if ($this->data instanceof SerializableInterface) {
            $this->serialized = $this->data->serialize();
        }

        if (!is_null($this->serialized))
            $this->data = $this->serialized;

        return $this->data;
    }

    private function serializeArray(?array $data_array)
    {
        $this->serialized = [];

        foreach ($data_array as $key => $item) {
            if ($item instanceof SerializableInterface) {
                $data_array[$key] = $item->serialize();
            } elseif (is_array($item)) {
                $data_array[$key] = $this->serializeArray($item);
            } else {
                $data_array[$key] = $item;
            }
        }

        return $data_array;
    }

    public function created()
    {
        $this->status = self::HTTP_CREATED;

        return $this->restResponse();
    }

    public function accepted()
    {
        $this->status = self::HTTP_ACCEPTED;

        return $this->restResponse();
    }

    /**
     * The server encountered an unexpected condition that prevented it from fulfilling the request.
     */
    public function badRequest()
    {
        $this->status = self::HTTP_BAD_REQUEST;

        return $this->badResponse();
    }

    /**
     * @param bool|null $reset_data
     * @return JsonResponse
     */
    private function badResponse(?bool $reset_data = true): JsonResponse
    {
        if ($reset_data) $this->badData();

        return $this->restResponse();
    }

    public function badData()
    {
        $this->data = false;
    }

    public function notAuthenticated()
    {
        $this->status = self::HTTP_UNAUTHORIZED;

        return $this->badResponse();
    }

    /**
     *The server understood the request but refuses to authorize it.
     *server that wishes to make public why the request has been forbidden
     *can describe that reason in the response payload (if any).
     */
    public function forbidden()
    {
        $this->status = self::HTTP_FORBIDDEN;

        return $this->badResponse();
    }

    public function notFound()
    {
        $this->status = self::HTTP_NOT_FOUND;

        return $this->badResponse();
    }

    /**
     * The request could not be completed due to a conflict
     * with the current state of the target resource.
     * This code is used in situations where the user might be able
     * to resolve the conflict and resubmit the request.
     * @param bool|null $reset_data
     * @return JsonResponse
     */
    public function conflict(?bool $reset_data = true)
    {
        $this->status = self::HTTP_CONFLICT;

        return $this->badResponse($reset_data);
    }

    /**
     * @return null
     */
    public function getSerialized()
    {
        return $this->serialized;
    }

}