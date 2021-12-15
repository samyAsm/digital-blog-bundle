<?php
/**
 * Date: 27/11/21
 * Time: 08:22
 */

namespace DhiBlogBundle\Controller\Test;


use DhiBlogBundle\Services\BlogService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{

    /**
     * @var BlogService
     */
    private $blogService;

    public function __construct(BlogService $blogService)
    {
        $this->blogService = $blogService;
    }

    /**
     * @Route(path="/gogod", name="bundle_test")
     */
    public function index()
    {

        dd($this->blogService);

        return new Response("OK");
    }
}