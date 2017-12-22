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
        switch ($fileKey) {
            case 0: $type = '_logo.';
                break;
            case 1: $type = '_lettre_cheque.';
                break;
            case 2: $type = '_import_error.';
                break;
            default: $type = '';
                break;
        }

        switch ($folderKey) {
            case 0: $folder = 'client/logo/';
                break;
            case 1: $folder = 'client/lettreCheque/';
                break;
            case 2: $folder = 'import/error/';
                break;
            default: $folder = '';
                break;
        }

        if ('txt' == $extension) $extension = 'pdf';

        $path = $this->get('kernel')->getRootDir(). "/../data/" . $folder;
        $file = $path . $uploadId . $type . $extension;
        $response = new BinaryFileResponse($file);

        return $response;
    }
}
