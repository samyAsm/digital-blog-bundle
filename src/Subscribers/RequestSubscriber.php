<?php
/**
 * Created by PhpStorm.
 * User: samuel
 * Date: 02/12/21
 * Time: 16:42
 */

namespace Dhi\BlogBundle\Subscribers;


use Dhi\BlogBundle\Services\KernelService;
use Exception;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Twig\Environment;
use Symfony\Component\HttpFoundation\Request;

class RequestSubscriber
{
    private $container;


    /**
     * @var Request
     */
    protected $request;

    public function __construct(KernelService $kernelService)
    {
        $this->container = $kernelService->getContainer();
    }

    /**
     * @param RequestEvent $event
     * @throws Exception
     */
    public function onKernelRequest(RequestEvent $event)
    {
        try {

            /** @var Environment $twig */
            $twig = $this->container->get('twig');

            $this->define_twig_store_parameters($twig);

            $this->define_twig_assets_parameters($twig);

            $this->define_twig_theme_parameters($twig);

            $this->parseRequest($event->getRequest());

        } catch (Exception $exception) {

            // DO Nothing
        }
    }


    private function parseRequest(Request $request)
    {
        $contents = json_decode(trim($request->getContent()), true);

        if (!is_array($contents))
            parse_str($request->getContent(), $contents);

        try {
            if (is_array($contents)) {
                foreach ($contents as $key => $content) {
                    if (!$request->request->get($this->sanitizeContent($key))) {
                        if (is_string($key)) {
                            $request->request->set($this->sanitizeContent($key), $this->sanitizeContent($content));
                        }
                    }
                }
            }
        } catch (Exception $exception) {
            //
        }
    }

    /**
     * @param string|array $str
     * @return mixed
     */
    private function sanitizeContent($str)
    {
        return $str;
    }

    /**
     * @param Environment $twig
     */
    protected function define_twig_store_parameters($twig): void
    {
        try {
            $store_configs = $this->container->getParameter('blog_configurations')[0]['store'];
            $twig->addGlobal('twig_p_author_image_path', $store_configs['author_image_store']);
            $twig->addGlobal('twig_p_article_image_path', $store_configs['article_image_store']);
            $twig->addGlobal('twig_p_category_image_path', $store_configs['category_image_store']);
        } catch (\Throwable $throwable) {
        }
    }

    /**
     * @param Environment $twig
     */
    protected function define_twig_assets_parameters($twig): void
    {
        try {
            $assets_configs = $this->container->getParameter('blog_configurations')[0]['assets'];
            $twig->addGlobal('twig_p_digital_blog_logo', $assets_configs['logo']);
            $twig->addGlobal('twig_p_digital_blog_hero_bg', $assets_configs['hero_bg']);
        } catch (\Throwable $throwable) {

        }
    }

    /**
     * @param Environment $twig
     */
    protected function define_twig_theme_parameters($twig): void
    {

        try {
            $theme_configs = $this->container->getParameter('blog_configurations')[0]['theme'];
            $twig->addGlobal('twig_p_digital_blog_theme_primary', $theme_configs['color_primary']);
            $twig->addGlobal('twig_p_digital_blog_theme_secondary', $theme_configs['color_secondary']);
        } catch (\Throwable $throwable) {

        }

    }
}