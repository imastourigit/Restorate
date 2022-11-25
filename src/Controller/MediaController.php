<?php
namespace App\Controller;

use App\Entity\Media;
use App\Repository\CityRepository;
use App\Repository\MediaRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MediaController extends AbstractController
{


    // AJAX MEDIA DELETE
    #[Route('delete/media/{id}', name: 'app_media_delete', methods: ['DELETE']),IsGranted('ROLE_RESTAURANT')]
    public function delete_media(Media $media,Request $request,MediaRepository $mediaRepository)
    {
        $data = json_decode($request->getContent(), true);
            //token check
        if($this->isCsrfTokenValid('delete'.$media->getId(), $data['_token'])){
           
            $filename = $media->getFilename();
            // File delete
            unlink($this->getParameter('media_directory').'/'.$filename);

            // data delete
            $mediaRepository->remove($media, true);

        return new JsonResponse(['success'=>1]);
        }else{
        return new JsonResponse(['error' => 'Token Invalide'], 400);
         }
    }

}