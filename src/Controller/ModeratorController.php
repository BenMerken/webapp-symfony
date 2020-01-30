<?php

namespace App\Controller;

use App\Entity\Asset;
use App\Entity\Complaint;
use App\Entity\Room;
use App\Form\AssetCreationFormType;
use App\Form\ComplaintRegistrationFormType;
use App\Service\ComplaintService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_MOD")
 */
class ModeratorController extends AbstractController
{
    /**
     * @Route("/moderator", name="moderator_dashboard")
     */
    public function index(ComplaintService $complaintService)
    {
        $complaints = $complaintService
            ->getComplaintsForUserEmail($this->get('security.token_storage')->getToken()->getUser()->getEmail());
        $rooms = $this->getDoctrine()->getRepository(Room::class)->findAll();

        return $this->render('moderator/index.html.twig', [
            'controller_name' => 'ModeratorController',
            'complaints' => $complaints,
            'rooms' => $rooms
        ]);
    }

    /**
     * @Route("moderator/register_complaint", name="register_complaint")
     */
    public function registerComplaint(Request $request)
    {
        $complaint = new Complaint();
        $form = $this->createForm(ComplaintRegistrationFormType::class, $complaint);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->get('user')->getData();

            $complaint->setUser($user);
            $complaint->setReason($form->get('reason')->getData());

            $entitiyManager = $this->getDoctrine()
                ->getManager();

            $entitiyManager->persist($complaint);
            $entitiyManager->flush();

            $message = 'Your complaint for user ' . $user->getEmail() . ' was successfully registered.';
            $this->addFlash('success', $message);

            return $this->redirectToRoute('moderator_dashboard', [
                'created_complaint' => $complaint,
            ]);
        }

        return $this->render('moderator/register_complaint.html.twig', [
            'register_form' => $form->createView(),
        ]);
    }

    /**
     * @Route("moderator/create/{roomId}", name="create_asset")
     */
    public function createAsset(Request $request, $roomId)
    {
        $asset = new Asset();
        $room = $this->getDoctrine()->getRepository(Room::class)->findOneBy(['id' => $roomId]);
        $form = $this->createForm(AssetCreationFormType::class, $asset);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $asset->setRoom($room);
            $asset->setName($form->get('name')->getData());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($asset);
            $entityManager->flush();

            $this->addFlash('success', 'Asset ' . $form->get('name')->getData() . ' for room ' . $room->getName() . ' created.');

            return $this->redirectToRoute('moderator_dashboard', []);
        }

        return $this->render('moderator/create_asset.html.twig', [
            'asset_form' => $form->createView(),
            'room_name' => $room->getName()
        ]);
    }


    /**
     * @Route("moderator/delete/{assetId}", name="delete_asset")
     */
    public function deleteAsset($assetId)
    {
        $assetRepository = $this->getDoctrine()->getRepository(Asset::class);
        $asset = $assetRepository->find($assetId);
        if ($asset) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($asset);
            $entityManager->flush();
            $this->addFlash('success', 'Asset ' . $asset->getName() . ' successfully deleted.');
        }

        return $this->redirectToRoute('moderator_dashboard');
    }
}
