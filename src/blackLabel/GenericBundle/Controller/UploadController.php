<?php

namespace blackLabel\GenericBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Class UploadController
 * @package blackLabel\GenericBundle\Controller
 */
class UploadController extends Controller
{
    /**
     * @param $uploadId
     * @param $typeFile
     * @param $extension
     * @param $typeAppel
     * @return BinaryFileResponse
     */
    public function readAction($uploadId, $typeFile, $extension, $typeAppel)
    {
        switch ($typeAppel) {
            case 0: $folder = 'client/logo/';
                break;
            default: $folder = '';
                break;
        }

        switch ($typeFile) {
            case 0: $type = '_logo.';
                break;
            default: $type = '';
                break;
        }

        $path = $this->get('kernel')->getRootDir(). "/../data/" . $folder;
        $file = $path . $uploadId . $type . $extension;
        $response = new BinaryFileResponse($file);

        return $response;
    }
}
