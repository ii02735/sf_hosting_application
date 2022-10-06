<?php

namespace App\Controller;

use App\Form\OrderServerType;
use App\Model\OrderServerModel;
use App\Services\OrderManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    /**
     * @Route("/order", name="order")
     */
    public function serverOrder(Request $request): Response
    {
        $model = new OrderServerModel();
        $form = $this->createForm(OrderServerType::class, $model);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            dd($form->getData());
        }

        return $this->render('order/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
