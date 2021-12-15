<?php
/**
 * Created by PhpStorm.
 * User: mathermann
 * Date: 13/11/2020
 * Time: 19:30
 */

namespace DhiBlogBundle\Core\Controller;

use DhiBlogBundle\Core\Data\APIResponse;
use DhiBlogBundle\Core\Entity\CoreEntity;
use DhiBlogBundle\Services\KernelService;
use DhiBlogBundle\Services\ManagerService;
use DhiBlogBundle\Services\RepositoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Contracts\Translation\TranslatorInterface;
use Throwable;

abstract class AbstractRESTController extends AbstractController
{
    protected const EXCEPTION_MESSAGE = "Nos services rencontrent un problème.";

    /**
     * @var APIResponse|null $response
     * */
    protected $response;

    /**
     * @var Request
     */
    protected $request;
    /**
     * @var Kernel
     */
    protected $kernel;

    protected $serviceProvider;

    /**
     * @var ManagerService
     */
    protected $manager;
    protected $error_stack;
    protected $validation_error;
    /**
     * @var TranslatorInterface
     */
    protected $translator;

    protected $paginator;
    protected $env;
    /**
     * @var RepositoryService|null
     */
    protected $repositoryService;

    private $validatorService;

    public function __construct(KernelService $kernelService)
    {
        $this->kernel = $kernelService->getKernel();
        $this->serviceProvider = $this->kernel->getContainer();
        $this->env = $this->serviceProvider->get('dhi_blog_service.env_service');
        $this->request = $this->serviceProvider->get('dhi_blog_service.request_service_provider')->getRequest();
        $this->translator = $this->serviceProvider->get('dhi_blog_service.translator_provider_service')->getTranslator();
        $this->paginator = $this->serviceProvider->get('dhi_blog_service.paginator_service');
        $this->manager = $this->serviceProvider->get('dhi_blog_service.manager_service');
        $this->repositoryService = $this->serviceProvider->get('dhi_blog_service.repository_service');
        $this->validatorService = $this->serviceProvider->get('dhi_blog_service.validator_service');
        $this->response = new APIResponse();
    }

    /**
     * @return APIResponse|null
     */
    public function getResponse(): ?APIResponse
    {
        return $this->response;
    }

    protected function addData($data, $key = null)
    {
        $this->response->addData($data, $key);
    }

    protected function setData($data, $key = null)
    {
        $this->response->data($data, $key);
    }

    protected function setMessage($message)
    {
        $this->response->setMessage($message);
    }

    protected function normalizeDataValues()
    {
        $this->response->normalizeDataValues();
    }

    /**
     * @return JsonResponse
     */
    protected function created(): JsonResponse
    {
        return $this->response->created();
    }

    /**
     * @return JsonResponse
     */
    protected function success(): JsonResponse
    {
        return $this->response->success();
    }

    /**
     * @return JsonResponse
     */
    protected function accepted(): JsonResponse
    {
        return $this->response->accepted();
    }

    /**
     * @return JsonResponse
     */
    protected function badRequest(): JsonResponse
    {
        return $this->response->badRequest();
    }

    /**
     * @return JsonResponse
     */
    protected function notAuthenticated(): JsonResponse
    {
        return $this->response->notAuthenticated();
    }

    /**
     * @return JsonResponse
     */
    protected function forbidden(): JsonResponse
    {
        return $this->response->forbidden();
    }

    /**
     * @return JsonResponse
     */
    protected function notFound(): JsonResponse
    {
        return $this->response->notFound();
    }

    /**
     * @param bool|null $reset_data
     * @return JsonResponse
     */
    protected function conflict(?bool $reset_data = true): JsonResponse
    {
        return $this->response->conflict($reset_data);
    }

    /**
     * @param Throwable|null $e
     * @return JsonResponse
     */
    protected function exception(?Throwable $e = null): JsonResponse
    {
        return $this->response->badRequest();
    }

    protected function validate(CoreEntity $entity)
    {
        $this->validatorService->validate($entity);

        return $this;
    }

    /**
     * @param array|null $parameters
     * @return RedirectResponse
     */
    public final function redirectBack(?array $parameters = []):RedirectResponse
    {
        //TODO: fill parameters
        if ($this->request->headers->get('referer'))
            return $this->redirect($this->request->headers->get('referer'));

        return $this->redirectToRoute('home', $parameters);
    }

}
