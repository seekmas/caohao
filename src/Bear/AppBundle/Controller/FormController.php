<?php

namespace Bear\AppBundle\Controller;

use Bear\AppBundle\Entity\Info;
use Bear\AppBundle\Entity\Owner;
use Bear\AppBundle\Form\InfoType;
use Bear\AppBundle\Form\OwnerType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Goutte\Client;
use Symfony\Component\HttpFoundation\Response;

class FormController extends Controller
{
    public function indexAction(Request $request , $name = 'caohao')
    {
        //$request->getSession()->getFlashBag()->add('success' , '预约成功');
        $info = new Info();
        $info->setOwner($name);
        $form = $this->createForm(new InfoType(),$info);
        $form->handleRequest($request);
        if($form->isValid())
        {

            $info->setCreatedAt(new \Datetime());
            $em = $this->getDoctrine()->getManager();
            $em->persist($info);
            $em->flush();

            $this->send($info);

            $request->getSession()->getFlashBag()->add('success' , '预约成功');
            return $this->redirect($this->generateUrl('index' , ['name' => $name]));
        }


        return $this->render('BearAppBundle:Form:index.html.twig',
            [
                'form' => $form->createView() ,
            ]
        );
    }

    public function createAction(Request $request)
    {
        $owner = new Owner();
        $form = $this->createForm(new OwnerType(),$owner);
        $form->handleRequest($request);
        if($form->isValid())
        {
            $data = $form->getData();

            if($this->get('owner')->findOneByName($data->getName()))
            {
                return new Response('这个名字已经被注册了');
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($owner);
            $em->flush();
        }

        return $this->render('BearAppBundle:Form:create.html.twig' ,
            [
                'form' => $form->createView() ,
            ]
        );
    }

    private function goutte(Info $info)
    {
    //        $client = new Client();
    //        $crawler = $client->request('GET', 'http://58.247.97.18/');
    //        $client->getClient()->setDefaultOption('config/curl/'.CURLOPT_TIMEOUT, 6000);
    //        //$client->getClient()->setDefaultOption('config/curl/'.CURLOPT_HTTPHEADER, 'Accept: text/plain');
    //
    //
    //        $form = $crawler->selectButton('预 约')->form();
    //
    //        $crawler = $client->submit($form,
    //            array(
    //                'ctl00$ContentPlaceHolder1$txt_mobile' => $info->getPhone() ,
    //                'ctl00$ContentPlaceHolder1$startdate' => $info->getDate()->format('Y-m-d') ,
    //                'ctl00$ContentPlaceHolder1$dp_time' => $info->getTime(),
    //                'ctl00$ContentPlaceHolder1$txt_name' => $info->getName(),
    //                'ctl00$ContentPlaceHolder1$Radio_Sex' => $info->getGender() ? '男' : '女',
    //                'ctl00$ContentPlaceHolder1$txt_age' => $info->getAge() ,
    //                'ctl00$ContentPlaceHolder1$txt_email' => '曹浩'.$info->getEmail()
    //            )
    //        );
    //
    //        $crawler->filter('form')->each(function ($node) {
    //            print $node->text()."\n";
    //        });
    //
    //        exit;
    }

    public function listAction($name)
    {
        $orders = $this->get('info');
        $list = $orders->findByOwner($name);

        return $this->render('BearAppBundle:Form:list.html.twig' ,
            [
                'list' => $list ,
                'name' => $name ,
            ]
        );
    }

    public function send($data)
    {
        $owner = $this->get('owner')->findOneByName($data->getOwner());

        $message = \Swift_Message::newInstance()
            ->setSubject('有人预约了')
            ->setFrom('594137631@qq.com')
            ->setTo($owner->getEmail())
            ->setBody(
                '名字'.$data->getName().' 年龄'.$data->getAge().' 手机'.$data->getPhone().' 性别'.($data->getGender() ? '男' : '女').' 邮箱: '.$data->getEmail().' 请登录 http://form.jingqi100.cn/'.$data->getOwner().'/list 查询'
            )
        ;

        $this->get('mailer')->send($message);

    }
}
