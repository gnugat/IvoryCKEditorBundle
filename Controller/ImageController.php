<?php

namespace Ivory\CKEditorBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Constraints\Image;

/**
 * Manages CKEditor image.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class ImageController extends ContainerAware
{
    /**
     * Uploads a picture.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function uploadAction()
    {
        /* @var $request \Symfony\Component\HttpFoundation\Request */
        $request = $request = $this->container->get('request');

        if (!$request->files->has('upload'))
            throw new NotFoundHttpException();

        /* @var $uploadedFile \Symfony\Component\HttpFoundation\File\UploadedFile */
        $uploadedFile = $request->files->get('upload');

        $webUploadDir = '/uploads/ckeditor';
        $absoluteUploadDir = $this->container->get('kernel')->getRootDir().'/../web'.$webUploadDir;

        $pictureName = uniqid('', true) . '.' . $uploadedFile->guessExtension();
        $uploadedFile->move($absoluteUploadDir, $pictureName);

        $link = $webUploadDir . '/' . $pictureName;

        /* @var $templating \Symfony\Bundle\TwigBundle\Debug\TimedTwigEngine */
        $templating = $this->container->get('templating');

        return $templating->renderResponse('IvoryCKEditorBundle:Image:upload.html.twig', array(
            'link' => $link
        ));
    }
}
