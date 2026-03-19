<?php

namespace App\Controller;

use App\DBAL\EstablishmentType;
use App\Entity\Doctor;
use App\Entity\Establishment;
use App\Entity\Laboratory;
use App\Entity\Pharmacy;
use App\Entity\User;
use App\Form\AddEstablishementType;
use App\Repository\DoctorRepository;
use App\Repository\PharmacyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

final class EstablishementController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $entity_manager)
    {
        
    }
    #[Route('/establishement/add', name: 'add_establishement')]
    public function index(Request $request): Response
    {
        $add_establishement_form = $this->createForm(AddEstablishementType::class);
        $add_establishement_form->handleRequest($request);
        if (
            $add_establishement_form->has('save') &&
            $add_establishement_form->get('save')->isClicked() &&
            $add_establishement_form->isValid()
        ) {
            $establishement_data = $add_establishement_form->getData();
            $business = $establishement_data['business'];
            /**
             * @var User $handler
             */
            $handler = $this->getUser();
            $this->entity_manager->beginTransaction();
            try {
                $establishement = new Establishment();
                $type = $establishement_data['type'] ?? EstablishmentType::OTHER;
                if ($type === EstablishmentType::OTHER) {
                    $establishement->setCustomAddress($business['address'])
                    ->setCustomType($business['custom_type'])
                    ->setCustomCity($business['city'])
                    ->setName($business['name'])
                    ->setDescription($business['description'] ?? null)
                    ->setCustomPhone($business['phone']);
                } else {
                    $this->entity_manager->persist($business);
                    $establishement->setTarget($business->getId());
                }
                $establishement->setType($type);
                $establishement->addHandler($handler);
                $handler->addEstablishment($establishement);
                $this->entity_manager->persist($handler);
                $this->entity_manager->persist($establishement);
                $this->entity_manager->flush();
                $this->entity_manager->commit();
                if ($handler->getEstablishments()->count() > 0) {
                    return $this->redirectToRoute($type . '_establishement');
                }
            } catch (\Exception $e) {
                $this->addFlash('error', 'Error');
                throw $e;
            }
        }
        return $this->render('establishement/add.html.twig', [
            'form' => $add_establishement_form,
        ]);
    }

    #[Route('/establishement/home', name: 'my_establishement')]
    public function goToMyEstablishement(Request $request): Response
    {
        /**
         * @var User $user;
         */
        $user = $this->getUser();
        if ($user->getEstablishments()->count() === 0) {
            throw new AccessDeniedException('No establishement found forr you');
        }
        $type = $user->getEstablishments()->get(0)?->getType() ?? '';
        return $this->redirectToRoute($type . '_establishement');
    }

    #[Route('/cabinet', name: 'doctor_establishement')]
    public function doctorEstablishement(Request $request, DoctorRepository $doctorRepository): Response
    {
        $doctor = $this->getEstablishementData(EstablishmentType::DOCTOR);
        if (!$doctor) {
            throw new Exception('Your doctor data is lost');
        }
        return $this->render('establishement/my_doctor_profile.html.twig', ['doctor' => $doctor]);
    }
    
    #[Route('/mypharmacy', name: 'pharmacy_establishement')]
    public function pharmacyEstablishement(Request $request, PharmacyRepository $pharmacyRepository): Response
    {
        $pharmacy = $this->getEstablishementData(EstablishmentType::PHARMACY);
        if (!$pharmacy) {
            throw new Exception('Your pharmacy data is lost');
        }
        return $this->render('establishement/my_pharmacy_profile.html.twig', ['pharmacy' => $pharmacy]);
    }

    #[Route('/mylaboratory', name: 'laboratory_establishement')]
    public function laboratoryEstablishement(Request $request): Response
    {
        $laboratory = $this->getEstablishementData(EstablishmentType::LABORATORY);
        if (!$laboratory) {
            throw new Exception('Your laboratory data is lost');
        }
        return $this->render('establishement/my_laboratory_profile.html.twig', ['laboratory' => $laboratory]);
    }

    #[Route('/myestablishement', name: 'other_establishement')]
    public function otherEstablishement(Request $request): Response
    {
        $myestablishement = $this->getEstablishementData(EstablishmentType::OTHER);
        if (!$myestablishement) {
            throw new Exception('Your establishement data is lost');
        }
        return $this->render('establishement/my_establishement_profile.html.twig', ['establishement' => $myestablishement]);
    }

    private function getEstablishementData(string $establishmentType): mixed {
        /**
         * @var User $handler
         */
        $handler = $this->getUser();
        /**
         * @var Establishment $establishement
         */
        $establishements = $handler->getEstablishments()->filter(fn(Establishment $est) => $est->getType() === $establishmentType);
        if (!$establishements->count()) {
            throw new Exception('Your establishement data is empty');
        }
        $establishement = $establishements->get(0);
        $establishement_id = $establishement->getTarget();
        $class = match($establishmentType) {
            EstablishmentType::DOCTOR => Doctor::class,
            EstablishmentType::LABORATORY => Laboratory::class,
            EstablishmentType::PHARMACY => Pharmacy::class,
            default => Establishment::class
        };
        if ($class === Establishment::class) {
            return $establishement;
        }
        $establishement_data = $this->entity_manager->getRepository($class)->find($establishement_id);
        return $establishement_data;
    }
}
