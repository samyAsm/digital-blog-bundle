<?php
/**
 * Date: 02/09/21
 * Time: 09:56
 */

namespace DhiBlogBundle\Core\Response;


use DhiBlogBundle\Core\Data\APIResponse;

abstract class AbstractUnauthorizedResponse extends AbstractResponse
{

    public function __construct(APIResponse $response)
    {
        parent::__construct($response->setStatusCode(self::HTTP_UNAUTHORIZED));
    }
}