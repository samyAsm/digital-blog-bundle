<?php
/**
 * Date: 15/03/21
 * Time: 09:29
 */

namespace Dhi\BlogBundle\Core\Entity;

use Dhi\BlogBundle\Annotations\MethodDescription;
use Dhi\BlogBundle\Annotations\PropertyDescription;
use Dhi\BlogBundle\Core\Data\SerializableInterface;
use Doctrine\ORM\Mapping as ORM;


abstract class CoreEntity implements SerializableInterface
{
    /**
     * @PropertyDescription(
     *     name="id",
     *     type="int",
     *     description="Identifiant unique généré par le SGBD",
     *     required=true,
     * )
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @PropertyDescription(
     *     name="uniq_key",
     *     type="string",
     *     description="Clé unique générée par le système (Utilisée pour des raisons de sécurité)",
     *     size="10",
     *     unique=true,
     *     required=true,
     * )
     * @ORM\Column(type="string", length=10, unique=true, nullable=false)
     */
    protected $uniq_key;

    /**
     * @PropertyDescription(
     *     name="created_at",
     *     type="datetime",
     *     description="Date de création",
     *     required=true,
     * )
     * @ORM\Column(type="datetime")
     */
    protected $created_at;

    /**
     * @PropertyDescription(
     *     name="updated_at",
     *     type="datetime",
     *     description="Date de dernière modification par l'utilisateur",
     *     required=true,
     * )
     * @ORM\Column(type="datetime")
     */
    protected $updated_at;

    /**
     * @PropertyDescription(
     *     name="changed_at",
     *     type="datetime",
     *     description="Date de dernière mise à jour effectuée par le système",
     *     required=false,
     * )
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $changed_at;

    /**
     * @PropertyDescription(
     *     name="deleted_at",
     *     type="datetime",
     *     description="Date de supression",
     * )
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $deleted_at;

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        if (!$this->created_at)
            $this->created_at = new \DateTime();

        if (!$this->updated_at)
            $this->updated_at = new \DateTime();

        if (is_null($this->deleted_at))
            $this->deleted_at = null;

        $this->generateUniqKey();

    }

    /**
     * @MethodDescription(
     *     name="generateUniqKey",
     *     description="Generateur de clé système unique",
     *     return="void"
     * )
     * @param bool|null $force
     */
    public function generateUniqKey(?bool $force = false)
    {
        if (!$this->uniq_key && !$force)
            $this->uniq_key = self::v4();
        elseif ($force)
            $this->uniq_key = self::v4();
    }

    /**
     *
     * Generate v4 UUID
     *
     * Version 4 UUIDs are pseudo-random.
     */
    public static function v4()
    {
        return sprintf('%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

    /**
     * @throws \Exception
     */
    public function touch()
    {
        $this->updated_at = new \DateTime();
        return $this->hasChanged();
    }

    /**
     * @throws \Exception
     */
    public function hasChanged()
    {
        $this->changed_at = new \DateTime();
        return $this;
    }

    public function isDeleted(): ?bool
    {
        return $this->deleted_at !== null;
    }

    /**
     * @throws \Exception
     */
    public function delete()
    {
        $this->deleted_at = new \DateTime();

        return $this;
    }

    public function restore()
    {
        $this->deleted_at = null;
    }

    public abstract function serialize();

    public function serializeDefault()
    {
        return array(
            'bd_id' => $this->getId(),
            'id' => $this->getUniqKey(),
            'created_at' => $this->fullyFormatDate($this->getCreatedAt()),
            'updated_at' => $this->fullyFormatDate($this->getUpdatedAt()),
            'deleted_at' => $this->fullyFormatDate($this->getDeletedAt()),
        );
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isPersisted()
    {
        return $this->id !== null;
    }

    /**
     * @return mixed
     */
    public function getUniqKey()
    {
        return $this->uniq_key;
    }

    public final function fullyFormatDate(?\DateTimeInterface $dateTime = null): ?string
    {
        if ($dateTime)
            return $dateTime->format('Y-m-d H:i');

        return "";
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function getDeletedAt(): ?\DateTimeInterface
    {
        return $this->deleted_at;
    }

    public final function formatDate(?\DateTimeInterface $dateTime = null): ?string
    {
        if (!$dateTime)
            return "";

        return $dateTime->format('Y-m-d');
    }

    public final function formatDateInTime(?\DateTimeInterface $dateTime = null): ?string
    {
        try {
            if (!$dateTime)
                return "";

            return $dateTime->format('H:i');
        } catch (\Exception $e) {
            return null;
        }
    }

    protected function getImageWithoutSlashDirectory(? string $image_file = null)
    {
        if (!$image_file)
            return $image_file;

        $path = explode('/', $image_file);

        return array_pop($path);
    }
}