<?php
/**
 * Date: 14/04/21
 * Time: 11:01
 */

namespace Dhi\BlogBundle\Services\Managers;


use Dhi\BlogBundle\Core\Data\ValuesRetrieverTrait;
use Dhi\BlogBundle\Core\Exceptions\Alert;
use Dhi\BlogBundle\Entity\Author;
use Dhi\BlogBundle\Services\DirectoryService;
use Dhi\BlogBundle\Services\FileUploadService;
use Dhi\BlogBundle\Services\RepositoryService;
use Dhi\BlogBundle\Services\RequestServiceProvider;
use Dhi\BlogBundle\Services\TranslatorProviderService;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Translation\TranslatorInterface;

class AuthorManagerService
{

    private $request;
    /**
     * @var RepositoryService
     */
    private $repositoryService;
    /**
     * @var TranslatorInterface
     */
    private $translator;
    /**
     * @var DirectoryService
     */
    private $directoryService;
    /**
     * @var FileUploadService
     */
    private $uploadService;

    use ValuesRetrieverTrait;

    /**
     * PaymentManagerService constructor.
     * @param RequestServiceProvider $requestServiceProvider
     * @param RepositoryService $repositoryService
     * @param TranslatorProviderService $translatorProviderService
     * @param DirectoryService $directoryService
     * @param FileUploadService $uploadService
     */
    public function __construct(RequestServiceProvider $requestServiceProvider,
                                RepositoryService $repositoryService,
                                TranslatorProviderService $translatorProviderService,
                                DirectoryService $directoryService,
                                FileUploadService $uploadService)
    {
        $this->request = $requestServiceProvider->getRequest();
        $this->repositoryService = $repositoryService;
        $this->translator = $translatorProviderService->getTranslator();
        $this->directoryService = $directoryService;
        $this->uploadService = $uploadService;
    }

    /**
     * @param Author|null $author
     * @return Author|null
     * @throws \Exception
     */
    public function buildAuthorFromRequest(?Author $author = null)
    {
        if (!$author) $author = new Author();

        $author_name = $this->request->get('author_name');
        $last_name = $this->request->get('last_name');
        $first_name = $this->request->get('first_name');
        $email = $this->request->get('email');
        $description = $this->request->get('description');
        $can_publish = $this->getBool($this->request->get('can_publish'));
        $is_admin = $this->getBool($this->request->get('is_admin'));

        $author
            ->setAuthorName($author_name)
            ->setDescription($description)
            ->setCanPublish($can_publish)
            ->setIsAdmin($is_admin)
            ->setLastName($last_name)
            ->setFirstName($first_name)
            ->setEmail($email)
        ;

        /**
         * @var UploadedFile $profile_picture
         */
        $profile_picture = $this->request->files->get('profile_picture');

        $directory = $this->directoryService->getPublicAuthorUploadDirectory();

        if ($profile_picture) {
            $profile_picture = $this->uploadService->storeUploadedFile(
                $profile_picture,
                $directory,
                $profile_picture->getClientOriginalName()
            );
        }

        $author->setProfilePicture($profile_picture);

        return $author;
    }

    /**
     * @param bool|null $strict
     * @return Author|null
     * @throws Alert
     */
    public function getAuthorFromRequest(?bool $strict = true): ?Author
    {

        $author_id = $this->request->get('author_id');

        /**
         * @var Author|null $author
         */
        $author = $this->repositoryService->getAuthorRepository()
            ->findByUniqKeyUnDeleted($author_id);

        if ($strict && !$author)
            throw new Alert(
                $this->translator->trans("Veuillez fournir un auteur valide")
            );

        return $author;
    }
}