<?php
namespace Wiki\IndexBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

 /** @PreAuthorize("hasRole('ROLE_SUPER_ADMIN')") */
class AjaxController extends Controller
{
}
