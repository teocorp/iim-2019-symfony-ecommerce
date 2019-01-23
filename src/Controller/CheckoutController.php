<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\AddressType;
use App\Form\CardType;
use App\Model\Card;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/checkout")
 */
class CheckoutController extends AbstractController
{
    /**
     * @param UserInterface $user
     * @param Request $request
     * @Route("/address", name="checkout_address", methods={"GET", "POST"})
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deliveryAddress(UserInterface $user, Request $request)
    {
        $address = new Address();
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $address->setUser($user);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($address);
            $entityManager->flush();
        }

        $repositoryAddress = $this->getDoctrine()->getRepository(Address::class);
        $userAddresses = $repositoryAddress->findBy([
            'user' => $user
        ]);

        return $this->render('checkout/address.html.twig', [
            'form' => $form->createView(),
            'myAddresses' => $userAddresses
        ]);
    }

    /**
     * @param Request $request
     * @Route("/payment", name="checkout_payment", methods={"GET", "POST"})
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function payment(Request $request)
    {
        $card = new Card();
        $form = $this->createForm(CardType::class, $card);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // TODO: Process Payment
            dd($card);
        }

        return $this->render('checkout/payment.html.twig', [
            'card' => $card,
            'form' => $form->createView(),
        ]);
    }
}
