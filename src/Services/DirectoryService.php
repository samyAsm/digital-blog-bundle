<?php

namespace DhiBlogBundle\Services;

class DirectoryService
{

    /**
     * @var KernelService
     */
    private $kernelService;

    private $configs;

    public function __construct(KernelService $kernelService)
    {
        $this->kernelService = $kernelService;

        try{
            $this->configs = $this->kernelService->getContainer()
                ->getParameter('blog_configurations')[0]['store'];

        }catch (\Throwable $e){}

    }

    /**
     * @param string $dir
     * @return bool|string
     */
    protected function createDirectory(string $dir)
    {
        try {
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }
            return $dir;
        } catch (\Exception $exception) {
            return false;
        }
    }

    public final function getPublicDirectory()
    {
        return $this->createDirectory(__DIR__ . '/../../public/');
    }

    public final function getPublicArticleUploadDirectory()
    {
        return $this->createDirectory($this->getProjectPublicDir().$this->configs['article_image_store']);
    }

    public final function getPublicCategoryUploadDirectory()
    {
        return $this->createDirectory($this->getProjectPublicDir().$this->configs['category_image_store']);
    }
    
    public final function getPublicAuthorUploadDirectory()
    {
        return $this->createDirectory($this->getProjectPublicDir().$this->configs['author_image_store']);
    }

    /**
     * @return string
     */
    protected function getProjectPublicDir(): string
    {
        return $this->kernelService->getKernel()->getProjectDir(). '/public/';
    }

}