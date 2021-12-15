<?php
/**
 * Date: 02/09/21
 * Time: 09:56
 */

namespace Dhi\BlogBundle\Core\Response;

use Dhi\BlogBundle\Core\Data\APIResponse;

abstract class AbstractBadRequestResponse extends AbstractResponse
{

    public function __construct(APIResponse $response)
    {
        $response->setStatusCode(self::HTTP_BAD_REQUEST);

        parent::__construct($response);
    }
}