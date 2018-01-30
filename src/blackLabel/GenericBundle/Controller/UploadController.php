<?php

namespace blackLabel\GenericBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class UploadController
 * @package blackLabel\GenericBundle\Controller
 */
class UploadController extends Controller
{
    /**
     * @param $clientId
     * @param $uploadId
     * @param $fileKey
     * @param $extension
     * @param $folderKey
     * @return BinaryFileResponse
     */
    public function readAction($clientId, $uploadId, $fileKey, $extension, $folderKey)
    {
        switch ($fileKey) {
            case 0: $type = '_logo.';
                break;
            case 1: $type = '_modele_lettre.';
                break;
            case 2: $type = '_import_error.';
                break;
            default: $type = '';
                break;
        }

        switch ($folderKey) {
            case 0: $folder = 'logo/';
                break;
            case 1: $folder = 'modeleLettre/';
                break;
            case 2: $folder = 'import/error/';
                break;
            default: $folder = '';
                break;
        }

        $path = $this->get('kernel')->getRootDir(). "/../data/" . $clientId . '/' . $folder;
        $file = $path . $uploadId . $type . $extension;
        $response = new BinaryFileResponse($file);

        return $response;
    }
}
