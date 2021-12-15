<?php
/**
 * Date: 06/10/20
 * Time: 07:30
 */

namespace Dhi\BlogBundle\Services;

use Dhi\BlogBundle\Core\Exceptions\Alert;
use Dhi\BlogBundle\Utils\RandomStringGenerator;
use Hshn\Base64EncodedFile\HttpFoundation\File\Base64EncodedFile;
use Hshn\Base64EncodedFile\HttpFoundation\File\UploadedBase64EncodedFile;
use Intervention\Image\ImageManager;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploadService
{
    /**
     * @var EnvService
     */
    private $envService;

    private $translator;

    public function __construct(EnvService $envService, TranslatorProviderService $translatorProvider)
    {
        $this->envService = $envService;
        $this->translator = $translatorProvider->getTranslator();
    }

    public static $allowedFileFormat = [
        "jpg", "jpeg", "png", "pdf"
    ];

    private $max_file_size = 20480000;

    public function uploadSpreadSheet(?UploadedFile $file, string $dir)
    {
        self::$allowedFileFormat = ['xlsx', 'xls'];

        $this->max_file_size = 20480000;

        return $this->store_file($file, $dir, true);
    }

    private $characters = "abcdefghijklmnopqrstuvwxyz0123456789";

    /**
     * @param UploadedFile $uploadedFile
     * @param string|null $dir
     * @param string|null $name
     * @return string|null
     * @throws Alert
     * @throws \Dhi\BlogBundle\Exceptions\InvalidArgumentException
     */
    public function storeUploadedFile(UploadedFile $uploadedFile, ?string $dir = null, ?string $name = null)
    {

        $ext = "";

        if (!$name){
            $ext = $uploadedFile->getClientOriginalExtension();
            $name = $this->generateImageId();
        }

        if ($ext){
            $filename = $name.".".$ext;
        }else{
            $filename = $name;
        }

        $mega_size = $uploadedFile->getSize()/1000000;

        if ($mega_size > $this->envService->getParam('MAX_FILE_SIZE'))
            throw new Alert($this->translator->trans("Taille de fichier incorrect"));

        if (!is_dir($dir))
            return null;

        $uploadedFile->move($dir, $filename);

        return $filename;
    }

    /**
     * @param UploadedFile $uploadedFile
     * @param string|null $dir
     * @return string|null
     * @throws Alert
     * @throws \Dhi\BlogBundle\Exceptions\InvalidArgumentException
     */
    public function store_image(UploadedFile $uploadedFile, ?string $dir = null)
    {

        $ext = $uploadedFile->getClientOriginalExtension();

        if (!in_array($ext, array('jpg', 'png', 'jpeg')))
            return null;

        return $this->storeUploadedFile($uploadedFile, $dir);
    }

    /**
     * @param UploadedFile $uploadedFile
     * @param string|null $dir
     * @return string|null
     * @throws Alert
     * @throws \Dhi\BlogBundle\Exceptions\InvalidArgumentException
     */
    public function store_doc(UploadedFile $uploadedFile, ?string $dir = null)
    {

        $ext = $uploadedFile->getClientOriginalExtension();

        if (!in_array($ext, array('pdf', 'doc', 'docx')))
            return null;

        return $this->storeUploadedFile($uploadedFile, $dir);
    }

    protected function generateImageId()
    {
        $characters = "";

        while (strlen($characters) < 15) {
            $characters .= strtoupper(substr(str_shuffle($this->characters), 0, 6));
            $characters .= substr(str_shuffle($this->characters), 0, 6);
        }

        return $characters . time();
    }

    /**
     * @param string $base64
     * @param string $dir
     * @return string
     * @throws Alert
     */
    public function store_base64_image(?string $base64 = null, ?string $dir = null)
    {

        if (!$base64 || !$dir)
            return null;

        $name = null;

        $base64 = trim($base64, ' ');

        if ($base64 != null && preg_match("#^data:image#", $base64)) {

            $myFile = new UploadedBase64EncodedFile(new Base64EncodedFile($base64));

            $name = str_ireplace("Base64EncodedFile", time(), $myFile->getFilename());

            if (!is_dir($dir)) {
                throw new Alert("Répertoire invalide");
            }

            $myFile->move($dir, $name);
        }

        return $name;
    }

    /**
     * @param UploadedFile|null $file
     * @param string $dir
     * @param bool|null $throw_errors
     * @return string|string[]|null
     * @throws \Exception
     */
    public final function store_file(?UploadedFile $file, string $dir, ?bool $throw_errors = false)
    {
        if (!$file)
            return null;

        try{
            if (!is_dir($dir))
                mkdir($dir, 0777, true);

            if (!$file)
                return null;

            $uploadOk = 1;
            $documentFileType = strtolower($file->getClientOriginalExtension());
            $base_name = $this->generateName();
            $base_name .= ".".$documentFileType;
            $base_name = preg_replace("#[- ]#", '_', $base_name);
            $base_name = preg_replace("#[_ ]+#", '_', $base_name);

            // Check file size
            if ($file->getSize() > $this->max_file_size) {
                $uploadOk = 0;
                if ($throw_errors)
                    throw new \Exception("Taille de fichier invalide");
            }
            // Allow certain file formats
            if (!in_array($documentFileType, self::$allowedFileFormat)) {
                $uploadOk = 0;
            }
            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0)
                return null;

            if ($file->move($dir, $base_name)) {
                return $base_name;
            }

        }catch (\Exception $exception){
            if ($throw_errors)
                throw $exception;

            return null;
        }

        return null;
    }

    public final function resizeImage(?string $filename,
                                      ?int $width,
                                      ?int $height,
                                      ?string $from_directory,
                                      string $to_directory
    )
    {

        if (!$width || !$height || !$filename){
            return null;
        }

        try{
            if (!is_dir($to_directory)) {
                mkdir($to_directory, 0777, true);
            }

            $manager = new ImageManager();

            $image = $manager->make($from_directory ."/". $filename)
                ->fit($width, $height, function ($constraint) {
                    $constraint->upsize();
                })->interlace(true)
                ->save($to_directory ."/". $filename);

            $new_file = $image->filename . '.webp';

            $destination = $to_directory . '/' . $new_file;

            $this->convertImageToWebP($image->basePath(), $destination);

            $fileSystem = new Filesystem();

            $fileSystem->remove($image->basePath());

            return $new_file;

        }catch (\Exception $exception){
            return null;
        }
    }

    public final function convertImageToWebP(string $source, string $destination, int $quality = 80)
    {
        $image = null;

        $extension = pathinfo($source, PATHINFO_EXTENSION);

        if ($extension == 'jpeg' || $extension == 'jpg')
            $image = imagecreatefromjpeg($source);
        elseif ($extension == 'gif')
            $image = imagecreatefromgif($source);
        elseif ($extension == 'png')
            $image = imagecreatefrompng($source);

        return imagewebp($image, $destination, $quality);
    }

    private function generateName(): ?string
    {
        return (new RandomStringGenerator())->generate(20)."-".time();
    }
}