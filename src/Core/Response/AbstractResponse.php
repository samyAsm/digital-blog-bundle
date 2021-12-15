<?php
/**
 * Date: 02/09/21
 * Time: 09:56
 */

namespace DhiBlogBundle\Core\Response;


use DhiBlogBundle\Core\Data\APIResponse;
use DhiBlogBundle\Utils\KernelUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

abstract class AbstractResponse extends Response
{
    private const JSON = "json";
    private const XML = "xml";

    /**
     * @var APIResponse
     */
    protected $response;

    /**
     * @var Environment
     */
    protected $twig;

    /**
     * CoreResponse constructor.
     * @param APIResponse $response
     */
    public function __construct(APIResponse $response)
    {
        $c = KernelUtils::getKernel()->getContainer();
        $this->response = $response;
        $this->twig = $c->get('twig');
        /**
         * @var Request $r
        */
        $r = $c->get('app.request_service_provider')->getRequest();

        $headers = $response->headers->all();

        if (isset($headers['content-type'])){
            foreach ($headers['content-type'] as $index => $header) {
                if ($header == "application/json" && $r->headers->get('accept') !== self::JSON) {
                    unset($headers['content-type'][$index]);
                }
            }
        }

        parent::__construct($this->content($r->headers->get('accept')), $response->getStatusCode(), $headers);
    }

    protected function content($format = "json")
    {
        if ($format == self::JSON) return $this->rest();

        return $this->web();
    }

    protected function buildWebResponse($response = null)
    {
        return $this->getResponseContent(new Response($response, $this->response->getStatusCode()));
    }

    protected function rest()
    {
        return $this->getResponseContent($this->response->restResponse());
    }

    protected function getResponseContent(Response $r)
    {
        return $r->getContent();
    }

    protected function buildViewParameters()
    {
        return array_merge(
            $this->response->getViewData(),
            ["messages" => $this->buildViewMessages($this->response->getMessage())]
        );
    }

    protected function buildViewMessages($messages)
    {
        if (!is_array($messages)) return [$messages];

        return $messages;
    }

    /**
     * @return false|string
     */
    protected function web()
    {
        return $this->buildWebResponse(
            $this->getTemplate()
        );
    }

    protected function getTemplate(){}
}