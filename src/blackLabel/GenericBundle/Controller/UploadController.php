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
     * @param $fileKey
     * @param $extension
     * @param $folderKey
     * @return BinaryFileResponse
     */
    public function readAction($uploadId, $fileKey, $extension, $folderKey)
    {
        switch ($folderKey) {
            case 0: $folder = 'client/logo/';
                break;
            case 1: $folder = 'client/lettreCheque/';
                break;
            default: $folder = '';
                break;
        }

        switch ($fileKey) {
            case 0: $type = '_logo.';
                break;
            case 1: $type = '_lettre_cheque.';
                break;
            default: $type = '';
                break;
        }

        if ('txt' == $extension) $extension = 'pdf';

        $path = $this->get('kernel')->getRootDir(). "/../data/" . $folder;
        $file = $path . $uploadId . $type . $extension;
        $response = new BinaryFileResponse($file);

        return $response;
    }
}
