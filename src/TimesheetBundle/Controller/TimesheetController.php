<?php

/*
 * This file is part of the Kimai package.
 *
 * (c) Kevin Papst <kevin@kevinpapst.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TimesheetBundle\Controller;

use TimesheetBundle\Entity\Timesheet;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;

/**
 * Controller used to manage timesheet contents in the public part of the site.
 *
 * @Route("/timesheet")
 * @Security("has_role('ROLE_USER')")
 *
 * @author Kevin Papst <kevin@kevinpapst.de>
 */
class TimesheetController extends Controller
{
    /**
     * @Route("/", defaults={"page": 1}, name="timesheet")
     * @Route("/page/{page}", requirements={"page": "[1-9]\d*"}, name="timesheet_paginated")
     * @Method("GET")
     * @Cache(smaxage="10")
     */
    public function indexAction($page)
    {
        $user = $this->getUser();
        /* @var $entries Pagerfanta */
        $entries = $this->getDoctrine()->getRepository(Timesheet::class)->findLatest($user, $page);

        return $this->render('TimesheetBundle:timesheet:index.html.twig', ['entries' => $entries]);
    }

    public function statusEntryAction()
    {
        $user = $this->getUser();
        $activeEntry = $this->getDoctrine()->getRepository(Timesheet::class)->getActiveEntry($user);

        $activeEntry = null;
        return $this->render(
            'TimesheetBundle:Sidebar:navbar-panel.html.twig',
            ['entry' => $activeEntry]
        );
    }
}
