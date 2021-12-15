<?php
/**
 * Date: 02/09/21
 * Time: 09:56
 */

namespace Dhi\BlogBundle\Core\Response;


use Dhi\BlogBundle\Core\Data\APIResponse;

abstract class AbstractForbiddenResponse extends AbstractResponse
{

    public function __construct(APIResponse $response)
    {
        parent::__construct($response->setStatusCode(self::HTTP_FORBIDDEN));
    }
}