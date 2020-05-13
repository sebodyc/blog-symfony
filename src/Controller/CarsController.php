<?php

namespace App\Controller;

use App\Entity\Cars;
use App\Form\CarsType;
use App\Repository\CarsRepository;
use Doctrine\DBAL\Types\BooleanType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class CarsController extends AbstractController
{


    /**
     * @Route("/cars", name="cars")
     */
    public function index(CarsRepository $carsRepository)
    {

        $cars = $carsRepository->findBy([], ['id' => 'DESC']);


        return $this->render('cars/index.html.twig', [
            'cars' => $cars,




        ]);
    }


    /**
     * @Route("/cars/show/{id<\d+>}", name="cars_show")
     */
    public function ShowCar(Cars $cars)
    {



        return $this->render('cars/showCar.html.twig', [
            'car' => $cars,


        ]);
    }

    /**
     * @Route("cars/admin/create", name="cars_create")
     */
    public function create(Request $request, EntityManagerInterface $em)
    {

        //creation du formulaire via la classe carstype
        $form = $this->createForm(CarsType::class);
        //annalyse de la requette
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            /**@var \App\Entity\Cars */

            $cars = $form->getData();
            $em->persist($cars);
            $em->flush();

            return  $this->redirectToRoute('cars_show', ['id' => $cars->getId()]);
        }
        return $this->render('cars/createCars.html.twig', [
            'carsForm' => $form->createView()

        ]);
    }


    /**
     * @Route("cars/admin/edit/{id}", name="cars_edit")
     */
    public function editCars($id, CarsRepository $carsRepository, EntityManagerInterface $em, Request $request, Cars $cart)
    {
        $cars = $carsRepository->find($id);

        $form = $this->createForm(CarsType::class, $cars);
        $form->handleRequest($request);


        if ($form->isSubmitted()) {

            if ($form->get('delete')->isClicked()) {
                $delete = $em->getReference(Cars::class, $id);
                $em->remove($delete);
                $em->flush();
                return $this->redirectToRoute('cars');
            }


            $em->flush();
            return $this->redirectToRoute('cars_show', ['id' => $cars->getId()]);
        }


        return $this->render('cars/editCars.html.twig', [
            'carsForm' => $form->createView()

        ]);
    }

    /**
     * @Route("cars/admin/delete/{id}", name="cars_delete")
     */

    public function delete(Cars $cars, EntityManagerInterface $em)
    {

        $em->remove($cars);
        // dd($post);
        $em->flush();
        return $this->redirectToRoute('cars');
    }
}
