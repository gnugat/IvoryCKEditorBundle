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
        $imageConstraint = new Image();

        /* @var $validator \Symfony\Component\Validator\Validator */
        $validator = $this->container->get('validator');
        $errors = $validator->validateValue($uploadedFile, $imageConstraint);

        if (count($errors) == 0) {
            $webUploadDir = '/uploads/ckeditor';
            $absoluteUploadDir = $this->container->get('kernel')->getRootDir().'/../web'.$webUploadDir;

            $pictureName = uniqid('', true) . '.' . $uploadedFile->guessExtension();
            $uploadedFile->move($absoluteUploadDir, $pictureName);

            $link = $webUploadDir . '/' . $pictureName;
        } else {
            $link = null;
        }

        /* @var $templating \Symfony\Bundle\TwigBundle\Debug\TimedTwigEngine */
        $templating = $this->container->get('templating');

        return $templating->renderResponse('IvoryCKEditorBundle:Image:upload.html.twig', array(
            'errors' => $errors,
            'link' => $link
        ));
    }
}
