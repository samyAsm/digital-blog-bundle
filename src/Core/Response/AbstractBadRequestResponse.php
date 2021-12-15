<?php
/**
 * Date: 02/09/21
 * Time: 09:56
 */

namespace DhiBlogBundle\Core\Response;

use DhiBlogBundle\Core\Data\APIResponse;

abstract class AbstractBadRequestResponse extends AbstractResponse
{

    public function __construct(APIResponse $response)
    {
        $response->setStatusCode(self::HTTP_BAD_REQUEST);

        parent::__construct($response);
    }
}