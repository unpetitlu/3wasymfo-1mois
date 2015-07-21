<?php

namespace Troiswa\BackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Image
 *
 * @ORM\Table(name="image")
 * @ORM\Entity(repositoryClass="Troiswa\BackBundle\Repository\ImageRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Image
{
    private $thumbnails =
        [
            'thumb-small' => [350,160]
        ];

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255)
     */
    private $path;


    /**
     * @var string
     *
     * @ORM\Column(name="alt", type="string", length=255)
     */
    private $alt;

    /**
     * @Assert\Image(
     *     mimeTypes = {"image/gif", "image/jpeg", "image/png"},
     *     mimeTypesMessage = "Choisissez un fichier image valide"
     * )
     */
    private $file;

    private $temp;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return Image
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }

    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
        // check if we have an old image path
        if (isset($this->path))
        {
            $this->temp = $this->path;
            /*
             * Si on modifie uniquement l'image et pas le alt par exemple
             * je crée une modification fictive
            */
            $this->path = null;
        }

        return $this;
    }

    public function getFile()
    {
        return $this->file;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->getFile()) {
            // generate a unique name
            $filename = sha1(uniqid(mt_rand(), true));
            $this->path = $filename.'.'.$this->getFile()->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->getFile()) {
            return;
        }

        // Upload picture
        $this->getFile()->move($this->getUploadRootDir(), $this->path);

        // create thumbnails
        $imagine = new \Imagine\Gd\Imagine();
        $imagineOpen = $imagine->open($this->getAbsolutePath());
        foreach($this->thumbnails as $key => $thumb)
        {
            $imagineOpen->thumbnail(new \Imagine\Image\Box($thumb[0], $thumb[1]))
                ->save(
                    $this->getUploadRootDir() . '/'.$key.'-' . $this->path
                );
        }



        // check if we have an old image
        if (isset($this->temp))
        {
            // delete the old image
            if (file_exists($this->getUploadRootDir().'/'.$this->temp))
            {
                unlink($this->getUploadRootDir() . '/' . $this->temp);
            }

            foreach($this->thumbnails as $key => $thumb)
            {
                if (file_exists($this->getUploadRootDir() . '/'.$key.'-' . $this->temp))
                {
                    unlink($this->getUploadRootDir() . '/'.$key.'-' . $this->temp);
                }
            }
            // clear the temp image path
            $this->temp = null;
        }

        // clean up the file property as you won't need it anymore
        $this->file = null;
    }

    /**
     * @ORM\PreRemove()
     */
    public function preRemoveUpload()
    {
        // traitement avant remove
        // permet également de récupérer l'entité proxy
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        $file = $this->getAbsolutePath();
        if (file_exists($file))
        {
            unlink($file);
        }

        // remove thumbnails
        foreach($this->thumbnails as $key => $thumb)
        {
            $fileThumb = $this->getUploadRootDir().'/'.$key.'-'.$this->path;
            if (file_exists($fileThumb))
            {
                unlink($fileThumb);
            }
        }

    }

    public function getAbsolutePath()
    {
        return null === $this->path
            ? null
            : $this->getUploadRootDir().'/'.$this->path;
    }

    public function getWebPath($size = null)
    {
        if (array_key_exists($size, $this->thumbnails))
        {
            return null === $this->path
                ? null
                : $this->getUploadDir().'/'.$size.'-'.$this->path;
        }
        else
        {
            return null === $this->path
                ? null
                : $this->getUploadDir().'/'.$this->path;
        }
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/products';
    }

    /**
     * Set alt
     *
     * @param string $alt
     * @return Image
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;

        return $this;
    }

    /**
     * Get alt
     *
     * @return string 
     */
    public function getAlt()
    {
        return $this->alt;
    }
}
