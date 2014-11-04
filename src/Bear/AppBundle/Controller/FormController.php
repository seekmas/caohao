<?php

namespace Bear\AppBundle\Controller;

use Bear\AppBundle\Entity\Info;
use Bear\AppBundle\Form\InfoType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class FormController extends Controller
{
    public function indexAction(Request $request)
    {

        //$request->getSession()->getFlashBag()->add('success' , '预约成功');

        $info = new Info();
        $form = $this->createForm(new InfoType(),$info);
        $form->handleRequest($request);
        if($form->isValid())
        {
            $info->setCreatedAt(new \Datetime());
            $em = $this->getDoctrine()->getManager();
            $em->persist($info);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success' , '预约成功');

            return $this->redirect($this->generateUrl('index'));
        }


        return $this->render('BearAppBundle:Form:index.html.twig',
            [
                'form' => $form->createView() ,
            ]
        );
    }

}
